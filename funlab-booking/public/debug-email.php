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
echo "<li>From Email: " . ($settings['mail_from_email'] ?? 'N/A') . "</li>";
echo "<li>From Name: " . ($settings['mail_from_name'] ?? 'N/A') . "</li>";
echo "</ul>";

// Étape 4: Préparation du message
echo "<div class='step'><strong>Étape 4:</strong> Préparation du message HTML...</div>";
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

// Étape 5: Préparation des headers
echo "<div class='step'><strong>Étape 5:</strong> Préparation des headers email...</div>";
$to = $user['email'];
$subject = 'Vérification de votre compte FunLab';

$headers = "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
$headers .= "From: " . ($settings['mail_from_name'] ?? 'FunLab') . " <" . ($settings['mail_from_email'] ?? 'noreply@funlab.tn') . ">\r\n";
$headers .= "Reply-To: " . ($settings['mail_from_email'] ?? 'noreply@funlab.tn') . "\r\n";

echo "<div class='step success'>✅ Headers préparés</div>";
echo "<pre style='background:#f8f9fa;padding:10px;font-size:11px;'>" . htmlspecialchars($headers) . "</pre>";

// Étape 6: Vérification fonction mail()
echo "<div class='step'><strong>Étape 6:</strong> Vérification fonction mail()...</div>";
if (!function_exists('mail')) {
    echo "<div class='step error'>❌ La fonction mail() n'est pas disponible sur ce serveur !</div>";
    die();
}
echo "<div class='step success'>✅ Fonction mail() disponible</div>";

// Étape 7: Envoi de l'email
echo "<div class='step'><strong>Étape 7:</strong> Tentative d'envoi de l'email...</div>";
echo "<div class='step warning'>
    <strong>Destinataire:</strong> $to<br>
    <strong>Sujet:</strong> $subject<br>
    <strong>Taille message:</strong> " . strlen($message) . " octets
</div>";

$startTime = microtime(true);

// Activer le rapport d'erreur pour mail()
error_reporting(E_ALL);
$lastError = error_get_last();

$result = @mail($to, $subject, $message, $headers);

$endTime = microtime(true);
$duration = round(($endTime - $startTime) * 1000, 2);

$newError = error_get_last();

if ($result) {
    echo "<div class='step success'>✅ <strong>mail() a retourné TRUE</strong></div>";
    echo "<div class='step success'>⏱️ Temps d'exécution: {$duration}ms</div>";
    echo "<div class='step warning'>
        ⚠️ <strong>Important:</strong> mail() retourne TRUE ne signifie pas que l'email est arrivé !<br>
        Cela signifie seulement que PHP a transmis l'email au serveur mail.<br><br>
        <strong>Vérifications à faire:</strong>
        <ul>
            <li>Vérifier les SPAMS dans votre boîte mail</li>
            <li>Vérifier les logs du serveur mail: /var/log/mail.log</li>
            <li>Vérifier la configuration DNS (SPF, DKIM) dans cPanel</li>
            <li>Tester avec un autre email (Gmail peut bloquer certains serveurs)</li>
        </ul>
    </div>";
} else {
    echo "<div class='step error'>❌ <strong>mail() a retourné FALSE</strong></div>";
    echo "<div class='step error'>⏱️ Temps d'exécution: {$duration}ms</div>";
    
    if ($newError && $newError !== $lastError) {
        echo "<div class='step error'><strong>Erreur PHP:</strong><br>" . htmlspecialchars($newError['message']) . "</div>";
    }
    
    echo "<div class='step error'>
        <strong>Causes possibles:</strong>
        <ul>
            <li>Fonction mail() désactivée dans php.ini</li>
            <li>Serveur mail (sendmail/postfix) non configuré</li>
            <li>Permissions insuffisantes</li>
            <li>Pare-feu bloquant le port 25</li>
        </ul>
    </div>";
}

// Étape 8: Informations serveur
echo "<div class='step'><strong>Étape 8:</strong> Informations serveur mail...</div>";
echo "<ul>";
echo "<li>sendmail_path: " . ini_get('sendmail_path') . "</li>";
echo "<li>SMTP: " . ini_get('SMTP') . "</li>";
echo "<li>smtp_port: " . ini_get('smtp_port') . "</li>";
echo "</ul>";

$mysqli->close();

echo "<hr>";
echo "<p><a href='/auth/verify-email' style='display:inline-block;background:#667eea;color:white;padding:12px 24px;text-decoration:none;border-radius:5px;'>→ Aller à la page de vérification</a></p>";
