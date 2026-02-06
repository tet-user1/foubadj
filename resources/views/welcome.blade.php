<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Foubàdj - Accueil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <style>
        html, body {
            height: 100%;
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        body {
            background-image: url('{{ asset('images/afr.png') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            color: white;
            position: relative;
        }
        /* Overlay sombre */
        body::before {
            content: "";
            position: fixed;
            inset: 0;
            background-color: rgba(0,0,0,0.65);
            z-index: 0;
        }
        .content-wrapper {
            position: relative;
            z-index: 1;
        }
        /* HERO */
        .hero-section {
            min-height: 80vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 0 1rem;
        }
        .hero-section h1 {
            font-weight: 700;
            font-size: 3.5rem;
            text-shadow: 0 0 10px rgba(0,0,0,0.7);
        }
        .hero-section h1 .text-success {
            color: #28a745;
        }
        .hero-section p.lead {
            font-size: 1.5rem;
            margin-top: 1rem;
            margin-bottom: 2rem;
            text-shadow: 0 0 7px rgba(0,0,0,0.7);
        }
        .btn-custom {
            min-width: 160px;
            font-weight: 600;
            font-size: 1.15rem;
            border-radius: 50px;
            padding: 0.85rem 2rem;
            margin: 0 0.5rem;
            transition: background-color 0.3s ease, color 0.3s ease;
            border: 2px solid transparent;
        }
        .btn-marketplace {
            background-color: transparent;
            border-color: #ffc107;
            color: #ffc107;
        }
        .btn-marketplace:hover {
            background-color: #ffc107;
            color: #212529;
        }
        .btn-login {
            background-color: transparent;
            border-color: #28a745;
            color: #28a745;
        }
        .btn-login:hover {
            background-color: #28a745;
            color: white;
        }
        .btn-register {
            background-color: #28a745;
            color: white;
            border-color: #28a745;
        }
        .btn-register:hover {
            background-color: #218838;
            border-color: #1e7e34;
            color: white;
        }
        /* VALEURS */
        section.values-section {
            background-color: white;
            color: #212529;
            padding: 4rem 1rem;
            text-align: center;
        }
        section.values-section h2 {
            font-weight: 700;
            margin-bottom: 2rem;
            font-size: 2.5rem;
        }
        .value-item {
            margin-bottom: 2rem;
        }
        .value-item i {
            color: #28a745;
            font-size: 3.5rem;
            margin-bottom: 1rem;
        }
        .value-item h5 {
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        /* CTA */
        section.cta-section {
            background-color: #212529;
            color: white;
            text-align: center;
            padding: 3rem 1rem;
        }
        section.cta-section h3 {
            font-weight: 700;
            font-size: 2.25rem;
            margin-bottom: 1rem;
        }
        section.cta-section p {
            font-size: 1.25rem;
            margin-bottom: 2rem;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
        }
        section.cta-section .btn-register {
            min-width: 200px;
            font-size: 1.25rem;
        }
        /* Responsive */
        @media (max-width: 767px) {
            .hero-section h1 {
                font-size: 2.2rem;
            }
            .hero-section p.lead {
                font-size: 1.1rem;
            }
            section.values-section h2 {
                font-size: 1.9rem;
            }
            .btn-custom {
                margin-bottom: 0.5rem;
            }
        }
    </style>
</head>
<body>

    <div class="content-wrapper">

        <!-- HERO -->
        <section class="hero-section">
            <div class="container">
                <h1>Bienvenue sur <span class="text-success">Fubadj</span></h1>
                <p class="lead">Le marché digital des terriroirs africains.</p>
                <div>
                    <a href="{{ route('marketplace.index') }}" class="btn btn-marketplace btn-custom">
                        <i class="bi bi-shop me-2"></i>Découvrir le Marketplace
                    </a>
                    <a href="{{ route('login') }}" class="btn btn-login btn-custom">Se connecter</a>
                    <a href="{{ route('register') }}" class="btn btn-register btn-custom">Créer un compte</a>
                </div>
            </div>
        </section>

        <!-- VALEURS -->
        <section class="values-section">
            <div class="container">
                <h2>Pourquoi choisir Foubàdj ?</h2>
                <div class="row justify-content-center mt-4">
                    <div class="col-6 col-md-3 value-item">
                        <i class="bi bi-globe"></i>
                        <h5>Marché élargi</h5>
                        <p>Exportez vos produits au-delà des frontières.</p>
                    </div>
                    <div class="col-6 col-md-3 value-item">
                        <i class="bi bi-lock"></i>
                        <h5>Sécurité</h5>
                        <p>Paiement sécurisé et livraison garantie.</p>
                    </div>
                    <div class="col-6 col-md-3 value-item">
                        <i class="bi bi-person-video2"></i>
                        <h5>Accompagnement</h5>
                        <p>Une équipe dédiée vous accompagne.</p>
                    </div>
                    <div class="col-6 col-md-3 value-item">
                        <i class="bi bi-people"></i>
                        <h5>Communauté</h5>
                        <p>Participez à un réseau engagé africain.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA -->
        <section class="cta-section">
            <div class="container">
                <h3>Rejoignez le réseau Fubàdj</h3>
                <p>Unissez-vous à des centaines de producteurs et acheteurs à travers le continent.</p>
                <a href="{{ route('register') }}" class="btn btn-register btn-custom">Créer un compte maintenant</a>
            </div>
        </section>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>