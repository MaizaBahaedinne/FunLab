<?php
// Charger les paramètres du footer
$settingModel = new \App\Models\SettingModel();
$footerSettings = $settingModel->getByCategory('footer');
$companyName = $footerSettings['footer_company_name'] ?? 'FunLab Tunisie';
$description = $footerSettings['footer_description'] ?? 'Centre d\'activités indoor premium';
$address = $footerSettings['footer_address'] ?? '';
$email = $footerSettings['footer_email'] ?? '';
$phone = $footerSettings['footer_phone'] ?? '';
$hours = $footerSettings['footer_hours'] ?? '';
$facebook = $footerSettings['footer_facebook'] ?? '';
$instagram = $footerSettings['footer_instagram'] ?? '';
$twitter = $footerSettings['footer_twitter'] ?? '';
$copyright = $footerSettings['footer_copyright'] ?? '© {year} FunLab Tunisie. Tous droits réservés.';
$copyright = str_replace('{year}', date('Y'), $copyright);
?>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <h5><i class="bi bi-joystick"></i> <?= esc($companyName) ?></h5>
                    <?php if ($description): ?>
                        <p><?= esc($description) ?></p>
                    <?php endif; ?>
                    
                    <?php if ($facebook || $instagram || $twitter): ?>
                    <div class="mt-3">
                        <?php if ($facebook): ?>
                            <a href="<?= esc($facebook) ?>" target="_blank" class="text-white me-3">
                                <i class="bi bi-facebook"></i>
                            </a>
                        <?php endif; ?>
                        <?php if ($instagram): ?>
                            <a href="<?= esc($instagram) ?>" target="_blank" class="text-white me-3">
                                <i class="bi bi-instagram"></i>
                            </a>
                        <?php endif; ?>
                        <?php if ($twitter): ?>
                            <a href="<?= esc($twitter) ?>" target="_blank" class="text-white me-3">
                                <i class="bi bi-twitter"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
                
                <div class="col-md-4 mb-3">
                    <h5>Contact</h5>
                    <p>
                        <?php if ($address): ?>
                            <i class="bi bi-geo-alt"></i> <?= esc($address) ?><br>
                        <?php endif; ?>
                        <?php if ($email): ?>
                            <i class="bi bi-envelope"></i> <a href="mailto:<?= esc($email) ?>" class="text-white text-decoration-none"><?= esc($email) ?></a><br>
                        <?php endif; ?>
                        <?php if ($phone): ?>
                            <i class="bi bi-telephone"></i> <a href="tel:<?= esc($phone) ?>" class="text-white text-decoration-none"><?= esc($phone) ?></a>
                        <?php endif; ?>
                    </p>
                </div>
                
                <div class="col-md-4 mb-3">
                    <?php if ($hours): ?>
                    <h5>Horaires</h5>
                    <p><?= $hours ?></p>
                    <?php endif; ?>
                    
                    <h6 class="mt-3">Liens Utiles</h6>
                    <ul class="list-unstyled">
                        <li><a href="<?= base_url('/') ?>" class="text-white text-decoration-none">Accueil</a></li>
                        <li><a href="<?= base_url('about') ?>" class="text-white text-decoration-none">À Propos</a></li>
                        <li><a href="<?= base_url('games') ?>" class="text-white text-decoration-none">Jeux</a></li>
                        <li><a href="<?= base_url('booking') ?>" class="text-white text-decoration-none">Réservation</a></li>
                        <li><a href="<?= base_url('contact') ?>" class="text-white text-decoration-none">Contact</a></li>
                    </ul>
                </div>
            </div>
            <hr class="my-3">
            <div class="text-center">
                <p class="mb-0"><?= $copyright ?></p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <?= $additionalJS ?? '' ?>
</body>
</html>
