<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - FunLab</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
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
                        <i class="bi bi-person-circle"></i> Admin
                    </span>
                </div>
            </nav>

            <div class="container-fluid p-4">
                <div class="row g-4 mb-4">
                    <div class="col-md-3">
                        <div class="card text-white bg-primary">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title">Réservations</h6>
                                        <h2 class="mb-0">0</h2>
                                    </div>
                                    <i class="bi bi-calendar-check" style="font-size: 3rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-success">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title">Revenus</h6>
                                        <h2 class="mb-0">0 DT</h2>
                                    </div>
                                    <i class="bi bi-cash-stack" style="font-size: 3rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-info">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title">Salles</h6>
                                        <h2 class="mb-0">5</h2>
                                    </div>
                                    <i class="bi bi-door-closed" style="font-size: 3rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-warning">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title">Jeux</h6>
                                        <h2 class="mb-0">6</h2>
                                    </div>
                                    <i class="bi bi-controller" style="font-size: 3rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="alert alert-success">
                    <h5><i class="bi bi-check-circle"></i> Système Opérationnel</h5>
                    <p class="mb-0">L'Availability Engine est fonctionnel. Vous pouvez créer des réservations via l'API.</p>
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
</body>
</html>
