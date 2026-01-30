<?php
// Test direct d'envoi d'email avec CodeIgniter
define('FCPATH', __DIR__ . '/');
require_once '../app/Config/Paths.php';
$paths = new Config\Paths();
require rtrim($paths->systemDirectory, '\\/ ') . '/bootstrap.php';

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
