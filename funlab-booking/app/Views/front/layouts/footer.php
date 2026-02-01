<?php
// Charger les paramètres du footer
$settingModel = new \App\Models\SettingModel();
$footerSettings = $settingModel->getByCategory('footer');
$companyName = $footerSettings['footer_company_name'] ?? 'FunLab Tunisie';
$description = $footerSettings['footer_description'] ?? 'Centre d\'activités indoor premium';
$address = $footerSettings['footer_address'] ?? '';
$email = $footerSettings['footer_email'] ?? '';
$phone = $footerSettings['footer_phone'] ?? '';
$facebook = $footerSettings['footer_facebook'] ?? '';
$instagram = $footerSettings['footer_instagram'] ?? '';
$twitter = $footerSettings['footer_twitter'] ?? '';
$tiktok = $footerSettings['footer_tiktok'] ?? '';
$whatsapp = $footerSettings['footer_whatsapp'] ?? '';
$copyright = $footerSettings['footer_copyright'] ?? '© {year} FunLab Tunisie. Tous droits réservés.';
$copyright = str_replace('{year}', date('Y'), $copyright);

// Charger les horaires d'ouverture depuis settings/hours
$hoursSettings = $settingModel->getByCategory('hours');
$daysMap = [
    'monday' => 'Lundi',
    'tuesday' => 'Mardi',
    'wednesday' => 'Mercredi',
    'thursday' => 'Jeudi',
    'friday' => 'Vendredi',
    'saturday' => 'Samedi',
    'sunday' => 'Dimanche'
];

$hoursDisplay = '';
$groupedHours = [];

foreach ($daysMap as $dayKey => $dayLabel) {
    $settingKey = 'business_hours_' . $dayKey;
    if (isset($hoursSettings[$settingKey])) {
        // Décoder seulement si c'est une string
        $hours = is_string($hoursSettings[$settingKey]) 
            ? json_decode($hoursSettings[$settingKey], true) 
            : $hoursSettings[$settingKey];
            
        if ($hours && isset($hours['enabled']) && $hours['enabled']) {
            $timeRange = $hours['open'] . ' - ' . $hours['close'];
            if (!isset($groupedHours[$timeRange])) {
                $groupedHours[$timeRange] = [];
            }
            $groupedHours[$timeRange][] = $dayLabel;
        }
    }
}

// Construire l'affichage groupé
foreach ($groupedHours as $timeRange => $days) {
    if (count($days) > 1) {
        $hoursDisplay .= $days[0] . ' - ' . end($days) . ': ' . $timeRange . '<br>';
    } else {
        $hoursDisplay .= $days[0] . ': ' . $timeRange . '<br>';
    }
}

if (empty($hoursDisplay)) {
    $hoursDisplay = 'Lundi - Dimanche<br>09:00 - 22:00';
}
?>

    <!-- Footer Moderne -->
    <footer class="bg-dark text-white py-5 mt-5" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);">
        <div class="container">
            <div class="row g-4">
                <!-- À Propos -->
                <div class="col-lg-4 mb-4" data-aos="fade-up">
                    <div class="footer-section">
                        <h5 class="mb-4" style="font-weight: 700; position: relative; padding-bottom: 12px;">
                            <i class="bi bi-joystick me-2" style="color: #667eea;"></i><?= esc($companyName) ?>
                            <div style="position: absolute; bottom: 0; left: 0; width: 60px; height: 3px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 10px;"></div>
                        </h5>
                        <?php if ($description): ?>
                            <p style="color: #b8b8b8; line-height: 1.8;"><?= nl2br(esc($description)) ?></p>
                        <?php endif; ?>
                        
                        <?php if ($facebook || $instagram || $twitter || $tiktok || $whatsapp): ?>
                        <div class="mt-4 d-flex gap-3">
                            <?php if ($facebook): ?>
                                <a href="<?= esc($facebook) ?>" target="_blank" 
                                   class="social-icon" title="Facebook"
                                   style="display: flex; align-items: center; justify-content: center; width: 45px; height: 45px; background: rgba(255,255,255,0.1); border-radius: 12px; color: white; text-decoration: none; transition: all 0.3s ease;">
                                    <i class="bi bi-facebook fs-5"></i>
                                </a>
                            <?php endif; ?>
                            <?php if ($instagram): ?>
                                <a href="<?= esc($instagram) ?>" target="_blank" 
                                   class="social-icon" title="Instagram"
                                   style="display: flex; align-items: center; justify-content: center; width: 45px; height: 45px; background: rgba(255,255,255,0.1); border-radius: 12px; color: white; text-decoration: none; transition: all 0.3s ease;">
                                    <i class="bi bi-instagram fs-5"></i>
                                </a>
                            <?php endif; ?>
                            <?php if ($twitter): ?>
                                <a href="<?= esc($twitter) ?>" target="_blank" 
                                   class="social-icon" title="Twitter"
                                   style="display: flex; align-items: center; justify-content: center; width: 45px; height: 45px; background: rgba(255,255,255,0.1); border-radius: 12px; color: white; text-decoration: none; transition: all 0.3s ease;">
                                    <i class="bi bi-twitter fs-5"></i>
                                </a>
                            <?php endif; ?>
                            <?php if ($tiktok): ?>
                                <a href="<?= esc($tiktok) ?>" target="_blank" 
                                   class="social-icon" title="TikTok"
                                   style="display: flex; align-items: center; justify-content: center; width: 45px; height: 45px; background: rgba(255,255,255,0.1); border-radius: 12px; color: white; text-decoration: none; transition: all 0.3s ease;">
                                    <i class="bi bi-tiktok fs-5"></i>
                                </a>
                            <?php endif; ?>
                            <?php if ($whatsapp): ?>
                                <a href="https://wa.me/<?= preg_replace('/[^0-9+]/', '', esc($whatsapp)) ?>" target="_blank" 
                                   class="social-icon" title="WhatsApp"
                                   style="display: flex; align-items: center; justify-content: center; width: 45px; height: 45px; background: rgba(255,255,255,0.1); border-radius: 12px; color: white; text-decoration: none; transition: all 0.3s ease;">
                                    <i class="bi bi-whatsapp fs-5"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Contact -->
                <div class="col-lg-4 mb-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="footer-section">
                        <h5 class="mb-4" style="font-weight: 700; position: relative; padding-bottom: 12px;">
                            Contact
                            <div style="position: absolute; bottom: 0; left: 0; width: 60px; height: 3px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 10px;"></div>
                        </h5>
                        <div class="contact-info">
                            <?php if ($address): ?>
                                <div class="mb-3 d-flex align-items-start">
                                    <i class="bi bi-geo-alt-fill me-3" style="color: #667eea; font-size: 1.2rem;"></i>
                                    <span style="color: #b8b8b8;"><?= esc($address) ?></span>
                                </div>
                            <?php endif; ?>
                            <?php if ($email): ?>
                                <div class="mb-3 d-flex align-items-center">
                                    <i class="bi bi-envelope-fill me-3" style="color: #667eea; font-size: 1.2rem;"></i>
                                    <a href="mailto:<?= esc($email) ?>" 
                                       style="color: #b8b8b8; text-decoration: none; transition: color 0.3s ease;">
                                        <?= esc($email) ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            <?php if ($phone): ?>
                                <div class="mb-3 d-flex align-items-center">
                                    <i class="bi bi-telephone-fill me-3" style="color: #667eea; font-size: 1.2rem;"></i>
                                    <a href="tel:<?= esc($phone) ?>" 
                                       style="color: #b8b8b8; text-decoration: none; transition: color 0.3s ease;">
                                        <?= esc($phone) ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mt-4">
                            <h6 style="font-weight: 600; margin-bottom: 15px;">Horaires</h6>
                            <div style="color: #b8b8b8; line-height: 2;"><?= $hoursDisplay ?></div>
                        </div>
                    </div>
                </div>
                
                <!-- Liens Rapides -->
                <div class="col-lg-4 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="footer-section">
                        <h5 class="mb-4" style="font-weight: 700; position: relative; padding-bottom: 12px;">
                            Liens Rapides
                            <div style="position: absolute; bottom: 0; left: 0; width: 60px; height: 3px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 10px;"></div>
                        </h5>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <a href="<?= base_url('/') ?>" class="footer-link" 
                                   style="color: #b8b8b8; text-decoration: none; transition: all 0.3s ease; display: flex; align-items: center;">
                                    <i class="bi bi-chevron-right me-2" style="color: #667eea;"></i>Accueil
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="<?= base_url('about') ?>" class="footer-link" 
                                   style="color: #b8b8b8; text-decoration: none; transition: all 0.3s ease; display: flex; align-items: center;">
                                    <i class="bi bi-chevron-right me-2" style="color: #667eea;"></i>À Propos
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="<?= base_url('games') ?>" class="footer-link" 
                                   style="color: #b8b8b8; text-decoration: none; transition: all 0.3s ease; display: flex; align-items: center;">
                                    <i class="bi bi-chevron-right me-2" style="color: #667eea;"></i>Nos Jeux
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="<?= base_url('booking') ?>" class="footer-link" 
                                   style="color: #b8b8b8; text-decoration: none; transition: all 0.3s ease; display: flex; align-items: center;">
                                    <i class="bi bi-chevron-right me-2" style="color: #667eea;"></i>Réservation
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="<?= base_url('contact') ?>" class="footer-link" 
                                   style="color: #b8b8b8; text-decoration: none; transition: all 0.3s ease; display: flex; align-items: center;">
                                    <i class="bi bi-chevron-right me-2" style="color: #667eea;"></i>Contact
                                </a>
                            </li>
                            <?php if (session()->get('isLoggedIn')): ?>
                            <li class="mb-2">
                                <a href="<?= base_url('account') ?>" class="footer-link" 
                                   style="color: #b8b8b8; text-decoration: none; transition: all 0.3s ease; display: flex; align-items: center;">
                                    <i class="bi bi-chevron-right me-2" style="color: #667eea;"></i>Mon Compte
                                </a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
            
            <!-- Copyright -->
            <hr style="border-color: rgba(255, 255, 255, 0.1); margin: 3rem 0 1.5rem;">
            <div class="text-center">
                <p class="mb-0" style="color: #b8b8b8;">
                    <?= $copyright ?>
                </p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        .social-icon:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        }
        
        .footer-link:hover {
            color: #667eea !important;
            padding-left: 5px;
        }
        
        .contact-info a:hover {
            color: #667eea !important;
        }
    </style>
    
    <?= $additionalJS ?? '' ?>
</body>
</html>
