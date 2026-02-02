<?php
/**
 * Hero Component - Section bannière unifiée pour toutes les pages
 * 
 * @param string $title - Titre principal
 * @param string $subtitle - Sous-titre (optionnel)
 * @param array $breadcrumbs - Fil d'Ariane ['Accueil' => '/', 'Page' => null]
 * @param string $background - Image de fond ou 'gradient' (défaut: gradient)
 * @param string $height - Hauteur: 'small', 'medium', 'large' (défaut: medium)
 * @param string $overlay - Opacité overlay: '0' à '9' (défaut: 6)
 */

$title = $title ?? 'FunLab Tunisie';
$subtitle = $subtitle ?? null;
$breadcrumbs = $breadcrumbs ?? [];
$background = $background ?? 'gradient';
$height = $height ?? 'medium';
$overlay = $overlay ?? '6';
$textAlign = $textAlign ?? 'center';

// Heights
$heights = [
    'small' => '250px',
    'medium' => '400px',
    'large' => '600px',
    'full' => '100vh'
];

$heroHeight = $heights[$height] ?? $heights['medium'];

// Background style
if ($background === 'gradient') {
    $bgStyle = 'background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);';
} else {
    $bgStyle = "background-image: url('" . esc($background) . "'); background-size: cover; background-position: center;";
}
?>

<section class="hero-section" style="<?= $bgStyle ?> min-height: <?= $heroHeight ?>; position: relative;">
    <div class="hero-overlay" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.<?= $overlay ?>);"></div>
    
    <div class="container h-100">
        <div class="row h-100 align-items-center">
            <div class="col-12 text-<?= $textAlign ?>" style="position: relative; z-index: 2;">
                <!-- Breadcrumbs -->
                <?php if (!empty($breadcrumbs)): ?>
                <nav aria-label="breadcrumb" class="mb-3">
                    <ol class="breadcrumb justify-content-<?= $textAlign ?> mb-0">
                        <?php foreach ($breadcrumbs as $label => $url): ?>
                            <?php if ($url === null): ?>
                                <li class="breadcrumb-item active text-white" aria-current="page"><?= esc($label) ?></li>
                            <?php else: ?>
                                <li class="breadcrumb-item">
                                    <a href="<?= esc($url) ?>" class="text-white text-decoration-none opacity-75">
                                        <?= esc($label) ?>
                                    </a>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ol>
                </nav>
                <?php endif; ?>
                
                <!-- Title -->
                <h1 class="display-3 fw-bold text-white mb-3" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.3);">
                    <?= esc($title) ?>
                </h1>
                
                <!-- Subtitle -->
                <?php if ($subtitle): ?>
                <p class="lead text-white mb-4" style="font-size: 1.5rem; text-shadow: 1px 1px 2px rgba(0,0,0,0.3);">
                    <?= esc($subtitle) ?>
                </p>
                <?php endif; ?>
                
                <!-- CTA Buttons (optionnel) -->
                <?php if (isset($ctaButtons) && !empty($ctaButtons)): ?>
                <div class="hero-cta mt-4">
                    <?php foreach ($ctaButtons as $button): ?>
                    <a href="<?= esc($button['url']) ?>" 
                       class="btn btn-<?= esc($button['style'] ?? 'primary') ?> btn-lg me-2 mb-2">
                        <?php if (isset($button['icon'])): ?>
                        <i class="bi bi-<?= esc($button['icon']) ?> me-2"></i>
                        <?php endif; ?>
                        <?= esc($button['text']) ?>
                    </a>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Spacing après hero -->
<div class="hero-spacing" style="margin-top: 60px;"></div>

<style>
.hero-section {
    display: flex;
    align-items: center;
}

.breadcrumb {
    background: transparent;
    padding: 0;
}

.breadcrumb-item + .breadcrumb-item::before {
    content: "›";
    color: white;
    font-size: 1.2rem;
}

@media (max-width: 768px) {
    .hero-section {
        min-height: 300px !important;
    }
    
    .hero-section h1 {
        font-size: 2rem !important;
    }
    
    .hero-section .lead {
        font-size: 1.1rem !important;
    }
}
</style>
