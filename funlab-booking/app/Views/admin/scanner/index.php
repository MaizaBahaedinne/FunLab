<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scanner QR - FunLab Tunisie</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .scanner-container {
            max-width: 600px;
            margin: 0 auto;
        }
        .scanner-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            overflow: hidden;
        }
        #reader {
            width: 100%;
            border-bottom: 3px solid #667eea;
        }
        .access-granted {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white;
            padding: 30px;
            text-align: center;
            animation: slideDown 0.5s ease;
        }
        .access-denied {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
            color: white;
            padding: 30px;
            text-align: center;
            animation: slideDown 0.5s ease;
        }
        .access-warning {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            padding: 30px;
            text-align: center;
            animation: slideDown 0.5s ease;
        }
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }
        .stat-value {
            font-size: 2rem;
            font-weight: bold;
            color: #667eea;
        }
        .stat-label {
            font-size: 0.875rem;
            color: #6c757d;
            margin-top: 5px;
        }
        .upcoming-list {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }
        .upcoming-item {
            padding: 10px;
            border-left: 3px solid #667eea;
            margin-bottom: 10px;
            background: #f8f9fa;
        }
        .sound-toggle {
            position: fixed;
            top: 20px;
            right: 20px;
            background: white;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            z-index: 1000;
        }
    </style>
</head>
<body>
    <div class="scanner-container">
        <!-- Header -->
        <div class="text-center mb-4">
            <h1 class="text-white mb-2">
                <i class="bi bi-qr-code-scan"></i> Scanner QR
            </h1>
            <p class="text-white-50">FunLab Tunisie - Contrôle d'Accès</p>
        </div>

        <!-- Scanner Card -->
        <div class="scanner-card mb-3">
            <div id="reader"></div>
            
            <div class="p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Scanner actif</h5>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="scannerToggle" checked>
                        <label class="form-check-label" for="scannerToggle">Activé</label>
                    </div>
                </div>

                <div id="scan-result-container"></div>

                <div class="alert alert-info mt-3">
                    <i class="bi bi-info-circle"></i>
                    <strong>Instructions :</strong> Présentez le QR code du billet devant la caméra
                </div>
            </div>
        </div>

        <!-- Statistiques -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-value" id="stat-today">0</div>
                <div class="stat-label">Aujourd'hui</div>
            </div>
            <div class="stat-card">
                <div class="stat-value" id="stat-active">0</div>
                <div class="stat-label">En cours</div>
            </div>
            <div class="stat-card">
                <div class="stat-value" id="stat-completed">0</div>
                <div class="stat-label">Terminées</div>
            </div>
            <div class="stat-card">
                <div class="stat-value" id="stat-participants">0</div>
                <div class="stat-label">Participants</div>
            </div>
        </div>

        <!-- Prochaines réservations -->
        <div class="upcoming-list">
            <h6 class="mb-3"><i class="bi bi-clock-history"></i> Prochaines arrivées</h6>
            <div id="upcoming-bookings">
                <p class="text-muted text-center">Chargement...</p>
            </div>
        </div>
    </div>

    <!-- Toggle son -->
    <div class="sound-toggle" id="soundToggle" title="Activer/Désactiver le son">
        <i class="bi bi-volume-up fs-4" id="soundIcon"></i>
    </div>

    <!-- Sons -->
    <audio id="sound-success" src="data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBSuFzu/eijkIGmeR7+SvYhQFUKnk5r95KwUng8jr47tqHQY3jtbyyXksBSV+xe/aijkIG2yy7+KuXhMHUavm5b92LAQpg8jr4bpqHgU1jdXxy3gtBiWAx+/cijgIHGyx7t2tYRQHT6rm6Lh0KgYmgsXr37trHQU1jdXxy3csBiR/xe3cizgIG2yw7tyuXxMHT6nk6rd2KgYngsXr3rpqHQU1i9Txy3csBSR/xevcizgIG2ux7dutYBMGT6jk6bZ4KgYmgsXr3rpqHQU0i9Tyy3ctBSR+xu3bjDcIG2ux7dqtYBMGTqfk6rV5KgYmgcTq3rpqHgU0idTxy3YtBSR+xu3bjDcIG2qv7NqtYBMGTqfk6rV5KgYlgcPq3rpqHgUziNPxy3YtBSN+xu3bjDcIG2qv7NqtYRMGTqXi6rZ5KgYlgcPq3rpqHgUziNPxy3YtBSN+xu3bjDcIG2qv7NqtYRMGTqXi6rV5KgYlgMPq3rpqHwUziNPxy3YtBSN9xe3bjDcIG2mvqt" preload="auto"></audio>
    <audio id="sound-error" src="data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBSuFzu/eijkIGmea7+aaYhMFUKvl47h1KgYpgsnq3rpqHgU0idPxy3YtBSN+xu3bjDcIG2qw7NqtYRMGTqXi6rV5KgYlgcPq3rpqHgUziNPxy3YtBSN+xu3bjDcIG2qw7NqtYRMGTqXi6rV5KgYlgcPq3rpqHgUziNPxy3YtBSN+xu3bjDcIG2qw7NqtYRMGTqXi6rV5KgYlgcPq3rpqHgUziNPxy3YtBSN+xu3bjDcIG2qw7NqtYRMGTqXi6rV5KgYlgcPq3rpqHgUziNPxy3YtBSN+xu3bjDcIG2qw7NqtYRMGTqXi6rV5KgYlgcPq3rpqHgUziNPxy3YtBSN+xu3bjDcIG2qw7NqtYRMGTqXi6rV5Kg" preload="auto"></audio>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <script>
        const API_BASE_URL = '/api';
        let html5QrCode;
        let soundEnabled = true;
        let lastScanTime = 0;
        const SCAN_COOLDOWN = 3000; // 3 secondes entre chaque scan

        // Initialisation
        document.addEventListener('DOMContentLoaded', function() {
            initScanner();
            loadStats();
            setupSoundToggle();
            
            // Rafraîchir les stats toutes les 30 secondes
            setInterval(loadStats, 30000);
        });

        function initScanner() {
            html5QrCode = new Html5Qrcode("reader");
            
            const config = {
                fps: 10,
                qrbox: { width: 250, height: 250 },
                aspectRatio: 1.0
            };

            html5QrCode.start(
                { facingMode: "environment" },
                config,
                onScanSuccess,
                onScanError
            ).catch(err => {
                console.error("Erreur démarrage scanner:", err);
                showError("Impossible d'accéder à la caméra");
            });

            // Toggle scanner
            document.getElementById('scannerToggle').addEventListener('change', function(e) {
                if (e.target.checked) {
                    html5QrCode.resume();
                } else {
                    html5QrCode.pause();
                }
            });
        }

        function onScanSuccess(decodedText, decodedResult) {
            const now = Date.now();
            if (now - lastScanTime < SCAN_COOLDOWN) {
                return; // Ignorer les scans trop rapprochés
            }
            lastScanTime = now;

            // Pause le scanner pendant la validation
            html5QrCode.pause();

            try {
                const qrData = JSON.parse(decodedText);
                validateQRCode(qrData);
            } catch (e) {
                console.error("QR invalide:", e);
                showError("QR code invalide ou corrompu");
                setTimeout(() => html5QrCode.resume(), 2000);
            }
        }

        function onScanError(error) {
            // Ignorer les erreurs de scan normales
        }

        async function validateQRCode(qrData) {
            try {
                const response = await fetch(`${API_BASE_URL}/scan/validate`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(qrData)
                });

                const result = await response.json();

                if (response.ok && result.status === 'success') {
                    displayValidationResult(result.data);
                    
                    // Si accès accordé, enregistrer le check-in automatiquement
                    if (result.data.access_granted) {
                        await performCheckIn(result.data.booking.id);
                    }
                } else {
                    showError(result.message || 'Erreur de validation');
                }

            } catch (error) {
                console.error("Erreur validation:", error);
                showError("Erreur de connexion au serveur");
            }

            // Reprendre le scan après 3 secondes
            setTimeout(() => {
                document.getElementById('scan-result-container').innerHTML = '';
                html5QrCode.resume();
            }, 3000);
        }

        async function performCheckIn(bookingId) {
            try {
                const response = await fetch(`${API_BASE_URL}/scan/checkin`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ booking_id: bookingId })
                });

                const result = await response.json();
                
                if (result.status === 'success') {
                    console.log('Check-in réussi:', result.data);
                    loadStats(); // Rafraîchir les statistiques
                }

            } catch (error) {
                console.error("Erreur check-in:", error);
            }
        }

        function displayValidationResult(data) {
            const container = document.getElementById('scan-result-container');
            
            const statusClass = data.access_granted ? 'access-granted' : 
                               (data.booking.status === 'cancelled' ? 'access-denied' : 'access-warning');
            
            const icon = data.access_granted ? 'check-circle-fill' : 'x-circle-fill';
            
            playSound(data.access_granted ? 'success' : 'error');

            container.innerHTML = `
                <div class="${statusClass}">
                    <i class="bi bi-${icon}" style="font-size: 3rem;"></i>
                    <h4 class="mt-3">${data.access_message}</h4>
                    
                    <div class="mt-4 text-start">
                        <strong>${data.booking.customer_name}</strong><br>
                        <small>${data.booking.game_name} - ${data.booking.room_name}</small><br>
                        <small>${data.booking.start_time} - ${data.booking.end_time}</small><br>
                        <small>Joueurs: ${data.participants.checked_in}/${data.participants.total}</small>
                    </div>
                </div>
            `;
        }

        function showError(message) {
            const container = document.getElementById('scan-result-container');
            playSound('error');
            
            container.innerHTML = `
                <div class="access-denied">
                    <i class="bi bi-exclamation-triangle-fill" style="font-size: 3rem;"></i>
                    <h4 class="mt-3">Erreur</h4>
                    <p>${message}</p>
                </div>
            `;
        }

        async function loadStats() {
            try {
                const response = await fetch(`${API_BASE_URL}/scan/stats`);
                const result = await response.json();

                if (result.status === 'success') {
                    const stats = result.data.stats;
                    
                    document.getElementById('stat-today').textContent = stats.total_bookings;
                    document.getElementById('stat-active').textContent = stats.in_progress;
                    document.getElementById('stat-completed').textContent = stats.completed;
                    document.getElementById('stat-participants').textContent = 
                        `${stats.checked_in_participants}/${stats.total_participants}`;

                    // Afficher les prochaines réservations
                    displayUpcomingBookings(result.data.upcoming_bookings);
                }

            } catch (error) {
                console.error("Erreur chargement stats:", error);
            }
        }

        function displayUpcomingBookings(bookings) {
            const container = document.getElementById('upcoming-bookings');
            
            if (bookings.length === 0) {
                container.innerHTML = '<p class="text-muted text-center">Aucune réservation à venir</p>';
                return;
            }

            container.innerHTML = bookings.map(b => `
                <div class="upcoming-item">
                    <strong>${b.start_time}</strong> - ${b.customer_name}
                    <br><small>${b.game_name} (${b.num_players} joueurs)</small>
                </div>
            `).join('');
        }

        function setupSoundToggle() {
            document.getElementById('soundToggle').addEventListener('click', function() {
                soundEnabled = !soundEnabled;
                const icon = document.getElementById('soundIcon');
                icon.className = soundEnabled ? 'bi bi-volume-up fs-4' : 'bi bi-volume-mute fs-4';
            });
        }

        function playSound(type) {
            if (!soundEnabled) return;
            
            const sound = document.getElementById(`sound-${type}`);
            if (sound) {
                sound.currentTime = 0;
                sound.play().catch(e => console.log("Erreur lecture son:", e));
            }
        }
    </script>
</body>
</html>
