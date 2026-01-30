<?php
// Test simple d'envoi d'email
ini_set('display_errors', 1);
error_reporting(E_ALL);

$to = 'maizabahaedinne@gmail.com';
$subject = 'Test Email FunLab - ' . date('H:i:s');
$message = 'Test simple envoyé depuis le serveur à ' . date('d/m/Y H:i:s');
$headers = 'From: funlab@faltaagency.com' . "\r\n" .
           'Reply-To: funlab@faltaagency.com' . "\r\n" .
           'X-Mailer: PHP/' . phpversion();

echo "<h2>Test d'envoi d'email</h2>";
echo "<p>Tentative d'envoi vers: <strong>$to</strong></p>";
echo "<p>De: <strong>funlab@faltaagency.com</strong></p>";

if (mail($to, $subject, $message, $headers)) {
    echo "<p style='color:green;'>✅ L'email a été accepté par le serveur mail()</p>";
    echo "<p>⚠️ Cela ne garantit pas la livraison. Vérifiez vos spams.</p>";
} else {
    echo "<p style='color:red;'>❌ Échec de l'envoi</p>";
    echo "<p>Erreur: " . error_get_last()['message'] . "</p>";
}

echo "<hr>";
echo "<h3>Test SMTP avec fsockopen</h3>";

$smtp_server = 'mail.faltaagency.com';
$smtp_port = 465;

echo "<p>Tentative de connexion à $smtp_server:$smtp_port...</p>";

$fp = @fsockopen('ssl://' . $smtp_server, $smtp_port, $errno, $errstr, 10);
if ($fp) {
    echo "<p style='color:green;'>✅ Connexion SSL réussie au serveur SMTP</p>";
    $response = fgets($fp, 1024);
    echo "<p>Réponse: <code>$response</code></p>";
    fclose($fp);
} else {
    echo "<p style='color:red;'>❌ Impossible de se connecter: $errstr ($errno)</p>";
}

// Test port 587 TLS
$smtp_port_tls = 587;
echo "<p>Tentative de connexion à $smtp_server:$smtp_port_tls...</p>";

$fp2 = @fsockopen($smtp_server, $smtp_port_tls, $errno2, $errstr2, 10);
if ($fp2) {
    echo "<p style='color:green;'>✅ Connexion TLS réussie au serveur SMTP</p>";
    $response2 = fgets($fp2, 1024);
    echo "<p>Réponse: <code>$response2</code></p>";
    fclose($fp2);
} else {
    echo "<p style='color:red;'>❌ Impossible de se connecter: $errstr2 ($errno2)</p>";
}
