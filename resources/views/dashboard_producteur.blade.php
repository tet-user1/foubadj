@extends('layouts.app')

@section('title', 'Tableau de bord Producteur')

@section('content')
<div class="container-fluid">
    <!-- En-tête du dashboard -->
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="h3 text-dark mb-0">Tableau de bord Producteur</h2>
            <p class="text-muted">Bienvenue {{ Auth::user()->name }}, gérez votre activité depuis ce tableau de bord</p>
        </div>
    </div>

    <!-- Statistiques rapides -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title" id="total-produits">{{ $stats['total_produits'] ?? 0 }}</h4>
                            <p class="card-text mb-0">Produits totaux</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-box fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title" id="produits-stock">{{ $stats['produits_en_stock'] ?? 0 }}</h4>
                            <p class="card-text mb-0">En stock</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-check-circle fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title" id="valeur-stock">{{ number_format($stats['valeur_totale_stock'] ?? 0, 0, ',', ' ') }} FCFA</h4>
                            <p class="card-text mb-0">Valeur du stock</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-coins fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title">{{ $stats['produits_stock_faible'] ?? 0 }}</h4>
                            <p class="card-text mb-0">Alertes stock</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-exclamation-triangle fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions principales -->
    <div class="row mb-4">
        <div class="col-12">
            <h4 class="text-dark mb-3">Actions principales</h4>
        </div>

        <div class="col-lg-4 col-md-6 mb-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-plus-circle fa-3x text-success"></i>
                    </div>
                    <h5 class="card-title">Ajouter un produit</h5>
                    <p class="card-text text-muted">Publiez un nouveau produit dans votre catalogue</p>
                    <a href="{{ route('produits.create') }}" class="btn btn-success">
                        <i class="fas fa-plus me-2"></i>Nouveau produit
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 mb-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-seedling fa-3x text-primary"></i>
                    </div>
                    <h5 class="card-title">Mes produits</h5>
                    <p class="card-text text-muted">Gérez, modifiez et organisez votre catalogue</p>
                    <a href="{{ route('produits.index') }}" class="btn btn-primary">
                        <i class="fas fa-list me-2"></i>Voir mes produits
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 mb-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-chart-line fa-3x text-info"></i>
                    </div>
                    <h5 class="card-title">Statistiques</h5>
                    <p class="card-text text-muted">Analysez vos performances de vente</p>
                    <a href="{{ route('statistics') }}" class="btn btn-info">
                        <i class="fas fa-chart-bar me-2"></i>Voir statistiques
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Sections du dashboard -->
    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-box text-primary me-2"></i>Mes produits récents
                    </h5>
                </div>
                <div class="card-body">
                    @if(isset($recentProducts) && $recentProducts->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Produit</th>
                                        <th>Prix</th>
                                        <th>Stock</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentProducts as $produit)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($produit->image_url)
                                                    <img src="{{ $produit->image_url }}" alt="{{ $produit->nom }}" class="rounded me-2" width="40" height="40">
                                                @else
                                                    <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                        <i class="fas fa-image text-muted"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <strong>{{ $produit->nom }}</strong>
                                                    <br><small class="text-muted">{{ Str::limit($produit->description, 30) }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><strong>{{ number_format($produit->prix, 0, ',', ' ') }} FCFA</strong></td>
                                        <td>
                                            <span class="badge {{ $produit->quantite > 10 ? 'bg-success' : ($produit->quantite > 0 ? 'bg-warning' : 'bg-danger') }}">
                                                {{ $produit->quantite }} unités
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge {{ $produit->quantite > 10 ? 'bg-success' : ($produit->quantite > 0 ? 'bg-warning' : 'bg-danger') }}">
                                                @if($produit->quantite == 0)
                                                    Rupture
                                                @elseif($produit->quantite <= 10)
                                                    Stock faible
                                                @else
                                                    Disponible
                                                @endif
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('produits.edit', $produit) }}" class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="{{ route('produits.show', $produit) }}" class="btn btn-outline-info btn-sm">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center mt-3">
                            <a href="{{ route('produits.index') }}" class="btn btn-outline-primary">Voir tous mes produits</a>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-seedling fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucun produit pour le moment</h5>
                            <p class="text-muted">Commencez par ajouter votre premier produit</p>
                            <a href="{{ route('produits.create') }}" class="btn btn-success">
                                <i class="fas fa-plus me-2"></i>Ajouter un produit
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bell text-warning me-2"></i>Alertes de stock
                    </h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @if(isset($alertesStock) && $alertesStock->count() > 0)
                            @foreach($alertesStock->take(5) as $alerte)
                                <div class="list-group-item px-0 border-0">
                                    <div class="d-flex">
                                        <div class="me-3">
                                            @if($alerte->quantite == 0)
                                                <i class="fas fa-times-circle text-danger"></i>
                                            @else
                                                <i class="fas fa-exclamation-triangle text-warning"></i>
                                            @endif
                                        </div>
                                        <div class="flex-grow-1">
                                            <p class="mb-1 fw-bold">{{ $alerte->nom }}</p>
                                            <p class="mb-1 text-muted small">
                                                @if($alerte->quantite == 0)
                                                    Produit en rupture de stock
                                                @else
                                                    Il ne reste que {{ $alerte->quantite }} unité{{ $alerte->quantite > 1 ? 's' : '' }}
                                                @endif
                                            </p>
                                            <small class="text-muted">{{ $alerte->updated_at->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            
                            @if($alertesStock->count() > 5)
                                <div class="text-center mt-2">
                                    <small class="text-muted">
                                        Et {{ $alertesStock->count() - 5 }} autre{{ $alertesStock->count() - 5 > 1 ? 's' : '' }} alerte{{ $alertesStock->count() - 5 > 1 ? 's' : '' }}
                                    </small>
                                </div>
                            @endif
                        @else
                            <div class="text-center py-3">
                                <i class="fas fa-check-circle text-success fa-2x mb-2"></i>
                                <p class="mb-1 fw-bold text-success">Aucune alerte de stock</p>
                                <p class="mb-0 text-muted small">Tous vos produits ont un stock suffisant</p>
                            </div>
                        @endif
                    </div>
                    
                    <div class="text-center mt-3">
                        <a href="{{ route('produits.index') }}" class="btn btn-outline-primary btn-sm">Gérer le stock</a>
                    </div>
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-white border-bottom">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bolt text-primary me-2"></i>Actions rapides
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="#" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-clipboard-list me-2"></i>Gérer les commandes
                        </a>
                        <a href="#" class="btn btn-outline-info btn-sm">
                            <i class="fas fa-truck me-2"></i>Suivi des livraisons
                        </a>
                        <a href="#" class="btn btn-outline-success btn-sm">
                            <i class="fas fa-star me-2"></i>Avis clients
                        </a>
                        <a href="#" class="btn btn-outline-warning btn-sm">
                            <i class="fas fa-cog me-2"></i>Paramètres boutique
                        </a>
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
    // Charger les statistiques via AJAX si nécessaire
    fetch('/api/produits-stats')
        .then(response => response.json())
        .then(data => {
            document.getElementById('total-produits').textContent = data.total_produits || 0;
            document.getElementById('produits-stock').textContent = data.produits_en_stock || 0;
            document.getElementById('valeur-stock').textContent = new Intl.NumberFormat('fr-FR').format(data.valeur_totale_stock || 0) + ' FCFA';
        })
        .catch(error => {
            console.error('Erreur lors du chargement des statistiques:', error);
        });
});
</script>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Vérifier si on a des alertes stock passées depuis le contrôleur
    @if(isset($alertesStock) && $alertesStock->count() > 0)
        let alertes = @json($alertesStock);

        alertes.forEach(alerte => {
            let toastHtml = `
                <div class="toast align-items-center text-white ${alerte.statut === 'Rupture' ? 'bg-danger' : 'bg-warning'} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">
                            <strong>${alerte.nom}</strong> - ${alerte.statut}
                            (${alerte.stock} unité${alerte.stock > 1 ? 's' : ''} restant${alerte.stock > 1 ? 's' : ''})
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Fermer"></button>
                    </div>
                </div>
            `;

            // Ajouter le toast au conteneur
            document.getElementById('toast-container').insertAdjacentHTML('beforeend', toastHtml);
        });

        // Activer tous les toasts
        let toastElList = [].slice.call(document.querySelectorAll('.toast'))
        toastElList.map(function (toastEl) {
            let toast = new bootstrap.Toast(toastEl, { delay: 5000 });
            toast.show();
        });
    @endif
});
</script>
@endsection

@endsection