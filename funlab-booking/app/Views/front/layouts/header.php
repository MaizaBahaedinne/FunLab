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
            background: var(--primary-color) !important;
            color: white !important;
            padding: 12px 30px;
            border-radius: 8px;
            font-family: 'Oswald', sans-serif;
            font-size: 16px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            border: 2px solid var(--primary-color) !important;
            transition: all 0.3s ease;
        }
        
        .btn-book-now:hover {
            background: var(--secondary-color) !important;
            border-color: var(--secondary-color) !important;
            color: white !important;
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }
        
        /* Dropdown */
        .dropdown-menu-modern {
            border: none;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            border-radius: 15px;
            padding: 15px;
            margin-top: 10px;
            background: white;
            backdrop-filter: blur(10px);
            min-width: 280px;
            animation: dropdownFadeIn 0.3s ease;
        }
        
        @keyframes dropdownFadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .dropdown-item-modern {
            padding: 12px 20px;
            font-size: 15px;
            font-family: var(--font-body);
            font-weight: 500;
            color: var(--text-color);
            border-radius: 10px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            margin-bottom: 5px;
        }
        
        .dropdown-item-modern:last-child {
            margin-bottom: 0;
        }
        
        .dropdown-item-modern i {
            color: var(--primary-color);
            transition: transform 0.3s ease;
        }
        
        .dropdown-item-modern:hover {
            background: linear-gradient(to right, rgba(var(--primary-color-rgb, 255, 107, 53), 0.1), transparent);
            color: var(--primary-color);
            transform: translateX(5px);
        }
        
        .dropdown-item-modern:hover i {
            transform: scale(1.2);
        }
        
        .dropdown-divider {
            margin: 10px 0;
            border-top-color: rgba(0,0,0,0.05);
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
        .btn-primary, .btn-primary-modern {
            background: var(--primary-color) !important;
            border-color: var(--primary-color) !important;
            color: white !important;
            padding: 12px 35px;
            border-radius: 0;
            font-family: var(--font-heading);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover, .btn-primary-modern:hover {
            background: transparent !important;
            color: var(--primary-color) !important;
            transform: translateY(-2px);
        }
        
        .btn-secondary, .btn-secondary-modern {
            background: var(--secondary-color) !important;
            border-color: var(--secondary-color) !important;
            color: white !important;
            padding: 12px 35px;
            border-radius: 0;
            font-family: var(--font-heading);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }
        
        .btn-secondary:hover, .btn-secondary-modern:hover {
            background: transparent !important;
            color: var(--secondary-color) !important;
        }
        
        .btn-outline-primary {
            background: transparent !important;
            border-color: var(--primary-color) !important;
            color: var(--primary-color) !important;
        }
        
        .btn-outline-primary:hover {
            background: var(--primary-color) !important;
            color: white !important;
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
            background: var(--primary-color);
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
        
        /* Modern Review Cards */
        .review-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
        }
        
        .review-card::before {
            content: '"';
            position: absolute;
            top: -10px;
            left: 15px;
            font-size: 80px;
            color: var(--primary-color);
            opacity: 0.1;
            font-family: Georgia, serif;
        }
        
        .review-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }
        
        .review-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            gap: 15px;
        }
        
        .review-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            font-weight: 700;
            flex-shrink: 0;
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        }
        
        .review-info {
            flex: 1;
        }
        
        .review-name {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 5px;
        }
        
        .review-rating {
            font-size: 16px;
        }
        
        .review-rating .bi-star-fill {
            color: #e0e0e0;
        }
        
        .review-rating .bi-star-fill.active {
            color: #ffc107;
        }
        
        .review-comment {
            font-size: 0.95rem;
            color: #555;
            line-height: 1.7;
            margin-bottom: 20px;
            flex: 1;
            font-style: italic;
        }
        
        .review-footer {
            padding-top: 15px;
            border-top: 1px solid #f0f0f0;
        }
        
        .review-date {
            color: #999;
            font-size: 0.85rem;
        }
        
        /* Stat Boxes */
        .stat-box {
            background: white;
            border-radius: 15px;
            padding: 30px 20px;
            transition: all 0.3s ease;
            box-shadow: 0 3px 15px rgba(0,0,0,0.08);
        }
        
        .stat-box:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }
        
        .stat-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            background: var(--primary-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2.5rem;
        }
        
        .stat-number {
            font-size: 3rem;
            font-weight: 700;
            color: var(--primary-color);
            margin: 15px 0 10px;
            font-family: var(--font-heading);
        }
        
        .stat-label {
            color: #6c757d;
            font-size: 1rem;
            margin: 0;
        }
        
        /* Feature Boxes */
        .feature-box {
            text-align: center;
            padding: 30px 20px;
            background: white;
            border-radius: 15px;
            transition: all 0.3s ease;
            height: 100%;
            box-shadow: 0 3px 15px rgba(0,0,0,0.08);
        }
        
        .feature-box:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }
        
        .feature-icon-circle {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            background: rgba(var(--primary-color-rgb, 255, 107, 53), 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
            font-size: 2rem;
            transition: all 0.3s ease;
        }
        
        .feature-box:hover .feature-icon-circle {
            background: var(--primary-color);
            color: white;
            transform: scale(1.1);
        }
        
        .feature-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 10px;
            font-family: var(--font-heading);
        }
        
        .feature-description {
            color: #6c757d;
            font-size: 0.95rem;
            margin: 0;
        }
        
        /* CTA Section */
        .cta-section {
            background: var(--primary-color);
        }
        
        /* Newsletter */
        .newsletter-box {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 5px 30px rgba(0,0,0,0.1);
        }
        
        .newsletter-icon {
            width: 80px;
            height: 80px;
            background: rgba(var(--primary-color-rgb, 255, 107, 53), 0.1);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
            font-size: 2.5rem;
            margin-bottom: 20px;
        }
        
        .newsletter-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 10px;
            font-family: var(--font-heading);
        }
        
        .newsletter-subtitle {
            color: #6c757d;
            font-size: 1rem;
            margin: 0;
        }
        
        .newsletter-form .input-group {
            box-shadow: 0 3px 15px rgba(0,0,0,0.1);
            border-radius: 10px;
            overflow: hidden;
        }
        
        .newsletter-form .form-control {
            border: none;
            padding: 15px 20px;
            font-size: 1rem;
        }
        
        .newsletter-form .form-control:focus {
            box-shadow: none;
        }
        
        .newsletter-form .btn {
            padding: 15px 30px;
            font-weight: 600;
            border: none;
        }
        
        <?= $additionalStyles ?? '' ?>
    </style>
</head>
<body>
