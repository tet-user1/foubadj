@extends('layouts.app')

@section('title', 'Détails du Produit - ' . $produit->nom)

@section('styles')
<style>
    .product-image-preview {
        max-width: 100%;
        height: 400px;
        object-fit: cover;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .product-info-card {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
    }

    .info-label {
        font-weight: 600;
        color: #6c757d;
        font-size: 0.9rem;
        margin-bottom: 5px;
    }

    .info-value {
        font-size: 1.1rem;
        color: #212529;
        margin-bottom: 15px;
    }

    .status-badge {
        font-size: 1rem;
        padding: 8px 16px;
    }

    .edit-section {
        background: white;
        border-radius: 10px;
        padding: 30px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .btn-action {
        min-width: 120px;
    }

    .product-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 15px;
        margin-top: 20px;
    }

    .stat-card {
        background: white;
        border-radius: 8px;
        padding: 15px;
        text-align: center;
        border: 2px solid #e9ecef;
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        border-color: #2c7873;
        transform: translateY(-3px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .stat-icon {
        font-size: 2rem;
        margin-bottom: 10px;
    }

    .stat-value {
        font-size: 1.5rem;
        font-weight: bold;
        color: #2c7873;
    }

    .stat-label {
        font-size: 0.85rem;
        color: #6c757d;
        text-transform: uppercase;
    }

    .image-upload-zone {
        border: 2px dashed #dee2e6;
        border-radius: 10px;
        padding: 30px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .image-upload-zone:hover {
        border-color: #2c7873;
        background-color: #f8f9fa;
    }

    .image-upload-zone.dragover {
        border-color: #2c7873;
        background-color: #e3f2fd;
    }
</style>
@endsection

@section('content')
<div class="container py-4">
    <!-- En-tête avec breadcrumb -->
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard.producteur') }}">
                            <i class="fas fa-home me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('mes.produits') }}">Mes Produits</a>
                    </li>
                    <li class="breadcrumb-item active">{{ $produit->nom }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Alertes -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Onglets -->
    <ul class="nav nav-tabs mb-4" id="productTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="view-tab" data-bs-toggle="tab" data-bs-target="#view" 
                    type="button" role="tab">
                <i class="fas fa-eye me-2"></i>Aperçu
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="edit-tab" data-bs-toggle="tab" data-bs-target="#edit" 
                    type="button" role="tab">
                <i class="fas fa-edit me-2"></i>Modifier
            </button>
        </li>
    </ul>

    <div class="tab-content" id="productTabsContent">
        <!-- ========================================
             ONGLET APERÇU
        ======================================== -->
        <div class="tab-pane fade show active" id="view" role="tabpanel">
            <div class="row">
                <!-- Colonne Image -->
                <div class="col-lg-5 mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-0">
                            @if($produit->image_url)
                                <img src="{{ $produit->image_url }}" 
                                     alt="{{ $produit->nom }}" 
                                     class="product-image-preview w-100">
                            @else
                                <div class="d-flex align-items-center justify-content-center bg-light" 
                                     style="height: 400px;">
                                    <div class="text-center text-muted">
                                        <i class="fas fa-image fa-4x mb-3"></i>
                                        <p>Aucune image</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Statistiques -->
                    <div class="product-stats">
                        <div class="stat-card">
                            <div class="stat-icon text-primary">
                                <i class="fas fa-boxes"></i>
                            </div>
                            <div class="stat-value">{{ $produit->quantite }}</div>
                            <div class="stat-label">En Stock</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon text-success">
                                <i class="fas fa-tag"></i>
                            </div>
                            <div class="stat-value">{{ number_format($produit->prix, 0, ',', ' ') }}</div>
                            <div class="stat-label">FCFA</div>
                        </div>
                    </div>
                </div>

                <!-- Colonne Informations -->
                <div class="col-lg-7">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <!-- En-tête -->
                            <div class="d-flex justify-content-between align-items-start mb-4">
                                <div>
                                    <h2 class="mb-2">{{ $produit->nom }}</h2>
                                    @if($produit->quantite > 0)
                                        <span class="badge status-badge bg-success">
                                            <i class="fas fa-check-circle me-1"></i>En stock
                                        </span>
                                    @else
                                        <span class="badge status-badge bg-danger">
                                            <i class="fas fa-times-circle me-1"></i>Rupture de stock
                                        </span>
                                    @endif
                                </div>
                                <div class="text-end">
                                    <h3 class="text-success mb-0">
                                        {{ number_format($produit->prix, 0, ',', ' ') }} FCFA
                                    </h3>
                                    <small class="text-muted">l'unité</small>
                                </div>
                            </div>

                            <!-- Informations détaillées -->
                            <div class="product-info-card">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="info-label">
                                            <i class="fas fa-tag me-1"></i>Catégorie
                                        </div>
                                        <div class="info-value">
                                            {{ ucfirst(str_replace('-', ' ', $produit->categorie)) }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-label">
                                            <i class="fas fa-boxes me-1"></i>Quantité disponible
                                        </div>
                                        <div class="info-value">{{ $produit->quantite }} unités</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-label">
                                            <i class="fas fa-calendar-plus me-1"></i>Date de création
                                        </div>
                                        <div class="info-value">
                                            {{ $produit->created_at->format('d/m/Y à H:i') }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-label">
                                            <i class="fas fa-calendar-check me-1"></i>Dernière modification
                                        </div>
                                        <div class="info-value">
                                            {{ $produit->updated_at->format('d/m/Y à H:i') }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="mt-4">
                                <h5 class="mb-3">
                                    <i class="fas fa-align-left me-2 text-muted"></i>Description
                                </h5>
                                <p class="text-muted" style="line-height: 1.8; text-align: justify;">
                                    {{ $produit->description ?: 'Aucune description disponible.' }}
                                </p>
                            </div>

                            <!-- Actions -->
                            <div class="mt-4 d-flex gap-2 flex-wrap">
                                <button class="btn btn-primary btn-action" 
                                        onclick="document.getElementById('edit-tab').click()">
                                    <i class="fas fa-edit me-2"></i>Modifier
                                </button>
                                <a href="{{ route('catalogue.show', $produit->id) }}" 
                                   class="btn btn-outline-info btn-action" 
                                   target="_blank">
                                    <i class="fas fa-external-link-alt me-2"></i>Voir Public
                                </a>
                                <button class="btn btn-outline-danger btn-action" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#deleteModal">
                                    <i class="fas fa-trash me-2"></i>Supprimer
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ========================================
             ONGLET MODIFICATION
        ======================================== -->
        <div class="tab-pane fade" id="edit" role="tabpanel">
            <div class="edit-section">
                <h3 class="mb-4">
                    <i class="fas fa-edit me-2 text-primary"></i>Modifier le produit
                </h3>

                <form action="{{ route('produits.update', $produit->id) }}" 
                      method="POST" 
                      enctype="multipart/form-data"
                      id="editProductForm">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <!-- Nom du produit -->
                        <div class="col-md-6 mb-3">
                            <label for="nom" class="form-label fw-bold">
                                <i class="fas fa-box me-1"></i>Nom du produit *
                            </label>
                            <input type="text" 
                                   class="form-control @error('nom') is-invalid @enderror" 
                                   id="nom" 
                                   name="nom" 
                                   value="{{ old('nom', $produit->nom) }}" 
                                   required>
                            @error('nom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Catégorie -->
                        <div class="col-md-6 mb-3">
                            <label for="categorie" class="form-label fw-bold">
                                <i class="fas fa-tag me-1"></i>Catégorie *
                            </label>
                            <select class="form-select @error('categorie') is-invalid @enderror" 
                                    id="categorie" 
                                    name="categorie" 
                                    required>
                                <option value="">Sélectionner une catégorie</option>
                                <option value="fruits" {{ old('categorie', $produit->categorie) == 'fruits' ? 'selected' : '' }}>
                                    🍎 Fruits
                                </option>
                                <option value="legumes" {{ old('categorie', $produit->categorie) == 'legumes' ? 'selected' : '' }}>
                                    🥕 Légumes
                                </option>
                                <option value="cereales" {{ old('categorie', $produit->categorie) == 'cereales' ? 'selected' : '' }}>
                                    🌾 Céréales
                                </option>
                                <option value="produits-laitiers" {{ old('categorie', $produit->categorie) == 'produits-laitiers' ? 'selected' : '' }}>
                                    🥛 Produits Laitiers
                                </option>
                                <option value="viandes" {{ old('categorie', $produit->categorie) == 'viandes' ? 'selected' : '' }}>
                                    🍖 Viandes
                                </option>
                                <option value="poissons" {{ old('categorie', $produit->categorie) == 'poissons' ? 'selected' : '' }}>
                                    🐟 Poissons
                                </option>
                                <option value="epices" {{ old('categorie', $produit->categorie) == 'epices' ? 'selected' : '' }}>
                                    🌶️ Épices
                                </option>
                                <option value="boissons" {{ old('categorie', $produit->categorie) == 'boissons' ? 'selected' : '' }}>
                                    🧃 Boissons
                                </option>
                                <option value="autres" {{ old('categorie', $produit->categorie) == 'autres' ? 'selected' : '' }}>
                                    📦 Autres
                                </option>
                            </select>
                            @error('categorie')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Prix -->
                        <div class="col-md-6 mb-3">
                            <label for="prix" class="form-label fw-bold">
                                <i class="fas fa-money-bill-wave me-1"></i>Prix unitaire (FCFA) *
                            </label>
                            <input type="number" 
                                   class="form-control @error('prix') is-invalid @enderror" 
                                   id="prix" 
                                   name="prix" 
                                   value="{{ old('prix', $produit->prix) }}" 
                                   min="0" 
                                   step="1" 
                                   required>
                            @error('prix')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Quantité -->
                        <div class="col-md-6 mb-3">
                            <label for="quantite" class="form-label fw-bold">
                                <i class="fas fa-boxes me-1"></i>Quantité en stock *
                            </label>
                            <input type="number" 
                                   class="form-control @error('quantite') is-invalid @enderror" 
                                   id="quantite" 
                                   name="quantite" 
                                   value="{{ old('quantite', $produit->quantite) }}" 
                                   min="0" 
                                   required>
                            @error('quantite')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="col-12 mb-3">
                            <label for="description" class="form-label fw-bold">
                                <i class="fas fa-align-left me-1"></i>Description
                            </label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="4" 
                                      placeholder="Décrivez votre produit...">{{ old('description', $produit->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Image -->
                        <div class="col-12 mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-image me-1"></i>Image du produit
                            </label>
                            
                            <!-- Aperçu de l'image actuelle -->
                            @if($produit->image_url)
                                <div class="mb-3">
                                    <img src="{{ $produit->image_url }}" 
                                         alt="Image actuelle" 
                                         class="img-thumbnail" 
                                         style="max-height: 200px;"
                                         id="currentImage">
                                </div>
                            @endif

                            <!-- Zone d'upload -->
                            <div class="image-upload-zone" id="uploadZone">
                                <input type="file" 
                                       class="form-control d-none @error('image') is-invalid @enderror" 
                                       id="image" 
                                       name="image" 
                                       accept="image/*"
                                       onchange="previewImage(event)">
                                <label for="image" class="w-100 m-0" style="cursor: pointer;">
                                    <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3 d-block"></i>
                                    <p class="mb-0">
                                        <strong>Cliquez pour choisir</strong> ou glissez une image ici
                                    </p>
                                    <small class="text-muted">PNG, JPG, JPEG (max. 2MB)</small>
                                </label>
                            </div>
                            
                            <!-- Aperçu de la nouvelle image -->
                            <div id="imagePreview" class="mt-3" style="display: none;">
                                <img id="previewImg" class="img-thumbnail" style="max-height: 200px;">
                            </div>

                            @error('image')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Boutons d'action -->
                    <div class="d-flex gap-2 justify-content-end mt-4">
                        <button type="button" 
                                class="btn btn-secondary btn-action" 
                                onclick="document.getElementById('view-tab').click()">
                            <i class="fas fa-times me-2"></i>Annuler
                        </button>
                        <button type="submit" class="btn btn-success btn-action">
                            <i class="fas fa-save me-2"></i>Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation de suppression -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle me-2"></i>Confirmer la suppression
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0">Êtes-vous sûr de vouloir supprimer ce produit ?</p>
                <p class="text-danger fw-bold">Cette action est irréversible.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form action="{{ route('produits.destroy', $produit->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-2"></i>Supprimer
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Prévisualisation de l'image
function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('imagePreview').style.display = 'block';
        }
        reader.readAsDataURL(file);
    }
}

// Drag & Drop
const uploadZone = document.getElementById('uploadZone');
const fileInput = document.getElementById('image');

['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
    uploadZone.addEventListener(eventName, preventDefaults, false);
});

function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
}

['dragenter', 'dragover'].forEach(eventName => {
    uploadZone.addEventListener(eventName, () => {
        uploadZone.classList.add('dragover');
    });
});

['dragleave', 'drop'].forEach(eventName => {
    uploadZone.addEventListener(eventName, () => {
        uploadZone.classList.remove('dragover');
    });
});

uploadZone.addEventListener('drop', (e) => {
    const dt = e.dataTransfer;
    const files = dt.files;
    fileInput.files = files;
    
    if (files.length > 0) {
        const event = new Event('change', { bubbles: true });
        fileInput.dispatchEvent(event);
    }
});
</script>
@endsection