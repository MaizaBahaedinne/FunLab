<?php
$title = 'Page Contact';
$activeMenu = 'settings-contact';
$pageTitle = 'Configuration Page Contact';
$breadcrumbs = [
    ['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
    ['title' => 'Paramètres', 'url' => base_url('admin/settings')],
    ['title' => 'Contact', 'url' => '']
];

// Charger les paramètres existants
$contactSettings = [];
if (isset($settings) && is_array($settings)) {
    foreach ($settings as $setting) {
        if (is_array($setting) && isset($setting['key']) && isset($setting['value'])) {
            $contactSettings[$setting['key']] = $setting['value'];
        }
    }
}

// Debug pour vérifier le chargement
log_message('debug', 'Contact settings loaded: ' . json_encode($contactSettings));
?>

<?= view('admin/layouts/header', compact('title')) ?>
<?= view('admin/layouts/sidebar', compact('activeMenu')) ?>
<?= view('admin/layouts/topbar', compact('pageTitle', 'breadcrumbs')) ?>

<div class="container-fluid">
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle"></i> <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle"></i> <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-lg-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-envelope"></i> Configuration Page Contact</h5>
                    <a href="<?= base_url('contact') ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-eye"></i> Aperçu
                    </a>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?= base_url('admin/settings/contact') ?>">
                        <?= csrf_field() ?>

                        <!-- En-tête -->
                        <div class="mb-4">
                            <h6 class="border-bottom pb-2 mb-3">
                                <i class="bi bi-type"></i> En-tête de la page
                            </h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Titre Principal</label>
                                    <input type="text" name="contact_title" class="form-control" 
                                           value="<?= esc($contactSettings['contact_title'] ?? 'Contactez-Nous') ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Sous-titre</label>
                                    <input type="text" name="contact_subtitle" class="form-control" 
                                           value="<?= esc($contactSettings['contact_subtitle'] ?? 'Nous sommes là pour vous') ?>">
                                </div>
                            </div>
                        </div>

                        <!-- Coordonnées -->
                        <div class="mb-4">
                            <h6 class="border-bottom pb-2 mb-3">
                                <i class="bi bi-geo-alt"></i> Coordonnées
                            </h6>
                            <div class="mb-3">
                                <label class="form-label">Adresse Complète</label>
                                <textarea name="contact_address" class="form-control" rows="2"><?= esc($contactSettings['contact_address'] ?? '') ?></textarea>
                                <small class="text-muted">Adresse physique de votre établissement</small>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Téléphone Principal</label>
                                    <input type="text" name="contact_phone" class="form-control" 
                                           value="<?= esc($contactSettings['contact_phone'] ?? '') ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="contact_email" class="form-control" 
                                           value="<?= esc($contactSettings['contact_email'] ?? '') ?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">WhatsApp</label>
                                    <input type="text" name="contact_whatsapp" class="form-control" 
                                           value="<?= esc($contactSettings['contact_whatsapp'] ?? '') ?>"
                                           placeholder="+216 XX XXX XXX">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Facebook Messenger</label>
                                    <input type="text" name="contact_messenger" class="form-control" 
                                           value="<?= esc($contactSettings['contact_messenger'] ?? '') ?>"
                                           placeholder="https://m.me/username">
                                </div>
                            </div>
                        </div>

                        <!-- Carte Google Maps -->
                        <div class="mb-4">
                            <h6 class="border-bottom pb-2 mb-3">
                                <i class="bi bi-map"></i> Carte Google Maps
                            </h6>
                            <div class="mb-3">
                                <label class="form-label">Code d'intégration Google Maps</label>
                                <textarea name="contact_map_embed" class="form-control" rows="4"><?= esc($contactSettings['contact_map_embed'] ?? '') ?></textarea>
                                <small class="text-muted">
                                    Coller le code iframe depuis Google Maps (Partager > Intégrer une carte)
                                </small>
                            </div>
                        </div>

                        <!-- Horaires d'ouverture -->
                        <div class="mb-4">
                            <h6 class="border-bottom pb-2 mb-3">
                                <i class="bi bi-clock"></i> Horaires d'Ouverture
                            </h6>
                            <div class="mb-3">
                                <label class="form-label">Texte Horaires</label>
                                <textarea name="contact_hours_text" class="form-control" rows="3"><?= esc($contactSettings['contact_hours_text'] ?? '') ?></textarea>
                                <small class="text-muted">
                                    Ou laissez vide pour charger automatiquement depuis Paramètres > Horaires
                                </small>
                            </div>
                        </div>

                        <!-- Email de réception -->
                        <div class="mb-4">
                            <h6 class="border-bottom pb-2 mb-3">
                                <i class="bi bi-inbox"></i> Formulaire de Contact
                            </h6>
                            <div class="mb-3">
                                <label class="form-label">Email de Réception</label>
                                <input type="email" name="contact_receive_email" class="form-control" 
                                       value="<?= esc($contactSettings['contact_receive_email'] ?? '') ?>">
                                <small class="text-muted">
                                    Les messages du formulaire seront envoyés à cette adresse
                                </small>
                            </div>
                        </div>

                        <!-- Texte personnalisé -->
                        <div class="mb-4">
                            <h6 class="border-bottom pb-2 mb-3">
                                <i class="bi bi-chat-text"></i> Texte Personnalisé
                            </h6>
                            <div class="mb-3">
                                <label class="form-label">Message d'accueil</label>
                                <textarea name="contact_welcome_text" class="form-control" rows="3"><?= esc($contactSettings['contact_welcome_text'] ?? '') ?></textarea>
                                <small class="text-muted">Message affiché en haut du formulaire</small>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Enregistrer les Modifications
                            </button>
                            <a href="<?= base_url('contact') ?>" target="_blank" class="btn btn-outline-secondary">
                                <i class="bi bi-eye"></i> Voir la Page
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= view('admin/layouts/footer') ?>
