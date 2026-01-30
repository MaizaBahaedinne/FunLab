<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>🔍 Debug - Envoi d'email de vérification</h2>";
echo "<style>
.step { background: #f8f9fa; border-left: 4px solid #007bff; padding: 15px; margin: 10px 0; }
.success { border-left-color: #28a745; background: #d4edda; }
.error { border-left-color: #dc3545; background: #f8d7da; }
.warning { border-left-color: #ffc107; background: #fff3cd; }
</style>";

// Étape 1: Connexion DB
echo "<div class='step'><strong>Étape 1:</strong> Connexion à la base de données...</div>";
$mysqli = new mysqli('localhost', 'funl_FunLabBooking', 'FunLabBooking2026!', 'funl_FunLabBooking');

if ($mysqli->connect_error) {
    echo "<div class='step error'>❌ Erreur: " . $mysqli->connect_error . "</div>";
    die();
}
echo "<div class='step success'>✅ Connexion DB réussie</div>";

// Étape 2: Récupération utilisateur
echo "<div class='step'><strong>Étape 2:</strong> Récupération de l'utilisateur...</div>";
$result = $mysqli->query("SELECT * FROM users WHERE email = 'maizakoussai@gmail.com'");
$user = $result->fetch_assoc();

if (!$user) {
    echo "<div class='step error'>❌ Utilisateur non trouvé</div>";
    die();
}
echo "<div class='step success'>✅ Utilisateur trouvé: " . $user['email'] . "</div>";
echo "<div class='step warning'>📌 Code actuel: <strong style='font-size:20px;'>" . ($user['verification_code'] ?? 'NULL') . "</strong></div>";

// Étape 3: Récupération paramètres email
echo "<div class='step'><strong>Étape 3:</strong> Chargement des paramètres email...</div>";
$settingsResult = $mysqli->query("SELECT `key`, value FROM settings WHERE category = 'mail'");
$settings = [];
while ($row = $settingsResult->fetch_assoc()) {
    $settings[$row['key']] = $row['value'];
}

echo "<div class='step success'>✅ Paramètres chargés:</div>";
echo "<ul>";
echo "<li>Protocol: " . ($settings['mail_protocol'] ?? 'N/A') . "</li>";
echo "<li>SMTP Host: " . ($settings['mail_smtp_host'] ?? 'N/A') . "</li>";
echo "<li>SMTP Port: " . ($settings['mail_smtp_port'] ?? 'N/A') . "</li>";
echo "<li>SMTP User: " . ($settings['mail_smtp_user'] ?? 'N/A') . "</li>";
echo "<li>SMTP Crypto: " . ($settings['mail_smtp_crypto'] ?? 'N/A') . "</li>";
echo "<li>From Email: " . ($settings['mail_from_email'] ?? 'N/A') . "</li>";
echo "<li>From Name: " . ($settings['mail_from_name'] ?? 'N/A') . "</li>";
echo "</ul>";

// Étape 4: Chargement CodeIgniter
echo "<div class='step'><strong>Étape 4:</strong> Chargement de CodeIgniter...</div>";

// Définir les constantes nécessaires
define('FCPATH', __DIR__ . '/');
define('SYSTEMPATH', realpath(__DIR__ . '/../vendor/codeigniter4/framework/system') . '/');
define('APPPATH', realpath(__DIR__ . '/../app') . '/');
define('WRITEPATH', realpath(__DIR__ . '/../writable') . '/');
define('ROOTPATH', realpath(__DIR__ . '/..') . '/');

// Charger l'autoloader
require_once ROOTPATH . 'vendor/autoload.php';

// Définir l'environnement
defined('ENVIRONMENT') || define('ENVIRONMENT', 'production');

// Charger les fonctions communes de CodeIgniter
require_once SYSTEMPATH . 'Common.php';

echo "<div class='step success'>✅ CodeIgniter chargé</div>";

// Étape 5: Préparation du message
echo "<div class='step'><strong>Étape 5:</strong> Préparation du message HTML...</div>";
$code = $user['verification_code'] ?? '000000';
$firstName = $user['first_name'];

$message = <<<HTML
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"></head>
<body style="font-family: Arial, sans-serif;">
    <div style="max-width: 600px; margin: 20px auto;">
        <div style="background: #667eea; color: white; padding: 30px; text-align: center;">
            <h1>🎮 Bienvenue sur FunLab !</h1>
        </div>
        <div style="background: #f9f9f9; padding: 30px;">
            <h2>Bonjour $firstName,</h2>
            <p>Voici votre code de vérification :</p>
            <div style="background: white; border: 2px dashed #667eea; padding: 20px; text-align: center; margin: 20px 0;">
                <div style="font-size: 32px; font-weight: bold; color: #667eea;">$code</div>
            </div>
            <p><strong>Ce code expire dans 15 minutes.</strong></p>
        </div>
    </div>
</body>
</html>
HTML;

echo "<div class='step success'>✅ Message HTML préparé (" . strlen($message) . " caractères)</div>";

// Étape 6: Configuration SMTP avec CodeIgniter
echo "<div class='step'><strong>Étape 6:</strong> Configuration SMTP CodeIgniter...</div>";
$to = $user['email'];
$subject = 'Vérification de votre compte FunLab - TEST DEBUG';

$config = [
    'protocol'     => 'smtp',
    'SMTPHost'     => $settings['mail_smtp_host'] ?? 'mail.faltaagency.com',
    'SMTPPort'     => (int)($settings['mail_smtp_port'] ?? 587),
    'SMTPUser'     => $settings['mail_smtp_user'] ?? 'funlab@faltaagency.com',
    'SMTPPass'     => $settings['mail_smtp_pass'] ?? '',
    'SMTPCrypto'   => $settings['mail_smtp_crypto'] ?? 'tls',
    'SMTPAuth'     => true,
    'SMTPTimeout'  => 10,
    'mailType'     => 'html',
    'charset'      => 'utf-8',
    'newline'      => "\r\n",
    'wordWrap'     => true
];

echo "<div class='step success'>✅ Configuration SMTP:</div>";
echo "<pre style='background:#f8f9fa;padding:10px;font-size:11px;'>";
echo "Host: " . $config['SMTPHost'] . "\n";
echo "Port: " . $config['SMTPPort'] . "\n";
echo "User: " . $config['SMTPUser'] . "\n";
echo "Crypto: " . $config['SMTPCrypto'] . "\n";
echo "</pre>";

// Étape 7: Envoi via CodeIgniter Email
echo "<div class='step'><strong>Étape 7:</strong> Tentative d'envoi via SMTP externe...</div>";
echo "<div class='step warning'>
    <strong>Destinataire:</strong> $to<br>
    <strong>Sujet:</strong> $subject
</div>";

try {
    $email = \Config\Services::email($config);
    $email->setFrom(
        $settings['mail_from_email'] ?? 'funlab@faltaagency.com',
        $settings['mail_from_name'] ?? 'FunLab'
    );
    $email->setTo($to);
    $email->setSubject($subject);
    $email->setMessage($message);

    $startTime = microtime(true);
    $result = $email->send();
    $endTime = microtime(true);
    $duration = round(($endTime - $startTime) * 1000, 2);

    if ($result) {
        echo "<div class='step success'>✅ <strong>Email envoyé avec succès via SMTP !</strong></div>";
        echo "<div class='step success'>⏱️ Temps d'exécution: {$duration}ms</div>";
        echo "<div class='step warning'>
            📧 <strong>Vérifiez votre boîte mail:</strong>
            <ul>
                <li>Boîte de réception: $to</li>
                <li>Dossier SPAM / Courrier indésirable</li>
                <li>Peut prendre quelques minutes pour arriver</li>
            </ul>
        </div>";
    } else {
        echo "<div class='step error'>❌ <strong>Échec de l'envoi SMTP</strong></div>";
        echo "<div class='step error'>⏱️ Temps d'exécution: {$duration}ms</div>";
        echo "<div class='step error'><strong>Détails:</strong><br><pre style='background:#f8d7da;padding:10px;overflow:auto;max-height:300px;'>";
        echo $email->printDebugger(['headers', 'subject', 'body']);
        echo "</pre></div>";
    }
} catch (Exception $e) {
    echo "<div class='step error'>❌ <strong>Exception:</strong> " . htmlspecialchars($e->getMessage()) . "</div>";
}

// Étape 8: Test de connexion SMTP
echo "<div class='step'><strong>Étape 8:</strong> Test de connexion SMTP...</div>";
$smtp_host = $config['SMTPHost'];
$smtp_port = $config['SMTPPort'];
$smtp_crypto = $config['SMTPCrypto'];

$fp = @fsockopen(($smtp_crypto == 'tls' ? '' : 'ssl://') . $smtp_host, $smtp_port, $errno, $errstr, 5);
if ($fp) {
    echo "<div class='step success'>✅ Connexion {$smtp_crypto}://{$smtp_host}:{$smtp_port} réussie</div>";
    $response = fgets($fp, 1024);
    echo "<div class='step success'>Réponse serveur: <code>" . htmlspecialchars($response) . "</code></div>";
    fclose($fp);
} else {
    echo "<div class='step error'>❌ Impossible de se connecter à {$smtp_host}:{$smtp_port}</div>";
    echo "<div class='step error'>Erreur: $errstr ($errno)</div>";
}

echo "<ul>";
echo "<li>Config: {$smtp_crypto}://{$smtp_host}:{$smtp_port}</li>";
echo "<li>User: " . $config['SMTPUser'] . "</li>";
echo "</ul>";

echo "<hr>";
echo "<p><a href='/auth/verify-email' style='display:inline-block;background:#667eea;color:white;padding:12px 24px;text-decoration:none;border-radius:5px;'>→ Aller à la page de vérification</a></p>";
