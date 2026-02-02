<?php helper('theme'); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Admin' ?> - FunLab</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=<?= urlencode(theme_font('body', 'Roboto')) ?>:wght@300;400;500;700&family=<?= urlencode(theme_font('heading', 'Oswald')) ?>:wght@400;500;600;700&display=swap" rel="stylesheet">
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
        body {
            overflow-x: hidden;
            font-family: var(--font-body);
            color: var(--text-color);
            font-size: var(--font-size-base);
        }
        h1, h2, h3, h4, h5, h6 {
            font-family: var(--font-heading);
        }
        .admin-layout {
            display: flex;
            min-height: 100vh;
        }
        .admin-sidebar {
            width: 250px;
            height: 100vh;
            background: var(--dark-color);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            overflow-y: auto;
            overflow-x: hidden;
            transition: transform 0.3s ease;
        }
        .admin-sidebar::-webkit-scrollbar {
            width: 8px;
        }
        .admin-sidebar::-webkit-scrollbar-track {
            background: rgba(255,255,255,0.05);
        }
        .admin-sidebar::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.2);
            border-radius: 4px;
        }
        .admin-sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255,255,255,0.3);
        }
        .admin-sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            transition: all 0.3s;
        }
        .admin-sidebar .nav-link:hover {
            color: white;
            background: rgba(255,255,255,0.1);
        }
        .admin-sidebar .nav-link.active {
            background: var(--primary-color) !important;
            color: white !important;
        }
        .admin-sidebar .badge {
            font-size: 0.7rem;
            padding: 3px 7px;
            font-weight: 600;
        }
        .admin-content {
            flex: 1;
            margin-left: 250px;
            min-height: 100vh;
            background: var(--light-color);
        }
        .admin-topbar {
            background: white;
            border-bottom: 1px solid #dee2e6;
            padding: 15px 30px;
            position: sticky;
            top: 0;
            z-index: 999;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .sidebar-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--dark-color);
            cursor: pointer;
            padding: 5px 10px;
        }
        .sidebar-toggle:hover {
            color: var(--primary-color);
        }
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 999;
        }
        @media (max-width: 991px) {
            .admin-sidebar {
                transform: translateX(-100%);
            }
            .admin-sidebar.show {
                transform: translateX(0);
            }
            .admin-content {
                margin-left: 0;
            }
            .sidebar-toggle {
                display: block;
            }
            .sidebar-overlay.show {
                display: block;
            }
            .admin-main {
                padding: 20px 15px;
            }
        }
        .admin-main {
            padding: 30px;
        }
        .btn-primary {
            background: var(--primary-color) !important;
            border-color: var(--primary-color) !important;
        }
        .btn-primary:hover {
            background: var(--secondary-color) !important;
            border-color: var(--secondary-color) !important;
        }
        .badge.bg-primary {
            background: var(--primary-color) !important;
        }
        .sidebar-section-title {
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            opacity: 0.7;
        }
        <?= $additionalStyles ?? '' ?>
    </style>
</head>
<body>
    <div class="admin-layout">
