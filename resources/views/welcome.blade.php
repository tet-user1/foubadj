<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Foubàdj - Le Marché Digital des Terroirs Africains</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <style>
        html, body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        body {
            background-color: #f8f9fa;
        }
        
        /* HEADER / NAVBAR */
        .navbar-custom {
            background-color: rgba(33, 37, 41, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .navbar-custom .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: #28a745 !important;
        }
        .navbar-custom .nav-link {
            color: white !important;
            margin: 0 0.5rem;
            transition: color 0.3s;
        }
        .navbar-custom .nav-link:hover {
            color: #28a745 !important;
        }
        
        /* HERO SECTION */
        .hero-section {
            background-image: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('{{ asset('images/afr.png') }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 90vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            position: relative;
        }
        .hero-section h1 {
            font-weight: 700;
            font-size: 3.5rem;
            text-shadow: 0 0 20px rgba(0,0,0,0.7);
            margin-bottom: 1.5rem;
        }
        .hero-section h1 .text-success {
            color: #28a745;
        }
        .hero-section p.lead {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            text-shadow: 0 0 10px rgba(0,0,0,0.7);
        }
        .hero-section p.subtitle {
            font-size: 1.2rem;
            margin-bottom: 2.5rem;
            opacity: 0.9;
        }
        .btn-custom {
            min-width: 180px;
            font-weight: 600;
            font-size: 1.15rem;
            border-radius: 50px;
            padding: 0.85rem 2rem;
            margin: 0.5rem;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }
        .btn-marketplace {
            background-color: #ffc107;
            border-color: #ffc107;
            color: #212529;
        }
        .btn-marketplace:hover {
            background-color: #e0a800;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 193, 7, 0.4);
        }
        .btn-login {
            background-color: transparent;
            border-color: #28a745;
            color: white;
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
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
        }

        /* ABOUT SECTION */
        .about-section {
            padding: 5rem 1rem;
            background-color: white;
        }
        .about-section h2 {
            font-weight: 700;
            font-size: 2.5rem;
            margin-bottom: 1.5rem;
            color: #212529;
        }
        .about-section .lead {
            font-size: 1.2rem;
            color: #6c757d;
            margin-bottom: 2rem;
        }
        .stats-box {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 2rem;
            border-radius: 15px;
            text-align: center;
            margin-bottom: 1.5rem;
            transition: transform 0.3s;
        }
        .stats-box:hover {
            transform: translateY(-5px);
        }
        .stats-box h3 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        .stats-box p {
            font-size: 1.1rem;
            margin: 0;
            opacity: 0.9;
        }

        /* FEATURES SECTION */
        .features-section {
            padding: 5rem 1rem;
            background-color: #f8f9fa;
        }
        .features-section h2 {
            font-weight: 700;
            font-size: 2.5rem;
            margin-bottom: 3rem;
            text-align: center;
            color: #212529;
        }
        .feature-card {
            background: white;
            border-radius: 15px;
            padding: 2.5rem 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            transition: all 0.3s;
            height: 100%;
        }
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }
        .feature-card i {
            font-size: 3.5rem;
            color: #28a745;
            margin-bottom: 1.5rem;
        }
        .feature-card h4 {
            font-weight: 600;
            margin-bottom: 1rem;
            color: #212529;
        }
        .feature-card p {
            color: #6c757d;
            line-height: 1.7;
        }

        /* HOW IT WORKS */
        .how-it-works {
            padding: 5rem 1rem;
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }
        .how-it-works h2 {
            font-weight: 700;
            font-size: 2.5rem;
            margin-bottom: 3rem;
            text-align: center;
        }
        .step-card {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255,255,255,0.2);
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            text-align: center;
            transition: all 0.3s;
        }
        .step-card:hover {
            background: rgba(255,255,255,0.15);
            transform: scale(1.05);
        }
        .step-number {
            width: 60px;
            height: 60px;
            background: white;
            color: #28a745;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            font-weight: 700;
            margin: 0 auto 1.5rem;
        }
        .step-card h4 {
            font-weight: 600;
            margin-bottom: 1rem;
        }

        /* PROFILES SECTION */
        .profiles-section {
            padding: 5rem 1rem;
            background-color: white;
        }
        .profiles-section h2 {
            font-weight: 700;
            font-size: 2.5rem;
            margin-bottom: 1rem;
            text-align: center;
            color: #212529;
        }
        .profiles-section .subtitle {
            text-align: center;
            color: #6c757d;
            font-size: 1.2rem;
            margin-bottom: 3rem;
        }
        .profile-card {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 15px;
            padding: 3rem 2rem;
            margin-bottom: 2rem;
            text-align: center;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            transition: all 0.3s;
        }
        .profile-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }
        .profile-card i {
            font-size: 4rem;
            color: #28a745;
            margin-bottom: 1.5rem;
        }
        .profile-card h3 {
            font-weight: 700;
            margin-bottom: 1rem;
            color: #212529;
        }
        .profile-card ul {
            list-style: none;
            padding: 0;
            text-align: left;
            margin-bottom: 2rem;
        }
        .profile-card ul li {
            padding: 0.5rem 0;
            color: #495057;
        }
        .profile-card ul li i {
            color: #28a745;
            font-size: 1rem;
            margin-right: 0.5rem;
        }

        /* TESTIMONIALS */
        .testimonials-section {
            padding: 5rem 1rem;
            background-color: #f8f9fa;
        }
        .testimonials-section h2 {
            font-weight: 700;
            font-size: 2.5rem;
            margin-bottom: 3rem;
            text-align: center;
            color: #212529;
        }
        .testimonial-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        }
        .testimonial-card .quote {
            font-size: 1.1rem;
            color: #495057;
            font-style: italic;
            margin-bottom: 1.5rem;
            line-height: 1.7;
        }
        .testimonial-card .author {
            display: flex;
            align-items: center;
        }
        .testimonial-card .author-avatar {
            width: 50px;
            height: 50px;
            background: #28a745;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.2rem;
            margin-right: 1rem;
        }
        .testimonial-card .author-info h5 {
            margin: 0;
            font-weight: 600;
            color: #212529;
        }
        .testimonial-card .author-info p {
            margin: 0;
            color: #6c757d;
            font-size: 0.9rem;
        }

        /* CTA SECTION */
        .cta-section {
            background: linear-gradient(135deg, #212529 0%, #343a40 100%);
            color: white;
            text-align: center;
            padding: 5rem 1rem;
        }
        .cta-section h3 {
            font-weight: 700;
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }
        .cta-section p {
            font-size: 1.25rem;
            margin-bottom: 2.5rem;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
        }

        /* FOOTER */
        .footer {
            background-color: #212529;
            color: white;
            padding: 3rem 1rem 1rem;
        }
        .footer h5 {
            font-weight: 700;
            margin-bottom: 1rem;
            color: #28a745;
        }
        .footer ul {
            list-style: none;
            padding: 0;
        }
        .footer ul li {
            margin-bottom: 0.5rem;
        }
        .footer ul li a {
            color: #adb5bd;
            text-decoration: none;
            transition: color 0.3s;
        }
        .footer ul li a:hover {
            color: #28a745;
        }
        .footer-bottom {
            border-top: 1px solid #495057;
            padding-top: 2rem;
            margin-top: 2rem;
            text-align: center;
            color: #adb5bd;
        }

        /* Responsive */
        @media (max-width: 767px) {
            .hero-section h1 {
                font-size: 2.2rem;
            }
            .hero-section p.lead {
                font-size: 1.2rem;
            }
            .hero-section p.subtitle {
                font-size: 1rem;
            }
            .about-section h2, .features-section h2, .how-it-works h2, 
            .profiles-section h2, .testimonials-section h2, .cta-section h3 {
                font-size: 1.9rem;
            }
            .stats-box h3 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-custom sticky-top">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="bi bi-basket2-fill me-2"></i>Fubàdj
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#about">À propos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Fonctionnalités</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#how">Comment ça marche</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#profiles">Pour qui</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Connexion</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-success text-white px-3" href="{{ route('register') }}">S'inscrire</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- HERO SECTION -->
    <section class="hero-section">
        <div class="container">
            <h1>Bienvenue sur <span class="text-success">Fubàdj</span></h1>
            <p class="lead">Le marché digital des terroirs africains</p>
            <p class="subtitle">Connectez producteurs locaux et acheteurs passionnés à travers toute l'Afrique</p>
            <div>
                <a href="{{ route('marketplace.index') }}" class="btn btn-marketplace btn-custom">
                    <i class="bi bi-shop me-2"></i>Découvrir le Marketplace
                </a>
                <a href="{{ route('login') }}" class="btn btn-login btn-custom">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Se connecter
                </a>
                <a href="{{ route('register') }}" class="btn btn-register btn-custom">
                    <i class="bi bi-person-plus me-2"></i>Créer un compte
                </a>
            </div>
        </div>
    </section>

    <!-- ABOUT SECTION -->
    <section class="about-section" id="about">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4">
                    <h2>Qu'est-ce que Fubàdj ?</h2>
                    <p class="lead">
                        Fubàdj est une plateforme digitale innovante qui révolutionne la commercialisation 
                        des produits agricoles et artisanaux africains.
                    </p>
                    <p>
                        Notre mission est de créer un pont direct entre les producteurs locaux et les acheteurs, 
                        éliminant les intermédiaires et garantissant des prix justes pour tous. Nous croyons en 
                        la valorisation des terroirs africains et en la promotion d'une économie locale durable.
                    </p>
                    <p>
                        Que vous soyez agriculteur, artisan, transformateur ou acheteur, Fubàdj vous offre 
                        un espace sécurisé pour échanger, vendre et acheter des produits authentiques et de qualité.
                    </p>
                </div>
                <div class="col-lg-6">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="stats-box">
                                <h3>500+</h3>
                                <p>Producteurs actifs</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="stats-box">
                                <h3>1000+</h3>
                                <p>Produits disponibles</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="stats-box">
                                <h3>15+</h3>
                                <p>Pays africains</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="stats-box">
                                <h3>98%</h3>
                                <p>Clients satisfaits</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FEATURES SECTION -->
    <section class="features-section" id="features">
        <div class="container">
            <h2>Nos Fonctionnalités</h2>
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <i class="bi bi-shop"></i>
                        <h4>Marketplace Complet</h4>
                        <p>
                            Parcourez des milliers de produits locaux : fruits, légumes, céréales, 
                            produits transformés, artisanat et bien plus encore. Tous certifiés et 
                            contrôlés pour garantir leur qualité.
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <i class="bi bi-credit-card-2-front"></i>
                        <h4>Paiement Sécurisé</h4>
                        <p>
                            Effectuez vos transactions en toute sécurité avec plusieurs options de paiement : 
                            mobile money (Orange Money, Wave, Free Money), cartes bancaires et paiement à la livraison.
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <i class="bi bi-truck"></i>
                        <h4>Livraison Rapide</h4>
                        <p>
                            Bénéficiez d'un réseau de livraison fiable couvrant tout le territoire. 
                            Suivi en temps réel de vos commandes et livraison à domicile ou en point relais.
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <i class="bi bi-graph-up-arrow"></i>
                        <h4>Gestion Simplifiée</h4>
                        <p>
                            Dashboard intuitif pour les producteurs : gestion des stocks, suivi des ventes, 
                            statistiques détaillées et outils marketing pour booster votre visibilité.
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <i class="bi bi-shield-check"></i>
                        <h4>Garantie Qualité</h4>
                        <p>
                            Tous nos producteurs sont vérifiés et leurs produits contrôlés. 
                            Système d'évaluation transparent et garantie satisfait ou remboursé.
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <i class="bi bi-headset"></i>
                        <h4>Support Dédié</h4>
                        <p>
                            Une équipe à votre écoute 7j/7 pour répondre à vos questions, 
                            vous accompagner dans vos démarches et résoudre tout problème rapidement.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- HOW IT WORKS -->
    <section class="how-it-works" id="how">
        <div class="container">
            <h2>Comment Ça Marche ?</h2>
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="step-card">
                        <div class="step-number">1</div>
                        <h4>Inscrivez-vous</h4>
                        <p>Créez votre compte gratuitement en quelques clics. Choisissez votre profil : producteur ou acheteur.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="step-card">
                        <div class="step-number">2</div>
                        <h4>Explorez</h4>
                        <p>Découvrez notre marketplace et trouvez les produits qui vous intéressent ou ajoutez vos propres produits.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="step-card">
                        <div class="step-number">3</div>
                        <h4>Commandez</h4>
                        <p>Passez vos commandes en toute simplicité avec notre panier intelligent et nos options de paiement flexibles.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="step-card">
                        <div class="step-number">4</div>
                        <h4>Recevez</h4>
                        <p>Suivez votre livraison en temps réel et recevez vos produits frais directement chez vous.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- PROFILES SECTION -->
    <section class="profiles-section" id="profiles">
        <div class="container">
            <h2>Fubàdj Pour Qui ?</h2>
            <p class="subtitle">Notre plateforme s'adresse à tous les acteurs de la chaîne de valeur agricole</p>
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="profile-card">
                        <i class="bi bi-people-fill"></i>
                        <h3>Producteurs</h3>
                        <ul>
                            <li><i class="bi bi-check-circle-fill"></i> Agriculteurs et maraîchers</li>
                            <li><i class="bi bi-check-circle-fill"></i> Éleveurs et pêcheurs</li>
                            <li><i class="bi bi-check-circle-fill"></i> Transformateurs agroalimentaires</li>
                            <li><i class="bi bi-check-circle-fill"></i> Artisans locaux</li>
                            <li><i class="bi bi-check-circle-fill"></i> Coopératives agricoles</li>
                        </ul>
                        <a href="{{ route('register') }}" class="btn btn-success btn-custom">Vendre mes produits</a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="profile-card">
                        <i class="bi bi-cart-fill"></i>
                        <h3>Acheteurs</h3>
                        <ul>
                            <li><i class="bi bi-check-circle-fill"></i> Particuliers & familles</li>
                            <li><i class="bi bi-check-circle-fill"></i> Restaurants et hôtels</li>
                            <li><i class="bi bi-check-circle-fill"></i> Supermarchés et épiceries</li>
                            <li><i class="bi bi-check-circle-fill"></i> Cantines scolaires</li>
                            <li><i class="bi bi-check-circle-fill"></i> Exportateurs</li>
                        </ul>
                        <a href="{{ route('marketplace.index') }}" class="btn btn-success btn-custom">Acheter des produits</a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="profile-card">
                        <i class="bi bi-building"></i>
                        <h3>Entreprises</h3>
                        <ul>
                            <li><i class="bi bi-check-circle-fill"></i> Approvisionnement en gros</li>
                            <li><i class="bi bi-check-circle-fill"></i> Contrats de fourniture</li>
                            <li><i class="bi bi-check-circle-fill"></i> Sourcing de matières premières</li>
                            <li><i class="bi bi-check-circle-fill"></i> Partenariats commerciaux</li>
                            <li><i class="bi bi-check-circle-fill"></i> Solutions B2B sur mesure</li>
                        </ul>
                        <a href="{{ route('register') }}" class="btn btn-success btn-custom">Rejoindre le réseau</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- TESTIMONIALS -->
    <section class="testimonials-section">
        <div class="container">
            <h2>Ils Nous Font Confiance</h2>
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="testimonial-card">
                        <p class="quote">
                            "Grâce à Fubàdj, j'ai pu écouler toute ma production de tomates sans intermédiaire. 
                            Mes revenus ont augmenté de 40% en 3 mois !"
                        </p>
                        <div class="author">
                            <div class="author-avatar">AM</div>
                            <div class="author-info">
                                <h5>Amadou Mbaye</h5>
                                <p>Maraîcher - Thiès, Sénégal</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="testimonial-card">
                        <p class="quote">
                            "Je commande tous mes légumes frais sur Fubàdj pour mon restaurant. 
                            Qualité excellente, livraison ponctuelle et prix très compétitifs."
                        </p>
                        <div class="author">
                            <div class="author-avatar">FD</div>
                            <div class="author-info">
                                <h5>Fatou Diop</h5>
                                <p>Restauratrice - Dakar, Sénégal</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="testimonial-card">
                        <p class="quote">
                            "Une plateforme révolutionnaire ! Enfin un espace qui valorise nos produits locaux 
                            et nous permet d'atteindre de nouveaux marchés."
                        </p>
                        <div class="author">
                            <div class="author-avatar">KT</div>
                            <div class="author-info">
                                <h5>Kofi Touré</h5>
                                <p>Coopérative agricole - Abidjan, Côte d'Ivoire</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA SECTION -->
    <section class="cta-section">
        <div class="container">
            <h3>Prêt à Rejoindre la Révolution Agricole Digitale ?</h3>
            <p>
                Rejoignez des milliers de producteurs et acheteurs qui font confiance à Fubàdj 
                pour leurs transactions agricoles. Ensemble, construisons l'avenir de l'agriculture africaine.
            </p>
            <a href="{{ route('register') }}" class="btn btn-success btn-custom btn-lg">
                <i class="bi bi-rocket-takeoff me-2"></i>Créer mon compte gratuitement
            </a>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4">
                    <h5><i class="bi bi-basket2-fill me-2"></i>Fubàdj</h5>
                    <p class="text-muted">
                        Le marché digital des terroirs africains. Connecter, valoriser et développer 
                        l'agriculture locale à travers le continent.
                    </p>
                    <div class="mt-3">
                        <a href="#" class="text-white me-3"><i class="bi bi-facebook fs-4"></i></a>
                        <a href="#" class="text-white me-3"><i class="bi bi-twitter fs-4"></i></a>
                        <a href="#" class="text-white me-3"><i class="bi bi-instagram fs-4"></i></a>
                        <a href="#" class="text-white"><i class="bi bi-linkedin fs-4"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5>Liens Rapides</h5>
                    <ul>
                        <li><a href="#about">À propos</a></li>
                        <li><a href="#features">Fonctionnalités</a></li>
                        <li><a href="#how">Comment ça marche</a></li>
                        <li><a href="{{ route('marketplace.index') }}">Marketplace</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5>Support</h5>
                    <ul>
                        <li><a href="#">Centre d'aide</a></li>
                        <li><a href="#">FAQ</a></li>
                        <li><a href="#">Contact</a></li>
                        <li><a href="#">Politique de confidentialité</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <h5>Contact</h5>
                    <ul>
                        <li><i class="bi bi-geo-alt-fill text-success me-2"></i>Dakar, Sénégal</li>
                        <li><i class="bi bi-envelope-fill text-success me-2"></i>contact@fubadj.com</li>
                        <li><i class="bi bi-telephone-fill text-success me-2"></i>+221 77 123 45 67</li>
                        <li><i class="bi bi-clock-fill text-success me-2"></i>Lun-Sam: 8h-20h</li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2026 Fubàdj. Tous droits réservés. | Conçu avec ❤️ pour l'Afrique</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Smooth scroll pour les liens d'ancrage
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>
</html>