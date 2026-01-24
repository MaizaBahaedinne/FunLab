<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lien expiré - FunLab</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 20px;
        }
        .error-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            text-align: center;
            max-width: 500px;
            margin: 0 auto;
        }
        .error-icon {
            font-size: 80px;
            color: #ffc107;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="error-card">
            <i class="bi bi-clock-history error-icon"></i>
            <h3 class="mt-4 mb-3">Session Terminée</h3>
            <p class="text-muted"><?= esc($message) ?></p>
            <a href="<?= base_url() ?>" class="btn btn-primary mt-3">
                <i class="bi bi-house"></i> Retour à l'accueil
            </a>
        </div>
    </div>
</body>
</html>
