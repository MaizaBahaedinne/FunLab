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
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&family=Oswald:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    
    <?= $additionalCSS ?? '' ?>
    
    <style>
        :root {
            --primary-color: #ff6b35;
            --secondary-color: #004e89;
            --dark-color: #1a1a1a;
            --light-color: #f7f7f7;
            --text-color: #333333;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Roboto', sans-serif;
            color: var(--text-color);
            background: #ffffff;
            line-height: 1.6;
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Oswald', sans-serif;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
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
            position: sticky;
            top: 0;
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
        
        <?= $additionalStyles ?? '' ?>
    </style>
</head>
<body>
