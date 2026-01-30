<?php
/**
 * TEST DE CONNEXION À LA BASE DE DONNÉES
 * Fichier de diagnostic pour vérifier la connexion MySQL
 * 
 * URL: https://funlab.faltaagency.com/test-db.php
 */

header('Content-Type: text/plain; charset=utf-8');

echo "═══════════════════════════════════════════════════════\n";
echo "  TEST DE CONNEXION - FUNLAB BOOKING\n";
echo "═══════════════════════════════════════════════════════\n\n";

// Configuration de la base de données
$host = 'localhost';
$user = 'funl_FunLabBooking';
$pass = 'FunLabBooking2026!';
$dbname = 'funl_FunLabBooking';

echo "📊 Configuration:\n";
echo "   Host: $host\n";
echo "   User: $user\n";
echo "   Database: $dbname\n\n";

// Test de connexion MySQLi
echo "🔍 Test MySQLi...\n";
$mysqli = @new mysqli($host, $user, $pass, $dbname);

if ($mysqli->connect_error) {
    echo "❌ ERREUR MySQLi: " . $mysqli->connect_error . "\n";
    echo "   Code: " . $mysqli->connect_errno . "\n\n";
} else {
    echo "✅ Connexion MySQLi réussie!\n";
    echo "   Version MySQL: " . $mysqli->server_info . "\n\n";
    
    // Test des tables
    echo "📋 Vérification des tables:\n";
    $tables = ['rooms', 'games', 'bookings', 'participants', 'room_games', 'closures', 'users', 'settings'];
    
    foreach ($tables as $table) {
        $result = $mysqli->query("SELECT COUNT(*) as count FROM `$table`");
        if ($result) {
            $row = $result->fetch_assoc();
            echo "   ✅ $table: " . $row['count'] . " enregistrement(s)\n";
        } else {
            echo "   ❌ $table: Table non trouvée ou erreur\n";
        }
    }
    
    $mysqli->close();
}

echo "\n";

// Test de connexion PDO
echo "🔍 Test PDO...\n";
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Connexion PDO réussie!\n";
    
    // Test d'une requête simple
    $stmt = $pdo->query("SELECT VERSION() as version");
    $version = $stmt->fetchColumn();
    echo "   Version MySQL: $version\n";
    
} catch (PDOException $e) {
    echo "❌ ERREUR PDO: " . $e->getMessage() . "\n";
}

echo "\n";
echo "═══════════════════════════════════════════════════════\n";
echo "  PHP Version: " . PHP_VERSION . "\n";
echo "  Date: " . date('Y-m-d H:i:s') . "\n";
echo "═══════════════════════════════════════════════════════\n";
