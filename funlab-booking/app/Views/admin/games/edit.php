<?php
$title = 'Modifier le Jeu';
$pageTitle = 'Modifier le jeu';
$activeMenu = 'games';
$breadcrumbs = ['Admin' => base_url('admin'), 'Jeux' => base_url('admin/games'), 'Modifier' => null];
$additionalStyles = '
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
';
?>

<?= view('admin/layouts/header', compact('title', 'additionalStyles')) ?>
<?= view('admin/layouts/sidebar', compact('activeMenu')) ?>
<?= view('admin/layouts/topbar', compact('pageTitle', 'breadcrumbs')) ?>

            <div class="container-fluid p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="bi bi-controller"></i> Modifier le jeu</h2>
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

                <form action="<?= base_url('admin/games/update/' . $game['id']) ?>" method="post" enctype="multipart/form-data">
                    <?= csrf_field() ?>

                    <!-- Informations générales -->
                    <div class="form-section">
                        <h5><i class="bi bi-info-circle"></i> Informations générales</h5>
                        <div class="mb-3">
                            <label for="name" class="form-label">Nom du jeu <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" 
                                   value="<?= old('name', $game['name']) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="4"><?= old('description', $game['description'] ?? '') ?></textarea>
                            <div class="form-text">Description détaillée du jeu, règles, ambiance, etc.</div>
                        </div>
                    </div>

                    <!-- Configuration du jeu -->
                    <div class="form-section">
                        <h5><i class="bi bi-gear"></i> Configuration du jeu</h5>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="duration_minutes" class="form-label">Durée (minutes) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="duration_minutes" name="duration_minutes" 
                                       value="<?= old('duration_minutes', $game['duration_minutes'] ?? 60) ?>" min="15" max="300" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="min_players" class="form-label">Joueurs min <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="min_players" name="min_players" 
                                       value="<?= old('min_players', $game['min_players'] ?? 2) ?>" min="1" max="20" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="max_players" class="form-label">Joueurs max <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="max_players" name="max_players" 
                                       value="<?= old('max_players', $game['max_players'] ?? 6) ?>" min="1" max="50" required>
                            </div>
                        </div>
                    </div>

                    <!-- Tarification -->
                    <div class="form-section">
                        <h5><i class="bi bi-currency-exchange"></i> Tarification</h5>
                        <div class="mb-3">
                            <label for="price" class="form-label">Prix (TND) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="price" name="price" 
                                   value="<?= old('price', $game['price'] ?? 0) ?>" min="0" step="0.01" required>
                            <div class="form-text">Prix de la session</div>
                        </div>
                    </div>

                    <!-- Salles associées -->
                    <div class="form-section">
                        <h5><i class="bi bi-door-open"></i> Salles associées</h5>
                        <?php if (isset($rooms) && !empty($rooms)): ?>
                            <?php 
                            // Get selected room IDs
                            $selectedRooms = old('rooms', isset($game_rooms) ? array_column($game_rooms, 'room_id') : []);
                            ?>
                            <div class="row">
                                <?php foreach ($rooms as $room): ?>
                                    <div class="col-md-6 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" 
                                                   name="rooms[]" value="<?= $room['id'] ?>" 
                                                   id="room_<?= $room['id'] ?>"
                                                   <?= in_array($room['id'], $selectedRooms) ? 'checked' : '' ?>>
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
                                   value="active" <?= old('status', $game['status'] ?? 'active') === 'active' ? 'checked' : '' ?>>
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
                            <i class="bi bi-check-circle"></i> Enregistrer les modifications
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
