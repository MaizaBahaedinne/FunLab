<!-- Navigation Moderne -->
<nav class="modern-navbar">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <!-- Logo -->
            <a class="navbar-brand-logo" href="<?= base_url('/') ?>">
                <?php 
                $settingModel = new \App\Models\SettingModel();
                $siteLogo = $settingModel->getSetting('site_logo');
                $logoWidth = $settingModel->getSetting('logo_width') ?: 50;
                $logoHeight = $settingModel->getSetting('logo_height') ?: 50;
                
                if ($siteLogo): 
                ?>
                    <img src="<?= esc($siteLogo) ?>" 
                         alt="FunLab Logo" 
                         width="<?= esc($logoWidth) ?>" 
                         height="<?= esc($logoHeight) ?>">
                <?php else: ?>
                    <i class="bi bi-joystick" style="font-size: 2rem; color: #667eea;"></i>
                <?php endif; ?>
                <span class="brand-text">FunLab</span>
            </a>
            
            <!-- Toggle Button Mobile -->
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#modernNav">
                <i class="bi bi-list" style="font-size: 1.8rem; color: #333;"></i>
            </button>
            
            <!-- Menu -->
            <div class="collapse navbar-collapse" id="modernNav">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link-modern <?= ($activeMenu ?? '') === 'home' ? 'active' : '' ?>" 
                           href="<?= base_url('/') ?>">
                            <i class="bi bi-house-door"></i>Accueil
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link-modern <?= ($activeMenu ?? '') === 'about' ? 'active' : '' ?>" 
                           href="<?= base_url('about') ?>">
                            <i class="bi bi-info-circle"></i>À Propos
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link-modern dropdown-toggle <?= ($activeMenu ?? '') === 'games' ? 'active' : '' ?>" 
                           href="<?= base_url('games') ?>" 
                           role="button" 
                           data-bs-toggle="dropdown">
                            <i class="bi bi-controller"></i>Jeux
                        </a>
                        <ul class="dropdown-menu dropdown-menu-modern">
                            <li>
                                <a class="dropdown-item-modern" href="<?= base_url('games') ?>">
                                    <i class="bi bi-grid-3x3-gap me-2"></i>Tous les Jeux
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
                            <i class="bi bi-envelope"></i>Contact
                        </a>
                    </li>
                </ul>
                
                <!-- User Menu & Réserver -->
                <div class="d-flex align-items-center gap-3">
                    <?php if (session()->get('isLoggedIn')): ?>
                    <div class="dropdown">
                        <a class="nav-link-modern dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i><?= esc(session()->get('username')) ?>
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
                    </div>
                    <?php else: ?>
                    <a class="nav-link-modern" href="<?= base_url('login') ?>">
                        <i class="bi bi-box-arrow-in-right"></i>Connexion
                    </a>
                    <?php endif; ?>
                    
                    <a href="<?= base_url('booking') ?>" class="btn-reserve-modern">
                        <i class="bi bi-calendar-check me-2"></i>Réserver
                    </a>
                </div>
            </div>
        </div>
    </div>
</nav>

<script>
// Animation navbar au scroll
window.addEventListener('scroll', function() {
    const navbar = document.querySelector('.modern-navbar');
    if (window.scrollY > 50) {
        navbar.classList.add('scrolled');
    } else {
        navbar.classList.remove('scrolled');
    }
});

// Smooth scroll pour les ancres
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
