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

    <!-- Footer -->
    <footer class="bg-dark text-white py-5">
        <div class="container">
            <div class="row g-4">
                <!-- About -->
                <div class="col-lg-3 col-md-6">
                    <h5 class="text-uppercase mb-4" style="font-family: 'Oswald', sans-serif; font-weight: 600; letter-spacing: 1px;">À Propos</h5>
                    <?php if ($description): ?>
                        <p style="color: #bbb; line-height: 1.8; font-size: 14px;"><?= nl2br(esc($description)) ?></p>
                    <?php endif; ?>
                    
                    <ul class="list-unstyled mt-3">
                        <li class="mb-2">
                            <a href="<?= base_url('/') ?>" style="color: #bbb; text-decoration: none; font-size: 14px; transition: color 0.3s;" 
                               onmouseover="this.style.color='var(--primary-color)'" 
                               onmouseout="this.style.color='#bbb'">
                                Accueil
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="<?= base_url('about') ?>" style="color: #bbb; text-decoration: none; font-size: 14px; transition: color 0.3s;" 
                               onmouseover="this.style.color='var(--primary-color)'" 
                               onmouseout="this.style.color='#bbb'">
                                À Propos
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="<?= base_url('games') ?>" style="color: #bbb; text-decoration: none; font-size: 14px; transition: color 0.3s;" 
                               onmouseover="this.style.color='var(--primary-color)'" 
                               onmouseout="this.style.color='#bbb'">
                                Nos Activités
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="<?= base_url('contact') ?>" style="color: #bbb; text-decoration: none; font-size: 14px; transition: color 0.3s;" 
                               onmouseover="this.style.color='var(--primary-color)'" 
                               onmouseout="this.style.color='#bbb'">
                                Contact
                            </a>
                        </li>
                    </ul>
                </div>
                
                <!-- Contact -->
                <div class="col-lg-3 col-md-6">
                    <h5 class="text-uppercase mb-4" style="font-family: 'Oswald', sans-serif; font-weight: 600; letter-spacing: 1px;">Contact</h5>
                    <ul class="list-unstyled">
                        <?php if ($address): ?>
                        <li class="mb-3">
                            <i class="bi bi-geo-alt-fill" style="color: var(--primary-color); font-size: 1.1rem;"></i>
                            <span style="color: #bbb; font-size: 14px; margin-left: 10px;"><?= esc($address) ?></span>
                        </li>
                        <?php endif; ?>
                        
                        <?php if ($phone): ?>
                        <li class="mb-3">
                            <i class="bi bi-telephone-fill" style="color: var(--primary-color); font-size: 1.1rem;"></i>
                            <a href="tel:<?= esc($phone) ?>" 
                               style="color: #bbb; text-decoration: none; font-size: 14px; margin-left: 10px; transition: color 0.3s;"
                               onmouseover="this.style.color='var(--primary-color)'" 
                               onmouseout="this.style.color='#bbb'">
                                <?= esc($phone) ?>
                            </a>
                        </li>
                        <?php endif; ?>
                        
                        <?php if ($email): ?>
                        <li class="mb-3">
                            <i class="bi bi-envelope-fill" style="color: var(--primary-color); font-size: 1.1rem;"></i>
                            <a href="mailto:<?= esc($email) ?>" 
                               style="color: #bbb; text-decoration: none; font-size: 14px; margin-left: 10px; transition: color 0.3s;"
                               onmouseover="this.style.color='var(--primary-color)'" 
                               onmouseout="this.style.color='#bbb'">
                                <?= esc($email) ?>
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>
                
                <!-- Opening Hours -->
                <div class="col-lg-3 col-md-6">
                    <h5 class="text-uppercase mb-4" style="font-family: 'Oswald', sans-serif; font-weight: 600; letter-spacing: 1px;">Horaires d'ouverture</h5>
                    <div style="color: #bbb; font-size: 14px; line-height: 2;">
                        <?= $hoursDisplay ?>
                    </div>
                </div>
                
                <!-- Social Media -->
                <div class="col-lg-3 col-md-6">
                    <h5 class="text-uppercase mb-4" style="font-family: 'Oswald', sans-serif; font-weight: 600; letter-spacing: 1px;">Suivez-nous</h5>
                    
                    <?php if ($facebook || $instagram || $twitter || $tiktok || $whatsapp): ?>
                    <div class="d-flex gap-2 mb-4">
                        <?php if ($facebook): ?>
                        <a href="<?= esc($facebook) ?>" target="_blank" 
                           class="social-link"
                           style="display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; background: rgba(255,255,255,0.1); color: white; text-decoration: none; transition: all 0.3s ease;"
                           onmouseover="this.style.background='var(--primary-color)'; this.style.transform='translateY(-3px)'" 
                           onmouseout="this.style.background='rgba(255,255,255,0.1)'; this.style.transform='translateY(0)'">
                            <i class="bi bi-facebook"></i>
                        </a>
                        <?php endif; ?>
                        
                        <?php if ($instagram): ?>
                        <a href="<?= esc($instagram) ?>" target="_blank" 
                           class="social-link"
                           style="display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; background: rgba(255,255,255,0.1); color: white; text-decoration: none; transition: all 0.3s ease;"
                           onmouseover="this.style.background='var(--primary-color)'; this.style.transform='translateY(-3px)'" 
                           onmouseout="this.style.background='rgba(255,255,255,0.1)'; this.style.transform='translateY(0)'">
                            <i class="bi bi-instagram"></i>
                        </a>
                        <?php endif; ?>
                        
                        <?php if ($twitter): ?>
                        <a href="<?= esc($twitter) ?>" target="_blank" 
                           class="social-link"
                           style="display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; background: rgba(255,255,255,0.1); color: white; text-decoration: none; transition: all 0.3s ease;"
                           onmouseover="this.style.background='var(--primary-color)'; this.style.transform='translateY(-3px)'" 
                           onmouseout="this.style.background='rgba(255,255,255,0.1)'; this.style.transform='translateY(0)'">
                            <i class="bi bi-twitter"></i>
                        </a>
                        <?php endif; ?>
                        
                        <?php if ($tiktok): ?>
                        <a href="<?= esc($tiktok) ?>" target="_blank" 
                           class="social-link"
                           style="display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; background: rgba(255,255,255,0.1); color: white; text-decoration: none; transition: all 0.3s ease;"
                           onmouseover="this.style.background='var(--primary-color)'; this.style.transform='translateY(-3px)'" 
                           onmouseout="this.style.background='rgba(255,255,255,0.1)'; this.style.transform='translateY(0)'">
                            <i class="bi bi-tiktok"></i>
                        </a>
                        <?php endif; ?>
                        
                        <?php if ($whatsapp): ?>
                        <a href="https://wa.me/<?= preg_replace('/[^0-9+]/', '', esc($whatsapp)) ?>" target="_blank" 
                           class="social-link"
                           style="display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; background: rgba(255,255,255,0.1); color: white; text-decoration: none; transition: all 0.3s ease;"
                           onmouseover="this.style.background='var(--primary-color)'; this.style.transform='translateY(-3px)'" 
                           onmouseout="this.style.background='rgba(255,255,255,0.1)'; this.style.transform='translateY(0)'">
                            <i class="bi bi-whatsapp"></i>
                        </a>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (session()->get('isLoggedIn')): ?>
                    <div class="mt-3">
                        <a href="<?= base_url('account') ?>" class="btn btn-sm btn-outline-light" style="border-radius: 0; font-family: 'Oswald', sans-serif; text-transform: uppercase; letter-spacing: 1px;">
                            <i class="bi bi-person-circle"></i> Mon Compte
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Copyright -->
            <hr style="border-color: rgba(255,255,255,0.1); margin: 3rem 0 2rem;">
            <div class="text-center">
                <p class="mb-0" style="color: #888; font-size: 13px;">
                    <?= $copyright ?>
                </p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <?= $additionalJS ?? '' ?>
</body>
</html>
