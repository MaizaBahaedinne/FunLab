<form action="/admin/settings/save" method="POST">
    <input type="hidden" name="category" value="mail">

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5><i class="bi bi-envelope"></i> Configuration Email</h5>
            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#testEmailModal">
                <i class="bi bi-send"></i> Tester
            </button>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Email expéditeur</label>
                        <input type="email" class="form-control" name="settings[mail_from_email]" 
                            value="<?= esc($settings['mail_from_email'] ?? '') ?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Nom expéditeur</label>
                        <input type="text" class="form-control" name="settings[mail_from_name]" 
                            value="<?= esc($settings['mail_from_name'] ?? '') ?>">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Protocole</label>
                        <select class="form-select" name="settings[mail_protocol]">
                            <option value="smtp" <?= ($settings['mail_protocol'] ?? '') === 'smtp' ? 'selected' : '' ?>>SMTP</option>
                            <option value="sendmail" <?= ($settings['mail_protocol'] ?? '') === 'sendmail' ? 'selected' : '' ?>>Sendmail</option>
                            <option value="mail" <?= ($settings['mail_protocol'] ?? '') === 'mail' ? 'selected' : '' ?>>PHP Mail</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Serveur SMTP</label>
                        <input type="text" class="form-control" name="settings[mail_smtp_host]" 
                            value="<?= esc($settings['mail_smtp_host'] ?? '') ?>"
                            placeholder="smtp.gmail.com">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Port SMTP</label>
                        <input type="number" class="form-control" name="settings[mail_smtp_port]" 
                            value="<?= esc($settings['mail_smtp_port'] ?? '587') ?>">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Utilisateur SMTP</label>
                        <input type="text" class="form-control" name="settings[mail_smtp_user]" 
                            value="<?= esc($settings['mail_smtp_user'] ?? '') ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Mot de passe SMTP</label>
                        <input type="password" class="form-control" name="settings[mail_smtp_pass]" 
                            value="<?= esc($settings['mail_smtp_pass'] ?? '') ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Cryptage</label>
                        <select class="form-select" name="settings[mail_smtp_crypto]">
                            <option value="tls" <?= ($settings['mail_smtp_crypto'] ?? 'tls') === 'tls' ? 'selected' : '' ?>>TLS</option>
                            <option value="ssl" <?= ($settings['mail_smtp_crypto'] ?? '') === 'ssl' ? 'selected' : '' ?>>SSL</option>
                        </select>
                    </div>
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

<!-- Modal Test Email -->
<div class="modal fade" id="testEmailModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="/admin/settings/test-email" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Tester la configuration email</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Email de test</label>
                        <input type="email" class="form-control" name="test_email" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Envoyer</button>
                </div>
            </form>
        </div>
    </div>
</div>
