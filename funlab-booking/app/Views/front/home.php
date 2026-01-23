<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FunLab Tunisie - Centre d'Activités Indoor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
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
    </style>
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
                        <a class="nav-link active" href="<?= base_url('/') ?>">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('booking') ?>">Réserver</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('account') ?>">Mon Compte</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('admin') ?>">Admin</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section text-center">
        <div class="container">
            <h1 class="display-3 fw-bold mb-4">Bienvenue chez FunLab Tunisie</h1>
            <p class="lead mb-5">Escape Game • Réalité Virtuelle • Laser Game</p>
            <a href="<?= base_url('booking') ?>" class="btn btn-light btn-lg px-5">
                <i class="bi bi-calendar-check"></i> Réserver Maintenant
            </a>
        </div>
    </section>

    <!-- Activités -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">Nos Activités</h2>
            <div class="row g-4">
                <!-- Escape Room -->
                <div class="col-md-4">
                    <div class="card game-card">
                        <div class="card-body text-center p-4">
                            <i class="bi bi-door-closed feature-icon mb-3"></i>
                            <h3 class="card-title">Escape Room</h3>
                            <p class="card-text">
                                Résolvez des énigmes et échappez-vous en équipe. 
                                Plusieurs scénarios disponibles !
                            </p>
                            <ul class="list-unstyled text-start">
                                <li><i class="bi bi-check-circle text-success"></i> 4-8 joueurs</li>
                                <li><i class="bi bi-check-circle text-success"></i> 60 minutes</li>
                                <li><i class="bi bi-check-circle text-success"></i> À partir de 120 DT</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Réalité Virtuelle -->
                <div class="col-md-4">
                    <div class="card game-card">
                        <div class="card-body text-center p-4">
                            <i class="bi bi-headset-vr feature-icon mb-3"></i>
                            <h3 class="card-title">Réalité Virtuelle</h3>
                            <p class="card-text">
                                Plongez dans des mondes immersifs avec nos casques VR dernière génération.
                            </p>
                            <ul class="list-unstyled text-start">
                                <li><i class="bi bi-check-circle text-success"></i> 1-2 joueurs</li>
                                <li><i class="bi bi-check-circle text-success"></i> 30-60 minutes</li>
                                <li><i class="bi bi-check-circle text-success"></i> À partir de 25 DT</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Laser Game -->
                <div class="col-md-4">
                    <div class="card game-card">
                        <div class="card-body text-center p-4">
                            <i class="bi bi-lightning-charge feature-icon mb-3"></i>
                            <h3 class="card-title">Laser Game</h3>
                            <p class="card-text">
                                Affrontez vos amis dans notre arène high-tech. Action garantie !
                            </p>
                            <ul class="list-unstyled text-start">
                                <li><i class="bi bi-check-circle text-success"></i> 4-12 joueurs</li>
                                <li><i class="bi bi-check-circle text-success"></i> 30-60 minutes</li>
                                <li><i class="bi bi-check-circle text-success"></i> À partir de 15 DT</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pourquoi FunLab -->
    <section class="bg-light py-5">
        <div class="container">
            <h2 class="text-center mb-5">Pourquoi Choisir FunLab ?</h2>
            <div class="row g-4">
                <div class="col-md-3 text-center">
                    <i class="bi bi-trophy feature-icon mb-3"></i>
                    <h4>Qualité Premium</h4>
                    <p>Équipements dernière génération</p>
                </div>
                <div class="col-md-3 text-center">
                    <i class="bi bi-shield-check feature-icon mb-3"></i>
                    <h4>Sécurisé</h4>
                    <p>Réservation en ligne sécurisée</p>
                </div>
                <div class="col-md-3 text-center">
                    <i class="bi bi-clock feature-icon mb-3"></i>
                    <h4>Horaires Flexibles</h4>
                    <p>Ouvert de 9h à 22h</p>
                </div>
                <div class="col-md-3 text-center">
                    <i class="bi bi-people feature-icon mb-3"></i>
                    <h4>Pour Tous</h4>
                    <p>Idéal pour familles et groupes</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-5 bg-primary text-white text-center">
        <div class="container">
            <h2 class="mb-4">Prêt pour l'Aventure ?</h2>
            <p class="lead mb-4">Réservez votre créneau dès maintenant et vivez une expérience inoubliable !</p>
            <a href="<?= base_url('booking') ?>" class="btn btn-light btn-lg px-5">
                Réserver Maintenant
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>FunLab Tunisie</h5>
                    <p>Centre d'activités indoor premium</p>
                </div>
                <div class="col-md-4">
                    <h5>Contact</h5>
                    <p>
                        <i class="bi bi-envelope"></i> contact@funlab.tn<br>
                        <i class="bi bi-telephone"></i> +216 70 123 456
                    </p>
                </div>
                <div class="col-md-4">
                    <h5>Horaires</h5>
                    <p>
                        Lundi - Dimanche<br>
                        09:00 - 22:00
                    </p>
                </div>
            </div>
            <hr class="my-3">
            <div class="text-center">
                <p class="mb-0">&copy; 2026 FunLab Tunisie. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
