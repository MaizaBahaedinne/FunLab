<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Équipes - Réservation #<?= $booking['id'] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        .teams-container {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            padding: 20px 0;
        }
        .team-card {
            flex: 1;
            min-width: 300px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .team-header {
            padding: 15px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .team-body {
            padding: 15px;
            min-height: 200px;
            background: #f8f9fa;
        }
        .participant-item {
            background: white;
            border: 2px solid #dee2e6;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 10px;
            cursor: move;
            transition: all 0.3s;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .participant-item:hover {
            border-color: #667eea;
            box-shadow: 0 2px 8px rgba(102,126,234,0.2);
            transform: translateY(-2px);
        }
        .participant-item.dragging {
            opacity: 0.5;
            transform: rotate(2deg);
        }
        .team-body.drag-over {
            background: #e7f0ff;
            border: 2px dashed #667eea;
        }
        .unassigned-zone {
            background: white;
            border: 2px dashed #dee2e6;
            border-radius: 10px;
            padding: 20px;
            min-height: 150px;
        }
        .unassigned-zone.drag-over {
            background: #fff3cd;
            border-color: #ffc107;
        }
        .team-counter {
            background: rgba(255,255,255,0.2);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
        }
        .color-picker-btn {
            width: 30px;
            height: 30px;
            border: 2px solid white;
            border-radius: 50%;
            cursor: pointer;
        }
        .add-team-btn {
            min-width: 300px;
            height: 200px;
            border: 2px dashed #dee2e6;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s;
            background: white;
        }
        .add-team-btn:hover {
            border-color: #667eea;
            background: #f0f4ff;
        }
    </style>
</head>
<body class="bg-light">
    <!-- Sidebar -->
    <div class="d-flex">
        <div class="bg-dark text-white p-3" style="width: 250px; min-height: 100vh;">
            <h4 class="mb-4">
                <i class="bi bi-speedometer2"></i> Admin FunLab
            </h4>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link text-white" href="<?= base_url('admin/dashboard') ?>">
                        <i class="bi bi-house"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white active" href="<?= base_url('admin/bookings') ?>">
                        <i class="bi bi-calendar-check"></i> Réservations
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="<?= base_url('admin/rooms') ?>">
                        <i class="bi bi-door-closed"></i> Salles
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="<?= base_url('admin/games') ?>">
                        <i class="bi bi-joystick"></i> Jeux
                    </a>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="flex-grow-1 p-4">
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2><i class="bi bi-diagram-3"></i> Gestion des Équipes</h2>
                        <p class="text-muted mb-0">Réservation #<?= $booking['id'] ?> - <?= $booking['num_players'] ?> joueurs</p>
                    </div>
                    <div>
                        <a href="<?= base_url('admin/bookings/view/' . $booking['id']) ?>" class="btn btn-outline-secondary me-2">
                            <i class="bi bi-arrow-left"></i> Retour
                        </a>
                        <button class="btn btn-primary" onclick="addTeam()">
                            <i class="bi bi-plus-circle"></i> Nouvelle Équipe
                        </button>
                    </div>
                </div>

                <!-- Participants Non Assignés -->
                <div class="mb-4">
                    <h5><i class="bi bi-people"></i> Participants Non Assignés (<?= count($unassignedParticipants) ?>)</h5>
                    <div id="unassigned-zone" class="unassigned-zone">
                        <?php if (empty($unassignedParticipants)): ?>
                            <p class="text-muted text-center mb-0">
                                <i class="bi bi-check-circle"></i> Tous les participants sont assignés
                            </p>
                        <?php else: ?>
                            <?php foreach ($unassignedParticipants as $participant): ?>
                                <div class="participant-item" 
                                     draggable="true" 
                                     data-participant-id="<?= $participant['id'] ?>"
                                     data-team-id="">
                                    <div>
                                        <strong><?= esc($participant['first_name'] ?? $participant['name']) ?> <?= esc($participant['last_name'] ?? '') ?></strong>
                                        <?php if (!empty($participant['email'])): ?>
                                            <br><small class="text-muted"><?= esc($participant['email']) ?></small>
                                        <?php endif; ?>
                                    </div>
                                    <i class="bi bi-grip-vertical text-muted"></i>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Équipes -->
                <h5><i class="bi bi-diagram-3-fill"></i> Équipes (<?= count($teams) ?>)</h5>
                <div class="teams-container" id="teams-container">
                    <?php foreach ($teams as $team): ?>
                        <div class="team-card" data-team-id="<?= $team['id'] ?>">
                            <div class="team-header" style="background: <?= esc($team['color']) ?>;">
                                <div>
                                    <h6 class="mb-0" contenteditable="true" 
                                        onblur="updateTeamName(<?= $team['id'] ?>, this.textContent)">
                                        <?= esc($team['name']) ?>
                                    </h6>
                                    <span class="team-counter"><?= count($team['participants']) ?> membres</span>
                                </div>
                                <div class="d-flex gap-2">
                                    <input type="color" 
                                           class="color-picker-btn" 
                                           value="<?= esc($team['color']) ?>"
                                           onchange="updateTeamColor(<?= $team['id'] ?>, this.value)">
                                    <button class="btn btn-sm btn-light" onclick="deleteTeam(<?= $team['id'] ?>)">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="team-body" data-team-id="<?= $team['id'] ?>">
                                <?php if (empty($team['participants'])): ?>
                                    <p class="text-muted text-center">Glissez des participants ici</p>
                                <?php else: ?>
                                    <?php foreach ($team['participants'] as $participant): ?>
                                        <div class="participant-item" 
                                             draggable="true" 
                                             data-participant-id="<?= $participant['id'] ?>"
                                             data-team-id="<?= $team['id'] ?>">
                                            <div>
                                                <strong><?= esc($participant['first_name'] ?? $participant['name']) ?> <?= esc($participant['last_name'] ?? '') ?></strong>
                                                <?php if (!empty($participant['email'])): ?>
                                                    <br><small class="text-muted"><?= esc($participant['email']) ?></small>
                                                <?php endif; ?>
                                            </div>
                                            <i class="bi bi-grip-vertical text-muted"></i>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <!-- Bouton Ajouter Équipe -->
                    <div class="add-team-btn" onclick="addTeam()">
                        <div class="text-center">
                            <i class="bi bi-plus-circle fs-1 text-primary"></i>
                            <p class="text-muted mb-0">Ajouter une équipe</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const bookingId = <?= $booking['id'] ?>;
        const API_BASE_URL = '<?= base_url() ?>';

        // Drag & Drop Handlers
        let draggedElement = null;

        document.addEventListener('DOMContentLoaded', function() {
            initDragAndDrop();
        });

        function initDragAndDrop() {
            // Initialiser tous les participants comme draggables
            document.querySelectorAll('.participant-item').forEach(item => {
                item.addEventListener('dragstart', handleDragStart);
                item.addEventListener('dragend', handleDragEnd);
            });

            // Initialiser les zones de drop
            document.querySelectorAll('.team-body, #unassigned-zone').forEach(zone => {
                zone.addEventListener('dragover', handleDragOver);
                zone.addEventListener('dragleave', handleDragLeave);
                zone.addEventListener('drop', handleDrop);
            });
        }

        function handleDragStart(e) {
            draggedElement = e.target;
            e.target.classList.add('dragging');
            e.dataTransfer.effectAllowed = 'move';
        }

        function handleDragEnd(e) {
            e.target.classList.remove('dragging');
            document.querySelectorAll('.drag-over').forEach(zone => {
                zone.classList.remove('drag-over');
            });
        }

        function handleDragOver(e) {
            e.preventDefault();
            e.dataTransfer.dropEffect = 'move';
            e.currentTarget.classList.add('drag-over');
        }

        function handleDragLeave(e) {
            e.currentTarget.classList.remove('drag-over');
        }

        async function handleDrop(e) {
            e.preventDefault();
            e.currentTarget.classList.remove('drag-over');

            if (!draggedElement) return;

            const participantId = draggedElement.dataset.participantId;
            const targetZone = e.currentTarget;
            const newTeamId = targetZone.dataset.teamId || null;

            // Déplacer visuellement
            targetZone.appendChild(draggedElement);
            draggedElement.dataset.teamId = newTeamId || '';

            // Mettre à jour le compteur
            updateTeamCounters();

            // Envoyer au serveur
            await assignParticipant(participantId, newTeamId);
        }

        async function assignParticipant(participantId, teamId) {
            try {
                const response = await fetch(`${API_BASE_URL}/admin/teams/assign-participant`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams({
                        participant_id: participantId,
                        team_id: teamId || ''
                    })
                });

                const result = await response.json();
                if (result.status !== 'success') {
                    alert('Erreur lors de l\'assignation');
                    location.reload();
                }
            } catch (error) {
                console.error('Erreur:', error);
                alert('Erreur de connexion');
            }
        }

        async function addTeam() {
            const name = prompt('Nom de l\'équipe:');
            if (!name) return;

            const colors = ['#667eea', '#764ba2', '#f093fb', '#4facfe', '#00f2fe', '#43e97b', '#fa709a'];
            const randomColor = colors[Math.floor(Math.random() * colors.length)];

            try {
                const response = await fetch(`${API_BASE_URL}/admin/teams/create`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams({
                        booking_id: bookingId,
                        name: name,
                        color: randomColor
                    })
                });

                const result = await response.json();
                if (result.status === 'success') {
                    location.reload();
                } else {
                    alert('Erreur: ' + result.message);
                }
            } catch (error) {
                console.error('Erreur:', error);
                alert('Erreur de connexion');
            }
        }

        async function updateTeamName(teamId, newName) {
            if (!newName.trim()) {
                alert('Le nom ne peut pas être vide');
                location.reload();
                return;
            }

            try {
                await fetch(`${API_BASE_URL}/admin/teams/update/${teamId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams({
                        name: newName
                    })
                });
            } catch (error) {
                console.error('Erreur:', error);
            }
        }

        async function updateTeamColor(teamId, newColor) {
            const header = event.target.closest('.team-header');
            header.style.background = newColor;

            try {
                await fetch(`${API_BASE_URL}/admin/teams/update/${teamId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams({
                        color: newColor
                    })
                });
            } catch (error) {
                console.error('Erreur:', error);
            }
        }

        async function deleteTeam(teamId) {
            if (!confirm('Supprimer cette équipe ? Les participants seront désassignés.')) {
                return;
            }

            try {
                const response = await fetch(`${API_BASE_URL}/admin/teams/delete/${teamId}`, {
                    method: 'POST'
                });

                const result = await response.json();
                if (result.status === 'success') {
                    location.reload();
                }
            } catch (error) {
                console.error('Erreur:', error);
                alert('Erreur de connexion');
            }
        }

        function updateTeamCounters() {
            document.querySelectorAll('.team-card').forEach(card => {
                const teamBody = card.querySelector('.team-body');
                const counter = card.querySelector('.team-counter');
                const count = teamBody.querySelectorAll('.participant-item').length;
                counter.textContent = count + ' membre' + (count > 1 ? 's' : '');
            });

            // Mettre à jour la zone non assignée
            const unassignedZone = document.getElementById('unassigned-zone');
            const unassignedCount = unassignedZone.querySelectorAll('.participant-item').length;
            const title = unassignedZone.previousElementSibling;
            title.innerHTML = `<i class="bi bi-people"></i> Participants Non Assignés (${unassignedCount})`;
        }
    </script>
</body>
</html>
