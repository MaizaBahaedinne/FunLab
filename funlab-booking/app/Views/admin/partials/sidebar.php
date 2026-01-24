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
            <li class="nav-item">
                <a class="nav-link text-white <?= strpos(uri_string(), 'admin/settings') !== false ? 'active bg-primary' : '' ?>" 
                    href="<?= base_url('admin/settings') ?>">
                    <i class="bi bi-gear"></i> Paramètres
                </a>
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
</style>
