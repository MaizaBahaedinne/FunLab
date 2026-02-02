            </div> <!-- .admin-main -->
            
            <!-- Footer -->
            <footer class="bg-white border-top py-3 px-4 mt-auto">
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                        © <?= date('Y') ?> FunLab - Tous droits réservés
                    </small>
                    <small class="text-muted">
                        Version 1.0.0
                    </small>
                </div>
            </footer>
        </div> <!-- .admin-content -->
    </div> <!-- .admin-layout -->
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Toggle sidebar on mobile
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.querySelector('.admin-sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('show');
                overlay.classList.toggle('show');
            });
        }
        
        if (overlay) {
            overlay.addEventListener('click', function() {
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
            });
        }
    </script>
    <?= $additionalJS ?? '' ?>
</body>
</html>
