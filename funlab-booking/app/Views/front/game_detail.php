<?php
$additionalStyles = <<<CSS
<style>
.game-detail-hero {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 40px 0;
    margin-bottom: 50px;
}

.breadcrumb {
    background: transparent;
    padding: 0;
    margin-bottom: 20px;
}

.breadcrumb-item a {
    color: rgba(255,255,255,0.8);
    text-decoration: none;
}

.breadcrumb-item.active {
    color: white;
}

.game-image-container {
    position: relative;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 20px 40px rgba(0,0,0,0.2);
    margin-bottom: 30px;
}

.game-main-image {
    width: 100%;
    height: 500px;
    object-fit: cover;
}

.game-placeholder-image {
    width: 100%;
    height: 500px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    font-size: 8rem;
}

.category-badge {
    position: absolute;
    top: 20px;
    left: 20px;
    padding: 10px 20px;
    border-radius: 50px;
    font-weight: 600;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    gap: 8px;
    backdrop-filter: blur(10px);
    background: rgba(255,255,255,0.9);
}

.game-info-card {
    background: white;
    border-radius: 20px;
    padding: 30px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    position: sticky;
    top: 100px;
}

.game-price {
    font-size: 2.5rem;
    font-weight: 700;
    color: #667eea;
    margin-bottom: 10px;
}

.game-price-label {
    color: #718096;
    font-size: 0.9rem;
    margin-bottom: 20px;
}

.game-specs {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
    margin: 25px 0;
    padding: 25px 0;
    border-top: 1px solid #e2e8f0;
    border-bottom: 1px solid #e2e8f0;
}

.spec-item {
    display: flex;
    align-items: center;
    gap: 12px;
}

.spec-icon {
    font-size: 1.5rem;
    color: #667eea;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f7fafc;
    border-radius: 10px;
}

.spec-content {
    flex: 1;
}

.spec-label {
    font-size: 0.75rem;
    color: #718096;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.spec-value {
    font-size: 1rem;
    font-weight: 600;
    color: #2d3748;
}

.btn-book-now {
    width: 100%;
    padding: 18px;
    font-size: 1.1rem;
    font-weight: 600;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    color: white;
    border-radius: 12px;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

.btn-book-now:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
}

.share-buttons {
    display: flex;
    gap: 10px;
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid #e2e8f0;
}

.share-button {
    flex: 1;
    padding: 12px;
    border: none;
    border-radius: 10px;
    color: white;
    font-weight: 600;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    text-decoration: none;
}

.share-button:hover {
    transform: translateY(-2px);
    color: white;
}

.share-facebook {
    background: #1877f2;
}

.share-twitter {
    background: #1da1f2;
}

.share-whatsapp {
    background: #25d366;
}

.share-linkedin {
    background: #0a66c2;
}

.game-description-section {
    background: white;
    border-radius: 20px;
    padding: 40px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    margin-bottom: 30px;
}

.section-title {
    font-size: 1.8rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 15px;
}

.section-title i {
    color: #667eea;
}

.game-description {
    color: #4a5568;
    font-size: 1.1rem;
    line-height: 1.8;
}

.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-top: 30px;
}

.feature-item {
    background: #f7fafc;
    padding: 20px;
    border-radius: 15px;
    display: flex;
    align-items: start;
    gap: 15px;
}

.feature-icon {
    font-size: 2rem;
    color: #667eea;
}

.feature-content h4 {
    font-size: 1.1rem;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 5px;
}

.feature-content p {
    color: #718096;
    margin: 0;
    font-size: 0.9rem;
}

.related-games {
    margin-top: 60px;
}

.alert-deposit {
    background: #fef5e7;
    border-left: 4px solid #f39c12;
    padding: 15px;
    border-radius: 10px;
    margin-top: 20px;
}

/* Hero Section Modern Redesign */
.game-detail-hero-top {
    position: relative;
    min-height: 500px;
    display: flex;
    align-items: center;
    overflow: hidden;
    margin-bottom: 50px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.game-detail-hero-top::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23ffffff" fill-opacity="0.1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,112C672,96,768,96,864,112C960,128,1056,160,1152,165.3C1248,171,1344,149,1392,138.7L1440,128L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') no-repeat bottom;
    background-size: cover;
    opacity: 0.3;
}

.game-detail-hero-top::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: radial-gradient(circle at 20% 50%, rgba(255,255,255,0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(255,255,255,0.1) 0%, transparent 50%);
}

.game-hero-content {
    position: relative;
    z-index: 2;
    text-align: center;
    color: white;
    padding: 80px 0;
    max-width: 1000px;
    margin: 0 auto;
}

.game-hero-category {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 12px 28px;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    border-radius: 50px;
    font-size: 0.95rem;
    font-weight: 600;
    margin-bottom: 30px;
    border: 1px solid rgba(255, 255, 255, 0.3);
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    animation: fadeInDown 0.6s ease-out;
}

.game-hero-category i {
    font-size: 1.3rem;
}

.game-hero-title {
    font-size: 4rem;
    font-weight: 800;
    margin-bottom: 30px;
    text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    line-height: 1.1;
    animation: fadeInUp 0.6s ease-out 0.2s both;
    letter-spacing: -1px;
}

.game-hero-specs {
    display: flex;
    justify-content: center;
    gap: 50px;
    margin-top: 50px;
    flex-wrap: wrap;
    animation: fadeInUp 0.6s ease-out 0.4s both;
}

.hero-spec-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
    padding: 25px 35px;
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    border: 1px solid rgba(255, 255, 255, 0.25);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    min-width: 180px;
}

.hero-spec-item:hover {
    background: rgba(255, 255, 255, 0.25);
    transform: translateY(-8px);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
}

.hero-spec-icon {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.25);
    border-radius: 18px;
    font-size: 1.8rem;
}

.hero-spec-value {
    font-size: 2rem;
    font-weight: 700;
    margin-top: 8px;
}

.hero-spec-label {
    font-size: 0.9rem;
    font-weight: 500;
    opacity: 0.95;
    text-transform: uppercase;
    letter-spacing: 1.5px;
}

@keyframes fadeInDown {
    from {
        opacity: 0;
        transform: translateY(-30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@media (max-width: 992px) {
    .game-hero-title {
        font-size: 3rem;
    }
    
    .game-hero-specs {
        gap: 30px;
    }
}

@media (max-width: 768px) {
    .game-hero-title {
        font-size: 2.5rem;
    }
    
    .game-hero-specs {
        gap: 20px;
    }
    
    .hero-spec-item {
        padding: 20px 25px;
        min-width: 150px;
    }
    
    .hero-spec-icon {
        width: 50px;
        height: 50px;
        font-size: 1.5rem;
    }
    
    .hero-spec-value {
        font-size: 1.6rem;
    }
    
    .game-hero-content {
        padding: 60px 0;
    }
}
</style>
CSS;
?>

<?= view('front/layouts/header', array_merge(compact('title', 'additionalStyles'), [
    'metaTitle' => $metaTitle ?? '',
    'metaDescription' => $metaDescription ?? '',
    'metaKeywords' => $metaKeywords ?? '',
    'canonicalUrl' => $canonicalUrl ?? '',
    'ogType' => $ogType ?? '',
    'ogUrl' => $ogUrl ?? '',
    'ogTitle' => $ogTitle ?? '',
    'ogDescription' => $ogDescription ?? '',
    'ogImage' => $ogImage ?? '',
    'twitterUrl' => $twitterUrl ?? '',
    'twitterTitle' => $twitterTitle ?? '',
    'twitterDescription' => $twitterDescription ?? '',
    'twitterImage' => $twitterImage ?? ''
])) ?>
<?= view('front/layouts/navbar', compact('activeMenu')) ?>

<!-- Hero Section -->
<section class="game-detail-hero-top">
    <div class="container">
        <div class="game-hero-content">
            <?php if (!empty($game['category_name'])): ?>
                <div class="game-hero-category">
                    <i class="<?= esc($game['category_icon']) ?>"></i>
                    <span><?= esc($game['category_name']) ?></span>
                </div>
            <?php endif; ?>
            
            <h1 class="game-hero-title"><?= esc($game['name']) ?></h1>
            
            <div class="game-hero-specs">
                <div class="hero-spec-item">
                    <div class="hero-spec-icon"><i class="bi bi-clock"></i></div>
                    <div class="hero-spec-value"><?= $game['duration_minutes'] ?> min</div>
                    <div class="hero-spec-label">Durée</div>
                </div>
                <div class="hero-spec-item">
                    <div class="hero-spec-icon"><i class="bi bi-people"></i></div>
                    <div class="hero-spec-value"><?= $game['min_players'] ?>-<?= $game['max_players'] ?></div>
                    <div class="hero-spec-label">Joueurs</div>
                </div>
                <div class="hero-spec-item">
                    <div class="hero-spec-icon"><i class="bi bi-tag"></i></div>
                    <div class="hero-spec-value"><?= number_format($game['price'], 0) ?> TND</div>
                    <div class="hero-spec-label">Prix</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Breadcrumb removed from hero, now separate -->
<section class="container mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-transparent">
            <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Accueil</a></li>
            <li class="breadcrumb-item"><a href="<?= base_url('games') ?>">Jeux</a></li>
            <li class="breadcrumb-item active"><?= esc($game['name']) ?></li>
        </ol>
    </nav>
</section>

<!-- Game Detail -->
<section class="pb-5">
    <div class="container">
        <div class="row">
            <!-- Left Column - Images & Description -->
            <div class="col-lg-7">
                <!-- Main Image -->
                <div class="game-image-container">
                    <?php if (!empty($game['image'])): ?>
                        <img src="<?= base_url('uploads/games/' . esc($game['image'])) ?>" 
                             alt="<?= esc($game['name']) ?>"
                             class="game-main-image">
                    <?php else: ?>
                        <div class="game-placeholder-image">
                            <i class="<?= esc($game['category_icon'] ?? 'bi-controller') ?>"></i>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($game['category_name'])): ?>
                        <div class="category-badge" style="color: <?= esc($game['category_color']) ?>">
                            <i class="<?= esc($game['category_icon']) ?>"></i>
                            <?= esc($game['category_name']) ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Description -->
                <div class="game-description-section">
                    <h2 class="section-title">
                        <i class="bi bi-info-circle"></i>
                        Description
                    </h2>
                    <div class="game-description">
                        <?php if (!empty($game['description'])): ?>
                            <?= nl2br(esc($game['description'])) ?>
                        <?php else: ?>
                            <p>Découvrez <strong><?= esc($game['name']) ?></strong>, une expérience unique chez FunLab Tunisie !</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Features -->
                <div class="game-description-section">
                    <h2 class="section-title">
                        <i class="bi bi-star"></i>
                        Points Forts
                    </h2>
                    <div class="features-grid">
                        <div class="feature-item">
                            <i class="bi bi-people feature-icon"></i>
                            <div class="feature-content">
                                <h4>Équipe</h4>
                                <p><?= $game['min_players'] ?>-<?= $game['max_players'] ?> joueurs</p>
                            </div>
                        </div>
                        <div class="feature-item">
                            <i class="bi bi-clock feature-icon"></i>
                            <div class="feature-content">
                                <h4>Durée</h4>
                                <p><?= $game['duration_minutes'] ?> minutes</p>
                            </div>
                        </div>
                        <div class="feature-item">
                            <i class="bi bi-shield-check feature-icon"></i>
                            <div class="feature-content">
                                <h4>Sécurité</h4>
                                <p>Équipement supervisé</p>
                            </div>
                        </div>
                        <div class="feature-item">
                            <i class="bi bi-trophy feature-icon"></i>
                            <div class="feature-content">
                                <h4>Expérience</h4>
                                <p>Tous niveaux</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Booking Info -->
            <div class="col-lg-5">
                <div class="game-info-card">
                    <h1 class="h2 mb-3"><?= esc($game['name']) ?></h1>
                    
                    <div class="game-price">
                        <?= number_format($game['price'], 2) ?> TND
                    </div>
                    <div class="game-price-label">
                        <?php if ($game['price_per_person']): ?>
                            Prix par personne
                        <?php else: ?>
                            Prix par session
                        <?php endif; ?>
                    </div>

                    <!-- Specs -->
                    <div class="game-specs">
                        <div class="spec-item">
                            <div class="spec-icon">
                                <i class="bi bi-clock"></i>
                            </div>
                            <div class="spec-content">
                                <div class="spec-label">Durée</div>
                                <div class="spec-value"><?= $game['duration_minutes'] ?> min</div>
                            </div>
                        </div>
                        <div class="spec-item">
                            <div class="spec-icon">
                                <i class="bi bi-people"></i>
                            </div>
                            <div class="spec-content">
                                <div class="spec-label">Joueurs</div>
                                <div class="spec-value"><?= $game['min_players'] ?>-<?= $game['max_players'] ?></div>
                            </div>
                        </div>
                        <div class="spec-item">
                            <div class="spec-icon">
                                <i class="bi bi-star"></i>
                            </div>
                            <div class="spec-content">
                                <div class="spec-label">Statut</div>
                                <div class="spec-value">Disponible</div>
                            </div>
                        </div>
                        <div class="spec-item">
                            <div class="spec-icon">
                                <i class="bi bi-tag"></i>
                            </div>
                            <div class="spec-content">
                                <div class="spec-label">Catégorie</div>
                                <div class="spec-value"><?= esc($game['category_name'] ?? 'Jeu') ?></div>
                            </div>
                        </div>
                    </div>

                    <!-- Book Button -->
                    <a href="<?= base_url('booking?game=' . $game['id']) ?>" class="btn btn-book-now">
                        <i class="bi bi-calendar-check"></i>
                        Réserver Maintenant
                    </a>

                    <?php if ($game['deposit_required']): ?>
                        <div class="alert-deposit">
                            <i class="bi bi-info-circle"></i>
                            Acompte requis : <?= $game['deposit_percentage'] ?>% du prix total
                        </div>
                    <?php endif; ?>

                    <!-- Share Buttons -->
                    <div class="share-buttons">
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode($shareUrl ?? current_url()) ?>" 
                           target="_blank" 
                           class="share-button share-facebook"
                           title="Partager sur Facebook">
                            <i class="bi bi-facebook"></i>
                        </a>
                        <a href="https://twitter.com/intent/tweet?url=<?= urlencode($shareUrl ?? current_url()) ?>&text=<?= urlencode($shareTitle ?? '') ?>" 
                           target="_blank" 
                           class="share-button share-twitter"
                           title="Partager sur Twitter">
                            <i class="bi bi-twitter"></i>
                        </a>
                        <a href="https://wa.me/?text=<?= urlencode(($shareTitle ?? '') . ' - ' . ($shareUrl ?? current_url())) ?>" 
                           target="_blank" 
                           class="share-button share-whatsapp"
                           title="Partager sur WhatsApp">
                            <i class="bi bi-whatsapp"></i>
                        </a>
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?= urlencode($shareUrl ?? current_url()) ?>" 
                           target="_blank" 
                           class="share-button share-linkedin"
                           title="Partager sur LinkedIn">
                            <i class="bi bi-linkedin"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Reviews Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <!-- Flash Messages -->
                <?php if (session()->getFlashdata('review_success')): ?>
                    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        <?= session()->getFlashdata('review_success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <?php if (session()->getFlashdata('review_error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <?= session()->getFlashdata('review_error') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <!-- Reviews Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="h3 mb-0">
                        <i class="bi bi-star-fill text-warning"></i> Avis des Joueurs
                    </h2>
                    <?php if ($reviewCount > 0): ?>
                        <div class="text-end">
                            <div class="h4 mb-0"><?= $averageRating ?> <small class="text-muted">/5</small></div>
                            <small class="text-muted"><?= $reviewCount ?> avis</small>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Add Review Form -->
                <?php if (!$hasReviewed): ?>
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Laissez votre avis</h5>
                        
                        <?php if (session()->getFlashdata('review_success')): ?>
                            <div class="alert alert-success">
                                <?= session()->getFlashdata('review_success') ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (session()->getFlashdata('review_error')): ?>
                            <div class="alert alert-danger">
                                <?= session()->getFlashdata('review_error') ?>
                            </div>
                        <?php endif; ?>

                        <form action="<?= base_url('games/' . $game['id'] . '/review') ?>" method="POST">
                            <?= csrf_field() ?>
                            
                            <?php if (!session()->get('isLoggedIn')): ?>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nom <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control" required>
                                </div>
                            </div>
                            <?php endif; ?>

                            <div class="mb-3">
                                <label class="form-label">Note <span class="text-danger">*</span></label>
                                <div class="rating-input">
                                    <?php for ($i = 5; $i >= 1; $i--): ?>
                                        <input type="radio" name="rating" value="<?= $i ?>" id="star<?= $i ?>" required>
                                        <label for="star<?= $i ?>"><i class="bi bi-star-fill"></i></label>
                                    <?php endfor; ?>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Commentaire <span class="text-danger">*</span></label>
                                <textarea name="comment" class="form-control" rows="4" required 
                                          placeholder="Partagez votre expérience..."></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-send"></i> Publier l'avis
                            </button>
                            
                            <small class="text-muted d-block mt-2">
                                Votre avis sera publié après modération
                            </small>
                        </form>
                    </div>
                </div>
                <?php else: ?>
                    <div class="alert alert-info mb-4">
                        <i class="bi bi-info-circle"></i> Vous avez déjà laissé un avis pour ce jeu
                    </div>
                <?php endif; ?>

                <!-- Reviews List -->
                <?php if (!empty($reviews)): ?>
                    <div class="reviews-list">
                        <?php foreach ($reviews as $review): ?>
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h6 class="mb-0">
                                            <?= esc($review['username'] ?? $review['name']) ?>
                                        </h6>
                                        <small class="text-muted">
                                            <?= date('d/m/Y', strtotime($review['created_at'])) ?>
                                        </small>
                                    </div>
                                    <div class="rating-display">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <i class="bi bi-star-fill <?= $i <= $review['rating'] ? 'text-warning' : 'text-muted' ?>"></i>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                <p class="mb-0"><?= nl2br(esc($review['comment'])) ?></p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="bi bi-chat-dots" style="font-size: 3rem; color: #ccc;"></i>
                        <p class="text-muted mt-3">Aucun avis pour le moment. Soyez le premier !</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<style>
.rating-input {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
    gap: 5px;
}

.rating-input input[type="radio"] {
    display: none;
}

.rating-input label {
    cursor: pointer;
    font-size: 2rem;
    color: #ddd;
    transition: color 0.2s;
}

.rating-input label:hover,
.rating-input label:hover ~ label,
.rating-input input[type="radio"]:checked ~ label {
    color: #ffc107;
}

.rating-display {
    font-size: 1.2rem;
}
</style>

<?= view('front/layouts/footer') ?>
