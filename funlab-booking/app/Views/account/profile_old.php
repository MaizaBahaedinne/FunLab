<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon profil - FunLab Tunisie</title>
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
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .badge-provider {
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: 500;
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
                                <i class="bi bi-person fs-1 text-white"></i>
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
                        <a class="nav-link active" href="<?= base_url('account/profile') ?>">
                            <i class="bi bi-person"></i> Mon profil
                        </a>
                        <a class="nav-link" href="<?= base_url('account/bookings') ?>">
                            <i class="bi bi-calendar-check"></i> Mes réservations
                        </a>
                        <a class="nav-link" href="<?= base_url('account/password') ?>">
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
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4><i class="bi bi-person-badge"></i> Informations personnelles</h4>
                        <?php 
                        $provider = $user['auth_provider'] ?? 'native';
                        $providerColors = [
                            'native' => 'primary',
                            'google' => 'danger',
                            'facebook' => 'primary'
                        ];
                        $providerIcons = [
                            'native' => 'envelope',
                            'google' => 'google',
                            'facebook' => 'facebook'
                        ];
                        $color = $providerColors[$provider] ?? 'secondary';
                        $icon = $providerIcons[$provider] ?? 'person';
                        ?>
                        <span class="badge badge-provider bg-<?= $color ?>">
                            <i class="bi bi-<?= $icon ?>"></i>
                            <?= ucfirst($provider) ?>
                        </span>
                    </div>

                    <form action="<?= base_url('account/profile') ?>" method="POST">
                        <?= csrf_field() ?>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Prénom *</label>
                                <input type="text" name="first_name" class="form-control" required 
                                       value="<?= esc($user['first_name']) ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nom *</label>
                                <input type="text" name="last_name" class="form-control" required 
                                       value="<?= esc($user['last_name']) ?>">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" value="<?= esc($user['email']) ?>" disabled>
                            <small class="text-muted">L'email ne peut pas être modifié</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Téléphone</label>
                            <input type="tel" name="phone" class="form-control" 
                                   value="<?= esc($user['phone'] ?? '') ?>" placeholder="+216 20 123 456">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nom d'utilisateur</label>
                            <input type="text" class="form-control" value="<?= esc($user['username']) ?>" disabled>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Rôle</label>
                                <input type="text" class="form-control" 
                                       value="<?= ucfirst($user['role']) ?>" disabled>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Membre depuis</label>
                                <input type="text" class="form-control" 
                                       value="<?= date('d/m/Y', strtotime($user['created_at'])) ?>" disabled>
                            </div>
                        </div>

                        <?php if (!empty($user['last_login'])): ?>
                            <div class="mb-4">
                                <label class="form-label">Dernière connexion</label>
                                <input type="text" class="form-control" 
                                       value="<?= date('d/m/Y à H:i', strtotime($user['last_login'])) ?>" disabled>
                            </div>
                        <?php endif; ?>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Enregistrer les modifications
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
</body>
</html>
