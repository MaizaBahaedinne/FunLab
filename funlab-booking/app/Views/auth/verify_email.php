<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérification Email - FunLab</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .verify-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            max-width: 500px;
            width: 100%;
            padding: 40px;
        }
        .code-inputs {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin: 30px 0;
        }
        .code-input {
            width: 50px;
            height: 60px;
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            border: 2px solid #dee2e6;
            border-radius: 8px;
            transition: all 0.3s;
        }
        .code-input:focus {
            border-color: #667eea;
            outline: none;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        .verify-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 8px;
            transition: transform 0.2s;
        }
        .verify-btn:hover {
            transform: translateY(-2px);
        }
        .resend-link {
            color: #667eea;
            cursor: pointer;
            text-decoration: none;
        }
        .resend-link:hover {
            text-decoration: underline;
        }
        .timer {
            color: #6c757d;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="verify-card">
        <div class="text-center mb-4">
            <i class="bi bi-envelope-check" style="font-size: 60px; color: #667eea;"></i>
            <h2 class="mt-3">Vérifiez votre email</h2>
            <p class="text-muted">Nous avons envoyé un code à 6 chiffres à<br><strong><?= esc($email) ?></strong></p>
        </div>

        <form id="verifyForm">
            <div class="code-inputs">
                <input type="text" class="form-control code-input" maxlength="1" id="code1" autocomplete="off">
                <input type="text" class="form-control code-input" maxlength="1" id="code2" autocomplete="off">
                <input type="text" class="form-control code-input" maxlength="1" id="code3" autocomplete="off">
                <input type="text" class="form-control code-input" maxlength="1" id="code4" autocomplete="off">
                <input type="text" class="form-control code-input" maxlength="1" id="code5" autocomplete="off">
                <input type="text" class="form-control code-input" maxlength="1" id="code6" autocomplete="off">
            </div>

            <button type="submit" class="btn verify-btn btn-primary w-100 mb-3">
                <i class="bi bi-check-circle"></i> Vérifier
            </button>
        </form>

        <div class="text-center">
            <p class="mb-2">Vous n'avez pas reçu le code ?</p>
            <a href="#" class="resend-link" id="resendBtn">Renvoyer le code</a>
            <div class="timer mt-2" id="timer"></div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Gestion des inputs de code
        const inputs = document.querySelectorAll('.code-input');
        
        inputs.forEach((input, index) => {
            input.addEventListener('input', (e) => {
                if (e.target.value.length === 1 && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
            });

            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && e.target.value === '' && index > 0) {
                    inputs[index - 1].focus();
                }
            });

            // Autoriser seulement les chiffres
            input.addEventListener('keypress', (e) => {
                if (!/[0-9]/.test(e.key)) {
                    e.preventDefault();
                }
            });

            // Coller le code complet
            input.addEventListener('paste', (e) => {
                e.preventDefault();
                const pastedData = e.clipboardData.getData('text').replace(/\D/g, '').substring(0, 6);
                pastedData.split('').forEach((char, i) => {
                    if (inputs[i]) {
                        inputs[i].value = char;
                    }
                });
                if (pastedData.length > 0) {
                    inputs[Math.min(pastedData.length, 5)].focus();
                }
            });
        });

        // Focus automatique sur le premier input
        inputs[0].focus();

        // Soumettre le formulaire
        document.getElementById('verifyForm').addEventListener('submit', async (e) => {
            e.preventDefault();

            const code = Array.from(inputs).map(input => input.value).join('');

            if (code.length !== 6) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Code incomplet',
                    text: 'Veuillez entrer les 6 chiffres du code'
                });
                return;
            }

            try {
                const response = await fetch('<?= base_url('auth/attempt-verify-email') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ 
                        code: code,
                        email: '<?= esc($email) ?>' // Envoyer l'email en fallback
                    })
                });

                const data = await response.json();

                if (data.success) {
                    await Swal.fire({
                        icon: 'success',
                        title: 'Email vérifié !',
                        text: 'Votre compte a été activé avec succès',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    window.location.href = data.redirect;
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Code incorrect',
                        text: data.message
                    });
                    inputs.forEach(input => input.value = '');
                    inputs[0].focus();
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: 'Une erreur est survenue'
                });
            }
        });

        // Renvoyer le code
        let canResend = true;
        let countdown = 60;

        document.getElementById('resendBtn').addEventListener('click', async (e) => {
            e.preventDefault();

            if (!canResend) {
                return;
            }

            try {
                const response = await fetch('<?= base_url('auth/resend-verification-code') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ email: '<?= esc($email) ?>' })
                });

                const data = await response.json();

                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Code renvoyé',
                        text: data.message,
                        timer: 2000,
                        showConfirmButton: false
                    });

                    // Démarrer le compte à rebours
                    canResend = false;
                    countdown = 60;
                    updateTimer();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
                        text: data.message
                    });
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: 'Une erreur est survenue'
                });
            }
        });

        function updateTimer() {
            const timerEl = document.getElementById('timer');
            const resendBtn = document.getElementById('resendBtn');

            if (countdown > 0) {
                timerEl.textContent = `Nouveau code disponible dans ${countdown}s`;
                resendBtn.style.pointerEvents = 'none';
                resendBtn.style.opacity = '0.5';
                countdown--;
                setTimeout(updateTimer, 1000);
            } else {
                timerEl.textContent = '';
                resendBtn.style.pointerEvents = 'auto';
                resendBtn.style.opacity = '1';
                canResend = true;
            }
        }
    </script>
</body>
</html>
