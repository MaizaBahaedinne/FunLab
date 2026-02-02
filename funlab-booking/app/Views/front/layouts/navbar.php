<?php 
helper('theme');
$logo = theme_setting('site_logo', '/assets/images/logo.png');
$logoWidth = theme_setting('logo_width', 75);
$logoHeight = theme_setting('logo_height', 75);
$phone = theme_setting('header_topbar_phone', theme_setting('footer_phone', ''));
$email = theme_setting('header_topbar_email', theme_setting('footer_email', ''));
$showTopbar = theme_setting('header_show_topbar', '1') === '1';
?>

<!-- Top Info Bar -->
<?php if($showTopbar && ($phone || $email)): ?>
<div class="top-bar d-none d-lg-block">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="d-flex gap-3">
                    <?php if($phone): ?>
                    <span><i class="bi bi-telephone-fill"></i> <?= esc($phone) ?></span>
                    <?php endif; ?>
                    <?php if($email): ?>
                    <span><i class="bi bi-envelope-fill"></i> <?= esc($email) ?></span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-6 text-end">
                <?php if (session()->get('isLoggedIn')): ?>
                    <span><i class="bi bi-person-circle"></i> Bonjour, <?= esc(session()->get('firstName')) ?></span>
                <?php else: ?>
                    <a href="<?= base_url('login') ?>"><i class="bi bi-box-arrow-in-right"></i> Connexion</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Main Header -->
<header class="main-header">
    <nav class="navbar navbar-expand-lg main-nav">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand-logo" href="<?= base_url('/') ?>">
                <?php if ($logo): ?>
                    <img src="<?= esc($logo) ?>" 
                         alt="<?= esc(theme_setting('app_name', 'FunLab')) ?> Logo" 
                         width="<?= esc($logoWidth) ?>" 
                         height="<?= esc($logoHeight) ?>">
                <?php else: ?>
                    <i class="bi bi-joystick" style="font-size: 2.5rem; color: var(--primary-color);"></i>
                <?php endif; ?>
                <span class="brand-text">FunLab</span>
            </a>
            
            <!-- Toggle Button -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                <i class="bi bi-list" style="font-size: 1.8rem;"></i>
            </button>
            
            <!-- Menu -->
            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link-modern <?= ($activeMenu ?? '') === 'home' ? 'active' : '' ?>" 
                           href="<?= base_url('/') ?>">
                            Accueil
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link-modern <?= ($activeMenu ?? '') === 'about' ? 'active' : '' ?>" 
                           href="<?= base_url('about') ?>">
                            À Propos
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link-modern dropdown-toggle <?= ($activeMenu ?? '') === 'games' ? 'active' : '' ?>" 
                           href="<?= base_url('games') ?>" 
                           role="button" 
                           data-bs-toggle="dropdown">
                            Activités
                        </a>
                        <ul class="dropdown-menu dropdown-menu-modern">
                            <li>
                                <a class="dropdown-item-modern" href="<?= base_url('games') ?>">
                                    <i class="bi bi-grid-3x3-gap me-2"></i>Toutes les Activités
                                </a>
                            </li>
                            <?php 
                            $gameModel = new \App\Models\GameModel();
                            $menuGames = $gameModel->where('status', 'active')->orderBy('name', 'ASC')->findAll();
                            if (!empty($menuGames)): 
                            ?>
                                <li><hr class="dropdown-divider"></li>
                                <?php foreach ($menuGames as $menuGame): ?>
                                <li>
                                    <a class="dropdown-item-modern" 
                                       href="<?= base_url('booking?game=' . $menuGame['id']) ?>">
                                        <i class="bi bi-joystick me-2"></i><?= esc($menuGame['name']) ?>
                                    </a>
                                </li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link-modern <?= ($activeMenu ?? '') === 'contact' ? 'active' : '' ?>" 
                           href="<?= base_url('contact') ?>">
                            Contact
                        </a>
                    </li>
                    
                    <?php if (session()->get('isLoggedIn')): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link-modern dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-modern dropdown-menu-end">
                            <li>
                                <a class="dropdown-item-modern" href="<?= base_url('account') ?>">
                                    <i class="bi bi-person me-2"></i>Mon Profil
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item-modern" href="<?= base_url('account/bookings') ?>">
                                    <i class="bi bi-ticket-perforated me-2"></i>Mes Réservations
                                </a>
                            </li>
                            <?php if (in_array(session()->get('role'), ['admin', 'staff'])): ?>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item-modern" href="<?= base_url('admin') ?>">
                                    <i class="bi bi-speedometer2 me-2"></i>Administration
                                </a>
                            </li>
                            <?php endif; ?>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item-modern" href="<?= base_url('logout') ?>">
                                    <i class="bi bi-box-arrow-right me-2"></i>Déconnexion
                                </a>
                            </li>
                        </ul>
                    </li>
                    <?php endif; ?>
                    
                    <li class="nav-item ms-3">
                        <a href="<?= base_url('booking') ?>" class="btn-book-now">
                            Réserver
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>

<script>
// Sticky header effect
window.addEventListener('scroll', function() {
    const header = document.querySelector('.main-header');
    if (window.scrollY > 50) {
        header.classList.add('scrolled');
    } else {
        header.classList.remove('scrolled');
    }
});

// Smooth scroll
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});
</script>
