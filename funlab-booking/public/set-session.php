<?php
// Script temporaire pour définir la session et tester la vérification
session_start();

// Simuler la session de vérification
$_SESSION['_ci_old_input'] = [];
$_SESSION['__ci_vars'] = [];
$_SESSION['pending_verification_user_id'] = 2; // ID de l'utilisateur

echo "<h2>Session configurée</h2>";
echo "<p>Vous pouvez maintenant accéder à la page de vérification :</p>";
echo '<p><a href="/auth/verify-email">Aller à la page de vérification</a></p>';
echo '<p>Code à entrer : <strong>123456</strong></p>';
