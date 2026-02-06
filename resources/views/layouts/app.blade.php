<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>@yield('title', config('app.name', 'Fubadj'))</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <!-- Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Styles pour le badge du panier */
        .badge-pulse {
            animation: badgePulse 0.3s ease-in-out;
        }

        @keyframes badgePulse {
            0% { transform: translate(-50%, -50%) scale(1); }
            50% { transform: translate(-50%, -50%) scale(1.3); }
            100% { transform: translate(-50%, -50%) scale(1); }
        }

        #panier-badge {
            box-shadow: 0 0 8px rgba(220, 53, 69, 0.5);
            border: 2px solid #fff;
            font-weight: bold;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        /* Amélioration de la navigation */
        .navbar-nav .nav-link {
            transition: color 0.3s ease;
            color: #495057 !important;
        }

        .navbar-nav .nav-link:hover {
            color: #2c7873 !important;
        }

        .navbar-brand {
            color: #2c7873 !important;
        }
    </style>
</head>
<body class="bg-gray-100 font-sans text-gray-800 antialiased">

    <!-- Toast notifications -->
    <div id="toast-container" class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1200;"></div>

    <div class="min-h-screen d-flex flex-column">
        
        <!-- Navigation -->
        @include('layouts.navigation')

        <!-- Header -->
        @hasSection('header')
            <header class="bg-white shadow-sm">
                <div class="container py-4">
                    @yield('header')
                </div>
            </header>
        @endif

        <!-- Main content -->
        <main class="flex-grow-1 py-4">
            <div class="container">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Script de gestion du badge panier -->
    <script>
    // =============================================================================
    // GESTION GLOBALE DU BADGE PANIER
    // =============================================================================
    document.addEventListener('DOMContentLoaded', function() {
        const panierBadge = document.getElementById('panier-badge');
        
        function mettreAJourBadgePanier() {
            if (!panierBadge) return;
            
            try {
                // Récupérer le panier depuis localStorage
                const panier = JSON.parse(localStorage.getItem('panier')) || [];
                
                // Calculer le nombre total d'articles (quantités cumulées)
                const totalItems = panier.reduce((total, produit) => {
                    return total + (produit.quantite || 1);
                }, 0);
                
                console.log('🛒 Badge panier:', totalItems + ' articles');
                
                // Mettre à jour le badge
                panierBadge.textContent = totalItems;
                
                if (totalItems > 0) {
                    panierBadge.style.display = 'inline-block';
                    
                    // Animation
                    panierBadge.classList.add('badge-pulse');
                    setTimeout(() => {
                        panierBadge.classList.remove('badge-pulse');
                    }, 300);
                    
                    // Changer la couleur si beaucoup d'articles
                    if (totalItems > 9) {
                        panierBadge.classList.remove('bg-danger');
                        panierBadge.classList.add('bg-warning');
                        panierBadge.style.color = 'black';
                    } else {
                        panierBadge.classList.remove('bg-warning');
                        panierBadge.classList.add('bg-danger');
                        panierBadge.style.color = 'white';
                    }
                } else {
                    panierBadge.style.display = 'none';
                }
            } catch (error) {
                console.error('❌ Erreur badge panier:', error);
                if (panierBadge) {
                    panierBadge.style.display = 'none';
                }
            }
        }
        
        // Mettre à jour au chargement
        mettreAJourBadgePanier();
        
        // Écouter les changements de localStorage (autres onglets)
        window.addEventListener('storage', function(e) {
            if (e.key === 'panier') {
                mettreAJourBadgePanier();
            }
        });
        
        // Mettre à jour périodiquement (au cas où)
        setInterval(mettreAJourBadgePanier, 3000);
        
        // Rendre la fonction accessible globalement
        window.mettreAJourBadgePanierGlobal = mettreAJourBadgePanier;
        
        console.log('✅ Gestionnaire badge panier initialisé');
    });
    </script>

    <!-- Scripts spécifiques aux pages -->
    @stack('scripts')

</body>
</html>