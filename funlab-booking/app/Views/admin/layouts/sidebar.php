<?php 
helper('permission');
helper('theme');
$logo = theme_setting('site_logo', '/assets/images/logo.png');
$logoWidth = theme_setting('logo_width', 50);
$logoHeight = theme_setting('logo_height', 50);
$appName = theme_setting('app_name', 'FunLab');

// Compter les notifications
$bookingModel = new \App\Models\BookingModel();
$reviewModel = new \App\Models\ReviewModel();
$contactModel = new \App\Models\ContactMessageModel();

$pendingBookingsCount = $bookingModel->where('status', 'pending')->countAllResults();
$pendingReviewsCount = $reviewModel->where('is_approved', 0)->countAllResults();
$unreadContactsCount = $contactModel->where('status', 'new')->countAllResults();
?>
        <!-- Sidebar -->
        <div class="admin-sidebar text-white">
            <div class="p-4 border-bottom border-secondary">
                <div class="d-flex align-items-center gap-2">
                    <?php if ($logo): ?>
                        <img src="<?= esc($logo) ?>" 
                             alt="<?= esc($appName) ?> Logo" 
                             width="<?= esc($logoWidth) ?>" 
                             height="<?= esc($logoHeight) ?>">
                    <?php else: ?>
                        <i class="bi bi-speedometer2" style="font-size: 2rem; color: var(--primary-color);"></i>
                    <?php endif; ?>
                    <h4 class="mb-0">
                        <?= esc($appName) ?> Admin
                    </h4>
                </div>
            </div>
            
            <nav class="nav flex-column p-3">
                <!-- Dashboard -->
                <?php if (canAccessModule('dashboard')): ?>
                <a class="nav-link text-white <?= ($activeMenu ?? '') === 'dashboard' ? 'active bg-primary rounded' : '' ?>" 
                   href="<?= base_url('admin/dashboard') ?>">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
                <?php endif; ?>
                
                <div class="sidebar-section-title text-white-50 text-uppercase small px-2 mt-3 mb-2">Opérations</div>
                
                <!-- Réservations -->
                <?php if (canAccessModule('bookings')): ?>
                <a class="nav-link text-white <?= ($activeMenu ?? '') === 'bookings' ? 'active bg-primary rounded' : '' ?>" 
                   href="<?= base_url('admin/bookings') ?>">
                    <i class="bi bi-calendar-check"></i> Réservations
                    <?php if ($pendingBookingsCount > 0): ?>
                        <span class="badge bg-danger rounded-pill float-end"><?= $pendingBookingsCount ?></span>
                    <?php endif; ?>
                </a>
                <?php endif; ?>
                
                <!-- Scanner -->
                <?php if (canAccessModule('scanner')): ?>
                <a class="nav-link text-white <?= ($activeMenu ?? '') === 'scanner' ? 'active bg-primary rounded' : '' ?>" 
                   href="<?= base_url('admin/scanner') ?>">
                    <i class="bi bi-qr-code-scan"></i> Scanner Tickets
                </a>
                <?php endif; ?>
                
                <!-- Équipes -->
                <?php if (canAccessModule('teams')): ?>
                <a class="nav-link text-white <?= ($activeMenu ?? '') === 'teams' ? 'active bg-primary rounded' : '' ?>" 
                   href="<?= base_url('admin/teams') ?>">
                    <i class="bi bi-people-fill"></i> Équipes
                </a>
                <?php endif; ?>
                
                <div class="sidebar-section-title text-white-50 text-uppercase small px-2 mt-3 mb-2">Gestion Contenu</div>
                
                <!-- Jeux & Catégories -->
                <?php if (canAccessModule('games')): ?>
                <a class="nav-link text-white <?= in_array($activeMenu ?? '', ['games', 'categories']) ? 'active bg-primary rounded' : '' ?>" 
                   data-bs-toggle="collapse" 
                   href="#gamesMenu" 
                   role="button" 
                   aria-expanded="<?= in_array($activeMenu ?? '', ['games', 'categories']) ? 'true' : 'false' ?>"
                   aria-controls="gamesMenu">
                    <i class="bi bi-controller"></i> Jeux
                    <i class="bi bi-chevron-down float-end"></i>
                </a>
                <div class="collapse <?= in_array($activeMenu ?? '', ['games', 'categories']) ? 'show' : '' ?>" id="gamesMenu">
                    <ul class="nav flex-column ms-3">
                        <li class="nav-item">
                            <a class="nav-link text-white-50 <?= ($activeMenu ?? '') === 'games' ? 'text-white' : '' ?>" 
                               href="<?= base_url('admin/games') ?>">
                                <i class="bi bi-joystick"></i> Liste des Jeux
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50 <?= ($activeMenu ?? '') === 'categories' ? 'text-white' : '' ?>" 
                               href="<?= base_url('admin/game-categories') ?>">
                                <i class="bi bi-grid"></i> Catégories
                            </a>
                        </li>
                    </ul>
                </div>
                <?php endif; ?>
                
                <!-- Salles & Fermetures -->
                <?php if (canAccessModule('rooms') || canAccessModule('closures')): ?>
                <a class="nav-link text-white <?= in_array($activeMenu ?? '', ['rooms', 'closures']) ? 'active bg-primary rounded' : '' ?>" 
                   data-bs-toggle="collapse" 
                   href="#roomsMenu" 
                   role="button" 
                   aria-expanded="<?= in_array($activeMenu ?? '', ['rooms', 'closures']) ? 'true' : 'false' ?>"
                   aria-controls="roomsMenu">
                    <i class="bi bi-door-closed"></i> Salles
                    <i class="bi bi-chevron-down float-end"></i>
                </a>
                <div class="collapse <?= in_array($activeMenu ?? '', ['rooms', 'closures']) ? 'show' : '' ?>" id="roomsMenu">
                    <ul class="nav flex-column ms-3">
                        <?php if (canAccessModule('rooms')): ?>
                        <li class="nav-item">
                            <a class="nav-link text-white-50 <?= ($activeMenu ?? '') === 'rooms' ? 'text-white' : '' ?>" 
                               href="<?= base_url('admin/rooms') ?>">
                                <i class="bi bi-building"></i> Gestion Salles
                            </a>
                        </li>
                        <?php endif; ?>
                        <?php if (canAccessModule('closures')): ?>
                        <li class="nav-item">
                            <a class="nav-link text-white-50 <?= ($activeMenu ?? '') === 'closures' ? 'text-white' : '' ?>" 
                               href="<?= base_url('admin/closures') ?>">
                                <i class="bi bi-x-circle"></i> Fermetures
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>
                <?php endif; ?>
                
                <!-- Avis -->
                <?php if (canAccessModule('reviews')): ?>
                <a class="nav-link text-white <?= ($activeMenu ?? '') === 'reviews' ? 'active bg-primary rounded' : '' ?>" 
                   href="<?= base_url('admin/reviews') ?>">
                    <i class="bi bi-star-fill"></i> Avis Clients
                    <?php if ($pendingReviewsCount > 0): ?>
                        <span class="badge bg-warning rounded-pill float-end"><?= $pendingReviewsCount ?></span>
                    <?php endif; ?>
                </a>
                <?php endif; ?>
                
                <div class="sidebar-section-title text-white-50 text-uppercase small px-2 mt-3 mb-2">Marketing</div>
                
                <!-- Codes Promo -->
                <?php if (canAccessModule('promo_codes')): ?>
                <a class="nav-link text-white <?= ($activeMenu ?? '') === 'promo-codes' ? 'active bg-primary rounded' : '' ?>" 
                   href="<?= base_url('admin/promo-codes') ?>">
                    <i class="bi bi-ticket-perforated"></i> Codes Promo
                </a>
                <?php endif; ?>
                
                <!-- Contacts & Newsletter -->
                <?php if (canAccessModule('contacts')): ?>
                <a class="nav-link text-white <?= in_array($activeMenu ?? '', ['contacts', 'newsletters']) ? 'active bg-primary rounded' : '' ?>" 
                   data-bs-toggle="collapse" 
                   href="#contactsMenu" 
                   role="button" 
                   aria-expanded="<?= in_array($activeMenu ?? '', ['contacts', 'newsletters']) ? 'true' : 'false' ?>"
                   aria-controls="contactsMenu">
                    <i class="bi bi-envelope-at"></i> Communication
                    <?php if ($unreadContactsCount > 0): ?>
                        <span class="badge bg-danger rounded-pill float-end me-2"><?= $unreadContactsCount ?></span>
                    <?php endif; ?>
                    <i class="bi bi-chevron-down float-end"></i>
                </a>
                <div class="collapse <?= in_array($activeMenu ?? '', ['contacts', 'newsletters']) ? 'show' : '' ?>" id="contactsMenu">
                    <ul class="nav flex-column ms-3">
                        <li class="nav-item">
                            <a class="nav-link text-white-50 <?= ($activeMenu ?? '') === 'contacts' ? 'text-white' : '' ?>" 
                               href="<?= base_url('admin/contacts') ?>">
                                <i class="bi bi-chat-left-text"></i> Messages
                                <?php if ($unreadContactsCount > 0): ?>
                                    <span class="badge bg-danger rounded-pill float-end"><?= $unreadContactsCount ?></span>
                                <?php endif; ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50 <?= ($activeMenu ?? '') === 'newsletters' ? 'text-white' : '' ?>" 
                               href="<?= base_url('admin/newsletters') ?>">
                                <i class="bi bi-envelope-heart"></i> Newsletter
                            </a>
                        </li>
                    </ul>
                </div>
                <?php endif; ?>
                
                <?php if (canAccessModule('settings') || canAccessModule('users')): ?>
                <div class="sidebar-section-title text-white-50 text-uppercase small px-2 mt-4 mb-2">Configuration</div>
                <?php endif; ?>
                
                <!-- Paramètres Généraux -->
                <?php if (canAccessModule('settings')): ?>
                <a class="nav-link text-white <?= in_array($activeMenu ?? '', ['settings-general', 'settings-hours', 'settings-oauth', 'settings-about', 'settings-footer', 'settings-contact', 'settings-seo']) ? 'active bg-primary rounded' : '' ?>" 
                   data-bs-toggle="collapse" 
                   href="#settingsMenu" 
                   role="button" 
                   aria-expanded="<?= in_array($activeMenu ?? '', ['settings-general', 'settings-hours', 'settings-oauth', 'settings-about', 'settings-footer', 'settings-contact', 'settings-seo']) ? 'true' : 'false' ?>"
                   aria-controls="settingsMenu">
                    <i class="bi bi-gear-fill"></i> Paramètres
                    <i class="bi bi-chevron-down float-end"></i>
                </a>
                <div class="collapse <?= in_array($activeMenu ?? '', ['settings-general', 'settings-hours', 'settings-oauth', 'settings-about', 'settings-footer', 'settings-contact', 'settings-seo']) ? 'show' : '' ?>" id="settingsMenu">
                    <ul class="nav flex-column ms-3">
                        <li class="nav-item">
                            <a class="nav-link text-white-50 <?= ($activeMenu ?? '') === 'settings-general' ? 'text-white' : '' ?>" 
                               href="<?= base_url('admin/settings/general') ?>">
                                <i class="bi bi-sliders"></i> Général
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50 <?= ($activeMenu ?? '') === 'settings-hours' ? 'text-white' : '' ?>" 
                               href="<?= base_url('admin/settings/hours') ?>">
                                <i class="bi bi-clock"></i> Horaires
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50 <?= ($activeMenu ?? '') === 'settings-oauth' ? 'text-white' : '' ?>" 
                               href="<?= base_url('admin/settings/oauth') ?>">
                                <i class="bi bi-shield-lock"></i> OAuth
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50 <?= ($activeMenu ?? '') === 'settings-about' ? 'text-white' : '' ?>" 
                               href="<?= base_url('admin/settings/about') ?>">
                                <i class="bi bi-info-circle"></i> À Propos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50 <?= ($activeMenu ?? '') === 'settings-contact' ? 'text-white' : '' ?>" 
                               href="<?= base_url('admin/settings/contact') ?>">
                                <i class="bi bi-telephone"></i> Contact
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50 <?= ($activeMenu ?? '') === 'settings-seo' ? 'text-white' : '' ?>" 
                               href="<?= base_url('admin/settings/seo') ?>">
                                <i class="bi bi-search"></i> SEO
                            </a>
                        </li>
                    </ul>
                </div>
                
                <!-- Communication -->
                <a class="nav-link text-white <?= in_array($activeMenu ?? '', ['settings-mail', 'settings-sms', 'email-templates']) ? 'active bg-primary rounded' : '' ?>" 
                   data-bs-toggle="collapse" 
                   href="#commsMenu" 
                   role="button" 
                   aria-expanded="<?= in_array($activeMenu ?? '', ['settings-mail', 'settings-sms', 'email-templates']) ? 'true' : 'false' ?>"
                   aria-controls="commsMenu">
                    <i class="bi bi-send-fill"></i> Email & SMS
                    <i class="bi bi-chevron-down float-end"></i>
                </a>
                <div class="collapse <?= in_array($activeMenu ?? '', ['settings-mail', 'settings-sms', 'email-templates']) ? 'show' : '' ?>" id="commsMenu">
                    <ul class="nav flex-column ms-3">
                        <li class="nav-item">
                            <a class="nav-link text-white-50 <?= ($activeMenu ?? '') === 'settings-mail' ? 'text-white' : '' ?>" 
                               href="<?= base_url('admin/settings/mail') ?>">
                                <i class="bi bi-envelope"></i> Config Email
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50 <?= ($activeMenu ?? '') === 'settings-sms' ? 'text-white' : '' ?>" 
                               href="<?= base_url('admin/settings/sms') ?>">
                                <i class="bi bi-phone"></i> Config SMS
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50 <?= ($activeMenu ?? '') === 'email-templates' ? 'text-white' : '' ?>" 
                               href="<?= base_url('admin/email-templates') ?>">
                                <i class="bi bi-envelope-paper"></i> Templates
                            </a>
                        </li>
                    </ul>
                </div>
                
                <!-- Apparence -->
                <a class="nav-link text-white <?= in_array($activeMenu ?? '', ['pages', 'theme-branding', 'theme-colors', 'theme-typography', 'theme-header', 'theme-footer']) ? 'active bg-primary rounded' : '' ?>" 
                   data-bs-toggle="collapse" 
                   href="#appearanceMenu" 
                   role="button" 
                   aria-expanded="<?= in_array($activeMenu ?? '', ['pages', 'theme-branding', 'theme-colors', 'theme-typography', 'theme-header', 'theme-footer']) ? 'true' : 'false' ?>"
                   aria-controls="appearanceMenu">
                    <i class="bi bi-palette-fill"></i> Apparence
                    <i class="bi bi-chevron-down float-end"></i>
                </a>
                <div class="collapse <?= in_array($activeMenu ?? '', ['pages', 'theme-branding', 'theme-colors', 'theme-typography', 'theme-header', 'theme-footer']) ? 'show' : '' ?>" id="appearanceMenu">
                    <ul class="nav flex-column ms-3">
                        <li class="nav-item">
                            <a class="nav-link text-white-50 <?= ($activeMenu ?? '') === 'pages' ? 'text-white' : '' ?>" 
                               href="<?= base_url('admin/slides') ?>">
                                <i class="bi bi-images"></i> Slider Homepage
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50 <?= ($activeMenu ?? '') === 'theme-branding' ? 'text-white' : '' ?>" 
                               href="<?= base_url('admin/theme/branding') ?>">
                                <i class="bi bi-image"></i> Logo
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50 <?= ($activeMenu ?? '') === 'theme-colors' ? 'text-white' : '' ?>" 
                               href="<?= base_url('admin/theme/colors') ?>">
                                <i class="bi bi-palette"></i> Couleurs
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50 <?= ($activeMenu ?? '') === 'theme-typography' ? 'text-white' : '' ?>" 
                               href="<?= base_url('admin/theme/typography') ?>">
                                <i class="bi bi-fonts"></i> Typographie
                            </a>
                        </li>
                    </ul>
                </div>
                <?php endif; ?>
                
                <!-- Utilisateurs -->
                <?php if (canAccessModule('users')): ?>
                <a class="nav-link text-white <?= in_array($activeMenu ?? '', ['settings-users', 'settings-roles']) ? 'active bg-primary rounded' : '' ?>" 
                   data-bs-toggle="collapse" 
                   href="#usersMenu" 
                   role="button" 
                   aria-expanded="<?= in_array($activeMenu ?? '', ['settings-users', 'settings-roles']) ? 'true' : 'false' ?>"
                   aria-controls="usersMenu">
                    <i class="bi bi-people"></i> Utilisateurs
                    <i class="bi bi-chevron-down float-end"></i>
                </a>
                <div class="collapse <?= in_array($activeMenu ?? '', ['settings-users', 'settings-roles']) ? 'show' : '' ?>" id="usersMenu">
                    <ul class="nav flex-column ms-3">
                        <li class="nav-item">
                            <a class="nav-link text-white-50 <?= ($activeMenu ?? '') === 'settings-users' ? 'text-white' : '' ?>" 
                               href="<?= base_url('admin/settings/users') ?>">
                                <i class="bi bi-person"></i> Gestion
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50 <?= ($activeMenu ?? '') === 'settings-roles' ? 'text-white' : '' ?>" 
                               href="<?= base_url('admin/settings/roles') ?>">
                                <i class="bi bi-shield-check"></i> Permissions
                            </a>
                        </li>
                    </ul>
                </div>
                <?php endif; ?>
                
                <hr class="border-secondary my-3">
                
                <a class="nav-link text-white <?= ($activeMenu ?? '') === 'wiki' ? 'active bg-primary rounded' : '' ?>" 
                   href="<?= base_url('admin/wiki') ?>">
                    <i class="bi bi-book"></i> Documentation
                </a>
                
                <a class="nav-link text-white" href="<?= base_url('/') ?>" target="_blank">
                    <i class="bi bi-box-arrow-up-right"></i> Voir le Site
                </a>
                
                <a class="nav-link text-white" href="<?= base_url('logout') ?>">
                    <i class="bi bi-box-arrow-right"></i> Déconnexion
                </a>
            </nav>
        </div>
