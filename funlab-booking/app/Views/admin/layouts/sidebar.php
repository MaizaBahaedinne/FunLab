        <!-- Sidebar -->
        <div class="admin-sidebar text-white">
            <div class="p-4 border-bottom border-secondary">
                <h4 class="mb-0">
                    <i class="bi bi-speedometer2"></i> FunLab Admin
                </h4>
            </div>
            
            <nav class="nav flex-column p-3">
                <a class="nav-link text-white <?= ($activeMenu ?? '') === 'dashboard' ? 'active bg-primary rounded' : '' ?>" 
                   href="<?= base_url('admin/dashboard') ?>">
                    <i class="bi bi-house"></i> Dashboard
                </a>
                
                <a class="nav-link text-white <?= ($activeMenu ?? '') === 'bookings' ? 'active bg-primary rounded' : '' ?>" 
                   href="<?= base_url('admin/bookings') ?>">
                    <i class="bi bi-calendar-check"></i> Réservations
                </a>
                
                <a class="nav-link text-white <?= ($activeMenu ?? '') === 'games' ? 'active bg-primary rounded' : '' ?>" 
                   href="<?= base_url('admin/games') ?>">
                    <i class="bi bi-controller"></i> Jeux
                </a>
                
                <a class="nav-link text-white <?= ($activeMenu ?? '') === 'rooms' ? 'active bg-primary rounded' : '' ?>" 
                   href="<?= base_url('admin/rooms') ?>">
                    <i class="bi bi-door-closed"></i> Salles
                </a>
                
                <a class="nav-link text-white <?= ($activeMenu ?? '') === 'closures' ? 'active bg-primary rounded' : '' ?>" 
                   href="<?= base_url('admin/closures') ?>">
                    <i class="bi bi-x-circle"></i> Fermetures
                </a>
                
                <a class="nav-link text-white <?= ($activeMenu ?? '') === 'scanner' ? 'active bg-primary rounded' : '' ?>" 
                   href="<?= base_url('admin/scanner') ?>">
                    <i class="bi bi-qr-code-scan"></i> Scanner
                </a>
                
                <a class="nav-link text-white <?= ($activeMenu ?? '') === 'teams' ? 'active bg-primary rounded' : '' ?>" 
                   href="<?= base_url('admin/teams') ?>">
                    <i class="bi bi-people"></i> Équipes
                </a>
                
                <hr class="border-secondary my-3">
                
                <!-- Paramètres avec sous-menu -->
                <a class="nav-link text-white <?= (str_starts_with($activeMenu ?? '', 'settings')) ? 'active bg-primary rounded' : '' ?>" 
                   data-bs-toggle="collapse" 
                   href="#settingsMenu" 
                   role="button" 
                   aria-expanded="<?= (str_starts_with($activeMenu ?? '', 'settings')) ? 'true' : 'false' ?>"
                   aria-controls="settingsMenu">
                    <i class="bi bi-gear"></i> Paramètres
                    <i class="bi bi-chevron-down float-end"></i>
                </a>
                <div class="collapse <?= (str_starts_with($activeMenu ?? '', 'settings')) ? 'show' : '' ?>" id="settingsMenu">
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
                            <a class="nav-link text-white-50 <?= ($activeMenu ?? '') === 'settings-seo' ? 'text-white' : '' ?>" 
                               href="<?= base_url('admin/settings/seo') ?>">
                                <i class="bi bi-search"></i> SEO
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50 <?= ($activeMenu ?? '') === 'settings-oauth' ? 'text-white' : '' ?>" 
                               href="<?= base_url('admin/settings/oauth') ?>">
                                <i class="bi bi-shield-lock"></i> Authentification OAuth
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50 <?= ($activeMenu ?? '') === 'settings-users' ? 'text-white' : '' ?>" 
                               href="<?= base_url('admin/settings/users') ?>">
                                <i class="bi bi-person"></i> Utilisateurs
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
                
                <hr class="border-secondary my-3">
                
                <a class="nav-link text-white" href="<?= base_url('/') ?>" target="_blank">
                    <i class="bi bi-box-arrow-up-right"></i> Voir le site
                </a>
                
                <a class="nav-link text-white" href="<?= base_url('logout') ?>">
                    <i class="bi bi-box-arrow-right"></i> Déconnexion
                </a>
            </nav>
        </div>
