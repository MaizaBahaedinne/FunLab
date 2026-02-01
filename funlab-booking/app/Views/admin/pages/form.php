<?php
$activeMenu = 'pages';
$pageTitle = isset($page) ? 'Modifier la page' : 'Nouvelle page';
$breadcrumbs = ['Admin' => base_url('admin/dashboard'), 'Pages' => base_url('admin/pages'), $pageTitle => null];
?>

<?= view('admin/layouts/header', compact('title')) ?>
<?= view('admin/layouts/sidebar', compact('activeMenu')) ?>
<?= view('admin/layouts/topbar', compact('pageTitle', 'breadcrumbs')) ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-pencil-square"></i> <?= isset($page) ? 'Modifier la page' : 'Nouvelle page' ?></h5>
                    <a href="<?= base_url('admin/pages') ?>" class="btn btn-light btn-sm">
                        <i class="bi bi-arrow-left"></i> Retour
                    </a>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('admin/pages/save' . (isset($page) ? '/' . $page['id'] : '')) ?>" method="POST">
                        <?= csrf_field() ?>
                        
                        <div class="row">
                            <div class="col-md-8">
                                <!-- Titre -->
                                <div class="mb-3">
                                    <label class="form-label">Titre *</label>
                                    <input type="text" class="form-control" name="title" 
                                           value="<?= old('title', $page['title'] ?? '') ?>" required
                                           onkeyup="generateSlug(this.value)">
                                </div>

                                <!-- Slug -->
                                <div class="mb-3">
                                    <label class="form-label">Slug (URL) *</label>
                                    <input type="text" class="form-control" name="slug" id="slug"
                                           value="<?= old('slug', $page['slug'] ?? '') ?>" required>
                                    <small class="text-muted">URL de la page (sans espaces ni caractères spéciaux)</small>
                                </div>

                                <!-- Contenu -->
                                <div class="mb-3">
                                    <label class="form-label">Contenu *</label>
                                    <textarea class="form-control" name="content" rows="15" required><?= old('content', $page['content'] ?? '') ?></textarea>
                                </div>

                                <!-- SEO -->
                                <hr class="my-4">
                                <h6>SEO</h6>
                                
                                <div class="mb-3">
                                    <label class="form-label">Titre SEO (meta title)</label>
                                    <input type="text" class="form-control" name="meta_title" 
                                           value="<?= old('meta_title', $page['meta_title'] ?? '') ?>">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Description SEO (meta description)</label>
                                    <textarea class="form-control" name="meta_description" rows="3"><?= old('meta_description', $page['meta_description'] ?? '') ?></textarea>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <!-- Statut -->
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <label class="form-label">Statut</label>
                                        <select class="form-select" name="status">
                                            <option value="draft" <?= old('status', $page['status'] ?? 'draft') === 'draft' ? 'selected' : '' ?>>Brouillon</option>
                                            <option value="published" <?= old('status', $page['status'] ?? '') === 'published' ? 'selected' : '' ?>>Publié</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Template -->
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <label class="form-label">Template</label>
                                        <select class="form-select" name="template">
                                            <option value="default" <?= old('template', $page['template'] ?? 'default') === 'default' ? 'selected' : '' ?>>Par défaut</option>
                                            <option value="full-width" <?= old('template', $page['template'] ?? '') === 'full-width' ? 'selected' : '' ?>>Pleine largeur</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="card">
                                    <div class="card-body">
                                        <button type="submit" class="btn btn-primary w-100 mb-2">
                                            <i class="bi bi-check-circle"></i> Enregistrer
                                        </button>
                                        <?php if (isset($page) && $page['status'] === 'published'): ?>
                                        <a href="<?= base_url($page['slug']) ?>" target="_blank" class="btn btn-outline-info w-100">
                                            <i class="bi bi-eye"></i> Voir la page
                                        </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function generateSlug(title) {
    const slug = title.toLowerCase()
        .replace(/[àáâãäå]/g, 'a')
        .replace(/[èéêë]/g, 'e')
        .replace(/[ìíîï]/g, 'i')
        .replace(/[òóôõö]/g, 'o')
        .replace(/[ùúûü]/g, 'u')
        .replace(/[ç]/g, 'c')
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/^-+|-+$/g, '');
    
    document.getElementById('slug').value = slug;
}
</script>

<?= view('admin/layouts/footer') ?>
