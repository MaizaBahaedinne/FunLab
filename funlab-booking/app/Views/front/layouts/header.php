<?php 
helper('theme');
$theme = get_theme_settings();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $metaTitle ?? $title ?? theme_setting('app_name', 'FunLab Tunisie') . ' - Centre d\'Activités Indoor' ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= theme_setting('site_favicon', '/assets/images/favicon.ico') ?>">
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="<?= $metaDescription ?? 'FunLab Tunisie - Découvrez nos jeux d\'escape game, réalité virtuelle, laser game et arcade. Réservez votre expérience maintenant !' ?>">
    <meta name="keywords" content="<?= $metaKeywords ?? 'funlab, escape game tunisie, réalité virtuelle, laser game, arcade, divertissement, activités indoor' ?>">
    <meta name="author" content="<?= theme_setting('app_name', 'FunLab Tunisie') ?>">
    <link rel="canonical" href="<?= $canonicalUrl ?? current_url() ?>">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="<?= $ogType ?? 'website' ?>">
    <meta property="og:url" content="<?= $ogUrl ?? current_url() ?>">
    <meta property="og:title" content="<?= $ogTitle ?? $metaTitle ?? $title ?? theme_setting('app_name', 'FunLab Tunisie') ?>">
    <meta property="og:description" content="<?= $ogDescription ?? $metaDescription ?? 'FunLab Tunisie - Centre d\'activités indoor' ?>">
    <meta property="og:image" content="<?= $ogImage ?? base_url('assets/images/og-default.jpg') ?>">
    <meta property="og:site_name" content="<?= theme_setting('app_name', 'FunLab Tunisie') ?>">
    <meta property="og:locale" content="fr_TN">
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="<?= $twitterUrl ?? current_url() ?>">
    <meta name="twitter:title" content="<?= $twitterTitle ?? $metaTitle ?? $title ?? theme_setting('app_name', 'FunLab Tunisie') ?>">
    <meta name="twitter:description" content="<?= $twitterDescription ?? $metaDescription ?? 'FunLab Tunisie - Centre d\'activités indoor' ?>">
    <meta name="twitter:image" content="<?= $twitterImage ?? $ogImage ?? base_url('assets/images/og-default.jpg') ?>">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=<?= urlencode(theme_font('body', 'Roboto')) ?>:wght@300;400;500;700;900&family=<?= urlencode(theme_font('heading', 'Oswald')) ?>:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    
    <?= $additionalCSS ?? '' ?>
    
    <style>
        :root {
            --primary-color: <?= theme_color('primary', '#ff6b35') ?>;
            --secondary-color: <?= theme_color('secondary', '#004e89') ?>;
            --dark-color: <?= theme_color('dark', '#1a1a1a') ?>;
            --light-color: <?= theme_color('light', '#f7f7f7') ?>;
            --text-color: <?= theme_color('text', '#333333') ?>;
            --link-color: <?= theme_color('link', '#ff6b35') ?>;
            --font-heading: '<?= theme_font('heading', 'Oswald') ?>', sans-serif;
            --font-body: '<?= theme_font('body', 'Roboto') ?>', sans-serif;
            --font-size-base: <?= theme_setting('font_size_base', '16') ?>px;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: var(--font-body);
            color: var(--text-color);
            background: #ffffff;
            line-height: 1.6;
            font-size: var(--font-size-base);
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-family: var(--font-heading);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        a {
            color: var(--link-color);
            text-decoration: none;
            transition: all 0.3s;
        }
        
        a:hover {
            color: var(--primary-color);
            opacity: 0.8;
        }
        
        /* Top Info Bar */
        .top-bar {
            background: var(--dark-color);
            color: white;
            padding: 10px 0;
            font-size: 14px;
        }
        
        .top-bar a {
            color: white;
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .top-bar a:hover {
            color: var(--primary-color);
        }
        
        /* Main Header */
        .main-header {
            <?= theme_setting('header_sticky', '1') === '1' ? 'position: sticky; top: 0;' : '' ?>
            z-index: 1000;
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        
        .main-header.scrolled {
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        }
        
        body {
            padding-top: 0;
        }
        
        .navbar-brand-logo {
            display: flex;
            align-items: center;
            gap: 15px;
            text-decoration: none;
        }
        
        .navbar-brand-logo img {
            max-height: 60px;
            transition: transform 0.3s ease;
        }
        
        .navbar-brand-logo:hover img {
            transform: scale(1.05);
        }
        
        .brand-text {
            font-family: 'Oswald', sans-serif;
            font-size: 28px;
            font-weight: 700;
            color: var(--secondary-color);
            letter-spacing: 2px;
        }
        
        /* Navigation */
        .main-nav {
            padding: 20px 0;
        }
        
        .main-nav .navbar-nav {
            gap: 5px;
        }
        
        .nav-link-modern {
            font-family: 'Oswald', sans-serif;
            font-size: 16px;
            font-weight: 500;
            color: var(--text-color) !important;
            padding: 10px 20px !important;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .nav-link-modern::after {
            content: '';
            position: absolute;
            bottom: 5px;
            left: 20px;
            right: 20px;
            height: 2px;
            background: var(--primary-color);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }
        
        .nav-link-modern:hover::after,
        .nav-link-modern.active::after {
            transform: scaleX(1);
        }
        
        .nav-link-modern:hover {
            color: var(--primary-color) !important;
        }
        
        .nav-link-modern.active {
            color: var(--primary-color) !important;
        }
        
        /* Book Now Button */
        .btn-book-now {
            background: var(--primary-color);
            color: white;
            padding: 12px 30px;
            border-radius: 0;
            font-family: 'Oswald', sans-serif;
            font-size: 16px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            border: 2px solid var(--primary-color);
            transition: all 0.3s ease;
        }
        
        .btn-book-now:hover {
            background: transparent;
            color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 107, 53, 0.3);
        }
        
        /* Dropdown */
        .dropdown-menu-modern {
            border: none;
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
            border-radius: 0;
            padding: 10px 0;
            margin-top: 0;
        }
        
        .dropdown-item-modern {
            padding: 10px 25px;
            font-size: 15px;
            transition: all 0.3s ease;
        }
        
        .dropdown-item-modern:hover {
            background: var(--light-color);
            color: var(--primary-color);
            padding-left: 30px;
        }
        
        /* Cards */
        .activity-card {
            transition: all 0.4s ease;
            border: none;
            border-radius: 0;
            overflow: hidden;
            background: white;
            box-shadow: 0 3px 15px rgba(0,0,0,0.1);
        }
        
        .activity-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        
        .activity-card img {
            transition: transform 0.5s ease;
        }
        
        .activity-card:hover img {
            transform: scale(1.1);
        }
        
        /* Buttons */
        .btn-primary-modern {
            background: var(--primary-color);
            color: white;
            padding: 12px 35px;
            border: 2px solid var(--primary-color);
            border-radius: 0;
            font-family: 'Oswald', sans-serif;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }
        
        .btn-primary-modern:hover {
            background: transparent;
            color: var(--primary-color);
            transform: translateY(-2px);
        }
        
        .btn-secondary-modern {
            background: var(--secondary-color);
            color: white;
            padding: 12px 35px;
            border: 2px solid var(--secondary-color);
            border-radius: 0;
            font-family: 'Oswald', sans-serif;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }
        
        .btn-secondary-modern:hover {
            background: transparent;
            color: var(--secondary-color);
        }
        
        /* Section Headers */
        .section-title {
            font-family: 'Oswald', sans-serif;
            font-size: 42px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 10px;
            position: relative;
            display: inline-block;
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 80px;
            height: 3px;
            background: var(--primary-color);
        }
        
        /* Animations */
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
        
        .animate-on-scroll {
            animation: fadeInUp 0.6s ease forwards;
        }
        
        /* Home Hero Section */
        .home-hero {
            position: relative;
            min-height: 700px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            overflow: hidden;
        }
        
        .home-hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23ffffff" fill-opacity="0.05" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,144C960,149,1056,139,1152,122.7C1248,107,1344,85,1392,74.7L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') no-repeat bottom;
            background-size: cover;
            opacity: 0.3;
        }
        
        .home-hero-content {
            position: relative;
            z-index: 2;
            padding: 100px 0;
        }
        
        .home-hero h1 {
            font-size: 4rem;
            color: white;
            text-shadow: 2px 2px 8px rgba(0,0,0,0.3);
            animation: fadeInUp 1s ease;
        }
        
        .home-hero .lead {
            font-size: 1.8rem;
            color: rgba(255,255,255,0.95);
            text-shadow: 1px 1px 4px rgba(0,0,0,0.2);
            animation: fadeInUp 1s ease 0.2s backwards;
        }
        
        .home-hero .cta-buttons {
            animation: fadeInUp 1s ease 0.4s backwards;
        }
        
        @media (max-width: 768px) {
            .home-hero {
                min-height: 500px;
            }
            .home-hero h1 {
                font-size: 2.5rem;
            }
            .home-hero .lead {
                font-size: 1.2rem;
            }
        }
        
        <?= $additionalStyles ?? '' ?>
    </style>
</head>
<body>
