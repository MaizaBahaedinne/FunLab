<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un Jeu - FunLab Admin</title>
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
        .form-section {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .form-section h5 {
            color: #667eea;
            margin-bottom: 15px;
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
                    <h2><i class="bi bi-controller"></i> Créer un nouveau jeu</h2>
                    <a href="<?= base_url('admin/games') ?>" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Retour à la liste
                    </a>
                </div>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="bi bi-exclamation-triangle"></i>
                        <?= session()->getFlashdata('error') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (isset($validation)): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="bi bi-exclamation-triangle"></i>
                        <strong>Erreurs de validation :</strong>
                        <ul class="mb-0 mt-2">
                            <?php foreach ($validation->getErrors() as $error): ?>
                                <li><?= esc($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('admin/games/store') ?>" method="post" enctype="multipart/form-data">
                    <?= csrf_field() ?>

                    <!-- Informations générales -->
                    <div class="form-section">
                        <h5><i class="bi bi-info-circle"></i> Informations générales</h5>
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label for="name" class="form-label">Nom du jeu <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" 
                                       value="<?= old('name') ?>" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="category" class="form-label">Catégorie <span class="text-danger">*</span></label>
                                <select class="form-select" id="category" name="category" required>
                                    <option value="">Sélectionner...</option>
                                    <option value="Escape Game" <?= old('category') === 'Escape Game' ? 'selected' : '' ?>>Escape Game</option>
                                    <option value="VR" <?= old('category') === 'VR' ? 'selected' : '' ?>>VR</option>
                                    <option value="Laser Game" <?= old('category') === 'Laser Game' ? 'selected' : '' ?>>Laser Game</option>
                                    <option value="Arcade" <?= old('category') === 'Arcade' ? 'selected' : '' ?>>Arcade</option>
                                    <option value="Autre" <?= old('category') === 'Autre' ? 'selected' : '' ?>>Autre</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="4"><?= old('description') ?></textarea>
                            <div class="form-text">Description détaillée du jeu, règles, ambiance, etc.</div>
                        </div>
                    </div>

                    <!-- Configuration du jeu -->
                    <div class="form-section">
                        <h5><i class="bi bi-gear"></i> Configuration du jeu</h5>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="duration" class="form-label">Durée (minutes) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="duration" name="duration" 
                                       value="<?= old('duration', 60) ?>" min="15" max="300" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="min_participants" class="form-label">Participants min <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="min_participants" name="min_participants" 
                                       value="<?= old('min_participants', 2) ?>" min="1" max="20" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="max_participants" class="form-label">Participants max <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="max_participants" name="max_participants" 
                                       value="<?= old('max_participants', 6) ?>" min="1" max="50" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="difficulty" class="form-label">Niveau de difficulté</label>
                            <select class="form-select" id="difficulty" name="difficulty">
                                <option value="">Non spécifié</option>
                                <option value="Facile" <?= old('difficulty') === 'Facile' ? 'selected' : '' ?>>Facile</option>
                                <option value="Moyen" <?= old('difficulty') === 'Moyen' ? 'selected' : '' ?>>Moyen</option>
                                <option value="Difficile" <?= old('difficulty') === 'Difficile' ? 'selected' : '' ?>>Difficile</option>
                                <option value="Expert" <?= old('difficulty') === 'Expert' ? 'selected' : '' ?>>Expert</option>
                            </select>
                        </div>
                    </div>

                    <!-- Tarification -->
                    <div class="form-section">
                        <h5><i class="bi bi-currency-exchange"></i> Tarification</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="price" class="form-label">Prix fixe (TND)</label>
                                <input type="number" class="form-control" id="price" name="price" 
                                       value="<?= old('price', 0) ?>" min="0" step="0.01">
                                <div class="form-text">Prix fixe pour une session (0 = utiliser le prix par personne)</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="price_per_person" class="form-label">Prix par personne (TND)</label>
                                <input type="number" class="form-control" id="price_per_person" name="price_per_person" 
                                       value="<?= old('price_per_person', 0) ?>" min="0" step="0.01">
                                <div class="form-text">Prix variable selon le nombre de participants</div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="deposit_required" 
                                           name="deposit_required" value="1" 
                                           <?= old('deposit_required') ? 'checked' : '' ?>
                                           onchange="toggleDeposit()">
                                    <label class="form-check-label" for="deposit_required">
                                        Acompte requis
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3" id="deposit-percentage-group" style="display: none;">
                                <label for="deposit_percentage" class="form-label">Pourcentage d'acompte (%)</label>
                                <input type="number" class="form-control" id="deposit_percentage" 
                                       name="deposit_percentage" value="<?= old('deposit_percentage', 30) ?>" 
                                       min="10" max="100">
                            </div>
                        </div>
                    </div>

                    <!-- Salles associées -->
                    <div class="form-section">
                        <h5><i class="bi bi-door-open"></i> Salles associées</h5>
                        <?php if (isset($rooms) && !empty($rooms)): ?>
                            <div class="row">
                                <?php foreach ($rooms as $room): ?>
                                    <div class="col-md-6 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" 
                                                   name="rooms[]" value="<?= $room['id'] ?>" 
                                                   id="room_<?= $room['id'] ?>"
                                                   <?= in_array($room['id'], old('rooms', [])) ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="room_<?= $room['id'] ?>">
                                                <?= esc($room['name']) ?>
                                                <span class="text-muted small">(Capacité: <?= $room['capacity'] ?>)</span>
                                            </label>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle"></i>
                                Aucune salle disponible. <a href="<?= base_url('admin/rooms/create') ?>">Créer une salle</a>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Statut -->
                    <div class="form-section">
                        <h5><i class="bi bi-toggle-on"></i> Statut</h5>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="status" name="status" 
                                   value="active" <?= old('status', 'active') === 'active' ? 'checked' : '' ?>>
                            <label class="form-check-label" for="status">
                                Jeu actif (visible pour les réservations)
                            </label>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="d-flex justify-content-between">
                        <a href="<?= base_url('admin/games') ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle"></i> Annuler
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Créer le jeu
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle deposit percentage input
        function toggleDeposit() {
            const depositRequired = document.getElementById('deposit_required').checked;
            const depositGroup = document.getElementById('deposit-percentage-group');
            depositGroup.style.display = depositRequired ? 'block' : 'none';
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            toggleDeposit();
        });

        // Validate participants min/max
        document.getElementById('min_participants').addEventListener('change', function() {
            const min = parseInt(this.value);
            const maxInput = document.getElementById('max_participants');
            const max = parseInt(maxInput.value);
            
            if (max < min) {
                maxInput.value = min;
            }
        });

        document.getElementById('max_participants').addEventListener('change', function() {
            const max = parseInt(this.value);
            const minInput = document.getElementById('min_participants');
            const min = parseInt(minInput.value);
            
            if (max < min) {
                this.value = min;
                alert('Le maximum doit être supérieur ou égal au minimum');
            }
        });
    </script>
</body>
</html>
