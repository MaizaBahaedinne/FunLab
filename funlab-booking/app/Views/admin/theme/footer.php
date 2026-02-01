<?php
$activeMenu = 'settings';
$pageTitle = 'Options du pied de page';
$breadcrumbs = ['Admin' => base_url('admin/dashboard'), 'Apparence' => null, 'Pied de page' => null];
?>

<?= view('admin/layouts/header', compact('title')) ?>
<?= view('admin/layouts/sidebar', compact('activeMenu')) ?>
<?= view('admin/layouts/topbar', compact('pageTitle', 'breadcrumbs')) ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-layout-text-window-reverse"></i> Options du pied de page</h5>
                </div>
                <div class="card-body">
                    <form id="footerThemeForm">
                        <?= csrf_field() ?>
                        
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="footer_show_social" 
                                           value="1" <?= ($settings['footer_show_social'] ?? '1') === '1' ? 'checked' : '' ?>>
                                    <label class="form-check-label">Afficher les réseaux sociaux</label>
                                </div>
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="form-label">Nombre de colonnes</label>
                                <select class="form-select" name="footer_columns">
                                    <option value="3" <?= ($settings['footer_columns'] ?? '4') === '3' ? 'selected' : '' ?>>3 colonnes</option>
                                    <option value="4" <?= ($settings['footer_columns'] ?? '4') === '4' ? 'selected' : '' ?>>4 colonnes</option>
                                </select>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Texte du copyright</label>
                                <input type="text" class="form-control" name="footer_copyright" 
                                       value="<?= $settings['footer_copyright'] ?? '© {year} FunLab Tunisie. Tous droits réservés.' ?>">
                                <small class="text-muted">Utilisez {year} pour l'année actuelle</small>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> Pour modifier le contenu du footer (À propos, Contact, etc.), 
                            allez dans <a href="<?= base_url('admin/settings/footer') ?>">Pages & Contenu → Footer</a>
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
document.getElementById('footerThemeForm').addEventListener('submit', async function(e) {
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
