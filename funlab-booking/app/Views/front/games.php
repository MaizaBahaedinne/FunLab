<?php
$additionalStyles = <<<CSS
<style>
.category-section {
    margin-bottom: 60px;
}

.category-header {
    display: flex;
    align-items: center;
    margin-bottom: 30px;
    padding-bottom: 15px;
    border-bottom: 3px solid;
}

.category-icon {
    font-size: 2.5rem;
    margin-right: 15px;
}

.game-card {
    border: none;
    border-radius: 15px;
    overflow: hidden;
    transition: all 0.3s ease;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    height: 100%;
}

.game-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 12px 20px rgba(0,0,0,0.2);
}

.game-card-image {
    height: 200px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 4rem;
}

.game-card-body {
    padding: 20px;
}

.game-title {
    font-size: 1.3rem;
    font-weight: 700;
    margin-bottom: 10px;
    color: #2d3748;
}

.game-description {
    color: #718096;
    font-size: 0.95rem;
    margin-bottom: 15px;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.game-details {
    display: flex;
    gap: 15px;
    margin-bottom: 15px;
    flex-wrap: wrap;
}

.detail-badge {
    display: flex;
    align-items: center;
    gap: 5px;
    padding: 5px 12px;
    background: #f7fafc;
    border-radius: 20px;
    font-size: 0.85rem;
    color: #4a5568;
}

.detail-badge i {
    color: #667eea;
}

.game-price {
    font-size: 1.5rem;
    font-weight: 700;
    color: #667eea;
    margin-bottom: 15px;
}

.btn-book-game {
    width: 100%;
    padding: 12px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    color: white;
    font-weight: 600;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.btn-book-game:hover {
    transform: scale(1.02);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

.no-games {
    text-align: center;
    padding: 60px 20px;
    color: #a0aec0;
}

.no-games i {
    font-size: 5rem;
    margin-bottom: 20px;
}
</style>
CSS;
?>

<?= view('front/layouts/header', compact('title', 'additionalStyles')) ?>
<?= view('front/layouts/navbar', compact('activeMenu')) ?>

<!-- Hero Section -->
<?= view('front/components/hero', [
    'title' => 'Nos Jeux',
    'subtitle' => 'Découvrez nos activités',
    'breadcrumbs' => [
        'Accueil' => base_url('/'),
        'Activités' => null
    ],
    'height' => 'small'
]) ?>
        <p class="lead mb-0">Découvrez notre collection de jeux exceptionnels</p>
    </div>
</section>

<!-- Games by Category -->
<section class="py-5">
    <div class="container">
        <?php if (empty($gamesByCategory)): ?>
            <div class="no-games">
                <i class="bi bi-inbox"></i>
                <h3>Aucun jeu disponible</h3>
                <p>Nos jeux seront bientôt disponibles. Revenez plus tard !</p>
            </div>
        <?php else: ?>
            <?php foreach ($gamesByCategory as $categoryName => $categoryData): ?>
                <div class="category-section">
                    <div class="category-header" style="border-color: <?= esc($categoryData['category']['color']) ?>">
                        <i class="category-icon <?= esc($categoryData['category']['icon']) ?>" 
                           style="color: <?= esc($categoryData['category']['color']) ?>"></i>
                        <h2 class="mb-0"><?= esc($categoryName) ?></h2>
                    </div>

                    <div class="row g-4">
                        <?php foreach ($categoryData['games'] as $game): ?>
                            <div class="col-md-6 col-lg-4">
                                <div class="game-card">
                                    <?php if (!empty($game['image'])): ?>
                                        <img src="<?= base_url('uploads/games/' . esc($game['image'])) ?>" 
                                             class="card-img-top" 
                                             alt="<?= esc($game['name']) ?>"
                                             style="height: 200px; object-fit: cover;">
                                    <?php else: ?>
                                        <div class="game-card-image">
                                            <i class="<?= esc($categoryData['category']['icon']) ?>"></i>
                                        </div>
                                    <?php endif; ?>

                                    <div class="game-card-body">
                                        <h3 class="game-title"><?= esc($game['name']) ?></h3>
                                        
                                        <?php if (!empty($game['description'])): ?>
                                            <p class="game-description"><?= esc($game['description']) ?></p>
                                        <?php endif; ?>

                                        <div class="game-details">
                                            <span class="detail-badge">
                                                <i class="bi bi-clock"></i>
                                                <?= $game['duration_minutes'] ?> min
                                            </span>
                                            <span class="detail-badge">
                                                <i class="bi bi-people"></i>
                                                <?= $game['min_players'] ?>-<?= $game['max_players'] ?> joueurs
                                            </span>
                                        </div>

                                        <div class="game-price">
                                            <?= number_format($game['price'], 2) ?> TND
                                            <?php if ($game['price_per_person']): ?>
                                                <small class="text-muted">/personne</small>
                                            <?php endif; ?>
                                        </div>

                                        <a href="<?= base_url('booking?game=' . $game['id']) ?>" 
                                           class="btn btn-book-game">
                                            <i class="bi bi-calendar-check"></i> Réserver Maintenant
                                        </a>
                                        
                                        <a href="<?= base_url('games/' . $game['id']) ?>" 
                                           class="btn btn-outline-primary w-100 mt-2">
                                            <i class="bi bi-eye"></i> Voir Détails
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>

<?= view('front/layouts/footer') ?>
