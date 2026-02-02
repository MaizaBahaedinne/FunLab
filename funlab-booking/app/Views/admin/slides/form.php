<?php
$activeMenu = 'pages';
$pageTitle = isset($slide) ? 'Modifier la Slide' : 'Nouvelle Slide';
$breadcrumbs = ['Admin' => base_url('admin/dashboard'), 'Slider' => base_url('admin/slides'), $pageTitle => null];
?>

<?= view('admin/layouts/header', compact('title')) ?>
<?= view('admin/layouts/sidebar', compact('activeMenu')) ?>
<?= view('admin/layouts/topbar', compact('pageTitle', 'breadcrumbs')) ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-image"></i> <?= $pageTitle ?></h5>
                    <a href="<?= base_url('admin/slides') ?>" class="btn btn-light btn-sm">
                        <i class="bi bi-arrow-left"></i> Retour
                    </a>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('admin/slides/save' . (isset($slide) ? '/' . $slide['id'] : '')) ?>" method="POST">
                        <?= csrf_field() ?>
                        
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label">Titre *</label>
                                    <input type="text" class="form-control" name="title" 
                                           value="<?= old('title', $slide['title'] ?? '') ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Sous-titre</label>
                                    <input type="text" class="form-control" name="subtitle" 
                                           value="<?= old('subtitle', $slide['subtitle'] ?? '') ?>">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" name="description" rows="3"><?= old('description', $slide['description'] ?? '') ?></textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Image URL *</label>
                                    <input type="text" class="form-control" name="image" id="imageUrl"
                                           value="<?= old('image', $slide['image'] ?? '') ?>" required
                                           placeholder="/assets/images/slides/slide1.jpg">
                                    <small class="text-muted">URL de l'image de fond</small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Aperçu</label>
                                    <div id="imagePreview" style="max-width: 100%; height: 200px; background: #f0f0f0; border-radius: 8px; overflow: hidden;">
                                        <?php if (!empty($slide['image'])): ?>
                                        <img src="<?= esc($slide['image']) ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <hr class="my-4">
                                <h6>Bouton CTA</h6>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Texte du bouton</label>
                                        <input type="text" class="form-control" name="button_text" 
                                               value="<?= old('button_text', $slide['button_text'] ?? '') ?>">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Lien du bouton</label>
                                        <input type="text" class="form-control" name="button_link" 
                                               value="<?= old('button_link', $slide['button_link'] ?? '') ?>">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Style du bouton</label>
                                        <select class="form-select" name="button_style">
                                            <option value="primary" <?= ($slide['button_style'] ?? 'primary') === 'primary' ? 'selected' : '' ?>>Primary</option>
                                            <option value="secondary" <?= ($slide['button_style'] ?? '') === 'secondary' ? 'selected' : '' ?>>Secondary</option>
                                            <option value="light" <?= ($slide['button_style'] ?? '') === 'light' ? 'selected' : '' ?>>Light</option>
                                            <option value="outline-light" <?= ($slide['button_style'] ?? '') === 'outline-light' ? 'selected' : '' ?>>Outline Light</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <label class="form-label">Statut</label>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="active" value="1" 
                                                   <?= old('active', $slide['active'] ?? 1) ? 'checked' : '' ?>>
                                            <label class="form-check-label">Actif</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="card mb-3">
                                    <div class="card-body">
                                        <label class="form-label">Ordre d'affichage</label>
                                        <input type="number" class="form-control" name="order" 
                                               value="<?= old('order', $slide['order'] ?? 0) ?>">
                                        <small class="text-muted">Plus le nombre est petit, plus la slide apparaît en premier</small>
                                    </div>
                                </div>

                                <div class="card mb-3">
                                    <div class="card-body">
                                        <label class="form-label">Couleur du texte</label>
                                        <input type="color" class="form-control form-control-color" name="text_color" 
                                               value="<?= old('text_color', $slide['text_color'] ?? '#ffffff') ?>">
                                    </div>
                                </div>

                                <div class="card mb-3">
                                    <div class="card-body">
                                        <label class="form-label">Opacité de l'overlay (0-9)</label>
                                        <input type="range" class="form-range" name="overlay_opacity" min="0" max="9" 
                                               value="<?= old('overlay_opacity', $slide['overlay_opacity'] ?? 6) ?>" 
                                               oninput="this.nextElementSibling.textContent = this.value">
                                        <span><?= $slide['overlay_opacity'] ?? 6 ?></span>
                                        <small class="text-muted d-block">0 = transparent, 9 = très sombre</small>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-check-circle"></i> Enregistrer
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('imageUrl').addEventListener('input', function() {
    const url = this.value;
    const preview = document.getElementById('imagePreview');
    if (url) {
        preview.innerHTML = `<img src="${url}" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.src='/assets/images/placeholder.jpg'">`;
    } else {
        preview.innerHTML = '';
    }
});
</script>

<?= view('admin/layouts/footer') ?>
