<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $metaTitle ?? $title ?? 'FunLab Tunisie - Centre d\'Activités Indoor' ?></title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="<?= $metaDescription ?? 'FunLab Tunisie - Découvrez nos jeux d\'escape game, réalité virtuelle, laser game et arcade. Réservez votre expérience maintenant !' ?>">
    <meta name="keywords" content="<?= $metaKeywords ?? 'funlab, escape game tunisie, réalité virtuelle, laser game, arcade, divertissement, activités indoor' ?>">
    <meta name="author" content="FunLab Tunisie">
    <link rel="canonical" href="<?= $canonicalUrl ?? current_url() ?>">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="<?= $ogType ?? 'website' ?>">
    <meta property="og:url" content="<?= $ogUrl ?? current_url() ?>">
    <meta property="og:title" content="<?= $ogTitle ?? $metaTitle ?? $title ?? 'FunLab Tunisie' ?>">
    <meta property="og:description" content="<?= $ogDescription ?? $metaDescription ?? 'FunLab Tunisie - Centre d\'activités indoor' ?>">
    <meta property="og:image" content="<?= $ogImage ?? base_url('assets/images/og-default.jpg') ?>">
    <meta property="og:site_name" content="FunLab Tunisie">
    <meta property="og:locale" content="fr_TN">
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="<?= $twitterUrl ?? current_url() ?>">
    <meta name="twitter:title" content="<?= $twitterTitle ?? $metaTitle ?? $title ?? 'FunLab Tunisie' ?>">
    <meta name="twitter:description" content="<?= $twitterDescription ?? $metaDescription ?? 'FunLab Tunisie - Centre d\'activités indoor' ?>">
    <meta name="twitter:image" content="<?= $twitterImage ?? $ogImage ?? base_url('assets/images/og-default.jpg') ?>">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    
    <?= $additionalCSS ?? '' ?>
    
    <style>
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 100px 0;
        }
        .game-card {
            transition: transform 0.3s;
            height: 100%;
        }
        .game-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }
        .feature-icon {
            font-size: 3rem;
            color: #667eea;
        }
        <?= $additionalStyles ?? '' ?>
    </style>
</head>
<body>
