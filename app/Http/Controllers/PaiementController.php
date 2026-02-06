<?php
// app/Http/Controllers/PaiementController.php

namespace App\Http\Controllers;

use App\Services\PayTechService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Commande;

class PaiementController extends Controller
{
    protected $paytech;

    public function __construct(PayTechService $paytech)
    {
        $this->paytech = $paytech;
    }

    /**
     * 🔹 Initiation du paiement via GET
     */
    public function process(Request $request)
    {
        try {
            $user = Auth::user();
            
            Log::info('🚀 Processus PayTech démarré', [
                'user_id' => $user->id,
                'user_email' => $user->email
            ]);

            // Récupérer les données depuis les paramètres GET
            $panierData = $request->query('panier');
            $total = $request->query('total');

            Log::info('📦 Données reçues', [
                'has_panier' => !empty($panierData),
                'total' => $total
            ]);

            if (!$panierData || !$total) {
                Log::error('❌ Données manquantes pour PayTech');
                return redirect()->route('panier')->with('error', 'Données de commande manquantes');
            }

            // Décoder le panier
            $panier = json_decode($panierData, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('❌ Erreur de décodage JSON du panier');
                return redirect()->route('panier')->with('error', 'Format de données invalide');
            }

            Log::info('🛒 Panier décodé', [
                'nombre_articles' => count($panier)
            ]);

            // Préparer la description
            $itemNames = array_map(function($item) {
                return $item['name'] . ' (x' . $item['quantity'] . ')';
            }, $panier);
            
            $description = implode(', ', $itemNames);

            // Préparer les données pour PayTech
            $paymentData = [
                'item_name' => $description,
                'amount' => $total,
                'reference' => 'CMD_' . time() . '_' . $user->id,
                'description' => 'Commande en ligne FUBAD',
                'commande_id' => 0, // La commande sera créée après paiement
                'user_id' => $user->id,
                'panier' => $panier
            ];

            // Utiliser le service PayTech
            $response = $this->paytech->initiatePayment($paymentData);

            if (isset($response['success']) && $response['success'] == 1 && isset($response['redirect_url'])) {
                Log::info('🔗 Redirection vers PayTech', [
                    'url' => $response['redirect_url']
                ]);
                return redirect()->away($response['redirect_url']);
            }

            Log::error('❌ Échec PayTech', ['response' => $response]);
            return redirect()->route('panier')->with('error', 'Erreur PayTech: ' . ($response['error'] ?? 'Erreur inconnue'));

        } catch (\Exception $e) {
            Log::error('❌ Erreur dans process PayTech: ' . $e->getMessage());
            return redirect()->route('panier')->with('error', 'Erreur lors du traitement: ' . $e->getMessage());
        }
    }

    /**
     * ✅ Page succès PayTech
     */
    public function success(Request $request)
    {
        Log::info('✅ Paiement réussi - Page success', $request->all());
        
        // Vider le panier après paiement réussi
        try {
            if (Auth::check()) {
                // Si vous stockez le panier en session ou base de données
                session()->forget('panier');
                
                // Ou vider le localStorage via JavaScript
            }
        } catch (\Exception $e) {
            Log::error('Erreur lors du vidage du panier: ' . $e->getMessage());
        }

        return view('paiement.success', [
            'reference' => $request->get('ref_command', 'N/A'),
            'message' => 'Votre paiement a été traité avec succès!',
            'transaction_id' => $request->get('transaction_id', ''),
            'amount' => $request->get('item_price', '')
        ]);
    }

    /**
     * ❌ Page annulation PayTech
     */
    public function cancel(Request $request)
    {
        Log::info('❌ Paiement annulé - Page cancel', $request->all());
        
        return view('paiement.cancel', [
            'message' => 'Vous avez annulé le paiement.',
            'reference' => $request->get('ref_command', 'N/A')
        ]);
    }

    /**
     * 🔄 Webhook PayTech : création de la commande après paiement réussi
     */
    public function callback(Request $request)
    {
        Log::info('📥 Webhook PayTech reçu', $request->all());

        try {
            // Vérifier la signature (optionnel mais recommandé)
            // $isValid = $this->paytech->validateSignature($request->all(), $request->header('Signature'));
            // if (!$isValid) {
            //     Log::error('❌ Signature PayTech invalide');
            //     return response()->json(['status' => 'error', 'message' => 'Signature invalide'], 401);
            // }

            // Vérifier si le paiement est réussi
            $paymentStatus = $request->get('payment_status');
            $customField = $request->get('custom_field');

            Log::info('🔍 Analyse webhook', [
                'payment_status' => $paymentStatus,
                'has_custom_field' => !empty($customField)
            ]);

            if ($paymentStatus === 'completed' && !empty($customField)) {
                
                $customData = json_decode($customField, true);
                $panier = $customData['panier'] ?? [];
                $userId = $customData['user_id'] ?? null;

                Log::info('🔍 Données custom décodées', [
                    'user_id' => $userId,
                    'nombre_articles' => count($panier)
                ]);

                if (!empty($panier) && $userId) {
                    // Calculer le total
                    $total = collect($panier)->sum(function ($item) {
                        return $item['price'] * $item['quantity'];
                    });

                    // Créer la commande après paiement réussi
                    $commande = Commande::create([
                        'user_id' => $userId,
                        'status' => 'payée',
                        'total' => $total,
                        'reference' => $request->get('ref_command', 'CMD_' . time()),
                        'transaction_id' => $request->get('transaction_id', ''),
                        'payment_method' => 'paytech',
                        'items' => json_encode($panier) // Stocker les articles en JSON
                    ]);

                    Log::info('✅ Commande créée après paiement PayTech', [
                        'commande_id' => $commande->id,
                        'user_id' => $userId,
                        'total' => $total,
                        'reference' => $commande->reference
                    ]);

                    // Vous pouvez aussi envoyer un email de confirmation ici
                    // Mail::to($user->email)->send(new CommandeConfirmee($commande));
                } else {
                    Log::warning('⚠️ Données insuffisantes pour créer la commande', [
                        'user_id' => $userId,
                        'panier_count' => count($panier)
                    ]);
                }
            } else {
                Log::info('ℹ️ Webhook ignoré - Statut non complet ou données manquantes', [
                    'payment_status' => $paymentStatus,
                    'has_custom_data' => !empty($customField)
                ]);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Webhook traité avec succès'
            ]);

        } catch (\Exception $e) {
            Log::error('❌ Erreur dans le webhook PayTech: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * 🔹 Page de test PayTech (optionnel)
     */
    public function testPaytech()
    {
        if (!app()->environment('local')) {
            abort(404);
        }

        // Données de test
        $testPanier = [
            [
                'id' => 1,
                'name' => 'Produit Test 1',
                'price' => 5000,
                'quantity' => 2
            ],
            [
                'id' => 2,
                'name' => 'Produit Test 2',
                'price' => 3000,
                'quantity' => 1
            ]
        ];

        $total = 13000;

        return view('paiement.test', [
            'panier' => $testPanier,
            'total' => $total
        ]);
    }
    

  // Dans PaiementController.php, modifiez la méthode showCheckout :

public function showCheckout()
{
    try {
        // Vérifier si l'utilisateur est connecté
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter pour finaliser votre commande.');
        }

        $user = Auth::user();
        
        // Récupérer le panier depuis la session
        $panier = session()->get('panier', []);
        
        // Si le panier est vide
        if (empty($panier)) {
            return redirect()->route('catalogue.index')->with('error', 'Votre panier est vide');
        }

        // Calculer le total
        $total = 0;
        foreach ($panier as $item) {
            $total += $item['prix'] * $item['quantite'];
        }
        
        Log::info('✅ Page checkout affichée', [
            'user_id' => $user->id,
            'nombre_articles' => count($panier),
            'total' => $total
        ]);

        return view('paiement.checkout', compact('panier', 'total', 'user'));

    } catch (\Exception $e) {
        Log::error('❌ Erreur dans showCheckout: ' . $e->getMessage());
        return redirect()->route('catalogue.index')->with('error', 'Erreur lors du chargement de la page de paiement');
    }
}
}