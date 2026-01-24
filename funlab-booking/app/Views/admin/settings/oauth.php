<?php
$title = 'Authentification OAuth';
$pageTitle = 'Configuration OAuth';
$activeMenu = 'settings-oauth';
$breadcrumbs = ['Admin' => base_url('admin'), 'Paramètres' => base_url('admin/settings'), 'OAuth' => null];
?>

<?= view('admin/layouts/header', compact('title')) ?>
<?= view('admin/layouts/sidebar', compact('activeMenu')) ?>
<?= view('admin/layouts/topbar', compact('pageTitle', 'breadcrumbs')) ?>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <form action="/admin/settings/save" method="POST">
                <input type="hidden" name="category" value="oauth">

                <!-- Google OAuth -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-google text-danger"></i> Google OAuth
                        </h5>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="settings[oauth_google_enabled]" 
                                id="googleEnabled" value="1" 
                                <?= ($settings['oauth_google_enabled'] ?? '0') === '1' ? 'checked' : '' ?>>
                            <label class="form-check-label" for="googleEnabled">Activer</label>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <strong><i class="bi bi-info-circle"></i> Configuration Google OAuth</strong>
                            <ol class="mb-0 mt-2">
                                <li>Créez un projet dans <a href="https://console.cloud.google.com" target="_blank">Google Cloud Console</a></li>
                                <li>Activez l'API "Google+ API"</li>
                                <li>Créez des identifiants OAuth 2.0</li>
                                <li>Ajoutez l'URI de redirection autorisée : <code><?= base_url('auth/google/callback') ?></code></li>
                                <li>Copiez le Client ID et Client Secret ci-dessous</li>
                            </ol>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Client ID</label>
                                    <input type="text" class="form-control" name="settings[oauth_google_client_id]" 
                                        value="<?= esc($settings['oauth_google_client_id'] ?? '') ?>"
                                        placeholder="123456789-abc...xyz.apps.googleusercontent.com">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Client Secret</label>
                                    <input type="password" class="form-control" name="settings[oauth_google_client_secret]" 
                                        value="<?= esc($settings['oauth_google_client_secret'] ?? '') ?>"
                                        placeholder="GOCSPX-...">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">URI de redirection (à copier dans Google Console)</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="googleRedirectUri" 
                                    value="<?= base_url('auth/google/callback') ?>" readonly>
                                <button class="btn btn-outline-secondary" type="button" onclick="copyToClipboard('googleRedirectUri')">
                                    <i class="bi bi-clipboard"></i> Copier
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Facebook OAuth -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-facebook text-primary"></i> Facebook OAuth
                        </h5>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="settings[oauth_facebook_enabled]" 
                                id="facebookEnabled" value="1" 
                                <?= ($settings['oauth_facebook_enabled'] ?? '0') === '1' ? 'checked' : '' ?>>
                            <label class="form-check-label" for="facebookEnabled">Activer</label>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <strong><i class="bi bi-info-circle"></i> Configuration Facebook OAuth</strong>
                            <ol class="mb-0 mt-2">
                                <li>Créez une application dans <a href="https://developers.facebook.com" target="_blank">Facebook Developers</a></li>
                                <li>Ajoutez le produit "Facebook Login"</li>
                                <li>Configurez les paramètres OAuth</li>
                                <li>Ajoutez l'URI de redirection OAuth valide : <code><?= base_url('auth/facebook/callback') ?></code></li>
                                <li>Copiez l'App ID et App Secret ci-dessous</li>
                            </ol>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">App ID</label>
                                    <input type="text" class="form-control" name="settings[oauth_facebook_app_id]" 
                                        value="<?= esc($settings['oauth_facebook_app_id'] ?? '') ?>"
                                        placeholder="123456789012345">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">App Secret</label>
                                    <input type="password" class="form-control" name="settings[oauth_facebook_app_secret]" 
                                        value="<?= esc($settings['oauth_facebook_app_secret'] ?? '') ?>"
                                        placeholder="abc123...xyz789">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">URI de redirection (à copier dans Facebook App)</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="facebookRedirectUri" 
                                    value="<?= base_url('auth/facebook/callback') ?>" readonly>
                                <button class="btn btn-outline-secondary" type="button" onclick="copyToClipboard('facebookRedirectUri')">
                                    <i class="bi bi-clipboard"></i> Copier
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Sauvegarder
                    </button>
                </div>
            </form>

<?php
$additionalJS = <<<'JS'
<script>
    function copyToClipboard(elementId) {
        const input = document.getElementById(elementId);
        input.select();
        input.setSelectionRange(0, 99999); // Pour mobile
        
        navigator.clipboard.writeText(input.value).then(() => {
            // Feedback visuel
            const button = event.target.closest('button');
            const originalHtml = button.innerHTML;
            button.innerHTML = '<i class="bi bi-check"></i> Copié !';
            button.classList.remove('btn-outline-secondary');
            button.classList.add('btn-success');
            
            setTimeout(() => {
                button.innerHTML = originalHtml;
                button.classList.remove('btn-success');
                button.classList.add('btn-outline-secondary');
            }, 2000);
        });
    }
</script>
JS;
?>

<?= view('admin/layouts/footer', compact('additionalJS')) ?>
