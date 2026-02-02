<?php
$title = 'Mon compte - FunLab Tunisie';
$activeMenu = 'account';
$additionalStyles = <<<CSS
<style>
body {
    background: #f8f9fa;
}
.account-content {
    padding-top: 60px;
}
.sidebar {
    background: white;
    border-radius: 15px;
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
    color: var(--primary-color);
}
.sidebar .nav-link.active {
    background: var(--primary-color) !important;
    color: white !important;
}
.content-card {
    background: white;
    border-radius: 15px;
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
.stat-card {
    background: var(--primary-color);
    color: white;
    border-radius: 15px;
    padding: 20px;
    margin-bottom: 20px;
}
.booking-card {
    border: 1px solid #dee2e6;
    border-radius: 15px;
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
</style>
CSS;
?>

<?= view('front/layouts/header', compact('title', 'additionalStyles')) ?>
<?= view('front/layouts/navbar', compact('activeMenu')) ?>

    <div class="container account-content">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3">
                <div class="sidebar">
                    <div class="text-center mb-4">
                        <?php if (session()->get('avatar')): ?>
                            <img src="<?= session()->get('avatar') ?>" alt="Avatar" class="user-avatar">
                        <?php else: ?>
                            <div class="user-avatar bg-primary d-flex align-items-center justify-content-center">
                                <i class="bi bi-person fs-2 text-white"></i>
                            </div>
                        <?php endif; ?>
                        <h5 class="mt-3 mb-0">
                            <?= session()->get('firstName') ?> <?= session()->get('lastName') ?>
                        </h5>
                        <small class="text-muted"><?= session()->get('email') ?></small>
                    </div>

                    <nav class="nav flex-column">
                        <a class="nav-link active" href="<?= base_url('account') ?>">
                            <i class="bi bi-speedometer2"></i> Tableau de bord
                        </a>
                        <a class="nav-link" href="<?= base_url('account/profile') ?>">
                            <i class="bi bi-person"></i> Mon profil
                        </a>
                        <a class="nav-link" href="<?= base_url('account/bookings') ?>">
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

                <!-- Stats Cards -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="stat-card">
                            <h3 class="mb-0"><?= $stats['total_bookings'] ?? 0 ?></h3>
                            <p class="mb-0">
                                <i class="bi bi-calendar-check"></i> Réservations totales
                            </p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card">
                            <h3 class="mb-0"><?= $stats['upcoming_bookings'] ?? 0 ?></h3>
                            <p class="mb-0">
                                <i class="bi bi-clock"></i> À venir
                            </p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card">
                            <h3 class="mb-0"><?= $stats['completed_bookings'] ?? 0 ?></h3>
                            <p class="mb-0">
                                <i class="bi bi-check2-circle"></i> Complétées
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Recent Bookings -->
                <div class="content-card">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4><i class="bi bi-calendar-event"></i> Réservations récentes</h4>
                        <a href="<?= base_url('account/bookings') ?>" class="btn btn-sm btn-outline-primary">
                            Voir toutes
                        </a>
                    </div>

                    <?php if (empty($recent_bookings)): ?>
                        <div class="text-center py-5">
                            <i class="bi bi-calendar-x display-1 text-muted"></i>
                            <h5 class="mt-3 text-muted">Aucune réservation</h5>
                            <p class="text-muted">Commencez par réserver une activité !</p>
                            <a href="<?= base_url('booking') ?>" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Nouvelle réservation
                            </a>
                        </div>
                    <?php else: ?>
                        <?php foreach ($recent_bookings as $booking): ?>
                            <div class="booking-card">
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <h6 class="mb-2">
                                            <i class="bi bi-controller"></i>
                                            <?= esc($booking['game_name']) ?>
                                        </h6>
                                        <p class="text-muted mb-1">
                                            <i class="bi bi-calendar3"></i>
                                            <?= date('d/m/Y', strtotime($booking['booking_date'])) ?>
                                            à <?= date('H:i', strtotime($booking['start_time'])) ?>
                                        </p>
                                        <p class="text-muted mb-0">
                                            <i class="bi bi-people"></i>
                                            <?= $booking['num_players'] ?> participants
                                        </p>
                                    </div>
                                    <div class="col-md-4 text-md-end">
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
                                        ?>
                                        <span class="badge badge-status bg-<?= $color ?>"><?= $label ?></span>
                                        <br>
                                        <a href="<?= base_url('account/bookings/' . $booking['id']) ?>" 
                                           class="btn btn-sm btn-outline-primary mt-2">
                                            Détails
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

<?= view('front/layouts/footer') ?>
