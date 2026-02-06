@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- En-tête avec statistiques globales -->
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="mb-3">📊 Tableau de bord administrateur</h1>
        </div>
    </div>

    <!-- Cartes de statistiques -->
    <div class="row g-3 mb-4">
        <!-- Total utilisateurs -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Total Utilisateurs</p>
                            <h3 class="mb-0">{{ $stats['total'] ?? 0 }}</h3>
                            <small class="text-success">
                                <i class="bi bi-arrow-up"></i> +{{ $stats['new_this_month'] ?? 0 }} ce mois
                            </small>
                        </div>
                        <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-people-fill text-primary" viewBox="0 0 16 16">
                                <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5.784 6A2.238 2.238 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.325 6.325 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Acheteurs -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Acheteurs</p>
                            <h3 class="mb-0">{{ $stats['acheteurs'] ?? 0 }}</h3>
                            <small class="text-muted">
                                {{ $stats['acheteurs_percent'] ?? 0 }}% du total
                            </small>
                        </div>
                        <div class="bg-info bg-opacity-10 rounded-circle p-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-cart-fill text-info" viewBox="0 0 16 16">
                                <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5M5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4m-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2m7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Producteurs -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Producteurs</p>
                            <h3 class="mb-0">{{ $stats['producteurs'] ?? 0 }}</h3>
                            <small class="text-muted">
                                {{ $stats['producteurs_percent'] ?? 0 }}% du total
                            </small>
                        </div>
                        <div class="bg-success bg-opacity-10 rounded-circle p-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-shop text-success" viewBox="0 0 16 16">
                                <path d="M2.97 1.35A1 1 0 0 1 3.73 1h8.54a1 1 0 0 1 .76.35l2.609 3.044A1.5 1.5 0 0 1 16 5.37v.255a2.375 2.375 0 0 1-4.25 1.458A2.371 2.371 0 0 1 9.875 8 2.37 2.37 0 0 1 8 7.083 2.37 2.37 0 0 1 6.125 8a2.37 2.37 0 0 1-1.875-.917A2.375 2.375 0 0 1 0 5.625V5.37a1.5 1.5 0 0 1 .361-.976l2.61-3.045zm1.78 4.275a1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0 1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0 1.375 1.375 0 1 0 2.75 0V5.37a.5.5 0 0 0-.12-.325L12.27 2H3.73L1.12 5.045A.5.5 0 0 0 1 5.37v.255a1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0M1.5 8.5A.5.5 0 0 1 2 9v6h1v-5a1 1 0 0 1 1-1h3a1 1 0 0 1 1 1v5h6V9a.5.5 0 0 1 1 0v6h.5a.5.5 0 0 1 0 1H.5a.5.5 0 0 1 0-1H1V9a.5.5 0 0 1 .5-.5M4 15h3v-5H4zm5-5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1zm3 0h-2v3h2z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Email non vérifiés -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Non Vérifiés</p>
                            <h3 class="mb-0">{{ $stats['not_verified'] ?? 0 }}</h3>
                            <small class="text-warning">
                                Nécessite attention
                            </small>
                        </div>
                        <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-envelope-exclamation text-warning" viewBox="0 0 16 16">
                                <path d="M2 2a2 2 0 0 0-2 2v8.01A2 2 0 0 0 2 14h5.5a.5.5 0 0 0 0-1H2a1 1 0 0 1-.966-.741l5.64-3.471L8 9.583l7-4.2V8.5a.5.5 0 0 0 1 0V4a2 2 0 0 0-2-2zm3.708 6.208L1 11.105V5.383zM1 4.217V4a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v.217l-7 4.2z"/>
                                <path d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m.5-5v1.5a.5.5 0 0 1-1 0V11a.5.5 0 0 1 1 0m0 3a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques supplémentaires -->
    <div class="row g-3 mb-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0">📈 Activité récente</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="border-end">
                                <h4 class="text-primary mb-1">{{ $stats['inscriptions_today'] ?? 0 }}</h4>
                                <small class="text-muted">Inscriptions aujourd'hui</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="border-end">
                                <h4 class="text-success mb-1">{{ $stats['inscriptions_week'] ?? 0 }}</h4>
                                <small class="text-muted">Cette semaine</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <h4 class="text-info mb-1">{{ $stats['inscriptions_month'] ?? 0 }}</h4>
                            <small class="text-muted">Ce mois</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0">⚡ Actions rapides</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.users.index', ['email_verified' => 'not_verified']) }}" class="btn btn-outline-warning btn-sm">
                            <i class="bi bi-envelope-exclamation"></i> Emails non vérifiés
                        </a>
                        <a href="{{ route('admin.users.index', ['role' => 'producteur']) }}" class="btn btn-outline-success btn-sm">
                            <i class="bi bi-shop"></i> Voir tous les producteurs
                        </a>
                        <a href="{{ route('admin.users.index', ['role' => 'acheteur']) }}" class="btn btn-outline-info btn-sm">
                            <i class="bi bi-cart"></i> Voir tous les acheteurs
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-0 py-3">
            <h5 class="mb-0">🔍 Filtres de recherche</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.users.index') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="search" class="form-label small text-muted">Rechercher</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="{{ request('search') }}" placeholder="Nom, email, téléphone...">
                    </div>
                    <div class="col-md-2">
                        <label for="role" class="form-label small text-muted">Rôle</label>
                        <select class="form-select" id="role" name="role">
                            <option value="">Tous les rôles</option>
                            <option value="acheteur" {{ request('role') === 'acheteur' ? 'selected' : '' }}>Acheteur</option>
                            <option value="producteur" {{ request('role') === 'producteur' ? 'selected' : '' }}>Producteur</option>
                            <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="email_verified" class="form-label small text-muted">Email</label>
                        <select class="form-select" id="email_verified" name="email_verified">
                            <option value="">Tous</option>
                            <option value="verified" {{ request('email_verified') === 'verified' ? 'selected' : '' }}>Vérifié</option>
                            <option value="not_verified" {{ request('email_verified') === 'not_verified' ? 'selected' : '' }}>Non vérifié</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="status" class="form-label small text-muted">Statut</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">Tous</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Actif</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactif</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary flex-fill">
                            <i class="bi bi-funnel"></i> Filtrer
                        </button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Messages flash -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="bi bi-info-circle-fill"></i> {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Table des utilisateurs -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">👥 Liste des utilisateurs ({{ $users->total() }})</h5>
                <span class="badge bg-secondary">Page {{ $users->currentPage() }} / {{ $users->lastPage() }}</span>
            </div>
        </div>
        <div class="card-body p-0">
            @if($users->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="px-3">ID</th>
                                <th>Utilisateur</th>
                                <th>Contact</th>
                                <th>Rôle</th>
                                <th>Vérification</th>
                                <th>Statut</th>
                                <th>Activité</th>
                                <th>Inscription</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td class="px-3">
                                        <span class="badge bg-light text-dark">#{{ $user->id }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px; font-weight: bold;">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <strong>{{ $user->name }}</strong>
                                                @if($user->id === auth()->id())
                                                    <span class="badge bg-warning text-dark ms-1">Vous</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="small">
                                            <div><i class="bi bi-envelope text-muted"></i> {{ $user->email }}</div>
                                            @if($user->telephone)
                                                <div><i class="bi bi-telephone text-muted"></i> {{ $user->telephone }}</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'producteur' ? 'success' : 'primary') }}">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($user->email_verified_at)
                                            <span class="badge bg-success">
                                                <i class="bi bi-check-circle"></i> Vérifié
                                            </span>
                                            <div class="small text-muted">{{ $user->email_verified_at->format('d/m/Y') }}</div>
                                        @else
                                            <span class="badge bg-warning">
                                                <i class="bi bi-exclamation-circle"></i> Non vérifié
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($user->is_active ?? true)
                                            <span class="badge bg-success">
                                                <i class="bi bi-check-circle-fill"></i> Actif
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                <i class="bi bi-x-circle-fill"></i> Inactif
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($user->role === 'producteur')
                                            <div class="small">
                                                <span class="badge bg-light text-dark">{{ $user->produits_count ?? 0 }} produits</span>
                                            </div>
                                        @endif
                                        @if($user->role === 'acheteur')
                                            <div class="small">
                                                <span class="badge bg-light text-dark">{{ $user->commandes_count ?? 0 }} commandes</span>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="small">{{ $user->created_at->format('d/m/Y') }}</div>
                                        <div class="small text-muted">{{ $user->created_at->diffForHumans() }}</div>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.users.show', $user) }}" 
                                               class="btn btn-sm btn-outline-primary" 
                                               title="Voir détails"
                                               data-bs-toggle="tooltip">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            
                                            <!-- Dropdown actions -->
                                            <div class="btn-group" role="group">
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-secondary dropdown-toggle" 
                                                        data-bs-toggle="dropdown"
                                                        title="Plus d'actions">
                                                    <i class="bi bi-three-dots-vertical"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><h6 class="dropdown-header">Changer le rôle</h6></li>
                                                    <li><a class="dropdown-item" href="#" onclick="changeRole({{ $user->id }}, 'acheteur')">
                                                        <i class="bi bi-cart text-primary"></i> Acheteur
                                                    </a></li>
                                                    <li><a class="dropdown-item" href="#" onclick="changeRole({{ $user->id }}, 'producteur')">
                                                        <i class="bi bi-shop text-success"></i> Producteur
                                                    </a></li>
                                                    <li><a class="dropdown-item" href="#" onclick="changeRole({{ $user->id }}, 'admin')">
                                                        <i class="bi bi-shield text-danger"></i> Admin
                                                    </a></li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    @if(!$user->email_verified_at)
                                                        <li>
                                                            <form method="POST" action="{{ route('admin.users.verify-email', $user) }}">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button type="submit" class="dropdown-item text-success">
                                                                    <i class="bi bi-check-circle"></i> Vérifier l'email
                                                                </button>
                                                            </form>
                                                        </li>
                                                    @endif
                                                    <li>
                                                        <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="dropdown-item text-warning">
                                                                <i class="bi bi-{{ ($user->is_active ?? true) ? 'pause' : 'play' }}-circle"></i>
                                                                {{ ($user->is_active ?? true) ? 'Désactiver' : 'Activer' }}
                                                            </button>
                                                        </form>
                                                    </li>
                                                    @if($user->id !== auth()->id())
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li>
                                                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" 
                                                                  onsubmit="return confirm('⚠️ Êtes-vous sûr de vouloir supprimer cet utilisateur ?\n\nCette action est irréversible et supprimera :\n- Tous ses produits (si producteur)\n- Toutes ses commandes\n- Toutes ses données personnelles')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item text-danger">
                                                                    <i class="bi bi-trash"></i> Supprimer
                                                                </button>
                                                            </form>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="p-3 border-top">
                    {{ $users->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                    </div>
                    <p class="text-muted">Aucun utilisateur trouvé</p>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-secondary">
                        Réinitialiser les filtres
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Formulaire caché pour changer le rôle -->
<form id="roleForm" method="POST" style="display: none;">
    @csrf
    @method('PATCH')
    <input type="hidden" name="role" id="roleInput">
</form>

<script>
function changeRole(userId, role) {
    if (confirm(`Êtes-vous sûr de vouloir changer le rôle de cet utilisateur en "${role}" ?`)) {
        const form = document.getElementById('roleForm');
        form.action = `/admin/users/${userId}/role`;
        document.getElementById('roleInput').value = role;
        form.submit();
    }
}

// Initialiser les tooltips Bootstrap
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>

<style>
.avatar-circle {
    min-width: 40px;
}

.card {
    transition: transform 0.2s, box-shadow 0.2s;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.table tbody tr {
    transition: background-color 0.2s;
}

.table tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.05);
}

.btn-group .dropdown-menu {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}
</style>
@endsection