<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use Illuminate\Http\Request;

class MarketplaceController extends Controller
{
    /**
     * Afficher la liste des produits du marketplace
     */
    public function index(Request $request)
    {
        // Récupérer les produits avec l'utilisateur (producteur)
        // La catégorie est maintenant un champ texte direct dans le modèle
        $query = Produit::with(['user']);

        // Optionnel: filtrer uniquement les produits avec quantité > 0
        // Décommentez la ligne suivante si vous voulez afficher seulement les produits en stock
        // $query->where('quantite', '>', 0);

        // Filtrer par catégorie si spécifié
        if ($request->has('categorie') && $request->categorie != '') {
            // La catégorie est maintenant stockée directement en texte
            $query->where('categorie', $request->categorie);
        }

        // Filtrer par prix minimum
        if ($request->has('min_prix') && $request->min_prix != '') {
            $query->where('prix', '>=', $request->min_prix);
        }

        // Filtrer par prix maximum
        if ($request->has('max_prix') && $request->max_prix != '') {
            $query->where('prix', '<=', $request->max_prix);
        }

        // Recherche par mot-clé
        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('nom', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // Tri
        $sort = $request->get('sort', 'recent');
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
            default:
                $query->orderBy('created_at', 'desc');
        }

        $produits = $query->paginate(12);
        
        // Récupérer les catégories uniques depuis les produits existants
        $categories = Produit::whereNotNull('categorie')
            ->where('categorie', '!=', '')
            ->distinct()
            ->pluck('categorie')
            ->toArray();
        
        $categories_count = count($categories);

        return view('marketplace', compact('produits', 'categories', 'categories_count'));
    }

    /**
     * Afficher les détails d'un produit
     */
    public function show($id)
    {
        $produit = Produit::with(['user'])->findOrFail($id);

        // Produits similaires (même catégorie, excluant le produit actuel)
        $produitsSimilaires = collect();
        
        // Si le produit a une catégorie (champ texte)
        if ($produit->categorie) {
            $produitsSimilaires = Produit::where('categorie', $produit->categorie)
                                         ->where('id', '!=', $produit->id)
                                         ->where('quantite', '>', 0)
                                         ->take(4)
                                         ->get();
        }

        // Vérifier si l'utilisateur est connecté pour afficher le bouton d'achat
        $isAuthenticated = auth()->check();
        $isAcheteur = $isAuthenticated && auth()->user()->hasRole('acheteur');

        return view('marketplace.show', compact('produit', 'produitsSimilaires', 'isAuthenticated', 'isAcheteur'));
    }
}