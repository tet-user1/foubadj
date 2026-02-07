<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\CatalogueController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PanierController;
use App\Http\Controllers\PaiementController;
use App\Http\Controllers\FinancementController;
use App\Http\Controllers\MarketplaceController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Page d'accueil
Route::get('/', function () {
    return view('welcome');
});

// ========================================
// AUTHENTIFICATION
// ========================================
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// Inscription
Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register', [RegisteredUserController::class, 'store']);

// ========================================
// ROUTES ADMIN (retiré 'verified')
// ========================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/users', [AdminController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [AdminController::class, 'show'])->name('users.show');
    Route::patch('/users/{user}/role', [AdminController::class, 'updateRole'])->name('users.update-role');
    Route::patch('/users/{user}/verify-email', [AdminController::class, 'verifyEmail'])->name('users.verify-email');
    Route::patch('/users/{user}/toggle-status', [AdminController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::delete('/users/{user}', [AdminController::class, 'destroy'])->name('users.destroy');
});

// ========================================
// PROFILE (tous les utilisateurs authentifiés)
// ========================================
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ========================================
// ROUTES ACHETEUR
// ========================================
Route::middleware(['auth', 'role:acheteur'])->group(function () {
    Route::get('/dashboard-acheteur', [DashboardController::class, 'acheteur'])->name('dashboard.acheteur');
    Route::get('/catalogue', [CatalogueController::class, 'index'])->name('catalogue.index');
});

// ========================================
// ROUTES PRODUCTEUR
// ========================================
Route::middleware(['auth', 'role:producteur'])->group(function () {
    // Dashboard
    Route::get('/dashboard-producteur', [DashboardController::class, 'producteur'])->name('dashboard.producteur');

    // Mes produits
    Route::get('/mes-produits', [ProduitController::class, 'indexView'])->name('mes.produits');

    // Produits CRUD
    Route::get('/produits', [ProduitController::class, 'index'])->name('produits.index');
    Route::get('/produits/create', [ProduitController::class, 'create'])->name('produits.create');
    Route::post('/produits', [ProduitController::class, 'store'])->name('produits.store');
    Route::get('/produits/{produit}', [ProduitController::class, 'show'])->name('produits.show');
    Route::get('/produits/{produit}/edit', [ProduitController::class, 'edit'])->name('produits.edit');
    Route::put('/produits/{produit}', [ProduitController::class, 'update'])->name('produits.update');
    Route::delete('/produits/{produit}', [ProduitController::class, 'destroy'])->name('produits.destroy');

    // API / stats
    Route::get('/api/produits-stats', [ProduitController::class, 'getStats'])->name('produits.stats');
});

// ========================================
// CATALOGUE PUBLIC
// ========================================
Route::get('/catalogue', [CatalogueController::class, 'index'])->name('catalogue.index');
Route::get('/catalogue/produit/{id}', [CatalogueController::class, 'show'])->name('catalogue.show');

// Liste des produits du marketplace
Route::get('/marketplace', [MarketplaceController::class, 'index'])
    ->name('marketplace.index');

// Détails d'un produit (accessible à tous)
Route::get('/marketplace/produit/{id}', [MarketplaceController::class, 'show'])
    ->name('marketplace.show');


    
// API Catalogue
Route::prefix('api/catalogue')->group(function () {
    Route::get('/search', [CatalogueController::class, 'search'])->name('api.catalogue.search');
    Route::get('/filter', [CatalogueController::class, 'filter'])->name('api.catalogue.filter');
});

// ========================================
// PANIER
// ========================================
// routes/web.php - Dans le groupe de routes du panier
Route::prefix('panier')->group(function () {
    Route::get('/', [PanierController::class, 'index'])->name('panier.index');
    
    // Ajoutez cette route pour l'API GET
    Route::get('/get', [PanierController::class, 'getPanier'])->name('panier.get');
    
    // Vos autres routes existantes
    Route::post('/ajouter/{id}', [PanierController::class, 'ajouter'])->name('panier.ajouter');
    Route::delete('/supprimer/{id}', [PanierController::class, 'supprimer'])->name('panier.supprimer');
    Route::post('/vider', [PanierController::class, 'vider'])->name('panier.vider');
    Route::put('/mettre-a-jour/{id}', [PanierController::class, 'mettreAJour'])->name('panier.mettre-a-jour');
    Route::get('/recapitulatif', [PanierController::class, 'recapitulatif'])->name('panier.recapitulatif');
    Route::post('/synchroniser', [PanierController::class, 'synchroniser'])->name('panier.synchroniser');
    Route::post('/checkout', [PanierController::class, 'checkout'])->name('panier.checkout');
    // Dans le groupe de routes du panier, ajoutez :
Route::post('/update-session', [PanierController::class, 'updateSession'])->name('panier.updateSession');

  // Ajoutez ces routes pour la gestion de session
    Route::post('/store-session', [PanierController::class, 'storeInSession'])->name('panier.storeInSession');
    Route::post('/update-session', [PanierController::class, 'updateSession'])->name('panier.updateSession');
    Route::get('/get-session', [PanierController::class, 'getSession'])->name('panier.getSession'); // <-- Celle-ci est manquante
    Route::post('/synchroniser', [PanierController::class, 'synchroniser'])->name('panier.synchroniser');


});

// ========================================
// COMMANDES
// ========================================
Route::middleware(['auth'])->group(function () {
    // Liste des commandes
    Route::get('/commandes', [CommandeController::class, 'index'])->name('commandes.index');
    
    // Routes spécifiques (AVANT les routes dynamiques)
    Route::get('/commandes/nouvelle', [CommandeController::class, 'showOrderForm'])->name('commandes.nouvelle');
    Route::get('/commandes/historique', [CommandeController::class, 'showUserOrders'])->name('commandes.history');
    Route::post('/commandes/traiter', [CommandeController::class, 'processOrder'])->name('commandes.traiter');
    
    // Routes dynamiques (EN DERNIER)
    Route::get('/commandes/{commande}/confirmation', [CommandeController::class, 'showOrderConfirmation'])->name('commandes.confirmation');
    Route::get('/commandes/{commande}', [CommandeController::class, 'showOrderDetails'])->name('commandes.show');
});

// ========================================
// PAIEMENT
// ========================================

// ========================================
// PAIEMENT
// ========================================

// Page de checkout
Route::get('/paiement/checkout', [PaiementController::class, 'showCheckout'])
    ->middleware('auth')
    ->name('paiement.checkout');

// Traitement PayTech (c'est la route qui manque !)
Route::post('/paiement/paytech/process', [PaiementController::class, 'processPaytech'])
    ->middleware('auth')
    ->name('paiement.paytech.process');

// Pages publiques paiement
Route::get('/paiement/success', [PaiementController::class, 'success'])->name('paiement.success');
Route::get('/paiement/cancel', [PaiementController::class, 'cancel'])->name('paiement.cancel');

// Webhook PayTech
Route::post('/webhook/paytech', [PaiementController::class, 'callback'])->name('paiement.callback');
// ========================================
// STATISTIQUES
// ========================================
Route::middleware(['auth'])->group(function () {
    Route::get('/statistics', [DashboardController::class, 'statistics'])->name('statistics');
    Route::get('/api/produits-stats', [DashboardController::class, 'getProduitsStats'])->name('api.produits-stats');
    Route::get('/api/advanced-stats', [DashboardController::class, 'getAdvancedStats'])->name('api.advanced-stats');
});




//==========================================
//pour voir le profil de l'utilisateur
//==========================================

Route::middleware(['auth'])->group(function () {
    Route::get('/mon-profil', [ProfileController::class, 'show'])->name('profile.show');
    // Vos autres routes de profil...
});



//===========================================
// routes pour les finances
//===========================================

// Route pour les utilisateurs (producteurs et acheteurs) - sauf admin
Route::middleware(['auth'])->group(function () {
    Route::middleware(['checkRole:producteur,acheteur'])->group(function () {
        Route::get('/financement', [FinancementController::class, 'index'])->name('financement.index');
        // ... autres routes de financement pour les utilisateurs
    });
});

Route::middleware(['auth'])->group(function () {
    // IMPORTANT : L'ORDRE EST CRUCIEL !
    
    // 1. Route spécifique /demande (DOIT ÊTRE AVANT /{id})
    Route::get('/financement/demande', [FinancementController::class, 'demande'])->name('financement.demande');
    
    // 2. Route avec paramètre
    Route::get('/financement/{id}', [FinancementController::class, 'show'])->name('financement.show');
    
    // 3. Route d'accueil (DOIT ÊTRE EN DERNIER)
    Route::get('/financement', [FinancementController::class, 'index'])->name('financement.index');
    
    // 4. Autres routes (ordre moins important)
    Route::get('/financement/historique', [FinancementController::class, 'historique'])->name('financement.historique');
    Route::post('/financement/{id}/annuler', [FinancementController::class, 'annuler'])->name('financement.annuler');
    Route::post('/financement', [FinancementController::class, 'store'])->name('financement.store');
    
    // 5. Ancienne route pour compatibilité
    Route::get('/financement/creer', [FinancementController::class, 'create'])->name('financement.create');





    
    Route::get('/financement/suivi', [FinancementController::class, 'suivi'])
    ->name('financement.suivi');
    Route::get('/financement/{id}', [FinancementController::class, 'show'])
    ->name('financement.show');


    // Dans web.php
Route::get('/financement/{id}/edit', [FinancementController::class, 'edit'])
    ->name('financement.edit');
Route::delete('/financement/{id}', [FinancementController::class, 'destroy'])
    ->name('financement.destroy');
Route::get('/financement/{id}/contrat', [FinancementController::class, 'contrat'])
    ->name('financement.contrat');
Route::get('/financement/{id}/echeancier', [FinancementController::class, 'echeancier'])
    ->name('financement.echeancier');
Route::get('/financement/{id}/imprimer', [FinancementController::class, 'imprimer'])
    ->name('financement.imprimer');

});




// Route temporaire pour créer un admin - À SUPPRIMER APRÈS UTILISATION
Route::get('/create-admin-secret-route-12345', function () {
    // Vérifier si l'admin existe déjà
    $existingAdmin = App\Models\User::where('email', 'tetedieme350@gmail.com')->first();
    
    if ($existingAdmin) {
        return 'Un admin avec cet email existe déjà !';
    }
    
    $admin = App\Models\User::create([
        'name' => 'Admin-sana',
        'email' => 'fubadjmarketplace@gmail.com',
        'password' => bcrypt('1999&fubadj'),
        'role' => 'admin',
        'telephone' => '782146164',
        'adresse' => 'Dakar, Sénégal',
        'email_verified_at' => now(),
    ]);
    
    return 'Admin créé avec succès ! Email: ' . $admin->email . ' - MOT DE PASSE SUPPRIMÉ POUR SÉCURITÉ - Supprimez maintenant cette route !';
});
