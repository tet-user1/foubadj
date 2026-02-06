<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Produit;
use App\Models\Commande;
use App\Models\CommandeProduit;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function acheteur()
    {
        return view('dashboard_acheteur');
    }

    public function producteur()
    {
        $user = Auth::user();

        // Récupérer tous les produits du producteur connecté
        $produits = Produit::where('user_id', $user->id)->get();

        // Calculer les statistiques
        $stats = [
            'total_produits' => $produits->count(),
            'produits_en_stock' => $produits->where('quantite', '>', 0)->count(),
            'valeur_totale_stock' => $produits->sum(fn($p) => $p->prix * $p->quantite),
            'produits_stock_faible' => $produits->where('quantite', '>', 0)->where('quantite', '<=', 10)->count(),
        ];

        // Produits récents (5 derniers)
        $recentProducts = Produit::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Produits en alerte (rupture ou stock faible)
        $alertesStock = Produit::where('user_id', $user->id)
            ->where(function ($query) {
                $query->where('quantite', 0)
                      ->orWhere('quantite', '<=', 10);
            })
            ->orderBy('quantite', 'asc')
            ->get();

        return view('dashboard_producteur', compact('stats', 'recentProducts', 'alertesStock'));
    }

    /**
     * Vue des statistiques détaillées
     */
    public function statistics()
    {
        $user = Auth::user();
        $produits = Produit::where('user_id', $user->id)->get();

        $stats = [
            'total_produits' => $produits->count(),
            'produits_en_stock' => $produits->where('quantite', '>', 0)->count(),
            'valeur_totale_stock' => $produits->sum(fn($p) => $p->prix * $p->quantite),
            'produits_stock_faible' => $produits->where('quantite', '>', 0)->where('quantite', '<=', 10)->count(),
        ];

        return view('statistics', compact('stats'));
    }

    /**
     * API – stats avancées
     */
    public function getAdvancedStats(Request $request)
    {
        $userId = Auth::id();
        $dateDebut = $request->get('date_debut', Carbon::now()->subDays(30)->toDateString());
        $dateFin = $request->get('date_fin', Carbon::now()->toDateString());

        $produits = Produit::where('user_id', $userId)->get();

        // KPIs de base
        $commandesCount = rand(50, 300);
        $chiffresAffaires = rand(500000, 2000000);

        // Évolution des ventes simulée
        $evolutionLabels = [];
        $evolutionVentes = [];
        $evolutionCommandes = [];

        $current = Carbon::parse($dateDebut);
        $fin = Carbon::parse($dateFin);

        while ($current <= $fin) {
            $evolutionLabels[] = $current->format('d/m');
            $evolutionVentes[] = rand(5000, 50000);
            $evolutionCommandes[] = rand(1, 15);
            $current->addDay();
        }

        // Top produits (simulé si vide)
        $topProduits = [
            'labels' => $produits->take(5)->pluck('nom')->toArray(),
            'data' => $produits->take(5)->map(fn() => rand(10, 100))->toArray()
        ];

        while (count($topProduits['labels']) < 5) {
            $topProduits['labels'][] = 'Produit ' . (count($topProduits['labels']) + 1);
            $topProduits['data'][] = rand(5, 50);
        }

        // Catégories fictives
        $categories = [
            'labels' => ['Légumes', 'Fruits', 'Céréales', 'Légumineuses', 'Autres'],
            'data' => [40, 25, 15, 12, 8]
        ];

        // Évolution du stock par semaine
        $stockLabels = [];
        $stockData = [];
        for ($i = 3; $i >= 0; $i--) {
            $date = Carbon::now()->subWeeks($i);
            $stockLabels[] = 'Semaine ' . $date->weekOfYear;
            $valeurStock = $produits->sum(fn($p) => $p->prix * $p->quantite);
            $variation = rand(-20000, 30000);
            $stockData[] = max(0, $valeurStock + $variation);
        }

        // Produits les plus vendus (fictifs si pas de ventes)
        $topVentesDetails = $produits->take(5)->map(function ($produit) {
            return [
                'nom' => $produit->nom,
                'quantite_vendue' => rand(20, 200),
                'ca_genere' => rand(50000, 500000)
            ];
        })->toArray();

        // Alertes de stock
        $alertesStock = $produits->filter(fn($p) => $p->quantite <= 10)->map(function ($produit) {
            return [
                'nom' => $produit->nom,
                'stock' => $produit->quantite,
                'seuil' => 10,
                'statut' => $produit->quantite == 0 ? 'Rupture' : 'Stock faible'
            ];
        })->values()->toArray();

        return response()->json([
            'kpis' => [
                'totalProduits' => $produits->count(),
                'commandes' => $commandesCount,
                'chiffresAffaires' => $chiffresAffaires,
                'vues' => rand(1000, 5000),
                'tauxConversion' => round(rand(800, 1500) / 100, 1),
                'noteMoyenne' => round(rand(350, 500) / 100, 1)
            ],
            'evolution' => [
                'labels' => $evolutionLabels,
                'ventesData' => $evolutionVentes,
                'commandesData' => $evolutionCommandes
            ],
            'topProduits' => $topProduits,
            'categories' => $categories,
            'stock' => [
                'labels' => $stockLabels,
                'data' => $stockData
            ],
            'topVentesDetails' => $topVentesDetails,
            'alertesStock' => $alertesStock
        ]);
    }

    /* ================== MÉTHODES PRIVÉES ================== */

    private function calculateKPIs($produitsIds, $dateDebut, $dateFin)
    {
        $commandes = DB::table('commande_produits')
            ->join('commandes', 'commande_produits.commande_id', '=', 'commandes.id')
            ->whereIn('commande_produits.produit_id', $produitsIds)
            ->whereBetween('commandes.created_at', [$dateDebut, $dateFin])
            ->distinct('commandes.id')
            ->count();

        $chiffreAffaires = DB::table('commande_produits')
            ->join('commandes', 'commande_produits.commande_id', '=', 'commandes.id')
            ->whereIn('commande_produits.produit_id', $produitsIds)
            ->whereBetween('commandes.created_at', [$dateDebut, $dateFin])
            ->sum(DB::raw('commande_produits.quantite * commande_produits.prix_unitaire'));

        $vues = rand(500, 2000);
        $tauxConversion = $vues > 0 ? round(($commandes / $vues) * 100, 1) : 0;
        $noteMoyenne = rand(35, 50) / 10;

        return [
            'total_produits' => count($produitsIds),
            'commandes' => $commandes,
            'chiffre_affaires' => $chiffreAffaires ?: 0,
            'vues' => $vues,
            'taux_conversion' => $tauxConversion,
            'note_moyenne' => $noteMoyenne
        ];
    }

    private function getEvolutionVentes($produitsIds, $periode)
    {
        $labels = [];
        $ventesData = [];
        $commandesData = [];

        for ($i = $periode - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $labels[] = $date->format('d/m');

            $caJour = DB::table('commande_produits')
                ->join('commandes', 'commande_produits.commande_id', '=', 'commandes.id')
                ->whereIn('commande_produits.produit_id', $produitsIds)
                ->whereDate('commandes.created_at', $date->format('Y-m-d'))
                ->sum(DB::raw('commande_produits.quantite * commande_produits.prix_unitaire')) ?: 0;

            $commandesJour = DB::table('commande_produits')
                ->join('commandes', 'commande_produits.commande_id', '=', 'commandes.id')
                ->whereIn('commande_produits.produit_id', $produitsIds)
                ->whereDate('commandes.created_at', $date->format('Y-m-d'))
                ->distinct('commandes.id')
                ->count();

            $ventesData[] = $caJour;
            $commandesData[] = $commandesJour;
        }

        return [
            'labels' => $labels,
            'ventesData' => $ventesData,
            'commandesData' => $commandesData
        ];
    }

    private function getTopProduits($produitsIds, $dateDebut, $dateFin)
    {
        $topProduits = DB::table('commande_produits')
            ->join('commandes', 'commande_produits.commande_id', '=', 'commandes.id')
            ->join('produits', 'commande_produits.produit_id', '=', 'produits.id')
            ->whereIn('commande_produits.produit_id', $produitsIds)
            ->whereBetween('commandes.created_at', [$dateDebut, $dateFin])
            ->select('produits.nom', DB::raw('SUM(commande_produits.quantite) as total_vendu'))
            ->groupBy('produits.id', 'produits.nom')
            ->orderBy('total_vendu', 'desc')
            ->limit(5)
            ->get();

        if ($topProduits->isEmpty()) {
            return [
                'labels' => ['Tomates', 'Pommes de terre', 'Oignons', 'Carottes', 'Salade'],
                'data' => [25, 20, 18, 15, 12]
            ];
        }

        return [
            'labels' => $topProduits->pluck('nom')->toArray(),
            'data' => $topProduits->pluck('total_vendu')->toArray()
        ];
    }

    private function getRepartitionCategories($produitsIds)
    {
        $categories = DB::table('produits')
            ->whereIn('id', $produitsIds)
            ->select('categorie_id', DB::raw('COUNT(*) as count'))
            ->groupBy('categorie_id')
            ->get();

        if ($categories->isEmpty()) {
            return [
                'labels' => ['Légumes', 'Fruits', 'Céréales', 'Légumineuses'],
                'data' => [40, 30, 20, 10]
            ];
        }

        $labels = [];
        $data = [];
        foreach ($categories as $categorie) {
            $labels[] = 'Catégorie ' . $categorie->categorie_id;
            $data[] = $categorie->count;
        }

        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    private function getEvolutionStock($userId)
    {
        $labels = [];
        $data = [];

        for ($i = 3; $i >= 0; $i--) {
            $semaine = Carbon::now()->subWeeks($i);
            $labels[] = 'Semaine ' . ($i + 1);

            $valeurStock = Produit::where('user_id', $userId)
                ->sum(DB::raw('prix * quantite'));

            $variation = (rand(-20, 20) / 100) * $valeurStock;
            $data[] = max(0, $valeurStock + $variation);
        }

        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    private function getAlertesStock($userId)
    {
        return Produit::where('user_id', $userId)
            ->where(function ($query) {
                $query->where('quantite', 0)
                      ->orWhere('quantite', '<=', 10);
            })
            ->select('nom', 'quantite')
            ->limit(10)
            ->get()
            ->map(function ($produit) {
                return [
                    'nom' => $produit->nom,
                    'stock' => $produit->quantite,
                    'seuil' => 10,
                    'statut' => $produit->quantite == 0 ? 'Rupture' : 'Stock faible'
                ];
            });
    }

    private function getTopVentesDetails($produitsIds, $dateDebut, $dateFin)
    {
        return DB::table('commande_produits')
            ->join('commandes', 'commande_produits.commande_id', '=', 'commandes.id')
            ->join('produits', 'commande_produits.produit_id', '=', 'produits.id')
            ->whereIn('commande_produits.produit_id', $produitsIds)
            ->whereBetween('commandes.created_at', [$dateDebut, $dateFin])
            ->select(
                'produits.nom',
                DB::raw('SUM(commande_produits.quantite) as quantite_vendue'),
                DB::raw('SUM(commande_produits.quantite * commande_produits.prix_unitaire) as ca_genere')
            )
            ->groupBy('produits.id', 'produits.nom')
            ->orderBy('quantite_vendue', 'desc')
            ->limit(10)
            ->get();
    }
}
