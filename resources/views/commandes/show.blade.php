@extends('layouts.app')

@section('title', 'Commande #' . $commande->numero_commande)

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('commandes.history') }}">Mes commandes</a></li>
                    <li class="breadcrumb-item active">{{ $commande->numero_commande }}</li>
                </ol>
            </nav>

            <!-- En-tête de la commande -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h1 class="h3 fw-bold text-primary mb-2">
                                <i class="fas fa-receipt me-3"></i>
                                Commande {{ $commande->numero_commande }}
                            </h1>
                            <div class="d-flex flex-wrap gap-3 text-muted">
                                <span>
                                    <i class="fas fa-calendar me-1"></i>
                                    {{ $commande->date_commande->format('d/m/Y à H:i') }}
                                </span>
                                <span>
                                    <i class="fas fa-box me-1"></i>
                                    {{ $commande->nombre_articles }} article(s)
                                </span>
                                <span>
                                    <i class="fas fa-credit-card me-1"></i>
                                    {{ ucfirst(str_replace('_', ' ', $commande->mode_paiement ?? 'Non défini')) }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-4 text-end">
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
                            
                            <span class="badge bg-{{ $badgeClass }} fs-5 px-4 py-2 mb-3">
                                <i class="fas fa-{{ $statusIcon }} me-2"></i>
                                {{ $commande->statut_formatte }}
                            </span>
                            
                            <h3 class="text-success fw-bold mb-0">
                                {{ number_format($commande->total, 0, ',', ' ') }} FCFA
                            </h3>
                        </div>
                    </div>

                    <!-- Barre de progression -->
                    <div class="row mt-4">
                        <div class="col-12">
                            @php
                                $steps = [
                                    'en_attente' => ['label' => 'En attente', 'icon' => 'clock'],
                                    'confirmee' => ['label' => 'Confirmée', 'icon' => 'check-circle'],
                                    'en_preparation' => ['label' => 'En préparation', 'icon' => 'cog'],
                                    'en_livraison' => ['label' => 'En livraison', 'icon' => 'truck'],
                                    'livree' => ['label' => 'Livrée', 'icon' => 'check-double']
                                ];
                                
                                $stepKeys = array_keys($steps);
                                $currentIndex = array_search($commande->statut, $stepKeys);
                                $isCompleted = $commande->statut == 'livree';
                                $isCancelled = $commande->statut == 'annulee';
                            @endphp
                            
                            @if(!$isCancelled)
                                <div class="progress-steps">
                                    <div class="progress-line"></div>
                                    @foreach($steps as $stepKey => $step)
                                        @php
                                            $stepIndex = array_search($stepKey, $stepKeys);
                                            $isActive = $stepIndex <= $currentIndex;
                                            $isCurrent = $stepKey == $commande->statut;
                                        @endphp
                                        
                                        <div class="progress-step {{ $isActive ? 'active' : '' }} {{ $isCurrent ? 'current' : '' }}">
                                            <div class="step-icon">
                                                <i class="fas fa-{{ $step['icon'] }}"></i>
                                            </div>
                                            <div class="step-label">{{ $step['label'] }}</div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="alert alert-danger text-center">
                                    <i class="fas fa-times-circle fa-2x mb-2"></i>
                                    <h5>Commande annulée</h5>
                                    <p class="mb-0">Cette commande a été annulée le {{ $commande->updated_at->format('d/m/Y à H:i') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Articles de la commande -->
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                <i class="fas fa-shopping-cart me-2"></i>
                                Articles commandés
                            </h5>
                        </div>
                        <div class="card-body">
                            @foreach($commande->items as $item)
                                <div class="d-flex align-items-center border-bottom py-3 {{ $loop->last ? 'border-0 pb-0' : '' }}">
                                    <!-- Image du produit -->
                                    <div class="me-3">
                                        @if($item->produit->image_url)
                                            <img src="{{ $item->produit->image_url }}" 
                                                 alt="{{ $item->produit->nom }}"
                                                 class="rounded" 
                                                 style="width: 80px; height: 80px; object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                                 style="width: 80px; height: 80px;">
                                                <i class="fas fa-image text-muted fa-2x"></i>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Informations du produit -->
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">{{ $item->produit->nom }}</h6>
                                        <small class="text-muted">
                                            <i class="fas fa-user-tie me-1"></i>
                                            Par {{ $item->produit->user->name ?? 'N/A' }}
                                        </small>
                                        <div class="mt-2">
                                            <span class="badge bg-secondary me-2">{{ $item->quantite }}x</span>
                                            <span class="text-muted">{{ number_format($item->prix_unitaire, 0, ',', ' ') }} FCFA/unité</span>
                                        </div>
                                    </div>

                                    <!-- Prix total -->
                                    <div class="text-end">
                                        <h6 class="text-success mb-0">
                                            {{ number_format($item->sous_total, 0, ',', ' ') }} FCFA
                                        </h6>
                                    </div>
                                </div>
                            @endforeach

                            <!-- Récapitulatif des prix -->
                            <div class="border-top pt-3 mt-3">
                                <div class="row">
                                    <div class="col-6">
                                        <strong>Sous-total :</strong>
                                    </div>
                                    <div class="col-6 text-end">
                                        <strong>{{ number_format($commande->items->sum('sous_total'), 0, ',', ' ') }} FCFA</strong>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-6">
                                        <strong>Livraison :</strong>
                                    </div>
                                    <div class="col-6 text-end">
                                        <strong class="text-success">Gratuite</strong>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-6">
                                        <h5 class="mb-0">Total :</h5>
                                    </div>
                                    <div class="col-6 text-end">
                                        <h5 class="text-success mb-0">{{ number_format($commande->total, 0, ',', ' ') }} FCFA</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Notes du client -->
                    @if($commande->notes_client)
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">
                                    <i class="fas fa-comment me-2"></i>
                                    Vos notes
                                </h6>
                            </div>
                            <div class="card-body">
                                <p class="mb-0 text-muted">{{ $commande->notes_client }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Notes administratives -->
                    @if($commande->notes_admin)
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-info text-white">
                                <h6 class="mb-0">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Message de l'équipe Fubadj
                                </h6>
                            </div>
                            <div class="card-body">
                                <p class="mb-0">{{ $commande->notes_admin }}</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Informations de livraison et actions -->
                <div class="col-lg-4">
                    <!-- Informations de livraison -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-success text-white">
                            <h6 class="mb-0">
                                <i class="fas fa-truck me-2"></i>
                                Informations de livraison
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <strong class="text-muted d-block">Adresse :</strong>
                                <p class="mb-0">{{ $commande->adresse_livraison }}</p>
                            </div>
                            <div class="mb-3">
                                <strong class="text-muted d-block">Ville :</strong>
                                <p class="mb-0">{{ $commande->ville_livraison }}</p>
                            </div>
                            <div class="mb-3">
                                <strong class="text-muted d-block">Téléphone :</strong>
                                <p class="mb-0">
                                    <i class="fas fa-phone me-1"></i>
                                    {{ $commande->telephone_livraison }}
                                </p>
                            </div>
                            @if($commande->date_livraison)
                                <div class="mb-0">
                                    <strong class="text-muted d-block">Date de livraison :</strong>
                                    <p class="mb-0 text-success">
                                        <i class="fas fa-calendar-check me-1"></i>
                                        {{ $commande->date_livraison->format('d/m/Y à H:i') }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Informations de paiement -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-warning text-white">
                            <h6 class="mb-0">
                                <i class="fas fa-credit-card me-2"></i>
                                Paiement
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <strong class="text-muted d-block">Mode de paiement :</strong>
                                <p class="mb-0">
                                    @php
                                        $paymentIcons = [
                                            'especes' => 'money-bill-wave',
                                            'mobile_money' => 'mobile-alt',
                                            'carte_bancaire' => 'credit-card',
                                            'virement' => 'university'
                                        ];
                                        $icon = $paymentIcons[$commande->mode_paiement] ?? 'credit-card';
                                    @endphp
                                    <i class="fas fa-{{ $icon }} me-1"></i>
                                    {{ ucfirst(str_replace('_', ' ', $commande->mode_paiement ?? 'Non défini')) }}
                                </p>
                            </div>
                            <div class="mb-0">
                                <strong class="text-muted d-block">Statut du paiement :</strong>
                                @php
                                    $paymentBadgeClass = match($commande->statut_paiement) {
                                        'en_attente' => 'warning',
                                        'paye' => 'success',
                                        'rembourse' => 'info',
                                        default => 'secondary'
                                    };
                                @endphp
                                <span class="badge bg-{{ $paymentBadgeClass }}">
                                    {{ $commande->statut_paiement_formatte }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">
                                <i class="fas fa-cogs me-2"></i>
                                Actions
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                @if($commande->peutEtreAnnulee())
                                    <button class="btn btn-outline-danger" 
                                            onclick="confirmCancel('{{ $commande->id }}', '{{ $commande->numero_commande }}')">
                                        <i class="fas fa-times me-2"></i>
                                        Annuler la commande
                                    </button>
                                @endif

                                @if($commande->statut == 'livree')
                                    <button class="btn btn-outline-warning" onclick="rateOrder('{{ $commande->id }}')">
                                        <i class="fas fa-star me-2"></i>
                                        Évaluer ma commande
                                    </button>
                                @endif

                                <button class="btn btn-outline-primary" onclick="downloadInvoice()">
                                    <i class="fas fa-download me-2"></i>
                                    Télécharger la facture
                                </button>

                                <button class="btn btn-outline-info" onclick="contactSupport()">
                                    <i class="fas fa-headset me-2"></i>
                                    Contacter le support
                                </button>

                                <a href="{{ route('commandes.history') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>
                                    Retour à mes commandes
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Timeline des événements -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">
                                <i class="fas fa-history me-2"></i>
                                Historique
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="timeline">
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-success"></div>
                                    <div class="timeline-content">
                                        <h6 class="mb-1">Commande passée</h6>
                                        <small class="text-muted">{{ $commande->date_commande->format('d/m/Y à H:i') }}</small>
                                    </div>
                                </div>

                                @if($commande->statut != 'en_attente')
                                    <div class="timeline-item">
                                        <div class="timeline-marker bg-info"></div>
                                        <div class="timeline-content">
                                            <h6 class="mb-1">Commande confirmée</h6>
                                            <small class="text-muted">{{ $commande->updated_at->format('d/m/Y à H:i') }}</small>
                                        </div>
                                    </div>
                                @endif

                                @if(in_array($commande->statut, ['en_preparation', 'en_livraison', 'livree']))
                                    <div class="timeline-item">
                                        <div class="timeline-marker bg-primary"></div>
                                        <div class="timeline-content">
                                            <h6 class="mb-1">Préparation en cours</h6>
                                            <small class="text-muted">Vos produits sont en cours de préparation</small>
                                        </div>
                                    </div>
                                @endif

                                @if(in_array($commande->statut, ['en_livraison', 'livree']))
                                    <div class="timeline-item">
                                        <div class="timeline-marker bg-warning"></div>
                                        <div class="timeline-content">
                                            <h6 class="mb-1">En cours de livraison</h6>
                                            <small class="text-muted">Votre commande est en route</small>
                                        </div>
                                    </div>
                                @endif

                                @if($commande->statut == 'livree')
                                    <div class="timeline-item">
                                        <div class="timeline-marker bg-success"></div>
                                        <div class="timeline-content">
                                            <h6 class="mb-1">Commande livrée</h6>
                                            <small class="text-muted">
                                                {{ $commande->date_livraison ? $commande->date_livraison->format('d/m/Y à H:i') : 'Date non renseignée' }}
                                            </small>
                                        </div>
                                    </div>
                                @endif

                                @if($commande->statut == 'annulee')
                                    <div class="timeline-item">
                                        <div class="timeline-marker bg-danger"></div>
                                        <div class="timeline-content">
                                            <h6 class="mb-1">Commande annulée</h6>
                                            <small class="text-muted">{{ $commande->updated_at->format('d/m/Y à H:i') }}</small>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals réutilisés depuis la vue historique -->
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
/* Styles pour la barre de progression des étapes */
.progress-steps {
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: relative;
    margin: 2rem 0;
}

.progress-line {
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    height: 2px;
    background: #e9ecef;
    transform: translateY(-50%);
    z-index: 1;
}

.progress-step {
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
    z-index: 2;
    flex: 1;
}

.step-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: #e9ecef;
    color: #6c757d;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 0.5rem;
    transition: all 0.3s ease;
    border: 3px solid #e9ecef;
}

.progress-step.active .step-icon {
    background: #0d6efd;
    color: white;
    border-color: #0d6efd;
}

.progress-step.current .step-icon {
    background: #28a745;
    color: white;
    border-color: #28a745;
    box-shadow: 0 0 0 4px rgba(40, 167, 69, 0.2);
}

.step-label {
    font-size: 0.875rem;
    font-weight: 600;
    color: #6c757d;
    text-align: center;
}

.progress-step.active .step-label {
    color: #0d6efd;
}

.progress-step.current .step-label {
    color: #28a745;
}

/* Timeline styles */
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e9ecef;
}

.timeline-item {
    position: relative;
    margin-bottom: 1.5rem;
}

.timeline-item:last-child {
    margin-bottom: 0;
}

.timeline-marker {
    position: absolute;
    left: -23px;
    top: 0;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    border: 3px solid white;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.timeline-content h6 {
    margin-bottom: 0.25rem;
    color: #495057;
}

/* Rating stars */
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

/* Responsive adjustments */
@media (max-width: 768px) {
    .progress-steps {
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .progress-step {
        flex: 0 0 calc(50% - 0.5rem);
    }
    
    .progress-line {
        display: none;
    }
    
    .step-icon {
        width: 40px;
        height: 40px;
    }
    
    .timeline {
        padding-left: 20px;
    }
    
    .timeline-marker {
        left: -15px;
        width: 12px;
        height: 12px;
    }
}

/* Animation classes */
.fade-in {
    animation: fadeIn 0.3s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>
@endsection

@section('scripts')
<script>
let currentOrderId = {{ $commande->id }};

// Fonction pour confirmer l'annulation
function confirmCancel(orderId, orderNumber) {
    currentOrderId = orderId;
    document.getElementById('cancelOrderNumber').textContent = orderNumber;
    const modal = new bootstrap.Modal(document.getElementById('cancelModal'));
    modal.show();
}

// Confirmer l'annulation
document.getElementById('confirmCancelBtn')?.addEventListener('click', function() {
    if (!currentOrderId) return;
    
    this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Annulation...';
    this.disabled = true;
    
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

// Télécharger la facture
function downloadInvoice() {
    showToast('Génération de la facture en cours...', 'info');
    // Ici vous pouvez implémenter le téléchargement de facture
    setTimeout(() => {
        showToast('Fonctionnalité bientôt disponible', 'warning');
    }, 2000);
}

// Contacter le support
function contactSupport() {
    const message = `Bonjour, j'ai une question concernant ma commande ${document.querySelector('h1').textContent.trim()}`;
    const phoneNumber = '+221701234567'; // Remplacez par votre numéro
    const whatsappUrl = `https://wa.me/${phoneNumber}?text=${encodeURIComponent(message)}`;
    window.open(whatsappUrl, '_blank');
}

// Gestion des étoiles d'évaluation
document.addEventListener('DOMContentLoaded', function() {
    const stars = document.querySelectorAll('.rating-stars .fas');
    const ratingContainer = document.querySelector('.rating-stars');
    
    if (ratingContainer) {
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
    }
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
    const ratingContainer = document.querySelector('.rating-stars');
    const rating = parseInt(ratingContainer.dataset.rating);
    const comment = document.getElementById('commentaire').value;
    
    if (rating === 0) {
        showToast('Veuillez sélectionner une note', 'warning');
        return;
    }
    
    console.log('Évaluation:', { orderId: currentOrderId, rating, comment });
    
    showToast('Merci pour votre évaluation !', 'success');
    bootstrap.Modal.getInstance(document.getElementById('ratingModal')).hide();
    
    // Reset du formulaire
    ratingContainer.dataset.rating = '0';
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