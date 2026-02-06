<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil - Fubadj</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .profile-header {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            padding: 3rem 0;
            margin-bottom: 2rem;
        }
        .profile-card {
            border: none;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            border-radius: 1rem;
            margin-bottom: 2rem;
        }
        .profile-avatar {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid white;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        .info-label {
            font-weight: 600;
            color: #495057;
        }
        .info-value {
            color: #6c757d;
        }
        .role-badge {
            font-size: 0.9em;
            padding: 0.5em 1em;
        }
    </style>
</head>
<body>
    <!-- Navigation intégrée -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold text-success" href="{{ url('/') }}">
                <i class="fas fa-leaf me-2"></i>Fubadj
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side -->
                <ul class="navbar-nav me-auto">
                    @auth
                        @if(Auth::user()->hasRole('producteur'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('dashboard.producteur') }}">
                                    <i class="fas fa-tachometer-alt me-1"></i>Dashboard Producteur
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('mes.produits') }}">
                                    <i class="fas fa-box me-1"></i>Mes Produits
                                </a>
                            </li>
                        @elseif(Auth::user()->hasRole('acheteur'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('dashboard.acheteur') }}">
                                    <i class="fas fa-tachometer-alt me-1"></i>Dashboard Acheteur
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('catalogue.index') }}">
                                    <i class="fas fa-store me-1"></i>Catalogue
                                </a>
                            </li>
                        @elseif(Auth::user()->hasRole('admin'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                    <i class="fas fa-cog me-1"></i>Admin
                                </a>
                            </li>
                        @endif
                    @endauth
                </ul>

                <!-- Right Side -->
                <ul class="navbar-nav ms-auto">
                    @auth
                        <!-- Menu utilisateur -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user me-1"></i>{{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li>
                                    <a class="dropdown-item" href="{{ route('profile.show') }}">
                                        <i class="fas fa-user me-2"></i>Voir mon profil
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                        <i class="fas fa-user-edit me-2"></i>Modifier mon profil
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button class="dropdown-item" type="submit">
                                            <i class="fas fa-sign-out-alt me-2"></i>Se déconnecter
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <!-- Non connecté -->
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt me-1"></i>Se connecter
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">
                                <i class="fas fa-user-plus me-1"></i>S'inscrire
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <div class="profile-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-2 text-center">
                    @if($user->profile_photo_path)
                        <img src="{{ asset('storage/' . $user->profile_photo_path) }}" 
                             alt="Photo de profil" class="profile-avatar">
                    @else
                        <div class="profile-avatar bg-white d-flex align-items-center justify-content-center mx-auto">
                            <i class="fas fa-user text-success" style="font-size: 4rem;"></i>
                        </div>
                    @endif
                </div>
                <div class="col-md-10">
                    <h1 class="display-5 fw-bold">{{ $user->name }}</h1>
                    <p class="lead mb-2">
                        <i class="fas fa-envelope me-2"></i>{{ $user->email }}
                        @if($user->email_verified_at)
                            <span class="badge bg-success ms-2">
                                <i class="fas fa-check-circle me-1"></i>Email vérifié
                            </span>
                        @else
                            <span class="badge bg-warning ms-2">
                                <i class="fas fa-exclamation-triangle me-1"></i>Email non vérifié
                            </span>
                        @endif
                    </p>
                    <p class="mb-2">
                        <i class="fas fa-user-tag me-2"></i>
                        <span class="badge bg-info role-badge">{{ ucfirst($user->role) }}</span>
                    </p>
                    <p class="mb-0">
                        <i class="fas fa-calendar-alt me-2"></i>
                        Membre depuis : {{ $user->created_at->format('d/m/Y') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row justify-content-center">
            <!-- Informations personnelles -->
            <div class="col-md-8">
                <div class="card profile-card">
                    <div class="card-header bg-success text-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-id-card me-2"></i>Informations du Profil
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-sm-4 info-label">Nom complet:</div>
                            <div class="col-sm-8 info-value">{{ $user->name }}</div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-sm-4 info-label">Adresse email:</div>
                            <div class="col-sm-8 info-value">{{ $user->email }}</div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-4 info-label">Rôle:</div>
                            <div class="col-sm-8 info-value">
                                <span class="badge bg-primary">{{ ucfirst($user->role) }}</span>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-4 info-label">Téléphone:</div>
                            <div class="col-sm-8 info-value">
                                {{ $user->telephone ?? 'Non renseigné' }}
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-4 info-label">Adresse:</div>
                            <div class="col-sm-8 info-value">
                                {{ $user->adresse ?? 'Non renseignée' }}
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-4 info-label">Compte créé le:</div>
                            <div class="col-sm-8 info-value">
                                {{ $user->created_at->format('d/m/Y à H:i') }}
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-4 info-label">Dernière mise à jour:</div>
                            <div class="col-sm-8 info-value">
                                {{ $user->updated_at->format('d/m/Y à H:i') }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions rapides -->
                <div class="card profile-card">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-bolt me-2"></i>Actions
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <a href="{{ route('profile.edit') }}" class="btn btn-success w-100">
                                    <i class="fas fa-edit me-2"></i>Modifier mon profil
                                </a>
                            </div>
                            <div class="col-md-6 mb-3">
                                <form method="POST" action="{{ route('logout') }}" class="d-inline w-100">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger w-100">
                                        <i class="fas fa-sign-out-alt me-2"></i>Se déconnecter
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>