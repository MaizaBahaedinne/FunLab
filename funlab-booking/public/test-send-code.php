<?php
// Test simple d'envoi d'email avec mail() natif PHP
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Test d'envoi d'email de vérification</h2>";

// Connexion DB
$mysqli = @new mysqli('localhost', 'funl_FunLabBooking', 'FunLabBooking2026!', 'funl_FunLabBooking');

if ($mysqli->connect_error) {
    die('<p style="color:red;">❌ Erreur DB: ' . htmlspecialchars($mysqli->connect_error) . '</p>');
}

// Récupérer l'utilisateur
$result = $mysqli->query("SELECT * FROM users WHERE email = 'maizakoussai@gmail.com'");
if (!$result) {
    die('<p style="color:red;">❌ Erreur requête: ' . htmlspecialchars($mysqli->error) . '</p>');
}

$user = $result->fetch_assoc();

if (!$user) {
    die('<p style="color:red;">❌ Utilisateur non trouvé</p>');
}

echo "<p>Email: {$user['email']}</p>";
echo "<p>Code actuel: <strong style='font-size:24px;color:#667eea;background:#f0f0f0;padding:10px;border-radius:5px;'>{$user['verification_code']}</strong></p>";
echo "<p>Expire: {$user['verification_code_expires']}</p>";

// Charger les paramètres email
$settingsResult = $mysqli->query("SELECT `key`, value FROM settings WHERE category = 'mail'");
$settings = [];
while ($row = $settingsResult->fetch_assoc()) {
    $settings[$row['key']] = $row['value'];
}

echo "<hr>";
echo "<h3>Configuration email:</h3>";
echo "<ul>";
echo "<li>From: " . htmlspecialchars($settings['mail_from_email'] ?? 'N/A') . "</li>";
echo "<li>Name: " . htmlspecialchars($settings['mail_from_name'] ?? 'N/A') . "</li>";
echo "</ul>";

// Préparer l'email
$to = $user['email'];
$subject = 'Vérification de votre compte FunLab';
$code = $user['verification_code'];
$firstName = $user['first_name'];

$message = <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0;">
    <div style="max-width: 600px; margin: 20px auto; padding: 0;">
        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
            <h1 style="margin: 0;">🎮 Bienvenue sur FunLab !</h1>
        </div>
        <div style="background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px;">
            <h2>Bonjour $firstName,</h2>
            <p>Merci de vous être inscrit sur FunLab ! Pour activer votre compte, veuillez entrer le code de vérification ci-dessous :</p>
            <div style="background: white; border: 2px dashed #667eea; padding: 20px; text-align: center; margin: 20px 0; border-radius: 8px;">
                <div style="font-size: 32px; font-weight: bold; color: #667eea; letter-spacing: 5px;">$code</div>
            </div>
            <p><strong>Ce code expire dans 15 minutes.</strong></p>
            <p>Si vous n'avez pas créé de compte, ignorez cet email.</p>
        </div>
        <div style="text-align: center; margin-top: 20px; color: #999; font-size: 12px;">
            <p>FunLab Tunisie | funlab@faltaagency.com</p>
        </div>
    </div>
</body>
</html>
HTML;

$headers = "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
$headers .= "From: " . ($settings['mail_from_name'] ?? 'FunLab') . " <" . ($settings['mail_from_email'] ?? 'noreply@funlab.tn') . ">\r\n";
$headers .= "Reply-To: " . ($settings['mail_from_email'] ?? 'noreply@funlab.tn') . "\r\n";

echo "<h3>Envoi en cours...</h3>";

// Envoyer l'email
if (mail($to, $subject, $message, $headers)) {
    echo '<div style="background:#d4edda;border:1px solid #c3e6cb;color:#155724;padding:20px;border-radius:5px;margin:20px 0;">';
    echo '<h3 style="margin:0 0 10px 0;">✅ Email envoyé avec succès !</h3>';
    echo '<p>Destinataire : <strong>' . htmlspecialchars($user['email']) . '</strong></p>';
    echo '<p style="margin:0;">⚠️ Pensez à vérifier les <strong>SPAMS / Courrier indésirable</strong></p>';
    echo '</div>';
    echo '<p><a href="/auth/verify-email" style="display:inline-block;background:#667eea;color:white;padding:12px 24px;text-decoration:none;border-radius:5px;font-weight:bold;">→ Aller à la page de vérification</a></p>';
} else {
    $error = error_get_last();
    echo '<div style="background:#f8d7da;border:1px solid #f5c6cb;color:#721c24;padding:20px;border-radius:5px;margin:20px 0;">';
    echo '<h3 style="margin:0 0 10px 0;">❌ Échec de l\'envoi</h3>';
    if ($error) {
        echo '<p><strong>Erreur:</strong> ' . htmlspecialchars($error['message']) . '</p>';
    } else {
        echo '<p>La fonction mail() a échoué. Vérifiez la configuration SMTP du serveur.</p>';
    }
    echo '</div>';
}

$mysqli->close();


// Connexion DB
$mysqli = new mysqli('localhost', 'funl_FunLabBooking', 'FunLabBooking2026!', 'funl_FunLabBooking');

if ($mysqli->connect_error) {
    die('Erreur DB: ' . $mysqli->connect_error);
}

// Récupérer l'utilisateur
$result = $mysqli->query("SELECT * FROM users WHERE email = 'maizakoussai@gmail.com'");
$user = $result->fetch_assoc();

if (!$user) {
    die('Utilisateur non trouvé');
}

echo "<p>Email: {$user['email']}</p>";
echo "<p>Code actuel: <strong style='font-size:24px;color:#667eea;background:#f0f0f0;padding:10px;'>{$user['verification_code']}</strong></p>";
echo "<p>Expire: {$user['verification_code_expires']}</p>";

// Charger les paramètres email
$settingsResult = $mysqli->query("SELECT `key`, value FROM settings WHERE category = 'mail'");
$settings = [];
while ($row = $settingsResult->fetch_assoc()) {
    $settings[$row['key']] = $row['value'];
}

echo "<h3>Configuration SMTP:</h3>";
echo "<ul>";
echo "<li>Host: " . ($settings['mail_smtp_host'] ?? 'N/A') . "</li>";
echo "<li>Port: " . ($settings['mail_smtp_port'] ?? 'N/A') . "</li>";
echo "<li>User: " . ($settings['mail_smtp_user'] ?? 'N/A') . "</li>";
echo "<li>Crypto: " . ($settings['mail_smtp_crypto'] ?? 'N/A') . "</li>";
echo "</ul>";

// Vérifier si PHPMailer est disponible
if (!class_exists('PHPMailer\\PHPMailer\\PHPMailer')) {
    die('<p style="color:red;">❌ PHPMailer non trouvé. Installez-le avec composer.</p>');
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
    // Configuration SMTP
    $mail->SMTPDebug = 0;
    $mail->isSMTP();
    $mail->Host = $settings['mail_smtp_host'];
    $mail->SMTPAuth = true;
    $mail->Username = $settings['mail_smtp_user'];
    $mail->Password = $settings['mail_smtp_pass'];
    $mail->SMTPSecure = $settings['mail_smtp_crypto'];
    $mail->Port = $settings['mail_smtp_port'];
    $mail->CharSet = 'UTF-8';

    // Destinataires
    $mail->setFrom($settings['mail_from_email'], $settings['mail_from_name']);
    $mail->addAddress($user['email'], $user['first_name']);

    // Contenu
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

    echo "<h3>Envoi en cours...</h3>";
    $mail->send();
    
    echo '<div style="background:#d4edda;border:1px solid #c3e6cb;color:#155724;padding:20px;border-radius:5px;margin:20px 0;">';
    echo '<h3 style="margin:0 0 10px 0;">✅ Email envoyé avec succès !</h3>';
    echo '<p>Vérifiez votre boîte mail : <strong>' . $user['email'] . '</strong></p>';
    echo '<p style="margin:0;">⚠️ Pensez à vérifier les <strong>SPAMS / Courrier indésirable</strong></p>';
    echo '</div>';
    echo '<p><a href="/auth/verify-email" style="display:inline-block;background:#667eea;color:white;padding:12px 24px;text-decoration:none;border-radius:5px;font-weight:bold;">→ Aller à la page de vérification</a></p>';
    
} catch (Exception $e) {
    echo '<div style="background:#f8d7da;border:1px solid #f5c6cb;color:#721c24;padding:20px;border-radius:5px;margin:20px 0;">';
    echo '<h3 style="margin:0 0 10px 0;">❌ Échec de l\'envoi</h3>';
    echo '<p><strong>Erreur:</strong> ' . htmlspecialchars($mail->ErrorInfo) . '</p>';
    echo '</div>';
}

$mysqli->close();


// Connexion DB
$mysqli = new mysqli('localhost', 'funl_FunLabBooking', 'FunLabBooking2026!', 'funl_FunLabBooking');

if ($mysqli->connect_error) {
    die('Erreur DB: ' . $mysqli->connect_error);
}

// Récupérer l'utilisateur
$result = $mysqli->query("SELECT * FROM users WHERE email = 'maizakoussai@gmail.com'");
$user = $result->fetch_assoc();

if (!$user) {
    die('Utilisateur non trouvé');
}

echo "<h2>Test envoi email de vérification</h2>";
echo "<p>Email: {$user['email']}</p>";
echo "<p>Code actuel: <strong style='font-size:20px;color:#667eea;'>{$user['verification_code']}</strong></p>";
echo "<p>Expire: {$user['verification_code_expires']}</p>";

// Charger les paramètres email
$settingsResult = $mysqli->query("SELECT `key`, value FROM settings WHERE category = 'mail'");
$settings = [];
while ($row = $settingsResult->fetch_assoc()) {
    $settings[$row['key']] = $row['value'];
}

echo "<h3>Configuration SMTP:</h3>";
echo "<pre>";
echo "Host: " . ($settings['mail_smtp_host'] ?? 'N/A') . "\n";
echo "Port: " . ($settings['mail_smtp_port'] ?? 'N/A') . "\n";
echo "User: " . ($settings['mail_smtp_user'] ?? 'N/A') . "\n";
echo "Crypto: " . ($settings['mail_smtp_crypto'] ?? 'N/A') . "\n";
echo "From: " . ($settings['mail_from_email'] ?? 'N/A') . "\n";
echo "</pre>";

// Utiliser PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = $settings['mail_smtp_host'];
    $mail->SMTPAuth = true;
    $mail->Username = $settings['mail_smtp_user'];
    $mail->Password = $settings['mail_smtp_pass'];
    $mail->SMTPSecure = $settings['mail_smtp_crypto'];
    $mail->Port = $settings['mail_smtp_port'];
    $mail->CharSet = 'UTF-8';

    $mail->setFrom($settings['mail_from_email'], $settings['mail_from_name']);
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

    echo "<h3>Tentative d'envoi...</h3>";
    $mail->send();
    
    echo '<p style="color:green;font-size:18px;">✅ <strong>Email envoyé avec succès !</strong></p>';
    echo '<p>Vérifiez votre boîte mail : <strong>' . $user['email'] . '</strong></p>';
    echo '<p style="background:#fff3cd;padding:10px;border-left:4px solid #ffc107;">⚠️ N\'oubliez pas de vérifier les <strong>SPAMS / Courrier indésirable</strong></p>';
    echo '<p style="margin-top:20px;"><a href="/auth/verify-email" style="background:#667eea;color:white;padding:10px 20px;text-decoration:none;border-radius:5px;">Aller à la page de vérification</a></p>';
    
} catch (Exception $e) {
    echo '<p style="color:red;font-size:18px;">❌ <strong>Échec de l\'envoi</strong></p>';
    echo '<h4>Erreur:</h4>';
    echo '<pre style="background:#f5f5f5;padding:15px;border-left:4px solid #dc3545;">';
    echo htmlspecialchars($mail->ErrorInfo);
    echo '</pre>';
}

$mysqli->close();

$db = \Config\Database::connect();

// Récupérer l'utilisateur
$user = $db->table('users')->where('email', 'maizakoussai@gmail.com')->get()->getRowArray();

if (!$user) {
    die('Utilisateur non trouvé');
}

echo "<h2>Test envoi email de vérification</h2>";
echo "<p>Email: {$user['email']}</p>";
echo "<p>Code actuel: {$user['verification_code']}</p>";
echo "<p>Expire: {$user['verification_code_expires']}</p>";

// Charger les paramètres email
$settings = [];
$result = $db->table('settings')->where('category', 'mail')->get()->getResultArray();
foreach ($result as $setting) {
    $settings[$setting['key']] = $setting['value'];
}

echo "<h3>Configuration SMTP:</h3>";
echo "<pre>";
echo "Host: " . ($settings['mail_smtp_host'] ?? 'N/A') . "\n";
echo "Port: " . ($settings['mail_smtp_port'] ?? 'N/A') . "\n";
echo "User: " . ($settings['mail_smtp_user'] ?? 'N/A') . "\n";
echo "Crypto: " . ($settings['mail_smtp_crypto'] ?? 'N/A') . "\n";
echo "</pre>";

// Configurer l'email
$config = [
    'protocol'     => $settings['mail_protocol'] ?? 'smtp',
    'SMTPHost'     => $settings['mail_smtp_host'] ?? '',
    'SMTPPort'     => (int)($settings['mail_smtp_port'] ?? 587),
    'SMTPUser'     => $settings['mail_smtp_user'] ?? '',
    'SMTPPass'     => $settings['mail_smtp_pass'] ?? '',
    'SMTPCrypto'   => $settings['mail_smtp_crypto'] ?? 'tls',
    'SMTPAuth'     => true,
    'mailType'     => 'html',
    'charset'      => 'utf-8',
    'newline'      => "\r\n"
];

$email = \Config\Services::email($config);
$email->setFrom(
    $settings['mail_from_email'] ?? 'noreply@funlab.tn',
    $settings['mail_from_name'] ?? 'FunLab'
);
$email->setTo($user['email']);
$email->setSubject('Test - Vérification de votre compte FunLab');

$code = $user['verification_code'];
$firstName = $user['first_name'];

$message = <<<HTML
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

$email->setMessage($message);

echo "<h3>Tentative d'envoi...</h3>";

if ($email->send()) {
    echo '<p style="color:green;">✅ Email envoyé avec succès !</p>';
    echo '<p>Vérifiez votre boîte mail : <strong>' . $user['email'] . '</strong></p>';
    echo '<p>N\'oubliez pas de vérifier les <strong>spams</strong></p>';
} else {
    echo '<p style="color:red;">❌ Échec de l\'envoi</p>';
    echo '<h4>Détails de l\'erreur:</h4>';
    echo '<pre style="background:#f5f5f5;padding:15px;overflow:auto;max-height:300px;">';
    echo $email->printDebugger(['headers', 'subject', 'body']);
    echo '</pre>';
}
