<nav class="navbar navbar-dark bg-dark sticky-top shadow">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?= base_url('admin/dashboard') ?>">
            <i class="bi bi-joystick"></i> FunLab Admin
        </a>
        <div class="d-flex align-items-center">
            <span class="text-white me-3">
                <i class="bi bi-person-circle"></i> <?= esc(session()->get('userName') ?? 'Admin') ?>
            </span>
            <a href="<?= base_url('auth/logout') ?>" class="btn btn-outline-light btn-sm">
                <i class="bi bi-box-arrow-right"></i> Déconnexion
            </a>
        </div>
    </div>
</nav>
