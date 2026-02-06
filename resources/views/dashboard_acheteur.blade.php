@extends('layouts.app')

@section('title', 'Tableau de bord Acheteur')

@section('content')
<div class="container-fluid">
    <!-- En-tête du dashboard -->
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="h3 text-dark mb-0">Bienvenue {{ Auth::user()->name }}</h2>
            <p class="text-muted">Découvrez les meilleurs produits locaux et gérez vos achats facilement</p>
        </div>
    </div>

    <!-- Statistiques rapides -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title">{{ $stats['total_commandes'] ?? 0 }}</h4>
                            <p class="card-text mb-0">Commandes totales</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-shopping-bag fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title">{{ $stats['commandes_en_cours'] ?? 0 }}</h4>
                            <p class="card-text mb-0">En cours</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-clock fa-2x opacity-75"></i>
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
                            <h4 class="card-title">{{ number_format($stats['montant_total'] ?? 0, 0, ',', ' ') }} FCFA</h4>
                            <p class="card-text mb-0">Total dépensé</p>
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
                            <h4 class="card-title">{{ $stats['produits_favoris'] ?? 0 }}</h4>
                            <p class="card-text mb-0">Favoris</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-heart fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions principales -->
    <div class="row mb-4">
        <div class="col-12">
            <h4 class="text-dark mb-3">Que souhaitez-vous faire ?</h4>
        </div>

        <div class="col-lg-4 col-md-6 mb-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-store fa-3x text-success"></i>
                    </div>
                    <h5 class="card-title">Consulter le catalogue</h5>
                    <p class="card-text text-muted">Découvrez tous les produits frais disponibles</p>
                    <a href="{{ route('catalogue.index') }}" class="btn btn-success">
                        <i class="fas fa-shopping-cart me-2"></i>Faire mes courses
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 mb-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-truck fa-3x text-primary"></i>
                    </div>
                    <h5 class="card-title">Suivre mes commandes</h5>
                    <p class="card-text text-muted">Vérifiez l'état de vos commandes et livraisons</p>
                    <a href="{{ route('commandes.history') }}" class="btn btn-primary">
                        <i class="fas fa-list me-2"></i>Mes commandes
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 mb-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-heart fa-3x text-danger"></i>
                    </div>
                    <h5 class="card-title">Mes favoris</h5>
                    <p class="card-text text-muted">Retrouvez vos produits préférés</p>
                    <a href="#" class="btn btn-outline-danger">
                        <i class="fas fa-heart me-2"></i>Voir mes favoris
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Commandes récentes -->
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-history text-primary me-2"></i>Mes commandes récentes
                    </h5>
                </div>
                <div class="card-body">
                    @if(isset($recentOrders) && $recentOrders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>N° Commande</th>
                                        <th>Date</th>
                                        <th>Montant</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentOrders as $order)
                                    <tr>
                                        <td><strong>#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</strong></td>
                                        <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                        <td><strong>{{ number_format($order->total, 0, ',', ' ') }} FCFA</strong></td>
                                        <td>
                                            <span class="badge bg-{{ $order->status_color }}">
                                                {{ $order->status_label }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('commandes.show', $order) }}" class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if($order->canReorder())
                                                <a href="#" class="btn btn-outline-success btn-sm">
                                                    <i class="fas fa-redo"></i>
                                                </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center mt-3">
                            <a href="{{ route('commandes.index') }}" class="btn btn-outline-primary">Voir toutes mes commandes</a>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-shopping-bag fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucune commande pour le moment</h5>
                            <p class="text-muted">Commencez par explorer notre catalogue</p>
                            <a href="{{ route('catalogue.index') }}" class="btn btn-success">
                                <i class="fas fa-store me-2"></i>Découvrir les produits
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4 mb-4">
            <!-- Produits recommandés -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-star text-warning me-2"></i>Recommandés pour vous
                    </h5>
                </div>
                <div class="card-body">
                    @if(isset($recommendedProducts) && $recommendedProducts->count() > 0)
                        @foreach($recommendedProducts as $produit)
                        <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                            @if($produit->image_url)
                                <img src="{{ $produit->image_url }}" alt="{{ $produit->nom }}" class="rounded me-3" width="50" height="50">
                            @else
                                <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                    <i class="fas fa-image text-muted"></i>
                                </div>
                            @endif
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ $produit->nom }}</h6>
                                <small class="text-muted">{{ $produit->user->name }}</small>
                                <br><strong class="text-success">{{ number_format($produit->prix, 0, ',', ' ') }} FCFA</strong>
                            </div>
                        </div>
                        @endforeach
                        <div class="text-center">
                            <a href="{{ route('catalogue.index') }}" class="btn btn-outline-primary btn-sm">Voir plus</a>
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-seedling fa-2x text-muted mb-2"></i>
                            <p class="text-muted mb-0">Découvrez nos produits</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Notifications -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bell text-primary me-2"></i>Notifications
                    </h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item px-0 border-0">
                            <div class="d-flex">
                                <div class="me-3">
                                    <i class="fas fa-truck text-success"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="mb-1 fw-bold">Livraison en cours</p>
                                    <p class="mb-1 text-muted small">Votre commande #123456 est en route</p>
                                    <small class="text-muted">Il y a 1 heure</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="list-group-item px-0 border-0">
                            <div class="d-flex">
                                <div class="me-3">
                                    <i class="fas fa-tag text-warning"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="mb-1 fw-bold">Nouvelle promotion</p>
                                    <p class="mb-1 text-muted small">-20% sur les légumes bio cette semaine</p>
                                    <small class="text-muted">Il y a 2 heures</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-center mt-3">
                        <a href="#" class="btn btn-outline-primary btn-sm">Toutes les notifications</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section découverte -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body bg-light">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="text-dark mb-2">🌱 Découvrez Jokko</h4>
                            <p class="text-muted mb-0">
                                Soutenez l'agriculture locale en achetant directement auprès des producteurs de votre région. 
                                Des produits frais, de qualité, livrés chez vous !
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            <a href="{{ route('catalogue.index') }}" class="btn btn-success btn-lg">
                                <i class="fas fa-leaf me-2"></i>Explorer le catalogue
                            </a>
                        </div>
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
    // Animation des cartes au survol
    document.querySelectorAll('.card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
            this.style.transition = 'transform 0.2s ease';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });

    // Charger les statistiques si disponibles
    if (typeof loadUserStats === 'function') {
        loadUserStats();
    }
});
</script>
@endsection