<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - <?= esc($booking['game_name']) ?> - FunLab</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 20px;
        }
        .registration-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            max-width: 600px;
            margin: 0 auto;
        }
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 20px 20px 0 0;
            padding: 30px;
        }
        .participant-item {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
        }
        .participant-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            margin-right: 12px;
        }
        .progress-circle {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: conic-gradient(#667eea 0%, #764ba2 var(--progress, 0%), #e9ecef var(--progress, 0%));
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }
        .progress-circle::before {
            content: '';
            width: 65px;
            height: 65px;
            border-radius: 50%;
            background: white;
            position: absolute;
        }
        .progress-text {
            position: relative;
            z-index: 1;
            font-weight: bold;
            font-size: 18px;
        }
        .btn-register {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px 30px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="registration-card">
            <div class="card-header text-center">
                <h3 class="mb-2"><i class="bi bi-controller"></i> FunLab Tunisie</h3>
                <h5><?= esc($booking['game_name']) ?></h5>
                <p class="mb-0">
                    <i class="bi bi-calendar3"></i> 
                    <?php
                    $date = new DateTime($booking['booking_date']);
                    echo $date->format('d/m/Y');
                    ?>
                    à <?= date('H:i', strtotime($booking['start_time'])) ?>
                </p>
            </div>

            <div class="card-body p-4">
                <!-- Info Session -->
                <div class="alert alert-info mb-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong><i class="bi bi-door-closed"></i> Salle:</strong> <?= esc($booking['room_name']) ?><br>
                            <strong><i class="bi bi-clock"></i> Durée:</strong> 
                            <?= date('H:i', strtotime($booking['start_time'])) ?> - <?= date('H:i', strtotime($booking['end_time'])) ?>
                        </div>
                        <div class="progress-circle" id="progress-circle">
                            <span class="progress-text" id="progress-text">
                                <?= $participantsCount ?>/<?= $booking['num_players'] ?>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Formulaire d'inscription -->
                <div class="mb-4">
                    <h5><i class="bi bi-person-plus"></i> Inscrivez-vous</h5>
                    <form id="registration-form">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Prénom *</label>
                                <input type="text" name="first_name" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nom *</label>
                                <input type="text" name="last_name" class="form-control" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email (optionnel)</label>
                            <input type="email" name="email" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Téléphone (optionnel)</label>
                            <input type="tel" name="phone" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-register btn-primary w-100">
                            <i class="bi bi-check-circle"></i> S'inscrire
                        </button>
                    </form>
                </div>

                <!-- Liste des participants -->
                <div id="participants-section">
                    <h5 class="mb-3">
                        <i class="bi bi-people-fill"></i> 
                        Participants inscrits (<span id="participants-count"><?= $participantsCount ?></span>)
                    </h5>
                    <div id="participants-list">
                        <?php if (empty($participants)): ?>
                            <p class="text-muted text-center">Soyez le premier à vous inscrire !</p>
                        <?php else: ?>
                            <?php foreach ($participants as $participant): ?>
                                <div class="participant-item">
                                    <div class="participant-avatar">
                                        <?= strtoupper(substr($participant['first_name'], 0, 1)) ?>
                                    </div>
                                    <div>
                                        <strong><?= esc($participant['first_name'] . ' ' . $participant['last_name']) ?></strong>
                                        <?php if (!empty($participant['email'])): ?>
                                            <br><small class="text-muted"><?= esc($participant['email']) ?></small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <small class="text-muted">
                        <i class="bi bi-shield-check"></i> Vos données sont sécurisées
                    </small>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const token = '<?= $token ?>';
        const maxParticipants = <?= $booking['num_players'] ?>;
        const API_BASE_URL = '<?= base_url() ?>';

        // Soumettre le formulaire
        document.getElementById('registration-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const formData = new FormData(e.target);
            const btn = e.target.querySelector('button[type="submit"]');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Inscription...';

            try {
                const response = await fetch(`${API_BASE_URL}/register/${token}/submit`, {
                    method: 'POST',
                    body: new URLSearchParams(formData)
                });

                const result = await response.json();

                if (result.status === 'success') {
                    // Réinitialiser le formulaire
                    e.target.reset();
                    
                    // Afficher un message de succès
                    const alert = document.createElement('div');
                    alert.className = 'alert alert-success alert-dismissible fade show';
                    alert.innerHTML = `
                        <i class="bi bi-check-circle"></i> ${result.message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    `;
                    e.target.parentElement.insertBefore(alert, e.target);

                    // Actualiser la liste des participants
                    loadParticipants();
                } else {
                    alert(result.message);
                }

                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-check-circle"></i> S\'inscrire';

            } catch (error) {
                console.error('Erreur:', error);
                alert('Erreur de connexion');
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-check-circle"></i> S\'inscrire';
            }
        });

        // Charger les participants
        async function loadParticipants() {
            try {
                const response = await fetch(`${API_BASE_URL}/register/${token}/participants`);
                const result = await response.json();

                if (result.status === 'success') {
                    updateParticipantsList(result.participants);
                    updateProgress(result.total, result.max);
                }
            } catch (error) {
                console.error('Erreur:', error);
            }
        }

        function updateParticipantsList(participants) {
            const list = document.getElementById('participants-list');
            document.getElementById('participants-count').textContent = participants.length;

            if (participants.length === 0) {
                list.innerHTML = '<p class="text-muted text-center">Soyez le premier à vous inscrire !</p>';
            } else {
                list.innerHTML = participants.map(p => `
                    <div class="participant-item">
                        <div class="participant-avatar">
                            ${p.first_name.charAt(0).toUpperCase()}
                        </div>
                        <div>
                            <strong>${p.first_name} ${p.last_name}</strong>
                            ${p.email ? `<br><small class="text-muted">${p.email}</small>` : ''}
                        </div>
                    </div>
                `).join('');
            }
        }

        function updateProgress(current, max) {
            const percentage = (current / max) * 100;
            document.getElementById('progress-circle').style.setProperty('--progress', percentage + '%');
            document.getElementById('progress-text').textContent = `${current}/${max}`;

            // Désactiver le formulaire si complet
            if (current >= max) {
                const form = document.getElementById('registration-form');
                form.innerHTML = '<div class="alert alert-warning text-center"><i class="bi bi-exclamation-triangle"></i> Le nombre maximum de participants est atteint</div>';
            }
        }

        // Calculer le pourcentage initial
        const currentCount = <?= $participantsCount ?>;
        const percentage = (currentCount / maxParticipants) * 100;
        document.getElementById('progress-circle').style.setProperty('--progress', percentage + '%');

        // Actualiser toutes les 10 secondes
        setInterval(loadParticipants, 10000);
    </script>
</body>
</html>
