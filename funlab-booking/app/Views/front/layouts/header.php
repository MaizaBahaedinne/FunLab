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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    
    <?= $additionalCSS ?? '' ?>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: #f8f9fa;
        }
        
        /* Header Fixed Moderne */
        .modern-navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            padding: 12px 0;
        }
        
        .modern-navbar.scrolled {
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.15);
            padding: 8px 0;
        }
        
        body {
            padding-top: 80px;
        }
        
        .navbar-brand-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            transition: transform 0.3s ease;
        }
        
        .navbar-brand-logo:hover {
            transform: scale(1.05);
        }
        
        .navbar-brand-logo img {
            transition: transform 0.3s ease;
        }
        
        .brand-text {
            font-size: 24px;
            font-weight: 700;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .nav-link-modern {
            color: #333 !important;
            font-weight: 500;
            padding: 10px 18px !important;
            border-radius: 8px;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .nav-link-modern:hover {
            background: linear-gradient(135deg, #f5f7fa 0%, #e8ebf2 100%);
            color: #667eea !important;
            transform: translateY(-2px);
        }
        
        .nav-link-modern.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white !important;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }
        
        .nav-link-modern i {
            margin-right: 6px;
        }
        
        .btn-reserve-modern {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 10px 25px;
            border-radius: 50px;
            font-weight: 600;
            border: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }
        
        .btn-reserve-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5);
            color: white;
        }
        
        .dropdown-menu-modern {
            border: none;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
            border-radius: 12px;
            padding: 8px;
            margin-top: 8px;
        }
        
        .dropdown-item-modern {
            border-radius: 8px;
            padding: 10px 15px;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        
        .dropdown-item-modern:hover {
            background: linear-gradient(135deg, #f5f7fa 0%, #e8ebf2 100%);
            color: #667eea;
            transform: translateX(5px);
        }
        
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 120px 0 80px;
            position: relative;
            overflow: hidden;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="rgba(255,255,255,0.1)" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,112C672,96,768,96,864,112C960,128,1056,160,1152,160C1248,160,1344,128,1392,112L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') no-repeat bottom;
            background-size: cover;
            opacity: 0.3;
        }
        
        .game-card {
            transition: all 0.4s ease;
            height: 100%;
            border: none;
            border-radius: 16px;
            overflow: hidden;
            background: white;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }
        
        .game-card:hover {
            transform: translateY(-15px);
            box-shadow: 0 15px 40px rgba(102, 126, 234, 0.25);
        }
        
        .game-card img {
            transition: transform 0.4s ease;
        }
        
        .game-card:hover img {
            transform: scale(1.1);
        }
        
        .feature-icon {
            font-size: 3.5rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            display: inline-block;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        .btn-modern {
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            border: none;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .btn-primary-modern {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }
        
        .btn-primary-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5);
            color: white;
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
