<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class ProduitController extends Controller
{
    public function __construct()
    {
        // Le middleware 'role:producteur' est déjà appliqué dans les routes
        // Pas besoin de vérification supplémentaire ici
    }

    /**
     * Afficher la liste des produits du producteur connecté avec filtres et tri
     */
    public function index(Request $request)
    {
        $query = Produit::byUser(Auth::id());

        // Gestion de la recherche
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('nom', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        // Gestion du filtrage par stock
        if ($request->filled('stock_filter')) {
            switch ($request->get('stock_filter')) {
                case 'available':
                    $query->where('quantite', '>', 10);
                    break;
                case 'low':
                    $query->whereBetween('quantite', [1, 10]);
                    break;
                case 'out':
                    $query->where('quantite', 0);
                    break;
            }
        }

        // Gestion du tri
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');

        switch ($sortBy) {
            case 'name':
                $query->orderBy('nom', $sortOrder);
                break;
            case 'price':
                $query->orderBy('prix', $sortOrder);
                break;
            case 'stock':
                $query->orderBy('quantite', $sortOrder);
                break;
            case 'date':
            default:
                $query->orderBy('created_at', $sortOrder);
                break;
        }

        $produits = $query->paginate(12)->appends($request->query());

        // Calculer les statistiques pour l'affichage
        $stats = $this->calculateUserStats();

        return view('produits.index', compact('produits', 'stats'));
    }

    /**
     * Afficher la vue d'index des produits (même que index)
     */
    public function indexView(Request $request)
    {
        return $this->index($request);
    }

    /**
     * Afficher le formulaire de création d'un produit
     */
    public function create()
    {
        return view('produits.create');
    }

    /**
     * Enregistrer un nouveau produit avec Cloudinary
     */
    public function store(Request $request)
    {
        // Validation des données avec messages personnalisés
        $validated = $request->validate([
            'nom' => 'required|string|max:255|min:2',
            'description' => 'required|string|max:2000|min:10',
            'prix' => 'required|numeric|min:0|max:999999999',
            'quantite' => 'required|integer|min:0|max:999999',
            'categorie' => 'required|string|max:255',
            'image' => [
                'nullable',
                File::image()
                    ->max(5 * 1024) // 5MB max
                    ->types(['jpeg', 'jpg', 'png', 'webp', 'gif'])
            ]
        ], [
            'nom.required' => 'Le nom du produit est obligatoire',
            'nom.min' => 'Le nom doit contenir au moins 2 caractères',
            'nom.max' => 'Le nom ne peut pas dépasser 255 caractères',
            'description.required' => 'La description est obligatoire',
            'description.min' => 'La description doit contenir au moins 10 caractères',
            'description.max' => 'La description ne peut pas dépasser 2000 caractères',
            'prix.required' => 'Le prix est obligatoire',
            'prix.numeric' => 'Le prix doit être un nombre valide',
            'prix.min' => 'Le prix ne peut pas être négatif',
            'prix.max' => 'Le prix est trop élevé',
            'quantite.required' => 'La quantité est obligatoire',
            'quantite.integer' => 'La quantité doit être un nombre entier',
            'quantite.min' => 'La quantité ne peut pas être négative',
            'quantite.max' => 'La quantité est trop élevée',
            'categorie.required' => 'La catégorie est obligatoire',
            'image.image' => 'Le fichier doit être une image valide',
            'image.max' => 'L\'image ne peut pas dépasser 5MB',
            'image.mimes' => 'L\'image doit être au format JPG, PNG, WebP ou GIF'
        ]);

        try {
            // Upload de l'image vers Cloudinary si présente
            if ($request->hasFile('image')) {
                $uploadedFileUrl = Cloudinary::upload(
                    $request->file('image')->getRealPath(),
                    [
                        'folder' => 'foubadj/produits',
                        'transformation' => [
                            'width' => 800,
                            'height' => 800,
                            'crop' => 'limit',
                            'quality' => 'auto'
                        ]
                    ]
                )->getSecurePath();
                
                $validated['image'] = $uploadedFileUrl;
            }

            // Ajouter l'ID de l'utilisateur et slug
            $validated['user_id'] = Auth::id();
            $validated['slug'] = Str::slug($validated['nom']) . '-' . time();

            // Créer le produit
            $produit = Produit::create($validated);

            // Log de l'activité
            \Log::info('Nouveau produit créé', [
                'produit_id' => $produit->id,
                'user_id' => Auth::id(),
                'nom' => $produit->nom
            ]);

            return redirect()
                ->route('produits.index')
                ->with('success', 'Produit "' . $produit->nom . '" créé avec succès !')
                ->with('refresh_stats', true);

        } catch (\Exception $e) {
    \Log::error('Erreur création produit', [
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString(),
        'user_id' => Auth::id()
    ]);

    // AFFICHER L'ERREUR COMPLETE (temporaire pour debug)
    dd([
        'message' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
        'trace' => $e->getTraceAsString()
    ]);
}
    }

    /**
     * Afficher un produit spécifique
     */
    public function show(Produit $produit)
    {
        // Vérifier que le produit appartient au producteur connecté
        if ($produit->user_id !== Auth::id()) {
            abort(403, 'Vous ne pouvez pas voir ce produit');
        }

        return view('produits.show', compact('produit'));
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(Produit $produit)
    {
        // Vérifier que le produit appartient au producteur connecté
        if ($produit->user_id !== Auth::id()) {
            abort(403, 'Vous ne pouvez pas modifier ce produit');
        }

        return view('produits.edit', compact('produit'));
    }

    /**
     * Mettre à jour un produit avec Cloudinary
     */
    public function update(Request $request, Produit $produit)
    {
        // Vérifier que le produit appartient au producteur connecté
        if ($produit->user_id !== Auth::id()) {
            abort(403, 'Vous ne pouvez pas modifier ce produit');
        }

        // Validation des données
        $validated = $request->validate([
            'nom' => 'required|string|max:255|min:2',
            'description' => 'required|string|max:2000|min:10',
            'prix' => 'required|numeric|min:0|max:999999999',
            'quantite' => 'required|integer|min:0|max:999999',
            'categorie' => 'required|string|max:255',
            'image' => [
                'nullable',
                File::image()
                    ->max(5 * 1024) // 5MB max
                    ->types(['jpeg', 'jpg', 'png', 'webp', 'gif'])
            ]
        ]);

        try {
            $oldImageUrl = $produit->image;

            // Upload de la nouvelle image vers Cloudinary si présente
            if ($request->hasFile('image')) {
                $uploadedFileUrl = Cloudinary::upload(
                    $request->file('image')->getRealPath(),
                    [
                        'folder' => 'foubadj/produits',
                        'transformation' => [
                            'width' => 800,
                            'height' => 800,
                            'crop' => 'limit',
                            'quality' => 'auto'
                        ]
                    ]
                )->getSecurePath();
                
                $validated['image'] = $uploadedFileUrl;
                
                // Supprimer l'ancienne image de Cloudinary
                if ($oldImageUrl && str_starts_with($oldImageUrl, 'http')) {
                    $this->deleteCloudinaryImage($oldImageUrl);
                }
            }

            // Mettre à jour le slug si le nom a changé
            if ($produit->nom !== $validated['nom']) {
                $validated['slug'] = Str::slug($validated['nom']) . '-' . time();
            }

            // Mettre à jour le produit
            $produit->update($validated);

            // Log de l'activité
            \Log::info('Produit mis à jour', [
                'produit_id' => $produit->id,
                'user_id' => Auth::id(),
                'modifications' => array_keys($validated)
            ]);

            return redirect()
                ->route('produits.show', $produit)
                ->with('success', 'Produit "' . $produit->nom . '" mis à jour avec succès !')
                ->with('refresh_stats', true);

        } catch (\Exception $e) {
            \Log::error('Erreur mise à jour produit', [
                'produit_id' => $produit->id,
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erreur lors de la mise à jour : ' . $e->getMessage());
        }
    }

    /**
     * Supprimer un produit avec suppression Cloudinary
     */
    public function destroy(Produit $produit)
    {
        // Vérifier que le produit appartient au producteur connecté
        if ($produit->user_id !== Auth::id()) {
            abort(403, 'Vous ne pouvez pas supprimer ce produit');
        }

        try {
            $nomProduit = $produit->nom;
            $imageUrl = $produit->image;
            
            // Supprimer le produit
            $produit->delete();
            
            // Supprimer l'image de Cloudinary
            if ($imageUrl && str_starts_with($imageUrl, 'http')) {
                $this->deleteCloudinaryImage($imageUrl);
            }

            // Log de l'activité
            \Log::info('Produit supprimé', [
                'produit_nom' => $nomProduit,
                'user_id' => Auth::id()
            ]);

            return redirect()
                ->route('produits.index')
                ->with('success', 'Produit "' . $nomProduit . '" supprimé avec succès !')
                ->with('refresh_stats', true);

        } catch (\Exception $e) {
            \Log::error('Erreur suppression produit', [
                'produit_id' => $produit->id,
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);

            return redirect()
                ->back()
                ->with('error', 'Erreur lors de la suppression : ' . $e->getMessage());
        }
    }

    /**
     * Méthode privée pour supprimer une image de Cloudinary
     */
    private function deleteCloudinaryImage($imageUrl)
    {
        try {
            // Extraire le public_id de l'URL Cloudinary
            // Format: https://res.cloudinary.com/cloud-name/image/upload/v123456/foubadj/produits/abc123.jpg
            preg_match('/\/foubadj\/produits\/([^\.]+)/', $imageUrl, $matches);
            
            if (isset($matches[1])) {
                $publicId = 'foubadj/produits/' . $matches[1];
                Cloudinary::destroy($publicId);
                \Log::info('Image Cloudinary supprimée', ['public_id' => $publicId]);
            }
        } catch (\Exception $e) {
            // Log l'erreur mais ne pas bloquer la suppression
            \Log::error('Erreur suppression image Cloudinary: ' . $e->getMessage());
        }
    }

    /**
     * API pour récupérer les statistiques (utilisée par le dashboard)
     */
    public function getStats()
    {
        $stats = $this->calculateUserStats();
        return response()->json($stats);
    }

    /**
     * Recherche rapide de produits (API)
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $produits = Produit::byUser(Auth::id())
            ->where(function($q) use ($query) {
                $q->where('nom', 'LIKE', "%{$query}%")
                  ->orWhere('description', 'LIKE', "%{$query}%");
            })
            ->select('id', 'nom', 'prix', 'quantite', 'image')
            ->limit(10)
            ->get()
            ->map(function($produit) {
                return [
                    'id' => $produit->id,
                    'nom' => $produit->nom,
                    'prix' => $produit->prix,
                    'quantite' => $produit->quantite,
                    'image_url' => $produit->image_url,
                    'stock_status' => $this->getStockStatus($produit->quantite)
                ];
            });

        return response()->json($produits);
    }

    /**
     * Mise à jour rapide du stock (API)
     */
    public function updateStock(Request $request, Produit $produit)
    {
        // Vérifier la propriété
        if ($produit->user_id !== Auth::id()) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        $validated = $request->validate([
            'quantite' => 'required|integer|min:0|max:999999'
        ]);

        try {
            $ancienneQuantite = $produit->quantite;
            $produit->update(['quantite' => $validated['quantite']]);

            \Log::info('Stock mis à jour', [
                'produit_id' => $produit->id,
                'ancienne_quantite' => $ancienneQuantite,
                'nouvelle_quantite' => $validated['quantite'],
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Stock mis à jour avec succès',
                'nouvelle_quantite' => $produit->quantite,
                'stock_status' => $this->getStockStatus($produit->quantite)
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de la mise à jour'], 500);
        }
    }

    /**
     * Duplication d'un produit
     */
    public function duplicate(Produit $produit)
    {
        // Vérifier la propriété
        if ($produit->user_id !== Auth::id()) {
            abort(403, 'Vous ne pouvez pas dupliquer ce produit');
        }

        try {
            $nouveauProduit = $produit->replicate();
            $nouveauProduit->nom = $produit->nom . ' (Copie)';
            $nouveauProduit->slug = Str::slug($nouveauProduit->nom) . '-' . time();
            $nouveauProduit->quantite = 0; // Nouveau produit sans stock
            
            // Garder la même URL d'image (pas besoin de dupliquer sur Cloudinary)
            $nouveauProduit->image = $produit->image;
            
            $nouveauProduit->save();

            return redirect()
                ->route('produits.edit', $nouveauProduit)
                ->with('success', 'Produit dupliqué avec succès ! Vous pouvez maintenant le modifier.')
                ->with('refresh_stats', true);

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Erreur lors de la duplication du produit.');
        }
    }

    /**
     * Export des produits en CSV
     */
    public function export()
    {
        $produits = Produit::byUser(Auth::id())
                          ->orderBy('nom')
                          ->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="mes_produits_' . date('Y-m-d') . '.csv"',
        ];

        $callback = function() use ($produits) {
            $file = fopen('php://output', 'w');
            
            // En-têtes CSV
            fputcsv($file, [
                'Nom',
                'Description',
                'Prix (FCFA)',
                'Quantité',
                'Valeur totale (FCFA)',
                'Statut stock',
                'Date création'
            ]);

            // Données
            foreach ($produits as $produit) {
                fputcsv($file, [
                    $produit->nom,
                    $produit->description,
                    $produit->prix,
                    $produit->quantite,
                    $produit->prix * $produit->quantite,
                    $this->getStockStatus($produit->quantite),
                    $produit->created_at->format('d/m/Y H:i')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Calculer les statistiques de l'utilisateur
     */
    private function calculateUserStats()
    {
        $userId = Auth::id();
        
        $produits = Produit::byUser($userId)->get();
        
        return [
            'total_produits' => $produits->count(),
            'produits_en_stock' => $produits->where('quantite', '>', 0)->count(),
            'produits_stock_faible' => $produits->whereBetween('quantite', [1, 10])->count(),
            'produits_rupture' => $produits->where('quantite', 0)->count(),
            'valeur_totale_stock' => $produits->sum(function($produit) {
                return $produit->prix * $produit->quantite;
            }),
            'prix_moyen' => $produits->avg('prix') ?? 0,
            'stock_total' => $produits->sum('quantite')
        ];
    }

    /**
     * Obtenir le statut du stock
     */
    private function getStockStatus($quantite)
    {
        if ($quantite > 10) {
            return 'En stock';
        } elseif ($quantite > 0) {
            return 'Stock faible';
        } else {
            return 'Rupture';
        }
    }

    /**
     * Mise à jour en lot des quantités (API)
     */
    public function bulkUpdateStock(Request $request)
    {
        $validated = $request->validate([
            'updates' => 'required|array',
            'updates.*.id' => 'required|exists:produits,id',
            'updates.*.quantite' => 'required|integer|min:0|max:999999'
        ]);

        try {
            $updated = 0;
            $errors = [];

            foreach ($validated['updates'] as $update) {
                $produit = Produit::find($update['id']);
                
                // Vérifier la propriété
                if ($produit->user_id !== Auth::id()) {
                    $errors[] = "Produit {$produit->nom} : non autorisé";
                    continue;
                }
                
                $produit->update(['quantite' => $update['quantite']]);
                $updated++;
            }

            return response()->json([
                'success' => true,
                'message' => "{$updated} produit(s) mis à jour",
                'errors' => $errors,
                'stats' => $this->calculateUserStats()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Erreur lors de la mise à jour en lot'
            ], 500);
        }
    }

    /**
     * Archiver/désarchiver un produit
     */
    public function toggleArchive(Produit $produit)
    {
        // Vérifier la propriété
        if ($produit->user_id !== Auth::id()) {
            abort(403, 'Vous ne pouvez pas modifier ce produit');
        }

        try {
            // Supposer qu'il y a un champ 'archived' dans la table
            $produit->update([
                'archived' => !($produit->archived ?? false)
            ]);

            $status = $produit->archived ? 'archivé' : 'désarchivé';
            
            return redirect()
                ->back()
                ->with('success', "Produit {$status} avec succès !")
                ->with('refresh_stats', true);

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Erreur lors de l\'opération.');
        }
    }

    /**
     * Obtenir les produits en stock faible (API)
     */
    public function getLowStock()
    {
        $produits = Produit::byUser(Auth::id())
                          ->whereBetween('quantite', [1, 10])
                          ->select('id', 'nom', 'quantite')
                          ->get();

        return response()->json($produits);
    }

    /**
     * Tableau de bord des produits avec graphiques
     */
    public function dashboard()
    {
        $stats = $this->calculateUserStats();
        
        // Données pour les graphiques
        $stockDistribution = [
            'en_stock' => $stats['produits_en_stock'],
            'stock_faible' => $stats['produits_stock_faible'],
            'rupture' => $stats['produits_rupture']
        ];

        // Évolution sur les 30 derniers jours
        $evolutionStock = Produit::byUser(Auth::id())
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('produits.dashboard', compact('stats', 'stockDistribution', 'evolutionStock'));
    }

    /**
     * Afficher la page "Mes Produits" (alias vers index)
     */
    public function myProducts(Request $request)
    {
        return $this->index($request);
    }

    /**
     * API pour récupérer les statistiques avancées 
     */
    public function getAdvancedProductStats(Request $request)
    {
        $userId = Auth::id();
        
        $produits = Produit::byUser($userId)->get();
        
        // Statistiques réelles basées sur vos produits
        $stats = [
            'total_produits' => $produits->count(),
            'valeur_totale_stock' => $produits->sum(function($p) {
                return $p->prix * $p->quantite;
            }),
            'produits_en_stock' => $produits->where('quantite', '>', 10)->count(),
            'produits_stock_faible' => $produits->whereBetween('quantite', [1, 10])->count(),
            'produits_rupture' => $produits->where('quantite', 0)->count(),
            'prix_moyen' => $produits->avg('prix') ?? 0
        ];
        
        // Top 5 produits par valeur de stock
        $topProduitsParValeur = $produits->sortByDesc(function($p) {
            return $p->prix * $p->quantite;
        })->take(5);
        
        return response()->json([
            'stats' => $stats,
            'top_produits_valeur' => $topProduitsParValeur->map(function($p) {
                return [
                    'nom' => $p->nom,
                    'valeur' => $p->prix * $p->quantite,
                    'quantite' => $p->quantite,
                    'prix' => $p->prix
                ];
            })->values()
        ]);
    }
}