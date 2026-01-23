<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes réservations - FunLab Tunisie</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background: #f8f9fa; }
        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .sidebar {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            padding: 20px;
        }
        .sidebar .nav-link {
            color: #333;
            padding: 12px 15px;
            margin-bottom: 5px;
            border-radius: 8px;
            transition: all 0.3s;
        }
        .sidebar .nav-link:hover {
            background: #f8f9fa;
            color: #667eea;
        }
        .sidebar .nav-link.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .content-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            padding: 30px;
        }
        .user-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .booking-card {
            border: 1px solid #dee2e6;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 15px;
            transition: all 0.3s;
        }
        .booking-card:hover {
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }
        .badge-status {
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 12px;
        }
        .filter-tabs .nav-link {
            color: #666;
            border: none;
            border-bottom: 2px solid transparent;
            padding: 10px 20px;
        }
        .filter-tabs .nav-link.active {
            color: #667eea;
            border-bottom-color: #667eea;
            background: transparent;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="<?= base_url('/') ?>">
                <i class="bi bi-joystick"></i> FunLab Tunisie
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('/') ?>">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('booking') ?>">Réserver</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i>
                            <?= session()->get('firstName') ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?= base_url('account') ?>">Mon compte</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('account/bookings') ?>">Mes réservations</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?= base_url('auth/logout') ?>">Déconnexion</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3">
                <div class="sidebar">
                    <div class="text-center mb-4">
                        <?php if (session()->get('avatar')): ?>
                            <img src="<?= session()->get('avatar') ?>" alt="Avatar" class="user-avatar">
                        <?php else: ?>
                            <div class="user-avatar bg-primary d-flex align-items-center justify-content-center mx-auto">
                                <i class="bi bi-person fs-2 text-white"></i>
                            </div>
                        <?php endif; ?>
                        <h5 class="mt-3 mb-0">
                            <?= session()->get('firstName') ?> <?= session()->get('lastName') ?>
                        </h5>
                        <small class="text-muted"><?= session()->get('email') ?></small>
                    </div>

                    <nav class="nav flex-column">
                        <a class="nav-link" href="<?= base_url('account') ?>">
                            <i class="bi bi-speedometer2"></i> Tableau de bord
                        </a>
                        <a class="nav-link" href="<?= base_url('account/profile') ?>">
                            <i class="bi bi-person"></i> Mon profil
                        </a>
                        <a class="nav-link active" href="<?= base_url('account/bookings') ?>">
                            <i class="bi bi-calendar-check"></i> Mes réservations
                        </a>
                        <a class="nav-link" href="<?= base_url('account/password') ?>">
                            <i class="bi bi-key"></i> Mot de passe
                        </a>
                        <hr>
                        <a class="nav-link text-danger" href="<?= base_url('auth/logout') ?>">
                            <i class="bi bi-box-arrow-right"></i> Déconnexion
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Content -->
            <div class="col-lg-9">
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="bi bi-check-circle"></i>
                        <?= session()->getFlashdata('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="content-card">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4><i class="bi bi-calendar-event"></i> Mes réservations</h4>
                        <a href="<?= base_url('booking/create') ?>" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Nouvelle réservation
                        </a>
                    </div>

                    <!-- Filtres -->
                    <ul class="nav filter-tabs mb-4" id="statusFilter">
                        <li class="nav-item">
                            <a class="nav-link active" data-filter="all" href="#">Toutes</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-filter="upcoming" href="#">À venir</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-filter="completed" href="#">Complétées</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-filter="cancelled" href="#">Annulées</a>
                        </li>
                    </ul>

                    <?php if (empty($bookings)): ?>
                        <div class="text-center py-5">
                            <i class="bi bi-calendar-x display-1 text-muted"></i>
                            <h5 class="mt-3 text-muted">Aucune réservation</h5>
                            <p class="text-muted">Vous n'avez pas encore effectué de réservation</p>
                            <a href="<?= base_url('booking/create') ?>" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Réserver maintenant
                            </a>
                        </div>
                    <?php else: ?>
                        <div id="bookingsList">
                            <?php foreach ($bookings as $booking): ?>
                                <?php
                                $statusColors = [
                                    'confirmed' => 'success',
                                    'pending' => 'warning',
                                    'completed' => 'info',
                                    'cancelled' => 'danger'
                                ];
                                $statusLabels = [
                                    'confirmed' => 'Confirmée',
                                    'pending' => 'En attente',
                                    'completed' => 'Complétée',
                                    'cancelled' => 'Annulée'
                                ];
                                $color = $statusColors[$booking['status']] ?? 'secondary';
                                $label = $statusLabels[$booking['status']] ?? $booking['status'];
                                
                                $bookingDate = strtotime($booking['booking_date']);
                                $isUpcoming = $bookingDate >= strtotime('today') && in_array($booking['status'], ['confirmed', 'pending']);
                                ?>
                                <div class="booking-card" data-status="<?= $booking['status'] ?>" 
                                     data-upcoming="<?= $isUpcoming ? 'true' : 'false' ?>">
                                    <div class="row align-items-center">
                                        <div class="col-md-8">
                                            <h6 class="mb-2">
                                                <i class="bi bi-controller text-primary"></i>
                                                <strong><?= esc($booking['game_name']) ?></strong>
                                            </h6>
                                            <p class="text-muted mb-1">
                                                <i class="bi bi-geo-alt"></i>
                                                Salle : <?= esc($booking['room_name']) ?>
                                            </p>
                                            <p class="text-muted mb-1">
                                                <i class="bi bi-calendar3"></i>
                                                <?= strftime('%A %d %B %Y', $bookingDate) ?>
                                                à <?= date('H:i', strtotime($booking['start_time'])) ?>
                                            </p>
                                            <p class="text-muted mb-0">
                                                <i class="bi bi-people"></i>
                                                <?= $booking['num_participants'] ?> participants
                                                <span class="mx-2">|</span>
                                                <i class="bi bi-clock"></i>
                                                <?= $booking['duration'] ?> minutes
                                            </p>
                                        </div>
                                        <div class="col-md-4 text-md-end mt-3 mt-md-0">
                                            <span class="badge badge-status bg-<?= $color ?> d-block mb-2">
                                                <?= $label ?>
                                            </span>
                                            <a href="<?= base_url('account/bookings/' . $booking['id']) ?>" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye"></i> Voir détails
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Filtrage des réservations
        document.querySelectorAll('.filter-tabs .nav-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Activer l'onglet
                document.querySelectorAll('.filter-tabs .nav-link').forEach(l => l.classList.remove('active'));
                this.classList.add('active');
                
                const filter = this.dataset.filter;
                const bookings = document.querySelectorAll('.booking-card');
                
                bookings.forEach(booking => {
                    const status = booking.dataset.status;
                    const isUpcoming = booking.dataset.upcoming === 'true';
                    
                    let show = false;
                    
                    if (filter === 'all') {
                        show = true;
                    } else if (filter === 'upcoming') {
                        show = isUpcoming;
                    } else {
                        show = status === filter;
                    }
                    
                    booking.style.display = show ? 'block' : 'none';
                });
            });
        });
    </script>
</body>
</html>
