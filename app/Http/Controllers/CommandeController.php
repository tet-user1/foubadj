<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Commande;
use App\Models\CommandeItem;
use App\Models\Produit;

class CommandeController extends Controller
{
    /**
     * Afficher le formulaire de commande
     */
    public function showOrderForm(Request $request)
    {
        // Récupérer les données du panier depuis la query string ou la session
        $cartData = null;
        if ($request->has('data')) {
            $cartData = json_decode($request->get('data'), true);
        }

        // Si pas de données de panier, récupérer depuis la base de données
        if (!$cartData) {
            $cartItems = Auth::user()->getPanierAvecProduits();
            if ($cartItems->isEmpty()) {
                return redirect()->route('catalogue.index')
                    ->with('warning', 'Votre panier est vide. Ajoutez des produits avant de passer commande.');
            }
            
            $cartData = [
                'items' => $cartItems->map(function ($item) {
                    return [
                        'id' => $item->produit->id,
                        'name' => $item->produit->nom,
                        'price' => $item->produit->prix,
                        'quantity' => $item->quantite,
                        'total' => $item->getSousTotal(),
                        'image' => $item->produit->image_url,
                        'producer' => $item->produit->user->name ?? 'N/A'
                    ];
                })->toArray(),
                'total' => Auth::user()->getTotalPanier(),
                'itemCount' => Auth::user()->getNombreArticlesPanier()
            ];
        }

        return view('commande.form', compact('cartData'));
    }

    /**
     * Traiter la commande depuis le formulaire web
     */
    public function processOrder(Request $request)
    {
        $request->validate([
            'items' => 'required|json',
            'adresse_livraison' => 'required|string|max:255',
            'ville_livraison' => 'required|string|max:100',
            'telephone_livraison' => 'required|string|max:20',
            'mode_paiement' => 'required|in:especes,mobile_money,carte_bancaire,virement',
            'notes_client' => 'nullable|string|max:500'
        ]);

        try {
            $items = json_decode($request->items, true);
            
            DB::beginTransaction();

            // Vérifier la disponibilité des stocks
            foreach ($items as $item) {
                $produit = Produit::findOrFail($item['id']);
                if ($item['quantity'] > $produit->quantite) {
                    throw new \Exception("Stock insuffisant pour le produit: {$produit->nom}");
                }
            }

            // Créer la commande
            $commande = Commande::create([
                'user_id' => Auth::id(),
                'numero_commande' => $this->generateOrderNumber(),
                'statut' => Commande::STATUT_EN_ATTENTE,
                'total' => collect($items)->sum('total'),
                'nombre_articles' => collect($items)->sum('quantity'),
                'date_commande' => now(),
                'adresse_livraison' => $request->adresse_livraison,
                'ville_livraison' => $request->ville_livraison,
                'telephone_livraison' => $request->telephone_livraison,
                'mode_paiement' => $request->mode_paiement,
                'statut_paiement' => Commande::STATUT_PAIEMENT_EN_ATTENTE,
                'notes_client' => $request->notes_client,
            ]);

            // Ajouter les articles et mettre à jour les stocks
            foreach ($items as $item) {
                $produit = Produit::findOrFail($item['id']);
                
                CommandeItem::create([
                    'commande_id' => $commande->id,
                    'produit_id' => $item['id'],
                    'quantite' => $item['quantity'],
                    'prix_unitaire' => $item['price'],
                    'sous_total' => $item['total'],
                ]);

                // Décrémenter le stock
                $produit->decrementerStock($item['quantity']);
            }

            // Vider le panier de la base de données
            if (Auth::check()) {
                Auth::user()->panier()->delete();
            }

            DB::commit();

            return redirect()->route('commandes.confirmation', $commande)
                ->with('success', 'Votre commande a été créée avec succès !');

        } catch (\Exception $e) {
            DB::rollback();
            
            return back()->withInput()
                ->with('error', 'Erreur lors de la création de la commande: ' . $e->getMessage());
        }
    }



     /**
     * Afficher la liste des commandes de l'utilisateur
     */
    public function index()
    {
        $commandes = Commande::where('user_id', Auth::id())
                            ->with('items.produit')
                            ->orderBy('created_at', 'desc')
                            ->paginate(10);

        return view('commandes.index', compact('commandes'));
    }



    /**
     * Afficher la confirmation de commande
     */
    public function showOrderConfirmation(Commande $commande)
    {
        // Vérifier que la commande appartient à l'utilisateur connecté
        if ($commande->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé');
        }

        $commande->load(['items.produit.user']);

        return view('commande.confirmation', compact('commande'));
    }

    /**
     * Afficher l'historique des commandes de l'utilisateur
     */
    public function showUserOrders(Request $request)
    {
        $statut = $request->get('statut');
        
        $query = Auth::user()->commandes()->with(['items.produit']);
        
        if ($statut) {
            $query->where('statut', $statut);
        }
        
        $commandes = $query->paginate(10);

        $statuts = [
            '' => 'Toutes',
            Commande::STATUT_EN_ATTENTE => 'En attente',
            Commande::STATUT_CONFIRMEE => 'Confirmées',
            Commande::STATUT_EN_PREPARATION => 'En préparation',
            Commande::STATUT_EN_LIVRAISON => 'En livraison',
            Commande::STATUT_LIVREE => 'Livrées',
            Commande::STATUT_ANNULEE => 'Annulées',
        ];

        return view('commandes.history', compact('commandes', 'statuts', 'statut'));
    }

    /**
     * Afficher les détails d'une commande
     */
    public function showOrderDetails(Commande $commande)
    {
        // Vérifier que la commande appartient à l'utilisateur connecté
        if ($commande->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé');
        }

        $commande->load(['items.produit.user']);

        return view('commande.show', compact('commande'));
    }

    /**
     * API: Récupérer les commandes de l'utilisateur
     */
    public function getUserOrders(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 10);
            $statut = $request->get('statut');
            
            $query = Auth::user()->commandes()->with(['items.produit']);
            
            if ($statut) {
                $query->where('statut', $statut);
            }
            
            $commandes = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $commandes
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des commandes',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Récupérer une commande spécifique
     */
    public function getOrder(Commande $commande)
    {
        // Vérifier que la commande appartient à l'utilisateur connecté
        if ($commande->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Accès non autorisé'
            ], 403);
        }

        try {
            $commande->load(['items.produit.user']);

            return response()->json([
                'success' => true,
                'data' => $commande
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération de la commande',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Annuler une commande
     */
    public function cancelOrder(Commande $commande)
    {
        // Vérifier que la commande appartient à l'utilisateur connecté
        if ($commande->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Accès non autorisé'
            ], 403);
        }

        try {
            // Vérifier si la commande peut être annulée
            if (!$commande->peutEtreAnnulee()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cette commande ne peut plus être annulée'
                ], 400);
            }

            DB::beginTransaction();

            // Remettre les stocks
            foreach ($commande->items as $item) {
                $item->produit->incrementerStock($item->quantite);
            }

            // Changer le statut
            $commande->update([
                'statut' => Commande::STATUT_ANNULEE
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Commande annulée avec succès'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'annulation de la commande',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Générer un numéro de commande unique
     */
    private function generateOrderNumber()
    {
        do {
            $number = 'CMD-' . date('Y') . '-' . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (Commande::where('numero_commande', $number)->exists());

        return $number;
    }
}