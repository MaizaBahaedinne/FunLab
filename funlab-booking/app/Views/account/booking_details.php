<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de la réservation - FunLab Tunisie</title>
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
        .detail-row {
            border-bottom: 1px solid #e9ecef;
            padding: 15px 0;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .badge-status {
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 14px;
        }
        .qr-code {
            max-width: 250px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            border: 2px solid #dee2e6;
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand fw-bold" href="<?= base_url() ?>">
                <i class="bi bi-controller"></i> FunLab Tunisie
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url() ?>">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('account') ?>">Mon compte</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('auth/logout') ?>">Déconnexion</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3">
                <div class="sidebar">
                    <div class="text-center mb-4">
                        <img src="https://ui-avatars.com/api/?name=<?= urlencode(session()->get('userName') ?? 'User') ?>&size=80&background=667eea&color=fff" 
                             alt="Avatar" class="user-avatar">
                        <h6 class="mt-3 mb-0"><?= esc(session()->get('userName') ?? 'Utilisateur') ?></h6>
                        <small class="text-muted"><?= esc(session()->get('userEmail') ?? '') ?></small>
                    </div>
                    <nav class="nav flex-column">
                        <a class="nav-link" href="<?= base_url('account') ?>">
                            <i class="bi bi-speedometer2"></i> Tableau de bord
                        </a>
                        <a class="nav-link active" href="<?= base_url('account/bookings') ?>">
                            <i class="bi bi-calendar-check"></i> Mes réservations
                        </a>
                        <a class="nav-link" href="<?= base_url('account/profile') ?>">
                            <i class="bi bi-person"></i> Mon profil
                        </a>
                        <a class="nav-link" href="<?= base_url('account/change-password') ?>">
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
            <div class="col-md-9">
                <div class="content-card">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h4 class="mb-1">Détails de la réservation #<?= esc($booking['id']) ?></h4>
                            <p class="text-muted mb-0">Référence: <?= esc($booking['reference_number'] ?? 'N/A') ?></p>
                        </div>
                        <a href="<?= base_url('account/bookings') ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Retour
                        </a>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <!-- Statut -->
                            <div class="detail-row">
                                <div class="row">
                                    <div class="col-5 fw-bold">Statut</div>
                                    <div class="col-7">
                                        <?php
                                        $statusClass = [
                                            'confirmed' => 'bg-success',
                                            'pending' => 'bg-warning text-dark',
                                            'cancelled' => 'bg-danger',
                                            'completed' => 'bg-info',
                                            'in_progress' => 'bg-primary'
                                        ];
                                        $statusLabel = [
                                            'confirmed' => 'Confirmée',
                                            'pending' => 'En attente',
                                            'cancelled' => 'Annulée',
                                            'completed' => 'Terminée',
                                            'in_progress' => 'En cours'
                                        ];
                                        ?>
                                        <span class="badge badge-status <?= $statusClass[$booking['status']] ?? 'bg-secondary' ?>">
                                            <?= $statusLabel[$booking['status']] ?? 'Inconnu' ?>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Jeu -->
                            <div class="detail-row">
                                <div class="row">
                                    <div class="col-5 fw-bold">Jeu</div>
                                    <div class="col-7">
                                        <h6 class="mb-1"><?= esc($booking['game_name']) ?></h6>
                                        <?php if (!empty($booking['description'])): ?>
                                            <small class="text-muted"><?= esc($booking['description']) ?></small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Salle -->
                            <div class="detail-row">
                                <div class="row">
                                    <div class="col-5 fw-bold">Salle</div>
                                    <div class="col-7"><?= esc($booking['room_name']) ?></div>
                                </div>
                            </div>

                            <!-- Date et horaire -->
                            <div class="detail-row">
                                <div class="row">
                                    <div class="col-5 fw-bold">Date</div>
                                    <div class="col-7">
                                        <i class="bi bi-calendar3"></i>
                                        <?php
                                        $date = new DateTime($booking['booking_date']);
                                        echo $date->format('d/m/Y');
                                        ?>
                                    </div>
                                </div>
                            </div>

                            <div class="detail-row">
                                <div class="row">
                                    <div class="col-5 fw-bold">Horaire</div>
                                    <div class="col-7">
                                        <i class="bi bi-clock"></i>
                                        <?= date('H:i', strtotime($booking['start_time'])) ?> - 
                                        <?= date('H:i', strtotime($booking['end_time'])) ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Participants -->
                            <div class="detail-row">
                                <div class="row">
                                    <div class="col-5 fw-bold">Nombre de joueurs</div>
                                    <div class="col-7">
                                        <i class="bi bi-people"></i>
                                        <?= esc($booking['num_players']) ?> joueurs
                                    </div>
                                </div>
                            </div>

                            <!-- Coordonnées -->
                            <div class="detail-row">
                                <div class="row">
                                    <div class="col-5 fw-bold">Nom du client</div>
                                    <div class="col-7"><?= esc($booking['customer_name']) ?></div>
                                </div>
                            </div>

                            <div class="detail-row">
                                <div class="row">
                                    <div class="col-5 fw-bold">Email</div>
                                    <div class="col-7"><?= esc($booking['customer_email']) ?></div>
                                </div>
                            </div>

                            <div class="detail-row">
                                <div class="row">
                                    <div class="col-5 fw-bold">Téléphone</div>
                                    <div class="col-7"><?= esc($booking['customer_phone']) ?></div>
                                </div>
                            </div>

                            <!-- Paiement -->
                            <?php if (!empty($booking['payment_method'])): ?>
                            <div class="detail-row">
                                <div class="row">
                                    <div class="col-5 fw-bold">Mode de paiement</div>
                                    <div class="col-7">
                                        <?php
                                        $paymentLabels = [
                                            'card' => 'Carte bancaire',
                                            'cash' => 'Espèces sur place',
                                            'stripe' => 'Stripe',
                                            'bank_transfer' => 'Virement bancaire'
                                        ];
                                        echo $paymentLabels[$booking['payment_method']] ?? ucfirst($booking['payment_method']);
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>

                            <div class="detail-row">
                                <div class="row">
                                    <div class="col-5 fw-bold">Prix total</div>
                                    <div class="col-7">
                                        <h5 class="text-primary mb-0"><?= number_format($booking['total_price'], 2) ?> TND</h5>
                                    </div>
                                </div>
                            </div>

                            <!-- Date de création -->
                            <div class="detail-row">
                                <div class="row">
                                    <div class="col-5 fw-bold">Réservé le</div>
                                    <div class="col-7">
                                        <?php
                                        $createdAt = new DateTime($booking['created_at']);
                                        echo $createdAt->format('d/m/Y à H:i');
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- QR Code -->
                        <div class="col-md-4">
                            <?php if ($booking['status'] === 'confirmed' && !empty($booking['qr_code'])): ?>
                            <div class="qr-code text-center">
                                <h6 class="mb-3">QR Code</h6>
                                <img src="<?= esc($booking['qr_code']) ?>" alt="QR Code" class="img-fluid">
                                <p class="text-muted small mt-3">Présentez ce code à l'entrée</p>
                            </div>
                            <?php endif; ?>

                            <!-- Actions -->
                            <?php if ($booking['status'] === 'confirmed' || $booking['status'] === 'pending'): ?>
                            <div class="mt-4">
                                <a href="<?= base_url('booking/print/' . $booking['id']) ?>" 
                                   class="btn btn-outline-primary w-100 mb-2" target="_blank">
                                    <i class="bi bi-printer"></i> Imprimer le ticket
                                </a>
                                <?php if ($booking['status'] === 'confirmed'): ?>
                                <button class="btn btn-outline-danger w-100" 
                                        onclick="confirmCancellation(<?= $booking['id'] ?>)">
                                    <i class="bi bi-x-circle"></i> Annuler la réservation
                                </button>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php if (!empty($booking['notes'])): ?>
                    <div class="mt-4 p-3 bg-light rounded">
                        <h6 class="mb-2"><i class="bi bi-chat-left-text"></i> Notes</h6>
                        <p class="mb-0 text-muted"><?= nl2br(esc($booking['notes'])) ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirmCancellation(bookingId) {
            if (confirm('Êtes-vous sûr de vouloir annuler cette réservation ?')) {
                window.location.href = '<?= base_url('booking/cancel') ?>/' + bookingId;
            }
        }
    </script>
</body>
</html>
