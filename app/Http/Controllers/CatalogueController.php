<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produit;
use App\Models\Categorie;
use Illuminate\Support\Facades\DB;

class CatalogueController extends Controller
{
    /**
     * Afficher la page du catalogue
     */
    public function index(Request $request)
    {
        // Récupérer les paramètres de filtrage
        $search = $request->input('search');
        $category = $request->input('category');
        $sort = $request->input('sort', 'recent');
        
        // Construire la requête
        $query = Produit::with('user')->where('quantite', '>', 0);
        
        // Appliquer les filtres
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        if ($category) {
            $query->where('categorie', $category);
        }
        
        // Appliquer le tri
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('prix', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('prix', 'desc');
                break;
            case 'name':
                $query->orderBy('nom', 'asc');
                break;
            case 'recent':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }
        
        // Récupérer les produits
        $produits = $query->paginate(12);
        
        // Récupérer les catégories pour le filtre
        $categories = Categorie::all();
        
        // Statistiques pour les cartes
        $stats = [
            'total_produits' => Produit::count(),
            'total_producteurs' => DB::table('produits')->distinct('user_id')->count('user_id'),
            'total_categories' => $categories->count(),
        ];
        
        return view('catalogue.index', compact('produits', 'categories', 'stats', 'search', 'category', 'sort'));
    }

    /**
     * Afficher les détails d'un produit
     */
    /**
 * Afficher les détails d'un produit
 */
public function show($id)
{
    $produit = Produit::with('user')->findOrFail($id);
    
    // Produits similaires (même catégorie)
    $produitsSimilaires = Produit::where('categorie', $produit->categorie)
                                ->where('id', '!=', $id)
                                ->where('quantite', '>', 0)
                                ->inRandomOrder()
                                ->limit(4)
                                ->get();
    
    // Si pas assez de produits dans la même catégorie, ajouter d'autres produits
    if ($produitsSimilaires->count() < 4) {
        $additionalProducts = Produit::where('categorie', '!=', $produit->categorie)
                                    ->where('id', '!=', $id)
                                    ->where('quantite', '>', 0)
                                    ->inRandomOrder()
                                    ->limit(4 - $produitsSimilaires->count())
                                    ->get();
        
        $produitsSimilaires = $produitsSimilaires->merge($additionalProducts);
    }
    
    return view('catalogue.show', compact('produit', 'produitsSimilaires'));
}

    /**
     * API: Recherche de produits
     */
    public function search(Request $request)
    {
        $search = $request->input('q');
        
        $produits = Produit::where('nom', 'like', "%{$search}%")
                          ->orWhere('description', 'like', "%{$search}%")
                          ->where('quantite', '>', 0)
                          ->with('user')
                          ->limit(10)
                          ->get();
        
        return response()->json($produits);
    }

    /**
     * API: Filtrage des produits
     */
    public function filter(Request $request)
    {
        $category = $request->input('category');
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');
        
        $query = Produit::where('quantite', '>', 0);
        
        if ($category) {
            $query->where('categorie', $category);
        }
        
        if ($minPrice) {
            $query->where('prix', '>=', $minPrice);
        }
        
        if ($maxPrice) {
            $query->where('prix', '<=', $maxPrice);
        }
        
        $produits = $query->with('user')->paginate(12);
        
        return response()->json([
            'produits' => $produits,
            'html' => view('catalogue.partials.products-grid', compact('produits'))->render()
        ]);
    }
}