@extends('layouts.app')

@section('title', 'Finaliser la Commande')

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
    
    .step-indicator {
        display: flex;
        justify-content: space-between;
        margin-bottom: 30px;
        position: relative;
    }
    
    .step {
        text-align: center;
        flex: 1;
        position: relative;
        z-index: 2;
    }
    
    .step-number {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #e9ecef;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 10px;
        font-weight: bold;
    }
    
    .step.active .step-number {
        background: #2c7873;
        color: white;
    }
    
    .step-line {
        position: absolute;
        top: 20px;
        left: 0;
        right: 0;
        height: 2px;
        background: #e9ecef;
        z-index: 1;
    }
</style>
@endsection

@section('content')
<div class="container py-4">
    <!-- Indicateur d'étapes -->
    <div class="row">
        <div class="col-12">
            <div class="step-indicator">
                <div class="step-line"></div>
                <div class="step active">
                    <div class="step-number">1</div>
                    <small>Panier</small>
                </div>
                <div class="step active">
                    <div class="step-number">2</div>
                    <small>Livraison</small>
                </div>
                <div class="step active">
                    <div class="step-number">3</div>
                    <small>Paiement</small>
                </div>
                <div class="step">
                    <div class="step-number">4</div>
                    <small>Confirmation</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Formulaire de paiement -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h4 class="mb-0"><i class="fas fa-credit-card me-2"></i>Informations de Paiement</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('paiement.process') }}" method="POST" id="paymentForm">
                        @csrf
                        <input type="hidden" name="panier_data" id="panierData">
                        <input type="hidden" name="total" id="totalAmount">

                        <!-- Informations de livraison -->
                        <div class="mb-4">
                            <h5 class="mb-3"><i class="fas fa-truck me-2"></i>Informations de Livraison</h5>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="telephone_livraison" class="form-label">Numéro de Téléphone <span class="text-danger">*</span></label>
                                        <input type="tel" class="form-control" id="telephone_livraison" name="telephone_livraison" 
                                               value="{{ $telephoneParDefaut ?? old('telephone_livraison') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="ville_livraison" class="form-label">Ville <span class="text-danger">*</span></label>
                                        <select class="form-control" id="ville_livraison" name="ville_livraison" required>
                                            <option value="">Sélectionnez votre ville</option>
                                            <option value="Dakar" {{ ($villeParDefaut ?? old('ville_livraison')) == 'Dakar' ? 'selected' : '' }}>Dakar</option>
                                            <option value="Thiès" {{ ($villeParDefaut ?? old('ville_livraison')) == 'Thiès' ? 'selected' : '' }}>Thiès</option>
                                            <option value="Saint-Louis" {{ ($villeParDefaut ?? old('ville_livraison')) == 'Saint-Louis' ? 'selected' : '' }}>Saint-Louis</option>
                                            <option value="Kaolack" {{ ($villeParDefaut ?? old('ville_livraison')) == 'Kaolack' ? 'selected' : '' }}>Kaolack</option>
                                            <option value="Ziguinchor" {{ ($villeParDefaut ?? old('ville_livraison')) == 'Ziguinchor' ? 'selected' : '' }}>Ziguinchor</option>
                                            <option value="Autre" {{ ($villeParDefaut ?? old('ville_livraison')) == 'Autre' ? 'selected' : '' }}>Autre</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="adresse_livraison" class="form-label">Adresse de Livraison <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="adresse_livraison" name="adresse_livraison" 
                                          rows="3" placeholder="Votre adresse complète (rue, quartier, point de repère)..." required>{{ $adresseParDefaut ?? old('adresse_livraison') }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label for="notes_client" class="form-label">Notes (Optionnel)</label>
                                <textarea class="form-control" id="notes_client" name="notes_client" 
                                          rows="2" placeholder="Instructions spéciales pour la livraison...">{{ old('notes_client') }}</textarea>
                            </div>
                        </div>

                        <!-- Méthodes de paiement -->
                        <div class="mb-4">
                            <h5 class="mb-3">Méthode de Paiement</h5>
                            
                            <div class="payment-method" onclick="selectPaymentMethod('mobile_money')">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="mode_paiement" id="mobile_money" value="mobile_money" required>
                                    <label class="form-check-label d-flex align-items-center" for="mobile_money">
                                        <i class="fas fa-mobile-alt payment-icon text-primary"></i>
                                        <div>
                                            <strong>Mobile Money</strong>
                                            <p class="mb-0 text-muted">Wave, Orange Money, Free Money</p>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <div class="payment-method" onclick="selectPaymentMethod('especes')">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="mode_paiement" id="especes" value="especes">
                                    <label class="form-check-label d-flex align-items-center" for="especes">
                                        <i class="fas fa-money-bill-wave payment-icon text-success"></i>
                                        <div>
                                            <strong>Paiement en Espèces</strong>
                                            <p class="mb-0 text-muted">Paiement à la livraison</p>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <div class="payment-method" onclick="selectPaymentMethod('carte_bancaire')">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="mode_paiement" id="carte_bancaire" value="carte_bancaire">
                                    <label class="form-check-label d-flex align-items-center" for="carte_bancaire">
                                        <i class="fas fa-credit-card payment-icon text-info"></i>
                                        <div>
                                            <strong>Carte Bancaire</strong>
                                            <p class="mb-0 text-muted">Paiement par carte Visa/Mastercard</p>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <div class="payment-method" onclick="selectPaymentMethod('virement')">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="mode_paiement" id="virement" value="virement">
                                    <label class="form-check-label d-flex align-items-center" for="virement">
                                        <i class="fas fa-university payment-icon text-warning"></i>
                                        <div>
                                            <strong>Virement Bancaire</strong>
                                            <p class="mb-0 text-muted">Virement vers notre compte</p>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-success btn-lg" id="submitBtn">
                                <i class="fas fa-lock me-2"></i>Confirmer la Commande
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Récapitulatif de la commande -->
        <div class="col-lg-4">
            <div class="order-summary">
                <h5 class="mb-3">Récapitulatif de la Commande</h5>
                
                <div id="orderItems">
                    <!-- Les articles seront ajoutés dynamiquement -->
                </div>
                
                <div class="d-flex justify-content-between mt-3 pt-3 border-top">
                    <strong>Total:</strong>
                    <strong id="orderTotal">0 FCFA</strong>
                </div>

                <div class="mt-4">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <small>Livraison sous 24-48h dans les zones urbaines.</small>
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
    loadOrderSummary();
    initializePaymentForm();
});

function loadOrderSummary() {
    const cart = JSON.parse(localStorage.getItem('fubadj_cart')) || [];
    const orderItems = document.getElementById('orderItems');
    const orderTotal = document.getElementById('orderTotal');
    const panierData = document.getElementById('panierData');
    const totalAmount = document.getElementById('totalAmount');

    let total = 0;
    let html = '';

    if (cart.length === 0) {
        html = '<p class="text-muted">Votre panier est vide</p>';
        orderTotal.textContent = '0 FCFA';
        return;
    }

    cart.forEach(item => {
        const itemTotal = item.price * item.quantity;
        total += itemTotal;

        html += `
            <div class="product-item">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <h6 class="mb-1">${item.name}</h6>
                        <small class="text-muted">${item.quantity} x ${formatPrice(item.price)} FCFA</small>
                    </div>
                    <strong class="ms-2">${formatPrice(itemTotal)} FCFA</strong>
                </div>
            </div>
        `;
    });

    orderItems.innerHTML = html;
    orderTotal.textContent = formatPrice(total) + ' FCFA';
    
    // Stocker les données pour le formulaire
    panierData.value = JSON.stringify(cart);
    totalAmount.value = total;
}

function initializePaymentForm() {
    const form = document.getElementById('paymentForm');
    const submitBtn = document.getElementById('submitBtn');

    form.addEventListener('submit', function(e) {
        const cart = JSON.parse(localStorage.getItem('fubadj_cart')) || [];
        
        if (cart.length === 0) {
            e.preventDefault();
            alert('Votre panier est vide!');
            return;
        }

        const paymentMethod = document.querySelector('input[name="mode_paiement"]:checked');
        if (!paymentMethod) {
            e.preventDefault();
            alert('Veuillez sélectionner une méthode de paiement');
            return;
        }

        // Désactiver le bouton pour éviter les doubles clics
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Traitement en cours...';
    });
}

function selectPaymentMethod(method) {
    // Mettre à jour l'apparence visuelle
    document.querySelectorAll('.payment-method').forEach(el => {
        el.classList.remove('selected');
    });
    
    document.querySelector(`[for="${method}"]`).closest('.payment-method').classList.add('selected');
    
    // Cocher le radio button
    document.getElementById(method).checked = true;
}

function formatPrice(price) {
    return new Intl.NumberFormat('fr-FR').format(price);
}
</script>
@endsection