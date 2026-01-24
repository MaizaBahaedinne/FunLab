<?php
$title = 'FunLab Tunisie - Centre d\'Activités Indoor';
$activeMenu = 'home';
?>

<?= view('front/layouts/header', compact('title')) ?>
<?= view('front/layouts/navbar', compact('activeMenu')) ?>

    <!-- Hero Section -->
    <section class="hero-section text-center">
        <div class="container">
            <h1 class="display-3 fw-bold mb-4">Bienvenue chez FunLab Tunisie</h1>
            <p class="lead mb-5">Escape Game • Réalité Virtuelle • Laser Game</p>
            <a href="<?= base_url('booking') ?>" class="btn btn-light btn-lg px-5">
                <i class="bi bi-calendar-check"></i> Réserver Maintenant
            </a>
        </div>
    </section>

    <!-- Activités -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">Nos Activités</h2>
            <div class="row g-4">
                <!-- Escape Room -->
                <div class="col-md-4">
                    <div class="card game-card">
                        <div class="card-body text-center p-4">
                            <i class="bi bi-door-closed feature-icon mb-3"></i>
                            <h3 class="card-title">Escape Room</h3>
                            <p class="card-text">
                                Résolvez des énigmes et échappez-vous en équipe. 
                                Plusieurs scénarios disponibles !
                            </p>
                            <ul class="list-unstyled text-start">
                                <li><i class="bi bi-check-circle text-success"></i> 4-8 joueurs</li>
                                <li><i class="bi bi-check-circle text-success"></i> 60 minutes</li>
                                <li><i class="bi bi-check-circle text-success"></i> À partir de 120 DT</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Réalité Virtuelle -->
                <div class="col-md-4">
                    <div class="card game-card">
                        <div class="card-body text-center p-4">
                            <i class="bi bi-headset-vr feature-icon mb-3"></i>
                            <h3 class="card-title">Réalité Virtuelle</h3>
                            <p class="card-text">
                                Plongez dans des mondes immersifs avec nos casques VR dernière génération.
                            </p>
                            <ul class="list-unstyled text-start">
                                <li><i class="bi bi-check-circle text-success"></i> 1-2 joueurs</li>
                                <li><i class="bi bi-check-circle text-success"></i> 30-60 minutes</li>
                                <li><i class="bi bi-check-circle text-success"></i> À partir de 25 DT</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Laser Game -->
                <div class="col-md-4">
                    <div class="card game-card">
                        <div class="card-body text-center p-4">
                            <i class="bi bi-lightning-charge feature-icon mb-3"></i>
                            <h3 class="card-title">Laser Game</h3>
                            <p class="card-text">
                                Affrontez vos amis dans notre arène high-tech. Action garantie !
                            </p>
                            <ul class="list-unstyled text-start">
                                <li><i class="bi bi-check-circle text-success"></i> 4-12 joueurs</li>
                                <li><i class="bi bi-check-circle text-success"></i> 30-60 minutes</li>
                                <li><i class="bi bi-check-circle text-success"></i> À partir de 15 DT</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pourquoi FunLab -->
    <section class="bg-light py-5">
        <div class="container">
            <h2 class="text-center mb-5">Pourquoi Choisir FunLab ?</h2>
            <div class="row g-4">
                <div class="col-md-3 text-center">
                    <i class="bi bi-trophy feature-icon mb-3"></i>
                    <h4>Qualité Premium</h4>
                    <p>Équipements dernière génération</p>
                </div>
                <div class="col-md-3 text-center">
                    <i class="bi bi-shield-check feature-icon mb-3"></i>
                    <h4>Sécurisé</h4>
                    <p>Réservation en ligne sécurisée</p>
                </div>
                <div class="col-md-3 text-center">
                    <i class="bi bi-clock feature-icon mb-3"></i>
                    <h4>Horaires Flexibles</h4>
                    <p>Ouvert de 9h à 22h</p>
                </div>
                <div class="col-md-3 text-center">
                    <i class="bi bi-people feature-icon mb-3"></i>
                    <h4>Pour Tous</h4>
                    <p>Idéal pour familles et groupes</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-5 bg-primary text-white text-center">
        <div class="container">
            <h2 class="mb-4">Prêt pour l'Aventure ?</h2>
            <p class="lead mb-4">Réservez votre créneau dès maintenant et vivez une expérience inoubliable !</p>
            <a href="<?= base_url('booking') ?>" class="btn btn-light btn-lg px-5">
                Réserver Maintenant
            </a>
        </div>
    </section>

<?= view('front/layouts/footer') ?>
