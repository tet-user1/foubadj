<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Récapitulatif du Panier - FUBADJ</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome pour les icônes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #198754;
            --primary-dark: #157347;
            --secondary-color: #6c757d;
            --success-color: #198754;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --light-bg: #f8f9fa;
            --dark-text: #212529;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--light-bg);
            color: var(--dark-text);
        }
        
        .navbar {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .navbar-brand {
            font-weight: 600;
            font-size: 1.3rem;
        }
        
        .page-header {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            padding: 3rem 0;
            margin-bottom: 2rem;
        }
        
        .recap-card {
            background: white;
            border-radius: 10px;
            padding: 2rem;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        
        .recap-item {
            border-bottom: 1px solid #eee;
            padding: 1.5rem 0;
        }
        
        .recap-item:last-child {
            border-bottom: none;
        }
        
        .item-image {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
        }
        
        .total-box {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-radius: 10px;
            padding: 1.5rem;
            border: 2px solid var(--primary-color);
        }
        
        .btn-checkout {
            background: var(--primary-color);
            color: white;
            padding: 12px 30px;
            font-size: 1.1rem;
            transition: all 0.3s;
        }
        
        .btn-checkout:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        .btn-back {
            background: #6c757d;
            color: white;
            padding: 10px 20px;
        }
        
        .btn-back:hover {
            background: #5a6268;
            color: white;
        }
        
        .alert-empty {
            background: #f8f9fa;
            border: 2px dashed #dee2e6;
            border-radius: 10px;
            padding: 3rem;
            text-align: center;
        }
        
        .quantity-badge {
            background: var(--primary-color);
            color: white;
            border-radius: 20px;
            padding: 5px 15px;
            font-size: 0.9rem;
        }
        
        @media (max-width: 768px) {
            .recap-card {
                padding: 1rem;
            }
            
            .page-header {
                padding: 2rem 0;
            }
            
            .item-image {
                width: 80px;
                height: 80px;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('catalogue.index') }}">
                <i class="fas fa-seedling me-2"></i>FUBADJ
            </a>
            
            <div class="d-flex align-items-center">
                <!-- Bouton Dashboard pour utilisateurs connectés -->
                @auth
                <div class="me-3">
                    <a href="{{ route('dashboard.acheteur') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-tachometer-alt me-1"></i> Dashboard
                    </a>
                </div>
                @endauth
            </div>
        </div>
    </nav>

    <!-- En-tête -->
    <div class="page-header">
        <div class="container">
            <h1 class="display-5 fw-bold mb-2">
                <i class="fas fa-shopping-basket me-3"></i>Récapitulatif de votre commande
            </h1>
            <p class="lead mb-0">Vérifiez vos articles avant de procéder au paiement</p>
        </div>
    </div>

    <!-- Contenu principal -->
    <div class="container pb-5">
        <div class="row">
            <div class="col-lg-8">
                <div class="recap-card">
                    <h3 class="mb-4">
                        <i class="fas fa-list-check me-2 text-success"></i>Vos articles
                        <span class="badge bg-primary ms-2" id="itemCount">0 article(s)</span>
                    </h3>
                    
                    <div id="recapItemsContainer">
                        <!-- Les articles seront ajoutés ici par JavaScript -->
                        <div class="alert-empty">
                            <i class="fas fa-shopping-basket fa-4x text-muted mb-4"></i>
                            <h4 class="text-dark mb-3">Votre panier est vide</h4>
                            <p class="text-muted mb-4">Vous n'avez pas encore ajouté d'articles à votre panier</p>
                            <a href="{{ route('catalogue.index') }}" class="btn btn-success">
                                <i class="fas fa-store me-2"></i>Parcourir le catalogue
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Informations client (si connecté) -->
                @auth
                <div class="recap-card">
                    <h3 class="mb-4">
                        <i class="fas fa-user me-2 text-success"></i>Informations client
                    </h3>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Nom complet</label>
                            <div class="form-control bg-light">{{ auth()->user()->name }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Email</label>
                            <div class="form-control bg-light">{{ auth()->user()->email }}</div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-edit me-1"></i>Modifier mes informations
                        </a>
                    </div>
                </div>
                @else
                <div class="recap-card">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Vous n'êtes pas connecté</strong>
                        <p class="mb-0 mt-2">Pour finaliser votre commande, veuillez vous connecter ou créer un compte.</p>
                    </div>
                    <div class="d-flex gap-3">
                        <a href="{{ route('login') }}" class="btn btn-primary">
                            <i class="fas fa-sign-in-alt me-2"></i>Se connecter
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-outline-primary">
                            <i class="fas fa-user-plus me-2"></i>Créer un compte
                        </a>
                    </div>
                </div>
                @endauth
            </div>
            
            <div class="col-lg-4">
                <div class="sticky-top" style="top: 20px;">
                    <div class="recap-card">
                        <h3 class="mb-4">
                            <i class="fas fa-receipt me-2 text-success"></i>Résumé de la commande
                        </h3>
                        
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Sous-total</span>
                                <span class="fw-bold" id="subtotal">0 FCFA</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Frais de livraison</span>
                                <span class="fw-bold" id="shipping">0 FCFA</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Taxes</span>
                                <span class="fw-bold" id="taxes">0 FCFA</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="fs-5 fw-bold">Total</span>
                                <span class="fs-4 fw-bold text-success" id="totalAmount">0 FCFA</span>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            @auth
                            <button class="btn btn-checkout btn-lg" id="finalCheckoutBtn">
                                <i class="fas fa-credit-card me-2"></i>Procéder au paiement
                            </button>
                            <a href="{{ route('commandes.nouvelle') }}" class="btn btn-outline-success btn-lg">
                                <i class="fas fa-shopping-bag me-2"></i>Créer la commande
                            </a>
                            @else
                            <a href="{{ route('login') }}" class="btn btn-checkout btn-lg">
                                <i class="fas fa-sign-in-alt me-2"></i>Se connecter pour payer
                            </a>
                            @endauth
                            
                            <a href="{{ route('catalogue.index') }}" class="btn btn-back">
                                <i class="fas fa-arrow-left me-2"></i>Retour au catalogue
                            </a>
                        </div>
                        
                        <div class="mt-4 text-center">
                            <small class="text-muted">
                                <i class="fas fa-lock me-1"></i>Paiement sécurisé
                            </small>
                        </div>
                    </div>
                    
                    <!-- Informations importantes -->
                    <div class="recap-card mt-3">
                        <h6 class="mb-3">
                            <i class="fas fa-info-circle me-2 text-primary"></i>Informations importantes
                        </h6>
                        <ul class="list-unstyled small text-muted">
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Paiement 100% sécurisé</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Produits frais garantis</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Livraison chez les producteurs locaux</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Support client disponible</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container text-center">
            <p class="mb-0">&copy; {{ date('Y') }} FUBADJ. Tous droits réservés.</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Récupérer les données du panier depuis localStorage
            const panierData = JSON.parse(localStorage.getItem('panier_recapitulatif'));
            
            if (panierData && panierData.items && panierData.items.length > 0) {
                // Afficher les articles
                displayRecapItems(panierData.items);
                
                // Calculer et afficher les totaux
                calculateAndDisplayTotals(panierData);
                
                // Afficher le nombre d'articles
                document.getElementById('itemCount').textContent = panierData.count + ' article(s)';
                
                // Si l'utilisateur est connecté, activer le bouton de paiement
                setupCheckoutButton(panierData);
            } else {
                // Afficher un message si le panier est vide
                showEmptyCartMessage();
            }
            
            function displayRecapItems(items) {
                const container = document.getElementById('recapItemsContainer');
                let html = '';
                
                items.forEach(item => {
                    html += `
                        <div class="recap-item">
                            <div class="row align-items-center">
                                <div class="col-md-2 col-3">
                                    ${item.image ? 
                                        `<img src="${item.image}" class="item-image" alt="${item.name}">` :
                                        `<div class="item-image bg-light d-flex align-items-center justify-content-center">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>`
                                    }
                                </div>
                                <div class="col-md-6 col-5">
                                    <h6 class="mb-1">${item.name}</h6>
                                    <small class="text-muted">${item.price.toFixed(0)} FCFA l'unité</small>
                                </div>
                                <div class="col-md-2 col-2 text-center">
                                    <span class="quantity-badge">${item.quantity}</span>
                                </div>
                                <div class="col-md-2 col-2 text-end">
                                    <span class="fw-bold text-success">${(item.price * item.quantity).toFixed(0)} FCFA</span>
                                </div>
                            </div>
                        </div>
                    `;
                });
                
                container.innerHTML = html;
            }
            
            function calculateAndDisplayTotals(data) {
                // Sous-total (total des articles)
                const subtotal = data.total;
                
                // Frais de livraison (exemple: 500 FCFA si total > 5000, sinon 1000)
                const shipping = subtotal > 5000 ? 500 : 1000;
                
                // Taxes (exemple: 18%)
                const taxes = subtotal * 0.18;
                
                // Total général
                const total = subtotal + shipping + taxes;
                
                // Afficher les montants
                document.getElementById('subtotal').textContent = subtotal.toFixed(0) + ' FCFA';
                document.getElementById('shipping').textContent = shipping.toFixed(0) + ' FCFA';
                document.getElementById('taxes').textContent = taxes.toFixed(0) + ' FCFA';
                document.getElementById('totalAmount').textContent = total.toFixed(0) + ' FCFA';
                
                // Stocker les totaux pour utilisation ultérieure
                localStorage.setItem('order_totals', JSON.stringify({
                    subtotal: subtotal,
                    shipping: shipping,
                    taxes: taxes,
                    total: total
                }));
            }
            
            function setupCheckoutButton(data) {
                const checkoutBtn = document.getElementById('finalCheckoutBtn');
                if (checkoutBtn) {
                    checkoutBtn.addEventListener('click', function() {
                        // Préparer les données complètes de la commande
                        const orderData = {
                            items: data.items,
                            totals: JSON.parse(localStorage.getItem('order_totals')),
                            transaction_id: data.transaction_id || 'TRX_' + Date.now(),
                            timestamp: new Date().toISOString(),
                            user_id: {{ auth()->id() ?? 'null' }}
                        };
                        
                        // Stocker les données de la commande
                        localStorage.setItem('order_data', JSON.stringify(orderData));
                        
                        // Rediriger vers la page de paiement
                        // Vous pouvez choisir une des options suivantes :
                        
                        // Option 1: Rediriger vers le processus de paiement
                        window.location.href = "{{ route('paiement.process') }}";
                        
                        // Option 2: Rediriger vers la création de commande
                        // window.location.href = "{{ route('commandes.nouvelle') }}";
                        
                        // Option 3: Soumettre un formulaire (si besoin de traitement serveur)
                        // submitOrderToServer(orderData);
                    });
                }
            }
            
            function submitOrderToServer(orderData) {
                // Créer un formulaire pour soumettre les données au serveur
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("commandes.traiter") }}';
                form.style.display = 'none';
                
                // Ajouter le token CSRF
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);
                
                // Ajouter les données de la commande
                const orderInput = document.createElement('input');
                orderInput.type = 'hidden';
                orderInput.name = 'order_data';
                orderInput.value = JSON.stringify(orderData);
                form.appendChild(orderInput);
                
                // Ajouter le formulaire à la page et le soumettre
                document.body.appendChild(form);
                form.submit();
            }
            
            function showEmptyCartMessage() {
                const container = document.getElementById('recapItemsContainer');
                const checkoutSection = document.querySelector('.col-lg-4 .recap-card');
                
                if (checkoutSection) {
                    checkoutSection.innerHTML = `
                        <div class="alert alert-warning">
                            <i class="fas fa-shopping-cart me-2"></i>
                            <strong>Votre panier est vide</strong>
                            <p class="mb-0 mt-2">Ajoutez des articles à votre panier avant de procéder au paiement.</p>
                        </div>
                        <div class="d-grid">
                            <a href="{{ route('catalogue.index') }}" class="btn btn-success">
                                <i class="fas fa-store me-2"></i>Retourner au catalogue
                            </a>
                        </div>
                    `;
                }
            }
            
            // Nettoyer le localStorage si l'utilisateur quitte la page
            window.addEventListener('beforeunload', function() {
                // Optionnel : Vous pouvez choisir de ne pas nettoyer pour garder les données
                // localStorage.removeItem('panier_recapitulatif');
            });
        });
    </script>
</body>
</html>