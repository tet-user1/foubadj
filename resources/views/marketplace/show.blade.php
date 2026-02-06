<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $produit->nom }} - FUBADJ Marketplace</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700;800&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #198754;
            --primary-dark: #157347;
            --secondary-color: #6c757d;
            --accent-color: #ffc107;
            --light-bg: #f8f9fa;
            --dark-text: #212529;
            --border-color: #e9ecef;
        }
        
        body {
            font-family: 'Lato', sans-serif;
            background-color: var(--light-bg);
            color: var(--dark-text);
        }
        
        h1, h2, h3, h4, h5 {
            font-family: 'Playfair Display', serif;
        }
        
        /* Navigation */
        .navbar {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.3rem;
            color: var(--primary-color) !important;
            font-family: 'Playfair Display', serif;
        }
        
        .nav-link {
            color: var(--dark-text) !important;
            transition: color 0.3s;
        }
        
        .nav-link:hover,
        .nav-link.active {
            color: var(--primary-color) !important;
        }
        
        /* Breadcrumb */
        .breadcrumb {
            background: transparent;
            padding: 1rem 0;
        }
        
        .breadcrumb-item + .breadcrumb-item::before {
            color: var(--primary-color);
        }
        
        .breadcrumb-item a {
            color: var(--primary-color);
            text-decoration: none;
            transition: opacity 0.3s;
        }
        
        .breadcrumb-item a:hover {
            opacity: 0.7;
        }
        
        /* Product Image Gallery */
        .product-image-container {
            position: relative;
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            height: 500px;
        }
        
        .product-main-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .product-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }
        
        .badge-stock-detail {
            position: absolute;
            top: 20px;
            right: 20px;
            font-size: 0.9rem;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }
        
        /* Product Info Card */
        .product-info-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            position: sticky;
            top: 20px;
        }
        
        .product-category-badge {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            padding: 0.4rem 1rem;
            border-radius: 20px;
            font-size: 0.85rem;
            display: inline-block;
            margin-bottom: 1rem;
        }
        
        .product-title {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--dark-text);
            margin-bottom: 1rem;
            line-height: 1.2;
        }
        
        .product-price-display {
            font-size: 3rem;
            font-weight: 700;
            color: var(--primary-color);
            margin: 1.5rem 0;
            font-family: 'Playfair Display', serif;
        }
        
        .price-label {
            font-size: 0.9rem;
            color: var(--secondary-color);
            font-weight: 400;
            display: block;
            margin-bottom: 0.5rem;
        }
        
        .product-meta {
            display: flex;
            gap: 2rem;
            padding: 1.5rem 0;
            border-top: 2px solid var(--border-color);
            border-bottom: 2px solid var(--border-color);
            margin: 1.5rem 0;
        }
        
        .meta-item {
            display: flex;
            flex-direction: column;
        }
        
        .meta-label {
            font-size: 0.8rem;
            color: var(--secondary-color);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 0.3rem;
        }
        
        .meta-value {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--dark-text);
        }
        
        .producer-info {
            background: var(--light-bg);
            padding: 1.5rem;
            border-radius: 10px;
            margin: 1.5rem 0;
        }
        
        .producer-info h6 {
            font-size: 0.85rem;
            text-transform: uppercase;
            color: var(--secondary-color);
            margin-bottom: 0.5rem;
            letter-spacing: 1px;
        }
        
        .producer-name {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--primary-color);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        /* Quantity Selector */
        .quantity-selector {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin: 1.5rem 0;
        }
        
        .quantity-label {
            font-weight: 600;
            color: var(--dark-text);
        }
        
        .quantity-controls {
            display: flex;
            align-items: center;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            overflow: hidden;
        }
        
        .quantity-btn {
            background: white;
            border: none;
            padding: 0.5rem 1rem;
            cursor: pointer;
            font-size: 1.2rem;
            color: var(--primary-color);
            transition: all 0.3s;
        }
        
        .quantity-btn:hover {
            background: var(--light-bg);
        }
        
        .quantity-input {
            border: none;
            width: 60px;
            text-align: center;
            font-weight: 600;
            font-size: 1.1rem;
            padding: 0.5rem;
        }
        
        .quantity-input:focus {
            outline: none;
        }
        
        /* Action Buttons */
        .btn-add-cart {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            border: none;
            color: white;
            padding: 1rem 2rem;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 10px;
            width: 100%;
            margin-top: 1rem;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(25, 135, 84, 0.3);
        }
        
        .btn-add-cart:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(25, 135, 84, 0.4);
            color: white;
        }
        
        .btn-login-required {
            background: linear-gradient(135deg, #ffc107, #ff9800);
            border: none;
            color: white;
            padding: 1rem 2rem;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 10px;
            width: 100%;
            margin-top: 1rem;
            transition: all 0.3s;
        }
        
        .btn-login-required:hover {
            transform: translateY(-2px);
            color: white;
        }
        
        /* Product Details Tabs */
        .product-details-section {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            margin-top: 3rem;
        }
        
        .section-title {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: var(--dark-text);
        }
        
        .product-description {
            font-size: 1.05rem;
            line-height: 1.8;
            color: #495057;
        }
        
        /* Similar Products */
        .similar-products-section {
            margin-top: 4rem;
            padding: 3rem 0;
            background: white;
        }
        
        .product-card {
            transition: all 0.3s ease;
            border: none;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            height: 100%;
            background: white;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        
        .product-card-img {
            height: 200px;
            object-fit: cover;
            width: 100%;
        }
        
        .product-card-body {
            padding: 1.5rem;
        }
        
        .product-card-title {
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .product-card-price {
            color: var(--primary-color);
            font-size: 1.3rem;
            font-weight: 700;
            font-family: 'Playfair Display', serif;
        }
        
        /* Alert Messages */
        .alert-custom {
            border-radius: 10px;
            border: none;
            padding: 1rem 1.5rem;
        }
        
        .alert-warning-custom {
            background: linear-gradient(135deg, #fff3cd, #ffe69c);
            color: #856404;
        }
        
        /* Footer */
        footer {
            background: #212529;
            color: white;
            margin-top: 5rem;
        }
        
        footer a {
            color: #adb5bd;
            text-decoration: none;
            transition: color 0.3s;
        }
        
        footer a:hover {
            color: var(--primary-color);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .product-title {
                font-size: 2rem;
            }
            
            .product-price-display {
                font-size: 2.2rem;
            }
            
            .product-info-card {
                position: relative;
                top: 0;
                margin-top: 2rem;
            }
            
            .product-image-container {
                height: 350px;
            }
        }
        
        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-fade-in {
            animation: fadeInUp 0.6s ease-out;
        }
        
        .animate-fade-in-delay {
            animation: fadeInUp 0.6s ease-out 0.2s backwards;
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

    <!-- Breadcrumb -->
    <div class="container mt-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Accueil</a></li>
                <li class="breadcrumb-item"><a href="{{ route('marketplace.index') }}">Marketplace</a></li>
                @if($produit->categorie)
                    <li class="breadcrumb-item"><a href="{{ route('marketplace.index', ['categorie' => $produit->categorie]) }}">{{ $produit->categorie }}</a></li>
                @endif
                <li class="breadcrumb-item active" aria-current="page">{{ $produit->nom }}</li>
            </ol>
        </nav>
    </div>

    <!-- Product Details -->
    <div class="container my-5">
        <div class="row">
            <!-- Product Image -->
            <div class="col-lg-6 mb-4 animate-fade-in">
                <div class="product-image-container">
                    @if($produit->quantite > 0)
                        <span class="badge bg-success badge-stock-detail">
                            <i class="fas fa-check-circle me-1"></i>En stock ({{ $produit->quantite }} unités)
                        </span>
                    @else
                        <span class="badge bg-danger badge-stock-detail">
                            <i class="fas fa-times-circle me-1"></i>Rupture de stock
                        </span>
                    @endif
                    
                    @if($produit->image_url)
                        <img src="{{ $produit->image_url }}" class="product-main-image" alt="{{ $produit->nom }}" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="product-placeholder" style="display: none;">
                            <i class="fas fa-image fa-5x text-muted"></i>
                        </div>
                    @else
                        <div class="product-placeholder">
                            <i class="fas fa-image fa-5x text-muted"></i>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Product Info -->
            <div class="col-lg-6 animate-fade-in-delay">
                <div class="product-info-card">
                    @if($produit->categorie)
                        <span class="product-category-badge">
                            <i class="fas fa-tag me-1"></i>{{ $produit->categorie }}
                        </span>
                    @endif
                    
                    <h1 class="product-title">{{ $produit->nom }}</h1>
                    
                    <div class="product-price-display">
                        <span class="price-label">Prix</span>
                        {{ number_format($produit->prix, 0, ',', ' ') }} <small style="font-size: 1.5rem;">FCFA</small>
                    </div>
                    
                    <div class="product-meta">
                        <div class="meta-item">
                            <span class="meta-label">Disponibilité</span>
                            <span class="meta-value">
                                @if($produit->quantite > 0)
                                    <i class="fas fa-check-circle text-success me-1"></i>{{ $produit->quantite }} unités
                                @else
                                    <i class="fas fa-times-circle text-danger me-1"></i>Épuisé
                                @endif
                            </span>
                        </div>
                    </div>
                    
                    @if($produit->user)
                        <div class="producer-info">
                            <h6>Producteur</h6>
                            <div class="producer-name">
                                <i class="fas fa-user-circle"></i>
                                {{ $produit->user->name }}
                            </div>
                        </div>
                    @endif
                    
                    @if($produit->quantite > 0)
                        @if($isAcheteur)
                            <!-- Quantity Selector -->
                            <form action="{{ route('panier.add', $produit->id) }}" method="POST">
                                @csrf
                                <div class="quantity-selector">
                                    <label class="quantity-label">Quantité :</label>
                                    <div class="quantity-controls">
                                        <button type="button" class="quantity-btn" onclick="decrementQuantity()">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                        <input type="number" name="quantite" id="quantite" class="quantity-input" value="1" min="1" max="{{ $produit->quantite }}" required>
                                        <button type="button" class="quantity-btn" onclick="incrementQuantity({{ $produit->quantite }})">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                
                                <button type="submit" class="btn btn-add-cart">
                                    <i class="fas fa-shopping-cart me-2"></i>Ajouter au panier
                                </button>
                            </form>
                        @elseif($isAuthenticated)
                            <div class="alert alert-custom alert-warning-custom mt-3">
                                <i class="fas fa-info-circle me-2"></i>
                                Seuls les acheteurs peuvent commander des produits.
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-login-required">
                                <i class="fas fa-sign-in-alt me-2"></i>Connectez-vous pour commander
                            </a>
                        @endif
                    @else
                        <div class="alert alert-custom alert-warning-custom mt-3">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Ce produit est actuellement en rupture de stock.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Product Description -->
        <div class="row">
            <div class="col-12">
                <div class="product-details-section animate-fade-in">
                    <h2 class="section-title">
                        <i class="fas fa-info-circle me-2 text-success"></i>Description du produit
                    </h2>
                    <div class="product-description">
                        {{ $produit->description ?? 'Aucune description disponible pour ce produit.' }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Similar Products -->
    @if($produitsSimilaires && count($produitsSimilaires) > 0)
    <div class="similar-products-section">
        <div class="container">
            <h2 class="section-title text-center mb-4">
                <i class="fas fa-boxes me-2 text-success"></i>Produits similaires
            </h2>
            
            <div class="row">
                @foreach($produitsSimilaires as $similaire)
                <div class="col-md-6 col-lg-3 mb-4">
                    <a href="{{ route('marketplace.show', $similaire->id) }}" class="text-decoration-none">
                        <div class="card product-card">
                            @if($similaire->image_url)
                                <img src="{{ $similaire->image_url }}" class="product-card-img" alt="{{ $similaire->nom }}" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div class="product-placeholder" style="display: none; height: 200px;">
                                    <i class="fas fa-image fa-3x text-muted"></i>
                                </div>
                            @else
                                <div class="product-placeholder" style="height: 200px;">
                                    <i class="fas fa-image fa-3x text-muted"></i>
                                </div>
                            @endif
                            
                            <div class="product-card-body">
                                <h5 class="product-card-title text-dark">{{ $similaire->nom }}</h5>
                                <p class="product-card-price mb-2">{{ number_format($similaire->prix, 0, ',', ' ') }} FCFA</p>
                                <small class="text-muted">
                                    <i class="fas fa-box me-1"></i>{{ $similaire->quantite }} unités
                                </small>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Footer -->
    <footer class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-3 mb-md-0">
                    <h5><i class="fas fa-seedling me-2"></i>FUBADJ</h5>
                    <p class="text-muted">Le marché digital des terroirs africains</p>
                </div>
                <div class="col-md-4 mb-3 mb-md-0">
                    <h6>Liens rapides</h6>
                    <ul class="list-unstyled">
                        <li><a href="{{ url('/') }}">Accueil</a></li>
                        <li><a href="{{ route('marketplace.index') }}">Marketplace</a></li>
                        <li><a href="{{ route('register') }}">S'inscrire</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h6>Contact</h6>
                    <p class="text-muted mb-1"><i class="fas fa-envelope me-2"></i>contact@fubadj.com</p>
                    <p class="text-muted"><i class="fas fa-phone me-2"></i>+221 76 473 31 57</p>
                </div>
            </div>
            <hr class="my-3 bg-secondary">
            <p class="text-center text-muted mb-0">&copy; {{ date('Y') }} FUBADJ. Tous droits réservés.</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function incrementQuantity(max) {
            const input = document.getElementById('quantite');
            const currentValue = parseInt(input.value);
            if (currentValue < max) {
                input.value = currentValue + 1;
            }
        }
        
        function decrementQuantity() {
            const input = document.getElementById('quantite');
            const currentValue = parseInt(input.value);
            if (currentValue > 1) {
                input.value = currentValue - 1;
            }
        }
    </script>
</body>
</html>