<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - FunLab Tunisie</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .auth-container {
            max-width: 500px;
            width: 100%;
        }
        .auth-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        .auth-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .auth-body {
            padding: 40px;
        }
        .social-btn {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: none;
            border-radius: 8px;
            font-weight: 500;
            transition: transform 0.2s;
        }
        .social-btn:hover {
            transform: translateY(-2px);
        }
        .google-btn {
            background: #fff;
            color: #333;
            border: 1px solid #ddd;
        }
        .facebook-btn {
            background: #1877f2;
            color: white;
        }
        .divider {
            text-align: center;
            margin: 30px 0;
            position: relative;
        }
        .divider:before {
            content: "";
            position: absolute;
            left: 0;
            top: 50%;
            width: 100%;
            height: 1px;
            background: #ddd;
        }
        .divider span {
            background: white;
            padding: 0 15px;
            position: relative;
            color: #999;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <h2><i class="bi bi-joystick"></i> FunLab Tunisie</h2>
                <p class="mb-0">Créez votre compte</p>
            </div>

            <div class="auth-body">
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('errors')): ?>
                    <div class="alert alert-danger">
                        <?php foreach (session()->getFlashdata('errors') as $error): ?>
                            <div><?= $error ?></div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <!-- Boutons OAuth -->
                <a href="<?= base_url('auth/google') ?>" class="btn social-btn google-btn">
                    <i class="bi bi-google"></i> S'inscrire avec Google
                </a>

                <a href="<?= base_url('auth/facebook') ?>" class="btn social-btn facebook-btn">
                    <i class="bi bi-facebook"></i> S'inscrire avec Facebook
                </a>

                <div class="divider">
                    <span>OU</span>
                </div>

                <!-- Formulaire d'inscription -->
                <form action="<?= base_url('auth/register') ?>" method="POST">
                    <?= csrf_field() ?>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Prénom *</label>
                            <input type="text" name="first_name" class="form-control" required 
                                   value="<?= old('first_name') ?>" placeholder="Ahmed">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nom *</label>
                            <input type="text" name="last_name" class="form-control" required 
                                   value="<?= old('last_name') ?>" placeholder="Ben Ali">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email *</label>
                        <input type="email" name="email" class="form-control" required 
                               value="<?= old('email') ?>" placeholder="votre@email.com">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Téléphone</label>
                        <input type="tel" name="phone" class="form-control" 
                               value="<?= old('phone') ?>" placeholder="+216 20 123 456">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mot de passe *</label>
                        <input type="password" name="password" class="form-control" required 
                               placeholder="••••••••">
                        <small class="text-muted">Minimum 8 caractères</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Confirmer le mot de passe *</label>
                        <input type="password" name="password_confirm" class="form-control" required 
                               placeholder="••••••••">
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="terms" required>
                        <label class="form-check-label" for="terms">
                            J'accepte les <a href="#" class="text-decoration-none">conditions d'utilisation</a>
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-person-plus"></i> Créer mon compte
                    </button>
                </form>

                <hr class="my-4">

                <div class="text-center">
                    <p class="mb-0">
                        Vous avez déjà un compte ?
                        <a href="<?= base_url('auth/login') ?>" class="text-decoration-none fw-bold">
                            Se connecter
                        </a>
                    </p>
                </div>
            </div>
        </div>

        <div class="text-center mt-3">
            <a href="<?= base_url('/') ?>" class="text-white text-decoration-none">
                <i class="bi bi-arrow-left"></i> Retour à l'accueil
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
