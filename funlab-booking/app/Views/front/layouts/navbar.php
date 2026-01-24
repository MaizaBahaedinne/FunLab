<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="<?= base_url('/') ?>">
            <i class="bi bi-joystick"></i> FunLab Tunisie
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link <?= ($activeMenu ?? '') === 'home' ? 'active' : '' ?>" href="<?= base_url('/') ?>">
                        <i class="bi bi-house"></i> Accueil
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($activeMenu ?? '') === 'about' ? 'active' : '' ?>" href="<?= base_url('about') ?>">
                        <i class="bi bi-info-circle"></i> À Propos
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($activeMenu ?? '') === 'games' ? 'active' : '' ?>" href="<?= base_url('games') ?>">
                        <i class="bi bi-controller"></i> Jeux
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($activeMenu ?? '') === 'booking' ? 'active' : '' ?>" href="<?= base_url('booking') ?>">
                        <i class="bi bi-calendar-check"></i> Réservation
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($activeMenu ?? '') === 'contact' ? 'active' : '' ?>" href="<?= base_url('contact') ?>">
                        <i class="bi bi-envelope"></i> Contact
                    </a>
                </li>
                
                <?php if (session()->get('isLoggedIn')): ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle"></i> <?= esc(session()->get('username')) ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="<?= base_url('account') ?>">
                                <i class="bi bi-person"></i> Mon Compte
                            </a>
                        </li>
                        <?php if (in_array(session()->get('role'), ['admin', 'staff'])): ?>
                        <li>
                            <a class="dropdown-item" href="<?= base_url('admin') ?>">
                                <i class="bi bi-speedometer2"></i> Administration
                            </a>
                        </li>
                        <?php endif; ?>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="<?= base_url('logout') ?>">
                                <i class="bi bi-box-arrow-right"></i> Déconnexion
                            </a>
                        </li>
                    </ul>
                </li>
                <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('login') ?>">
                        <i class="bi bi-box-arrow-in-right"></i> Connexion
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
