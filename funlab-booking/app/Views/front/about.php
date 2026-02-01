<?php
$pageTitle = $settings['about_title'] ?? 'À Propos de FunLab Tunisie';
$additionalStyles = '
    .about-section {
        padding: 40px 0;
    }
    .about-card {
        background: white;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        height: 100%;
        transition: transform 0.3s;
    }
    .about-card:hover {
        transform: translateY(-5px);
    }
    .about-icon {
        font-size: 3rem;
        color: #667eea;
        margin-bottom: 20px;
    }
    .value-item {
        background: #f8f9fa;
        padding: 15px 20px;
        border-radius: 10px;
        margin-bottom: 10px;
        border-left: 4px solid #667eea;
    }
';
?>

<?= view('front/layouts/header', compact('title', 'additionalStyles')) ?>
<?= view('front/layouts/navbar', compact('activeMenu')) ?>

<!-- Hero Section -->
<?= view('front/components/hero', [
    'title' => $settings['about_title'] ?? 'À Propos de FunLab',
    'subtitle' => $settings['about_subtitle'] ?? 'Découvrez qui nous sommes',
    'breadcrumbs' => [
        'Accueil' => base_url('/'),
        'À Propos' => null
    ],
    'height' => 'small',
    'background' => !empty($settings['about_hero_image']) ? $settings['about_hero_image'] : 'gradient'
]) ?>

<!-- Introduction -->
<?php if (!empty($settings['about_intro'])): ?>
<section class="about-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <p class="lead"><?= nl2br(esc($settings['about_intro'])) ?></p>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Mission, Vision, Valeurs -->
<section class="about-section bg-light">
    <div class="container">
        <div class="row g-4">
            <!-- Mission -->
            <?php if (!empty($settings['about_mission_title']) || !empty($settings['about_mission_content'])): ?>
            <div class="col-md-4">
                <div class="about-card text-center">
                    <i class="bi bi-bullseye about-icon"></i>
                    <h3><?= esc($settings['about_mission_title'] ?? 'Notre Mission') ?></h3>
                    <p><?= nl2br(esc($settings['about_mission_content'] ?? '')) ?></p>
                </div>
            </div>
            <?php endif; ?>

            <!-- Vision -->
            <?php if (!empty($settings['about_vision_title']) || !empty($settings['about_vision_content'])): ?>
            <div class="col-md-4">
                <div class="about-card text-center">
                    <i class="bi bi-eye about-icon"></i>
                    <h3><?= esc($settings['about_vision_title'] ?? 'Notre Vision') ?></h3>
                    <p><?= nl2br(esc($settings['about_vision_content'] ?? '')) ?></p>
                </div>
            </div>
            <?php endif; ?>

            <!-- Valeurs -->
            <?php if (!empty($settings['about_values_title']) || !empty($settings['about_values_content'])): ?>
            <div class="col-md-4">
                <div class="about-card text-center">
                    <i class="bi bi-star about-icon"></i>
                    <h3><?= esc($settings['about_values_title'] ?? 'Nos Valeurs') ?></h3>
                    <?php 
                    $values = $settings['about_values_content'] ?? '';
                    $valuesList = preg_split('/[,\n]+/', $values);
                    ?>
                    <div class="text-start">
                        <?php foreach ($valuesList as $value): ?>
                            <?php if (trim($value)): ?>
                                <div class="value-item">
                                    <i class="bi bi-check-circle text-success me-2"></i>
                                    <?= esc(trim($value)) ?>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Équipe -->
<?php if (!empty($settings['about_team_title']) || !empty($settings['about_team_content'])): ?>
<section class="about-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h2 class="mb-4"><?= esc($settings['about_team_title'] ?? 'Notre Équipe') ?></h2>
                <p class="lead"><?= nl2br(esc($settings['about_team_content'] ?? '')) ?></p>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Call to Action -->
<section class="about-section" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
    <div class="container text-center">
        <h2 class="mb-4">Prêt à vivre l'expérience FunLab ?</h2>
        <a href="<?= base_url('booking') ?>" class="btn btn-light btn-lg">
            <i class="bi bi-calendar-check"></i> Réserver Maintenant
        </a>
    </div>
</section>

<?= view('front/layouts/footer') ?>
