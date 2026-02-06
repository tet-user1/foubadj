@extends('layouts.app')

@section('title', 'Mon Panier - Fubadj')

@section('content')
<style>
    .cart-page {
        min-height: 100vh;
        padding: 2rem 0;
        background: linear-gradient(135deg, #f8fafc 0%, #ffffff 50%, #f1f5f9 100%);
    }

    .breadcrumb-nav {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        margin-bottom: 2rem;
        color: #64748b;
    }

    .breadcrumb-nav a {
        color: #64748b;
        text-decoration: none;
        transition: color 0.2s;
    }

    .breadcrumb-nav a:hover {
        color: #2c7873;
    }

    .breadcrumb-current {
        color: #2c7873;
        font-weight: 600;
    }

    .page-header {
        margin-bottom: 2.5rem;
    }

    .page-header h1 {
        font-size: 2.25rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 0.5rem;
    }

    .page-header p {
        font-size: 1.125rem;
        color: #64748b;
    }

    .alert {
        padding: 1rem 1.5rem;
        border-radius: 0.75rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-weight: 500;
        animation: slideDown 0.3s ease-out;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .alert-success {
        background: #d1fae5;
        color: #065f46;
        border: 1px solid #10b981;
    }

    .alert-error {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #dc2626;
    }

    .cart-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 2rem;
    }

    @media (min-width: 1024px) {
        .cart-grid {
            grid-template-columns: 2fr 1fr;
        }
    }

    .cart-section {
        background: white;
        border-radius: 1rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }

    .cart-header {
        padding: 1.5rem 2rem;
        border-bottom: 2px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: linear-gradient(135deg, #2c7873 0%, #6fb98f 100%);
        color: white;
    }

    .cart-header-left {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .cart-icon {
        width: 3rem;
        height: 3rem;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .cart-title h2 {
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0;
    }

    .cart-subtitle {
        font-size: 0.875rem;
        opacity: 0.9;
        margin: 0;
    }

    .clear-btn {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.3);
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .clear-btn:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateY(-1px);
    }

    .cart-items {
        max-height: 600px;
        overflow-y: auto;
    }

    .cart-items::-webkit-scrollbar {
        width: 8px;
    }

    .cart-items::-webkit-scrollbar-track {
        background: #f1f5f9;
    }

    .cart-items::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }

    .cart-item {
        padding: 1.5rem 2rem;
        border-bottom: 1px solid #f1f5f9;
        transition: background-color 0.2s;
    }

    .cart-item:hover {
        background-color: #f8fafc;
    }

    .cart-item:last-child {
        border-bottom: none;
    }

    .item-content {
        display: grid;
        grid-template-columns: 100px 1fr auto;
        gap: 1.5rem;
        align-items: center;
    }

    @media (max-width: 768px) {
        .item-content {
            grid-template-columns: 80px 1fr;
            gap: 1rem;
        }
    }

    .item-image {
        width: 100px;
        height: 100px;
        border-radius: 0.75rem;
        overflow: hidden;
        background: #f1f5f9;
        flex-shrink: 0;
        position: relative;
    }

    @media (max-width: 768px) {
        .item-image {
            width: 80px;
            height: 80px;
        }
    }

    .item-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s;
    }

    .item-image:hover img {
        transform: scale(1.1);
    }

    .item-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #94a3b8;
        font-size: 2rem;
        background: linear-gradient(135deg, #e2e8f0 0%, #f1f5f9 100%);
    }

    .item-info {
        flex: 1;
        min-width: 0;
    }

    .item-name {
        font-size: 1.125rem;
        font-weight: 600;
        color: #0f172a;
        margin-bottom: 0.5rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .item-price {
        font-size: 1.25rem;
        font-weight: 700;
        color: #2c7873;
        margin-bottom: 0.5rem;
    }

    .item-badges {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-weight: 600;
    }

    .badge-fresh {
        background: #d1fae5;
        color: #065f46;
    }

    .badge-local {
        background: #dbeafe;
        color: #1e40af;
    }

    .badge-category {
        background: #fef3c7;
        color: #92400e;
    }

    .item-controls {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 1rem;
    }

    @media (max-width: 768px) {
        .item-controls {
            grid-column: 1 / -1;
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
        }
    }

    .quantity-controls {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        background: #f8fafc;
        padding: 0.5rem;
        border-radius: 0.75rem;
        border: 1px solid #e2e8f0;
    }

    .qty-btn {
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 0.5rem;
        background: white;
        border: 1px solid #e2e8f0;
        color: #475569;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        font-weight: 600;
    }

    .qty-btn:hover:not(:disabled) {
        background: #2c7873;
        color: white;
        border-color: #2c7873;
        transform: scale(1.05);
    }

    .qty-btn:disabled {
        opacity: 0.3;
        cursor: not-allowed;
    }

    .quantity-display {
        min-width: 3rem;
        text-align: center;
        font-weight: 700;
        color: #0f172a;
        font-size: 1.125rem;
    }

    .item-actions {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 0.5rem;
    }

    .item-subtotal {
        font-size: 1.5rem;
        font-weight: 700;
        color: #0f172a;
    }

    .remove-btn {
        background: none;
        border: none;
        color: #dc2626;
        font-size: 0.875rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.25rem 0.5rem;
        border-radius: 0.375rem;
    }

    .remove-btn:hover {
        background: #fee2e2;
        transform: translateX(-2px);
    }

    .empty-cart {
        padding: 4rem 2rem;
        text-align: center;
    }

    .empty-icon {
        width: 8rem;
        height: 8rem;
        border-radius: 50%;
        background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 2rem;
        font-size: 4rem;
        color: #94a3b8;
        animation: pulse 2s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.05);
        }
    }

    .empty-cart h3 {
        font-size: 1.5rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 0.5rem;
    }

    .empty-cart p {
        color: #64748b;
        margin-bottom: 2rem;
        font-size: 1.125rem;
    }

    .shop-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        background: linear-gradient(135deg, #2c7873 0%, #6fb98f 100%);
        color: white;
        padding: 1rem 2rem;
        border-radius: 0.75rem;
        text-decoration: none;
        font-weight: 600;
        font-size: 1.125rem;
        transition: all 0.3s;
        border: none;
        cursor: pointer;
    }

    .shop-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(44, 120, 115, 0.3);
        color: white;
    }

    .summary-section {
        background: white;
        border-radius: 1rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        border: 1px solid #e2e8f0;
        position: sticky;
        top: 2rem;
        overflow: hidden;
    }

    .summary-header {
        padding: 1.5rem;
        border-bottom: 2px solid #f1f5f9;
        background: linear-gradient(135deg, #2c7873 0%, #6fb98f 100%);
        color: white;
    }

    .summary-header h2 {
        font-size: 1.25rem;
        font-weight: 700;
        margin: 0;
    }

    .summary-details {
        padding: 1.5rem;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        font-size: 1rem;
        color: #475569;
    }

    .summary-row span:last-child {
        font-weight: 600;
        color: #0f172a;
    }

    .summary-delivery {
        color: #10b981 !important;
        font-weight: 700 !important;
    }

    .summary-divider {
        height: 1px;
        background: linear-gradient(90deg, transparent, #e2e8f0, transparent);
        margin: 1.5rem 0;
    }

    .summary-total {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 1rem;
        border-top: 2px dashed #e2e8f0;
    }

    .summary-total-label {
        font-size: 1.25rem;
        font-weight: 700;
        color: #0f172a;
    }

    .summary-total-amount {
        font-size: 2rem;
        font-weight: 700;
        color: #2c7873;
    }

    .summary-actions {
        padding: 0 1.5rem 1.5rem 1.5rem;
    }

    .checkout-btn {
        width: 100%;
        background: linear-gradient(135deg, #2c7873 0%, #6fb98f 100%);
        color: white;
        border: none;
        padding: 1rem 1.5rem;
        border-radius: 0.75rem;
        font-size: 1.125rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s;
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        text-decoration: none;
    }

    .checkout-btn:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(44, 120, 115, 0.3);
        color: white;
    }

    .checkout-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none;
    }

    .continue-btn {
        width: 100%;
        background: white;
        color: #475569;
        border: 2px solid #e2e8f0;
        padding: 0.875rem 1.5rem;
        border-radius: 0.75rem;
        font-size: 1rem;
        font-weight: 600;
        text-decoration: none;
        text-align: center;
        display: block;
        transition: all 0.2s;
    }

    .continue-btn:hover {
        border-color: #2c7873;
        color: #2c7873;
        background: #f8fafc;
    }

    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
        margin-top: 2rem;
    }

    .feature-card {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1.25rem;
        background: white;
        border-radius: 0.75rem;
        border: 1px solid #e2e8f0;
        transition: all 0.2s;
    }

    .feature-card:hover {
        border-color: #2c7873;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(44, 120, 115, 0.1);
    }

    .feature-icon {
        width: 3rem;
        height: 3rem;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 1.5rem;
    }

    .feature-icon-green {
        background: #d1fae5;
        color: #10b981;
    }

    .feature-icon-blue {
        background: #dbeafe;
        color: #3b82f6;
    }

    .feature-icon-amber {
        background: #fef3c7;
        color: #f59e0b;
    }

    .feature-content h3 {
        font-size: 0.9375rem;
        font-weight: 600;
        color: #0f172a;
        margin: 0 0 0.25rem 0;
    }

    .feature-content p {
        font-size: 0.8125rem;
        color: #64748b;
        margin: 0;
    }

    .summary-guarantees {
        padding: 1rem 1.5rem 1.5rem 1.5rem;
        border-top: 1px solid #f1f5f9;
        background: #f8fafc;
    }

    .guarantee-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 0.875rem;
        color: #64748b;
        margin-bottom: 0.75rem;
    }

    .guarantee-item:last-child {
        margin-bottom: 0;
    }

    .guarantee-icon {
        color: #10b981;
        font-size: 1.125rem;
    }
</style>

@php
    $panier = session('panier', []);
    $panier_count = 0;
    $total = 0;
    
    foreach ($panier as $item) {
        $panier_count += $item['quantite'] ?? 0;
        $total += ($item['prix'] ?? 0) * ($item['quantite'] ?? 0);
    }
@endphp

<div class="cart-page">
    <div class="container">
        
        <!-- Breadcrumb -->
        <nav class="breadcrumb-nav">
            <a href="{{ route('catalogue.index') }}">
                <i class="fas fa-home"></i> Accueil
            </a>
            <span>/</span>
            <span class="breadcrumb-current">Mon Panier</span>
        </nav>

        <!-- Page Header -->
        <div class="page-header">
            <h1><i class="fas fa-shopping-cart"></i> Mon Panier</h1>
            <p>Vérifiez vos articles avant de finaliser votre commande</p>
        </div>

        <!-- Messages Flash -->
        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Cart Grid -->
        <div class="cart-grid">
            
            <!-- Section Articles -->
            <div>
                <div class="cart-section">
                    
                    <!-- Cart Header -->
                    <div class="cart-header">
                        <div class="cart-header-left">
                            <div class="cart-icon">
                                <i class="fas fa-shopping-bag"></i>
                            </div>
                            <div class="cart-title">
                                <h2>Mes Articles</h2>
                                <p class="cart-subtitle">
                                    {{ $panier_count }} article{{ $panier_count > 1 ? 's' : '' }} dans votre panier
                                </p>
                            </div>
                        </div>
                        @if($panier_count > 0)
                            <form action="{{ route('panier.vider') }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="clear-btn" onclick="return confirm('Voulez-vous vraiment vider votre panier ?')">
                                    <i class="fas fa-trash-alt"></i>
                                    <span>Vider</span>
                                </button>
                            </form>
                        @endif
                    </div>

                    <!-- Cart Items -->
                    <div class="cart-items">
                        @if($panier_count > 0 && !empty($panier))
                            @foreach($panier as $item)
                                @php
                                    $itemId = $item['id'] ?? null;
                                    $itemName = $item['nom'] ?? 'Produit';
                                    $itemPrice = floatval($item['prix'] ?? 0);
                                    $itemQuantity = intval($item['quantite'] ?? 1);
                                    $itemImage = $item['image'] ?? null;
                                    $itemCategory = $item['categorie'] ?? '';
                                    $itemProducer = $item['producteur'] ?? '';
                                    $subtotal = $itemPrice * $itemQuantity;
                                    $stock = $item['stock'] ?? 100;
                                @endphp
                                
                                @if($itemId)
                                <div class="cart-item" data-item-id="{{ $itemId }}">
                                    <div class="item-content">
                                        
                                        <!-- Image du produit -->
                                        <div class="item-image">
                                            @if($itemImage)
                                                <img src="{{ $itemImage }}" 
                                                     alt="{{ $itemName }}" 
                                                     onerror="this.parentElement.innerHTML='<div class=\'item-placeholder\'><i class=\'fas fa-leaf\'></i></div>'">
                                            @else
                                                <div class="item-placeholder">
                                                    <i class="fas fa-leaf"></i>
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <!-- Informations du produit -->
                                        <div class="item-info">
                                            <div class="item-name">{{ $itemName }}</div>
                                            <div class="item-price">{{ number_format($itemPrice, 0, ',', ' ') }} FCFA</div>
                                            <div class="item-badges">
                                                @if($itemCategory)
                                                    <span class="badge badge-category">
                                                        {{ ucfirst($itemCategory) }}
                                                    </span>
                                                @endif
                                                <span class="badge badge-fresh">
                                                    <i class="fas fa-leaf"></i> Frais
                                                </span>
                                                @if($itemProducer)
                                                    <span class="badge badge-local">
                                                        <i class="fas fa-user"></i> {{ $itemProducer }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <!-- Contrôles de quantité et actions -->
                                        <div class="item-controls">
                                            
                                            <!-- Contrôles de quantité -->
                                            <div class="quantity-controls">
                                                <!-- Bouton diminuer -->
                                                <form action="{{ route('panier.mettre-a-jour', $itemId) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="quantite" value="{{ max(1, $itemQuantity - 1) }}">
                                                    <button type="submit" 
                                                            class="qty-btn" 
                                                            {{ $itemQuantity <= 1 ? 'disabled' : '' }}>
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                </form>
                                                
                                                <!-- Affichage quantité -->
                                                <span class="quantity-display">{{ $itemQuantity }}</span>
                                                
                                                <!-- Bouton augmenter -->
                                                <form action="{{ route('panier.mettre-a-jour', $itemId) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="quantite" value="{{ $itemQuantity + 1 }}">
                                                    <button type="submit" 
                                                            class="qty-btn"
                                                            {{ $itemQuantity >= $stock ? 'disabled' : '' }}>
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </form>
                                            </div>
                                            
                                            <!-- Prix et bouton supprimer -->
                                            <div class="item-actions">
                                                <div class="item-subtotal">
                                                    {{ number_format($subtotal, 0, ',', ' ') }} FCFA
                                                </div>
                                                <form action="{{ route('panier.supprimer', $itemId) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="remove-btn">
                                                        <i class="fas fa-trash-alt"></i>
                                                        <span>Retirer</span>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            @endforeach
                        @else
                            <!-- Panier vide -->
                            <div class="empty-cart">
                                <div class="empty-icon">
                                    <i class="fas fa-shopping-bag"></i>
                                </div>
                                <h3>Votre panier est vide</h3>
                                <p>Découvrez nos produits frais et locaux directement des producteurs sénégalais</p>
                                <a href="{{ route('catalogue.index') }}" class="shop-btn">
                                    <i class="fas fa-store"></i>
                                    <span>Découvrir nos produits</span>
                                </a>
                            </div>
                        @endif
                    </div>

                </div>

                <!-- Features Cards -->
                @if($panier_count > 0)
                <div class="features-grid">
                    <div class="feature-card">
                        <div class="feature-icon feature-icon-green">
                            <i class="fas fa-truck"></i>
                        </div>
                        <div class="feature-content">
                            <h3>Livraison gratuite</h3>
                            <p>Sur toutes vos commandes</p>
                        </div>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon feature-icon-blue">
                            <i class="fas fa-shield-check"></i>
                        </div>
                        <div class="feature-content">
                            <h3>Paiement sécurisé</h3>
                            <p>Transactions protégées à 100%</p>
                        </div>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon feature-icon-amber">
                            <i class="fas fa-leaf"></i>
                        </div>
                        <div class="feature-content">
                            <h3>Produits frais</h3>
                            <p>Qualité garantie</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Section Résumé -->
            <div>
                <div class="summary-section">
                    
                    <!-- Summary Header -->
                    <div class="summary-header">
                        <h2><i class="fas fa-receipt"></i> Résumé de la commande</h2>
                    </div>

                    <!-- Summary Details -->
                    <div class="summary-details">
                        
                        <!-- Sous-total -->
                        <div class="summary-row">
                            <span><i class="fas fa-box"></i> Sous-total ({{ $panier_count }} article{{ $panier_count > 1 ? 's' : '' }})</span>
                            <span>{{ number_format($total, 0, ',', ' ') }} FCFA</span>
                        </div>

                        <!-- Livraison -->
                        <div class="summary-row">
                            <span><i class="fas fa-truck"></i> Livraison</span>
                            <span class="summary-delivery">Gratuit <i class="fas fa-check-circle"></i></span>
                        </div>

                        <!-- Divider -->
                        <div class="summary-divider"></div>

                        <!-- Total -->
                        <div class="summary-total">
                            <span class="summary-total-label">Total à payer</span>
                            <span class="summary-total-amount">{{ number_format($total, 0, ',', ' ') }} FCFA</span>
                        </div>

                    </div>

                    <!-- Summary Actions -->
                    <div class="summary-actions">
                        @if($panier_count > 0)
                            <a href="{{ route('panier.checkout') }}" class="checkout-btn">
                                <i class="fas fa-lock"></i>
                                <span>Procéder au paiement</span>
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        @else
                            <button class="checkout-btn" disabled>
                                <i class="fas fa-shopping-cart"></i>
                                <span>Panier vide</span>
                            </button>
                        @endif
                        
                        <a href="{{ route('catalogue.index') }}" class="continue-btn">
                            <i class="fas fa-arrow-left"></i> Continuer mes achats
                        </a>
                    </div>

                    <!-- Guarantees -->
                    <div class="summary-guarantees">
                        <div class="guarantee-item">
                            <i class="fas fa-check-circle guarantee-icon"></i>
                            <span>Paiement 100% sécurisé</span>
                        </div>
                        <div class="guarantee-item">
                            <i class="fas fa-check-circle guarantee-icon"></i>
                            <span>Satisfait ou remboursé</span>
                        </div>
                        <div class="guarantee-item">
                            <i class="fas fa-check-circle guarantee-icon"></i>
                            <span>Support client 24/7</span>
                        </div>
                    </div>

                </div>
            </div>

        </div>

    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('🛒 Page Panier chargée');
    console.log('📊 Statistiques:', {
        articles: {{ $panier_count }},
        total: '{{ number_format($total, 0, ',', ' ') }} FCFA',
        items: {{ count($panier) }}
    });
    
    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-20px)';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });
    
    // Confirmation avant suppression d'un produit
    const removeBtns = document.querySelectorAll('.remove-btn');
    removeBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            const itemName = this.closest('.cart-item').querySelector('.item-name').textContent;
            if (!confirm(`Voulez-vous vraiment retirer "${itemName}" du panier ?`)) {
                e.preventDefault();
            }
        });
    });
    
    // Loading state pour les formulaires
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function() {
            const button = this.querySelector('button[type="submit"]');
            if (button && !button.disabled) {
                button.disabled = true;
                button.style.opacity = '0.6';
                
                // Ajouter un spinner si c'est un bouton de quantité
                if (button.classList.contains('qty-btn')) {
                    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                }
            }
        });
    });
    
    // Animation smooth pour le bouton checkout
    const checkoutBtn = document.querySelector('.checkout-btn:not([disabled])');
    if (checkoutBtn) {
        checkoutBtn.addEventListener('click', function(e) {
            // Ajouter une animation avant la redirection
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>Redirection...</span>';
            this.style.opacity = '0.8';
        });
    }

    // Mettre à jour le badge du panier dans le header
    updateCartBadge({{ $panier_count }});
});

// Fonction pour mettre à jour le badge du panier dans le header
function updateCartBadge(count) {
    const cartBadge = document.querySelector('.cart-badge');
    const cartCount = document.querySelector('.cart-count');
    
    if (cartBadge) {
        cartBadge.textContent = count;
        cartBadge.style.display = count > 0 ? 'inline-block' : 'none';
    }
    
    if (cartCount) {
        cartCount.textContent = count;
    }
}
</script>
@endsection