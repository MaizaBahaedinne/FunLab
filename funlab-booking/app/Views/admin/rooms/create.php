<?php
$title = 'Créer une Salle';
$pageTitle = 'Créer une nouvelle salle';
$activeMenu = 'rooms';
$breadcrumbs = ['Admin' => base_url('admin'), 'Salles' => base_url('admin/rooms'), 'Créer' => null];
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
                    <h2><i class="bi bi-door-open"></i> Créer une nouvelle salle</h2>
                    <a href="<?= base_url('admin/rooms') ?>" class="btn btn-outline-secondary">
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

                <form action="<?= base_url('admin/rooms/store') ?>" method="post">
                    <?= csrf_field() ?>

                    <!-- Informations générales -->
                    <div class="form-section">
                        <h5><i class="bi bi-info-circle"></i> Informations générales</h5>
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label for="name" class="form-label">Nom de la salle <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" 
                                       value="<?= old('name') ?>" required 
                                       placeholder="Ex: Salle VR 1, Escape Room A, Laser Game Arena">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="capacity" class="form-label">Capacité <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="capacity" name="capacity" 
                                       value="<?= old('capacity', 6) ?>" min="1" max="100" required>
                                <div class="form-text">Nombre maximum de personnes</div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="4"><?= old('description') ?></textarea>
                            <div class="form-text">Description de la salle, équipements, particularités...</div>
                        </div>
                    </div>

                    <!-- Configuration -->
                    <div class="form-section">
                        <h5><i class="bi bi-gear"></i> Configuration</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="location" class="form-label">Emplacement</label>
                                <input type="text" class="form-control" id="location" name="location" 
                                       value="<?= old('location') ?>" 
                                       placeholder="Ex: Étage 1, Zone A, Bâtiment principal">
                                <div class="form-text">Position physique dans le centre</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="equipment" class="form-label">Équipements</label>
                                <input type="text" class="form-control" id="equipment" name="equipment" 
                                       value="<?= old('equipment') ?>" 
                                       placeholder="Ex: Casques VR, Projecteur, Sonorisation">
                                <div class="form-text">Équipements disponibles</div>
                            </div>
                        </div>
                    </div>

                    <!-- Statut -->
                    <div class="form-section">
                        <h5><i class="bi bi-toggle-on"></i> Statut</h5>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="status" name="status" 
                                   value="active" <?= old('status', 'active') === 'active' ? 'checked' : '' ?>>
                            <label class="form-check-label" for="status">
                                Salle active (disponible pour les réservations)
                            </label>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="d-flex justify-content-between">
                        <a href="<?= base_url('admin/rooms') ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle"></i> Annuler
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Créer la salle
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?= view('admin/layouts/footer') ?>
