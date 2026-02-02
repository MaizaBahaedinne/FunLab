<?php
/**
 * Slider Component pour page d'accueil
 * Affiche un carousel Bootstrap avec les slides actives
 */

$slideModel = new \App\Models\SlideModel();
$slides = $slideModel->getActiveSlides();

if (empty($slides)) {
    // Si aucune slide, afficher le hero par défaut
    echo view('front/components/hero', [
        'title' => theme_setting('app_name', 'FunLab Tunisie'),
        'subtitle' => 'Escape Game • Réalité Virtuelle • Laser Game',
        'height' => 'large',
        'ctaButtons' => [
            ['text' => 'Réserver Maintenant', 'url' => base_url('booking'), 'icon' => 'calendar-check', 'style' => 'light']
        ]
    ]);
    return;
}
?>

<div id="homeSlider" class="carousel slide carousel-fade" data-bs-ride="carousel">
    <!-- Indicators -->
    <?php if (count($slides) > 1): ?>
    <div class="carousel-indicators">
        <?php foreach ($slides as $index => $slide): ?>
        <button type="button" data-bs-target="#homeSlider" data-bs-slide-to="<?= $index ?>" 
                <?= $index === 0 ? 'class="active" aria-current="true"' : '' ?> 
                aria-label="Slide <?= $index + 1 ?>"></button>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- Slides -->
    <div class="carousel-inner">
        <?php foreach ($slides as $index => $slide): ?>
        <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>" 
             style="background-image: url('<?= esc($slide['image']) ?>'); 
                    background-size: cover; 
                    background-position: center; 
                    min-height: 700px; 
                    position: relative;">
            
            <!-- Overlay -->
            <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; 
                        background: rgba(0,0,0,0.<?= $slide['overlay_opacity'] ?>);"></div>
            
            <!-- Content -->
            <div class="carousel-caption d-flex align-items-center justify-content-center" 
                 style="top: 0; bottom: 0; left: 0; right: 0; text-align: center;">
                <div class="container">
                    <h1 class="display-2 fw-bold mb-4" 
                        style="color: <?= esc($slide['text_color']) ?>; 
                               text-shadow: 2px 2px 8px rgba(0,0,0,0.5); 
                               animation: fadeInUp 1s ease;">
                        <?= esc($slide['title']) ?>
                    </h1>
                    
                    <?php if ($slide['subtitle']): ?>
                    <p class="lead mb-4" 
                       style="font-size: 1.8rem; 
                              color: <?= esc($slide['text_color']) ?>; 
                              text-shadow: 1px 1px 4px rgba(0,0,0,0.4); 
                              animation: fadeInUp 1s ease 0.2s backwards;">
                        <?= esc($slide['subtitle']) ?>
                    </p>
                    <?php endif; ?>
                    
                    <?php if ($slide['description']): ?>
                    <p class="mb-5" 
                       style="font-size: 1.2rem; 
                              color: <?= esc($slide['text_color']) ?>; 
                              opacity: 0.9; 
                              animation: fadeInUp 1s ease 0.3s backwards;">
                        <?= esc($slide['description']) ?>
                    </p>
                    <?php endif; ?>
                    
                    <?php if ($slide['button_text'] && $slide['button_link']): ?>
                    <div style="animation: fadeInUp 1s ease 0.4s backwards;">
                        <a href="<?= esc($slide['button_link']) ?>" 
                           class="btn btn-<?= esc($slide['button_style']) ?> btn-lg px-5 py-3">
                            <?= esc($slide['button_text']) ?>
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Controls -->
    <?php if (count($slides) > 1): ?>
    <button class="carousel-control-prev" type="button" data-bs-target="#homeSlider" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Précédent</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#homeSlider" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Suivant</span>
    </button>
    <?php endif; ?>
</div>

<!-- Spacing après slider -->
<div class="hero-spacing" style="margin-top: 60px;"></div>

<style>
.carousel-item {
    transition: transform 1.5s ease, opacity 1.5s ease;
}

.carousel-fade .carousel-item {
    opacity: 0;
    transition-duration: 1s;
}

.carousel-fade .carousel-item.active {
    opacity: 1;
}

@media (max-width: 768px) {
    .carousel-item {
        min-height: 500px !important;
    }
    
    .carousel-caption h1 {
        font-size: 2.5rem !important;
    }
    
    .carousel-caption .lead {
        font-size: 1.2rem !important;
    }
}
</style>
