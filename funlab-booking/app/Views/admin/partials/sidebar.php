<nav class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse" style="min-height: 100vh;">
    <div class="position-sticky pt-3">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link text-white <?= (uri_string() == 'admin/dashboard' || uri_string() == 'admin') ? 'active bg-primary' : '' ?>" 
                    href="<?= base_url('admin/dashboard') ?>">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white <?= strpos(uri_string(), 'admin/rooms') !== false ? 'active bg-primary' : '' ?>" 
                    href="<?= base_url('admin/rooms') ?>">
                    <i class="bi bi-door-open"></i> Salles
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white <?= strpos(uri_string(), 'admin/games') !== false ? 'active bg-primary' : '' ?>" 
                    href="<?= base_url('admin/games') ?>">
                    <i class="bi bi-controller"></i> Jeux
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white <?= strpos(uri_string(), 'admin/bookings') !== false ? 'active bg-primary' : '' ?>" 
                    href="<?= base_url('admin/bookings') ?>">
                    <i class="bi bi-calendar-check"></i> Réservations
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white <?= strpos(uri_string(), 'admin/closures') !== false ? 'active bg-primary' : '' ?>" 
                    href="<?= base_url('admin/closures') ?>">
                    <i class="bi bi-calendar-x"></i> Fermetures
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white <?= strpos(uri_string(), 'admin/scanner') !== false ? 'active bg-primary' : '' ?>" 
                    href="<?= base_url('admin/scanner') ?>">
                    <i class="bi bi-qr-code-scan"></i> Scanner QR
                </a>
            </li>
            
            <!-- Paramètres avec sous-menu -->
            <li class="nav-item">
                <a class="nav-link text-white <?= strpos(uri_string(), 'admin/settings') !== false ? 'active' : '' ?>" 
                    data-bs-toggle="collapse" href="#settingsMenu" role="button" 
                    aria-expanded="<?= strpos(uri_string(), 'admin/settings') !== false ? 'true' : 'false' ?>">
                    <i class="bi bi-gear"></i> Paramètres
                    <i class="bi bi-chevron-down float-end"></i>
                </a>
                <div class="collapse <?= strpos(uri_string(), 'admin/settings') !== false ? 'show' : '' ?>" id="settingsMenu">
                    <ul class="nav flex-column ms-3">
                        <li class="nav-item">
                            <a class="nav-link text-white-50 <?= uri_string() == 'admin/settings/general' ? 'active' : '' ?>" 
                                href="<?= base_url('admin/settings/general') ?>">
                                <i class="bi bi-info-circle"></i> Général
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50 <?= uri_string() == 'admin/settings/hours' ? 'active' : '' ?>" 
                                href="<?= base_url('admin/settings/hours') ?>">
                                <i class="bi bi-clock"></i> Horaires
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50 <?= uri_string() == 'admin/settings/mail' ? 'active' : '' ?>" 
                                href="<?= base_url('admin/settings/mail') ?>">
                                <i class="bi bi-envelope"></i> Email
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50 <?= uri_string() == 'admin/settings/sms' ? 'active' : '' ?>" 
                                href="<?= base_url('admin/settings/sms') ?>">
                                <i class="bi bi-phone"></i> SMS
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50 <?= uri_string() == 'admin/settings/seo' ? 'active' : '' ?>" 
                                href="<?= base_url('admin/settings/seo') ?>">
                                <i class="bi bi-search"></i> SEO
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50 <?= uri_string() == 'admin/settings/users' ? 'active' : '' ?>" 
                                href="<?= base_url('admin/settings/users') ?>">
                                <i class="bi bi-people"></i> Utilisateurs
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50 <?= uri_string() == 'admin/settings/roles' ? 'active' : '' ?>" 
                                href="<?= base_url('admin/settings/roles') ?>">
                                <i class="bi bi-shield-check"></i> Rôles & Permissions
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>
</nav>

<style>
.sidebar .nav-link {
    padding: 0.75rem 1rem;
    border-radius: 0.25rem;
    margin-bottom: 0.25rem;
}
.sidebar .nav-link:hover {
    background-color: rgba(255, 255, 255, 0.1);
}
.sidebar .nav-link[data-bs-toggle] {
    cursor: pointer;
}
</style>
