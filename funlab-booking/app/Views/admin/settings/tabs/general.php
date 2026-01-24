<form action="/admin/settings/save" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="category" value="general">

    <div class="card">
        <div class="card-header">
            <h5><i class="bi bi-info-circle"></i> Paramètres généraux</h5>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label">Nom de l'application</label>
                <input type="text" class="form-control" name="settings[app_name]" 
                    value="<?= esc($settings['app_name'] ?? 'FunLab') ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea class="form-control" name="settings[app_description]" rows="3"><?= esc($settings['app_description'] ?? '') ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Logo de l'application</label>
                <?php if (!empty($settings['app_logo'])): ?>
                    <div class="mb-2">
                        <img src="<?= esc($settings['app_logo']) ?>" alt="Logo" style="max-height: 100px;">
                    </div>
                <?php endif; ?>
                <input type="file" class="form-control" id="logoUpload" accept="image/*">
                <input type="hidden" name="settings[app_logo]" id="logoPath" value="<?= esc($settings['app_logo'] ?? '') ?>">
                <small class="text-muted">Format: JPG, PNG, GIF (Max 2MB)</small>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <label class="form-label">Fuseau horaire</label>
                    <select class="form-select" name="settings[timezone]">
                        <option value="Africa/Tunis" <?= ($settings['timezone'] ?? '') === 'Africa/Tunis' ? 'selected' : '' ?>>Africa/Tunis</option>
                        <option value="Europe/Paris" <?= ($settings['timezone'] ?? '') === 'Europe/Paris' ? 'selected' : '' ?>>Europe/Paris</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Format de date</label>
                    <input type="text" class="form-control" name="settings[date_format]" 
                        value="<?= esc($settings['date_format'] ?? 'd/m/Y') ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Format d'heure</label>
                    <input type="text" class="form-control" name="settings[time_format]" 
                        value="<?= esc($settings['time_format'] ?? 'H:i') ?>">
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save"></i> Sauvegarder
            </button>
        </div>
    </div>
</form>

<script>
document.getElementById('logoUpload').addEventListener('change', async function(e) {
    const file = e.target.files[0];
    if (!file) return;

    const formData = new FormData();
    formData.append('image', file);
    formData.append('key', 'app_logo');
    formData.append('category', 'general');

    try {
        const response = await fetch('/admin/settings/upload-image', {
            method: 'POST',
            body: formData
        });
        const data = await response.json();
        
        if (data.status === 'success') {
            document.getElementById('logoPath').value = data.path;
            alert('Image téléchargée avec succès');
        } else {
            alert('Erreur: ' + data.message);
        }
    } catch (error) {
        alert('Erreur lors du téléchargement');
    }
});
</script>
