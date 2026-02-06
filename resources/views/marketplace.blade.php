<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marketplace - FUBADJ</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome pour les icônes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #198754;
            --primary-dark: #157347;
            --secondary-color: #6c757d;
            --success-color: #198754;
            --warning-color: #ffc107;
            --light-bg: #f8f9fa;
            --dark-text: #212529;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--light-bg);
            color: var(--dark-text);
        }
        
        .navbar {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .navbar-brand {
            font-weight: 600;
            font-size: 1.3rem;
            color: var(--primary-color) !important;
        }
        
        .navbar-brand:hover {
            color: var(--primary-dark) !important;
        }
        
        .nav-link {
            color: var(--dark-text) !important;
        }
        
        .nav-link:hover,
        .nav-link.active {
            color: var(--primary-color) !important;
        }
        
        .navbar-toggler {
            border-color: var(--primary-color);
        }
        
        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba(25, 135, 84, 1)' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }
        
        .dropdown-menu {
            border: 1px solid #e9ecef;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .dropdown-item:hover {
            background-color: var(--light-bg);
            color: var(--primary-color);
        }
        
        .btn-outline-light {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-outline-light:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }
        
        .btn-light {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }
        
        .btn-light:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
            color: white;
        }
        
        .product-card {
            transition: all 0.3s ease;
            border: none;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            height: 100%;
            background: white;
            cursor: pointer;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.12);
        }
        
        .product-img {
            height: 220px;
            object-fit: cover;
            width: 100%;
        }
        
        .product-price {
            color: var(--success-color);
            font-weight: bold;
            font-size: 1.4rem;
        }
        
        .badge-stock {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 0.75rem;
            z-index: 10;
        }
        
        .category-filter {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            position: sticky;
            top: 20px;
        }
        
        .category-filter h5 {
            color: var(--dark-text);
            border-bottom: 2px solid var(--primary-color);
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        
        .product-description {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            color: #6c757d;
            font-size: 0.9rem;
        }
        
        .search-bar {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        
        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .page-header {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            padding: 3rem 0;
            margin-bottom: 2rem;
        }
        
        .stats-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            text-align: center;
            margin-bottom: 1rem;
        }
        
        .stats-card i {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }
        
        .stats-card h3 {
            font-size: 2rem;
            font-weight: bold;
            color: var(--dark-text);
            margin: 0;
        }
        
        .stats-card p {
            color: #6c757d;
            margin: 0;
        }

        .btn-view-product {
            background: var(--success-color);
            border: none;
            color: white;
            transition: all 0.3s;
            width: 100%;
        }
        
        .btn-view-product:hover {
            background: var(--primary-dark);
            color: white;
        }

        .login-prompt {
            background: linear-gradient(135deg, #ffc107, #ff9800);
            color: white;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 12px rgba(255, 193, 7, 0.3);
        }

        .login-prompt h5 {
            margin-bottom: 1rem;
        }

        .category-badge {
            background: var(--primary-color);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            display: inline-block;
            margin-bottom: 0.5rem;
        }
        
        @media (max-width: 768px) {
            .page-header {
                padding: 2rem 0;
            }
            
            .category-filter {
                position: relative;
                top: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="fas fa-seedling me-2"></i>FUBADJ
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/') }}">
                            <i class="fas fa-home me-1"></i>Accueil
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('marketplace.index') }}">
                            <i class="fas fa-store me-1"></i>Marketplace
                        </a>
                    </li>
                </ul>

                <ul class="navbar-nav ms-auto">
                    @auth
                        <!-- Utilisateur connecté -->
                        @if(Auth::user()->hasRole('acheteur'))
                            <li class="nav-item">
                                <a href="{{ route('dashboard.acheteur') }}" class="btn btn-success btn-sm me-2">
                                    <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                                </a>
                            </li>
                        @elseif(Auth::user()->hasRole('producteur'))
                            <li class="nav-item">
                                <a href="{{ route('dashboard.producteur') }}" class="btn btn-success btn-sm me-2">
                                    <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                                </a>
                            </li>
                        @endif
                        
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle me-1"></i>{{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="{{ route('profile.show') }}">
                                        <i class="fas fa-user me-2"></i>Mon profil
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button class="dropdown-item" type="submit">
                                            <i class="fas fa-sign-out-alt me-2"></i>Déconnexion
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <!-- Utilisateur non connecté -->
                        <li class="nav-item">
                            <a href="{{ route('login') }}" class="btn btn-outline-success btn-sm me-2">
                                <i class="fas fa-sign-in-alt me-1"></i>Connexion
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('register') }}" class="btn btn-success btn-sm">
                                <i class="fas fa-user-plus me-1"></i>S'inscrire
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- En-tête -->
    <div class="page-header">
        <div class="container">
            <h1 class="display-5 fw-bold mb-2">
                <i class="fas fa-store me-3"></i>Marketplace
            </h1>
            <p class="lead mb-0">Découvrez nos produits frais et locaux du terroir africain</p>
        </div>
    </div>

    <!-- Contenu principal -->
    <div class="container pb-5">
        <!-- Message pour les visiteurs non connectés -->
        @guest
        <div class="login-prompt">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h5 class="mb-2">
                        <i class="fas fa-shopping-cart me-2"></i>Envie d'acheter nos produits ?
                    </h5>
                    <p class="mb-0">
                        Créez un compte acheteur pour pouvoir commander et profiter de nos meilleurs produits locaux !
                    </p>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <a href="{{ route('register') }}" class="btn btn-light btn-lg me-2">
                        <i class="fas fa-user-plus me-2"></i>S'inscrire
                    </a>
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-sign-in-alt me-2"></i>Se connecter
                    </a>
                </div>
            </div>
        </div>
        @endguest

        <!-- Statistiques -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="stats-card">
                    <i class="fas fa-box"></i>
                    <h3>{{ $produits->total() }}</h3>
                    <p>Produits disponibles</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card">
                    <i class="fas fa-users"></i>
                    <h3>{{ $categories_count ?? 0 }}</h3>
                    <p>Catégories</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card">
                    <i class="fas fa-leaf"></i>
                    <h3>100%</h3>
                    <p>Produits locaux</p>
                </div>
            </div>
        </div>

        <!-- Filtres et produits -->
        <div class="row">
            <!-- Sidebar Filtres -->
            <div class="col-lg-3 mb-4">
                <form method="GET" action="{{ route('marketplace.index') }}">
                    <!-- Filtre Catégories -->
                    <div class="category-filter mb-3">
                        <h5>
                            <i class="fas fa-filter me-2 text-success"></i>Catégories
                        </h5>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="categorie" value="" id="allCategories" 
                                   {{ request('categorie') == '' ? 'checked' : '' }}
                                   onchange="this.form.submit()">
                            <label class="form-check-label" for="allCategories">
                                <strong>Toutes les catégories</strong>
                            </label>
                        </div>
                        @if(isset($categories) && count($categories) > 0)
                            @foreach($categories as $cat)
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="categorie" 
                                           value="{{ $cat }}" id="cat{{ $loop->index }}"
                                           {{ request('categorie') == $cat ? 'checked' : '' }}
                                           onchange="this.form.submit()">
                                    <label class="form-check-label" for="cat{{ $loop->index }}">
                                        {{ $cat }}
                                    </label>
                                </div>
                            @endforeach
                        @endif
                    </div>

                    <!-- Filtre Prix -->
                    <div class="category-filter">
                        <h5>
                            <i class="fas fa-coins me-2 text-success"></i>Prix
                        </h5>
                        <div class="mb-3">
                            <label class="form-label small">Prix minimum (FCFA)</label>
                            <input type="number" class="form-control form-control-sm" name="min_prix" 
                                   value="{{ request('min_prix') }}" placeholder="0">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small">Prix maximum (FCFA)</label>
                            <input type="number" class="form-control form-control-sm" name="max_prix" 
                                   value="{{ request('max_prix') }}" placeholder="100000">
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-sm">
                                <i class="fas fa-check me-2"></i>Appliquer
                            </button>
                            <a href="{{ route('marketplace.index') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-redo me-2"></i>Réinitialiser
                            </a>
                        </div>
                    </div>
                    
                    <!-- Conserver les autres paramètres -->
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <input type="hidden" name="sort" value="{{ request('sort') }}">
                </form>
            </div>
            
            <!-- Grille de produits -->
            <div class="col-lg-9">
                <!-- Barre de recherche et tri -->
                <div class="row mb-4">
                    <div class="col-md-8 mb-3 mb-md-0">
                        <form method="GET" action="{{ route('marketplace.index') }}" class="search-bar">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="fas fa-search text-success"></i>
                                </span>
                                <input type="text" class="form-control border-start-0" name="search" 
                                       value="{{ request('search') }}" placeholder="Rechercher un produit...">
                                <button class="btn btn-success" type="submit">
                                    <i class="fas fa-search me-1"></i> Rechercher
                                </button>
                            </div>
                            <input type="hidden" name="categorie" value="{{ request('categorie') }}">
                            <input type="hidden" name="min_prix" value="{{ request('min_prix') }}">
                            <input type="hidden" name="max_prix" value="{{ request('max_prix') }}">
                            <input type="hidden" name="sort" value="{{ request('sort') }}">
                        </form>
                    </div>
                    <div class="col-md-4">
                        <form method="GET" action="{{ route('marketplace.index') }}" id="sortForm">
                            <select class="form-select" name="sort" onchange="this.form.submit()">
                                <option value="recent" {{ request('sort') == 'recent' ? 'selected' : '' }}>Plus récents</option>
                                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Prix croissant</option>
                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Prix décroissant</option>
                                <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nom A-Z</option>
                            </select>
                            <input type="hidden" name="search" value="{{ request('search') }}">
                            <input type="hidden" name="categorie" value="{{ request('categorie') }}">
                            <input type="hidden" name="min_prix" value="{{ request('min_prix') }}">
                            <input type="hidden" name="max_prix" value="{{ request('max_prix') }}">
                        </form>
                    </div>
                </div>
                
                <!-- Grille de produits -->
                <div class="row">
                    @forelse($produits as $produit)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card product-card h-100">
                            @if($produit->quantite > 0)
                                <span class="badge bg-success badge-stock">
                                    <i class="fas fa-check-circle me-1"></i>En stock
                                </span>
                            @else
                                <span class="badge bg-danger badge-stock">
                                    <i class="fas fa-times-circle me-1"></i>Rupture
                                </span>
                            @endif
                            
                            @if($produit->image_url)
                                <img src="{{ $produit->image_url }}" class="product-img" alt="{{ $produit->nom }}" onerror="this.onerror=null; this.src='{{ asset('images/default-product.png') }}';">
                            @else
                                <div class="product-img bg-light d-flex align-items-center justify-content-center">
                                    <i class="fas fa-image fa-3x text-muted"></i>
                                </div>
                            @endif
                            
                            <div class="card-body d-flex flex-column">
                                @if($produit->categorie)
                                <span class="category-badge">{{ $produit->categorie }}</span>
                                @endif
                                
                                <h5 class="card-title mb-2">{{ $produit->nom }}</h5>
                                
                                <p class="card-text product-description flex-grow-1">
                                    {{ Str::limit($produit->description, 100) }}
                                </p>
                                
                                @if($produit->user)
                                <div class="mb-2">
                                    <small class="text-muted">
                                        <i class="fas fa-user me-1"></i>{{ $produit->user->name }}
                                    </small>
                                </div>
                                @endif
                                
                                <div class="d-flex justify-content-between align-items-center mt-auto">
                                    <div class="product-price">
                                        {{ number_format($produit->prix, 0, ',', ' ') }} FCFA
                                    </div>
                                    <small class="text-muted">
                                        <i class="fas fa-box me-1"></i>{{ $produit->quantite }} unités
                                    </small>
                                </div>
                                
                                <a href="{{ route('marketplace.show', $produit->id) }}" class="btn btn-view-product mt-3">
                                    <i class="fas fa-eye me-2"></i>Voir le produit
                                </a>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12">
                        <div class="text-center py-5">
                            <i class="fas fa-search fa-3x text-muted mb-3"></i>
                            <h4 class="text-dark">Aucun produit trouvé</h4>
                            <p class="text-muted mb-3">Essayez de modifier vos critères de recherche</p>
                            <a href="{{ route('marketplace.index') }}" class="btn btn-outline-success">
                                <i class="fas fa-redo me-2"></i>Voir tous les produits
                            </a>
                        </div>
                    </div>
                    @endforelse
                </div>
                
                <!-- Pagination -->
                @if($produits->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $produits->withQueryString()->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-3 mb-md-0">
                    <h5><i class="fas fa-seedling me-2"></i>FUBADJ</h5>
                    <p class="text-muted">Le marché digital des terroirs africains</p>
                </div>
                <div class="col-md-4 mb-3 mb-md-0">
                    <h6>Liens rapides</h6>
                    <ul class="list-unstyled">
                        <li><a href="{{ url('/') }}" class="text-muted text-decoration-none">Accueil</a></li>
                        <li><a href="{{ route('marketplace.index') }}" class="text-muted text-decoration-none">Marketplace</a></li>
                        <li><a href="{{ route('register') }}" class="text-muted text-decoration-none">S'inscrire</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h6>Contact</h6>
                    <p class="text-muted mb-1"><i class="fas fa-envelope me-2"></i>contact@fubadj.com</p>
                    <p class="text-muted"><i class="fas fa-phone me-2"></i>+221 XX XXX XX XX</p>
                </div>
            </div>
            <hr class="my-3 bg-secondary">
            <p class="text-center text-muted mb-0">&copy; {{ date('Y') }} FUBADJ. Tous droits réservés.</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>