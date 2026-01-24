<form action="/admin/settings/save" method="POST">
    <input type="hidden" name="category" value="sms">

    <div class="card">
        <div class="card-header">
            <h5><i class="bi bi-phone"></i> Configuration SMS</h5>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <div class="form-check form-switch">
                    <input type="checkbox" class="form-check-input" name="settings[sms_enabled]" 
                        value="1" <?= ($settings['sms_enabled'] ?? false) ? 'checked' : '' ?>>
                    <label class="form-check-label">Activer les notifications SMS</label>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Fournisseur SMS</label>
                <select class="form-select" name="settings[sms_provider]">
                    <option value="">Sélectionner un fournisseur</option>
                    <option value="twilio" <?= ($settings['sms_provider'] ?? '') === 'twilio' ? 'selected' : '' ?>>Twilio</option>
                    <option value="nexmo" <?= ($settings['sms_provider'] ?? '') === 'nexmo' ? 'selected' : '' ?>>Nexmo/Vonage</option>
                    <option value="messagerie" <?= ($settings['sms_provider'] ?? '') === 'messagerie' ? 'selected' : '' ?>>Messagerie</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Clé API SMS</label>
                <input type="text" class="form-control" name="settings[sms_api_key]" 
                    value="<?= esc($settings['sms_api_key'] ?? '') ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Nom de l'expéditeur</label>
                <input type="text" class="form-control" name="settings[sms_sender_name]" 
                    value="<?= esc($settings['sms_sender_name'] ?? '') ?>"
                    maxlength="11">
                <small class="text-muted">Maximum 11 caractères</small>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save"></i> Sauvegarder
            </button>
        </div>
    </div>
</form>
