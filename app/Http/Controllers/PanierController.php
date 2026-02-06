<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produit;
use Illuminate\Support\Facades\Log;

class PanierController extends Controller
{
    /**
     * Ajouter un produit au panier
     */
    public function ajouter(Request $request)
    {
        try {
            $request->validate([
                'produit_id' => 'required|exists:produits,id',
                'quantite' => 'required|integer|min:1'
            ]);

            $produitId = $request->produit_id;
            $quantite = $request->quantite;

            // Récupérer le produit depuis la base de données
            $produit = Produit::findOrFail($produitId);

            // Vérifier le stock disponible
            if ($produit->quantite < $quantite) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stock insuffisant. Seulement ' . $produit->quantite . ' unité(s) disponible(s).'
                ], 400);
            }

            // Récupérer le panier de la session (ou créer un tableau vide)
            $panier = session()->get('panier', []);

            // Si le produit existe déjà, augmenter la quantité
            if (isset($panier[$produitId])) {
                $nouvelleQuantite = $panier[$produitId]['quantite'] + $quantite;
                
                // Vérifier à nouveau le stock
                if ($nouvelleQuantite > $produit->quantite) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Stock insuffisant pour cette quantité totale.'
                    ], 400);
                }
                
                $panier[$produitId]['quantite'] = $nouvelleQuantite;
            } else {
                // Ajouter le nouveau produit au panier
                $panier[$produitId] = [
                    'id' => $produit->id,
                    'nom' => $produit->nom,
                    'prix' => $produit->prix,
                    'quantite' => $quantite,
                    'image' => $produit->image_url,
                    'stock_disponible' => $produit->quantite
                ];
            }

            // Sauvegarder le panier en session
            session()->put('panier', $panier);

            // Calculer le total
            $total = $this->calculerTotal($panier);
            $nombreArticles = $this->compterArticles($panier);

            Log::info('✅ Produit ajouté au panier', [
                'produit_id' => $produitId,
                'quantite' => $quantite,
                'total_panier' => $total
            ]);

            return response()->json([
                'success' => true,
                'message' => $produit->nom . ' ajouté au panier',
                'panier' => $panier,
                'total' => $total,
                'nombre_articles' => $nombreArticles
            ]);

        } catch (\Exception $e) {
            Log::error('❌ Erreur ajout panier: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'ajout au panier'
            ], 500);
        }
    }

    /**
     * Mettre à jour la quantité d'un produit
     */
    public function mettreAJour(Request $request)
    {
        try {
            $request->validate([
                'produit_id' => 'required|exists:produits,id',
                'quantite' => 'required|integer|min:0'
            ]);

            $produitId = $request->produit_id;
            $quantite = $request->quantite;

            $panier = session()->get('panier', []);

            if (!isset($panier[$produitId])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Produit non trouvé dans le panier'
                ], 404);
            }

            // Si quantité = 0, supprimer du panier
            if ($quantite == 0) {
                unset($panier[$produitId]);
            } else {
                // Vérifier le stock
                $produit = Produit::findOrFail($produitId);
                if ($quantite > $produit->quantite) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Stock insuffisant'
                    ], 400);
                }

                $panier[$produitId]['quantite'] = $quantite;
            }

            session()->put('panier', $panier);

            $total = $this->calculerTotal($panier);
            $nombreArticles = $this->compterArticles($panier);

            return response()->json([
                'success' => true,
                'message' => 'Panier mis à jour',
                'panier' => $panier,
                'total' => $total,
                'nombre_articles' => $nombreArticles
            ]);

        } catch (\Exception $e) {
            Log::error('❌ Erreur mise à jour panier: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour'
            ], 500);
        }
    }

    /**
     * Supprimer un produit du panier
     */
    public function supprimer(Request $request)
    {
        try {
            $produitId = $request->produit_id;
            $panier = session()->get('panier', []);

            if (isset($panier[$produitId])) {
                unset($panier[$produitId]);
                session()->put('panier', $panier);
            }

            $total = $this->calculerTotal($panier);
            $nombreArticles = $this->compterArticles($panier);

            return response()->json([
                'success' => true,
                'message' => 'Produit retiré du panier',
                'panier' => $panier,
                'total' => $total,
                'nombre_articles' => $nombreArticles
            ]);

        } catch (\Exception $e) {
            Log::error('❌ Erreur suppression panier: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression'
            ], 500);
        }
    }

    /**
     * Vider le panier
     */
    public function vider()
    {
        session()->forget('panier');

        return response()->json([
            'success' => true,
            'message' => 'Panier vidé',
            'panier' => [],
            'total' => 0,
            'nombre_articles' => 0
        ]);
    }

    /**
     * Récupérer le contenu du panier
     */
    public function afficher()
    {
        $panier = session()->get('panier', []);
        $total = $this->calculerTotal($panier);
        $nombreArticles = $this->compterArticles($panier);

        return response()->json([
            'success' => true,
            'panier' => $panier,
            'total' => $total,
            'nombre_articles' => $nombreArticles
        ]);
    }

    /**
     * Calculer le total du panier
     */
    private function calculerTotal($panier)
    {
        $total = 0;
        foreach ($panier as $item) {
            $total += $item['prix'] * $item['quantite'];
        }
        return $total;
    }

    /**
     * Compter le nombre d'articles
     */
    private function compterArticles($panier)
    {
        $count = 0;
        foreach ($panier as $item) {
            $count += $item['quantite'];
        }
        return $count;
    }
}