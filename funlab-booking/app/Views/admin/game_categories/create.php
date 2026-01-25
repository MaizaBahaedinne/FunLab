<?php
$title = 'Nouvelle Catégorie';
$activeMenu = 'categories';
$pageTitle = 'Nouvelle Catégorie';
$breadcrumbs = [
    ['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
    ['title' => 'Catégories', 'url' => base_url('admin/game-categories')],
    ['title' => 'Nouveau', 'url' => '']
];
?>

<?= view('admin/layouts/header', compact('title')) ?>
<?= view('admin/layouts/sidebar', compact('activeMenu')) ?>
<?= view('admin/layouts/topbar', compact('pageTitle', 'breadcrumbs')) ?>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Créer une Catégorie</h5>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('admin/game-categories/store') ?>" method="POST">
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label class="form-label">Nom <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3"></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Icône Bootstrap</label>
                                <input type="text" name="icon" class="form-control" 
                                       placeholder="bi-controller" value="bi-grid">
                                <small class="text-muted">
                                    <a href="https://icons.getbootstrap.com/" target="_blank">Voir les icônes</a>
                                </small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Couleur</label>
                                <input type="color" name="color" class="form-control form-control-color" value="#667eea">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Ordre d'affichage</label>
                                <input type="number" name="display_order" class="form-control" value="0" min="0">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Statut</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_active" checked>
                                    <label class="form-check-label">Actif</label>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Enregistrer
                            </button>
                            <a href="<?= base_url('admin/game-categories') ?>" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= view('admin/layouts/footer') ?>
