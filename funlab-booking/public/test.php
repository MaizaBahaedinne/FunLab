<?php
/**
 * TEST PHP SIMPLE
 * Vérification basique du fonctionnement de PHP
 * 
 * URL: https://funlab.faltaagency.com/test.php
 */

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test PHP - FunLab</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 900px;
            margin: 40px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #2c3e50;
            border-bottom: 3px solid #3498db;
            padding-bottom: 10px;
        }
        .success {
            color: #27ae60;
            font-weight: bold;
        }
        .error {
            color: #e74c3c;
            font-weight: bold;
        }
        .info-box {
            background: #ecf0f1;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
        }
        .extension {
            display: inline-block;
            padding: 5px 10px;
            margin: 5px;
            background: #3498db;
            color: white;
            border-radius: 3px;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🚀 Test PHP - FunLab Booking</h1>
        
        <div class="info-box">
            <h2 class="success">✅ PHP fonctionne correctement!</h2>
            <p><strong>Version PHP:</strong> <?php echo PHP_VERSION; ?></p>
            <p><strong>Date/Heure:</strong> <?php echo date('Y-m-d H:i:s'); ?></p>
        </div>

        <h2>📂 Chemins du serveur</h2>
        <div class="info-box">
            <p><strong>Document Root:</strong> <?php echo $_SERVER['DOCUMENT_ROOT'] ?? 'N/A'; ?></p>
            <p><strong>Script Filename:</strong> <?php echo $_SERVER['SCRIPT_FILENAME'] ?? 'N/A'; ?></p>
            <p><strong>Server Name:</strong> <?php echo $_SERVER['SERVER_NAME'] ?? 'N/A'; ?></p>
            <p><strong>Server Software:</strong> <?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'N/A'; ?></p>
        </div>

        <h2>📁 Vérification des dossiers</h2>
        <div class="info-box">
            <?php
            $paths = [
                'app' => '../app',
                'writable' => '../writable',
                'writable/cache' => '../writable/cache',
                'writable/logs' => '../writable/logs',
                'writable/session' => '../writable/session',
                'writable/uploads' => '../writable/uploads',
            ];
            
            foreach ($paths as $name => $path) {
                $exists = is_dir($path);
                $writable = $exists && is_writable($path);
                
                echo '<p>';
                echo $exists ? '✅' : '❌';
                echo " <strong>$name:</strong> ";
                echo $exists ? 'Existe' : 'N\'existe pas';
                if ($exists) {
                    echo ' | ' . ($writable ? '<span class="success">Accessible en écriture</span>' : '<span class="error">Non accessible en écriture</span>');
                }
                echo '</p>';
            }
            ?>
        </div>

        <h2>🔧 Extensions PHP requises</h2>
        <div class="info-box">
            <?php
            $required = [
                'intl' => 'Internationalisation',
                'mbstring' => 'Chaînes multioctets',
                'json' => 'JSON',
                'mysqli' => 'MySQL Improved',
                'xml' => 'XML',
                'curl' => 'cURL',
                'gd' => 'GD (Images)',
                'zip' => 'ZIP',
            ];
            
            foreach ($required as $ext => $desc) {
                $loaded = extension_loaded($ext);
                echo '<span class="extension" style="background:' . ($loaded ? '#27ae60' : '#e74c3c') . '">';
                echo ($loaded ? '✅' : '❌') . ' ' . $ext;
                echo '</span>';
            }
            ?>
        </div>

        <h2>⚙️ Configuration PHP</h2>
        <div class="info-box">
            <p><strong>memory_limit:</strong> <?php echo ini_get('memory_limit'); ?></p>
            <p><strong>max_execution_time:</strong> <?php echo ini_get('max_execution_time'); ?>s</p>
            <p><strong>post_max_size:</strong> <?php echo ini_get('post_max_size'); ?></p>
            <p><strong>upload_max_filesize:</strong> <?php echo ini_get('upload_max_filesize'); ?></p>
            <p><strong>display_errors:</strong> <?php echo ini_get('display_errors') ? 'On' : 'Off'; ?></p>
            <p><strong>error_reporting:</strong> <?php echo error_reporting(); ?></p>
        </div>

        <h2>🔗 Liens de test</h2>
        <div class="info-box">
            <p><a href="test-db.php" target="_blank">➡️ Tester la connexion à la base de données</a></p>
            <p><a href="info.php" target="_blank">➡️ Voir phpinfo() complet</a></p>
            <p><a href="/" target="_blank">➡️ Accéder à l'application</a></p>
        </div>
    </div>
</body>
</html>
