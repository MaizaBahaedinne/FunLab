<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer une Salle - FunLab Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            transition: all 0.3s;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background: rgba(255,255,255,0.1);
        }
        .form-section {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .form-section h5 {
            color: #667eea;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 sidebar">
                <div class="text-center py-4">
                    <h4 class="text-white"><i class="bi bi-joystick"></i> FunLab Admin</h4>
                </div>
                <nav class="nav flex-column">
                    <a class="nav-link" href="<?= base_url('admin/dashboard') ?>">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                    <a class="nav-link active" href="<?= base_url('admin/rooms') ?>">
                        <i class="bi bi-door-open"></i> Salles
                    </a>
                    <a class="nav-link" href="<?= base_url('admin/games') ?>">
                        <i class="bi bi-controller"></i> Jeux
                    </a>
                    <a class="nav-link" href="<?= base_url('admin/bookings') ?>">
                        <i class="bi bi-calendar-check"></i> Réservations
                    </a>
                    <a class="nav-link" href="<?= base_url('admin/closures') ?>">
                        <i class="bi bi-calendar-x"></i> Fermetures
                    </a>
                    <a class="nav-link" href="<?= base_url('admin/scanner') ?>">
                        <i class="bi bi-qr-code-scan"></i> Scanner QR
                    </a>
                    <a class="nav-link" href="<?= base_url('admin/settings') ?>">
                        <i class="bi bi-gear"></i> Paramètres
                    </a>
                    <hr class="bg-white">
                    <a class="nav-link" href="<?= base_url('auth/logout') ?>">
                        <i class="bi bi-box-arrow-right"></i> Déconnexion
                    </a>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="bi bi-door-open"></i> Créer une nouvelle salle</h2>
                    <a href="<?= base_url('admin/rooms') ?>" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Retour à la liste
                    </a>
                </div>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="bi bi-exclamation-triangle"></i>
                        <?= session()->getFlashdata('error') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (isset($validation)): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="bi bi-exclamation-triangle"></i>
                        <strong>Erreurs de validation :</strong>
                        <ul class="mb-0 mt-2">
                            <?php foreach ($validation->getErrors() as $error): ?>
                                <li><?= esc($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('admin/rooms/store') ?>" method="post">
                    <?= csrf_field() ?>

                    <!-- Informations générales -->
                    <div class="form-section">
                        <h5><i class="bi bi-info-circle"></i> Informations générales</h5>
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label for="name" class="form-label">Nom de la salle <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" 
                                       value="<?= old('name') ?>" required 
                                       placeholder="Ex: Salle VR 1, Escape Room A, Laser Game Arena">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="capacity" class="form-label">Capacité <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="capacity" name="capacity" 
                                       value="<?= old('capacity', 6) ?>" min="1" max="100" required>
                                <div class="form-text">Nombre maximum de personnes</div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="4"><?= old('description') ?></textarea>
                            <div class="form-text">Description de la salle, équipements, particularités...</div>
                        </div>
                    </div>

                    <!-- Configuration -->
                    <div class="form-section">
                        <h5><i class="bi bi-gear"></i> Configuration</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="location" class="form-label">Emplacement</label>
                                <input type="text" class="form-control" id="location" name="location" 
                                       value="<?= old('location') ?>" 
                                       placeholder="Ex: Étage 1, Zone A, Bâtiment principal">
                                <div class="form-text">Position physique dans le centre</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="equipment" class="form-label">Équipements</label>
                                <input type="text" class="form-control" id="equipment" name="equipment" 
                                       value="<?= old('equipment') ?>" 
                                       placeholder="Ex: Casques VR, Projecteur, Sonorisation">
                                <div class="form-text">Équipements disponibles</div>
                            </div>
                        </div>
                    </div>

                    <!-- Statut -->
                    <div class="form-section">
                        <h5><i class="bi bi-toggle-on"></i> Statut</h5>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="status" name="status" 
                                   value="active" <?= old('status', 'active') === 'active' ? 'checked' : '' ?>>
                            <label class="form-check-label" for="status">
                                Salle active (disponible pour les réservations)
                            </label>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="d-flex justify-content-between">
                        <a href="<?= base_url('admin/rooms') ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle"></i> Annuler
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Créer la salle
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
