<form action="/admin/settings/save" method="POST">
    <input type="hidden" name="category" value="seo">

    <div class="card">
        <div class="card-header">
            <h5><i class="bi bi-search"></i> Référencement SEO</h5>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label">Titre SEO</label>
                <input type="text" class="form-control" name="settings[seo_title]" 
                    value="<?= esc($settings['seo_title'] ?? '') ?>"
                    maxlength="60">
                <small class="text-muted">Recommandé: 50-60 caractères</small>
            </div>

            <div class="mb-3">
                <label class="form-label">Description SEO</label>
                <textarea class="form-control" name="settings[seo_description]" 
                    rows="3" maxlength="160"><?= esc($settings['seo_description'] ?? '') ?></textarea>
                <small class="text-muted">Recommandé: 150-160 caractères</small>
            </div>

            <div class="mb-3">
                <label class="form-label">Mots-clés</label>
                <textarea class="form-control" name="settings[seo_keywords]" 
                    rows="2"><?= esc($settings['seo_keywords'] ?? '') ?></textarea>
                <small class="text-muted">Séparer les mots-clés par des virgules</small>
            </div>

            <div class="mb-3">
                <label class="form-label">Image Open Graph (Réseaux sociaux)</label>
                <?php if (!empty($settings['seo_og_image'])): ?>
                    <div class="mb-2">
                        <img src="<?= esc($settings['seo_og_image']) ?>" alt="OG Image" style="max-height: 200px;">
                    </div>
                <?php endif; ?>
                <input type="file" class="form-control" id="ogImageUpload" accept="image/*">
                <input type="hidden" name="settings[seo_og_image]" id="ogImagePath" value="<?= esc($settings['seo_og_image'] ?? '') ?>">
                <small class="text-muted">Recommandé: 1200x630px</small>
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
document.getElementById('ogImageUpload').addEventListener('change', async function(e) {
    const file = e.target.files[0];
    if (!file) return;

    const formData = new FormData();
    formData.append('image', file);
    formData.append('key', 'seo_og_image');
    formData.append('category', 'seo');

    try {
        const response = await fetch('/admin/settings/upload-image', {
            method: 'POST',
            body: formData
        });
        const data = await response.json();
        
        if (data.status === 'success') {
            document.getElementById('ogImagePath').value = data.path;
            alert('Image téléchargée avec succès');
        } else {
            alert('Erreur: ' + data.message);
        }
    } catch (error) {
        alert('Erreur lors du téléchargement');
    }
});
</script>
