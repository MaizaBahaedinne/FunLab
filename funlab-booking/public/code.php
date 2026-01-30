<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Test simple<br>";

// Test connexion DB
$mysqli = new mysqli('localhost', 'funl_FunLabBooking', 'FunLabBooking2026!', 'funl_FunLabBooking');

if ($mysqli->connect_error) {
    die('Erreur DB: ' . $mysqli->connect_error);
}

echo "DB OK<br>";

// Récupérer le code
$result = $mysqli->query("SELECT verification_code FROM users WHERE email = 'maizakoussai@gmail.com'");
$user = $result->fetch_assoc();

echo "Code actuel: <strong style='font-size:30px;color:green;'>" . $user['verification_code'] . "</strong><br>";
echo "<br>";
echo "Vous pouvez entrer ce code sur: <a href='/auth/verify-email'>Page de vérification</a>";

$mysqli->close();
