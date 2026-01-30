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
    <?= $additionalCSS ?? '' ?>
    <style>
        body {
            overflow-x: hidden;
        }
        .admin-layout {
            display: flex;
            min-height: 100vh;
        }
        .admin-sidebar {
            width: 250px;
            min-height: 100vh;
            background: #2c3e50;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            overflow-y: auto;
        }
        .admin-content {
            flex: 1;
            margin-left: 250px;
            min-height: 100vh;
            background: #f8f9fa;
        }
        .admin-topbar {
            background: white;
            border-bottom: 1px solid #dee2e6;
            padding: 15px 30px;
            position: sticky;
            top: 0;
            z-index: 999;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .admin-main {
            padding: 30px;
        }
        <?= $additionalStyles ?? '' ?>
    </style>
</head>
<body>
    <div class="admin-layout">
