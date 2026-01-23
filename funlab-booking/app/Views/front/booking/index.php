<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réservation - FunLab Tunisie</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="<?= base_url('/') ?>">
                <i class="bi bi-joystick"></i> FunLab Tunisie
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('/') ?>">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="<?= base_url('booking') ?>">Réserver</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('account') ?>">Mon Compte</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <h1 class="text-center mb-4">Réserver votre activité</h1>
        <p class="text-center text-muted mb-5">Consultez la page <a href="<?= base_url('availability-example.html') ?>">démo de disponibilité</a></p>
        
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> 
            Cette page est en cours de développement. Utilisez l'exemple d'intégration pour tester l'API de disponibilité.
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
