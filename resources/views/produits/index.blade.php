@extends('layouts.app')

@section('title', 'Mes Produits')

@section('content')
<div class="container-fluid">
    <!-- En-tête de la page avec statistiques -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm bg-gradient-primary text-white">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="h3 mb-1">
                                <i class="fas fa-store me-2"></i>Mon Catalogue Produits
                            </h2>
                            <p class="mb-0 opacity-75">Gérez et organisez votre inventaire</p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <a href="{{ route('produits.create') }}" class="btn btn-light btn-lg shadow-sm">
                                <i class="fas fa-plus me-2"></i>Nouveau Produit
                            </a>
                        </div>
                    </div>
                    
                    <!-- Statistiques rapides -->
                    @if($produits->total() > 0)
                    <div class="row mt-4">
                        <div class="col-md-4">
                            <div class="d-flex align-items-center">
                                <div class="icon-circle bg-white bg-opacity-20 me-3">
                                    <i class="fas fa-boxes text-white"></i>
                                </div>
                                <div>
                                    <div class="h5 mb-0">{{ $produits->total() }}</div>
                                    <small class="opacity-75">Produits total</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-center">
                                <div class="icon-circle bg-white bg-opacity-20 me-3">
                                    <i class="fas fa-check-circle text-white"></i>
                                </div>
                                <div>
                                    <div class="h5 mb-0">{{ $produits->where('quantite', '>', 0)->count() }}</div>
                                    <small class="opacity-75">En stock</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-center">
                                <div class="icon-circle bg-white bg-opacity-20 me-3">
                                    <i class="fas fa-chart-line text-white"></i>
                                </div>
                                <div>
                                    <div class="h5 mb-0">{{ number_format($produits->sum(function($p) { return $p->prix * $p->quantite; }), 0, ',', ' ') }} FCFA</div>
                                    <small class="opacity-75">Valeur stock</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Messages d'alerte -->
    @if (session('success'))
        <div class="row mb-4">
            <div class="col-12">
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle fa-lg me-3 text-success"></i>
                        <div class="flex-grow-1">
                            <strong>Succès !</strong> {{ session('success') }}
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="row mb-4">
            <div class="col-12">
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-triangle fa-lg me-3 text-danger"></i>
                        <div class="flex-grow-1">
                            <strong>Erreur !</strong> {{ session('error') }}
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Barre d'outils et filtres -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="row g-3 align-items-center">
                        <!-- Recherche -->
                        <div class="col-lg-4 col-md-6">
                            <div class="position-relative">
                                <input type="text" class="form-control form-control-lg ps-5 border-0 bg-light" 
                                       placeholder="🔍 Rechercher un produit..." id="searchProducts">
                                <i class="fas fa-search position-absolute text-muted" style="left: 18px; top: 50%; transform: translateY(-50%);"></i>
                            </div>
                        </div>
                        
                        <!-- Filtre par stock -->
                        <div class="col-lg-3 col-md-6">
                            <select class="form-select form-select-lg border-0 bg-light" id="filterStock">
                                <option value="">📦 Tous les stocks</option>
                                <option value="available">✅ En stock (>10)</option>
                                <option value="low">⚠️ Stock faible (1-10)</option>
                                <option value="out">❌ Rupture (0)</option>
                            </select>
                        </div>

                        <!-- Tri -->
                        <div class="col-lg-3 col-md-6">
                            <select class="form-select form-select-lg border-0 bg-light" id="sortProducts">
                                <option value="name-asc">📝 Nom (A-Z)</option>
                                <option value="name-desc">📝 Nom (Z-A)</option>
                                <option value="price-asc">💰 Prix croissant</option>
                                <option value="price-desc">💰 Prix décroissant</option>
                                <option value="stock-asc">📊 Stock croissant</option>
                                <option value="stock-desc">📊 Stock décroissant</option>
                                <option value="date-desc">🕒 Plus récents</option>
                                <option value="date-asc">🕒 Plus anciens</option>
                            </select>
                        </div>
                        
                        <!-- Sélecteur de vue -->
                        <div class="col-lg-2 col-md-6">
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="viewMode" id="gridView" value="grid" checked>
                                <label class="btn btn-outline-primary btn-lg" for="gridView" title="Vue grille">
                                    <i class="fas fa-th-large"></i>
                                </label>
                                <input type="radio" class="btn-check" name="viewMode" id="listView" value="list">
                                <label class="btn btn-outline-primary btn-lg" for="listView" title="Vue liste">
                                    <i class="fas fa-list"></i>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($produits->count())
        <!-- Vue grille (par défaut) -->
        <div id="gridViewContent" class="products-container">
            <div class="row" id="productsGrid">
                @foreach ($produits as $produit)
                    <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                        <div class="product-item" 
                             data-name="{{ strtolower($produit->nom) }}" 
                             data-stock="{{ $produit->quantite }}"
                             data-price="{{ $produit->prix }}"
                             data-date="{{ $produit->created_at->timestamp }}">
                            <div class="card product-card border-0 shadow-sm h-100">
                                <div class="position-relative overflow-hidden">
                                    @if ($produit->image_url)
                                        <img src="{{ $produit->image_url }}" 
                                             alt="{{ $produit->nom }}" 
                                             class="card-img-top product-image" 
                                             style="height: 220px; object-fit: cover; transition: transform 0.3s ease;">
                                    @else
                                        <div class="card-img-top bg-gradient-light d-flex align-items-center justify-content-center text-muted" 
                                             style="height: 220px;">
                                            <div class="text-center">
                                                <i class="fas fa-image fa-3x mb-2 opacity-50"></i>
                                                <br><small>Aucune image</small>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    <!-- Badge de statut stock -->
                                    <div class="position-absolute top-0 end-0 m-3">
                                        @if($produit->quantite > 10)
                                            <span class="badge bg-success shadow-sm">
                                                <i class="fas fa-check me-1"></i>En stock
                                            </span>
                                        @elseif($produit->quantite > 0)
                                            <span class="badge bg-warning shadow-sm">
                                                <i class="fas fa-exclamation me-1"></i>Stock faible
                                            </span>
                                        @else
                                            <span class="badge bg-danger shadow-sm">
                                                <i class="fas fa-times me-1"></i>Rupture
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Actions rapides overlay -->
                                    <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center quick-actions" 
                                         style="background: rgba(0,0,0,0.7); opacity: 0; transition: opacity 0.3s ease;">
                                        <div class="btn-group">
                                            <a href="{{ route('produits.show', $produit) }}" 
                                               class="btn btn-light btn-sm mx-1" title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('produits.edit', $produit) }}" 
                                               class="btn btn-primary btn-sm mx-1" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-danger btn-sm mx-1 delete-btn" 
                                                    data-id="{{ $produit->id }}"
                                                    data-name="{{ $produit->nom }}"
                                                    title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="card-body d-flex flex-column">
                                    <div class="mb-3">
                                        <h5 class="card-title text-dark mb-2 fw-bold">{{ $produit->nom }}</h5>
                                        <p class="card-text text-muted small flex-grow-1 mb-0">
                                            {{ Str::limit($produit->description, 100) }}
                                        </p>
                                    </div>
                                    
                                    <!-- Prix et quantité -->
                                    <div class="pricing-info mb-3 p-3 bg-light rounded">
                                        <div class="row align-items-center">
                                            <div class="col-7">
                                                <div class="price-display">
                                                    <span class="h4 text-success fw-bold mb-0">
                                                        {{ number_format($produit->prix, 0, ',', ' ') }}
                                                    </span>
                                                    <span class="text-muted ms-1">FCFA</span>
                                                </div>
                                            </div>
                                            <div class="col-5 text-end">
                                                <div class="stock-info">
                                                    <i class="fas fa-box text-primary me-1"></i>
                                                    <span class="fw-semibold">{{ $produit->quantite }}</span>
                                                    <br><small class="text-muted">unités</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Informations additionnelles -->
                                    <div class="product-meta mb-3">
                                        <div class="row text-center">
                                            <div class="col-6">
                                                <small class="text-muted d-block">Valeur stock</small>
                                                <span class="fw-semibold text-info">
                                                    {{ number_format($produit->prix * $produit->quantite, 0, ',', ' ') }} FCFA
                                                </span>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted d-block">Ajouté le</small>
                                                <span class="fw-semibold">{{ $produit->created_at->format('d/m/Y') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Actions principales -->
                                    <div class="d-grid gap-2 mt-auto">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('produits.show', $produit) }}" 
                                               class="btn btn-outline-info">
                                                <i class="fas fa-eye me-1"></i>Voir
                                            </a>
                                            <a href="{{ route('produits.edit', $produit) }}" 
                                               class="btn btn-outline-primary">
                                                <i class="fas fa-edit me-1"></i>Modifier
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-outline-danger delete-btn" 
                                                    data-id="{{ $produit->id }}"
                                                    data-name="{{ $produit->nom }}">
                                                <i class="fas fa-trash me-1"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Vue liste (cachée par défaut) -->
        <div id="listViewContent" class="d-none">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-table me-2 text-primary"></i>Vue tableau
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th class="ps-4">Produit</th>
                                    <th>Prix unitaire</th>
                                    <th>Stock</th>
                                    <th>Valeur totale</th>
                                    <th>Statut</th>
                                    <th>Date création</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="productsTable">
                                @foreach($produits as $produit)
                                <tr class="product-item" 
                                    data-name="{{ strtolower($produit->nom) }}" 
                                    data-stock="{{ $produit->quantite }}"
                                    data-price="{{ $produit->prix }}"
                                    data-date="{{ $produit->created_at->timestamp }}">
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="product-thumbnail me-3">
                                                @if($produit->image_url)
                                                    <img src="{{ $produit->image_url }}" 
                                                         alt="{{ $produit->nom }}" 
                                                         class="rounded shadow-sm" 
                                                         width="60" height="60" 
                                                         style="object-fit: cover;">
                                                @else
                                                    <div class="bg-light rounded shadow-sm d-flex align-items-center justify-content-center" 
                                                         style="width: 60px; height: 60px;">
                                                        <i class="fas fa-image text-muted"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark">{{ $produit->nom }}</div>
                                                <small class="text-muted">{{ Str::limit($produit->description, 60) }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-success h6 mb-0">
                                            {{ number_format($produit->prix, 0, ',', ' ') }} FCFA
                                        </span>
                                    </td>
                                    <td>
                                        <span class="fw-semibold">{{ $produit->quantite }}</span>
                                        <small class="text-muted d-block">unités</small>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-info">
                                            {{ number_format($produit->prix * $produit->quantite, 0, ',', ' ') }} FCFA
                                        </span>
                                    </td>
                                    <td>
                                        @if($produit->quantite > 10)
                                            <span class="badge bg-success fs-6">
                                                <i class="fas fa-check me-1"></i>En stock
                                            </span>
                                        @elseif($produit->quantite > 0)
                                            <span class="badge bg-warning fs-6">
                                                <i class="fas fa-exclamation me-1"></i>Stock faible
                                            </span>
                                        @else
                                            <span class="badge bg-danger fs-6">
                                                <i class="fas fa-times me-1"></i>Rupture
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="fw-semibold">{{ $produit->created_at->format('d/m/Y') }}</span>
                                        <small class="text-muted d-block">{{ $produit->created_at->format('H:i') }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('produits.show', $produit) }}" 
                                               class="btn btn-outline-info" title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('produits.edit', $produit) }}" 
                                               class="btn btn-outline-primary" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-outline-danger delete-btn" 
                                                    data-id="{{ $produit->id }}"
                                                    data-name="{{ $produit->nom }}"
                                                    title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pagination personnalisée -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted">
                        Affichage de {{ $produits->firstItem() }} à {{ $produits->lastItem() }} 
                        sur {{ $produits->total() }} produits
                    </div>
                    <div>
                        {{ $produits->links() }}
                    </div>
                </div>
            </div>
        </div>

    @else
        <!-- État vide avec design amélioré -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <div class="empty-state-icon mb-4">
                            <div class="icon-circle bg-light mx-auto mb-3" style="width: 120px; height: 120px;">
                                <i class="fas fa-seedling fa-4x text-success" style="line-height: 120px;"></i>
                            </div>
                        </div>
                        <h3 class="text-dark mb-3">Votre catalogue est vide</h3>
                        <p class="text-muted mb-4 lead">
                            Commencez dès maintenant à ajouter vos produits pour développer votre activité
                        </p>
                        <div class="d-flex justify-content-center gap-3">
                            <a href="{{ route('produits.create') }}" class="btn btn-success btn-lg shadow-sm">
                                <i class="fas fa-plus me-2"></i>Ajouter mon premier produit
                            </a>
                            <button class="btn btn-outline-secondary btn-lg" type="button" data-bs-toggle="collapse" data-bs-target="#helpSection">
                                <i class="fas fa-question-circle me-2"></i>Besoin d'aide ?
                            </button>
                        </div>
                        
                        <!-- Section d'aide -->
                        <div class="collapse mt-4" id="helpSection">
                            <div class="card border-0 bg-light">
                                <div class="card-body">
                                    <h5 class="text-dark mb-3">
                                        <i class="fas fa-lightbulb me-2 text-warning"></i>Conseils pour commencer
                                    </h5>
                                    <div class="row text-start">
                                        <div class="col-md-6">
                                            <h6 class="text-primary">📸 Photos de qualité</h6>
                                            <p class="small text-muted mb-3">
                                                Ajoutez des images claires et attrayantes de vos produits
                                            </p>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="text-primary">📝 Descriptions détaillées</h6>
                                            <p class="small text-muted mb-3">
                                                Décrivez précisément vos produits pour attirer les clients
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Modal de confirmation de suppression -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>Confirmer la suppression
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="mb-3">
                    <i class="fas fa-trash fa-3x text-danger mb-3"></i>
                </div>
                <h5 class="text-dark">Êtes-vous certain de vouloir supprimer ce produit ?</h5>
                <p class="text-muted mb-0">
                    Le produit "<span id="productNameToDelete" class="fw-bold"></span>" sera définitivement supprimé.
                    Cette action est irréversible.
                </p>
            </div>
            <div class="modal-footer border-0 justify-content-center">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Annuler
                </button>
                <form id="deleteForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-2"></i>Supprimer définitivement
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal de chargement -->
<div class="modal fade" id="loadingModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-body text-center py-4">
                <div class="spinner-border text-primary mb-3" role="status">
                    <span class="visually-hidden">Chargement...</span>
                </div>
                <p class="mb-0">Traitement en cours...</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Éléments du DOM
    const searchInput = document.getElementById('searchProducts');
    const filterStock = document.getElementById('filterStock');
    const sortProducts = document.getElementById('sortProducts');
    const gridViewBtn = document.getElementById('gridView');
    const listViewBtn = document.getElementById('listView');
    const gridViewContent = document.getElementById('gridViewContent');
    const listViewContent = document.getElementById('listViewContent');
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    const loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));

    // Variables pour le tri et filtrage
    let originalProductsOrder = [];
    
    // Initialiser l'ordre original des produits
    function initializeOriginalOrder() {
        const gridProducts = Array.from(document.querySelectorAll('#productsGrid .product-item'));
        const listProducts = Array.from(document.querySelectorAll('#productsTable .product-item'));
        
        originalProductsOrder = {
            grid: gridProducts.map(item => ({
                element: item.parentElement, // col-* container
                data: {
                    name: item.getAttribute('data-name'),
                    stock: parseInt(item.getAttribute('data-stock')),
                    price: parseFloat(item.getAttribute('data-price')),
                    date: parseInt(item.getAttribute('data-date'))
                }
            })),
            list: listProducts.map(item => ({
                element: item,
                data: {
                    name: item.getAttribute('data-name'),
                    stock: parseInt(item.getAttribute('data-stock')),
                    price: parseFloat(item.getAttribute('data-price')),
                    date: parseInt(item.getAttribute('data-date'))
                }
            }))
        };
    }

    // Fonction de tri
    function sortProducts() {
        const sortValue = sortProducts.value;
        const isGridView = gridViewBtn.checked;
        const container = isGridView ? document.getElementById('productsGrid') : document.getElementById('productsTable');
        const products = isGridView ? [...originalProductsOrder.grid] : [...originalProductsOrder.list];

        // Trier selon la valeur sélectionnée
        products.sort((a, b) => {
            switch(sortValue) {
                case 'name-asc':
                    return a.data.name.localeCompare(b.data.name);
                case 'name-desc':
                    return b.data.name.localeCompare(a.data.name);
                case 'price-asc':
                    return a.data.price - b.data.price;
                case 'price-desc':
                    return b.data.price - a.data.price;
                case 'stock-asc':
                    return a.data.stock - b.data.stock;
                case 'stock-desc':
                    return b.data.stock - a.data.stock;
                case 'date-desc':
                    return b.data.date - a.data.date;
                case 'date-asc':
                    return a.data.date - b.data.date;
                default:
                    return 0;
            }
        });

        // Réorganiser les éléments dans le DOM
        products.forEach(product => {
            container.appendChild(product.element);
        });
    }

    // Fonction de recherche et filtrage
    function filterProducts() {
        const searchTerm = searchInput ? searchInput.value.toLowerCase().trim() : '';
        const stockFilter = filterStock ? filterStock.value : '';
        
        // Obtenir les éléments selon la vue active
        let productItems;
        if (gridViewBtn && gridViewBtn.checked) {
            productItems = gridViewContent.querySelectorAll('.product-item');
        } else {
            productItems = listViewContent.querySelectorAll('tr.product-item');
        }

        let visibleCount = 0;

        productItems.forEach(item => {
            const productName = item.getAttribute('data-name') || '';
            const productStock = parseInt(item.getAttribute('data-stock')) || 0;
            
            let showProduct = true;

            // Filtrage par nom
            if (searchTerm && !productName.includes(searchTerm)) {
                showProduct = false;
            }

            // Filtrage par stock
            if (stockFilter) {
                switch (stockFilter) {
                    case 'available':
                        if (productStock <= 10) showProduct = false;
                        break;
                    case 'low':
                        if (productStock <= 0 || productStock > 10) showProduct = false;
                        break;
                    case 'out':
                        if (productStock > 0) showProduct = false;
                        break;
                }
            }

            // Afficher/masquer l'élément avec animation
            if (gridViewBtn && gridViewBtn.checked) {
                const colContainer = item.parentElement;
                if (colContainer) {
                    if (showProduct) {
                        colContainer.style.display = '';
                        colContainer.style.opacity = '0';
                        setTimeout(() => {
                            colContainer.style.opacity = '1';
                        }, 50);
                        visibleCount++;
                    } else {
                        colContainer.style.opacity = '0';
                        setTimeout(() => {
                            colContainer.style.display = 'none';
                        }, 200);
                    }
                }
            } else {
                if (showProduct) {
                    item.style.display = '';
                    item.style.opacity = '0';
                    setTimeout(() => {
                        item.style.opacity = '1';
                    }, 50);
                    visibleCount++;
                } else {
                    item.style.opacity = '0';
                    setTimeout(() => {
                        item.style.display = 'none';
                    }, 200);
                }
            }
        });

        // Afficher message si aucun résultat
        updateNoResultsMessage(visibleCount);
    }

    // Fonction pour afficher/masquer le message "aucun résultat"
    function updateNoResultsMessage(visibleCount) {
        const existingMessage = document.getElementById('noResultsMessage');
        
        if (visibleCount === 0) {
            if (!existingMessage) {
                const isGridView = gridViewBtn.checked;
                const container = isGridView ? gridViewContent : listViewContent;
                
                const messageHtml = `
                    <div id="noResultsMessage" class="col-12">
                        <div class="card border-0 bg-light">
                            <div class="card-body text-center py-5">
                                <i class="fas fa-search fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Aucun produit trouvé</h5>
                                <p class="text-muted mb-3">Essayez de modifier vos critères de recherche</p>
                                <button class="btn btn-outline-primary" onclick="clearFilters()">
                                    <i class="fas fa-eraser me-2"></i>Effacer les filtres
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                
                if (isGridView) {
                    document.getElementById('productsGrid').insertAdjacentHTML('afterend', messageHtml);
                } else {
                    container.insertAdjacentHTML('beforeend', messageHtml);
                }
            }
        } else {
            if (existingMessage) {
                existingMessage.remove();
            }
        }
    }

    // Fonction pour effacer tous les filtres
    window.clearFilters = function() {
        if (searchInput) searchInput.value = '';
        if (filterStock) filterStock.value = '';
        filterProducts();
    }

    // Fonction pour basculer entre les vues
    function toggleView() {
        if (!gridViewBtn || !listViewBtn || !gridViewContent || !listViewContent) {
            return;
        }

        // Afficher un loader pendant la transition
        showLoadingModal();

        setTimeout(() => {
            if (gridViewBtn.checked) {
                gridViewContent.classList.remove('d-none');
                listViewContent.classList.add('d-none');
                // Animation d'entrée pour la vue grille
                gridViewContent.style.opacity = '0';
                setTimeout(() => {
                    gridViewContent.style.opacity = '1';
                    gridViewContent.style.transition = 'opacity 0.3s ease-in-out';
                }, 50);
            } else if (listViewBtn.checked) {
                gridViewContent.classList.add('d-none');
                listViewContent.classList.remove('d-none');
                // Animation d'entrée pour la vue liste
                listViewContent.style.opacity = '0';
                setTimeout(() => {
                    listViewContent.style.opacity = '1';
                    listViewContent.style.transition = 'opacity 0.3s ease-in-out';
                }, 50);
            }
            
            // Réappliquer les filtres et le tri
            setTimeout(() => {
                filterProducts();
                sortProducts();
                hideLoadingModal();
            }, 100);
        }, 300);
    }

    // Fonctions pour gérer le modal de chargement
    function showLoadingModal() {
        loadingModal.show();
    }

    function hideLoadingModal() {
        loadingModal.hide();
    }

    // Gestion de la suppression avec modal
    function setupDeleteButtons() {
        const deleteButtons = document.querySelectorAll('.delete-btn');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const productId = this.getAttribute('data-id');
                const productName = this.getAttribute('data-name');
                
                // Remplir le modal avec les informations du produit
                document.getElementById('productNameToDelete').textContent = productName;
                document.getElementById('deleteForm').action = `/produits/${productId}`;
                
                // Afficher le modal
                deleteModal.show();
            });
        });
    }

    // Animation des cartes au survol
    function addHoverEffects() {
        const cards = document.querySelectorAll('.product-card');
        cards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-8px)';
                this.style.transition = 'all 0.3s ease';
                
                // Afficher les actions rapides
                const quickActions = this.querySelector('.quick-actions');
                if (quickActions) {
                    quickActions.style.opacity = '1';
                }
                
                // Effet zoom sur l'image
                const image = this.querySelector('.product-image');
                if (image) {
                    image.style.transform = 'scale(1.05)';
                }
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
                
                // Masquer les actions rapides
                const quickActions = this.querySelector('.quick-actions');
                if (quickActions) {
                    quickActions.style.opacity = '0';
                }
                
                // Remettre l'image à la normale
                const image = this.querySelector('.product-image');
                if (image) {
                    image.style.transform = 'scale(1)';
                }
            });
        });
    }

    // Animation de loading pour les actions
    function animateAction(button, action) {
        const originalContent = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        button.disabled = true;
        
        setTimeout(() => {
            button.innerHTML = originalContent;
            button.disabled = false;
            if (action) action();
        }, 800);
    }

    // Fonction de debouncing pour la recherche
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // Event listeners avec vérifications
    if (searchInput) {
        const debouncedFilter = debounce(filterProducts, 300);
        searchInput.addEventListener('input', function() {
            // Effet visuel pendant la recherche
            this.style.backgroundColor = '#f8f9fa';
            setTimeout(() => {
                this.style.backgroundColor = '';
            }, 200);
            debouncedFilter();
        });
    }

    if (filterStock) {
        filterStock.addEventListener('change', function() {
            showLoadingModal();
            setTimeout(() => {
                filterProducts();
                hideLoadingModal();
            }, 300);
        });
    }

    if (sortProducts) {
        sortProducts.addEventListener('change', function() {
            showLoadingModal();
            setTimeout(() => {
                sortProducts();
                filterProducts(); // Réappliquer les filtres après le tri
                hideLoadingModal();
            }, 300);
        });
    }

    if (gridViewBtn) {
        gridViewBtn.addEventListener('change', function() {
            if (this.checked) {
                toggleView();
            }
        });
    }

    if (listViewBtn) {
        listViewBtn.addEventListener('change', function() {
            if (this.checked) {
                toggleView();
            }
        });
    }

    // Initialisation
    initializeOriginalOrder();
    setupDeleteButtons();
    addHoverEffects();

    // Gestion des raccourcis clavier
    document.addEventListener('keydown', function(e) {
        // Ctrl + F : focus sur la recherche
        if (e.ctrlKey && e.key === 'f') {
            e.preventDefault();
            if (searchInput) {
                searchInput.focus();
                searchInput.select();
            }
        }
        
        // Ctrl + G : basculer vers vue grille
        if (e.ctrlKey && e.key === 'g') {
            e.preventDefault();
            if (gridViewBtn) {
                gridViewBtn.checked = true;
                toggleView();
            }
        }
        
        // Ctrl + L : basculer vers vue liste
        if (e.ctrlKey && e.key === 'l') {
            e.preventDefault();
            if (listViewBtn) {
                listViewBtn.checked = true;
                toggleView();
            }
        }
    });

    // Animation d'entrée pour les cartes
    function animateCardsEntrance() {
        const cards = document.querySelectorAll('.product-card');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            setTimeout(() => {
                card.style.transition = 'all 0.4s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });
    }

    // Démarrer l'animation d'entrée
    animateCardsEntrance();

    // Gestion du rafraîchissement des statistiques
    @if(session('refresh_stats'))
        // Déclencher l'événement pour rafraîchir les stats du dashboard
        if (window.opener && !window.opener.closed) {
            window.opener.dispatchEvent(new CustomEvent('produit-updated'));
        } else {
            window.dispatchEvent(new CustomEvent('produit-updated'));
        }
    @endif

    // Effet de pulsation pour les nouvelles notifications
    function pulseNewItems() {
        @if(session('success'))
            const successAlert = document.querySelector('.alert-success');
            if (successAlert) {
                successAlert.style.animation = 'pulse 2s ease-in-out';
            }
        @endif
    }

    pulseNewItems();

    // Sauvegarde des préférences utilisateur
    function saveUserPreferences() {
        const preferences = {
            viewMode: gridViewBtn.checked ? 'grid' : 'list',
            lastSort: sortProducts.value,
            lastFilter: filterStock.value
        };
        
        // Sauvegarder dans sessionStorage pour la session en cours
        try {
            sessionStorage.setItem('produits_preferences', JSON.stringify(preferences));
        } catch(e) {
            console.log('Préférences non sauvegardées');
        }
    }

    // Restaurer les préférences utilisateur
    function restoreUserPreferences() {
        try {
            const saved = sessionStorage.getItem('produits_preferences');
            if (saved) {
                const preferences = JSON.parse(saved);
                
                // Restaurer la vue
                if (preferences.viewMode === 'list') {
                    listViewBtn.checked = true;
                    toggleView();
                }
                
                // Restaurer le tri
                if (preferences.lastSort && sortProducts) {
                    sortProducts.value = preferences.lastSort;
                    sortProducts();
                }
                
                // Restaurer le filtre
                if (preferences.lastFilter && filterStock) {
                    filterStock.value = preferences.lastFilter;
                    filterProducts();
                }
            }
        } catch(e) {
            console.log('Impossible de restaurer les préférences');
        }
    }

    // Sauvegarder les préférences à chaque changement
    [gridViewBtn, listViewBtn, sortProducts, filterStock].forEach(element => {
        if (element) {
            element.addEventListener('change', saveUserPreferences);
        }
    });

    // Restaurer les préférences au chargement
    restoreUserPreferences();

    // Gestion de la responsive pour mobile
    function handleResponsive() {
        if (window.innerWidth < 768) {
            // Sur mobile, forcer la vue grille pour une meilleure UX
            if (listViewBtn.checked) {
                gridViewBtn.checked = true;
                toggleView();
            }
        }
    }

    window.addEventListener('resize', debounce(handleResponsive, 250));
    handleResponsive(); // Vérifier au chargement

    console.log('🚀 Système de gestion des produits initialisé avec succès !');
});
</script>

<style>
/* Variables CSS personnalisées */
:root {
    --primary-color: #0d6efd;
    --success-color: #198754;
    --warning-color: #ffc107;
    --danger-color: #dc3545;
    --info-color: #0dcaf0;
    --light-color: #f8f9fa;
    --dark-color: #212529;
}

/* Amélioration générale */
.container-fluid {
    max-width: 1400px;
    margin: 0 auto;
}

/* Gradient pour l'en-tête */
.bg-gradient-primary {
    background: linear-gradient(135deg, var(--primary-color) 0%, #4c84ff 100%);
}

.bg-gradient-light {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

/* Cercles d'icônes */
.icon-circle {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}

/* Cartes de produits améliorées */
.product-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    overflow: hidden;
    border-radius: 12px !important;
}

.product-card:hover {
    box-shadow: 0 20px 40px rgba(0,0,0,0.1) !important;
}

.product-card .card-img-top {
    border-radius: 12px 12px 0 0 !important;
}

/* Overlay d'actions rapides */
.quick-actions {
    border-radius: 12px 12px 0 0;
}

.quick-actions .btn {
    transform: scale(0.9);
    transition: transform 0.2s ease;
}

.quick-actions .btn:hover {
    transform: scale(1);
}

/* Informations de prix stylisées */
.pricing-info {
    border-left: 4px solid var(--success-color);
    background: linear-gradient(90deg, rgba(25,135,84,0.05) 0%, transparent 100%);
}

/* Améliorations des badges */
.badge {
    font-size: 0.75rem;
    padding: 0.5em 0.8em;
    border-radius: 20px;
    font-weight: 600;
    text-shadow: none;
}

/* Boutons d'action */
.btn-group .btn {
    border-radius: 8px !important;
    margin-right: 0.25rem;
    transition: all 0.2s ease;
}

.btn-group .btn:last-child {
    margin-right: 0;
}

.btn-group .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

/* Table améliorée */
.table {
    border-spacing: 0 8px;
    border-collapse: separate;
}

.table tbody tr {
    background: white;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    border-radius: 8px;
    transition: all 0.2s ease;
}

.table tbody tr:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

.table tbody td {
    border: none;
    padding: 1rem;
    vertical-align: middle;
}

.table tbody td:first-child {
    border-radius: 8px 0 0 8px;
}

.table tbody td:last-child {
    border-radius: 0 8px 8px 0;
}

.table thead th {
    border: none;
    background: var(--dark-color) !important;
    color: white;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
    padding: 1rem;
}

/* Vignettes de produits */
.product-thumbnail img,
.product-thumbnail div {
    border-radius: 8px;
    border: 2px solid rgba(0,0,0,0.05);
}

/* Contrôles de filtrage */
.form-control, .form-select {
    border-radius: 10px;
    border: 2px solid transparent;
    background-color: #f8f9fa;
    transition: all 0.2s ease;
}

.form-control:focus, .form-select:focus {
    background-color: white;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.2rem rgba(13,110,253,0.1);
}

/* État vide amélioré */
.empty-state-icon .icon-circle {
    animation: float 3s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

/* Améliorations responsive */
@media (max-width: 768px) {
    .btn-group {
        flex-direction: row;
        width: 100%;
    }
    
    .btn-group .btn {
        margin-right: 0.25rem;
        border-radius: 8px !important;
        flex: 1;
    }
    
    .pricing-info {
        margin-bottom: 1rem;
    }
    
    .product-card {
        margin-bottom: 2rem;
    }
    
    .table-responsive {
        border-radius: 12px;
    }
}

@media (max-width: 576px) {
    .container-fluid {
        padding: 0.5rem;
    }
    
    .card-body {
        padding: 1rem;
    }
    
    .btn-lg {
        font-size: 1rem;
        padding: 0.75rem 1.5rem;
    }
}

/* Animations personnalisées */
.product-item {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.fade-in {
    animation: fadeIn 0.5s ease-in-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Amélioration des alertes */
.alert {
    border-radius: 12px;
    border: none;
    padding: 1.25rem 1.5rem;
}

.alert-success {
    background: linear-gradient(135deg, rgba(25,135,84,0.1) 0%, rgba(25,135,84,0.05) 100%);
    border-left: 4px solid var(--success-color);
}

.alert-danger {
    background: linear-gradient(135deg, rgba(220,53,69,0.1) 0%, rgba(220,53,69,0.05) 100%);
    border-left: 4px solid var(--danger-color);
}

/* Pagination stylisée */
.pagination .page-link {
    border-radius: 8px;
    margin: 0 2px;
    border: 2px solid transparent;
    transition: all 0.2s ease;
}

.pagination .page-item.active .page-link {
    background: var(--primary-color);
    border-color: var(--primary-color);
    transform: scale(1.1);
}

.pagination .page-link:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

/* Loading spinner personnalisé */
.spinner-border {
    width: 3rem;
    height: 3rem;
}

/* Sélection des vues améliorée */
.btn-check:checked + .btn {
    background: var(--primary-color);
    border-color: var(--primary-color);
    color: white;
    transform: scale(1.05);
}

/* Tooltips et info-bulles */
[title] {
    cursor: help;
}

/* Amélioration des modals */
.modal-content {
    border-radius: 16px;
    overflow: hidden;
}

.modal-header {
    border-radius: 16px 16px 0 0;
}

/* Effets visuels avancés */
.card:hover .card-img-top {
    transform: scale(1.02);
}

.btn:active {
    transform: scale(0.98);
}

/* Indicateurs de stock visuels */
.badge.bg-success::before {
    content: "✓";
    margin-right: 4px;
}

.badge.bg-warning::before {
    content: "⚠";
    margin-right: 4px;
}

.badge.bg-danger::before {
    content: "✕";
    margin-right: 4px;
}

/* Amélioration de l'accessibilité */
.btn:focus {
    box-shadow: 0 0 0 0.25rem rgba(13,110,253,0.25);
}

.form-control:focus, .form-select:focus {
    box-shadow: 0 0 0 0.25rem rgba(13,110,253,0.1);
}

/* Animation de chargement de page */
@keyframes slideInUp {
    from {
        transform: translateY(30px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.products-container {
    animation: slideInUp 0.6s ease-out;
}

/* Amélioration des messages d'état */
#noResultsMessage {
    animation: fadeIn 0.5s ease-in-out;
}

/* Styles pour les écrans haute résolution */
@media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {
    .product-card {
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }
}

/* Dark mode support (si activé) */
@media (prefers-color-scheme: dark) {
    .bg-light {
        background-color: #2d3748 !important;
        color: #e2e8f0;
    }
    
    .text-muted {
        color: #a0aec0 !important;
    }
}

/* Impression */
@media print {
    .btn, .badge, .quick-actions {
        display: none !important;
    }
    
    .card {
        box-shadow: none !important;
        border: 1px solid #ddd !important;
    }
}
</style>
@endsection