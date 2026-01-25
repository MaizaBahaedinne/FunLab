<?php
$additionalStyles = <<<CSS
<style>
.contact-hero {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 80px 0 60px;
    margin-bottom: 50px;
}

.contact-info-card {
    background: white;
    border-radius: 20px;
    padding: 40px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    margin-bottom: 30px;
    height: 100%;
}

.contact-item {
    display: flex;
    align-items: start;
    gap: 20px;
    margin-bottom: 30px;
    padding-bottom: 30px;
    border-bottom: 1px solid #e2e8f0;
}

.contact-item:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

.contact-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    flex-shrink: 0;
}

.contact-content h4 {
    font-size: 1.1rem;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 8px;
}

.contact-content p {
    color: #4a5568;
    margin: 0;
    font-size: 1rem;
    line-height: 1.6;
}

.contact-content a {
    color: #667eea;
    text-decoration: none;
}

.contact-content a:hover {
    text-decoration: underline;
}

.contact-form-card {
    background: white;
    border-radius: 20px;
    padding: 40px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.form-label {
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 8px;
}

.form-control, .form-select {
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    padding: 12px 15px;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.btn-send {
    width: 100%;
    padding: 15px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    color: white;
    font-weight: 600;
    border-radius: 10px;
    font-size: 1.1rem;
    transition: all 0.3s ease;
}

.btn-send:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
}

.map-container {
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    margin-bottom: 30px;
}

.map-container iframe {
    width: 100%;
    height: 450px;
    border: none;
}

.hours-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.hours-list li {
    padding: 10px 0;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    justify-content: space-between;
}

.hours-list li:last-child {
    border-bottom: none;
}

.day-name {
    font-weight: 600;
    color: #2d3748;
}

.hours-time {
    color: #667eea;
    font-weight: 500;
}

.hours-closed {
    color: #a0aec0;
    font-style: italic;
}

.social-buttons {
    display: flex;
    gap: 10px;
    margin-top: 20px;
}

.social-btn {
    flex: 1;
    padding: 12px;
    border-radius: 10px;
    color: white;
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.social-btn:hover {
    transform: translateY(-2px);
    color: white;
}

.social-btn.whatsapp {
    background: #25d366;
}

.social-btn.messenger {
    background: #0084ff;
}

.welcome-message {
    background: #f7fafc;
    border-left: 4px solid #667eea;
    padding: 20px;
    border-radius: 10px;
    margin-bottom: 30px;
}

.welcome-message p {
    margin: 0;
    color: #4a5568;
    line-height: 1.6;
}
</style>
CSS;
?>

<?= view('front/layouts/header', array_merge(compact('title', 'additionalStyles'), [
    'metaTitle' => $metaTitle ?? '',
    'metaDescription' => $metaDescription ?? '',
    'metaKeywords' => $metaKeywords ?? '',
    'canonicalUrl' => $canonicalUrl ?? '',
    'ogType' => $ogType ?? '',
    'ogUrl' => $ogUrl ?? '',
    'ogTitle' => $ogTitle ?? '',
    'ogDescription' => $ogDescription ?? ''
])) ?>
<?= view('front/layouts/navbar', compact('activeMenu')) ?>

<!-- Hero Section -->
<section class="contact-hero">
    <div class="container text-center">
        <h1 class="display-4 fw-bold mb-3">
            <?= esc($contactSettings['contact_title'] ?? 'Contactez-Nous') ?>
        </h1>
        <p class="lead mb-0">
            <?= esc($contactSettings['contact_subtitle'] ?? 'Nous sommes là pour vous') ?>
        </p>
    </div>
</section>

<!-- Contact Content -->
<section class="pb-5">
    <div class="container">
        <!-- Messages Flash -->
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

        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <ul class="mb-0">
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <!-- Left Column - Contact Info -->
            <div class="col-lg-5 mb-4">
                <!-- Coordonnées -->
                <div class="contact-info-card">
                    <h2 class="h4 mb-4">Nos Coordonnées</h2>

                    <?php if (!empty($contactSettings['contact_address'])): ?>
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="bi bi-geo-alt"></i>
                        </div>
                        <div class="contact-content">
                            <h4>Adresse</h4>
                            <p><?= nl2br(esc($contactSettings['contact_address'])) ?></p>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($contactSettings['contact_phone'])): ?>
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="bi bi-telephone"></i>
                        </div>
                        <div class="contact-content">
                            <h4>Téléphone</h4>
                            <p>
                                <a href="tel:<?= esc($contactSettings['contact_phone']) ?>">
                                    <?= esc($contactSettings['contact_phone']) ?>
                                </a>
                            </p>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($contactSettings['contact_email'])): ?>
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="bi bi-envelope"></i>
                        </div>
                        <div class="contact-content">
                            <h4>Email</h4>
                            <p>
                                <a href="mailto:<?= esc($contactSettings['contact_email']) ?>">
                                    <?= esc($contactSettings['contact_email']) ?>
                                </a>
                            </p>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Horaires -->
                    <?php if (!empty($contactSettings['contact_hours_text'])): ?>
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="bi bi-clock"></i>
                        </div>
                        <div class="contact-content">
                            <h4>Horaires d'Ouverture</h4>
                            <p><?= nl2br(esc($contactSettings['contact_hours_text'])) ?></p>
                        </div>
                    </div>
                    <?php elseif (!empty($contactSettings['hours'])): ?>
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="bi bi-clock"></i>
                        </div>
                        <div class="contact-content">
                            <h4>Horaires d'Ouverture</h4>
                            <ul class="hours-list">
                                <?php
                                $days = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
                                foreach ($days as $index => $day) {
                                    $dayKey = 'hours_day_' . $index;
                                    if (isset($contactSettings['hours'][$dayKey])) {
                                        $dayData = json_decode($contactSettings['hours'][$dayKey], true);
                                        if ($dayData && isset($dayData['enabled']) && $dayData['enabled']) {
                                            echo '<li><span class="day-name">' . $day . '</span><span class="hours-time">' . esc($dayData['open']) . ' - ' . esc($dayData['close']) . '</span></li>';
                                        } else {
                                            echo '<li><span class="day-name">' . $day . '</span><span class="hours-closed">Fermé</span></li>';
                                        }
                                    }
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Boutons sociaux -->
                    <?php if (!empty($contactSettings['contact_whatsapp']) || !empty($contactSettings['contact_messenger'])): ?>
                    <div class="social-buttons">
                        <?php if (!empty($contactSettings['contact_whatsapp'])): ?>
                        <a href="https://wa.me/<?= esc(preg_replace('/[^0-9+]/', '', $contactSettings['contact_whatsapp'])) ?>" 
                           target="_blank" 
                           class="social-btn whatsapp">
                            <i class="bi bi-whatsapp"></i> WhatsApp
                        </a>
                        <?php endif; ?>

                        <?php if (!empty($contactSettings['contact_messenger'])): ?>
                        <a href="<?= esc($contactSettings['contact_messenger']) ?>" 
                           target="_blank" 
                           class="social-btn messenger">
                            <i class="bi bi-messenger"></i> Messenger
                        </a>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Right Column - Contact Form -->
            <div class="col-lg-7">
                <div class="contact-form-card">
                    <h2 class="h4 mb-4">Envoyez-nous un Message</h2>

                    <?php if (!empty($contactSettings['contact_welcome_text'])): ?>
                    <div class="welcome-message">
                        <p><?= nl2br(esc($contactSettings['contact_welcome_text'])) ?></p>
                    </div>
                    <?php endif; ?>

                    <form action="<?= base_url('contact/send') ?>" method="POST">
                        <?= csrf_field() ?>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nom Complet <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" 
                                       value="<?= old('name') ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" 
                                       value="<?= old('email') ?>" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Téléphone</label>
                                <input type="tel" name="phone" class="form-control" 
                                       value="<?= old('phone') ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Sujet <span class="text-danger">*</span></label>
                                <input type="text" name="subject" class="form-control" 
                                       value="<?= old('subject') ?>" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Message <span class="text-danger">*</span></label>
                            <textarea name="message" class="form-control" rows="6" required><?= old('message') ?></textarea>
                        </div>

                        <button type="submit" class="btn btn-send">
                            <i class="bi bi-send"></i> Envoyer le Message
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Google Maps -->
        <?php if (!empty($contactSettings['contact_map_embed'])): ?>
        <div class="row mt-5">
            <div class="col-12">
                <div class="map-container">
                    <?= $contactSettings['contact_map_embed'] ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<?= view('front/layouts/footer') ?>
