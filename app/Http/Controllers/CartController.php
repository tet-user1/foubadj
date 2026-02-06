<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Produit;
use App\Models\Commande;
use App\Models\CommandeItem;

class CartController extends Controller
{
    /**
     * Afficher la page du panier
     */
    public function index()
    {
        $panier = $this->getCartItems();
        
        return view('panier.index', compact('panier'));
    }

    /**
     * Ajouter un produit au panier
     */
    public function ajouter(Request $request, $id)
    {
        $request->validate([
            'quantite' => 'nullable|integer|min:1'
        ]);
        
        $produit = Produit::with('producteur')->findOrFail($id);
        $quantite = $request->input('quantite', 1);
        
        // Vérifier le stock
        if ($quantite > $produit->quantite) {
            return response()->json([
                'success' => false,
                'message' => "Stock insuffisant. Seulement {$produit->quantite} disponible(s)."
            ], 400);
        }
        
        // Récupérer le panier depuis la session
        $panier = session()->get('panier', []);
        
        // Vérifier si le produit existe déjà dans le panier
        $productExists = false;
        foreach ($panier as &$item) {
            if ($item['id'] == $id) {
                $nouvelleQuantite = $item['quantite'] + $quantite;
                
                // Vérifier le stock pour la nouvelle quantité
                if ($nouvelleQuantite > $produit->quantite) {
                    return response()->json([
                        'success' => false,
                        'message' => "Stock insuffisant. Maximum {$produit->quantite} disponible(s)."
                    ], 400);
                }
                
                $item['quantite'] = $nouvelleQuantite;
                $productExists = true;
                break;
            }
        }
        
        // Si le produit n'existe pas, l'ajouter
        if (!$productExists) {
            $panier[] = [
                'id' => $produit->id,
                'nom' => $produit->nom,
                'prix' => $produit->prix,
                'quantite' => $quantite,
                'image' => $produit->image_url ?? asset('storage/' . $produit->image),
                'stock' => $produit->quantite,
                'categorie' => $produit->categorie,
                'producteur' => $produit->producteur->name ?? 'Producteur'
            ];
        }
        
        // Sauvegarder dans la session
        session()->put('panier', $panier);
        
        // Calculer le nouveau total
        $stats = $this->calculateCartStats($panier);
        
        return response()->json([
            'success' => true,
            'message' => "{$produit->nom} ajouté au panier avec succès !",
            'panier_count' => $stats['count'],
            'total' => $stats['total']
        ]);
    }

    /**
     * Mettre à jour la quantité d'un produit
     */
    public function mettreAJour(Request $request, $id)
    {
        $request->validate([
            'quantite' => 'required|integer|min:1'
        ]);
        
        $produit = Produit::findOrFail($id);
        $quantite = $request->input('quantite');
        
        // Vérifier le stock
        if ($quantite > $produit->quantite) {
            return back()->with('error', "Stock insuffisant. Maximum {$produit->quantite} disponible(s).");
        }
        
        $panier = session()->get('panier', []);
        
        // Mettre à jour la quantité
        foreach ($panier as &$item) {
            if ($item['id'] == $id) {
                $item['quantite'] = $quantite;
                break;
            }
        }
        
        session()->put('panier', $panier);
        
        return back()->with('success', 'Quantité mise à jour !');
    }

    /**
     * Supprimer un produit du panier
     */
    public function supprimer($id)
    {
        $panier = session()->get('panier', []);
        
        // Filtrer pour supprimer le produit
        $panier = array_filter($panier, function($item) use ($id) {
            return $item['id'] != $id;
        });
        
        // Réindexer le tableau
        $panier = array_values($panier);
        
        session()->put('panier', $panier);
        
        return back()->with('success', 'Produit retiré du panier !');
    }

    /**
     * Vider le panier
     */
    public function vider()
    {
        session()->forget('panier');
        return back()->with('success', 'Panier vidé avec succès !');
    }

    /**
     * Obtenir le récapitulatif du panier (API pour AJAX)
     */
    public function recapitulatif()
    {
        $panier = session()->get('panier', []);
        $stats = $this->calculateCartStats($panier);
        
        return response()->json([
            'success' => true,
            'items' => $panier,
            'total' => $stats['total'],
            'panier_count' => $stats['count']
        ]);
    }

    /**
     * Synchroniser le localStorage avec les sessions (pour migration)
     */
    public function synchroniser(Request $request)
    {
        $request->validate([
            'panier' => 'required|array',
            'panier.*.id' => 'required|exists:produits,id',
            'panier.*.quantite' => 'required|integer|min:1'
        ]);
        
        $panier = [];
        
        foreach ($request->panier as $item) {
            $produit = Produit::with('producteur')->find($item['id']);
            
            if ($produit && $item['quantite'] <= $produit->quantite) {
                $panier[] = [
                    'id' => $produit->id,
                    'nom' => $produit->nom,
                    'prix' => $produit->prix,
                    'quantite' => $item['quantite'],
                    'image' => $produit->image_url ?? asset('storage/' . $produit->image),
                    'stock' => $produit->quantite,
                    'categorie' => $produit->categorie,
                    'producteur' => $produit->producteur->name ?? 'Producteur'
                ];
            }
        }
        
        session()->put('panier', $panier);
        
        return response()->json([
            'success' => true,
            'message' => 'Panier synchronisé avec succès'
        ]);
    }

    /**
     * Page de paiement/checkout
     */
    public function checkout()
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Veuillez vous connecter pour passer commande.');
        }
        
        $panier = session()->get('panier', []);
        
        if (empty($panier)) {
            return redirect()->route('catalogue.index')
                ->with('error', 'Votre panier est vide.');
        }
        
        $stats = $this->calculateCartStats($panier);
        
        return view('panier.checkout', [
            'panier' => $panier,
            'total' => $stats['total'],
            'count' => $stats['count']
        ]);
    }

    /**
     * Créer une commande à partir du panier de session
     */
    public function creerCommande(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Veuillez vous connecter pour passer commande.');
        }
        
        $panier = session()->get('panier', []);
        
        if (empty($panier)) {
            return back()->with('error', 'Votre panier est vide.');
        }
        
        try {
            DB::beginTransaction();
            
            $stats = $this->calculateCartStats($panier);
            
            // Vérifier les stocks pour tous les produits
            foreach ($panier as $item) {
                $produit = Produit::findOrFail($item['id']);
                
                if ($item['quantite'] > $produit->quantite) {
                    throw new \Exception("Stock insuffisant pour {$produit->nom}. Disponible: {$produit->quantite}");
                }
            }
            
            // Créer la commande
            $commande = Commande::create([
                'user_id' => Auth::id(),
                'numero_commande' => $this->generateOrderNumber(),
                'statut' => 'en_attente',
                'total' => $stats['total'],
                'nombre_articles' => $stats['count'],
                'date_commande' => now(),
            ]);
            
            // Créer les items de commande et mettre à jour les stocks
            foreach ($panier as $item) {
                $produit = Produit::findOrFail($item['id']);
                
                CommandeItem::create([
                    'commande_id' => $commande->id,
                    'produit_id' => $item['id'],
                    'quantite' => $item['quantite'],
                    'prix_unitaire' => $item['prix'],
                    'sous_total' => $item['prix'] * $item['quantite'],
                ]);
                
                // Décrémenter le stock
                $produit->decrement('quantite', $item['quantite']);
            }
            
            DB::commit();
            
            // Vider le panier
            session()->forget('panier');
            
            return redirect()->route('commandes.show', $commande->id)
                ->with('success', "Commande {$commande->numero_commande} créée avec succès !");
            
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    /**
     * Fonctions utilitaires privées
     */

    /**
     * Obtenir les items du panier formatés
     */
    private function getCartItems()
    {
        return session()->get('panier', []);
    }

    /**
     * Calculer les statistiques du panier
     */
    private function calculateCartStats($panier)
    {
        $total = 0;
        $count = 0;
        
        foreach ($panier as $item) {
            $total += ($item['prix'] ?? 0) * ($item['quantite'] ?? 0);
            $count += $item['quantite'] ?? 0;
        }
        
        return [
            'total' => $total,
            'count' => $count
        ];
    }

    /**
     * Générer un numéro de commande unique
     */
    private function generateOrderNumber()
    {
        do {
            $number = 'CMD-' . date('Ymd') . '-' . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (Commande::where('numero_commande', $number)->exists());
        
        return $number;
    }


      
    public function getBadge()
    {
        $panier = session()->get('panier', []);
        $stats = $this->calculateCartStats($panier);
        
        return response()->json([
            'success' => true,
            'panier_count' => $stats['count'],
            'total' => $stats['total']
        ]);
    }


}