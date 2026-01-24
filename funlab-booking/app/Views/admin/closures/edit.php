<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier la Fermeture - FunLab Admin</title>
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
                    <h2><i class="bi bi-calendar-x"></i> Modifier la fermeture</h2>
                    <a href="<?= base_url('admin/closures') ?>" class="btn btn-outline-secondary">
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

                <form action="<?= base_url('admin/closures/update/' . $closure['id']) ?>" method="post">
                    <?= csrf_field() ?>

                    <!-- Date de fermeture -->
                    <div class="form-section">
                        <h5><i class="bi bi-calendar3"></i> Date de fermeture</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="closure_date" class="form-label">Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="closure_date" name="closure_date" 
                                       value="<?= old('closure_date', $closure['closure_date']) ?>" required>
                                <div class="form-text">Date de la fermeture</div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="start_time" class="form-label">Heure de début</label>
                                <input type="time" class="form-control" id="start_time" name="start_time" 
                                       value="<?= old('start_time', $closure['start_time'] ?? '') ?>">
                                <div class="form-text">Laissez vide pour une fermeture toute la journée</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="end_time" class="form-label">Heure de fin</label>
                                <input type="time" class="form-control" id="end_time" name="end_time" 
                                       value="<?= old('end_time', $closure['end_time'] ?? '') ?>">
                                <div class="form-text">Laissez vide pour une fermeture toute la journée</div>
                            </div>
                        </div>
                    </div>

                    <!-- Portée de la fermeture -->
                    <div class="form-section">
                        <h5><i class="bi bi-door-open"></i> Portée de la fermeture</h5>
                        
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="all_rooms" 
                                       name="all_rooms" value="1" 
                                       <?= old('all_rooms', $closure['all_rooms'] ?? 0) ? 'checked' : '' ?>
                                       onchange="toggleRoomSelection()">
                                <label class="form-check-label" for="all_rooms">
                                    <strong>Fermer toutes les salles</strong>
                                </label>
                            </div>
                            <div class="form-text">Cochez pour fermer l'ensemble du centre</div>
                        </div>

                        <div id="room-selection" <?= old('all_rooms', $closure['all_rooms'] ?? 0) ? 'style="display:none"' : '' ?>>
                            <label class="form-label">Salle concernée</label>
                            <select class="form-select" id="room_id" name="room_id">
                                <option value="">Sélectionner une salle spécifique (optionnel)</option>
                                <?php if (isset($rooms) && !empty($rooms)): ?>
                                    <?php foreach ($rooms as $room): ?>
                                        <option value="<?= $room['id'] ?>" 
                                                <?= old('room_id', $closure['room_id'] ?? '') == $room['id'] ? 'selected' : '' ?>>
                                            <?= esc($room['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <div class="form-text">Laissez vide pour fermer toutes les salles</div>
                        </div>
                    </div>

                    <!-- Raison -->
                    <div class="form-section">
                        <h5><i class="bi bi-chat-text"></i> Raison de la fermeture</h5>
                        <div class="mb-3">
                            <label for="reason" class="form-label">Raison</label>
                            <textarea class="form-control" id="reason" name="reason" rows="3"><?= old('reason', $closure['reason'] ?? '') ?></textarea>
                            <div class="form-text">Ex: Jour férié, Maintenance, Événement privé, etc.</div>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="d-flex justify-content-between">
                        <a href="<?= base_url('admin/closures') ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle"></i> Annuler
                        </a>
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-check-circle"></i> Enregistrer les modifications
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleRoomSelection() {
            const allRooms = document.getElementById('all_rooms').checked;
            const roomSelection = document.getElementById('room-selection');
            const roomSelect = document.getElementById('room_id');
            
            if (allRooms) {
                roomSelection.style.display = 'none';
                roomSelect.value = '';
                roomSelect.removeAttribute('required');
            } else {
                roomSelection.style.display = 'block';
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            toggleRoomSelection();
        });
    </script>
</body>
</html>
