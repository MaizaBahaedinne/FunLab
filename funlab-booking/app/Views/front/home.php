<?php
$title = theme_setting('app_name', 'FunLab Tunisie') . ' - Centre d\'Activités Indoor';
$activeMenu = 'home';
?>

<?= view('front/layouts/header', compact('title')) ?>
<?= view('front/layouts/navbar', compact('activeMenu')) ?>

<!-- Home Hero Section -->
<section class="home-hero text-center">
    <div class="home-hero-content">
        <div class="container">
            <h1 class="display-3 fw-bold mb-4">Bienvenue chez <?= theme_setting('app_name', 'FunLab Tunisie') ?></h1>
            <p class="lead mb-5">Escape Game • Réalité Virtuelle • Laser Game • Arcade</p>
            <div class="cta-buttons">
                <a href="<?= base_url('booking') ?>" class="btn btn-light btn-lg px-5 me-3">
                    <i class="bi bi-calendar-check"></i> Réserver Maintenant
                </a>
                <a href="<?= base_url('games') ?>" class="btn btn-outline-light btn-lg px-5">
                    <i class="bi bi-controller"></i> Découvrir nos Activités
                </a>
            </div>
        </div>
    </div>
</section>

    <!-- Activités -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">Nos Jeux</h2>
            <?php if (!empty($games)): ?>
            <div class="row g-4">
                <?php foreach ($games as $game): ?>
                <div class="col-md-4">
                    <div class="card game-card h-100">
                        <?php if (!empty($game['image'])): ?>
                        <img src="<?= base_url('uploads/games/' . $game['image']) ?>" class="card-img-top" alt="<?= esc($game['name']) ?>">
                        <?php endif; ?>
                        <div class="card-body">
                            <?php if (!empty($game['category_name'])): ?>
                            <span class="badge bg-primary mb-2">
                                <?php if (!empty($game['category_icon'])): ?>
                                <i class="<?= esc($game['category_icon']) ?>"></i>
                                <?php endif; ?>
                                <?= esc($game['category_name']) ?>
                            </span>
                            <?php endif; ?>
                            <h3 class="card-title h5"><?= esc($game['name']) ?></h3>
                            <p class="card-text"><?= esc(mb_substr($game['description'], 0, 100)) ?>...</p>
                            <ul class="list-unstyled">
                                <li><i class="bi bi-people text-primary"></i> <?= $game['min_players'] ?>-<?= $game['max_players'] ?> joueurs</li>
                                <li><i class="bi bi-clock text-primary"></i> <?= $game['duration_minutes'] ?> minutes</li>
                                <li><i class="bi bi-tag text-primary"></i> À partir de <?= number_format($game['price'], 0) ?> DT</li>
                            </ul>
                            <a href="<?= base_url('games/' . $game['id']) ?>" class="btn btn-primary w-100">
                                <i class="bi bi-info-circle"></i> Découvrir
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="text-center mt-4">
                <a href="<?= base_url('games') ?>" class="btn btn-outline-primary">
                    Voir tous les jeux <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            <?php else: ?>
            <div class="alert alert-info text-center">
                <i class="bi bi-info-circle"></i> Aucun jeu disponible pour le moment.
            </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Statistiques -->
    <section class="bg-light py-5">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-4 mb-4 mb-md-0">
                    <i class="bi bi-trophy feature-icon text-primary mb-3"></i>
                    <h3 class="display-4 fw-bold"><?= $stats['total_games'] ?></h3>
                    <p class="text-muted">Jeux Disponibles</p>
                </div>
                <div class="col-md-4 mb-4 mb-md-0">
                    <i class="bi bi-calendar-check feature-icon text-primary mb-3"></i>
                    <h3 class="display-4 fw-bold"><?= $stats['total_bookings'] ?></h3>
                    <p class="text-muted">Réservations</p>
                </div>
                <div class="col-md-4">
                    <i class="bi bi-emoji-smile feature-icon text-primary mb-3"></i>
                    <h3 class="display-4 fw-bold"><?= $stats['happy_customers'] ?></h3>
                    <p class="text-muted">Clients Satisfaits</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Avis Clients -->
    <?php if (!empty($reviews)): ?>
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">Ce Que Disent Nos Clients</h2>
            <div class="row g-4">
                <?php foreach ($reviews as $review): ?>
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="me-3">
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                        <span class="fw-bold"><?= strtoupper(substr($review['name'] ?? 'A', 0, 1)) ?></span>
                                    </div>
                                </div>
                                <div>
                                    <h5 class="mb-0"><?= esc($review['name'] ?? ($review['first_name'] . ' ' . $review['last_name'])) ?></h5>
                                    <div class="text-warning">
                                        <?php for ($i = 0; $i < $review['rating']; $i++): ?>
                                        <i class="bi bi-star-fill"></i>
                                        <?php endfor; ?>
                                        <?php for ($i = $review['rating']; $i < 5; $i++): ?>
                                        <i class="bi bi-star"></i>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                            </div>
                            <p class="card-text">"<?= esc($review['comment']) ?>"</p>
                            <small class="text-muted"><?= date('d/m/Y', strtotime($review['created_at'])) ?></small>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

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
