<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - FunLab Tunisie</title>
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
            max-width: 450px;
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
                <p class="mb-0">Connectez-vous à votre compte</p>
            </div>

            <div class="auth-body">
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
                <?php endif; ?>

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
                <?php if ($googleEnabled || $facebookEnabled): ?>
                    <?php if ($googleEnabled): ?>
                    <a href="<?= base_url('auth/google') ?>" class="btn social-btn google-btn">
                        <i class="bi bi-google"></i> Continuer avec Google
                    </a>
                    <?php endif; ?>

                    <?php if ($facebookEnabled): ?>
                    <a href="<?= base_url('auth/facebook') ?>" class="btn social-btn facebook-btn">
                        <i class="bi bi-facebook"></i> Continuer avec Facebook
                    </a>
                    <?php endif; ?>

                    <div class="divider">
                        <span>OU</span>
                    </div>
                <?php endif; ?>

                <!-- Formulaire de connexion classique -->
                <form action="<?= base_url('auth/login') ?>" method="POST">
                    <?= csrf_field() ?>
                    
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required 
                               value="<?= old('email') ?>" placeholder="votre@email.com">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mot de passe</label>
                        <input type="password" name="password" class="form-control" required 
                               placeholder="••••••••">
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" name="remember" class="form-check-input" id="remember">
                        <label class="form-check-label" for="remember">
                            Se souvenir de moi
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-box-arrow-in-right"></i> Se connecter
                    </button>
                </form>

                <div class="text-center mt-4">
                    <a href="<?= base_url('auth/forgot-password') ?>" class="text-muted">
                        Mot de passe oublié ?
                    </a>
                </div>

                <hr>

                <div class="text-center">
                    <p class="mb-0">
                        Pas encore de compte ?
                        <a href="<?= base_url('auth/register') ?>" class="text-decoration-none fw-bold">
                            S'inscrire
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
