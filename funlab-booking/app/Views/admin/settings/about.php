<?php
$title = 'Configuration Page À Propos';
$activeMenu = 'settings-about';
$pageTitle = 'Page À Propos';
$breadcrumbs = [
    ['title' => 'Paramètres', 'url' => base_url('admin/settings')],
    ['title' => 'À Propos', 'url' => '']
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
                <i class="bi bi-info-circle"></i> Contenu de la Page À Propos
            </h5>
        </div>
        <div class="card-body">
            <form action="<?= base_url('admin/settings/about') ?>" method="POST">
                <?= csrf_field() ?>

                <!-- En-tête -->
                <div class="mb-4">
                    <h6 class="border-bottom pb-2 mb-3">En-tête de la page</h6>
                    
                    <div class="mb-3">
                        <label class="form-label">Titre principal</label>
                        <input type="text" name="about_title" class="form-control" 
                               value="<?= esc($settings['about_title'] ?? '') ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Sous-titre</label>
                        <input type="text" name="about_subtitle" class="form-control" 
                               value="<?= esc($settings['about_subtitle'] ?? '') ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Image hero (URL)</label>
                        <input type="text" name="about_hero_image" class="form-control" 
                               value="<?= esc($settings['about_hero_image'] ?? '') ?>" 
                               placeholder="https://...">
                        <small class="text-muted">URL de l'image d'en-tête</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Introduction</label>
                        <textarea name="about_intro" class="form-control" rows="4"><?= esc($settings['about_intro'] ?? '') ?></textarea>
                        <small class="text-muted">Texte d'introduction de la page</small>
                    </div>
                </div>

                <!-- Mission -->
                <div class="mb-4">
                    <h6 class="border-bottom pb-2 mb-3">Mission</h6>
                    
                    <div class="mb-3">
                        <label class="form-label">Titre</label>
                        <input type="text" name="about_mission_title" class="form-control" 
                               value="<?= esc($settings['about_mission_title'] ?? '') ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Contenu</label>
                        <textarea name="about_mission_content" class="form-control" rows="4"><?= esc($settings['about_mission_content'] ?? '') ?></textarea>
                    </div>
                </div>

                <!-- Vision -->
                <div class="mb-4">
                    <h6 class="border-bottom pb-2 mb-3">Vision</h6>
                    
                    <div class="mb-3">
                        <label class="form-label">Titre</label>
                        <input type="text" name="about_vision_title" class="form-control" 
                               value="<?= esc($settings['about_vision_title'] ?? '') ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Contenu</label>
                        <textarea name="about_vision_content" class="form-control" rows="4"><?= esc($settings['about_vision_content'] ?? '') ?></textarea>
                    </div>
                </div>

                <!-- Valeurs -->
                <div class="mb-4">
                    <h6 class="border-bottom pb-2 mb-3">Valeurs</h6>
                    
                    <div class="mb-3">
                        <label class="form-label">Titre</label>
                        <input type="text" name="about_values_title" class="form-control" 
                               value="<?= esc($settings['about_values_title'] ?? '') ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Contenu</label>
                        <textarea name="about_values_content" class="form-control" rows="4"><?= esc($settings['about_values_content'] ?? '') ?></textarea>
                        <small class="text-muted">Séparez les valeurs par des virgules ou des retours à la ligne</small>
                    </div>
                </div>

                <!-- Équipe -->
                <div class="mb-4">
                    <h6 class="border-bottom pb-2 mb-3">Équipe</h6>
                    
                    <div class="mb-3">
                        <label class="form-label">Titre</label>
                        <input type="text" name="about_team_title" class="form-control" 
                               value="<?= esc($settings['about_team_title'] ?? '') ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Contenu</label>
                        <textarea name="about_team_content" class="form-control" rows="4"><?= esc($settings['about_team_content'] ?? '') ?></textarea>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Enregistrer les modifications
                    </button>
                    <a href="<?= base_url('about') ?>" class="btn btn-outline-secondary" target="_blank">
                        <i class="bi bi-eye"></i> Prévisualiser la page
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<?= view('admin/layouts/footer') ?>
