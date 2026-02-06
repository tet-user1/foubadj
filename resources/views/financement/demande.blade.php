@extends('layouts.app')

@section('title', 'Nouvelle Demande de Financement - Fubadj')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- En-tête avec étapes -->
            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h1 class="h3 fw-bold text-success">
                        <i class="fas fa-hand-holding-usd me-2"></i>Nouvelle Demande de Financement
                    </h1>
                    <span class="badge bg-success fs-6">Étape 1/3</span>
                </div>
                
                <!-- Barre de progression -->
                <div class="progress mb-4" style="height: 10px;">
                    <div class="progress-bar bg-success" role="progressbar" style="width: 33%" 
                         aria-valuenow="33" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                
                <!-- Étapes -->
                <div class="row text-center">
                    <div class="col-4">
                        <div class="p-2 border-bottom border-3 border-success">
                            <i class="fas fa-user-circle fa-lg text-success mb-2"></i>
                            <div class="fw-bold">Informations Personnelles</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-2 border-bottom border-3 border-light">
                            <i class="fas fa-university fa-lg text-muted mb-2"></i>
                            <div class="text-muted">Structure Financière</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-2 border-bottom border-3 border-light">
                            <i class="fas fa-check-circle fa-lg text-muted mb-2"></i>
                            <div class="text-muted">Validation</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Carte principale -->
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-white border-bottom py-3">
                    <h3 class="mb-0 text-success">
                        <i class="fas fa-user-circle me-2"></i>1. Informations Personnelles
                    </h3>
                </div>

                <div class="card-body p-4">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <strong>Veuillez corriger les erreurs suivantes :</strong>
                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form id="demandeForm" action="{{ route('financement.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Section 1: Informations de base -->
                        <div class="mb-5">
                            <h4 class="text-success mb-4 border-bottom pb-2">
                                <i class="fas fa-id-card me-2"></i>Identité du Demandeur
                            </h4>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="prenom" class="form-label fw-bold">Prénom <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('prenom') is-invalid @enderror" 
                                           id="prenom" name="prenom" value="{{ old('prenom', Auth::user()->name) }}" 
                                           placeholder="Votre prénom" required>
                                    @error('prenom')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="nom" class="form-label fw-bold">Nom <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nom') is-invalid @enderror" 
                                           id="nom" name="nom" value="{{ old('nom', '') }}" 
                                           placeholder="Votre nom de famille" required>
                                    @error('nom')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label fw-bold">Adresse email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email', Auth::user()->email) }}" 
                                           placeholder="exemple@email.com" required readonly>
                                    <small class="text-muted">Cette adresse est associée à votre compte</small>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="telephone" class="form-label fw-bold">Téléphone <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control @error('telephone') is-invalid @enderror" 
                                           id="telephone" name="telephone" value="{{ old('telephone') }}" 
                                           placeholder="Ex: +221 77 123 45 67" required>
                                    @error('telephone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="cni" class="form-label fw-bold">Numéro CNI <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('cni') is-invalid @enderror" 
                                           id="cni" name="cni" value="{{ old('cni') }}" 
                                           placeholder="Ex: 1 23 45678 9 0" required>
                                    <small class="text-muted">Numéro de votre carte nationale d'identité</small>
                                    @error('cni')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="ville" class="form-label fw-bold">Ville de résidence <span class="text-danger">*</span></label>
                                    <select class="form-select @error('ville') is-invalid @enderror" 
                                            id="ville" name="ville" required>
                                        <option value="">Sélectionnez votre ville</option>
                                        <option value="Dakar" {{ old('ville') == 'Dakar' ? 'selected' : 'selected' }}>Dakar</option>
                                        <option value="Thiès" {{ old('ville') == 'Thiès' ? 'selected' : '' }}>Thiès</option>
                                        <option value="Saint-Louis" {{ old('ville') == 'Saint-Louis' ? 'selected' : '' }}>Saint-Louis</option>
                                        <option value="Kaolack" {{ old('ville') == 'Kaolack' ? 'selected' : '' }}>Kaolack</option>
                                        <option value="Ziguinchor" {{ old('ville') == 'Ziguinchor' ? 'selected' : '' }}>Ziguinchor</option>
                                        <option value="Diourbel" {{ old('ville') == 'Diourbel' ? 'selected' : '' }}>Diourbel</option>
                                        <option value="Louga" {{ old('ville') == 'Louga' ? 'selected' : '' }}>Louga</option>
                                        <option value="Tambacounda" {{ old('ville') == 'Tambacounda' ? 'selected' : '' }}>Tambacounda</option>
                                        <option value="Kolda" {{ old('ville') == 'Kolda' ? 'selected' : '' }}>Kolda</option>
                                        <option value="Matam" {{ old('ville') == 'Matam' ? 'selected' : '' }}>Matam</option>
                                        <option value="Kédougou" {{ old('ville') == 'Kédougou' ? 'selected' : '' }}>Kédougou</option>
                                        <option value="Sédhiou" {{ old('ville') == 'Sédhiou' ? 'selected' : '' }}>Sédhiou</option>
                                    </select>
                                    @error('ville')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label for="adresse" class="form-label fw-bold">Adresse complète</label>
                                    <textarea class="form-control @error('adresse') is-invalid @enderror" 
                                              id="adresse" name="adresse" rows="2" 
                                              placeholder="Rue, quartier, etc.">{{ old('adresse') }}</textarea>
                                    @error('adresse')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Section 2: Informations sur le projet -->
                        <div class="mb-5">
                            <h4 class="text-success mb-4 border-bottom pb-2">
                                <i class="fas fa-project-diagram me-2"></i>Informations sur le Projet
                            </h4>
                            
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label for="titre_projet" class="form-label fw-bold">Titre du projet <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('titre_projet') is-invalid @enderror" 
                                           id="titre_projet" name="titre_projet" value="{{ old('titre_projet') }}" 
                                           placeholder="Ex: Expansion de ma production de riz" required>
                                    @error('titre_projet')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="montant_demande" class="form-label fw-bold">Montant demandé (FCFA) <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('montant_demande') is-invalid @enderror" 
                                               id="montant_demande" name="montant_demande" value="{{ old('montant_demande') }}" 
                                               min="500000" max="10000000" step="50000" placeholder="500000" required>
                                        <span class="input-group-text">FCFA</span>
                                    </div>
                                    <small class="text-muted">Entre 500 000 FCFA et 10 000 000 FCFA</small>
                                    @error('montant_demande')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="duree_remboursement" class="form-label fw-bold">Durée de remboursement <span class="text-danger">*</span></label>
                                    <select class="form-select @error('duree_remboursement') is-invalid @enderror" 
                                            id="duree_remboursement" name="duree_remboursement" required>
                                        <option value="">Sélectionnez une durée</option>
                                        <option value="6" {{ old('duree_remboursement') == '6' ? 'selected' : '' }}>6 mois</option>
                                        <option value="12" {{ old('duree_remboursement') == '12' ? 'selected' : '' }}>12 mois</option>
                                        <option value="18" {{ old('duree_remboursement') == '18' ? 'selected' : '' }}>18 mois</option>
                                        <option value="24" {{ old('duree_remboursement') == '24' ? 'selected' : '' }}>24 mois</option>
                                        <option value="36" {{ old('duree_remboursement') == '36' ? 'selected' : '' }}>36 mois</option>
                                    </select>
                                    @error('duree_remboursement')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 mb-3">
                                    <label for="description_projet" class="form-label fw-bold">Description du projet <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('description_projet') is-invalid @enderror" 
                                              id="description_projet" name="description_projet" rows="4" 
                                              placeholder="Décrivez votre projet en détail..." required>{{ old('description_projet') }}</textarea>
                                    <small class="text-muted">Expliquez les objectifs, activités et bénéfices attendus</small>
                                    @error('description_projet')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 mb-3">
                                    <label for="utilisation_fonds" class="form-label fw-bold">Utilisation des fonds <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('utilisation_fonds') is-invalid @enderror" 
                                              id="utilisation_fonds" name="utilisation_fonds" rows="3" 
                                              placeholder="Détaillez comment vous utiliserez les fonds..." required>{{ old('utilisation_fonds') }}</textarea>
                                    <small class="text-muted">Ex: Achat de semences (30%), équipements (40%), main d'œuvre (30%)</small>
                                    @error('utilisation_fonds')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Section 3: Documents -->
                        <div class="mb-5">
                            <h4 class="text-success mb-4 border-bottom pb-2">
                                <i class="fas fa-file-upload me-2"></i>Espace Documents
                            </h4>
                            
                            <div class="alert alert-info mb-4">
                                <div class="d-flex">
                                    <i class="fas fa-info-circle fa-2x me-3 align-self-start"></i>
                                    <div>
                                        <h5 class="alert-heading mb-2">Informations importantes</h5>
                                        <p class="mb-2">Tous les documents doivent être au format PDF, JPG ou PNG.</p>
                                        <p class="mb-0">Taille maximale par fichier : 5MB</p>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Pièce d'identité -->
                                <div class="col-md-6 mb-4">
                                    <div class="card border h-100">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0 fw-bold">
                                                <i class="fas fa-id-card text-success me-2"></i>
                                                Pièce d'identité <span class="text-danger">*</span>
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label for="piece_identite" class="form-label fw-bold">
                                                    CNI ou Passeport
                                                </label>
                                                <input type="file" class="form-control @error('piece_identite') is-invalid @enderror" 
                                                       id="piece_identite" name="piece_identite" 
                                                       accept=".pdf,.jpg,.jpeg,.png" required>
                                                @error('piece_identite')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="form-text">
                                                <i class="fas fa-lightbulb text-warning me-1"></i>
                                                Photographiez ou scannez votre CNI recto-verso ou passeport
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Certificat de résidence -->
                                <div class="col-md-6 mb-4">
                                    <div class="card border h-100">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0 fw-bold">
                                                <i class="fas fa-home text-success me-2"></i>
                                                Certificat de Résidence <span class="text-danger">*</span>
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label for="certificat_residence" class="form-label fw-bold">
                                                    Justificatif de domicile
                                                </label>
                                                <input type="file" class="form-control @error('certificat_residence') is-invalid @enderror" 
                                                       id="certificat_residence" name="certificat_residence" 
                                                       accept=".pdf,.jpg,.jpeg,.png" required>
                                                @error('certificat_residence')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="form-text">
                                                <i class="fas fa-lightbulb text-warning me-1"></i>
                                                Facture d'eau, électricité ou téléphone de moins de 3 mois
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Documents supplémentaires (optionnels) -->
                                <div class="col-12 mb-4">
                                    <div class="card border">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0 fw-bold">
                                                <i class="fas fa-folder-plus text-success me-2"></i>
                                                Documents supplémentaires (optionnels)
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-4 mb-3">
                                                    <label for="releve_bancaire" class="form-label">
                                                        Relevé bancaire
                                                    </label>
                                                    <input type="file" class="form-control @error('releve_bancaire') is-invalid @enderror" 
                                                           id="releve_bancaire" name="releve_bancaire" 
                                                           accept=".pdf,.jpg,.jpeg,.png">
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label for="plan_affaires" class="form-label">
                                                        Plan d'affaires
                                                    </label>
                                                    <input type="file" class="form-control @error('plan_affaires') is-invalid @enderror" 
                                                           id="plan_affaires" name="plan_affaires" 
                                                           accept=".pdf,.doc,.docx">
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label for="contrats" class="form-label">
                                                        Contrats/Accords
                                                    </label>
                                                    <input type="file" class="form-control @error('contrats') is-invalid @enderror" 
                                                           id="contrats" name="contrats" 
                                                           accept=".pdf,.jpg,.jpeg,.png">
                                                </div>
                                            </div>
                                            <div class="form-text">
                                                <i class="fas fa-lightbulb text-warning me-1"></i>
                                                Ces documents peuvent accélérer le traitement de votre dossier
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section 4: Choix de la structure financière -->
                        <div class="mb-5">
                            <h4 class="text-success mb-4 border-bottom pb-2">
                                <i class="fas fa-university me-2"></i>2. Choix de la Structure Financière
                            </h4>
                            
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Cette section sera disponible à l'étape suivante après validation de vos informations personnelles.
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card border-success">
                                        <div class="card-header bg-success text-white">
                                            <h5 class="mb-0">
                                                <i class="fas fa-mobile-alt me-2"></i>
                                                Orange Bank
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="bg-warning p-3 rounded me-3">
                                                    <i class="fas fa-university fa-2x text-white"></i>
                                                </div>
                                                <div>
                                                    <h6 class="fw-bold mb-1">Partenaire principal</h6>
                                                    <p class="text-muted mb-0">Taux préférentiel pour les membres Fubadj</p>
                                                </div>
                                            </div>
                                            <ul class="list-unstyled">
                                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Taux d'intérêt : 8% annuel</li>
                                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Délai de traitement : 7 jours</li>
                                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Assurance incluse</li>
                                                <li><i class="fas fa-check text-success me-2"></i>Suivi personnalisé</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Validation et conditions -->
                        <div class="mb-4">
                            <div class="form-check mb-3">
                                <input class="form-check-input @error('conditions') is-invalid @enderror" 
                                       type="checkbox" id="conditions" name="conditions" required>
                                <label class="form-check-label" for="conditions">
                                    Je certifie que les informations fournies sont exactes et complètes.
                                    J'accepte les <a href="#" data-bs-toggle="modal" data-bs-target="#conditionsModal">conditions générales</a>.
                                    <span class="text-danger">*</span>
                                </label>
                                @error('conditions')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="consentement" name="consentement">
                                <label class="form-check-label" for="consentement">
                                    J'autorise Fubadj à partager mes informations avec Orange Bank pour le traitement de ma demande.
                                </label>
                            </div>
                        </div>

                        <!-- Boutons de navigation -->
                        <div class="d-flex justify-content-between pt-4 border-top">
                            <a href="{{ route('financement.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Annuler
                            </a>
                            <div>
                                <button type="submit" class="btn btn-success btn-lg px-5">
                                    Valider et continuer <i class="fas fa-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Informations utiles -->
            <div class="row mt-4">
                <div class="col-md-4 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-lock fa-3x text-success mb-3"></i>
                            <h5 class="card-title fw-bold">Données sécurisées</h5>
                            <p class="card-text">Vos informations sont cryptées et protégées</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-clock fa-3x text-success mb-3"></i>
                            <h5 class="card-title fw-bold">Traitement rapide</h5>
                            <p class="card-text">Réponse sous 7 jours ouvrés</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-headset fa-3x text-success mb-3"></i>
                            <h5 class="card-title fw-bold">Support client</h5>
                            <p class="card-text">Assistance au +221 33 123 45 67</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal conditions générales -->
<div class="modal fade" id="conditionsModal" tabindex="-1" aria-labelledby="conditionsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="conditionsModalLabel">
                    <i class="fas fa-file-contract me-2"></i>Conditions Générales
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6>1. Engagement du demandeur</h6>
                <p>En soumettant cette demande, vous vous engagez à :</p>
                <ul>
                    <li>Fournir des informations exactes et complètes</li>
                    <li>Utiliser les fonds uniquement pour le projet décrit</li>
                    <li>Respecter les échéances de remboursement</li>
                    <li>Informer Fubadj de tout changement de situation</li>
                </ul>
                
                <h6>2. Traitement des données</h6>
                <p>Vos données personnelles sont collectées pour :</p>
                <ul>
                    <li>Évaluer votre demande de financement</li>
                    <li>Communiquer avec vous sur l'avancement</li>
                    <li>Respecter nos obligations légales</li>
                    <li>Améliorer nos services</li>
                </ul>
                
                <h6>3. Décision de financement</h6>
                <p>La décision d'accorder un financement dépend de :</p>
                <ul>
                    <li>La viabilité de votre projet</li>
                    <li>Votre capacité de remboursement</li>
                    <li>La complétude de votre dossier</li>
                    <li>Les critères de nos partenaires financiers</li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-bs-dismiss="modal">J'ai compris</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Validation du montant
        const montantInput = document.getElementById('montant_demande');
        if (montantInput) {
            montantInput.addEventListener('change', function() {
                const montant = parseInt(this.value);
                if (montant < 500000 || montant > 10000000) {
                    this.classList.add('is-invalid');
                    alert('Le montant doit être entre 500 000 FCFA et 10 000 000 FCFA.');
                } else {
                    this.classList.remove('is-invalid');
                }
            });
        }

        // Validation des fichiers
        const fileInputs = document.querySelectorAll('input[type="file"]');
        fileInputs.forEach(input => {
            input.addEventListener('change', function() {
                const files = this.files;
                const maxSize = 5 * 1024 * 1024; // 5MB
                const allowedTypes = [
                    'application/pdf', 
                    'image/jpeg', 
                    'image/jpg', 
                    'image/png',
                    'application/msword',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
                ];
                
                for (let file of files) {
                    if (file.size > maxSize) {
                        alert(`Le fichier "${file.name}" dépasse 5MB. Veuillez choisir un fichier plus petit.`);
                        this.value = '';
                        return;
                    }
                    
                    if (!allowedTypes.includes(file.type)) {
                        alert(`Le fichier "${file.name}" n'est pas dans un format accepté (PDF, JPG, PNG, DOC, DOCX).`);
                        this.value = '';
                        return;
                    }
                }
            });
        });

        // Format du numéro de téléphone
        const telephoneInput = document.getElementById('telephone');
        if (telephoneInput) {
            telephoneInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 0) {
                    if (!value.startsWith('221')) {
                        value = '221' + value;
                    }
                    // Format: +221 XX XXX XX XX
                    let formatted = '+221 ';
                    if (value.length > 3) {
                        formatted += value.substring(3, 5) + ' ';
                    }
                    if (value.length > 5) {
                        formatted += value.substring(5, 8) + ' ';
                    }
                    if (value.length > 8) {
                        formatted += value.substring(8, 10) + ' ';
                    }
                    if (value.length > 10) {
                        formatted += value.substring(10, 12);
                    }
                    e.target.value = formatted.trim();
                }
            });
        }

        // Format du numéro CNI
        const cniInput = document.getElementById('cni');
        if (cniInput) {
            cniInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 0) {
                    // Format: X XX XXXXX X X
                    let formatted = '';
                    if (value.length > 0) {
                        formatted += value.charAt(0) + ' ';
                    }
                    if (value.length > 1) {
                        formatted += value.substring(1, 3) + ' ';
                    }
                    if (value.length > 3) {
                        formatted += value.substring(3, 8) + ' ';
                    }
                    if (value.length > 8) {
                        formatted += value.charAt(8) + ' ';
                    }
                    if (value.length > 9) {
                        formatted += value.charAt(9);
                    }
                    e.target.value = formatted.trim();
                }
            });
        }

        // Calcul d'estimation de mensualité
        function calculerMensualite() {
            const montant = parseInt(document.getElementById('montant_demande')?.value) || 0;
            const duree = parseInt(document.getElementById('duree_remboursement')?.value) || 0;
            
            if (montant > 0 && duree > 0) {
                const tauxMensuel = 0.08 / 12; // 8% annuel
                const mensualite = (montant * tauxMensuel) / (1 - Math.pow(1 + tauxMensuel, -duree));
                
                // Afficher l'estimation
                const estimationDiv = document.getElementById('estimationMensualite');
                if (!estimationDiv) {
                    const div = document.createElement('div');
                    div.id = 'estimationMensualite';
                    div.className = 'alert alert-info mt-3';
                    const montantFormate = Math.round(mensualite).toLocaleString('fr-FR');
                    div.innerHTML = `
                        <i class="fas fa-calculator me-2"></i>
                        <strong>Estimation :</strong> Mensualité d'environ <strong>${montantFormate} FCFA</strong> 
                        sur ${duree} mois avec Orange Bank (taux 8% annuel)
                    `;
                    document.querySelector('#duree_remboursement').parentNode.appendChild(div);
                } else {
                    const montantFormate = Math.round(mensualite).toLocaleString('fr-FR');
                    estimationDiv.innerHTML = `
                        <i class="fas fa-calculator me-2"></i>
                        <strong>Estimation :</strong> Mensualité d'environ <strong>${montantFormate} FCFA</strong> 
                        sur ${duree} mois avec Orange Bank (taux 8% annuel)
                    `;
                }
            }
        }

        // Écouter les changements pour le calcul
        document.getElementById('montant_demande')?.addEventListener('change', calculerMensualite);
        document.getElementById('duree_remboursement')?.addEventListener('change', calculerMensualite);

        // Validation avant soumission
        const form = document.getElementById('demandeForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                // Vérifier les champs obligatoires
                const requiredFields = form.querySelectorAll('[required]');
                let isValid = true;
                
                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        isValid = false;
                        field.classList.add('is-invalid');
                    }
                });
                
                if (!isValid) {
                    e.preventDefault();
                    alert('Veuillez remplir tous les champs obligatoires.');
                    return false;
                }
                
                // Confirmation
                if (!confirm('Êtes-vous sûr de vouloir soumettre votre demande ?')) {
                    e.preventDefault();
                    return false;
                }
                
                // Afficher un indicateur de chargement
                const submitBtn = form.querySelector('button[type="submit"]');
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Traitement en cours...';
                submitBtn.disabled = true;
            });
        }
    });
</script>
@endsection

@section('styles')
<style>
    .card.border-success {
        border-width: 2px !important;
    }
    
    .progress {
        border-radius: 10px;
    }
    
    .progress-bar {
        border-radius: 10px;
    }
    
    .form-control:readonly {
        background-color: #f8f9fa;
        border-color: #e9ecef;
    }
    
    .card.border {
        transition: all 0.3s ease;
    }
    
    .card.border:hover {
        border-color: #28a745 !important;
        box-shadow: 0 0 0 1px rgba(40, 167, 69, 0.25);
    }
</style>
@endsection