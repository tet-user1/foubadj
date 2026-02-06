<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalogue Produits - FUBADJ</title>
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
            --info-color: #0dcaf0;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --light-bg: #f8f9fa;
            --dark-text: #212529;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--light-bg);
            color: var(--dark-text);
        }
        
        .navbar {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .navbar-brand {
            font-weight: 600;
            font-size: 1.3rem;
        }
        
        .product-card {
            transition: all 0.3s ease;
            border: none;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            height: 100%;
            background: white;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.12);
        }
        
        .product-img {
            height: 200px;
            object-fit: cover;
            width: 100%;
        }
        
        .product-price {
            color: var(--success-color);
            font-weight: bold;
            font-size: 1.3rem;
        }
        
        .badge-stock {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 0.75rem;
        }
        
        .quantity-control {
            width: 120px;
        }
        
        .cart-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            font-size: 0.7rem;
        }
        
        .category-filter {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        
        .category-filter h5 {
            color: var(--dark-text);
            border-bottom: 2px solid var(--primary-color);
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        
        .cart-sidebar {
            position: fixed;
            right: -400px;
            top: 0;
            width: 380px;
            height: 100vh;
            background: white;
            box-shadow: -5px 0 15px rgba(0,0,0,0.2);
            transition: right 0.3s ease;
            z-index: 1050;
        }
        
        .cart-sidebar.open {
            right: 0;
        }
        
        .cart-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1040;
            display: none;
        }
        
        .cart-overlay.active {
            display: block;
        }
        
        .product-description {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            color: #6c757d;
            font-size: 0.9rem;
        }
        
        .btn-add-to-cart {
            background: var(--success-color);
            border: none;
            color: white;
            transition: all 0.3s;
        }
        
        .btn-add-to-cart:hover {
            background: var(--primary-dark);
            transform: scale(1.05);
            color: white;
        }
        
        .cart-item-img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
        }
        
        .cart-item {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
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
        
        .btn-outline-light:hover {
            background-color: rgba(255,255,255,0.2);
            border-color: white;
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
        
        @media (max-width: 768px) {
            .cart-sidebar {
                width: 100%;
                right: -100%;
            }
            
            .cart-sidebar.open {
                right: 0;
            }
            
            .page-header {
                padding: 2rem 0;
            }
        }
        
        .toast-container {
            z-index: 1060;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-seedling me-2"></i>FUBADJ
            </a>
            
            <div class="d-flex align-items-center">
                <!-- Bouton Dashboard pour utilisateurs connectés -->
                @auth
                <div class="me-3">
                    <a href="{{ route('dashboard.acheteur') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-tachometer-alt me-1"></i> Dashboard
                    </a>
                </div>
                @endauth

                <div class="d-flex align-items-center">
                    <!-- Bouton panier -->
                    <button class="btn btn-outline-light position-relative" id="cartToggle">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="cart-badge badge bg-danger" id="cartCount">0</span>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- En-tête -->
    <div class="page-header">
        <div class="container">
            <h1 class="display-5 fw-bold mb-2">
                <i class="fas fa-store me-3"></i>Catalogue de Produits
            </h1>
            <p class="lead mb-0">Découvrez nos produits frais et locaux</p>
        </div>
    </div>

    <!-- Overlay pour le panier -->
    <div class="cart-overlay" id="cartOverlay"></div>

    <!-- Sidebar Panier -->
    <div class="cart-sidebar" id="cartSidebar">
        <div class="p-4 h-100 d-flex flex-column">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="m-0">
                    <i class="fas fa-shopping-basket me-2 text-success"></i>Mon Panier
                </h4>
                <button class="btn btn-sm btn-outline-secondary" id="closeCart">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="flex-grow-1 overflow-auto" id="cartItems">
                <!-- Les articles du panier seront insérés ici -->
                <div class="text-center text-muted py-5">
                    <i class="fas fa-shopping-basket fa-3x mb-3"></i>
                    <p>Votre panier est vide</p>
                </div>
            </div>
            
            <div class="border-top pt-3">
                <div class="d-flex justify-content-between mb-3">
                    <span class="fw-bold fs-5">Total:</span>
                    <span class="fw-bold fs-4 text-success" id="cartTotal">0 FCFA</span>
                </div>
                <!-- Bouton de paiement avec redirection directe vers paiement.checkout -->
                <button class="btn btn-success w-100 py-2 mb-2" id="checkoutBtn">
                    <i class="fas fa-lock"></i>
                    <span>Procéder au paiement</span>
                    <i class="fas fa-arrow-right ms-2"></i>
                </button>
                <button class="btn btn-outline-danger w-100" id="clearCartBtn">
                    <i class="fas fa-trash me-2"></i>Vider le panier
                </button>
            </div>
        </div>
    </div>

    <!-- Contenu principal -->
    <div class="container pb-5">
        <!-- Statistiques -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="stats-card">
                    <i class="fas fa-box"></i>
                    <h3>{{ $stats['total_produits'] ?? 0 }}</h3>
                    <p>Produits disponibles</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card">
                    <i class="fas fa-users"></i>
                    <h3>{{ $stats['total_producteurs'] ?? 0 }}</h3>
                    <p>Producteurs locaux</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card">
                    <i class="fas fa-list"></i>
                    <h3>{{ $stats['total_categories'] ?? 0 }}</h3>
                    <p>Catégories</p>
                </div>
            </div>
        </div>

        <!-- Filtres -->
        <div class="row">
            <div class="col-lg-3 mb-4">
                <!-- Formulaire de filtrage -->
                <form method="GET" action="{{ route('catalogue.index') }}">
                    <div class="category-filter">
                        <h5>
                            <i class="fas fa-filter me-2 text-success"></i>Catégories
                        </h5>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="category" value="" id="allCategories" 
                                   {{ empty($category) ? 'checked' : '' }}>
                            <label class="form-check-label" for="allCategories">
                                Toutes les catégories
                            </label>
                        </div>
                        @foreach($categories as $cat)
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="category" 
                                       value="{{ $cat->id }}" id="cat{{ $cat->id }}"
                                       {{ $category == $cat->id ? 'checked' : '' }}>
                                <label class="form-check-label" for="cat{{ $cat->id }}">
                                    {{ $cat->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>

                    <!-- Filtre de prix -->
                    <div class="category-filter mt-3">
                        <h5>
                            <i class="fas fa-coins me-2 text-success"></i>Prix
                        </h5>
                        <div class="mb-3">
                            <label class="form-label small">Prix minimum (FCFA)</label>
                            <input type="number" class="form-control form-control-sm" name="min_price" 
                                   value="{{ request('min_price') }}" placeholder="0">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small">Prix maximum (FCFA)</label>
                            <input type="number" class="form-control form-control-sm" name="max_price" 
                                   value="{{ request('max_price') }}" placeholder="10000">
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-sm">
                                <i class="fas fa-check me-2"></i>Appliquer les filtres
                            </button>
                            <a href="{{ route('catalogue.index') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-redo me-2"></i>Réinitialiser
                            </a>
                        </div>
                    </div>
                    
                    <!-- Champ caché pour le tri -->
                    <input type="hidden" name="sort" value="{{ $sort }}" id="sortInput">
                </form>
            </div>
            
            <div class="col-lg-9">
                <!-- Barre de recherche et tri -->
                <div class="row mb-4">
                    <div class="col-md-8 mb-3 mb-md-0">
                        <form method="GET" action="{{ route('catalogue.index') }}" class="search-bar">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="fas fa-search text-success"></i>
                                </span>
                                <input type="text" class="form-control border-start-0" name="search" 
                                       value="{{ $search }}" placeholder="Rechercher un produit...">
                                <button class="btn btn-outline-secondary" type="button" id="clearSearch">
                                    <i class="fas fa-times"></i>
                                </button>
                                <button class="btn btn-success" type="submit">
                                    <i class="fas fa-search me-1"></i> Rechercher
                                </button>
                            </div>
                            <!-- Champs cachés pour conserver les autres filtres -->
                            <input type="hidden" name="category" value="{{ $category }}">
                            <input type="hidden" name="min_price" value="{{ request('min_price') }}">
                            <input type="hidden" name="max_price" value="{{ request('max_price') }}">
                            <input type="hidden" name="sort" value="{{ $sort }}">
                        </form>
                    </div>
                    <div class="col-md-4">
                        <form method="GET" action="{{ route('catalogue.index') }}" id="sortForm">
                            <select class="form-select" name="sort" onchange="document.getElementById('sortForm').submit()">
                                <option value="recent" {{ $sort == 'recent' ? 'selected' : '' }}>Plus récents</option>
                                <option value="price_asc" {{ $sort == 'price_asc' ? 'selected' : '' }}>Prix croissant</option>
                                <option value="price_desc" {{ $sort == 'price_desc' ? 'selected' : '' }}>Prix décroissant</option>
                                <option value="name" {{ $sort == 'name' ? 'selected' : '' }}>Nom A-Z</option>
                            </select>
                            <!-- Champs cachés pour conserver les autres filtres -->
                            <input type="hidden" name="search" value="{{ $search }}">
                            <input type="hidden" name="category" value="{{ $category }}">
                            <input type="hidden" name="min_price" value="{{ request('min_price') }}">
                            <input type="hidden" name="max_price" value="{{ request('max_price') }}">
                        </form>
                    </div>
                </div>
                
                <!-- Grille de produits -->
                <div class="row" id="productsGrid">
                    @forelse($produits as $produit)
                    <div class="col-md-6 col-lg-4 mb-4" 
                         data-category="{{ $produit->categorie }}" 
                         data-price="{{ $produit->prix }}"
                         data-name="{{ strtolower($produit->nom) }}"
                         data-description="{{ strtolower($produit->description) }}">
                        <div class="card product-card h-100">
                            @if($produit->quantite > 0)
                                <span class="badge bg-success badge-stock">En stock ({{ $produit->quantite }})</span>
                            @else
                                <span class="badge bg-danger badge-stock">Rupture de stock</span>
                            @endif
                            
                            @if($produit->image_url)
                                <img src="{{ $produit->image_url }}" class="product-img" alt="{{ $produit->nom }}">
                            @else
                                <div class="product-img bg-light d-flex align-items-center justify-content-center">
                                    <i class="fas fa-image fa-3x text-muted"></i>
                                </div>
                            @endif
                            
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">{{ $produit->nom }}</h5>
                                <p class="card-text product-description flex-grow-1">
                                    {{ \Illuminate\Support\Str::limit($produit->description, 100) }}
                                </p>
                                
                                @if($produit->user)
                                <div class="mb-2">
                                    <small class="text-muted">
                                        <i class="fas fa-user me-1"></i>{{ $produit->user->name }}
                                    </small>
                                </div>
                                @endif
                                
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <div class="product-price">
                                        {{ number_format($produit->prix, 0, ',', ' ') }} FCFA
                                    </div>
                                    
                                    <div class="d-flex align-items-center">
                                        @if($produit->quantite > 0)
                                        <div class="input-group quantity-control me-2">
                                            <button class="btn btn-outline-secondary btn-sm minus-btn" type="button" data-id="{{ $produit->id }}">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                            <input type="number" class="form-control form-control-sm text-center quantity-input" 
                                                   data-id="{{ $produit->id }}" 
                                                   value="1" min="1" max="{{ $produit->quantite }}">
                                            <button class="btn btn-outline-secondary btn-sm plus-btn" type="button" data-id="{{ $produit->id }}">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                        @endif
                                        
                                        <button class="btn btn-add-to-cart btn-sm" 
                                                data-id="{{ $produit->id }}"
                                                data-name="{{ $produit->nom }}"
                                                data-price="{{ $produit->prix }}"
                                                data-image="{{ $produit->image_url }}"
                                                data-stock="{{ $produit->quantite }}"
                                                {{ $produit->quantite <= 0 ? 'disabled' : '' }}>
                                            <i class="fas fa-cart-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12">
                        <div class="text-center py-5">
                            <i class="fas fa-search fa-3x text-muted mb-3"></i>
                            <h4 class="text-dark">Aucun produit trouvé</h4>
                            <p class="text-muted mb-3">Essayez de modifier vos critères de recherche</p>
                            <a href="{{ route('catalogue.index') }}" class="btn btn-outline-primary">
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
        <div class="container text-center">
            <p class="mb-0">&copy; {{ date('Y') }} FUBADJ. Tous droits réservés.</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        class CartManager {
            constructor() {
                this.cart = JSON.parse(localStorage.getItem('shopping_cart')) || [];
                this.updateCartDisplay();
            }
            
            addItem(productId, name, price, image, quantity = 1) {
                const existingItem = this.cart.find(item => item.id == productId);
                
                if (existingItem) {
                    existingItem.quantity += quantity;
                } else {
                    this.cart.push({
                        id: productId,
                        name: name,
                        price: parseFloat(price),
                        image: image,
                        quantity: quantity
                    });
                }
                
                this.saveCart();
                this.showNotification(`${name} ajouté au panier`);
            }
            
            removeItem(productId) {
                this.cart = this.cart.filter(item => item.id != productId);
                this.saveCart();
            }
            
            updateQuantity(productId, quantity) {
                const item = this.cart.find(item => item.id == productId);
                if (item) {
                    item.quantity = parseInt(quantity);
                    if (item.quantity <= 0) {
                        this.removeItem(productId);
                    } else {
                        this.saveCart();
                    }
                }
            }
            
            clearCart() {
                this.cart = [];
                this.saveCart();
            }
            
            saveCart() {
                localStorage.setItem('shopping_cart', JSON.stringify(this.cart));
                this.updateCartDisplay();
            }
            
            getTotal() {
                return this.cart.reduce((total, item) => total + (item.price * item.quantity), 0);
            }
            
            getItemCount() {
                return this.cart.reduce((count, item) => count + item.quantity, 0);
            }
            
            updateCartDisplay() {
                document.getElementById('cartCount').textContent = this.getItemCount();
                this.renderCartItems();
            }
            
            renderCartItems() {
                const cartItemsContainer = document.getElementById('cartItems');
                const cartTotalElement = document.getElementById('cartTotal');
                
                if (this.cart.length === 0) {
                    cartItemsContainer.innerHTML = `
                        <div class="text-center text-muted py-5">
                            <i class="fas fa-shopping-basket fa-3x mb-3"></i>
                            <p>Votre panier est vide</p>
                        </div>
                    `;
                    cartTotalElement.textContent = '0 FCFA';
                    return;
                }
                
                let html = '';
                this.cart.forEach(item => {
                    html += `
                        <div class="cart-item mb-3 p-3 rounded">
                            <div class="d-flex align-items-center">
                                ${item.image ? 
                                    `<img src="${item.image}" class="cart-item-img me-3" alt="${item.name}">` :
                                    `<div class="cart-item-img bg-light d-flex align-items-center justify-content-center me-3">
                                        <i class="fas fa-image text-muted"></i>
                                    </div>`
                                }
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">${item.name}</h6>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <span class="text-muted">${item.price.toFixed(0)} FCFA × </span>
                                            <input type="number" class="form-control form-control-sm d-inline-block quantity-input-cart" 
                                                   style="width: 70px;" 
                                                   value="${item.quantity}" 
                                                   min="1" 
                                                   data-id="${item.id}">
                                        </div>
                                        <div class="fw-bold text-success">${(item.price * item.quantity).toFixed(0)} FCFA</div>
                                    </div>
                                </div>
                                <button class="btn btn-sm btn-outline-danger ms-2 remove-item-btn" data-id="${item.id}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    `;
                });
                
                cartItemsContainer.innerHTML = html;
                cartTotalElement.textContent = this.getTotal().toFixed(0) + ' FCFA';
            }
            
            showNotification(message) {
                const toast = document.createElement('div');
                toast.className = 'position-fixed bottom-0 end-0 p-3';
                toast.style.zIndex = '11';
                toast.innerHTML = `
                    <div class="toast show" role="alert">
                        <div class="toast-header bg-success text-white">
                            <strong class="me-auto"><i class="fas fa-check-circle me-2"></i>Succès</strong>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
                        </div>
                        <div class="toast-body">
                            ${message}
                        </div>
                    </div>
                `;
                
                document.body.appendChild(toast);
                
                setTimeout(() => {
                    toast.remove();
                }, 3000);
            }
            
            prepareCheckoutData() {
                return {
                    items: this.cart,
                    total: this.getTotal(),
                    count: this.getItemCount(),
                    timestamp: new Date().toISOString()
                };
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const cartManager = new CartManager();
            
            const cartToggle = document.getElementById('cartToggle');
            const cartSidebar = document.getElementById('cartSidebar');
            const cartOverlay = document.getElementById('cartOverlay');
            const closeCart = document.getElementById('closeCart');
            const clearCartBtn = document.getElementById('clearCartBtn');
            const checkoutBtn = document.getElementById('checkoutBtn');
            const clearSearch = document.getElementById('clearSearch');
            
            // Charger le panier depuis la session au démarrage
            chargerPanierDepuisSession();
            
            cartToggle.addEventListener('click', () => {
                cartSidebar.classList.add('open');
                cartOverlay.classList.add('active');
            });
            
            closeCart.addEventListener('click', closeCartSidebar);
            cartOverlay.addEventListener('click', closeCartSidebar);
            
            function closeCartSidebar() {
                cartSidebar.classList.remove('open');
                cartOverlay.classList.remove('active');
            }
            
            clearCartBtn.addEventListener('click', () => {
                if (confirm('Voulez-vous vraiment vider votre panier ?')) {
                    cartManager.clearCart();
                    // Mettre à jour la session
                    synchroniserPanierAvecSession([]);
                }
            });
            
            // Gestionnaire pour le bouton checkout
            checkoutBtn.addEventListener('click', async function(e) {
                e.preventDefault();
                
                if (cartManager.cart.length === 0) {
                    alert('Votre panier est vide');
                    return;
                }
                
                // Vérifier si l'utilisateur est connecté
                @auth
                    // Utilisateur connecté - stocker en session puis rediriger
                    await proceedToPayment();
                @else
                    // Utilisateur non connecté
                    if (confirm('Vous devez être connecté pour procéder au paiement. Voulez-vous vous connecter maintenant ?')) {
                        // Stocker le panier dans la session avant redirection
                        const checkoutData = cartManager.prepareCheckoutData();
                        
                        try {
                            const response = await fetch('{{ route("panier.storeInSession") }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({ 
                                    panier: checkoutData.items,
                                    total: checkoutData.total 
                                })
                            });
                            
                            const data = await response.json();
                            if (data.success) {
                                // Rediriger vers la page de connexion
                                window.location.href = "{{ route('login') }}";
                            }
                        } catch (error) {
                            console.error('Erreur:', error);
                            alert('Erreur lors du stockage du panier');
                        }
                    }
                @endauth
            });
            
            async function proceedToPayment() {
                // Préparer les données du checkout
                const checkoutData = cartManager.prepareCheckoutData();
                
                // Afficher le chargement
                checkoutBtn.disabled = true;
                checkoutBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Traitement...';
                
                // Envoyer le panier au serveur pour le stocker en session
                try {
                    const response = await fetch('{{ route("panier.synchroniser") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ 
                            panier: checkoutData.items, 
                            total: checkoutData.total 
                        })
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        console.log('✅ Panier stocké en session:', data);
                        
                        // Fermer le sidebar du panier
                        closeCartSidebar();
                        
                        // Redirection vers la page de paiement
                        window.location.href = "{{ route('paiement.checkout') }}";
                    } else {
                        alert('Erreur lors de la synchronisation du panier');
                        checkoutBtn.disabled = false;
                        checkoutBtn.innerHTML = '<i class="fas fa-lock"></i><span>Procéder au paiement</span><i class="fas fa-arrow-right ms-2"></i>';
                    }
                    
                } catch (error) {
                    console.error('❌ Erreur de synchronisation:', error);
                    alert('Erreur de connexion au serveur');
                    checkoutBtn.disabled = false;
                    checkoutBtn.innerHTML = '<i class="fas fa-lock"></i><span>Procéder au paiement</span><i class="fas fa-arrow-right ms-2"></i>';
                }
            }
            
            // Gestion des clics sur les boutons
            document.addEventListener('click', async function(e) {
                if (e.target.closest('.btn-add-to-cart')) {
                    const button = e.target.closest('.btn-add-to-cart');
                    const productId = button.getAttribute('data-id');
                    const productName = button.getAttribute('data-name');
                    const productPrice = button.getAttribute('data-price');
                    const productImage = button.getAttribute('data-image');
                    const quantityInput = document.querySelector(`.quantity-input[data-id="${productId}"]`);
                    const quantity = parseInt(quantityInput ? quantityInput.value : 1);
                    
                    cartManager.addItem(productId, productName, productPrice, productImage, quantity);
                    
                    // Mettre à jour la session après chaque ajout
                    await synchroniserPanierAvecSession(cartManager.cart);
                    
                    cartSidebar.classList.add('open');
                    cartOverlay.classList.add('active');
                }
                
                if (e.target.closest('.plus-btn')) {
                    const button = e.target.closest('.plus-btn');
                    const productId = button.getAttribute('data-id');
                    const input = document.querySelector(`.quantity-input[data-id="${productId}"]`);
                    const max = parseInt(input.getAttribute('max')) || 999;
                    input.value = Math.min(parseInt(input.value) + 1, max);
                }
                
                if (e.target.closest('.minus-btn')) {
                    const button = e.target.closest('.minus-btn');
                    const productId = button.getAttribute('data-id');
                    const input = document.querySelector(`.quantity-input[data-id="${productId}"]`);
                    const min = parseInt(input.getAttribute('min')) || 1;
                    input.value = Math.max(parseInt(input.value) - 1, min);
                }
                
                if (e.target.closest('.remove-item-btn')) {
                    const button = e.target.closest('.remove-item-btn');
                    const productId = button.getAttribute('data-id');
                    cartManager.removeItem(productId);
                    
                    // Mettre à jour la session après suppression
                    await synchroniserPanierAvecSession(cartManager.cart);
                }
            });
            
            document.addEventListener('change', async function(e) {
                if (e.target.classList.contains('quantity-input-cart')) {
                    const productId = e.target.getAttribute('data-id');
                    const quantity = parseInt(e.target.value);
                    cartManager.updateQuantity(productId, quantity);
                    
                    // Mettre à jour la session après modification
                    await synchroniserPanierAvecSession(cartManager.cart);
                }
            });
            
            // Fonction pour synchroniser le panier avec la session
            async function synchroniserPanierAvecSession(cart) {
                try {
                    const response = await fetch('{{ route("panier.updateSession") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ 
                            panier: cart,
                            total: cartManager.getTotal()
                        })
                    });
                    
                    const data = await response.json();
                    if (data.success) {
                        console.log('✅ Session mise à jour');
                    }
                } catch (error) {
                    console.error('Erreur synchronisation session:', error);
                }
            }
            
            // Fonction pour charger le panier depuis la session
            async function chargerPanierDepuisSession() {
                try {
                    const response = await fetch('{{ route("panier.getSession") }}');
                    const data = await response.json();
                    
                    if (data.success && data.panier && data.panier.length > 0) {
                        // Fusionner avec le localStorage
                        const localCart = JSON.parse(localStorage.getItem('shopping_cart')) || [];
                        
                        // Fusionner les paniers (priorité à la session)
                        const mergedCart = fusionnerPaniers(data.panier, localCart);
                        
                        // Mettre à jour le localStorage et l'affichage
                        cartManager.cart = mergedCart;
                        cartManager.saveCart();
                        
                        console.log('✅ Panier chargé depuis session:', mergedCart.length, 'articles');
                    }
                } catch (error) {
                    console.error('Erreur chargement session:', error);
                }
            }
            
            function fusionnerPaniers(sessionCart, localCart) {
                const merged = [...sessionCart];
                
                localCart.forEach(localItem => {
                    const existingItem = merged.find(item => item.id == localItem.id);
                    if (existingItem) {
                        // Garder la plus grande quantité
                        existingItem.quantity = Math.max(existingItem.quantity, localItem.quantity);
                    } else {
                        merged.push(localItem);
                    }
                });
                
                return merged;
            }
            
            // Nettoyer la recherche
            clearSearch.addEventListener('click', function() {
                const searchInput = document.querySelector('input[name="search"]');
                const searchForm = document.querySelector('.search-bar form');
                searchInput.value = '';
                searchForm.submit();
            });
        });
    </script>
</body>
</html>