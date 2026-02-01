<?php
$activeMenu = 'settings';
$pageTitle = 'Options de l\'en-tête';
$breadcrumbs = ['Admin' => base_url('admin/dashboard'), 'Apparence' => null, 'En-tête' => null];
?>

<?= view('admin/layouts/header', compact('title')) ?>
<?= view('admin/layouts/sidebar', compact('activeMenu')) ?>
<?= view('admin/layouts/topbar', compact('pageTitle', 'breadcrumbs')) ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-layout-text-window"></i> Options de l'en-tête</h5>
                </div>
                <div class="card-body">
                    <form id="headerForm">
                        <?= csrf_field() ?>
                        
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="header_sticky" 
                                           value="1" <?= ($settings['header_sticky'] ?? '1') === '1' ? 'checked' : '' ?>>
                                    <label class="form-check-label">En-tête fixe (sticky)</label>
                                </div>
                                <small class="text-muted">L'en-tête reste visible lors du défilement</small>
                            </div>

                            <div class="col-md-6 mb-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="header_show_topbar" 
                                           value="1" <?= ($settings['header_show_topbar'] ?? '1') === '1' ? 'checked' : '' ?>>
                                    <label class="form-check-label">Afficher la barre du haut</label>
                                </div>
                                <small class="text-muted">Barre noire avec téléphone/email</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Téléphone (barre du haut)</label>
                                <input type="text" class="form-control" name="header_topbar_phone" 
                                       value="<?= $settings['header_topbar_phone'] ?? '' ?>">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email (barre du haut)</label>
                                <input type="email" class="form-control" name="header_topbar_email" 
                                       value="<?= $settings['header_topbar_email'] ?? '' ?>">
                            </div>
                        </div>

                        <div class="text-end mt-4">
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
document.getElementById('headerForm').addEventListener('submit', async function(e) {
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
        }
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Erreur',
            text: 'Une erreur est survenue'
        });
    }
});
</script>

<?= view('admin/layouts/footer') ?>
