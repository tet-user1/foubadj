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
                                <i class="fas fa-box me-1"></i>Catalogue
                            </a>
                        </li>
                        {{-- Lien Financement pour producteur --}}
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('financement.index') }}">
                                <i class="fas fa-hand-holding-usd me-1"></i>Demander un Financement
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
                                <i class="fas fa-store me-1"></i>Marketplace
                            </a>
                        </li>
                        {{-- Lien Financement pour acheteur --}}
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('financement.index') }}">
                                <i class="fas fa-hand-holding-usd me-1"></i>Demander un Financement
                            </a>
                        </li>
                    @elseif(Auth::user()->hasRole('admin'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                <i class="fas fa-cog me-1"></i>Admin Dashboard
                            </a>
                        </li>
                    @endif
                @endauth
            </ul>

            <!-- Right Side -->
            <ul class="navbar-nav ms-auto">
                @auth
                    {{-- Panier et Commandes : UNIQUEMENT pour les acheteurs --}}
                    @if(Auth::user()->hasRole('acheteur'))
                        

                        <!-- Mes Commandes -->
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('commandes.history') }}">
                                <i class="fas fa-receipt me-1"></i>Mes Commandes
                            </a>
                        </li>
                    @endif

                    {{-- Lien financement en version mobile pour producteurs et acheteurs --}}
                    @if(Auth::user()->hasRole('producteur') || Auth::user()->hasRole('acheteur'))
                        <li class="nav-item d-lg-none">
                            <a class="nav-link" href="{{ route('financement.index') }}">
                                <i class="fas fa-hand-holding-usd me-1"></i>Financement
                            </a>
                        </li>
                    @endif

                    <!-- Menu utilisateur -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user me-1"></i>{{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li>
                                <a class="dropdown-item" href="{{ route('profile.show') }}">
                                    <i class="fas fa-user-edit me-2"></i>Mon profil
                                </a>
                            </li>
                            
                            {{-- Option financement dans le dropdown pour producteurs et acheteurs --}}
                            @if(Auth::user()->hasRole('producteur') || Auth::user()->hasRole('acheteur'))
                                <li>
                                    <a class="dropdown-item" href="{{ route('financement.index') }}">
                                        <i class="fas fa-hand-holding-usd me-2"></i>Demander un Financement
                                    </a>
                                </li>
                            @endif
                            
                            {{-- Liens admin conditionnels --}}
                            @if(Auth::user()->hasRole('admin'))
                                @if(Route::has('admin.dashboard'))
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                        <i class="fas fa-tachometer-alt me-2"></i>Tableau de bord Admin
                                    </a>
                                </li>
                                @endif
                                
                                {{-- Vérifiez chaque route avant de l'afficher --}}
                                @php
                                    $adminRoutes = [
                                        ['route' => 'admin.users', 'icon' => 'fas fa-users', 'text' => 'Gestion des Utilisateurs'],
                                        ['route' => 'admin.produits', 'icon' => 'fas fa-boxes', 'text' => 'Gestion des Produits'],
                                        ['route' => 'admin.commandes', 'icon' => 'fas fa-shopping-cart', 'text' => 'Gestion des Commandes'],
                                        ['route' => 'admin.categories', 'icon' => 'fas fa-tags', 'text' => 'Gestion des Catégories'],
                                    ];
                                @endphp
                                
                                @foreach($adminRoutes as $adminRoute)
                                    @if(Route::has($adminRoute['route']))
                                    <li>
                                        <a class="dropdown-item" href="{{ route($adminRoute['route']) }}">
                                            <i class="{{ $adminRoute['icon'] }} me-2"></i>{{ $adminRoute['text'] }}
                                        </a>
                                    </li>
                                    @endif
                                @endforeach
                            @endif
                            
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
                        <a class="nav-link" href="{{ route('catalogue.index') }}">
                            <i class="fas fa-store me-1"></i>Marketplace
                        </a>
                    </li>
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

{{-- Script pour mettre à jour le badge du panier --}}
@auth
    @if(Auth::user()->hasRole('acheteur'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Fonction pour mettre à jour le badge du panier
                function updateCartBadge() {
                    fetch('{{ route("panier.get") }}')
                        .then(response => response.json())
                        .then(data => {
                            const badge = document.getElementById('panier-badge');
                            if (data.success && data.panier_count > 0) {
                                badge.textContent = data.panier_count;
                                badge.style.display = 'block';
                            } else {
                                badge.style.display = 'none';
                            }
                        })
                        .catch(error => {
                            console.error('Erreur lors de la récupération du panier:', error);
                        });
                }

                // Mettre à jour au chargement
                updateCartBadge();

                // Mettre à jour toutes les 30 secondes
                setInterval(updateCartBadge, 30000);

                // Écouter les événements de mise à jour du panier
                document.addEventListener('cartUpdated', function() {
                    updateCartBadge();
                });
            });
        </script>
    @endif
@endauth