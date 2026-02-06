@extends('layouts.app')

@section('title', 'Mes Commandes')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            
            <!-- En-tête -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="display-6 fw-bold text-primary">
                        <i class="fas fa-shopping-bag me-3"></i>Mes Commandes
                    </h1>
                    <p class="lead text-muted mb-0">Suivez l'état de toutes vos commandes</p>
                </div>
                <a href="{{ route('catalogue.index') }}" class="btn btn-success">
                    <i class="fas fa-plus me-2"></i>Nouvelle commande
                </a>
            </div>

            <!-- Filtres -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('commandes.history') }}" class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label for="statut" class="form-label fw-bold">
                                <i class="fas fa-filter text-primary me-1"></i>Filtrer par statut
                            </label>
                            <select name="statut" id="statut" class="form-select" onchange="this.form.submit()">
                                @foreach($statuts as $value => $label)
                                    <option value="{{ $value }}" {{ $statut == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-8">
                            <div class="d-flex gap-2 flex-wrap">
                                @foreach($statuts as $value => $label)
                                    @if($value != '')
                                        <a href="{{ route('commandes.history', ['statut' => $value]) }}" 
                                           class="btn btn-sm {{ $statut == $value ? 'btn-primary' : 'btn-outline-primary' }}">
                                            {{ $label }}
                                            @if($value == $statut)
                                                <span class="badge bg-white text-primary ms-1">
                                                    {{ $commandes->total() }}
                                                </span>
                                            @endif
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Liste des commandes -->
            @if($commandes->count() > 0)
                <div class="row">
                    @foreach($commandes as $commande)
                        <div class="col-12 mb-4">
                            <div class="card border-0 shadow-sm order-card">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        
                                        <!-- Informations principales -->
                                        <div class="col-lg-3 col-md-6 mb-3">
                                            <div class="d-flex align-items-center">
                                                <div class="order-icon me-3">
                                                    <i class="fas fa-receipt fa-2x text-primary"></i>
                                                </div>
                                                <div>
                                                    <h5 class="mb-1">{{ $commande->numero_commande }}</h5>
                                                    <small class="text-muted">
                                                        {{ $commande->date_commande->format('d/m/Y à H:i') }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Statut -->
                                        <div class="col-lg-2 col-md-6 mb-3 text-center">
                                            @php
                                                $badgeClass = match($commande->statut) {
                                                    'en_attente' => 'warning',
                                                    'confirmee' => 'info',
                                                    'en_preparation' => 'primary',
                                                    'en_livraison' => 'secondary',
                                                    'livree' => 'success',
                                                    'annulee' => 'danger',
                                                    default => 'secondary'
                                                };
                                                
                                                $statusIcon = match($commande->statut) {
                                                    'en_attente' => 'clock',
                                                    'confirmee' => 'check-circle',
                                                    'en_preparation' => 'cog',
                                                    'en_livraison' => 'truck',
                                                    'livree' => 'check-double',
                                                    'annulee' => 'times-circle',
                                                    default => 'info-circle'
                                                };
                                            @endphp
                                            
                                            <span class="badge bg-{{ $badgeClass }} fs-6 px-3 py-2">
                                                <i class="fas fa-{{ $statusIcon }} me-1"></i>
                                                {{ $commande->statut_formatte }}
                                            </span>
                                        </div>

                                        <!-- Détails -->
                                        <div class="col-lg-3 col-md-6 mb-3">
                                            <div class="text-center">
                                                <h6 class="mb-1 text-success">{{ number_format($commande->total, 0, ',', ' ') }} FCFA</h6>
                                                <small class="text-muted">
                                                    {{ $commande->nombre_articles }} article(s)
                                                </small>
                                            </div>
                                        </div>

                                        <!-- Actions -->
                                        <div class="col-lg-4 col-md-6 mb-3">
                                            <div class="d-flex justify-content-end gap-2">
                                                <a href="{{ route('commandes.details', $commande) }}" 
                                                   class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-eye me-1"></i>Voir détails
                                                </a>
                                                
                                                @if($commande->peutEtreAnnulee())
                                                    <button class="btn btn-outline-danger btn-sm" 
                                                            onclick="confirmCancel('{{ $commande->id }}', '{{ $commande->numero_commande }}')">
                                                        <i class="fas fa-times me-1"></i>Annuler
                                                    </button>
                                                @endif
                                                
                                                @if($commande->statut == 'livree')
                                                    <button class="btn btn-outline-warning btn-sm" 
                                                            onclick="rateOrder('{{ $commande->id }}')">
                                                        <i class="fas fa-star me-1"></i>Noter
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Aperçu des produits -->
                                    <div class="row mt-3 pt-3 border-top">
                                        <div class="col-12">
                                            <div class="d-flex align-items-center">
                                                <small class="text-muted me-3">Produits:</small>
                                                <div class="d-flex flex-wrap gap-2">
                                                    @foreach($commande->items->take(3) as $item)
                                                        <div class="d-flex align-items-center bg-light rounded px-2 py-1">
                                                            @if($item->produit->image_url)
                                                                <img src="{{ $item->produit->image_url }}" 
                                                                     alt="{{ $item->produit->nom }}"
                                                                     class="rounded me-2" 
                                                                     style="width: 24px; height: 24px; object-fit: cover;">
                                                            @endif
                                                            <small>{{ $item->produit->nom }} ({{ $item->quantite }})</small>
                                                        </div>
                                                    @endforeach
                                                    
                                                    @if($commande->items->count() > 3)
                                                        <small class="text-muted">
                                                            +{{ $commande->items->count() - 3 }} autre(s)
                                                        </small>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Barre de progression -->
                                    <div class="row mt-3">
                                        <div class="col-12">
                                            @php
                                                $progress = match($commande->statut) {
                                                    'en_attente' => 20,
                                                    'confirmee' => 40,
                                                    'en_preparation' => 60,
                                                    'en_livraison' => 80,
                                                    'livree' => 100,
                                                    'annulee' => 0,
                                                    default => 0
                                                };
                                                
                                                $progressClass = $commande->statut == 'annulee' ? 'danger' : 
                                                               ($commande->statut == 'livree' ? 'success' : 'primary');
                                            @endphp
                                            
                                            <div class="progress" style="height: 6px;">
                                                <div class="progress-bar bg-{{ $progressClass }}" 
                                                     role="progressbar" 
                                                     style="width: {{ $progress }}%"
                                                     aria-valuenow="{{ $progress }}" 
                                                     aria-valuemin="0" 
                                                     aria-valuemax="100">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $commandes->appends(request()->query())->links() }}
                </div>

            @else
                <!-- État vide -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <div class="mb-4">
                            <i class="fas fa-shopping-bag fa-4x text-muted"></i>
                        </div>
                        <h4 class="text-muted mb-3">
                            @if($statut)
                                Aucune commande avec le statut "{{ $statuts[$statut] }}"
                            @else
                                Vous n'avez pas encore passé de commande
                            @endif
                        </h4>
                        <p class="text-muted mb-4">
                            @if($statut)
                                Essayez de changer le filtre ou passez une nouvelle commande
                            @else
                                Découvrez nos produits frais et locaux pour passer votre première commande
                            @endif
                        </p>
                        <div class="d-flex justify-content-center gap-3">
                            <a href="{{ route('catalogue.index') }}" class="btn btn-success">
                                <i class="fas fa-shopping-cart me-2"></i>Voir le catalogue
                            </a>
                            @if($statut)
                                <a href="{{ route('commandes.history') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-list me-2"></i>Toutes mes commandes
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal d'annulation -->
<div class="modal fade" id="cancelModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                    Annuler la commande
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir annuler la commande <strong id="cancelOrderNumber"></strong> ?</p>
                <div class="alert alert-warning">
                    <i class="fas fa-info-circle me-2"></i>
                    Cette action est irréversible. Les produits seront remis en stock.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Non, garder</button>
                <button type="button" class="btn btn-danger" id="confirmCancelBtn">
                    <i class="fas fa-times me-1"></i>Oui, annuler
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal d'évaluation -->
<div class="modal fade" id="ratingModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-star text-warning me-2"></i>
                    Évaluer ma commande
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="ratingForm">
                    <div class="mb-3">
                        <label class="form-label">Note globale</label>
                        <div class="rating-stars" data-rating="0">
                            <i class="fas fa-star" data-value="1"></i>
                            <i class="fas fa-star" data-value="2"></i>
                            <i class="fas fa-star" data-value="3"></i>
                            <i class="fas fa-star" data-value="4"></i>
                            <i class="fas fa-star" data-value="5"></i>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="commentaire" class="form-label">Commentaire (optionnel)</label>
                        <textarea class="form-control" id="commentaire" rows="3" 
                                  placeholder="Partagez votre expérience..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-warning" onclick="submitRating()">
                    <i class="fas fa-paper-plane me-1"></i>Envoyer l'évaluation
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
.order-card {
    transition: all 0.3s ease;
}

.order-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}

.order-icon {
    min-width: 50px;
}

.progress {
    border-radius: 10px;
}

.progress-bar {
    border-radius: 10px;
}

.rating-stars {
    font-size: 1.5rem;
    color: #ddd;
    cursor: pointer;
}

.rating-stars .fas.active {
    color: #ffc107;
}

.rating-stars .fas:hover {
    color: #ffc107;
}

@media (max-width: 768px) {
    .order-card .row > div {
        text-align: center !important;
        margin-bottom: 1rem;
    }
    
    .btn-group-vertical {
        width: 100%;
    }
}
</style>
@endsection

@section('scripts')
<script>
let currentOrderId = null;

// Fonction pour confirmer l'annulation
function confirmCancel(orderId, orderNumber) {
    currentOrderId = orderId;
    document.getElementById('cancelOrderNumber').textContent = orderNumber;
    const modal = new bootstrap.Modal(document.getElementById('cancelModal'));
    modal.show();
}

// Confirmer l'annulation
document.getElementById('confirmCancelBtn').addEventListener('click', function() {
    if (!currentOrderId) return;
    
    this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Annulation...';
    this.disabled = true;
    
    // Appel API pour annuler la commande
    fetch(`/api/orders/${currentOrderId}/cancel`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Authorization': `Bearer ${localStorage.getItem('auth_token')}`
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Commande annulée avec succès', 'success');
            setTimeout(() => {
                location.reload();
            }, 2000);
        } else {
            showToast(data.message || 'Erreur lors de l\'annulation', 'error');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showToast('Erreur de connexion', 'error');
    })
    .finally(() => {
        bootstrap.Modal.getInstance(document.getElementById('cancelModal')).hide();
    });
});

// Fonction pour évaluer une commande
function rateOrder(orderId) {
    currentOrderId = orderId;
    const modal = new bootstrap.Modal(document.getElementById('ratingModal'));
    modal.show();
}

// Gestion des étoiles d'évaluation
document.addEventListener('DOMContentLoaded', function() {
    const stars = document.querySelectorAll('.rating-stars .fas');
    const ratingContainer = document.querySelector('.rating-stars');
    
    stars.forEach((star, index) => {
        star.addEventListener('mouseover', function() {
            highlightStars(index + 1);
        });
        
        star.addEventListener('click', function() {
            const rating = index + 1;
            ratingContainer.dataset.rating = rating;
            highlightStars(rating);
        });
    });
    
    ratingContainer.addEventListener('mouseleave', function() {
        const currentRating = parseInt(this.dataset.rating);
        highlightStars(currentRating);
    });
});

function highlightStars(count) {
    const stars = document.querySelectorAll('.rating-stars .fas');
    stars.forEach((star, index) => {
        if (index < count) {
            star.classList.add('active');
        } else {
            star.classList.remove('active');
        }
    });
}

// Soumettre l'évaluation
function submitRating() {
    const rating = parseInt(document.querySelector('.rating-stars').dataset.rating);
    const comment = document.getElementById('commentaire').value;
    
    if (rating === 0) {
        showToast('Veuillez sélectionner une note', 'warning');
        return;
    }
    
    // Ici vous pouvez implémenter l'envoi de l'évaluation
    console.log('Évaluation:', { orderId: currentOrderId, rating, comment });
    
    showToast('Merci pour votre évaluation !', 'success');
    bootstrap.Modal.getInstance(document.getElementById('ratingModal')).hide();
    
    // Reset du formulaire
    document.querySelector('.rating-stars').dataset.rating = '0';
    highlightStars(0);
    document.getElementById('commentaire').value = '';
}

// Fonction toast notification
function showToast(message, type = 'info') {
    const toastContainer = document.getElementById('toast-container') || createToastContainer();
    
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white bg-${type} border-0 show`;
    toast.style.marginBottom = '10px';
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                <i class="fas ${getToastIcon(type)} me-2"></i>${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;
    
    toastContainer.appendChild(toast);
    
    setTimeout(() => {
        if (toast.parentNode) {
            toast.classList.remove('show');
            setTimeout(() => {
                if (toast.parentNode) {
                    toastContainer.removeChild(toast);
                }
            }, 300);
        }
    }, 5000);
}

function createToastContainer() {
    const container = document.createElement('div');
    container.id = 'toast-container';
    container.className = 'position-fixed top-0 end-0 p-3';
    container.style.zIndex = '9999';
    document.body.appendChild(container);
    return container;
}

function getToastIcon(type) {
    const icons = {
        success: 'fa-check-circle',
        error: 'fa-exclamation-circle',
        warning: 'fa-exclamation-triangle',
        info: 'fa-info-circle'
    };
    return icons[type] || icons.info;
}
</script>
@endsection