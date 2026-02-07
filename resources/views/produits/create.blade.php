@extends('layouts.app')

@section('title', 'Ajouter un produit')

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="h3 text-dark mb-0">Ajouter un nouveau produit</h2>
                    <p class="text-muted">Ajoutez un nouveau produit à votre catalogue</p>
                </div>
                <a href="{{ route('produits.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Retour à mes produits
                </a>
            </div>
        </div>
    </div>

    <!-- Messages flash -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Formulaire -->
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-plus-circle text-success me-2"></i>Informations du produit
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('produits.store') }}" method="POST" enctype="multipart/form-data" id="produitForm">
                        @csrf
                        
                        <div class="row">
                            <!-- Colonne gauche - Informations principales -->
                            <div class="col-md-8">
                                <!-- Nom du produit -->
                                <div class="mb-3">
                                    <label for="nom" class="form-label fw-bold">
                                        <i class="fas fa-tag text-primary me-1"></i>Nom du produit <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('nom') is-invalid @enderror" 
                                           id="nom" 
                                           name="nom" 
                                           value="{{ old('nom') }}" 
                                           placeholder="Ex: Tomates fraîches bio"
                                           required>
                                    @error('nom')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Donnez un nom attractif et descriptif à votre produit</div>
                                </div>

                                <!-- Description -->
                                <div class="mb-3">
                                    <label for="description" class="form-label fw-bold">
                                        <i class="fas fa-align-left text-info me-1"></i>Description <span class="text-danger">*</span>
                                    </label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" 
                                              name="description" 
                                              rows="4" 
                                              placeholder="Décrivez votre produit en détail : origine, qualité, bienfaits..."
                                              required>{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Une bonne description aide les clients à faire leur choix</div>
                                </div>

                                <!-- Catégorie -->
                                <div class="mb-3">
                                    <label for="categorie" class="form-label fw-bold">
                                        <i class="fas fa-folder text-warning me-1"></i>Catégorie <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control @error('categorie') is-invalid @enderror" 
                                            id="categorie" 
                                            name="categorie" 
                                            required>
                                        <option value="">Sélectionnez une catégorie</option>
                                        <option value="fruits" {{ old('categorie') == 'fruits' ? 'selected' : '' }}>🍎 Fruits</option>
                                        <option value="legumes" {{ old('categorie') == 'legumes' ? 'selected' : '' }}>🥕 Légumes</option>
                                        <option value="cereales" {{ old('categorie') == 'cereales' ? 'selected' : '' }}>🌾 Céréales</option>
                                        <option value="produits-laitiers" {{ old('categorie') == 'produits-laitiers' ? 'selected' : '' }}>🥛 Produits Laitiers</option>
                                        <option value="viandes" {{ old('categorie') == 'viandes' ? 'selected' : '' }}>🍖 Viandes</option>
                                        <option value="poissons" {{ old('categorie') == 'poissons' ? 'selected' : '' }}>🐟 Poissons</option>
                                        <option value="epices" {{ old('categorie') == 'epices' ? 'selected' : '' }}>🌶️ Épices</option>
                                        <option value="boissons" {{ old('categorie') == 'boissons' ? 'selected' : '' }}>🧃 Boissons</option>
                                        <option value="autres" {{ old('categorie') == 'autres' ? 'selected' : '' }}>📦 Autres</option>
                                    </select>
                                    @error('categorie')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Choisissez la catégorie qui correspond le mieux à votre produit</div>
                                </div>

                                <!-- Prix et Quantité -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="prix" class="form-label fw-bold">
                                                <i class="fas fa-coins text-warning me-1"></i>Prix unitaire <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <input type="number" 
                                                       class="form-control @error('prix') is-invalid @enderror" 
                                                       id="prix" 
                                                       name="prix" 
                                                       value="{{ old('prix') }}" 
                                                       placeholder="0"
                                                       min="0"
                                                       step="1"
                                                       required>
                                                <span class="input-group-text">FCFA</span>
                                                @error('prix')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="form-text">Prix de vente par unité</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="quantite" class="form-label fw-bold">
                                                <i class="fas fa-boxes text-success me-1"></i>Quantité disponible <span class="text-danger">*</span>
                                            </label>
                                            <input type="number" 
                                                   class="form-control @error('quantite') is-invalid @enderror" 
                                                   id="quantite" 
                                                   name="quantite" 
                                                   value="{{ old('quantite') }}" 
                                                   placeholder="0"
                                                   min="0"
                                                   required>
                                            @error('quantite')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">Nombre d'unités en stock</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Colonne droite - Image -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="image" class="form-label fw-bold">
                                        <i class="fas fa-camera text-secondary me-1"></i>Photo du produit
                                    </label>
                                    
                                    <!-- Zone de prévisualisation -->
                                    <div class="border rounded p-3 text-center mb-3" id="imagePreviewZone" style="min-height: 200px; background-color: #f8f9fa;">
                                        <div id="imagePreview" class="d-none">
                                            <img id="previewImg" src="" alt="Aperçu" class="img-fluid rounded" style="max-height: 180px;">
                                            <div class="mt-2">
                                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeImage()">
                                                    <i class="fas fa-trash me-1"></i>Supprimer
                                                </button>
                                            </div>
                                        </div>
                                        <div id="imagePlaceholder">
                                            <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                                            <p class="text-muted mb-2">Cliquez pour ajouter une photo</p>
                                            <small class="text-muted">JPG, PNG, WebP max 5MB</small>
                                        </div>
                                    </div>
                                    
                                    <input type="file" 
                                           class="form-control @error('image') is-invalid @enderror" 
                                           id="image" 
                                           name="image" 
                                           accept="image/jpeg,image/png,image/webp"
                                           onchange="previewImage(this)">
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Une belle photo augmente vos chances de vente</div>
                                </div>

                                <!-- Info résumé -->
                                <div class="card border-light bg-light">
                                    <div class="card-body p-3">
                                        <h6 class="card-title"><i class="fas fa-info-circle text-info me-1"></i>Conseils</h6>
                                        <ul class="list-unstyled mb-0 small text-muted">
                                            <li><i class="fas fa-check text-success me-1"></i>Utilisez des mots-clés dans le nom</li>
                                            <li><i class="fas fa-check text-success me-1"></i>Soyez précis sur la qualité</li>
                                            <li><i class="fas fa-check text-success me-1"></i>Choisissez la bonne catégorie</li>
                                            <li><i class="fas fa-check text-success me-1"></i>Ajoutez une photo attractive</li>
                                            <li><i class="fas fa-check text-success me-1"></i>Indiquez l'origine du produit</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('produits.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-times me-2"></i>Annuler
                                    </a>
                                    <div>
                                        <button type="reset" class="btn btn-outline-warning me-2">
                                            <i class="fas fa-undo me-2"></i>Réinitialiser
                                        </button>
                                        <button type="submit" class="btn btn-success" id="submitBtn">
                                            <i class="fas fa-save me-2"></i>Enregistrer le produit
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Prévisualisation de l'image
function previewImage(input) {
    const file = input.files[0];
    const preview = document.getElementById('imagePreview');
    const placeholder = document.getElementById('imagePlaceholder');
    const previewImg = document.getElementById('previewImg');
    
    if (file) {
        // Vérifier la taille (2MB max)
        if (file.size > 2 * 1024 * 1024) {
            alert('La taille de l\'image ne doit pas dépasser 2MB');
            input.value = '';
            return;
        }
        
        // Vérifier le type
        if (!['image/jpeg', 'image/png', 'image/webp'].includes(file.type)) {
            alert('Format d\'image non supporté. Utilisez JPG, PNG ou WebP');
            input.value = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.classList.remove('d-none');
            placeholder.style.display = 'none';
        };
        reader.readAsDataURL(file);
    }
}

// Supprimer l'image
function removeImage() {
    const input = document.getElementById('image');
    const preview = document.getElementById('imagePreview');
    const placeholder = document.getElementById('imagePlaceholder');
    
    input.value = '';
    preview.classList.add('d-none');
    placeholder.style.display = 'block';
}

// Validation du formulaire
document.getElementById('produitForm').addEventListener('submit', function(e) {
    const submitBtn = document.getElementById('submitBtn');
    
    // Désactiver le bouton pour éviter les double-clics
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Enregistrement...';
    
    // Validation des champs
    const nom = document.getElementById('nom').value.trim();
    const description = document.getElementById('description').value.trim();
    const categorie = document.getElementById('categorie').value;
    const prix = parseFloat(document.getElementById('prix').value);
    const quantite = parseInt(document.getElementById('quantite').value);
    
    if (!nom || !description || !categorie || isNaN(prix) || isNaN(quantite) || prix < 0 || quantite < 0) {
        e.preventDefault();
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-save me-2"></i>Enregistrer le produit';
        alert('Veuillez remplir tous les champs obligatoires avec des valeurs valides');
        return;
    }
});

// Formatage automatique du prix
document.getElementById('prix').addEventListener('input', function(e) {
    let value = e.target.value;
    // Supprimer les espaces et caractères non numériques sauf les points
    value = value.replace(/[^\d.]/g, '');
    e.target.value = value;
});

// Auto-resize textarea
document.getElementById('description').addEventListener('input', function(e) {
    e.target.style.height = 'auto';
    e.target.style.height = (e.target.scrollHeight) + 'px';
});

// Click sur la zone d'image pour ouvrir le sélecteur
document.getElementById('imagePlaceholder').addEventListener('click', function() {
    document.getElementById('image').click();
});

// Drag & Drop pour l'image
const dropZone = document.getElementById('imagePreviewZone');

dropZone.addEventListener('dragover', function(e) {
    e.preventDefault();
    dropZone.classList.add('border-primary');
});

dropZone.addEventListener('dragleave', function(e) {
    e.preventDefault();
    dropZone.classList.remove('border-primary');
});

dropZone.addEventListener('drop', function(e) {
    e.preventDefault();
    dropZone.classList.remove('border-primary');
    
    const files = e.dataTransfer.files;
    if (files.length > 0) {
        const input = document.getElementById('image');
        input.files = files;
        previewImage(input);
    }
});

// Catégories avec recherche améliorée
document.addEventListener('DOMContentLoaded', function() {
    const categorieSelect = document.getElementById('categorie');
    
    // Ajouter une recherche dans les catégories
    const searchInput = document.createElement('input');
    searchInput.type = 'text';
    searchInput.className = 'form-control form-control-sm mb-2';
    searchInput.placeholder = 'Rechercher une catégorie...';
    
    // Insérer la recherche au-dessus du select
    categorieSelect.parentNode.insertBefore(searchInput, categorieSelect);
    
    searchInput.addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const options = categorieSelect.options;
        
        for (let i = 0; i < options.length; i++) {
            const option = options[i];
            const text = option.text.toLowerCase();
            if (text.includes(searchTerm)) {
                option.style.display = '';
            } else {
                option.style.display = 'none';
            }
        }
    });
});
</script>
@endsection