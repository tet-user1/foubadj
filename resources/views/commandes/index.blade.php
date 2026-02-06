@extends('layouts.app')

@section('title', 'Finaliser ma commande')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('catalogue.index') }}">Catalogue</a></li>
                    <li class="breadcrumb-item active">Commande</li>
                </ol>
            </nav>

            <!-- Titre -->
            <div class="text-center mb-5">
                <h1 class="display-5 fw-bold text-primary">
                    <i class="fas fa-shopping-cart me-3"></i>Finaliser ma commande
                </h1>
                <p class="lead text-muted">Vérifiez vos articles et renseignez vos informations de livraison</p>
            </div>

            <form action="{{ route('commande.process') }}" method="POST" id="orderForm">
                @csrf
                
                <div class="row">
                    <!-- Colonne de gauche - Récapitulatif de la commande -->
                    <div class="col-lg-7">
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">
                                    <i class="fas fa-list me-2"></i>Récapitulatif de votre commande
                                </h5>
                            </div>
                            <div class="card-body">
                                <div id="orderSummary">
                                    @if(isset($cartData) && !empty($cartData['items']))
                                        @foreach($cartData['items'] as $item)
                                        <div class="d-flex align-items-center border-bottom py-3">
                                            <img src="{{ $item['image'] ?? 'https://via.placeholder.com/80' }}" 
                                                 alt="{{ $item['name'] }}" 
                                                 class="rounded me-3" 
                                                 style="width: 60px; height: 60px; object-fit: cover;">
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1">{{ $item['name'] }}</h6>
                                                <small class="text-muted">
                                                    Par {{ $item['producer'] ?? 'N/A' }}
                                                </small>
                                                <div class="mt-1">
                                                    <span class="badge bg-secondary">{{ $item['quantity'] }}x</span>
                                                    <span class="text-muted">{{ number_format($item['price'], 0, ',', ' ') }} FCFA/unité</span>
                                                </div>
                                            </div>
                                            <div class="text-end">
                                                <strong class="text-success">
                                                    {{ number_format($item['total'], 0, ',', ' ') }} FCFA
                                                </strong>
                                            </div>
                                        </div>
                                        @endforeach
                                        
                                        <!-- Total -->
                                        <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                                            <div>
                                                <h5 class="mb-0">Total de la commande</h5>
                                                <small class="text-muted">{{ $cartData['itemCount'] }} article(s)</small>
                                            </div>
                                            <h4 class="mb-0 text-success fw-bold">
                                                {{ number_format($cartData['total'], 0, ',', ' ') }} FCFA
                                            </h4>
                                        </div>
                                    @else
                                        <div class="text-center py-4">
                                            <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                                            <h5 class="text-muted">Aucun article dans votre commande</h5>
                                            <a href="{{ route('catalogue.index') }}" class="btn btn-primary">
                                                <i class="fas fa-arrow-left me-1"></i>Retourner au catalogue
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Colonne de droite - Informations de livraison -->
                    <div class="col-lg-5">
                        <div class="card shadow-sm">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0">
                                    <i class="fas fa-truck me-2"></i>Informations de livraison
                                </h5>
                            </div>
                            <div class="card-body">
                                
                                <!-- Adresse de livraison -->
                                <div class="mb-3">
                                    <label for="adresse_livraison" class="form-label fw-bold">
                                        <i class="fas fa-map-marker-alt text-danger me-1"></i>
                                        Adresse de livraison <span class="text-danger">*</span>
                                    </label>
                                    <textarea class="form-control @error('adresse_livraison') is-invalid @enderror" 
                                              id="adresse_livraison" 
                                              name="adresse_livraison" 
                                              rows="3" 
                                              placeholder="Votre adresse complète de livraison"
                                              required>{{ old('adresse_livraison') }}</textarea>
                                    @error('adresse_livraison')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Ville -->
                                <div class="mb-3">
                                    <label for="ville_livraison" class="form-label fw-bold">
                                        <i class="fas fa-city text-info me-1"></i>
                                        Ville <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('ville_livraison') is-invalid @enderror" 
                                            id="ville_livraison" 
                                            name="ville_livraison" 
                                            required>
                                        <option value="">Sélectionnez votre ville</option>
                                        <option value="Dakar" {{ old('ville_livraison') == 'Dakar' ? 'selected' : '' }}>Dakar</option>
                                        <option value="Pikine" {{ old('ville_livraison') == 'Pikine' ? 'selected' : '' }}>Pikine</option>
                                        <option value="Guédiawaye" {{ old('ville_livraison') == 'Guédiawaye' ? 'selected' : '' }}>Guédiawaye</option>
                                        <option value="Rufisque" {{ old('ville_livraison') == 'Rufisque' ? 'selected' : '' }}>Rufisque</option>
                                        <option value="Thiès" {{ old('ville_livraison') == 'Thiès' ? 'selected' : '' }}>Thiès</option>
                                        <option value="Saint-Louis" {{ old('ville_livraison') == 'Saint-Louis' ? 'selected' : '' }}>Saint-Louis</option>
                                        <option value="Kaolack" {{ old('ville_livraison') == 'Kaolack' ? 'selected' : '' }}>Kaolack</option>
                                        <option value="Ziguinchor" {{ old('ville_livraison') == 'Ziguinchor' ? 'selected' : '' }}>Ziguinchor</option>
                                        <option value="Diourbel" {{ old('ville_livraison') == 'Diourbel' ? 'selected' : '' }}>Diourbel</option>
                                        <option value="Touba" {{ old('ville_livraison') == 'Touba' ? 'selected' : '' }}>Touba</option>
                                    </select>
                                    @error('ville_livraison')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Téléphone -->
                                <div class="mb-3">
                                    <label for="telephone_livraison" class="form-label fw-bold">
                                        <i class="fas fa-phone text-success me-1"></i>
                                        Téléphone <span class="text-danger">*</span>
                                    </label>
                                    <input type="tel" 
                                           class="form-control @error('telephone_livraison') is-invalid @enderror" 
                                           id="telephone_livraison" 
                                           name="telephone_livraison" 
                                           placeholder="+221 77 123 45 67"
                                           value="{{ old('telephone_livraison') }}"
                                           required>
                                    @error('telephone_livraison')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Numéro pour vous contacter lors de la livraison
                                    </small>
                                </div>

                                <!-- Mode de paiement -->
                                <div class="mb-3">
                                    <label class="form-label fw-bold">
                                        <i class="fas fa-credit-card text-warning me-1"></i>
                                        Mode de paiement <span class="text-danger">*</span>
                                    </label>
                                    
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <div class="form-check border rounded p-3">
                                                <input class="form-check-input" type="radio" name="mode_paiement" 
                                                       id="especes" value="especes" 
                                                       {{ old('mode_paiement', 'especes') == 'especes' ? 'checked' : '' }}>
                                                <label class="form-check-label w-100" for="especes">
                                                    <i class="fas fa-money-bill-wave text-success me-2"></i>
                                                    <strong>Espèces</strong>
                                                    <small class="d-block text-muted">À la livraison</small>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-check border rounded p-3">
                                                <input class="form-check-input" type="radio" name="mode_paiement" 
                                                       id="mobile_money" value="mobile_money"
                                                       {{ old('mode_paiement') == 'mobile_money' ? 'checked' : '' }}>
                                                <label class="form-check-label w-100" for="mobile_money">
                                                    <i class="fas fa-mobile-alt text-primary me-2"></i>
                                                    <strong>Mobile Money</strong>
                                                    <small class="d-block text-muted">Orange/Wave</small>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-check border rounded p-3">
                                                <input class="form-check-input" type="radio" name="mode_paiement" 
                                                       id="carte_bancaire" value="carte_bancaire"
                                                       {{ old('mode_paiement') == 'carte_bancaire' ? 'checked' : '' }}>
                                                <label class="form-check-label w-100" for="carte_bancaire">
                                                    <i class="fas fa-credit-card text-info me-2"></i>
                                                    <strong>Carte bancaire</strong>
                                                    <small class="d-block text-muted">Visa/Mastercard</small>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-check border rounded p-3">
                                                <input class="form-check-input" type="radio" name="mode_paiement" 
                                                       id="virement" value="virement"
                                                       {{ old('mode_paiement') == 'virement' ? 'checked' : '' }}>
                                                <label class="form-check-label w-100" for="virement">
                                                    <i class="fas fa-university text-secondary me-2"></i>
                                                    <strong>Virement</strong>
                                                    <small class="d-block text-muted">Bancaire</small>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    @error('mode_paiement')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Notes client -->
                                <div class="mb-4">
                                    <label for="notes_client" class="form-label fw-bold">
                                        <i class="fas fa-comment text-muted me-1"></i>
                                        Notes complémentaires
                                    </label>
                                    <textarea class="form-control @error('notes_client') is-invalid @enderror" 
                                              id="notes_client" 
                                              name="notes_client" 
                                              rows="3" 
                                              placeholder="Instructions spéciales, préférences d'horaire, etc.">{{ old('notes_client') }}</textarea>
                                    @error('notes_client')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Boutons d'action -->
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-success btn-lg" id="submitOrder">
                                        <i class="fas fa-check-circle me-2"></i>
                                        Confirmer ma commande
                                    </button>
                                    <a href="{{ route('catalogue.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-arrow-left me-1"></i>
                                        Retour au catalogue
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Informations de livraison -->
                        <div class="card shadow-sm mt-4">
                            <div class="card-body">
                                <h6 class="fw-bold text-primary">
                                    <i class="fas fa-info-circle me-2"></i>Informations de livraison
                                </h6>
                                <ul class="list-unstyled small mb-0">
                                    <li class="mb-2">
                                        <i class="fas fa-clock text-warning me-2"></i>
                                        Livraison sous 24-48h
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-shield-alt text-success me-2"></i>
                                        Produits frais garantis
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-undo text-info me-2"></i>
                                        Retour possible si non conforme
                                    </li>
                                    <li>
                                        <i class="fas fa-headset text-primary me-2"></i>
                                        Support client disponible
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Données cachées -->
                <input type="hidden" name="items" value="{{ json_encode($cartData['items'] ?? []) }}">
            </form>
        </div>
    </div>
</div>

<!-- Modal de confirmation -->
<div class="modal fade" id="confirmOrderModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-check-circle text-success me-2"></i>Confirmer votre commande
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0">Êtes-vous sûr de vouloir passer cette commande ?</p>
                <div class="mt-3 p-3 bg-light rounded">
                    <strong>Total: {{ number_format($cartData['total'] ?? 0, 0, ',', ' ') }} FCFA</strong>
                    <br>
                    <small class="text-muted">{{ ($cartData['itemCount'] ?? 0) }} article(s)</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-success" id="confirmOrderBtn">
                    <i class="fas fa-check me-1"></i>Oui, confirmer
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
.form-check {
    cursor: pointer;
    transition: all 0.3s ease;
}

.form-check:hover {
    background-color: #f8f9fa;
}

.form-check-input:checked + .form-check-label {
    color: #0d6efd;
}

.card {
    transition: box-shadow 0.3s ease;
}

.btn-loading {
    pointer-events: none;
}

@media (max-width: 768px) {
    .display-5 {
        font-size: 2rem;
    }
}
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('orderForm');
    const submitBtn = document.getElementById('submitOrder');
    const confirmModal = new bootstrap.Modal(document.getElementById('confirmOrderModal'));
    const confirmBtn = document.getElementById('confirmOrderBtn');

    // Validation du formulaire avant soumission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Vérifier que tous les champs requis sont remplis
        if (!form.checkValidity()) {
            form.classList.add('was-validated');
            return;
        }

        // Afficher le modal de confirmation
        confirmModal.show();
    });

    // Confirmer la commande
    confirmBtn.addEventListener('click', function() {
        confirmModal.hide();
        
        // Animation du bouton
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Traitement en cours...';
        submitBtn.classList.add('btn-loading');
        
        // Soumettre le formulaire
        setTimeout(() => {
            form.submit();
        }, 1000);
    });

    // Validation en temps réel des champs
    const requiredFields = document.querySelectorAll('[required]');
    requiredFields.forEach(field => {
        field.addEventListener('blur', function() {
            if (this.checkValidity()) {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            } else {
                this.classList.remove('is-valid');
                this.classList.add('is-invalid');
            }
        });
    });

    // Formatage du numéro de téléphone
    const phoneInput = document.getElementById('telephone_livraison');
    phoneInput.addEventListener('input', function() {
        let value = this.value.replace(/\D/g, '');
        if (value.length > 0) {
            if (value.startsWith('221')) {
                value = '+' + value;
            } else if (!value.startsWith('+221') && value.length >= 9) {
                value = '+221' + value;
            }
        }
        this.value = value;
    });
});
</script>
@endsection