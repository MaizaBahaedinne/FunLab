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

$pendingBookingsCount = $bookingModel->where('status', 'pending')->countAllResults();
$pendingReviewsCount = $reviewModel->where('is_approved', 0)->countAllResults();
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
                    <i class="bi bi-house"></i> Dashboard
                </a>
                <?php endif; ?>
                
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
                
                <!-- Jeux -->
                <?php if (canAccessModule('games')): ?>
                <a class="nav-link text-white <?= ($activeMenu ?? '') === 'games' ? 'active bg-primary rounded' : '' ?>" 
                   href="<?= base_url('admin/games') ?>">
                    <i class="bi bi-controller"></i> Jeux
                </a>
                <?php endif; ?>
                
                <!-- Catégories (lié aux jeux) -->
                <?php if (canAccessModule('games')): ?>
                <a class="nav-link text-white <?= ($activeMenu ?? '') === 'categories' ? 'active bg-primary rounded' : '' ?>" 
                   href="<?= base_url('admin/game-categories') ?>">
                    <i class="bi bi-grid"></i> Catégories
                </a>
                <?php endif; ?>
                
                <!-- Avis -->
                <?php if (canAccessModule('reviews')): ?>
                <a class="nav-link text-white <?= ($activeMenu ?? '') === 'reviews' ? 'active bg-primary rounded' : '' ?>" 
                   href="<?= base_url('admin/reviews') ?>">
                    <i class="bi bi-star"></i> Avis
                    <?php if ($pendingReviewsCount > 0): ?>
                        <span class="badge bg-warning rounded-pill float-end"><?= $pendingReviewsCount ?></span>
                    <?php endif; ?>
                </a>
                <?php endif; ?>
                
                <!-- Salles -->
                <?php if (canAccessModule('rooms')): ?>
                <a class="nav-link text-white <?= ($activeMenu ?? '') === 'rooms' ? 'active bg-primary rounded' : '' ?>" 
                   href="<?= base_url('admin/rooms') ?>">
                    <i class="bi bi-door-closed"></i> Salles
                </a>
                <?php endif; ?>
                
                <!-- Fermetures -->
                <?php if (canAccessModule('closures')): ?>
                <a class="nav-link text-white <?= ($activeMenu ?? '') === 'closures' ? 'active bg-primary rounded' : '' ?>" 
                   href="<?= base_url('admin/closures') ?>">
                    <i class="bi bi-x-circle"></i> Fermetures
                </a>
                <?php endif; ?>
                
                <!-- Scanner -->
                <?php if (canAccessModule('scanner')): ?>
                <a class="nav-link text-white <?= ($activeMenu ?? '') === 'scanner' ? 'active bg-primary rounded' : '' ?>" 
                   href="<?= base_url('admin/scanner') ?>">
                    <i class="bi bi-qr-code-scan"></i> Scanner
                </a>
                <?php endif; ?>
                
                <!-- Équipes (lié aux bookings) -->
                <?php if (canAccessModule('teams')): ?>
                <a class="nav-link text-white <?= ($activeMenu ?? '') === 'teams' ? 'active bg-primary rounded' : '' ?>" 
                   href="<?= base_url('admin/teams') ?>">
                    <i class="bi bi-people"></i> Équipes
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
                    <i class="bi bi-envelope-at"></i> Contacts & Newsletter
                    <i class="bi bi-chevron-down float-end"></i>
                </a>
                <div class="collapse <?= in_array($activeMenu ?? '', ['contacts', 'newsletters']) ? 'show' : '' ?>" id="contactsMenu">
                    <ul class="nav flex-column ms-3">
                        <li class="nav-item">
                            <a class="nav-link text-white-50 <?= ($activeMenu ?? '') === 'contacts' ? 'text-white' : '' ?>" 
                               href="<?= base_url('admin/contacts') ?>">
                                <i class="bi bi-chat-left-text"></i> Messages Contact
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50 <?= ($activeMenu ?? '') === 'newsletters' ? 'text-white' : '' ?>" 
                               href="<?= base_url('admin/newsletters') ?>">
                                <i class="bi bi-envelope-heart"></i> Abonnés Newsletter
                            </a>
                        </li>
                    </ul>
                </div>
                <?php endif; ?>
                
                <?php if (canAccessModule('settings') || canAccessModule('users')): ?>
                <hr class="border-secondary my-3">
                <?php endif; ?>
                
                <!-- Configuration Système (ADMIN ONLY) -->
                <?php if (canAccessModule('settings')): ?>
                <a class="nav-link text-white <?= (str_starts_with($activeMenu ?? '', 'settings-')) && in_array($activeMenu, ['settings-general', 'settings-hours', 'settings-oauth']) ? 'active bg-primary rounded' : '' ?>" 
                   data-bs-toggle="collapse" 
                   href="#configMenu" 
                   role="button" 
                   aria-expanded="<?= (str_starts_with($activeMenu ?? '', 'settings-')) && in_array($activeMenu, ['settings-general', 'settings-hours', 'settings-oauth']) ? 'true' : 'false' ?>"
                   aria-controls="configMenu">
                    <i class="bi bi-gear"></i> Configuration
                    <i class="bi bi-chevron-down float-end"></i>
                </a>
                <div class="collapse <?= (str_starts_with($activeMenu ?? '', 'settings-')) && in_array($activeMenu, ['settings-general', 'settings-hours', 'settings-oauth']) ? 'show' : '' ?>" id="configMenu">
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
                    </ul>
                </div>
                
                <!-- Communications (ADMIN ONLY) -->
                <a class="nav-link text-white <?= in_array($activeMenu ?? '', ['settings-mail', 'settings-sms', 'email-templates']) ? 'active bg-primary rounded' : '' ?>" 
                   data-bs-toggle="collapse" 
                   href="#commsMenu" 
                   role="button" 
                   aria-expanded="<?= in_array($activeMenu ?? '', ['settings-mail', 'settings-sms', 'email-templates']) ? 'true' : 'false' ?>"
                   aria-controls="commsMenu">
                    <i class="bi bi-chat-dots"></i> Communications
                    <i class="bi bi-chevron-down float-end"></i>
                </a>
                <div class="collapse <?= in_array($activeMenu ?? '', ['settings-mail', 'settings-sms', 'email-templates']) ? 'show' : '' ?>" id="commsMenu">
                    <ul class="nav flex-column ms-3">
                        <li class="nav-item">
                            <a class="nav-link text-white-50 <?= ($activeMenu ?? '') === 'settings-mail' ? 'text-white' : '' ?>" 
                               href="<?= base_url('admin/settings/mail') ?>">
                                <i class="bi bi-envelope"></i> Email
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50 <?= ($activeMenu ?? '') === 'settings-sms' ? 'text-white' : '' ?>" 
                               href="<?= base_url('admin/settings/sms') ?>">
                                <i class="bi bi-phone"></i> SMS
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50 <?= ($activeMenu ?? '') === 'email-templates' ? 'text-white' : '' ?>" 
                               href="<?= base_url('admin/email-templates') ?>">
                                <i class="bi bi-envelope-paper"></i> Templates Emails
                            </a>
                        </li>
                    </ul>
                </div>
                
                <!-- Pages & Contenu (ADMIN ONLY) -->
                <a class="nav-link text-white <?= in_array($activeMenu ?? '', ['settings-about', 'settings-footer', 'settings-seo', 'pages', 'theme']) ? 'active bg-primary rounded' : '' ?>" 
                   data-bs-toggle="collapse" 
                   href="#contentMenu" 
                   role="button" 
                   aria-expanded="<?= in_array($activeMenu ?? '', ['settings-about', 'settings-footer', 'settings-seo', 'pages', 'theme']) ? 'true' : 'false' ?>"
                   aria-controls="contentMenu">
                    <i class="bi bi-file-earmark-text"></i> Pages & Contenu
                    <i class="bi bi-chevron-down float-end"></i>
                </a>
                <div class="collapse <?= in_array($activeMenu ?? '', ['settings-about', 'settings-footer', 'settings-seo', 'pages', 'theme']) ? 'show' : '' ?>" id="contentMenu">
                    <ul class="nav flex-column ms-3">
                        <li class="nav-item">
                            <a class="nav-link text-white-50 <?= ($activeMenu ?? '') === 'pages' ? 'text-white' : '' ?>" 
                               href="<?= base_url('admin/slides') ?>">
                                <i class="bi bi-images"></i> Slider Homepage
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50 <?= ($activeMenu ?? '') === 'pages' ? 'text-white' : '' ?>" 
                               href="<?= base_url('admin/pages') ?>">
                                <i class="bi bi-file-earmark"></i> Pages
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50 <?= ($activeMenu ?? '') === 'settings-about' ? 'text-white' : '' ?>" 
                               href="<?= base_url('admin/settings/about') ?>">
                                <i class="bi bi-info-circle"></i> À Propos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50 <?= ($activeMenu ?? '') === 'settings-footer' ? 'text-white' : '' ?>" 
                               href="<?= base_url('admin/settings/footer') ?>">
                                <i class="bi bi-layout-text-sidebar-reverse"></i> Footer
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50 <?= ($activeMenu ?? '') === 'settings-contact' ? 'text-white' : '' ?>" 
                               href="<?= base_url('admin/settings/contact') ?>">
                                <i class="bi bi-envelope"></i> Contact
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
                
                <!-- Apparence / Thème (ADMIN ONLY) -->
                <a class="nav-link text-white <?= str_starts_with($activeMenu ?? '', 'theme-') ? 'active bg-primary rounded' : '' ?>" 
                   data-bs-toggle="collapse" 
                   href="#themeMenu" 
                   role="button" 
                   aria-expanded="<?= str_starts_with($activeMenu ?? '', 'theme-') ? 'true' : 'false' ?>"
                   aria-controls="themeMenu">
                    <i class="bi bi-palette"></i> Apparence
                    <i class="bi bi-chevron-down float-end"></i>
                </a>
                <div class="collapse <?= str_starts_with($activeMenu ?? '', 'theme-') ? 'show' : '' ?>" id="themeMenu">
                    <ul class="nav flex-column ms-3">
                        <li class="nav-item">
                            <a class="nav-link text-white-50 <?= ($activeMenu ?? '') === 'theme-branding' ? 'text-white' : '' ?>" 
                               href="<?= base_url('admin/theme/branding') ?>">
                                <i class="bi bi-image"></i> Logo & Branding
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50 <?= ($activeMenu ?? '') === 'theme-colors' ? 'text-white' : '' ?>" 
                               href="<?= base_url('admin/theme/colors') ?>">
                                <i class="bi bi-palette-fill"></i> Couleurs
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50 <?= ($activeMenu ?? '') === 'theme-typography' ? 'text-white' : '' ?>" 
                               href="<?= base_url('admin/theme/typography') ?>">
                                <i class="bi bi-fonts"></i> Typographie
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50 <?= ($activeMenu ?? '') === 'theme-header' ? 'text-white' : '' ?>" 
                               href="<?= base_url('admin/theme/header') ?>">
                                <i class="bi bi-layout-text-window"></i> En-tête
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50 <?= ($activeMenu ?? '') === 'theme-footer' ? 'text-white' : '' ?>" 
                               href="<?= base_url('admin/theme/footer') ?>">
                                <i class="bi bi-layout-text-window-reverse"></i> Pied de page
                            </a>
                        </li>
                    </ul>
                </div>
                <?php endif; ?>
                
                <!-- Utilisateurs & Permissions (ADMIN ONLY) -->
                <?php if (canAccessModule('users')): ?>
                <a class="nav-link text-white <?= in_array($activeMenu ?? '', ['settings-users', 'settings-roles']) ? 'active bg-primary rounded' : '' ?>" 
                   data-bs-toggle="collapse" 
                   href="#usersMenu" 
                   role="button" 
                   aria-expanded="<?= in_array($activeMenu ?? '', ['settings-users', 'settings-roles']) ? 'true' : 'false' ?>"
                   aria-controls="usersMenu">
                    <i class="bi bi-person-gear"></i> Utilisateurs
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
                                <i class="bi bi-shield-check"></i> Rôles & Permissions
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
                    <i class="bi bi-box-arrow-up-right"></i> Voir le site
                </a>
                
                <a class="nav-link text-white" href="<?= base_url('logout') ?>">
                    <i class="bi bi-box-arrow-right"></i> Déconnexion
                </a>
            </nav>
        </div>
