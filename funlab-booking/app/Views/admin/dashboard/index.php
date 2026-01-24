<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - FunLab</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <style>
        .stat-card {
            transition: transform 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        .recent-booking {
            border-left: 3px solid;
            padding-left: 15px;
            margin-bottom: 15px;
        }
        .status-pending { border-left-color: #ffc107; }
        .status-confirmed { border-left-color: #28a745; }
        .status-completed { border-left-color: #6c757d; }
        .status-cancelled { border-left-color: #dc3545; }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="d-flex">
        <div class="bg-dark text-white p-3" style="width: 250px; min-height: 100vh;">
            <h4 class="mb-4">
                <i class="bi bi-speedometer2"></i> Admin FunLab
            </h4>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link text-white active" href="<?= base_url('admin/dashboard') ?>">
                        <i class="bi bi-house"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="<?= base_url('admin/bookings') ?>">
                        <i class="bi bi-calendar-check"></i> Réservations
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="<?= base_url('admin/rooms') ?>">
                        <i class="bi bi-door-closed"></i> Salles
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="<?= base_url('admin/games') ?>">
                        <i class="bi bi-controller"></i> Jeux
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="<?= base_url('admin/closures') ?>">
                        <i class="bi bi-x-circle"></i> Fermetures
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="<?= base_url('admin/scanner') ?>">
                        <i class="bi bi-qr-code-scan"></i> Scanner
                    </a>
                </li>
                <hr class="text-white">
                <li class="nav-item">
                    <a class="nav-link text-white" href="<?= base_url('/') ?>">
                        <i class="bi bi-box-arrow-left"></i> Retour au site
                    </a>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="flex-grow-1">
            <nav class="navbar navbar-light bg-light border-bottom">
                <div class="container-fluid">
                    <span class="navbar-brand mb-0 h1">Dashboard</span>
                    <span class="text-muted">
                        <i class="bi bi-person-circle"></i> Admin | <span id="current-time"></span>
                    </span>
                </div>
            </nav>

            <div class="container-fluid p-4">
                <!-- Statistiques en temps réel -->
                <div class="row g-4 mb-4">
                    <div class="col-md-3">
                        <div class="card text-white bg-primary stat-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title text-uppercase">Aujourd'hui</h6>
                                        <h2 class="mb-0" id="stat-today">--</h2>
                                        <small>réservations</small>
                                    </div>
                                    <i class="bi bi-calendar-check" style="font-size: 3rem; opacity: 0.3;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-success stat-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title text-uppercase">En cours</h6>
                                        <h2 class="mb-0" id="stat-active">--</h2>
                                        <small>sessions</small>
                                    </div>
                                    <i class="bi bi-play-circle" style="font-size: 3rem; opacity: 0.3;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-info stat-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title text-uppercase">Terminées</h6>
                                        <h2 class="mb-0" id="stat-completed">--</h2>
                                        <small>aujourd'hui</small>
                                    </div>
                                    <i class="bi bi-check-circle" style="font-size: 3rem; opacity: 0.3;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-warning stat-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title text-uppercase">Revenus</h6>
                                        <h2 class="mb-0" id="stat-revenue">--</h2>
                                        <small>DT aujourd'hui</small>
                                    </div>
                                    <i class="bi bi-cash-stack" style="font-size: 3rem; opacity: 0.3;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Graphiques et données -->
                <div class="row g-4 mb-4">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="bi bi-bar-chart"></i> Réservations des 7 derniers jours</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="bookingsChart" height="100"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="bi bi-pie-chart"></i> Répartition par jeu</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="gamesChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Réservations récentes et prochaines -->
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0"><i class="bi bi-clock-history"></i> Prochaines réservations</h5>
                                <span class="badge bg-primary" id="upcoming-count">0</span>
                            </div>
                            <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                                <div id="upcoming-bookings">
                                    <div class="text-center text-muted py-4">
                                        <div class="spinner-border" role="status"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0"><i class="bi bi-list-ul"></i> Réservations récentes</h5>
                                <span class="badge bg-secondary" id="recent-count">0</span>
                            </div>
                            <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                                <div id="recent-bookings">
                                    <div class="text-center text-muted py-4">
                                        <div class="spinner-border" role="status"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="alert alert-info mt-4">
                    <h5><i class="bi bi-info-circle"></i> Accès rapides</h5>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="<?= base_url('admin/bookings') ?>" class="btn btn-outline-primary">
                            <i class="bi bi-calendar"></i> Gérer les réservations
                        </a>
                        <a href="<?= base_url('admin/scanner') ?>" class="btn btn-outline-success">
                            <i class="bi bi-qr-code-scan"></i> Ouvrir le scanner
                        </a>
                        <a href="<?= base_url('admin/closures') ?>" class="btn btn-outline-warning">
                            <i class="bi bi-x-circle"></i> Gérer les fermetures
                        </a>
                        <a href="<?= base_url('booking') ?>" class="btn btn-outline-info" target="_blank">
                            <i class="bi bi-plus-circle"></i> Nouvelle réservation
                        </a>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Liens rapides</h5>
                    </div>
                    <div class="card-body">
                        <ul>
                            <li><a href="<?= base_url('availability-example.html') ?>" target="_blank">Démo API Disponibilité</a></li>
                            <li><a href="<?= base_url('AVAILABILITY_API.md') ?>" target="_blank">Documentation API</a></li>
                            <li><a href="<?= base_url('QUICK_START.md') ?>" target="_blank">Guide de démarrage</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const API_BASE_URL = '/api';
        let bookingsChart, gamesChart;

        // Initialisation
        document.addEventListener('DOMContentLoaded', function() {
            updateTime();
            setInterval(updateTime, 1000);
            
            initCharts();
            loadRecentBookings();
            
            // Rafraîchir les données toutes les 30 secondes
            setInterval(loadRecentBookings, 30000);
        });

        function updateTime() {
            const now = new Date();
            document.getElementById('current-time').textContent = 
                now.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });
        }
        function updateStats(stats) {
            document.getElementById('stat-today').textContent = stats.total_bookings || 0;
            document.getElementById('stat-active').textContent = stats.in_progress || 0;
            document.getElementById('stat-completed').textContent = stats.completed || 0;
            
            // Calculer le revenu approximatif (basé sur un prix moyen)
            const avgPrice = 25; // DT par personne (à ajuster)
            const revenue = (stats.total_participants || 0) * avgPrice;
            document.getElementById('stat-revenue').textContent = revenue;
        }

        function displayUpcomingBookings(bookings) {
            const container = document.getElementById('upcoming-bookings');
            document.getElementById('upcoming-count').textContent = bookings.length;

            if (bookings.length === 0) {
                container.innerHTML = '<p class="text-muted text-center py-4">Aucune réservation à venir</p>';
                return;
            }

            container.innerHTML = bookings.map(b => `
                <div class="recent-booking status-confirmed mb-3">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <strong>${b.customer_name}</strong>
                            <br><small class="text-muted">${b.game_name}</small>
                            <br><small><i class="bi bi-clock"></i> ${b.start_time}</small>
                        </div>
                        <span class="badge bg-success">${b.num_players} joueurs</span>
                    </div>
                </div>
            `).join('');
        }

        async function loadRecentBookings() {
            try {
                const response = await fetch(`${API_BASE_URL}/../admin/dashboard/stats`);
                const result = await response.json();
                
                if (result.status === 'success') {
                    updateStats(result.data);
                    displayRecentBookings(result.data.recentBookings);
                    updateCharts(result.data);
                }
            } catch (error) {
                console.error('Erreur chargement statistiques:', error);
            }
        }

        function updateStats(data) {
            document.getElementById('stat-today').textContent = data.today || 0;
            document.getElementById('stat-active').textContent = data.active || 0;
            document.getElementById('stat-completed').textContent = data.completed || 0;
            document.getElementById('stat-revenue').textContent = data.revenue || 0;
        }

        function displayRecentBookings(bookings) {
            const container = document.getElementById('recent-bookings');
            
            if (!bookings || bookings.length === 0) {
                container.innerHTML = '<p class="text-muted text-center py-4">Aucune réservation récente</p>';
                document.getElementById('recent-count').textContent = '0';
                return;
            }

            document.getElementById('recent-count').textContent = bookings.length;

            const statusClasses = {
                'pending': 'status-pending',
                'confirmed': 'status-confirmed',
                'in_progress': 'status-confirmed',
                'completed': 'status-completed',
                'cancelled': 'status-cancelled'
            };

            const statusLabels = {
                'pending': 'En attente',
                'confirmed': 'Confirmé',
                'in_progress': 'En cours',
                'completed': 'Terminé',
                'cancelled': 'Annulé'
            };

            container.innerHTML = bookings.map(b => `
                <div class="recent-booking ${statusClasses[b.status] || 'status-confirmed'} mb-3">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <strong>${b.customer_name}</strong>
                            <br><small class="text-muted">${b.game_name}</small>
                            <br><small><i class="bi bi-clock"></i> ${b.start_time ? b.start_time.substring(0,5) : 'N/A'}</small>
                            <br><small class="badge bg-secondary">${statusLabels[b.status]}</small>
                        </div>
                        <span class="badge bg-success">${b.num_players || 0} joueurs</span>
                    </div>
                </div>
            `).join('');
        }

        function updateCharts(data) {
            // Mettre à jour le graphique des réservations
            if (bookingsChart && data.last7Days) {
                bookingsChart.data.labels = data.last7Days.map(d => d.date);
                bookingsChart.data.datasets[0].data = data.last7Days.map(d => d.count);
                bookingsChart.update();
            }

            // Mettre à jour le graphique des jeux
            if (gamesChart && data.gamesStats && data.gamesStats.length > 0) {
                gamesChart.data.labels = data.gamesStats.map(g => g.name);
                gamesChart.data.datasets[0].data = data.gamesStats.map(g => g.count);
                gamesChart.update();
            }
        }

        function initCharts() {
            // Graphique des réservations sur 7 jours
            const ctx1 = document.getElementById('bookingsChart');
            bookingsChart = new Chart(ctx1, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Réservations',
                        data: [],
                        borderColor: '#667eea',
                        backgroundColor: 'rgba(102, 126, 234, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 5
                            }
                        }
                    }
                }
            });

            // Graphique circulaire des jeux
            const ctx2 = document.getElementById('gamesChart');
            gamesChart = new Chart(ctx2, {
                type: 'doughnut',
                data: {
                    labels: [],
                    datasets: [{
                        data: [],
                        backgroundColor: [
                            '#667eea',
                            '#764ba2',
                            '#f093fb'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }

        function getLast7Days() {
            const days = [];
            const today = new Date();
            
            for (let i = 6; i >= 0; i--) {
                const date = new Date(today);
                date.setDate(date.getDate() - i);
                days.push(date.toLocaleDateString('fr-FR', { 
                    weekday: 'short', 
                    day: 'numeric' 
                }));
            }
            
            return days;
        }
    </script>
</body>
</html>
