@extends('layouts.app')

@section('title', 'Suivi de mes Demandes - Fubadj')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- En-tête -->
        <div class="col-12 mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 fw-bold text-success mb-2">
                        <i class="fas fa-chart-line me-2"></i>Suivi de mes Demandes
                    </h1>
                    <p class="text-muted mb-0">Consultez l'état d'avancement de vos demandes de financement</p>
                </div>
                <a href="{{ route('financement.create') }}" class="btn btn-success">
                    <i class="fas fa-plus me-2"></i>Nouvelle Demande
                </a>
            </div>
        </div>

        <!-- Statistiques rapides -->
        <div class="col-md-3 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 p-3 rounded">
                                <i class="fas fa-file-alt fa-2x text-primary"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Total demandes</h6>
                            <h3 class="mb-0 fw-bold">{{ $demandes->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-warning bg-opacity-10 p-3 rounded">
                                <i class="fas fa-clock fa-2x text-warning"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">En attente</h6>
                            <h3 class="mb-0 fw-bold">{{ $demandes->where('statut', 'en_attente')->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-success bg-opacity-10 p-3 rounded">
                                <i class="fas fa-check-circle fa-2x text-success"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Approuvées</h6>
                            <h3 class="mb-0 fw-bold">{{ $demandes->where('statut', 'approuve')->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-danger bg-opacity-10 p-3 rounded">
                                <i class="fas fa-times-circle fa-2x text-danger"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Rejetées</h6>
                            <h3 class="mb-0 fw-bold">{{ $demandes->where('statut', 'rejete')->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtres -->
        <div class="col-12 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form method="GET" action="{{ route('financement.suivi') }}" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Statut</label>
                            <select name="statut" class="form-select">
                                <option value="">Tous les statuts</option>
                                <option value="en_attente" {{ request('statut') == 'en_attente' ? 'selected' : '' }}>En attente</option>
                                <option value="en_cours" {{ request('statut') == 'en_cours' ? 'selected' : '' }}>En cours d'examen</option>
                                <option value="approuve" {{ request('statut') == 'approuve' ? 'selected' : '' }}>Approuvée</option>
                                <option value="rejete" {{ request('statut') == 'rejete' ? 'selected' : '' }}>Rejetée</option>
                                <option value="debloque" {{ request('statut') == 'debloque' ? 'selected' : '' }}>Débloquée</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Date de début</label>
                            <input type="date" name="date_debut" class="form-control" value="{{ request('date_debut') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Date de fin</label>
                            <input type="date" name="date_fin" class="form-control" value="{{ request('date_fin') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">&nbsp;</label>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-success flex-fill">
                                    <i class="fas fa-filter me-1"></i>Filtrer
                                </button>
                                <a href="{{ route('financement.suivi') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-redo"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Liste des demandes -->
        <div class="col-12">
            @if($demandes->isEmpty())
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                        <h4 class="text-muted">Aucune demande trouvée</h4>
                        <p class="text-muted">Vous n'avez pas encore soumis de demande de financement.</p>
                        <a href="{{ route('financement.create') }}" class="btn btn-success mt-3">
                            <i class="fas fa-plus me-2"></i>Créer ma première demande
                        </a>
                    </div>
                </div>
            @else
                @foreach($demandes as $demande)
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <!-- Informations principales -->
                            <div class="col-md-6">
                                <div class="d-flex align-items-start">
                                    <div class="flex-shrink-0 me-3">
                                        @php
                                            $iconClass = match($demande->statut) {
                                                'en_attente' => 'fa-hourglass-half text-warning',
                                                'en_cours' => 'fa-spinner text-info',
                                                'approuve' => 'fa-check-circle text-success',
                                                'rejete' => 'fa-times-circle text-danger',
                                                'debloque' => 'fa-money-bill-wave text-success',
                                                default => 'fa-file-alt text-muted'
                                            };
                                        @endphp
                                        <div class="bg-light p-3 rounded">
                                            <i class="fas {{ $iconClass }} fa-2x"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="mb-2 fw-bold">{{ $demande->titre_projet }}</h5>
                                        <div class="text-muted small">
                                            <div class="mb-1">
                                                <i class="fas fa-calendar me-1"></i>
                                                Soumis le {{ $demande->created_at->format('d/m/Y à H:i') }}
                                            </div>
                                            <div class="mb-1">
                                                <i class="fas fa-hashtag me-1"></i>
                                                Référence: <strong>{{ $demande->reference }}</strong>
                                            </div>
                                            <div>
                                                <i class="fas fa-money-bill-wave me-1"></i>
                                                Montant: <strong>{{ number_format($demande->montant_demande, 0, ',', ' ') }} FCFA</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Statut et progression -->
                            <div class="col-md-3 text-center">
                                @php
                                    $statutLabel = match($demande->statut) {
                                        'en_attente' => ['text' => 'En attente', 'class' => 'warning'],
                                        'en_cours' => ['text' => 'En cours d\'examen', 'class' => 'info'],
                                        'approuve' => ['text' => 'Approuvée', 'class' => 'success'],
                                        'rejete' => ['text' => 'Rejetée', 'class' => 'danger'],
                                        'debloque' => ['text' => 'Fonds débloqués', 'class' => 'success'],
                                        default => ['text' => 'Inconnu', 'class' => 'secondary']
                                    };
                                    $progression = match($demande->statut) {
                                        'en_attente' => 25,
                                        'en_cours' => 50,
                                        'approuve' => 75,
                                        'debloque' => 100,
                                        'rejete' => 100,
                                        default => 0
                                    };
                                @endphp
                                <span class="badge bg-{{ $statutLabel['class'] }} mb-2 px-3 py-2">
                                    {{ $statutLabel['text'] }}
                                </span>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-{{ $statutLabel['class'] }}" 
                                         role="progressbar" 
                                         style="width: {{ $progression }}%" 
                                         aria-valuenow="{{ $progression }}" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                    </div>
                                </div>
                                <small class="text-muted">{{ $progression }}% complété</small>
                            </div>

                            <!-- Actions -->
                            <div class="col-md-3 text-end">
                                <a href="{{ route('financement.show', $demande->id) }}" 
                                   class="btn btn-outline-success btn-sm mb-2 w-100">
                                    <i class="fas fa-eye me-1"></i>Détails
                                </a>
                                
                                @if($demande->statut === 'en_attente')
                                    <button type="button" class="btn btn-outline-primary btn-sm mb-2 w-100">
                                        <i class="fas fa-edit me-1"></i>Modifier
                                    </button>
                                @endif

                                @if(in_array($demande->statut, ['approuve', 'debloque']))
                                    <button type="button" class="btn btn-outline-info btn-sm w-100">
                                        <i class="fas fa-download me-1"></i>Contrat
                                    </button>
                                @endif
                            </div>
                        </div>

                        <!-- Timeline des événements -->
                        @if($demande->historique && count($demande->historique) > 0)
                        <div class="mt-3 pt-3 border-top">
                            <h6 class="text-muted small mb-2">
                                <i class="fas fa-history me-1"></i>Historique récent
                            </h6>
                            <div class="timeline-horizontal">
                                @foreach(array_slice($demande->historique, -3) as $event)
                                <div class="timeline-item">
                                    <small class="text-muted">
                                        <i class="fas fa-circle fa-xs me-1"></i>
                                        {{ $event['date'] }} - {{ $event['action'] }}
                                    </small>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Message de notification -->
                        @if($demande->message_admin)
                        <div class="mt-3 pt-3 border-top">
                            <div class="alert alert-{{ $demande->statut === 'rejete' ? 'danger' : 'info' }} mb-0">
                                <h6 class="alert-heading">
                                    <i class="fas fa-comment-dots me-2"></i>Message de l'équipe Fubadj
                                </h6>
                                <p class="mb-0">{{ $demande->message_admin }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $demandes->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal de détails (optionnel) -->
<div class="modal fade" id="detailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="fas fa-info-circle me-2"></i>Détails de la demande
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Contenu dynamique -->
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .timeline-horizontal {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }
    
    .timeline-item {
        position: relative;
        padding-left: 1rem;
    }
    
    .timeline-item:not(:last-child)::after {
        content: '→';
        position: absolute;
        right: -0.75rem;
        color: #dee2e6;
    }
    
    .card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }
    
    .progress {
        border-radius: 10px;
    }
    
    .progress-bar {
        border-radius: 10px;
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animation au scroll
        const cards = document.querySelectorAll('.card');
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '0';
                    entry.target.style.transform = 'translateY(20px)';
                    
                    setTimeout(() => {
                        entry.target.style.transition = 'all 0.5s ease';
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }, 100);
                }
            });
        }, { threshold: 0.1 });
        
        cards.forEach(card => observer.observe(card));
        
        // Mise à jour automatique des statuts (optionnel)
        setInterval(() => {
            // Vérifier s'il y a de nouvelles mises à jour
            // fetch('/api/check-updates')...
        }, 60000); // Toutes les 60 secondes
    });
</script>
@endsection