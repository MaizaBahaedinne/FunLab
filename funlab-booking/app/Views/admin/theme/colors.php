<?php
$activeMenu = 'settings';
$pageTitle = 'Couleurs du thème';
$breadcrumbs = ['Admin' => base_url('admin/dashboard'), 'Apparence' => null, 'Couleurs' => null];
?>

<?= view('admin/layouts/header', compact('title')) ?>
<?= view('admin/layouts/sidebar', compact('activeMenu')) ?>
<?= view('admin/layouts/topbar', compact('pageTitle', 'breadcrumbs')) ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-palette-fill"></i> Couleurs du thème</h5>
                </div>
                <div class="card-body">
                    <form id="colorsForm">
                        <?= csrf_field() ?>
                        
                        <div class="row">
                            <!-- Couleur Primaire -->
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Couleur primaire</label>
                                <div class="input-group">
                                    <input type="color" class="form-control form-control-color" name="color_primary" 
                                           value="<?= $settings['color_primary'] ?? '#ff6b35' ?>">
                                    <input type="text" class="form-control" 
                                           value="<?= $settings['color_primary'] ?? '#ff6b35' ?>"
                                           onchange="this.previousElementSibling.value = this.value">
                                </div>
                                <small class="text-muted">Boutons, liens, éléments principaux</small>
                            </div>

                            <!-- Couleur Secondaire -->
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Couleur secondaire</label>
                                <div class="input-group">
                                    <input type="color" class="form-control form-control-color" name="color_secondary" 
                                           value="<?= $settings['color_secondary'] ?? '#004e89' ?>">
                                    <input type="text" class="form-control" 
                                           value="<?= $settings['color_secondary'] ?? '#004e89' ?>"
                                           onchange="this.previousElementSibling.value = this.value">
                                </div>
                                <small class="text-muted">Éléments secondaires</small>
                            </div>

                            <!-- Couleur Sombre -->
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Couleur sombre</label>
                                <div class="input-group">
                                    <input type="color" class="form-control form-control-color" name="color_dark" 
                                           value="<?= $settings['color_dark'] ?? '#1a1a1a' ?>">
                                    <input type="text" class="form-control" 
                                           value="<?= $settings['color_dark'] ?? '#1a1a1a' ?>"
                                           onchange="this.previousElementSibling.value = this.value">
                                </div>
                                <small class="text-muted">Texte, bordures sombres</small>
                            </div>

                            <!-- Couleur Claire -->
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Couleur claire</label>
                                <div class="input-group">
                                    <input type="color" class="form-control form-control-color" name="color_light" 
                                           value="<?= $settings['color_light'] ?? '#f7f7f7' ?>">
                                    <input type="text" class="form-control" 
                                           value="<?= $settings['color_light'] ?? '#f7f7f7' ?>"
                                           onchange="this.previousElementSibling.value = this.value">
                                </div>
                                <small class="text-muted">Fond clair, sections</small>
                            </div>

                            <!-- Couleur du texte -->
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Couleur du texte</label>
                                <div class="input-group">
                                    <input type="color" class="form-control form-control-color" name="color_text" 
                                           value="<?= $settings['color_text'] ?? '#333333' ?>">
                                    <input type="text" class="form-control" 
                                           value="<?= $settings['color_text'] ?? '#333333' ?>"
                                           onchange="this.previousElementSibling.value = this.value">
                                </div>
                                <small class="text-muted">Texte principal</small>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Aperçu -->
                        <div class="row">
                            <div class="col-12">
                                <h6 class="mb-3">Aperçu</h6>
                                <div id="colorPreview" class="p-4 bg-light rounded">
                                    <div class="mb-3">
                                        <button type="button" class="btn" style="background: <?= $settings['color_primary'] ?? '#ff6b35' ?>; color: white;">
                                            Bouton Primaire
                                        </button>
                                        <button type="button" class="btn ms-2" style="background: <?= $settings['color_secondary'] ?? '#004e89' ?>; color: white;">
                                            Bouton Secondaire
                                        </button>
                                    </div>
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 style="color: <?= $settings['color_text'] ?? '#333333' ?>">Exemple de titre</h5>
                                            <p style="color: <?= $settings['color_text'] ?? '#333333' ?>">
                                                Ceci est un exemple de texte avec vos couleurs personnalisées.
                                                <a href="#" style="color: <?= $settings['color_primary'] ?? '#ff6b35' ?>">Lien exemple</a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-end mt-4">
                            <button type="button" class="btn btn-secondary" onclick="resetColors()">
                                <i class="bi bi-arrow-clockwise"></i> Réinitialiser
                            </button>
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
document.getElementById('colorsForm').addEventListener('submit', async function(e) {
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

// Sync color picker with text input
document.querySelectorAll('input[type="color"]').forEach(picker => {
    picker.addEventListener('input', function() {
        this.nextElementSibling.value = this.value;
        updatePreview();
    });
});

// Update preview
function updatePreview() {
    const primary = document.querySelector('[name="color_primary"]').value;
    const secondary = document.querySelector('[name="color_secondary"]').value;
    const text = document.querySelector('[name="color_text"]').value;
    
    const preview = document.getElementById('colorPreview');
    preview.querySelector('button:nth-child(1)').style.background = primary;
    preview.querySelector('button:nth-child(2)').style.background = secondary;
    preview.querySelectorAll('h5, p').forEach(el => el.style.color = text);
    preview.querySelector('a').style.color = primary;
}

// Reset to defaults
function resetColors() {
    document.querySelector('[name="color_primary"]').value = '#ff6b35';
    document.querySelector('[name="color_secondary"]').value = '#004e89';
    document.querySelector('[name="color_dark"]').value = '#1a1a1a';
    document.querySelector('[name="color_light"]').value = '#f7f7f7';
    document.querySelector('[name="color_text"]').value = '#333333';
    
    document.querySelectorAll('input[type="color"]').forEach(picker => {
        picker.nextElementSibling.value = picker.value;
    });
    
    updatePreview();
}
</script>

<?= view('admin/layouts/footer') ?>
