<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Fermetures - FunLab Admin</title>
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
        .closure-card {
            border-left: 4px solid #dc3545;
            transition: transform 0.3s;
        }
        .closure-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .badge-type {
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
                    <a class="nav-link" href="<?= base_url('admin/games') ?>">
                        <i class="bi bi-controller"></i> Jeux
                    </a>
                    <a class="nav-link" href="<?= base_url('admin/bookings') ?>">
                        <i class="bi bi-calendar-check"></i> Réservations
                    </a>
                    <a class="nav-link active" href="<?= base_url('admin/closures') ?>">
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
                    <h2><i class="bi bi-calendar-x"></i> Gestion des Fermetures</h2>
                    <a href="<?= base_url('admin/closures/create') ?>" class="btn btn-danger">
                        <i class="bi bi-plus-circle"></i> Ajouter une fermeture
                    </a>
                </div>

                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i>
                    Les fermetures bloquent les créneaux de réservation. Utilisez-les pour les jours fériés, maintenance, événements privés, etc.
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

                <!-- Filtres -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <label class="form-label">Type</label>
                                <select class="form-select" id="filter-type">
                                    <option value="">Tous les types</option>
                                    <option value="full_day">Journée complète</option>
                                    <option value="partial">Partielle</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Période</label>
                                <select class="form-select" id="filter-period">
                                    <option value="all">Toutes</option>
                                    <option value="upcoming">À venir</option>
                                    <option value="active">En cours</option>
                                    <option value="past">Passées</option>
                                </select>
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button class="btn btn-outline-primary w-100" onclick="applyFilters()">
                                    <i class="bi bi-funnel"></i> Filtrer
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row" id="closures-list">
                    <?php if (empty($closures)): ?>
                        <div class="col-12">
                            <div class="alert alert-secondary">
                                <i class="bi bi-inbox"></i>
                                Aucune fermeture programmée. <a href="<?= base_url('admin/closures/create') ?>">Créer une fermeture</a>
                            </div>
                        </div>
                    <?php else: ?>
                        <?php foreach ($closures as $closure): ?>
                            <?php
                            $closureDate = strtotime($closure['closure_date'] ?? date('Y-m-d'));
                            $startTime = strtotime(($closure['start_time'] ?? '00:00:00'));
                            $endTime = strtotime(($closure['end_time'] ?? '23:59:59'));
                            $now = time();
                            $isToday = date('Y-m-d', $closureDate) === date('Y-m-d');
                            $isPast = $closureDate < strtotime('today');
                            $isUpcoming = $closureDate > strtotime('today');
                            ?>
                            <div class="col-md-6 mb-4 closure-item" 
                                 data-type="<?= !empty($closure['start_time']) && !empty($closure['end_time']) ? 'partial' : 'full_day' ?>"
                                 data-status="<?= $isPast ? 'past' : ($isToday ? 'active' : 'upcoming') ?>">
                                <div class="card closure-card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <h5 class="card-title mb-0">
                                                <i class="bi bi-calendar-x text-danger"></i>
                                                <?= esc($closure['reason'] ?? 'Fermeture') ?>
                                            </h5>
                                            <div>
                                                <span class="badge badge-type bg-<?= (!empty($closure['start_time']) && !empty($closure['end_time'])) ? 'warning' : 'danger' ?>">
                                                    <?= (!empty($closure['start_time']) && !empty($closure['end_time'])) ? 'Partielle' : 'Journée complète' ?>
                                                </span>
                                                <?php if ($closure['all_rooms'] ?? 0): ?>
                                                    <span class="badge bg-dark ms-1">Toutes les salles</span>
                                                <?php endif; ?>
                                                <?php if ($isToday): ?>
                                                    <span class="badge bg-success ms-1">Aujourd'hui</span>
                                                <?php elseif ($isPast): ?>
                                                    <span class="badge bg-secondary ms-1">Terminée</span>
                                                <?php else: ?>
                                                    <span class="badge bg-info ms-1">À venir</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        <div class="mb-3<?= isset($closure['closure_date']) ? date('d/m/Y', strtotime($closure['closure_date'])) : 'N/A' ?></strong>
                                            </div>
                                            <?php if (!empty($closure['start_time']) && !empty($closure['end_time'])): ?>
                                                <div class="d-flex align-items-center">
                                                    <i class="bi bi-clock text-muted me-2"></i>
                                                    <strong>De <?= date('H:i', strtotime($closure['start_time'])) ?> à <?= date('H:i', strtotime($closure['end_time'])) ?></strong>
                                                </div>
                                            <?php endif; ??php if (($closure['type'] ?? '') === 'partial' && !empty($closure['end_time'])): ?>
                                                    à <?= date('H:i', strtotime($closure['end_time'])) ?>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <span class="badge bg-info">
                                                <i class="bi bi-door-open"></i>
                                                <?= !empty($closure['room_name']) ? esc($closure['room_name']) : 'Toutes les salles' ?>
                                            </span>
                                        </div>

                                        <?php if (!empty($closure['description'])): ?>
                                            <p class="card-text text-muted small">
                                                <?= esc($closure['description']) ?>
                                            </p>
                                        <?php endif; ?>

                                        <div class="d-flex gap-2">
                                            <a href="<?= base_url('admin/closures/edit/' . $closure['id']) ?>" 
                                               class="btn btn-sm btn-outline-primary flex-fill">
                                                <i class="bi bi-pencil"></i> Modifier
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-danger" 
                                                    onclick="deleteClosure(<?= $closure['id'] ?>)">
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
        function deleteClosure(id) {
            if (confirm('Êtes-vous sûr de vouloir supprimer cette fermeture ?\n\nLes créneaux redeviendront disponibles.')) {
                fetch(`<?= base_url('admin/closures/delete/') ?>${id}`, {
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

        function applyFilters() {
            const type = document.getElementById('filter-type').value;
            const period = document.getElementById('filter-period').value;
            const items = document.querySelectorAll('.closure-item');

            items.forEach(item => {
                const itemType = item.dataset.type;
                const itemStatus = item.dataset.status;

                let showType = !type || itemType === type;
                let showPeriod = period === 'all' || itemStatus === period;

                item.style.display = (showType && showPeriod) ? 'block' : 'none';
            });
        }
    </script>
</body>
</html>
