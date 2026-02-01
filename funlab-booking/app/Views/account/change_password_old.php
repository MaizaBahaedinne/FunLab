<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Changer mon mot de passe - FunLab Tunisie</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background: #f8f9fa; }
        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .sidebar {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            padding: 20px;
        }
        .sidebar .nav-link {
            color: #333;
            padding: 12px 15px;
            margin-bottom: 5px;
            border-radius: 8px;
            transition: all 0.3s;
        }
        .sidebar .nav-link:hover {
            background: #f8f9fa;
            color: #667eea;
        }
        .sidebar .nav-link.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .content-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            padding: 30px;
        }
        .user-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .password-requirements {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-top: 15px;
        }
        .password-requirements ul {
            margin: 10px 0 0 0;
            padding-left: 20px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark mb-4">
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
                        <a class="nav-link" href="<?= base_url('booking') ?>">Réserver</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i>
                            <?= session()->get('firstName') ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?= base_url('account') ?>">Mon compte</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('account/bookings') ?>">Mes réservations</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?= base_url('auth/logout') ?>">Déconnexion</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3">
                <div class="sidebar">
                    <div class="text-center mb-4">
                        <?php if (session()->get('avatar')): ?>
                            <img src="<?= session()->get('avatar') ?>" alt="Avatar" class="user-avatar">
                        <?php else: ?>
                            <div class="user-avatar bg-primary d-flex align-items-center justify-content-center mx-auto">
                                <i class="bi bi-person fs-2 text-white"></i>
                            </div>
                        <?php endif; ?>
                        <h5 class="mt-3 mb-0">
                            <?= session()->get('firstName') ?> <?= session()->get('lastName') ?>
                        </h5>
                        <small class="text-muted"><?= session()->get('email') ?></small>
                    </div>

                    <nav class="nav flex-column">
                        <a class="nav-link" href="<?= base_url('account') ?>">
                            <i class="bi bi-speedometer2"></i> Tableau de bord
                        </a>
                        <a class="nav-link" href="<?= base_url('account/profile') ?>">
                            <i class="bi bi-person"></i> Mon profil
                        </a>
                        <a class="nav-link" href="<?= base_url('account/bookings') ?>">
                            <i class="bi bi-calendar-check"></i> Mes réservations
                        </a>
                        <a class="nav-link active" href="<?= base_url('account/password') ?>">
                            <i class="bi bi-key"></i> Mot de passe
                        </a>
                        <hr>
                        <a class="nav-link text-danger" href="<?= base_url('auth/logout') ?>">
                            <i class="bi bi-box-arrow-right"></i> Déconnexion
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Content -->
            <div class="col-lg-9">
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="bi bi-check-circle"></i>
                        <?= session()->getFlashdata('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="bi bi-exclamation-triangle"></i>
                        <?= session()->getFlashdata('error') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('errors')): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <?php foreach (session()->getFlashdata('errors') as $error): ?>
                            <div><?= $error ?></div>
                        <?php endforeach; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="content-card">
                    <h4 class="mb-4"><i class="bi bi-shield-lock"></i> Changer mon mot de passe</h4>

                    <form action="<?= base_url('account/password') ?>" method="POST" id="passwordForm">
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label class="form-label">Mot de passe actuel *</label>
                            <div class="input-group">
                                <input type="password" name="current_password" id="current_password" 
                                       class="form-control" required placeholder="••••••••">
                                <button class="btn btn-outline-secondary" type="button" 
                                        onclick="togglePassword('current_password')">
                                    <i class="bi bi-eye" id="current_password-icon"></i>
                                </button>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="mb-3">
                            <label class="form-label">Nouveau mot de passe *</label>
                            <div class="input-group">
                                <input type="password" name="new_password" id="new_password" 
                                       class="form-control" required minlength="8" placeholder="••••••••">
                                <button class="btn btn-outline-secondary" type="button" 
                                        onclick="togglePassword('new_password')">
                                    <i class="bi bi-eye" id="new_password-icon"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Confirmer le nouveau mot de passe *</label>
                            <div class="input-group">
                                <input type="password" name="confirm_password" id="confirm_password" 
                                       class="form-control" required minlength="8" placeholder="••••••••">
                                <button class="btn btn-outline-secondary" type="button" 
                                        onclick="togglePassword('confirm_password')">
                                    <i class="bi bi-eye" id="confirm_password-icon"></i>
                                </button>
                            </div>
                        </div>

                        <div class="password-requirements">
                            <strong><i class="bi bi-info-circle"></i> Exigences du mot de passe :</strong>
                            <ul>
                                <li>Au moins 8 caractères</li>
                                <li>Recommandé : mélange de lettres majuscules et minuscules</li>
                                <li>Recommandé : ajout de chiffres et symboles</li>
                            </ul>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Changer le mot de passe
                            </button>
                            <a href="<?= base_url('account') ?>" class="btn btn-outline-secondary">
                                Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = document.getElementById(fieldId + '-icon');
            
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        }

        // Validation côté client
        document.getElementById('passwordForm').addEventListener('submit', function(e) {
            const newPassword = document.getElementById('new_password').value;
            const confirm = document.getElementById('confirm_password').value;

            if (newPassword !== confirm) {
                e.preventDefault();
                alert('Les mots de passe ne correspondent pas.');
                return false;
            }

            if (newPassword.length < 8) {
                e.preventDefault();
                alert('Le mot de passe doit contenir au moins 8 caractères.');
                return false;
            }
        });
    </script>
</body>
</html>
