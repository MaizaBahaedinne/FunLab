    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <h5><i class="bi bi-joystick"></i> FunLab Tunisie</h5>
                    <p>Centre d'activités indoor premium</p>
                    <div class="mt-3">
                        <a href="#" class="text-white me-3"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="text-white me-3"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="text-white me-3"><i class="bi bi-twitter"></i></a>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <h5>Contact</h5>
                    <p>
                        <i class="bi bi-geo-alt"></i> Tunis, Tunisie<br>
                        <i class="bi bi-envelope"></i> contact@funlab.tn<br>
                        <i class="bi bi-telephone"></i> +216 70 123 456
                    </p>
                </div>
                <div class="col-md-4 mb-3">
                    <h5>Horaires</h5>
                    <p>
                        Lundi - Dimanche<br>
                        09:00 - 22:00
                    </p>
                    <h6 class="mt-3">Liens Utiles</h6>
                    <ul class="list-unstyled">
                        <li><a href="<?= base_url('/') ?>" class="text-white text-decoration-none">Accueil</a></li>
                        <li><a href="<?= base_url('booking') ?>" class="text-white text-decoration-none">Réserver</a></li>
                        <li><a href="<?= base_url('account') ?>" class="text-white text-decoration-none">Mon Compte</a></li>
                    </ul>
                </div>
            </div>
            <hr class="my-3">
            <div class="text-center">
                <p class="mb-0">&copy; <?= date('Y') ?> FunLab Tunisie. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <?= $additionalJS ?? '' ?>
</body>
</html>
