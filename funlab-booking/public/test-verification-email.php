<?php
// Test d'envoi du code de vérification
require '../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Connexion DB
$mysqli = new mysqli('localhost', 'funl_FunLabBooking', 'FunLabBooking2026!', 'funl_FunLabBooking');

if ($mysqli->connect_error) {
    die('Erreur DB: ' . $mysqli->connect_error);
}

// Récupérer le dernier utilisateur
$result = $mysqli->query("SELECT * FROM users WHERE email = 'maizakoussai@gmail.com'");
$user = $result->fetch_assoc();

if (!$user) {
    die('Utilisateur non trouvé');
}

echo "<h2>Test envoi email de vérification</h2>";
echo "<p>Utilisateur: {$user['email']}</p>";
echo "<p>Code: {$user['verification_code']}</p>";
echo "<p>Expire: {$user['verification_code_expires']}</p>";

// Configuration email
$mail = new PHPMailer\PHPMailer\PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'mail.faltaagency.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'funlab@faltaagency.com';
    $mail->Password = 'FunLab2026';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;
    $mail->CharSet = 'UTF-8';

    $mail->setFrom('funlab@faltaagency.com', 'FunLab Tunisie');
    $mail->addAddress($user['email']);

    $mail->isHTML(true);
    $mail->Subject = 'Vérification de votre compte FunLab';
    
    $code = $user['verification_code'];
    $firstName = $user['first_name'];
    
    $mail->Body = <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; }
        .code-box { background: white; border: 2px dashed #667eea; padding: 20px; text-align: center; margin: 20px 0; border-radius: 8px; }
        .code { font-size: 32px; font-weight: bold; color: #667eea; letter-spacing: 5px; }
        .footer { text-align: center; margin-top: 20px; color: #999; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎮 Bienvenue sur FunLab !</h1>
        </div>
        <div class="content">
            <h2>Bonjour $firstName,</h2>
            <p>Merci de vous être inscrit sur FunLab ! Pour activer votre compte, veuillez entrer le code de vérification ci-dessous :</p>
            <div class="code-box">
                <div class="code">$code</div>
            </div>
            <p><strong>Ce code expire dans 15 minutes.</strong></p>
            <p>Si vous n'avez pas créé de compte, ignorez cet email.</p>
        </div>
        <div class="footer">
            <p>FunLab Tunisie | funlab@faltaagency.com</p>
        </div>
    </div>
</body>
</html>
HTML;

    $mail->send();
    echo '<p style="color:green;">✅ Email envoyé avec succès !</p>';
    echo '<p>Vérifiez votre boîte mail (et les spams)</p>';
    
} catch (Exception $e) {
    echo "<p style='color:red;'>❌ Erreur: {$mail->ErrorInfo}</p>";
}
