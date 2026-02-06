@extends('layouts.app')

@section('title', 'Finaliser la Commande - PayTech')

@section('styles')
<style>
.payment-method {
    border: 2px solid #e9ecef;
    border-radius: 10px;
    padding: 15px;
    margin-bottom: 15px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.payment-method:hover {
    border-color: #2c7873;
    background-color: #f8f9fa;
}

.payment-method.selected {
    border-color: #2c7873;
    background-color: #e8f4f3;
}

.payment-icon {
    font-size: 2rem;
    margin-right: 15px;
}

.order-summary {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 20px;
    position: sticky;
    top: 20px;
}

.product-item {
    border-bottom: 1px solid #dee2e6;
    padding: 10px 0;
}

.btn-paytech {
    background: linear-gradient(135deg, #28a745, #20c997);
    border: none;
    padding: 15px 30px;
    font-size: 1.1rem;
    font-weight: 600;
}

.btn-paytech:hover {
    background: linear-gradient(135deg, #218838, #1e9e8a);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

.btn-paytech:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
}

.cart-item-img {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 8px;
}

.quantity-badge {
    background: #28a745;
    color: white;
    border-radius: 50%;
    width: 24px;
    height: 24px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
}
</style>
@endsection

@section('content')
<div class="container py-4">
    <h2 class="mb-4 text-center fw-bold text-success">
        <i class="fas fa-credit-card me-2"></i> Finaliser la commande
    </h2>

    <div class="row">
        <!-- Récapitulatif du panier -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-shopping-basket me-2"></i>Votre Panier
                        <span class="badge bg-success ms-2" id="cartItemCount">0</span>
                    </h5>
                </div>
                <div class="card-body">
                    <div id="cartItemsContainer">
                        @if(isset($panier) && count($panier) > 0)
                            @foreach($panier as $item)
                            <div class="cart-item mb-3 p-3 rounded border">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        @if(isset($item['image']) && $item['image'])
                                            <img src="{{ $item['image'] }}" class="cart-item-img" alt="{{ $item['nom'] }}">
                                        @else
                                            <div class="cart-item-img bg-light d-flex align-items-center justify-content-center">
                                                <i class="fas fa-image text-muted"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col">
                                        <h6 class="mb-1">{{ $item['nom'] }}</h6>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <span class="text-muted me-2">{{ number_format($item['prix'], 0, ',', ' ') }} FCFA × </span>
                                                <span class="quantity-badge">{{ $item['quantite'] }}</span>
                                            </div>
                                            <div class="fw-bold text-success">
                                                {{ number_format($item['prix'] * $item['quantite'], 0, ',', ' ') }} FCFA
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-shopping-basket fa-3x text-muted mb-3"></i>
                                <p class="text-muted mb-0">Votre panier est vide</p>
                            </div>
                        @endif
                    </div>
                    
                    @if(isset($panier) && count($panier) > 0)
                    <div class="text-end mt-3">
                        <a href="{{ route('catalogue.index') }}" class="btn btn-outline-success btn-sm">
                            <i class="fas fa-plus me-1"></i>Ajouter d'autres produits
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Paiement PayTech -->
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-lock me-2"></i>Paiement sécurisé</h5>
                </div>
                <div class="card-body">
                    <!-- Récapitulatif commande -->
                    <div class="order-summary mb-4">
                        <h6 class="mb-3"><i class="fas fa-receipt me-2"></i>Récapitulatif de commande</h6>
                        
                        <div id="orderItems" class="mb-3">
                            <!-- Les articles seront ajoutés par JavaScript -->
                        </div>
                        
                        <div class="d-flex justify-content-between mt-3 pt-3 border-top">
                            <strong class="fs-5">Total:</strong>
                            <strong class="fs-5 text-success" id="orderTotal">0 FCFA</strong>
                        </div>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Vous allez être redirigé vers PayTech pour effectuer votre paiement sécurisé.
                    </div>
                    
                    <div class="text-center">
                        <button class="btn btn-paytech btn-lg" id="paytechBtn">
                            <i class="fas fa-shield-alt me-2"></i>Payer avec PayTech
                        </button>
                    </div>

                    <div class="mt-3 text-center">
                        <small class="text-muted">
                            <i class="fas fa-lock me-1"></i>
                            Transactions 100% sécurisées - Cryptage SSL
                        </small>
                    </div>
                </div>
            </div>
            
            <!-- Retour au catalogue -->
            <div class="text-center mt-3">
                <a href="{{ route('catalogue.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Retour au catalogue
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const paytechBtn = document.getElementById('paytechBtn');
    const orderItems = document.getElementById('orderItems');
    const orderTotal = document.getElementById('orderTotal');
    const cartItemCount = document.getElementById('cartItemCount');
    const cartItemsContainer = document.getElementById('cartItemsContainer');

    console.log('🚀 Initialisation de la page de paiement');

    // Récupérer le panier depuis localStorage (catalogue)
    const panier = JSON.parse(localStorage.getItem('shopping_cart')) || [];
    
    console.log('🛒 Panier récupéré du localStorage:', panier);

    // Mettre à jour le compteur
    const totalItems = panier.reduce((total, item) => total + item.quantity, 0);
    cartItemCount.textContent = totalItems;

    if (panier.length === 0) {
        orderItems.innerHTML = `
            <div class="alert alert-warning">
                <i class="fas fa-shopping-cart me-2"></i>
                Votre panier est vide
            </div>`;
        orderTotal.textContent = '0 FCFA';
        paytechBtn.disabled = true;
        paytechBtn.innerHTML = '<i class="fas fa-ban me-2"></i>Panier vide';
        return;
    }

    // Affichage des articles dans le récapitulatif
    let total = 0;
    let html = '';
    
    panier.forEach(item => {
        const itemTotal = item.price * item.quantity;
        total += itemTotal;
        html += `
        <div class="product-item d-flex justify-content-between align-items-center">
            <div class="flex-grow-1">
                <div class="fw-medium">${item.name}</div>
                <small class="text-muted">${item.quantity} x ${item.price.toLocaleString()} FCFA</small>
            </div>
            <div class="fw-bold text-success">${itemTotal.toLocaleString()} FCFA</div>
        </div>`;
    });
    
    orderItems.innerHTML = html;
    orderTotal.textContent = total.toLocaleString() + ' FCFA';

    console.log('✅ Récapitulatif affiché - Total:', total);

    // Gestion du clic sur le bouton PayTech
    paytechBtn.addEventListener('click', function() {
        console.log('🔄 Clic sur le bouton PayTech');
        
        // Préparer les données pour PayTech (même format que le catalogue)
        const cartForPaytech = panier.map(item => ({
            id: item.id,
            name: item.name,
            price: item.price,
            quantity: item.quantity
        }));

        const totalAmount = total;

        console.log('📦 Données préparées:', {
            articles: cartForPaytech.length,
            total: totalAmount
        });

        // Construire l'URL avec les paramètres
        const baseUrl = '{{ route("paiement.paytech.process") }}';
        const params = new URLSearchParams({
            panier: JSON.stringify(cartForPaytech),
            total: totalAmount
        });

        const finalUrl = `${baseUrl}?${params.toString()}`;
        
        console.log('🔗 URL de redirection:', finalUrl);

        // Afficher le loading
        paytechBtn.disabled = true;
        paytechBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Redirection...';

        // Sauvegarder le panier dans la session avant de rediriger
        fetch('{{ route("panier.synchroniser") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ panier: cartForPaytech, total: totalAmount })
        })
        .then(response => response.json())
        .then(data => {
            console.log('✅ Panier synchronisé:', data);
            
            // Rediriger vers PayTech après un court délai
            setTimeout(() => {
                window.location.href = finalUrl;
            }, 500);
        })
        .catch(error => {
            console.error('❌ Erreur synchronisation:', error);
            // Rediriger quand même
            setTimeout(() => {
                window.location.href = finalUrl;
            }, 500);
        });
    });

    // Si le panier est vide côté serveur mais qu'il y a des données dans localStorage
    // Mettre à jour l'affichage
    @if(!isset($panier) || count($panier) === 0)
    if (panier.length > 0) {
        // Générer l'HTML pour les articles du panier
        let cartHtml = '';
        panier.forEach(item => {
            cartHtml += `
            <div class="cart-item mb-3 p-3 rounded border">
                <div class="row align-items-center">
                    <div class="col-auto">
                        ${item.image ? 
                            `<img src="${item.image}" class="cart-item-img" alt="${item.name}">` :
                            `<div class="cart-item-img bg-light d-flex align-items-center justify-content-center">
                                <i class="fas fa-image text-muted"></i>
                            </div>`
                        }
                    </div>
                    <div class="col">
                        <h6 class="mb-1">${item.name}</h6>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted me-2">${item.price.toLocaleString()} FCFA × </span>
                                <span class="quantity-badge">${item.quantity}</span>
                            </div>
                            <div class="fw-bold text-success">
                                ${(item.price * item.quantity).toLocaleString()} FCFA
                            </div>
                        </div>
                    </div>
                </div>
            </div>`;
        });
        
        cartItemsContainer.innerHTML = cartHtml;
    }
    @endif

    // Vérifier que le bouton fonctionne
    console.log('✅ Bouton PayTech initialisé');
});
</script>
@endsection