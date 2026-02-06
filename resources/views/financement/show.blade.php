@extends('layouts.app')

@section('title', 'Détails de la demande - Fubadj')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- En-tête avec retour -->
        <div class="col-12 mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <a href="{{ route('financement.suivi') }}" class="btn btn-outline-secondary btn-sm mb-2">
                        <i class="fas fa-arrow-left me-2"></i>Retour au suivi
                    </a>
                    <h1 class="h3 fw-bold text-success mb-1">
                        <i class="fas fa-file-invoice-dollar me-2"></i>{{ $demande->titre_projet }}
                    </h1>
                    <p class="text-muted mb-0">Référence: <strong>{{ $demande->reference }}</strong></p>
                </div>
                @php
                    $statutLabel = match($demande->statut) {
                        'en_attente' => ['text' => 'En attente', 'class' => 'warning', 'icon' => 'hourglass-half'],
                        'en_cours' => ['text' => 'En cours d\'examen', 'class' => 'info', 'icon' => 'spinner'],
                        'approuve' => ['text' => 'Approuvée', 'class' => 'success', 'icon' => 'check-circle'],
                        'rejete' => ['text' => 'Rejetée', 'class' => 'danger', 'icon' => 'times-circle'],
                        'debloque' => ['text' => 'Fonds débloqués', 'class' => 'success', 'icon' => 'money-bill-wave'],
                        default => ['text' => 'Inconnu', 'class' => 'secondary', 'icon' => 'question-circle']
                    };
                @endphp
                <div class="text-end">
                    <span class="badge bg-{{ $statutLabel['class'] }} fs-5 px-4 py-2">
                        <i class="fas fa-{{ $statutLabel['icon'] }} me-2"></i>{{ $statutLabel['text'] }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Colonne principale -->
        <div class="col-lg-8">
            <!-- Résumé de la demande -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>Résumé de la Demande
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small mb-1">Montant demandé</label>
                            <h4 class="text-success fw-bold">{{ number_format($demande->montant_demande, 0, ',', ' ') }} FCFA</h4>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small mb-1">Durée de remboursement</label>
                            <h4 class="fw-bold">{{ $demande->duree_remboursement }} mois</h4>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small mb-1">Date de soumission</label>
                            <p class="mb-0 fw-bold">
                                <i class="fas fa-calendar me-2 text-success"></i>
                                {{ $demande->created_at->format('d/m/Y à H:i') }}
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small mb-1">Dernière mise à jour</label>
                            <p class="mb-0 fw-bold">
                                <i class="fas fa-sync me-2 text-success"></i>
                                {{ $demande->updated_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>

                    @if(in_array($demande->statut, ['approuve', 'debloque']))
                    <div class="alert alert-success mt-3 mb-0">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-calculator fa-2x me-3"></i>
                            <div class="flex-grow-1">
                                <h6 class="alert-heading mb-1">Mensualité estimée</h6>
                                @php
                                    $tauxMensuel = 0.08 / 12; // 8% annuel
                                    $montant = $demande->montant_demande;
                                    $duree = $demande->duree_remboursement;
                                    $mensualite = ($montant * $tauxMensuel) / (1 - pow(1 + $tauxMensuel, -$duree));
                                @endphp
                                <p class="mb-0 fs-5 fw-bold">{{ number_format($mensualite, 0, ',', ' ') }} FCFA/mois</p>
                                <small>Taux d'intérêt : 8% annuel avec Orange Bank</small>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Informations du demandeur -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-user-circle me-2 text-success"></i>Informations du Demandeur
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small mb-1">Nom complet</label>
                            <p class="mb-0 fw-bold">{{ $demande->prenom }} {{ $demande->nom }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small mb-1">Email</label>
                            <p class="mb-0">
                                <i class="fas fa-envelope text-success me-2"></i>
                                <a href="mailto:{{ $demande->email }}">{{ $demande->email }}</a>
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small mb-1">Téléphone</label>
                            <p class="mb-0">
                                <i class="fas fa-phone text-success me-2"></i>
                                <a href="tel:{{ $demande->telephone }}">{{ $demande->telephone }}</a>
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small mb-1">Numéro CNI</label>
                            <p class="mb-0 fw-bold">{{ $demande->cni }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small mb-1">Ville</label>
                            <p class="mb-0">
                                <i class="fas fa-map-marker-alt text-success me-2"></i>
                                {{ $demande->ville }}
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small mb-1">Adresse</label>
                            <p class="mb-0">{{ $demande->adresse ?? 'Non renseignée' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description du projet -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-project-diagram me-2 text-success"></i>Description du Projet
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <label class="text-muted small mb-2">Objectifs du projet</label>
                        <p class="text-justify">{{ $demande->description_projet }}</p>
                    </div>
                    <div>
                        <label class="text-muted small mb-2">Utilisation des fonds</label>
                        <div class="bg-light p-3 rounded">
                            <p class="mb-0">{{ $demande->utilisation_fonds }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Documents -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-folder-open me-2 text-success"></i>Documents fournis
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Pièce d'identité -->
                        @if($demande->piece_identite)
                        <div class="col-md-6 mb-3">
                            <div class="border rounded p-3 h-100">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-id-card fa-2x text-success me-3"></i>
                                    <div>
                                        <h6 class="mb-0 fw-bold">Pièce d'identité</h6>
                                        <small class="text-muted">CNI ou Passeport</small>
                                    </div>
                                </div>
                                <a href="{{ Storage::url($demande->piece_identite) }}" 
                                   target="_blank" 
                                   class="btn btn-sm btn-outline-success w-100 mt-2">
                                    <i class="fas fa-eye me-1"></i>Visualiser
                                </a>
                            </div>
                        </div>
                        @endif

                        <!-- Certificat de résidence -->
                        @if($demande->certificat_residence)
                        <div class="col-md-6 mb-3">
                            <div class="border rounded p-3 h-100">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-home fa-2x text-success me-3"></i>
                                    <div>
                                        <h6 class="mb-0 fw-bold">Certificat de résidence</h6>
                                        <small class="text-muted">Justificatif de domicile</small>
                                    </div>
                                </div>
                                <a href="{{ Storage::url($demande->certificat_residence) }}" 
                                   target="_blank" 
                                   class="btn btn-sm btn-outline-success w-100 mt-2">
                                    <i class="fas fa-eye me-1"></i>Visualiser
                                </a>
                            </div>
                        </div>
                        @endif

                        <!-- Relevé bancaire -->
                        @if($demande->releve_bancaire)
                        <div class="col-md-6 mb-3">
                            <div class="border rounded p-3 h-100">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-university fa-2x text-info me-3"></i>
                                    <div>
                                        <h6 class="mb-0 fw-bold">Relevé bancaire</h6>
                                        <small class="text-muted">Document optionnel</small>
                                    </div>
                                </div>
                                <a href="{{ Storage::url($demande->releve_bancaire) }}" 
                                   target="_blank" 
                                   class="btn btn-sm btn-outline-info w-100 mt-2">
                                    <i class="fas fa-eye me-1"></i>Visualiser
                                </a>
                            </div>
                        </div>
                        @endif

                        <!-- Plan d'affaires -->
                        @if($demande->plan_affaires)
                        <div class="col-md-6 mb-3">
                            <div class="border rounded p-3 h-100">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-file-alt fa-2x text-info me-3"></i>
                                    <div>
                                        <h6 class="mb-0 fw-bold">Plan d'affaires</h6>
                                        <small class="text-muted">Document optionnel</small>
                                    </div>
                                </div>
                                <a href="{{ Storage::url($demande->plan_affaires) }}" 
                                   target="_blank" 
                                   class="btn btn-sm btn-outline-info w-100 mt-2">
                                    <i class="fas fa-eye me-1"></i>Visualiser
                                </a>
                            </div>
                        </div>
                        @endif

                        <!-- Contrats -->
                        @if($demande->contrats)
                        <div class="col-md-6 mb-3">
                            <div class="border rounded p-3 h-100">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-file-contract fa-2x text-info me-3"></i>
                                    <div>
                                        <h6 class="mb-0 fw-bold">Contrats/Accords</h6>
                                        <small class="text-muted">Document optionnel</small>
                                    </div>
                                </div>
                                <a href="{{ Storage::url($demande->contrats) }}" 
                                   target="_blank" 
                                   class="btn btn-sm btn-outline-info w-100 mt-2">
                                    <i class="fas fa-eye me-1"></i>Visualiser
                                </a>
                            </div>
                        </div>
                        @endif
                    </div>

                    @if(!$demande->piece_identite && !$demande->certificat_residence)
                    <div class="alert alert-warning mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Aucun document n'a été téléchargé pour cette demande.
                    </div>
                    @endif
                </div>
            </div>

            <!-- Message de l'administration -->
            @if($demande->message_admin)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-{{ $demande->statut === 'rejete' ? 'danger' : 'info' }} text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-comment-dots me-2"></i>Message de l'équipe Fubadj
                    </h5>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $demande->message_admin }}</p>
                    @if($demande->date_reponse)
                    <small class="text-muted d-block mt-2">
                        <i class="fas fa-clock me-1"></i>
                        Envoyé le {{ \Carbon\Carbon::parse($demande->date_reponse)->format('d/m/Y à H:i') }}
                    </small>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Colonne latérale -->
        <div class="col-lg-4">
            <!-- Timeline de traitement -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-history me-2 text-success"></i>Historique du traitement
                    </h5>
                </div>
                <div class="card-body">
                    @php
                        $timeline = [
                            [
                                'date' => $demande->created_at,
                                'titre' => 'Demande soumise',
                                'description' => 'Votre demande a été enregistrée',
                                'icon' => 'fa-paper-plane',
                                'color' => 'success',
                                'completed' => true
                            ],
                            [
                                'date' => $demande->date_examen ?? null,
                                'titre' => 'En cours d\'examen',
                                'description' => 'Notre équipe analyse votre dossier',
                                'icon' => 'fa-search',
                                'color' => 'info',
                                'completed' => in_array($demande->statut, ['en_cours', 'approuve', 'rejete', 'debloque'])
                            ],
                            [
                                'date' => $demande->date_decision ?? null,
                                'titre' => $demande->statut === 'rejete' ? 'Demande rejetée' : 'Demande approuvée',
                                'description' => $demande->statut === 'rejete' ? 'Votre demande n\'a pas été acceptée' : 'Félicitations ! Votre demande est validée',
                                'icon' => $demande->statut === 'rejete' ? 'fa-times-circle' : 'fa-check-circle',
                                'color' => $demande->statut === 'rejete' ? 'danger' : 'success',
                                'completed' => in_array($demande->statut, ['approuve', 'rejete', 'debloque'])
                            ],
                            [
                                'date' => $demande->date_deblocage ?? null,
                                'titre' => 'Fonds débloqués',
                                'description' => 'Les fonds ont été transférés',
                                'icon' => 'fa-money-bill-wave',
                                'color' => 'success',
                                'completed' => $demande->statut === 'debloque'
                            ]
                        ];
                    @endphp

                    <div class="timeline">
                        @foreach($timeline as $index => $event)
                        <div class="timeline-item {{ $event['completed'] ? 'completed' : 'pending' }}">
                            <div class="timeline-marker bg-{{ $event['color'] }}">
                                <i class="fas {{ $event['icon'] }} text-white"></i>
                            </div>
                            <div class="timeline-content">
                                <h6 class="mb-1 fw-bold">{{ $event['titre'] }}</h6>
                                <p class="small text-muted mb-1">{{ $event['description'] }}</p>
                                @if($event['date'])
                                <small class="text-muted">
                                    <i class="fas fa-calendar me-1"></i>
                                    {{ \Carbon\Carbon::parse($event['date'])->format('d/m/Y à H:i') }}
                                </small>
                                @else
                                <small class="text-muted">En attente...</small>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Structure financière -->
            @if($demande->structure_financiere)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-university me-2 text-success"></i>Partenaire financier
                    </h5>
                </div>
                <div class="card-body text-center">
                    <div class="mb-3">
                        <div class="bg-warning bg-opacity-10 p-4 rounded-circle d-inline-block">
                            <i class="fas fa-building fa-3x text-warning"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold">{{ $demande->structure_financiere }}</h5>
                    <p class="text-muted small">Partenaire sélectionné</p>
                    <div class="border-top pt-3 mt-3">
                        <div class="row text-center">
                            <div class="col-6">
                                <h6 class="text-muted small mb-1">Taux</h6>
                                <p class="fw-bold mb-0">8% /an</p>
                            </div>
                            <div class="col-6">
                                <h6 class="text-muted small mb-1">Délai</h6>
                                <p class="fw-bold mb-0">7 jours</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Actions rapides -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-bolt me-2 text-success"></i>Actions
                    </h5>
                </div>
                <div class="card-body">
                    @if($demande->statut === 'en_attente')
                    <a href="{{ route('financement.edit', $demande->id) }}" 
                       class="btn btn-primary w-100 mb-2">
                        <i class="fas fa-edit me-2"></i>Modifier la demande
                    </a>
                    <button type="button" 
                            class="btn btn-outline-danger w-100"
                            onclick="confirmerAnnulation({{ $demande->id }})">
                        <i class="fas fa-times me-2"></i>Annuler la demande
                    </button>
                    @endif

                    @if(in_array($demande->statut, ['approuve', 'debloque']))
                    <a href="{{ route('financement.contrat', $demande->id) }}" 
                       class="btn btn-success w-100 mb-2"
                       target="_blank">
                        <i class="fas fa-file-contract me-2"></i>Télécharger le contrat
                    </a>
                    <a href="{{ route('financement.echeancier', $demande->id) }}" 
                       class="btn btn-outline-success w-100 mb-2"
                       target="_blank">
                        <i class="fas fa-calendar-alt me-2"></i>Voir l'échéancier
                    </a>
                    @endif

                    <a href="{{ route('financement.imprimer', $demande->id) }}" 
                       class="btn btn-outline-secondary w-100"
                       target="_blank">
                        <i class="fas fa-print me-2"></i>Imprimer le dossier
                    </a>
                </div>
            </div>

            <!-- Support -->
            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body text-center">
                    <i class="fas fa-headset fa-3x text-success mb-3"></i>
                    <h6 class="fw-bold">Besoin d'aide ?</h6>
                    <p class="small text-muted mb-3">Notre équipe est disponible pour vous accompagner</p>
                    <a href="tel:+221331234567" class="btn btn-success btn-sm">
                        <i class="fas fa-phone me-2"></i>+221 33 123 45 67
                    </a>
                    <a href="mailto:support@fubadj.sn" class="btn btn-outline-success btn-sm mt-2 w-100">
                        <i class="fas fa-envelope me-2"></i>support@fubadj.sn
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation d'annulation -->
<div class="modal fade" id="confirmAnnulationModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle me-2"></i>Confirmer l'annulation
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir annuler cette demande de financement ?</p>
                <p class="text-danger fw-bold">Cette action est irréversible.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Non, garder</button>
                <form id="formAnnulation" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times me-2"></i>Oui, annuler
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .timeline {
        position: relative;
        padding-left: 0;
    }
    
    .timeline-item {
        position: relative;
        padding-left: 50px;
        padding-bottom: 30px;
    }
    
    .timeline-item:not(:last-child)::before {
        content: '';
        position: absolute;
        left: 20px;
        top: 40px;
        width: 2px;
        height: calc(100% - 10px);
        background: #e9ecef;
    }
    
    .timeline-item.completed:not(:last-child)::before {
        background: #28a745;
    }
    
    .timeline-marker {
        position: absolute;
        left: 0;
        top: 0;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .timeline-item.pending .timeline-marker {
        background-color: #e9ecef !important;
    }
    
    .timeline-item.pending .timeline-marker i {
        color: #6c757d !important;
    }
    
    .timeline-content {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        border-left: 3px solid #28a745;
    }
    
    .timeline-item.pending .timeline-content {
        border-left-color: #e9ecef;
        opacity: 0.6;
    }
    
    .card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }
</style>
@endsection

@section('scripts')
<script>
    function confirmerAnnulation(demandeId) {
        const modal = new bootstrap.Modal(document.getElementById('confirmAnnulationModal'));
        const form = document.getElementById('formAnnulation');
        form.action = `/financement/${demandeId}`;
        modal.show();
    }
    
    // Animation au chargement
    document.addEventListener('DOMContentLoaded', function() {
        const timelineItems = document.querySelectorAll('.timeline-item');
        
        timelineItems.forEach((item, index) => {
            setTimeout(() => {
                item.style.opacity = '0';
                item.style.transform = 'translateX(-20px)';
                item.style.transition = 'all 0.5s ease';
                
                setTimeout(() => {
                    item.style.opacity = '1';
                    item.style.transform = 'translateX(0)';
                }, 50);
            }, index * 100);
        });
    });
</script>
@endsection