<?php
$title = 'Créer un Jeu';
$pageTitle = 'Créer un nouveau jeu';
$activeMenu = 'games';
$breadcrumbs = ['Admin' => base_url('admin'), 'Jeux' => base_url('admin/games'), 'Créer' => null];
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
                        <div class="mb-3">
                            <label for="name" class="form-label">Nom du jeu <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" 
                                   value="<?= old('name') ?>" required>
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
                                <label for="duration_minutes" class="form-label">Durée (minutes) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="duration_minutes" name="duration_minutes" 
                                       value="<?= old('duration_minutes', 60) ?>" min="15" max="300" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="min_players" class="form-label">Joueurs min <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="min_players" name="min_players" 
                                       value="<?= old('min_players', 2) ?>" min="1" max="20" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="max_players" class="form-label">Joueurs max <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="max_players" name="max_players" 
                                       value="<?= old('max_players', 6) ?>" min="1" max="50" required>
                            </div>
                        </div>
                    </div>

                    <!-- Tarification -->
                    <div class="form-section">
                        <h5><i class="bi bi-currency-exchange"></i> Tarification</h5>
                        <div class="mb-3">
                            <label for="price" class="form-label">Prix (TND) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="price" name="price" 
                                   value="<?= old('price', 0) ?>" min="0" step="0.01" required>
                            <div class="form-text">Prix de la session</div>
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

                    <!-- SEO & Partage Social -->
                    <div class="form-section">
                        <h5><i class="bi bi-share"></i> SEO & Partage Social</h5>
                        <p class="text-muted small mb-3">
                            <i class="bi bi-info-circle"></i> Ces informations améliorent le référencement et l'apparence lors du partage sur les réseaux sociaux
                        </p>
                        
                        <div class="mb-3">
                            <label for="meta_title" class="form-label">
                                Titre SEO
                                <span class="text-muted small">(Optionnel)</span>
                            </label>
                            <input type="text" class="form-control" id="meta_title" name="meta_title" 
                                   value="<?= old('meta_title') ?>" 
                                   maxlength="60">
                            <div class="form-text">
                                Titre optimisé pour les moteurs de recherche (max 60 caractères)
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="meta_description" class="form-label">
                                Description SEO
                                <span class="text-muted small">(Optionnel)</span>
                            </label>
                            <textarea class="form-control" id="meta_description" name="meta_description" 
                                      rows="3" maxlength="160"><?= old('meta_description') ?></textarea>
                            <div class="form-text">
                                Description pour les moteurs de recherche et réseaux sociaux (max 160 caractères)
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="meta_keywords" class="form-label">
                                Mots-clés SEO
                                <span class="text-muted small">(Optionnel)</span>
                            </label>
                            <input type="text" class="form-control" id="meta_keywords" name="meta_keywords" 
                                   value="<?= old('meta_keywords') ?>" 
                                   placeholder="escape game, réalité virtuelle, divertissement, tunisie">
                            <div class="form-text">
                                Mots-clés séparés par des virgules
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="og_image" class="form-label">
                                Image pour partage social
                                <span class="text-muted small">(Optionnel)</span>
                            </label>
                            <input type="text" class="form-control" id="og_image" name="og_image" 
                                   value="<?= old('og_image') ?>" 
                                   placeholder="https://exemple.com/image-partage.jpg">
                            <div class="form-text">
                                URL de l'image pour Facebook, WhatsApp, etc. (1200x630px recommandé)
                            </div>
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
