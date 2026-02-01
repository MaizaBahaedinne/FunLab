<?php
$activeMenu = 'settings';
$pageTitle = 'Typographie';
$breadcrumbs = ['Admin' => base_url('admin/dashboard'), 'Apparence' => null, 'Typographie' => null];
?>

<?= view('admin/layouts/header', compact('title')) ?>
<?= view('admin/layouts/sidebar', compact('activeMenu')) ?>
<?= view('admin/layouts/topbar', compact('pageTitle', 'breadcrumbs')) ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-fonts"></i> Typographie</h5>
                </div>
                <div class="card-body">
                    <form id="typographyForm">
                        <?= csrf_field() ?>
                        
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Police des titres</label>
                                <select class="form-select" name="font_heading">
                                    <option value="Oswald" <?= ($settings['font_heading'] ?? 'Oswald') === 'Oswald' ? 'selected' : '' ?>>Oswald</option>
                                    <option value="Roboto" <?= ($settings['font_heading'] ?? '') === 'Roboto' ? 'selected' : '' ?>>Roboto</option>
                                    <option value="Montserrat" <?= ($settings['font_heading'] ?? '') === 'Montserrat' ? 'selected' : '' ?>>Montserrat</option>
                                    <option value="Poppins" <?= ($settings['font_heading'] ?? '') === 'Poppins' ? 'selected' : '' ?>>Poppins</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="form-label">Police du texte</label>
                                <select class="form-select" name="font_body">
                                    <option value="Roboto" <?= ($settings['font_body'] ?? 'Roboto') === 'Roboto' ? 'selected' : '' ?>>Roboto</option>
                                    <option value="Open Sans" <?= ($settings['font_body'] ?? '') === 'Open Sans' ? 'selected' : '' ?>>Open Sans</option>
                                    <option value="Lato" <?= ($settings['font_body'] ?? '') === 'Lato' ? 'selected' : '' ?>>Lato</option>
                                    <option value="Poppins" <?= ($settings['font_body'] ?? '') === 'Poppins' ? 'selected' : '' ?>>Poppins</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="form-label">Taille de base (px)</label>
                                <input type="number" class="form-control" name="font_size_base" 
                                       value="<?= $settings['font_size_base'] ?? 16 ?>">
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
document.getElementById('typographyForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    try {
        const response = await fetch('<?= base_url('admin/theme/save') ?>', {
            method: 'POST',
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
