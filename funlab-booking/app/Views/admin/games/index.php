<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Jeux - FunLab Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            transition: all 0.3s;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background: rgba(255,255,255,0.1);
        }
        .game-card {
            transition: transform 0.3s;
            border-left: 4px solid #667eea;
        }
        .game-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .badge-status {
            padding: 6px 12px;
            border-radius: 15px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 sidebar">
                <div class="text-center py-4">
                    <h4 class="text-white"><i class="bi bi-joystick"></i> FunLab Admin</h4>
                </div>
                <nav class="nav flex-column">
                    <a class="nav-link" href="<?= base_url('admin/dashboard') ?>">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                    <a class="nav-link" href="<?= base_url('admin/rooms') ?>">
                        <i class="bi bi-door-open"></i> Salles
                    </a>
                    <a class="nav-link active" href="<?= base_url('admin/games') ?>">
                        <i class="bi bi-controller"></i> Jeux
                    </a>
                    <a class="nav-link" href="<?= base_url('admin/bookings') ?>">
                        <i class="bi bi-calendar-check"></i> Réservations
                    </a>
                    <a class="nav-link" href="<?= base_url('admin/closures') ?>">
                        <i class="bi bi-calendar-x"></i> Fermetures
                    </a>
                    <a class="nav-link" href="<?= base_url('admin/scanner') ?>">
                        <i class="bi bi-qr-code-scan"></i> Scanner QR
                    </a>
                    <hr class="bg-white">
                    <a class="nav-link" href="<?= base_url('auth/logout') ?>">
                        <i class="bi bi-box-arrow-right"></i> Déconnexion
                    </a>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="bi bi-controller"></i> Gestion des Jeux</h2>
                    <a href="<?= base_url('admin/games/create') ?>" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Ajouter un jeu
                    </a>
                </div>

                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="bi bi-check-circle"></i>
                        <?= session()->getFlashdata('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="bi bi-exclamation-triangle"></i>
                        <?= session()->getFlashdata('error') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="row">
                    <?php if (empty($games)): ?>
                        <div class="col-12">
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle"></i>
                                Aucun jeu trouvé. <a href="<?= base_url('admin/games/create') ?>">Créer le premier jeu</a>
                            </div>
                        </div>
                    <?php else: ?>
                        <?php foreach ($games as $game): ?>
                            <div class="col-md-6 mb-4">
                                <div class="card game-card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <div>
                                                <h5 class="card-title mb-1">
                                                    <i class="bi bi-controller text-primary"></i>
                                                    <?= esc($game['name']) ?>
                                                </h5>
                                                <span class="badge bg-secondary"><?= esc($game['category'] ?? 'Non catégorisé') ?></span>
                                            </div>
                                            <span class="badge badge-status bg-<?= $game['status'] === 'active' ? 'success' : 'secondary' ?>">
                                                <?= $game['status'] === 'active' ? 'Actif' : 'Inactif' ?>
                                            </span>
                                        </div>

                                        <?php if (!empty($game['description'])): ?>
                                            <p class="card-text text-muted small mb-3">
                                                <?= mb_substr(esc($game['description']), 0, 120) ?>
                                                <?= mb_strlen($game['description']) > 120 ? '...' : '' ?>
                                            </p>
                                        <?php endif; ?>

                                        <div class="row mb-3">
                                            <div class="col-6">
                                                <div class="d-flex justify-content-between">
                                                    <span class="text-muted small">
                                                        <i class="bi bi-clock"></i> Durée
                                                    </span>
                                                    <strong><?= $game['duration'] ?> min</strong>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="d-flex justify-content-between">
                                                    <span class="text-muted small">
                                                        <i class="bi bi-people"></i> Min
                                                    </span>
                                                    <strong><?= $game['min_participants'] ?>-<?= $game['max_participants'] ?></strong>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-6">
                                                <div class="d-flex justify-content-between">
                                                    <span class="text-muted small">
                                                        <i class="bi bi-tag"></i> Prix
                                                    </span>
                                                    <strong><?= number_format($game['price'] ?? 0, 2) ?> TND</strong>
                                                </div>
                                            </div>
                                            <?php if (isset($game['price_per_person']) && $game['price_per_person'] > 0): ?>
                                            <div class="col-6">
                                                <div class="d-flex justify-content-between">
                                                    <span class="text-muted small">
                                                        <i class="bi bi-person"></i> Par personne
                                                    </span>
                                                    <strong><?= number_format($game['price_per_person'], 2) ?> TND</strong>
                                                </div>
                                            </div>
                                            <?php endif; ?>
                                        </div>

                                        <?php if (isset($game['deposit_required']) && $game['deposit_required']): ?>
                                            <div class="alert alert-info py-2 px-3 mb-3 small">
                                                <i class="bi bi-info-circle"></i>
                                                Acompte requis: <?= $game['deposit_percentage'] ?? 30 ?>%
                                            </div>
                                        <?php endif; ?>

                                        <div class="d-flex gap-2">
                                            <a href="<?= base_url('admin/games/edit/' . $game['id']) ?>" 
                                               class="btn btn-sm btn-outline-primary flex-fill">
                                                <i class="bi bi-pencil"></i> Modifier
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-danger" 
                                                    onclick="deleteGame(<?= $game['id'] ?>, '<?= esc($game['name']) ?>')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function deleteGame(id, name) {
            if (confirm(`Êtes-vous sûr de vouloir supprimer le jeu "${name}" ?\n\nCette action est irréversible et supprimera toutes les réservations associées.`)) {
                fetch(`<?= base_url('admin/games/delete/') ?>${id}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    } else {
                        alert('Erreur: ' + data.message);
                    }
                })
                .catch(error => {
                    alert('Erreur lors de la suppression');
                    console.error(error);
                });
            }
        }
    </script>
</body>
</html>
