{{-- resources/views/catalogue/partials/products-grid.blade.php --}}

@foreach($produits as $produit)
<div class="col-lg-4 col-md-6 col-sm-6 mb-4">
    <div class="card border-0 shadow-sm h-100 product-card position-relative"
         data-product-id="{{ $produit->id }}"
         data-product-name="{{ $produit->nom }}"
         data-product-price="{{ $produit->prix }}"
         data-product-stock="{{ $produit->quantite }}"
         data-product-image="{{ $produit->image_url }}"
         data-product-category="{{ $produit->categorie->nom ?? '' }}"
         data-product-producteur="{{ $produit->user->name ?? 'Producteur' }}">

        <!-- Badge stock -->
        @if($produit->quantite > 0)
            <span class="position-absolute top-0 start-0 badge bg-success m-2" style="z-index: 10;">
                <i class="fas fa-check me-1"></i>En stock
            </span>
        @else
            <span class="position-absolute top-0 start-0 badge bg-danger m-2" style="z-index: 10;">
                <i class="fas fa-times me-1"></i>Rupture
            </span>
        @endif

        <!-- Image du produit -->
        <div class="position-relative overflow-hidden rounded-top">
            @if($produit->image_url)
                <img src="{{ $produit->image_url }}" 
                     alt="{{ $produit->nom }}" 
                     class="card-img-top product-image"
                     style="height: 200px; object-fit: cover;">
            @else
                <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                    <i class="fas fa-seedling text-muted fa-3x"></i>
                </div>
            @endif
            
            <!-- Overlay au hover -->
            <div class="product-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" 
                 style="background: rgba(0,0,0,0.1); opacity: 0; transition: opacity 0.3s;">
                <div class="btn-group">
                    <a href="{{ route('catalogue.show', $produit->id) }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-eye"></i>
                    </a>
                    @if($produit->quantite > 0)
                        <button class="btn btn-success btn-sm add-to-cart-btn" 
                                data-product-id="{{ $produit->id }}"
                                type="button">
                            <i class="fas fa-shopping-cart"></i>
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <div class="card-body d-flex flex-column">
            <!-- Nom du produit -->
            <h5 class="card-title fw-bold text-dark mb-2">
                <a href="{{ route('catalogue.show', $produit->id) }}" 
                   class="text-decoration-none text-dark product-title"
                   data-product-name="{{ $produit->nom }}">
                    {{ $produit->nom }}
                </a>
            </h5>

            <!-- Producteur -->
            <p class="text-muted small mb-2">
                <i class="fas fa-user-tie me-1"></i>
                <span class="product-producteur" data-product-producteur="{{ $produit->user->name ?? 'Producteur' }}">
                    {{ $produit->user->name ?? 'Producteur' }}
                </span>
            </p>

            <!-- Catégorie -->
            @if($produit->categorie)
            <p class="text-muted small mb-2">
                <i class="fas fa-tag me-1"></i>
                <span class="product-category" data-product-category="{{ $produit->categorie->nom }}">
                    {{ $produit->categorie->nom }}
                </span>
            </p>
            @endif

            <!-- Description -->
            <p class="card-text text-muted small flex-grow-1">
                {{ Str::limit($produit->description, 80) }}
            </p>

            <!-- Prix et actions -->
            <div class="d-flex justify-content-between align-items-center mt-auto">
                <div>
                    <span class="h5 text-success fw-bold mb-0 product-price" 
                          data-product-price="{{ $produit->prix }}">
                        {{ number_format($produit->prix, 0, ',', ' ') }} FCFA
                    </span>
                    <small class="text-muted d-block">
                        l'unité
                        <span class="d-none product-stock" data-product-stock="{{ $produit->quantite }}">
                            {{ $produit->quantite }} disponible(s)
                        </span>
                    </small>
                </div>
                
                @if($produit->quantite > 0)
                    <div class="text-end">
                        <!-- Sélecteur de quantité -->
                        <div class="quantity-selector mb-2 d-flex align-items-center justify-content-end">
                            <button class="quantity-btn minus btn btn-outline-secondary btn-sm me-1" 
                                    data-product-id="{{ $produit->id }}"
                                    type="button"
                                    style="width: 32px; height: 32px; border-radius: 50%;">
                                <i class="fas fa-minus fa-xs"></i>
                            </button>
                            <input type="number" 
                                   id="quantity-{{ $produit->id }}" 
                                   class="form-control form-control-sm text-center quantity-input" 
                                   style="width: 50px;"
                                   min="1" 
                                   max="{{ $produit->quantite }}" 
                                   value="1"
                                   data-product-id="{{ $produit->id }}">
                            <button class="quantity-btn plus btn btn-outline-secondary btn-sm ms-1" 
                                    data-product-id="{{ $produit->id }}"
                                    type="button"
                                    style="width: 32px; height: 32px; border-radius: 50%;">
                                <i class="fas fa-plus fa-xs"></i>
                            </button>
                        </div>
                        
                        <!-- Bouton Ajouter au panier -->
                        <button class="btn btn-success btn-sm w-100 add-to-cart-btn" 
                                data-product-id="{{ $produit->id }}"
                                type="button"
                                style="min-width: 120px;">
                            <i class="fas fa-cart-plus me-1"></i>
                            <span class="btn-text">Ajouter</span>
                        </button>
                    </div>
                @else
                    <button class="btn btn-outline-secondary btn-sm" disabled>
                        <i class="fas fa-ban me-1"></i>Indisponible
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- Résumé du panier flottant -->
<div id="cart-summary" class="cart-summary hidden">
    <div class="d-flex align-items-center">
        <i class="fas fa-shopping-cart fa-lg me-2"></i>
        <div>
            <div class="text-white-50 small">Total panier</div>
            <div class="cart-total" id="cart-total-amount">0 FCFA</div>
        </div>
    </div>
    <button onclick="proceedToCheckout()" 
            class="checkout-btn" 
            id="checkout-btn"
            type="button">
        Voir le panier
        <i class="fas fa-arrow-right ms-1"></i>
    </button>
</div>

@if($produits->count() == 0)
<div class="col-12">
    <div class="text-center py-5">
        <i class="fas fa-search fa-3x text-muted mb-3"></i>
        <h4 class="text-muted">Aucun produit trouvé</h4>
        <p class="text-muted">Essayez de modifier vos critères de recherche</p>
        <button class="btn btn-primary" onclick="clearFilters()">
            <i class="fas fa-redo me-2"></i>Réinitialiser les filtres
        </button>
    </div>
</div>
@endif

<script>
// Initialiser les interactions des produits
document.addEventListener('DOMContentLoaded', function() {
    initializeProductCards();
});

function initializeProductCards() {
    // Animation au survol
    document.querySelectorAll('.product-card').forEach(card => {
        const overlay = card.querySelector('.product-overlay');
        
        card.addEventListener('mouseenter', function() {
            if (overlay) overlay.style.opacity = '1';
        });
        
        card.addEventListener('mouseleave', function() {
            if (overlay) overlay.style.opacity = '0';
        });
    });

    // Initialiser les boutons de quantité
    initializeQuantityButtons();
    
    // Initialiser les boutons d'ajout au panier
    initializeAddToCartButtons();
}

function initializeQuantityButtons() {
    // Boutons plus
    document.querySelectorAll('.quantity-btn.plus').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const productId = this.getAttribute('data-product-id');
            const quantityInput = document.getElementById(`quantity-${productId}`);
            const currentQuantity = parseInt(quantityInput.value) || 1;
            const maxQuantity = parseInt(quantityInput.getAttribute('max')) || 99;
            
            if (currentQuantity < maxQuantity) {
                quantityInput.value = currentQuantity + 1;
                updateAddButtonText(productId, currentQuantity + 1);
            } else {
                showToast('Stock maximum atteint', 'warning');
            }
        });
    });
    
    // Boutons moins
    document.querySelectorAll('.quantity-btn.minus').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const productId = this.getAttribute('data-product-id');
            const quantityInput = document.getElementById(`quantity-${productId}`);
            const currentQuantity = parseInt(quantityInput.value) || 1;
            const minQuantity = parseInt(quantityInput.getAttribute('min')) || 1;
            
            if (currentQuantity > minQuantity) {
                quantityInput.value = currentQuantity - 1;
                updateAddButtonText(productId, currentQuantity - 1);
            }
        });
    });
    
    // Validation des inputs de quantité
    document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('change', function() {
            const productId = this.getAttribute('data-product-id');
            const value = parseInt(this.value) || 1;
            const min = parseInt(this.getAttribute('min')) || 1;
            const max = parseInt(this.getAttribute('max')) || 99;
            
            if (value < min) {
                this.value = min;
            } else if (value > max) {
                this.value = max;
                showToast('Stock maximum atteint', 'warning');
            }
            
            updateAddButtonText(productId, this.value);
        });
        
        input.addEventListener('blur', function() {
            if (!this.value || this.value < 1) {
                this.value = 1;
                const productId = this.getAttribute('data-product-id');
                updateAddButtonText(productId, 1);
            }
        });
    });
}

function initializeAddToCartButtons() {
    document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const productId = this.getAttribute('data-product-id');
            addProductToCart(productId);
        });
    });
}

function updateAddButtonText(productId, quantity) {
    const addButton = document.querySelector(`.add-to-cart-btn[data-product-id="${productId}"]`);
    if (addButton) {
        const btnText = addButton.querySelector('.btn-text');
        if (btnText) {
            if (quantity > 1) {
                btnText.textContent = `Ajouter ${quantity}`;
            } else {
                btnText.textContent = 'Ajouter';
            }
        }
    }
}

function getProductData(productId) {
    const productCard = document.querySelector(`[data-product-id="${productId}"]`);
    if (!productCard) {
        console.error('❌ Carte produit non trouvée pour ID:', productId);
        return null;
    }

    // Récupérer toutes les données depuis les attributs data
    const productData = {
        id: productId,
        name: productCard.getAttribute('data-product-name') || 
              productCard.querySelector('.product-title')?.textContent.trim() || 'Produit',
        price: parseFloat(productCard.getAttribute('data-product-price')) || 0,
        image: productCard.getAttribute('data-product-image') || '/images/default-product.jpg',
        stock: parseInt(productCard.getAttribute('data-product-stock')) || 0,
        category: productCard.getAttribute('data-product-category') || '',
        producteur: productCard.getAttribute('data-product-producteur') || 'Producteur'
    };

    console.log('📦 Données produit extraites:', productData);
    return productData;
}

function addProductToCart(productId) {
    console.log('🔄 Tentative d\'ajout du produit:', productId);
    
    const productData = getProductData(productId);
    if (!productData) {
        showToast('Produit non trouvé', 'error');
        return;
    }

    const quantityInput = document.getElementById(`quantity-${productId}`);
    let quantity = 1;
    
    if (quantityInput) {
        quantity = parseInt(quantityInput.value) || 1;
    }

    console.log('➕ Ajout au panier:', { productData, quantity });

    // Vérifications
    if (quantity <= 0) {
        showToast('Veuillez sélectionner une quantité', 'warning');
        return;
    }

    if (quantity > productData.stock) {
        showToast('Quantité supérieure au stock disponible', 'warning');
        return;
    }

    // Récupérer le panier actuel
    let cart = JSON.parse(localStorage.getItem('fubadj_cart')) || [];
    
    // Vérifier si le produit existe déjà
    const existingItemIndex = cart.findIndex(item => item.id == productId);
    
    if (existingItemIndex > -1) {
        // Mettre à jour la quantité existante
        const newQuantity = cart[existingItemIndex].quantity + quantity;
        if (newQuantity > productData.stock) {
            showToast('Stock insuffisant pour cette quantité', 'warning');
            return;
        }
        cart[existingItemIndex].quantity = newQuantity;
        console.log('📝 Quantité mise à jour:', cart[existingItemIndex]);
    } else {
        // Nouvel article
        const newItem = {
            id: productData.id,
            name: productData.name,
            price: productData.price,
            image: productData.image,
            quantity: quantity,
            stock: productData.stock,
            category: productData.category,
            producteur: productData.producteur,
            added_at: new Date().toISOString()
        };
        cart.push(newItem);
        console.log('✨ Nouveau produit ajouté:', newItem);
    }

    // Sauvegarder dans localStorage
    localStorage.setItem('fubadj_cart', JSON.stringify(cart));
    console.log('💾 Panier sauvegardé:', cart);

    // Réinitialiser la quantité à 1
    if (quantityInput) {
        quantityInput.value = 1;
        updateAddButtonText(productId, 1);
    }

    // Mettre à jour l'affichage du panier
    updateCartDisplay();
    updateCartSummary();

    // Animation du bouton
    animateAddToCart(productId);

    // Notification
    showToast(`✅ ${quantity} x ${productData.name} ajouté au panier`, 'success');
}

function animateAddToCart(productId) {
    const btn = document.querySelector(`.add-to-cart-btn[data-product-id="${productId}"]`);
    if (btn) {
        const originalHTML = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check"></i> Ajouté !';
        btn.classList.remove('btn-success');
        btn.classList.add('btn-primary');
        
        setTimeout(() => {
            btn.innerHTML = originalHTML;
            btn.classList.remove('btn-primary');
            btn.classList.add('btn-success');
        }, 1500);
    }
}

function updateCartDisplay() {
    const cart = JSON.parse(localStorage.getItem('fubadj_cart')) || [];
    const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
    
    // Mettre à jour le badge du panier dans le header
    const cartBadge = document.querySelector('.cart-badge');
    if (cartBadge) {
        cartBadge.textContent = totalItems;
        if (totalItems > 0) {
            cartBadge.style.display = 'inline-block';
        } else {
            cartBadge.style.display = 'none';
        }
    }
    
    console.log('📊 Affichage panier mis à jour:', totalItems + ' articles');
}

function updateCartSummary() {
    const cart = JSON.parse(localStorage.getItem('fubadj_cart')) || [];
    const cartSummary = document.getElementById('cart-summary');
    const cartTotalAmount = document.getElementById('cart-total-amount');
    const checkoutBtn = document.getElementById('checkout-btn');
    
    console.log('🛒 Mise à jour du résumé du panier:', cart);
    
    // Calculer le total
    const totalAmount = cart.reduce((sum, item) => {
        const price = parseFloat(item.price) || 0;
        const quantity = parseInt(item.quantity) || 0;
        const itemTotal = price * quantity;
        return sum + itemTotal;
    }, 0);
    
    console.log('💰 Total calculé:', totalAmount + ' FCFA');
    
    // Mettre à jour l'affichage du total
    if (cartTotalAmount) {
        cartTotalAmount.textContent = formatPrice(totalAmount) + ' FCFA';
    }
    
    // Afficher ou masquer le résumé
    if (cartSummary && checkoutBtn) {
        if (cart.length > 0 && totalAmount > 0) {
            cartSummary.classList.remove('hidden');
            checkoutBtn.disabled = false;
        } else {
            cartSummary.classList.add('hidden');
            checkoutBtn.disabled = true;
        }
    }
}

function proceedToCheckout() {
    const cart = JSON.parse(localStorage.getItem('fubadj_cart')) || [];
    
    console.log('🚀 Passage au paiement...');
    
    if (cart.length === 0) {
        showToast('Votre panier est vide', 'warning');
        return;
    }

    // Redirection vers la page panier
    window.location.href = '{{ route("panier.index") }}';
}

function formatPrice(price) {
    return new Intl.NumberFormat('fr-FR').format(price);
}

function showToast(message, type = 'info') {
    // Créer le conteneur de toast s'il n'existe pas
    let toastContainer = document.getElementById('toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toast-container';
        toastContainer.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 10px;
        `;
        document.body.appendChild(toastContainer);
    }
    
    const colors = {
        success: '#10b981',
        error: '#dc2626',
        warning: '#f59e0b',
        info: '#3b82f6'
    };
    
    const icons = {
        success: 'fa-check-circle',
        error: 'fa-exclamation-circle',
        warning: 'fa-exclamation-triangle',
        info: 'fa-info-circle'
    };
    
    const toast = document.createElement('div');
    toast.style.cssText = `
        background: ${colors[type] || colors.info};
        color: white;
        padding: 16px 24px;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 300px;
        animation: slideIn 0.3s ease-out;
        font-weight: 500;
    `;
    
    toast.innerHTML = `
        <i class="fas ${icons[type] || icons.info}"></i>
        <span>${message}</span>
    `;
    
    // Ajouter l'animation CSS
    if (!document.getElementById('toast-animation-style')) {
        const style = document.createElement('style');
        style.id = 'toast-animation-style';
        style.textContent = `
            @keyframes slideIn {
                from {
                    transform: translateX(400px);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
            @keyframes slideOut {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }
                to {
                    transform: translateX(400px);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    }
    
    toastContainer.appendChild(toast);
    
    // Supprimer après 3 secondes
    setTimeout(() => {
        toast.style.animation = 'slideOut 0.3s ease-out';
        setTimeout(() => {
            if (toast.parentNode) {
                toast.remove();
            }
        }, 300);
    }, 3000);
}

// Initialiser l'affichage du panier au chargement
document.addEventListener('DOMContentLoaded', function() {
    updateCartDisplay();
    updateCartSummary();
});
</script>

<style>
.product-card:hover .product-overlay {
    opacity: 1 !important;
}

.quantity-input {
    border: 1px solid #dee2e6;
}

.quantity-input:focus {
    border-color: #2c7873;
    box-shadow: 0 0 0 0.2rem rgba(44, 120, 115, 0.25);
}

.cart-summary {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background: linear-gradient(135deg, #2c7873 0%, #6fb98f 100%);
    color: white;
    padding: 1rem 1.5rem;
    border-radius: 12px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    display: flex;
    align-items: center;
    gap: 15px;
    z-index: 1000;
    transition: all 0.3s ease;
}

.cart-summary.hidden {
    transform: translateY(100px);
    opacity: 0;
    pointer-events: none;
}

.checkout-btn {
    background: rgba(255, 255, 255, 0.2);
    color: white;
    border: 1px solid rgba(255, 255, 255, 0.3);
    padding: 8px 16px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
}

.checkout-btn:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: translateY(-2px);
}

.checkout-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
}
</style>