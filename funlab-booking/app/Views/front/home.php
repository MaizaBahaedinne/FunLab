<?php
$title = theme_setting('app_name', 'FunLab Tunisie') . ' - Centre d\'Activités Indoor';
$activeMenu = 'home';
?>

<?= view('front/layouts/header', compact('title')) ?>
<?= view('front/layouts/navbar', compact('activeMenu')) ?>

<!-- Slider Homepage -->
<?= view('front/components/slider') ?>

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
            <div class="row text-center g-4">
                <div class="col-md-4">
                    <div class="stat-box">
                        <div class="stat-icon">
                            <i class="bi bi-trophy"></i>
                        </div>
                        <h3 class="stat-number"><?= $stats['total_games'] ?></h3>
                        <p class="stat-label">Jeux Disponibles</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-box">
                        <div class="stat-icon">
                            <i class="bi bi-calendar-check"></i>
                        </div>
                        <h3 class="stat-number"><?= $stats['total_bookings'] ?></h3>
                        <p class="stat-label">Réservations</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-box">
                        <div class="stat-icon">
                            <i class="bi bi-emoji-smile"></i>
                        </div>
                        <h3 class="stat-number"><?= $stats['happy_customers'] ?></h3>
                        <p class="stat-label">Clients Satisfaits</p>
                    </div>
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
                    <div class="review-card">
                        <div class="review-header">
                            <div class="review-avatar">
                                <span><?= strtoupper(substr($review['name'] ?? ($review['first_name'] ?? 'A'), 0, 1)) ?></span>
                            </div>
                            <div class="review-info">
                                <h5 class="review-name"><?= esc($review['name'] ?? ($review['first_name'] . ' ' . $review['last_name'])) ?></h5>
                                <div class="review-rating">
                                    <?php for ($i = 0; $i < 5; $i++): ?>
                                    <i class="bi bi-star-fill <?= $i < $review['rating'] ? 'active' : '' ?>"></i>
                                    <?php endfor; ?>
                                </div>
                            </div>
                        </div>
                        <p class="review-comment">"<?= esc($review['comment']) ?>"</p>
                        <div class="review-footer">
                            <small class="review-date">
                                <i class="bi bi-calendar3"></i> <?= date('d/m/Y', strtotime($review['created_at'])) ?>
                            </small>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Pourquoi FunLab -->
    <section class="bg-white py-5">
        <div class="container">
            <h2 class="text-center mb-5">Pourquoi Choisir FunLab ?</h2>
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="feature-box">
                        <div class="feature-icon-circle">
                            <i class="bi bi-trophy"></i>
                        </div>
                        <h4 class="feature-title">Qualité Premium</h4>
                        <p class="feature-description">Équipements dernière génération</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="feature-box">
                        <div class="feature-icon-circle">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h4 class="feature-title">Sécurisé</h4>
                        <p class="feature-description">Réservation en ligne sécurisée</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="feature-box">
                        <div class="feature-icon-circle">
                            <i class="bi bi-clock"></i>
                        </div>
                        <h4 class="feature-title">Horaires Flexibles</h4>
                        <p class="feature-description">Ouvert de 9h à 22h</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="feature-box">
                        <div class="feature-icon-circle">
                            <i class="bi bi-people"></i>
                        </div>
                        <h4 class="feature-title">Pour Tous</h4>
                        <p class="feature-description">Idéal pour familles et groupes</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-5 text-white text-center cta-section">
        <div class="container">
            <h2 class="mb-4">Prêt pour l'Aventure ?</h2>
            <p class="lead mb-4">Réservez votre créneau dès maintenant et vivez une expérience inoubliable !</p>
            <a href="<?= base_url('booking') ?>" class="btn btn-light btn-lg px-5">
                Réserver Maintenant
            </a>
        </div>
    </section>

    <!-- Newsletter -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="newsletter-box">
                <div class="row align-items-center">
                    <div class="col-md-5 mb-4 mb-md-0 text-center text-md-start">
                        <div class="newsletter-icon">
                            <i class="bi bi-envelope-heart"></i>
                        </div>
                        <h3 class="newsletter-title">Restez Informé</h3>
                        <p class="newsletter-subtitle">Inscrivez-vous à notre newsletter pour recevoir nos offres exclusives et nouveautés</p>
                    </div>
                    <div class="col-md-7">
                        <form id="newsletterForm" class="newsletter-form">
                            <div class="input-group">
                                <input type="email" class="form-control" placeholder="Votre email" required>
                                <button class="btn btn-primary" type="submit">
                                    <i class="bi bi-send"></i> S'inscrire
                                </button>
                            </div>
                            <small class="text-muted d-block mt-2">
                                <i class="bi bi-shield-check"></i> Vos données sont protégées
                            </small>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?= view('front/layouts/footer') ?>
