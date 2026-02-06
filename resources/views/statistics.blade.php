@extends('layouts.app')

@section('title', 'Statistiques Producteur')

{{-- Styles CSS personnalisés --}}
@section('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<style>
    .card {
        transition: transform 0.2s ease-in-out;
    }
    
    .card:hover {
        transform: translateY(-2px);
    }
    
    .kpi-card {
        background: linear-gradient(135deg, var(--bs-primary) 0%, var(--bs-primary-dark) 100%);
        border: none;
        border-radius: 10px;
    }
    
    .bg-primary { background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%) !important; }
    .bg-success { background: linear-gradient(135deg, #198754 0%, #146c43 100%) !important; }
    .bg-info { background: linear-gradient(135deg, #0dcaf0 0%, #087990 100%) !important; }
    .bg-warning { background: linear-gradient(135deg, #ffc107 0%, #cc9a06 100%) !important; }
    .bg-danger { background: linear-gradient(135deg, #dc3545 0%, #b02a37 100%) !important; }
    .bg-dark { background: linear-gradient(135deg, #212529 0%, #1a1e21 100%) !important; }
    
    .chart-container {
        position: relative;
        height: 300px;
    }
    
    .loading-spinner {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 200px;
    }
    
    .table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.875rem;
        letter-spacing: 0.5px;
    }
    
    .alert {
        border: none;
        border-radius: 10px;
    }
    
    .btn {
        border-radius: 8px;
        font-weight: 500;
    }
    
    .form-control, .form-select {
        border-radius: 8px;
        border: 1px solid #dee2e6;
        transition: all 0.15s ease-in-out;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }
    
    .shadow-sm {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
    }
    
    .border-end {
        border-right: 1px solid #dee2e6 !important;
    }
    
    @media (max-width: 768px) {
        .border-end {
            border-right: none !important;
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-4">
    <!-- En-tête des statistiques -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="h3 text-dark mb-0">Statistiques</h2>
                    <p class="text-muted">Analysez vos performances et suivez l'évolution de votre activité</p>
                </div>
                <div>
                    <a href="{{ route('dashboard.producteur') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Retour au tableau de bord
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres de période -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="row align-items-end">
                        <div class="col-lg-3 col-md-6 mb-3">
                            <label class="form-label fw-semibold">Période</label>
                            <select class="form-select" id="periode-filter">
                                <option value="7">7 derniers jours</option>
                                <option value="30" selected>30 derniers jours</option>
                                <option value="90">3 derniers mois</option>
                                <option value="365">Dernière année</option>
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <label class="form-label fw-semibold">Date de début</label>
                            <input type="date" class="form-control" id="date-debut">
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <label class="form-label fw-semibold">Date de fin</label>
                            <input type="date" class="form-control" id="date-fin">
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <button class="btn btn-primary w-100" id="appliquer-filtres">
                                <i class="fas fa-filter me-2"></i>Appliquer
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- KPIs principaux -->
    <div class="row mb-4">
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card bg-primary text-white h-100 border-0">
                <div class="card-body text-center">
                    <i class="fas fa-box fa-2x mb-3 opacity-75"></i>
                    <h4 class="card-title mb-1 fw-bold" id="kpi-total-produits">{{ $stats->total_produits ?? 0 }}</h4>
                    <small class="text-white-50 text-uppercase">Produits totaux</small>
                </div>
            </div>
        </div>

        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card bg-success text-white h-100 border-0">
                <div class="card-body text-center">
                    <i class="fas fa-shopping-cart fa-2x mb-3 opacity-75"></i>
                    <h4 class="card-title mb-1 fw-bold" id="kpi-commandes">0</h4>
                    <small class="text-white-50 text-uppercase">Commandes</small>
                </div>
            </div>
        </div>

        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card bg-info text-white h-100 border-0">
                <div class="card-body text-center">
                    <i class="fas fa-coins fa-2x mb-3 opacity-75"></i>
                    <h4 class="card-title mb-1 fw-bold" id="kpi-chiffre-affaires">0 FCFA</h4>
                    <small class="text-white-50 text-uppercase">Chiffre d'affaires</small>
                </div>
            </div>
        </div>

        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card bg-warning text-white h-100 border-0">
                <div class="card-body text-center">
                    <i class="fas fa-eye fa-2x mb-3 opacity-75"></i>
                    <h4 class="card-title mb-1 fw-bold" id="kpi-vues">0</h4>
                    <small class="text-white-50 text-uppercase">Vues produits</small>
                </div>
            </div>
        </div>

        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card bg-danger text-white h-100 border-0">
                <div class="card-body text-center">
                    <i class="fas fa-percentage fa-2x mb-3 opacity-75"></i>
                    <h4 class="card-title mb-1 fw-bold" id="kpi-taux-conversion">0%</h4>
                    <small class="text-white-50 text-uppercase">Taux conversion</small>
                </div>
            </div>
        </div>

        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card bg-dark text-white h-100 border-0">
                <div class="card-body text-center">
                    <i class="fas fa-star fa-2x mb-3 opacity-75"></i>
                    <h4 class="card-title mb-1 fw-bold" id="kpi-note-moyenne">0/5</h4>
                    <small class="text-white-50 text-uppercase">Note moyenne</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert pour les erreurs -->
    <div id="error-alert" class="alert alert-danger alert-dismissible fade" role="alert" style="display: none;">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <span id="error-message"></span>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>

    <!-- Graphiques -->
    <div class="row mb-4">
        <!-- Évolution des ventes -->
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="card-title mb-0 fw-semibold">
                        <i class="fas fa-chart-line text-primary me-2"></i>Évolution des ventes
                    </h5>
                </div>
                <div class="card-body">
                    <div id="ventes-loading" class="loading-spinner">
                        <div class="text-center">
                            <i class="fas fa-spinner fa-spin fa-2x text-muted"></i>
                            <p class="mt-2 text-muted mb-0">Chargement des données...</p>
                        </div>
                    </div>
                    <div class="chart-container">
                        <canvas id="ventesChart" style="display: none;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top produits -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="card-title mb-0 fw-semibold">
                        <i class="fas fa-medal text-warning me-2"></i>Top produits
                    </h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="topProduitsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <!-- Répartition des catégories -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="card-title mb-0 fw-semibold">
                        <i class="fas fa-chart-pie text-info me-2"></i>Répartition par catégories
                    </h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="categoriesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Évolution du stock -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="card-title mb-0 fw-semibold">
                        <i class="fas fa-boxes text-success me-2"></i>Évolution du stock
                    </h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="stockChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableaux détaillés -->
    <div class="row">
        <!-- Produits les plus vendus -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="card-title mb-0 fw-semibold">
                        <i class="fas fa-fire text-danger me-2"></i>Produits les plus vendus
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0 px-4 py-3">Produit</th>
                                    <th class="border-0 px-4 py-3">Quantité vendue</th>
                                    <th class="border-0 px-4 py-3">CA généré</th>
                                </tr>
                            </thead>
                            <tbody id="top-ventes-table">
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">
                                        <i class="fas fa-spinner fa-spin me-2"></i>Chargement des données...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Produits en rupture de stock -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="card-title mb-0 fw-semibold">
                        <i class="fas fa-exclamation-triangle text-warning me-2"></i>Alertes stock
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0 px-4 py-3">Produit</th>
                                    <th class="border-0 px-4 py-3">Stock actuel</th>
                                    <th class="border-0 px-4 py-3">Statut</th>
                                </tr>
                            </thead>
                            <tbody id="alertes-stock-table">
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">
                                        <i class="fas fa-spinner fa-spin me-2"></i>Chargement des données...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Résumé mensuel -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="card-title mb-0 fw-semibold">
                        <i class="fas fa-calendar-alt text-primary me-2"></i>Résumé mensuel
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-lg-3 col-md-6 mb-3">
                            <div class="border-end pe-3">
                                <h4 class="text-primary mb-1 fw-bold" id="resume-commandes">0</h4>
                                <small class="text-muted text-uppercase">Commandes ce mois</small>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <div class="border-end pe-3">
                                <h4 class="text-success mb-1 fw-bold" id="resume-ca">0 FCFA</h4>
                                <small class="text-muted text-uppercase">CA ce mois</small>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <div class="border-end pe-3">
                                <h4 class="text-info mb-1 fw-bold" id="resume-clients">0</h4>
                                <small class="text-muted text-uppercase">Nouveaux clients</small>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <h4 class="text-warning mb-1 fw-bold" id="resume-croissance">+0%</h4>
                            <small class="text-muted text-uppercase">Croissance vs mois dernier</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Bootstrap JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<!-- Chart.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Configuration des graphiques
    Chart.defaults.font.family = "'Segoe UI', 'Roboto', sans-serif";
    Chart.defaults.color = '#6c757d';
    
    // Variables pour stocker les instances des graphiques
    let ventesChart, topProduitsChart, categoriesChart, stockChart;
    
    // Initialiser les graphiques
    initCharts();
    
    // Charger les données initiales
    loadStatistiques();
    
    // Event listeners
    document.getElementById('appliquer-filtres').addEventListener('click', function() {
        this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Chargement...';
        this.disabled = true;
        
        loadStatistiques().finally(() => {
            this.innerHTML = '<i class="fas fa-filter me-2"></i>Appliquer';
            this.disabled = false;
        });
    });
    
    document.getElementById('periode-filter').addEventListener('change', function() {
        const periode = this.value;
        const dateDebut = new Date();
        dateDebut.setDate(dateDebut.getDate() - parseInt(periode));
        
        document.getElementById('date-debut').value = dateDebut.toISOString().split('T')[0];
        document.getElementById('date-fin').value = new Date().toISOString().split('T')[0];
        
        loadStatistiques();
    });
    
    function initCharts() {
        // Graphique des ventes
        const ventesCtx = document.getElementById('ventesChart').getContext('2d');
        ventesChart = new Chart(ventesCtx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Chiffre d\'affaires',
                    data: [],
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13, 110, 253, 0.1)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 2
                }, {
                    label: 'Nombre de commandes',
                    data: [],
                    borderColor: '#198754',
                    backgroundColor: 'rgba(25, 135, 84, 0.1)',
                    fill: false,
                    yAxisID: 'y1',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: {
                            display: true,
                            text: 'Chiffre d\'affaires (FCFA)',
                            font: {
                                weight: 'bold'
                            }
                        },
                        grid: {
                            color: 'rgba(0,0,0,0.1)'
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Nombre de commandes',
                            font: {
                                weight: 'bold'
                            }
                        },
                        grid: {
                            drawOnChartArea: false,
                        }
                    }
                }
            }
        });

        // Graphique des top produits
        const topProduitsCtx = document.getElementById('topProduitsChart').getContext('2d');
        topProduitsChart = new Chart(topProduitsCtx, {
            type: 'doughnut',
            data: {
                labels: [],
                datasets: [{
                    data: [],
                    backgroundColor: [
                        '#0d6efd',
                        '#198754',
                        '#ffc107',
                        '#dc3545',
                        '#0dcaf0',
                        '#6f42c1'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        });

        // Graphique des catégories
        const categoriesCtx = document.getElementById('categoriesChart').getContext('2d');
        categoriesChart = new Chart(categoriesCtx, {
            type: 'pie',
            data: {
                labels: [],
                datasets: [{
                    data: [],
                    backgroundColor: [
                        '#0d6efd',
                        '#198754',
                        '#ffc107',
                        '#dc3545',
                        '#0dcaf0'
                    ],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        });

        // Graphique du stock
        const stockCtx = document.getElementById('stockChart').getContext('2d');
        stockChart = new Chart(stockCtx, {
            type: 'bar',
            data: {
                labels: [],
                datasets: [{
                    label: 'Stock disponible',
                    data: [],
                    backgroundColor: '#198754',
                    borderColor: '#146c43',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Quantité',
                            font: {
                                weight: 'bold'
                            }
                        }
                    }
                }
            }
        });
    }
    
    function loadStatistiques() {
        // Simuler le chargement des données
        return new Promise((resolve) => {
            setTimeout(() => {
                // Masquer le spinner et afficher les graphiques
                document.getElementById('ventes-loading').style.display = 'none';
                document.getElementById('ventesChart').style.display = 'block';
                
                // Mettre à jour les KPIs avec des données d'exemple
                updateKPIs();
                
                // Mettre à jour les graphiques avec des données d'exemple
                updateCharts();
                
                // Mettre à jour les tableaux
                updateTables();
                
                resolve();
            }, 1500);
        });
    }
    
    function updateKPIs() {
        document.getElementById('kpi-commandes').textContent = '125';
        document.getElementById('kpi-chiffre-affaires').textContent = '2,450,000 FCFA';
        document.getElementById('kpi-vues').textContent = '1,847';
        document.getElementById('kpi-taux-conversion').textContent = '6.8%';
        document.getElementById('kpi-note-moyenne').textContent = '4.3/5';
    }
    
    function updateCharts() {
        // Données d'exemple pour les graphiques
        const ventesLabels = ['Sem 1', 'Sem 2', 'Sem 3', 'Sem 4'];
        const ventesData = [450000, 620000, 580000, 800000];
        const commandesData = [25, 32, 28, 40];
        
        ventesChart.data.labels = ventesLabels;
        ventesChart.data.datasets[0].data = ventesData;
        ventesChart.data.datasets[1].data = commandesData;
        ventesChart.update();
        
        // Top produits
        topProduitsChart.data.labels = ['Tomates', 'Oignons', 'Pommes de terre', 'Carottes'];
        topProduitsChart.data.datasets[0].data = [30, 25, 20, 15];
        topProduitsChart.update();
        
        // Catégories
        categoriesChart.data.labels = ['Légumes', 'Fruits', 'Céréales', 'Épices'];
        categoriesChart.data.datasets[0].data = [40, 30, 20, 10];
        categoriesChart.update();
        
        // Stock
        stockChart.data.labels = ['Tomates', 'Oignons', 'Carottes', 'Pommes de terre'];
        stockChart.data.datasets[0].data = [150, 200, 80, 120];
        stockChart.update();
    }
    
    function updateTables() {
        // Tableau des top ventes
        const topVentesHTML = `
            <tr>
                <td class="px-4 py-3"><strong>Tomates</strong></td>
                <td class="px-4 py-3">150 kg</td>
                <td class="px-4 py-3 text-success fw-semibold">750,000 FCFA</td>
            </tr>
            <tr>
                <td class="px-4 py-3"><strong>Oignons</strong></td>
                <td class="px-4 py-3">120 kg</td>
                <td class="px-4 py-3 text-success fw-semibold">480,000 FCFA</td>
            </tr>
            <tr>
                <td class="px-4 py-3"><strong>Carottes</strong></td>
                <td class="px-4 py-3">80 kg</td>
                <td class="px-4 py-3 text-success fw-semibold">320,000 FCFA</td>
            </tr>
        `;
        document.getElementById('top-ventes-table').innerHTML = topVentesHTML;
        
        // Tableau des alertes stock
        const alertesStockHTML = `
            <tr>
                <td class="px-4 py-3"><strong>Pommes de terre</strong></td>
                <td class="px-4 py-3">15 kg</td>
                <td class="px-4 py-3"><span class="badge bg-danger">Stock faible</span></td>
            </tr>
            <tr>
                <td class="px-4 py-3"><strong>Épinards</strong></td>
                <td class="px-4 py-3">0 kg</td>
                <td class="px-4 py-3"><span class="badge bg-dark">Rupture</span></td>
            </tr>
        `;
        document.getElementById('alertes-stock-table').innerHTML = alertesStockHTML;
    }
    
    // Initialiser les dates par défaut
    const dateDebut = new Date();
    dateDebut.setDate(dateDebut.getDate() - 30);
    document.getElementById('date-debut').value = dateDebut.toISOString().split('T')[0];
    document.getElementById('date-fin').value = new Date().toISOString().split('T')[0];
});
</script>
@endsection