@extends('layouts.app')

@section('title', 'Financement - Fubadj')

@section('content')
<div class="container py-5">
    <!-- En-tête -->
    <div class="row mb-5">
        <div class="col-12 text-center">
            <h1 class="display-5 fw-bold text-success mb-3">
                <i class="fas fa-hand-holding-usd me-3"></i>Financement Agricole
            </h1>
            <p class="lead text-muted mb-4">
                Accédez à des financements adaptés pour développer votre activité agricole
            </p>
            <div class="d-flex justify-content-center gap-3">
                <a href="#demander" class="btn btn-success btn-lg px-4">
                    <i class="fas fa-paper-plane me-2"></i>Faire une demande
                </a>
                <a href="#informations" class="btn btn-outline-success btn-lg px-4">
                    <i class="fas fa-info-circle me-2"></i>En savoir plus
                </a>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="row mb-5">
        <div class="col-md-3 col-6 mb-4">
            <div class="text-center">
                <div class="fs-1 fw-bold text-success mb-2">500K+</div>
                <div class="text-muted">Financements accordés</div>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-4">
            <div class="text-center">
                <div class="fs-1 fw-bold text-success mb-2">24h</div>
                <div class="text-muted">Pré-étude de dossier</div>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-4">
            <div class="text-center">
                <div class="fs-1 fw-bold text-success mb-2">14j</div>
                <div class="text-muted">Délai de réponse</div>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-4">
            <div class="text-center">
                <div class="fs-1 fw-bold text-success mb-2">95%</div>
                <div class="text-muted">Clients satisfaits</div>
            </div>
        </div>
    </div>

    <!-- Types de financement -->
    <div class="row mb-5" id="informations">
        <div class="col-12 mb-4">
            <h2 class="fw-bold text-center mb-4">Nos Solutions de Financement</h2>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm hover-shadow">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <i class="fas fa-seedling fa-3x text-success"></i>
                    </div>
                    <h4 class="card-title fw-bold mb-3">Financement Production</h4>
                    <p class="card-text mb-4">
                        Pour les producteurs qui souhaitent développer leur activité : 
                        achat de semences, équipements, main d'œuvre saisonnière.
                    </p>
                    <ul class="list-unstyled text-start">
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Montant : 500K à 5M FCFA</li>
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Durée : 6 à 24 mois</li>
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Taux préférentiel</li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm hover-shadow">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <i class="fas fa-tractor fa-3x text-success"></i>
                    </div>
                    <h4 class="card-title fw-bold mb-3">Financement Équipement</h4>
                    <p class="card-text mb-4">
                        Pour l'acquisition de matériel agricole : tracteurs, systèmes d'irrigation, 
                        équipements de transformation.
                    </p>
                    <ul class="list-unstyled text-start">
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Montant : 2M à 10M FCFA</li>
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Durée : 12 à 36 mois</li>
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Garanties adaptées</li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm hover-shadow">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <i class="fas fa-warehouse fa-3x text-success"></i>
                    </div>
                    <h4 class="card-title fw-bold mb-3">Financement Infrastructure</h4>
                    <p class="card-text mb-4">
                        Pour la construction ou rénovation d'infrastructures : 
                        hangars, chambres froides, entrepôts, unités de transformation.
                    </p>
                    <ul class="list-unstyled text-start">
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Montant : 5M à 10M FCFA</li>
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Durée : 24 à 36 mois</li>
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Étude de projet</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Processus -->
    <div class="row mb-5">
        <div class="col-12 mb-4">
            <h2 class="fw-bold text-center mb-5">Comment ça marche ?</h2>
        </div>
        
        <div class="col-md-3 mb-4">
            <div class="text-center position-relative">
                <div class="rounded-circle bg-success text-white d-inline-flex align-items-center justify-content-center mb-3" 
                     style="width: 80px; height: 80px;">
                    <span class="fs-4 fw-bold">1</span>
                </div>
                <h5 class="fw-bold mb-2">Demande en ligne</h5>
                <p class="text-muted">Remplissez le formulaire en 10 minutes</p>
            </div>
        </div>
        
        <div class="col-md-3 mb-4">
            <div class="text-center position-relative">
                <div class="rounded-circle bg-success text-white d-inline-flex align-items-center justify-content-center mb-3" 
                     style="width: 80px; height: 80px;">
                    <span class="fs-4 fw-bold">2</span>
                </div>
                <h5 class="fw-bold mb-2">Étude de dossier</h5>
                <p class="text-muted">Analyse par nos experts sous 24h</p>
            </div>
        </div>
        
        <div class="col-md-3 mb-4">
            <div class="text-center position-relative">
                <div class="rounded-circle bg-success text-white d-inline-flex align-items-center justify-content-center mb-3" 
                     style="width: 80px; height: 80px;">
                    <span class="fs-4 fw-bold">3</span>
                </div>
                <h5 class="fw-bold mb-2">Entretien</h5>
                <p class="text-muted">Visite terrain ou entretien téléphonique</p>
            </div>
        </div>
        
        <div class="col-md-3 mb-4">
            <div class="text-center position-relative">
                <div class="rounded-circle bg-success text-white d-inline-flex align-items-center justify-content-center mb-3" 
                     style="width: 80px; height: 80px;">
                    <span class="fs-4 fw-bold">4</span>
                </div>
                <h5 class="fw-bold mb-2">Déblocage</h5>
                <p class="text-muted">Versement des fonds sous 7 jours</p>
            </div>
        </div>
    </div>

    <!-- FAQ -->
    <div class="row mb-5">
        <div class="col-12 mb-4">
            <h2 class="fw-bold text-center mb-4">Questions Fréquentes</h2>
        </div>
        
        <div class="col-lg-8 mx-auto">
            <div class="accordion" id="faqAccordion">
                <div class="accordion-item border-0 shadow-sm mb-3">
                    <h2 class="accordion-header" id="headingOne">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                                data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                            <i class="fas fa-question-circle text-success me-3"></i>
                            Quels sont les critères d'éligibilité ?
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" 
                         data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            <ul class="mb-0">
                                <li>Être âgé de 21 ans minimum</li>
                                <li>Avoir un projet agricole viable</li>
                                <li>Résider en Afrique de l'Ouest</li>
                                <li>Disposer d'un compte bancaire</li>
                                <li>Fournir les documents requis</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div class="accordion-item border-0 shadow-sm mb-3">
                    <h2 class="accordion-header" id="headingTwo">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                                data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            <i class="fas fa-question-circle text-success me-3"></i>
                            Quels documents sont nécessaires ?
                        </button>
                    </h2>
                    <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" 
                         data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            <p>Les documents obligatoires :</p>
                            <ul>
                                <li>Pièce d'identité (CNI ou Passeport)</li>
                                <li>Justificatif de domicile récent</li>
                                <li>Relevé d'identité bancaire</li>
                                <li>Description détaillée du projet</li>
                            </ul>
                            <p class="mb-0">Documents recommandés :</p>
                            <ul class="mb-0">
                                <li>Plan d'affaires</li>
                                <li>Photos du projet existant</li>
                                <li>Contrats d'achat/vente</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div class="accordion-item border-0 shadow-sm mb-3">
                    <h2 class="accordion-header" id="headingThree">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                                data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            <i class="fas fa-question-circle text-success me-3"></i>
                            Combien de temps dure le traitement ?
                        </button>
                    </h2>
                    <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" 
                         data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            <p>Le traitement varie selon le type de financement :</p>
                            <ul class="mb-0">
                                <li><strong>Pré-étude :</strong> 24 à 48 heures</li>
                                <li><strong>Étude complète :</strong> 5 à 7 jours ouvrés</li>
                                <li><strong>Entretien :</strong> Sous 48h après validation du dossier</li>
                                <li><strong>Déblocage :</strong> 2 à 3 jours après signature</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section demande -->
    <div class="row" id="demander">
        <div class="col-lg-8 mx-auto">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-success text-white py-4">
                    <h3 class="mb-0 text-center">
                        <i class="fas fa-paper-plane me-2"></i>Faire une demande de financement
                    </h3>
                </div>
                <div class="card-body p-4 p-md-5">
                    @auth
                        @php
                            // Récupérer le rôle de l'utilisateur
                            $userRole = 'Utilisateur';
                            $user = Auth::user();
                            
                            if ($user) {
                                if ($user->isAdmin()) {
                                    $userRole = 'Administrateur';
                                } elseif ($user->isProducteur()) {
                                    $userRole = 'Producteur';
                                } elseif ($user->isAcheteur()) {
                                    $userRole = 'Acheteur';
                                } elseif (!empty($user->role)) {
                                    $userRole = ucfirst($user->role);
                                }
                            }
                            
                            // Initialiser les variables
                            $demandesEnCours = $demandesEnCours ?? collect();
                            $demandesHistorique = $demandesHistorique ?? collect();
                        @endphp

                        @if($user->isAdmin())
                            <div class="alert alert-warning text-center">
                                <i class="fas fa-info-circle me-2"></i>
                                Les administrateurs ne peuvent pas soumettre de demandes de financement.
                                <a href="{{ route('admin.financements') }}" class="alert-link ms-1">
                                    Accéder à la gestion des financements
                                </a>
                            </div>
                        @else
                            <!-- Vue pour les utilisateurs (producteurs et acheteurs) -->
                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="fas fa-check-circle me-2"></i>
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            @if($demandesEnCours->count() > 0)
                                <div class="alert alert-info">
                                    <div class="d-flex">
                                        <i class="fas fa-info-circle fa-2x me-3 align-self-start"></i>
                                        <div>
                                            <h5 class="alert-heading mb-2">Vous avez {{ $demandesEnCours->count() }} demande(s) en cours</h5>
                                            @foreach($demandesEnCours as $demande)
                                                <div class="mb-2">
                                                    <strong>{{ $demande->titre_projet }}</strong> - 
                                                    <span class="badge bg-info">{{ $demande->numero_dossier }}</span> - 
                                                    <span class="badge bg-warning">{{ ucfirst(str_replace('_', ' ', $demande->statut)) }}</span>
                                                    <a href="{{ route('financement.show', $demande->id) }}" class="ms-2">
                                                        <i class="fas fa-eye"></i> Voir
                                                    </a>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="text-center mb-4">
                                <p class="lead">Vous êtes connecté en tant que <strong>{{ Auth::user()->name }}</strong> ({{ $userRole }})</p>
                                <p>Vos informations personnelles seront pré-remplies dans le formulaire.</p>
                            </div>

                            <div class="d-grid gap-3">
                                <a href="{{ route('financement.demande') }}" class="btn btn-success btn-lg py-3">
                                    <i class="fas fa-plus-circle me-2"></i>Nouvelle demande de financement
                                </a>
                                
                                @if($demandesHistorique->count() > 0)
                                    <a href="{{ route('financement.historique') }}" class="btn btn-outline-success btn-lg py-3">
                                        <i class="fas fa-history me-2"></i>Voir mes demandes précédentes
                                    </a>
                                @endif
                            </div>
                        @endif
                    @else
                        <!-- Vue pour les visiteurs non connectés -->
                        <div class="text-center py-4">
                            <div class="mb-4">
                                <i class="fas fa-user-lock fa-4x text-muted mb-3"></i>
                                <h4 class="mb-3">Connectez-vous pour accéder au formulaire</h4>
                                <p class="text-muted mb-4">
                                Pour soumettre une demande de financement, vous devez avoir un compte Fubadj.
                                </p>
                            </div>
                            
                            <div class="d-grid gap-3 col-lg-6 mx-auto">
                                <a href="{{ route('login') }}" class="btn btn-success btn-lg">
                                    <i class="fas fa-sign-in-alt me-2"></i>Se connecter
                                </a>
                                <a href="{{ route('register') }}" class="btn btn-outline-success btn-lg">
                                    <i class="fas fa-user-plus me-2"></i>Créer un compte
                                </a>
                            </div>
                            
                            <div class="mt-5">
                                <h5 class="mb-3">Vous préférez nous contacter directement ?</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="d-flex align-items-center justify-content-center">
                                            <i class="fas fa-phone-alt text-success me-3 fs-4"></i>
                                            <div>
                                                <div class="fw-bold">Service Financement</div>
                                                <div>+221 33 123 45 67</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="d-flex align-items-center justify-content-center">
                                            <i class="fas fa-envelope text-success me-3 fs-4"></i>
                                            <div>
                                                <div class="fw-bold">Email</div>
                                                <div>financement@fubadj.sn</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <!-- Témoignages -->
    <div class="row mt-5">
        <div class="col-12 mb-4">
            <h2 class="fw-bold text-center mb-4">Ils nous font confiance</h2>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center me-3" 
                             style="width: 60px; height: 60px;">
                            <i class="fas fa-user fs-4"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">Mamadou Diop</h5>
                            <small class="text-muted">Producteur de riz, Kaolack</small>
                        </div>
                    </div>
                    <p class="card-text">
                        "Grâce au financement Fubadj, j'ai pu acheter un nouveau tracteur et doubler ma production. 
                        L'accompagnement a été exceptionnel !"
                    </p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center me-3" 
                             style="width: 60px; height: 60px;">
                            <i class="fas fa-user fs-4"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">Aïssatou Diallo</h5>
                            <small class="text-muted">Maraîchère, Thiès</small>
                        </div>
                    </div>
                    <p class="card-text">
                        "Le processus était simple et rapide. En 15 jours, j'avais les fonds pour installer 
                        mon système d'irrigation goutte-à-goutte."
                    </p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center me-3" 
                             style="width: 60px; height: 60px;">
                            <i class="fas fa-user fs-4"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">Cheikh Ndiaye</h5>
                            <small class="text-muted">Acheteur, Dakar</small>
                        </div>
                    </div>
                    <p class="card-text">
                        "En tant qu'acheteur, j'ai pu financer ma première récolte de mangues pour l'export. 
                        Un partenariat gagnant-gagnant avec les producteurs."
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .hover-shadow {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .hover-shadow:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
    }
    
    .accordion-button:not(.collapsed) {
        background-color: rgba(40, 167, 69, 0.1);
        color: #28a745;
        border-color: #28a745;
    }
    
    .accordion-button:focus {
        box-shadow: 0 0 0 0.25rem rgba(40, 167, 69, 0.25);
        border-color: #28a745;
    }
</style>
@endsection