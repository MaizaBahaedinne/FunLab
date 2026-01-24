<?php
$title = 'Réservation - FunLab Tunisie';
$activeMenu = 'booking';
?>

<?= view('front/layouts/header', compact('title')) ?>
<?= view('front/layouts/navbar', compact('activeMenu')) ?>

    <div class="container my-5">
        <h1 class="text-center mb-4">Réserver votre activité</h1>
        <p class="text-center text-muted mb-5">Consultez la page <a href="<?= base_url('availability-example.html') ?>">démo de disponibilité</a></p>
        
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> 
            Cette page est en cours de développement. Utilisez l'exemple d'intégration pour tester l'API de disponibilité.
        </div>
    </div>

<?= view('front/layouts/footer') ?>
