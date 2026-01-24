<?php
$title = 'Configuration Footer';
$activeMenu = 'settings-footer';
$pageTitle = 'Configuration Footer';
$breadcrumbs = [
    ['title' => 'Paramètres', 'url' => base_url('admin/settings')],
    ['title' => 'Footer', 'url' => '']
];
?>

<?= view('admin/layouts/header', compact('title')) ?>
<?= view('admin/layouts/sidebar', compact('activeMenu')) ?>
<?= view('admin/layouts/topbar', compact('pageTitle', 'breadcrumbs')) ?>

<div class="container-fluid">
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle"></i> <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle"></i> <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="bi bi-layout-text-sidebar-reverse"></i> Paramètres du Footer
            </h5>
        </div>
        <div class="card-body">
            <form action="<?= base_url('admin/settings/footer') ?>" method="post">
                <?= csrf_field() ?>

                <!-- Informations de l'entreprise -->
                <div class="mb-4">
                    <h6 class="border-bottom pb-2 mb-3">Informations de l'entreprise</h6>
                    
                    <div class="mb-3">
                        <label class="form-label">Nom de l'entreprise</label>
                        <input type="text" name="footer_company_name" class="form-control" 
                               value="<?= esc($settings['footer_company_name'] ?? '') ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <input type="text" name="footer_description" class="form-control" 
                               value="<?= esc($settings['footer_description'] ?? '') ?>">
                        <small class="text-muted">Courte description affichée dans le footer</small>
                    </div>
                </div>

                <!-- Coordonnées -->
                <div class="mb-4">
                    <h6 class="border-bottom pb-2 mb-3">Coordonnées</h6>
                    
                    <div class="mb-3">
                        <label class="form-label">Adresse</label>
                        <input type="text" name="footer_address" class="form-control" 
                               value="<?= esc($settings['footer_address'] ?? '') ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="footer_email" class="form-control" 
                               value="<?= esc($settings['footer_email'] ?? '') ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Téléphone</label>
                        <input type="text" name="footer_phone" class="form-control" 
                               value="<?= esc($settings['footer_phone'] ?? '') ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Horaires</label>
                        <textarea name="footer_hours" class="form-control" rows="3"><?= esc($settings['footer_hours'] ?? '') ?></textarea>
                        <small class="text-muted">Vous pouvez utiliser &lt;br&gt; pour les sauts de ligne</small>
                    </div>
                </div>

                <!-- Réseaux sociaux -->
                <div class="mb-4">
                    <h6 class="border-bottom pb-2 mb-3">Réseaux sociaux</h6>
                    
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="bi bi-facebook text-primary"></i> Facebook
                        </label>
                        <input type="url" name="footer_facebook" class="form-control" 
                               value="<?= esc($settings['footer_facebook'] ?? '') ?>" 
                               placeholder="https://facebook.com/...">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            <i class="bi bi-instagram text-danger"></i> Instagram
                        </label>
                        <input type="url" name="footer_instagram" class="form-control" 
                               value="<?= esc($settings['footer_instagram'] ?? '') ?>" 
                               placeholder="https://instagram.com/...">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            <i class="bi bi-twitter text-info"></i> Twitter
                        </label>
                        <input type="url" name="footer_twitter" class="form-control" 
                               value="<?= esc($settings['footer_twitter'] ?? '') ?>" 
                               placeholder="https://twitter.com/...">
                    </div>
                </div>

                <!-- Copyright -->
                <div class="mb-4">
                    <h6 class="border-bottom pb-2 mb-3">Copyright</h6>
                    
                    <div class="mb-3">
                        <label class="form-label">Texte du copyright</label>
                        <input type="text" name="footer_copyright" class="form-control" 
                               value="<?= esc($settings['footer_copyright'] ?? '') ?>">
                        <small class="text-muted">Utilisez {year} pour l'année en cours</small>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Enregistrer les modifications
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= view('admin/layouts/footer') ?>
