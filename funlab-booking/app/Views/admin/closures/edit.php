<?php
$title = 'Modifier la Fermeture';
$pageTitle = 'Modifier la fermeture';
$activeMenu = 'closures';
$breadcrumbs = ['Admin' => base_url('admin'), 'Fermetures' => base_url('admin/closures'), 'Modifier' => null];
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

                <div class="mb-3">
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

<?php
$additionalJS = '<script>
    function toggleRoomSelection() {
        const allRooms = document.getElementById("all_rooms").checked;
        const roomSelection = document.getElementById("room-selection");
        const roomSelect = document.getElementById("room_id");
        
        if (allRooms) {
            roomSelection.style.display = "none";
            roomSelect.value = "";
            roomSelect.removeAttribute("required");
        } else {
            roomSelection.style.display = "block";
        }
    }

    // Initialize on page load
    document.addEventListener("DOMContentLoaded", function() {
        toggleRoomSelection();
    });
</script>';
?>

<?= view('admin/layouts/footer', compact('additionalJS')) ?>
