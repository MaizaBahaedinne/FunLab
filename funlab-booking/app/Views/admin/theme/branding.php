<?php
$activeMenu = 'settings';
$pageTitle = 'Logo & Branding';
$breadcrumbs = ['Admin' => base_url('admin/dashboard'), 'Apparence' => null, 'Logo & Branding' => null];
?>

<?= view('admin/layouts/header', compact('title')) ?>
<?= view('admin/layouts/sidebar', compact('activeMenu')) ?>
<?= view('admin/layouts/topbar', compact('pageTitle', 'breadcrumbs')) ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-palette"></i> Logo & Branding</h5>
                </div>
                <div class="card-body">
                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success alert-dismissible">
                            <?= session()->getFlashdata('success') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form id="brandingForm">
                        <?= csrf_field() ?>
                        
                        <!-- Logo -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h6 class="border-bottom pb-2 mb-3">Logo du site</h6>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">URL du logo</label>
                                    <input type="text" class="form-control" name="site_logo" 
                                           value="<?= $settings['site_logo'] ?? '/assets/images/logo.png' ?>">
                                    <small class="text-muted">Chemin complet ou URL du logo</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Largeur (px)</label>
                                    <input type="number" class="form-control" name="logo_width" 
                                           value="<?= $settings['logo_width'] ?? 60 ?>">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Hauteur (px)</label>
                                    <input type="number" class="form-control" name="logo_height" 
                                           value="<?= $settings['logo_height'] ?? 60 ?>">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="p-3 bg-light rounded">
                                    <label class="form-label">Aperçu</label>
                                    <div id="logoPreview">
                                        <img src="<?= $settings['site_logo'] ?? '/assets/images/logo.png' ?>" 
                                             width="<?= $settings['logo_width'] ?? 60 ?>" 
                                             height="<?= $settings['logo_height'] ?? 60 ?>"
                                             alt="Logo" class="border">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <!-- Favicon -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h6 class="border-bottom pb-2 mb-3">Favicon</h6>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">URL du favicon</label>
                                    <input type="text" class="form-control" name="site_favicon" 
                                           value="<?= $settings['site_favicon'] ?? '/assets/images/favicon.ico' ?>">
                                    <small class="text-muted">Format .ico, .png (32x32 px recommandé)</small>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <!-- Nom du site -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h6 class="border-bottom pb-2 mb-3">Identité</h6>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Nom du site</label>
                                    <input type="text" class="form-control" name="site_name" 
                                           value="<?= $settings['site_name'] ?? 'FunLab Tunisie' ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Slogan</label>
                                    <input type="text" class="form-control" name="site_tagline" 
                                           value="<?= $settings['site_tagline'] ?? 'Centre d\'activités indoor' ?>">
                                </div>
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('brandingForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    try {
        const response = await fetch('<?= base_url('admin/theme/save') ?>', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        });
        
        const result = await response.json();
        
        if (result.status === 'success') {
            Swal.fire({
                icon: 'success',
                title: 'Succès',
                text: result.message,
                timer: 2000
            }).then(() => location.reload());
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Erreur',
                text: result.message
            });
        }
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Erreur',
            text: 'Une erreur est survenue'
        });
    }
});

// Preview logo
document.querySelectorAll('[name="site_logo"], [name="logo_width"], [name="logo_height"]').forEach(input => {
    input.addEventListener('input', function() {
        const logo = document.querySelector('[name="site_logo"]').value;
        const width = document.querySelector('[name="logo_width"]').value;
        const height = document.querySelector('[name="logo_height"]').value;
        
        document.getElementById('logoPreview').innerHTML = `
            <img src="${logo}" width="${width}" height="${height}" alt="Logo" class="border">
        `;
    });
});
</script>

<?= view('admin/layouts/footer') ?>
