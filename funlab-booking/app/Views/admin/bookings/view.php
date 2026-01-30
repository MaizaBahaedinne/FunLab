<?php
$title = 'Réservation #' . $booking['id'];
$pageTitle = 'Détails Réservation #' . $booking['id'];
$activeMenu = 'bookings';
$breadcrumbs = ['Admin' => base_url('admin'), 'Réservations' => base_url('admin/bookings'), 'Détails' => null];
$additionalStyles = '
.detail-card {
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    padding: 20px;
    margin-bottom: 20px;
}
.status-badge {
    padding: 8px 15px;
    border-radius: 20px;
    font-size: 14px;
}
.action-btn {
    margin: 5px;
}
.participant-item {
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 10px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
';
?>

<?= view('admin/layouts/header', compact('title', 'additionalStyles')) ?>
<?= view('admin/layouts/sidebar', compact('activeMenu')) ?>
<?= view('admin/layouts/topbar', compact('pageTitle', 'breadcrumbs')) ?>

                <div class="mb-4">
                    <a href="<?= base_url('admin/bookings') ?>" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Retour
                    </a>
                </div>

                <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <div class="row">
                    <!-- Colonne Gauche -->
                    <div class="col-lg-8">
                        <!-- Informations Générales -->
                        <div class="detail-card">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5><i class="bi bi-info-circle"></i> Informations Générales</h5>
                                <?php
                                $statusClasses = [
                                    'pending' => 'bg-warning text-dark',
                                    'confirmed' => 'bg-success',
                                    'in_progress' => 'bg-info',
                                    'completed' => 'bg-secondary',
                                    'cancelled' => 'bg-danger'
                                ];
                                $statusLabels = [
                                    'pending' => 'En attente',
                                    'confirmed' => 'Confirmé',
                                    'in_progress' => 'En cours',
                                    'completed' => 'Terminé',
                                    'cancelled' => 'Annulé'
                                ];
                                ?>
                                <span class="badge status-badge <?= $statusClasses[$booking['status']] ?? 'bg-secondary' ?>">
                                    <?= $statusLabels[$booking['status']] ?? 'Inconnu' ?>
                                </span>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong><i class="bi bi-joystick"></i> Jeu:</strong><br>
                                    <span class="fs-5"><?= esc($booking['game_name']) ?></span>
                                </div>
                                <div class="col-md-6">
                                    <strong><i class="bi bi-door-closed"></i> Salle:</strong><br>
                                    <span class="fs-5"><?= esc($booking['room_name']) ?></span>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong><i class="bi bi-calendar3"></i> Date:</strong><br>
                                    <?php
                                    $date = new DateTime($booking['booking_date']);
                                    echo $date->format('d/m/Y');
                                    ?>
                                </div>
                                <div class="col-md-6">
                                    <strong><i class="bi bi-clock"></i> Horaire:</strong><br>
                                    <?= date('H:i', strtotime($booking['start_time'])) ?> - 
                                    <?= date('H:i', strtotime($booking['end_time'])) ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <strong><i class="bi bi-people"></i> Nombre de joueurs:</strong><br>
                                    <?= esc($booking['num_players']) ?> joueurs
                                </div>
                                <div class="col-md-6">
                                    <strong><i class="bi bi-cash-coin"></i> Prix total:</strong><br>
                                    <span class="fs-4 text-primary"><?= number_format($booking['total_price'], 2) ?> TND</span>
                                </div>
                            </div>
                        </div>

                        <!-- Client -->
                        <div class="detail-card">
                            <h5><i class="bi bi-person"></i> Informations Client</h5>
                            <hr>
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <strong>Nom:</strong><br>
                                    <?= esc($booking['customer_name']) ?>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <strong>Email:</strong><br>
                                    <a href="mailto:<?= esc($booking['customer_email']) ?>">
                                        <?= esc($booking['customer_email']) ?>
                                    </a>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <strong>Téléphone:</strong><br>
                                    <a href="tel:<?= esc($booking['customer_phone']) ?>">
                                        <?= esc($booking['customer_phone']) ?>
                                    </a>
                                </div>
                                <?php if (!empty($booking['user_id'])): ?>
                                <div class="col-md-6 mb-2">
                                    <strong>Compte utilisateur:</strong><br>
                                    <span class="badge bg-info">
                                        <i class="bi bi-person-check"></i> Compte lié (ID: <?= $booking['user_id'] ?>)
                                    </span>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Participants & Équipes -->
                        <div class="detail-card">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5>
                                    <i class="bi bi-people-fill"></i> 
                                    Participants 
                                    <?php 
                                    $totalParticipants = count($unassignedParticipants);
                                    foreach ($teams as $team) {
                                        $totalParticipants += count($team['participants']);
                                    }
                                    ?>
                                    (<?= $totalParticipants ?>)
                                </h5>
                                <div>
                                    <a href="<?= base_url('admin/teams/manage/' . $booking['id']) ?>" class="btn btn-sm btn-info me-2">
                                        <i class="bi bi-diagram-3"></i> Gérer Équipes
                                    </a>
                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addParticipantModal">
                                        <i class="bi bi-plus-circle"></i> Ajouter
                                    </button>
                                </div>
                            </div>

                            <?php if (empty($teams) && empty($unassignedParticipants)): ?>
                                <p class="text-muted">Aucun participant enregistré</p>
                            <?php else: ?>
                                <!-- Afficher les équipes -->
                                <?php if (!empty($teams)): ?>
                                    <?php foreach ($teams as $team): ?>
                                        <div class="mb-3">
                                            <div class="d-flex align-items-center mb-2">
                                                <div class="me-2" style="width: 20px; height: 20px; background-color: <?= esc($team['color']) ?>; border-radius: 50%;"></div>
                                                <h6 class="mb-0">
                                                    <i class="bi bi-diagram-3"></i> 
                                                    <?= esc($team['name']) ?>
                                                    <span class="badge bg-secondary ms-2"><?= count($team['participants']) ?> membres</span>
                                                </h6>
                                            </div>
                                            
                                            <?php if (!empty($team['participants'])): ?>
                                                <div style="margin-left: 32px;">
                                                    <?php foreach ($team['participants'] as $participant): ?>
                                                    <div class="participant-item" style="border-left: 3px solid <?= esc($team['color']) ?>;">
                                                        <div>
                                                            <strong><?= esc(($participant['first_name'] ?? '') . ' ' . ($participant['last_name'] ?? '')) ?></strong><br>
                                                            <small class="text-muted">
                                                                <?= esc($participant['email'] ?? 'Pas d\'email') ?> 
                                                                <?php if (!empty($participant['phone'])): ?>
                                                                    | <?= esc($participant['phone']) ?>
                                                                <?php endif; ?>
                                                            </small>
                                                        </div>
                                                    </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>

                                <!-- Participants non assignés -->
                                <?php if (!empty($unassignedParticipants)): ?>
                                    <div class="mb-3">
                                        <h6 class="text-muted">
                                            <i class="bi bi-person"></i> Sans équipe
                                            <span class="badge bg-secondary ms-2"><?= count($unassignedParticipants) ?></span>
                                        </h6>
                                        <div style="margin-left: 32px;">
                                            <?php foreach ($unassignedParticipants as $participant): ?>
                                            <div class="participant-item">
                                                <div>
                                                    <strong><?= esc(($participant['first_name'] ?? '') . ' ' . ($participant['last_name'] ?? '')) ?></strong><br>
                                                    <small class="text-muted">
                                                        <?= esc($participant['email'] ?? 'Pas d\'email') ?> 
                                                        <?php if (!empty($participant['phone'])): ?>
                                                            | <?= esc($participant['phone']) ?>
                                                        <?php endif; ?>
                                                    </small>
                                                </div>
                                                <a href="<?= base_url('admin/bookings/delete-participant/' . $participant['id']) ?>" 
                                                   class="btn btn-sm btn-outline-danger"
                                                   onclick="event.preventDefault(); confirmDelete(this.href, \'Supprimer ce participant \?\', \'Le participant sera supprimé définitivement\')">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Colonne Droite -->
                    <div class="col-lg-4">
                        <!-- Actions Rapides -->
                        <div class="detail-card">
                            <h5><i class="bi bi-lightning-charge"></i> Actions Rapides</h5>
                            <hr>

                            <!-- Changer Statut -->
                            <form method="POST" action="<?= base_url('admin/bookings/update-status/' . $booking['id']) ?>" class="mb-3">
                                <?= csrf_field() ?>
                                <label class="form-label">Changer le statut:</label>
                                <select name="status" class="form-select mb-2">
                                    <option value="pending" <?= $booking['status'] === 'pending' ? 'selected' : '' ?>>En attente</option>
                                    <option value="confirmed" <?= $booking['status'] === 'confirmed' ? 'selected' : '' ?>>Confirmé</option>
                                    <option value="in_progress" <?= $booking['status'] === 'in_progress' ? 'selected' : '' ?>>En cours</option>
                                    <option value="completed" <?= $booking['status'] === 'completed' ? 'selected' : '' ?>>Terminé</option>
                                    <option value="cancelled" <?= $booking['status'] === 'cancelled' ? 'selected' : '' ?>>Annulé</option>
                                </select>
                                <button type="submit" class="btn btn-sm btn-primary w-100">
                                    <i class="bi bi-check-circle"></i> Mettre à jour
                                </button>
                            </form>

                            <div class="d-grid gap-2">
                                <?php if ($booking['status'] !== 'cancelled'): ?>
                                <a href="<?= base_url('admin/bookings/cancel/' . $booking['id']) ?>" 
                                   class="btn btn-outline-danger"
                                   onclick="event.preventDefault(); confirmDelete(this.href, \'Annuler cette réservation \?\', \'La réservation sera annulée\', \'Oui, annuler\')">
                                    <i class="bi bi-x-circle"></i> Annuler
                                </a>
                                <?php endif; ?>

                                <a href="<?= base_url('admin/bookings/delete/' . $booking['id']) ?>" 
                                   class="btn btn-outline-danger"
                                   onclick="event.preventDefault(); confirmDelete(this.href, \'Supprimer définitivement \?\', \'Cette action est irréversible\')">
                                    <i class="bi bi-trash"></i> Supprimer
                                </a>
                            </div>
                        </div>

                        <!-- Paiement -->
                        <div class="detail-card">
                            <h5><i class="bi bi-credit-card"></i> Paiement</h5>
                            <hr>

                            <form method="POST" action="<?= base_url('admin/bookings/update-payment/' . $booking['id']) ?>">
                                <?= csrf_field() ?>
                                
                                <div class="mb-3">
                                    <label class="form-label">Mode de paiement:</label>
                                    <select name="payment_method" class="form-select">
                                        <option value="">-- Sélectionner --</option>
                                        <option value="card" <?= ($booking['payment_method'] ?? '') === 'card' ? 'selected' : '' ?>>Carte bancaire</option>
                                        <option value="cash" <?= ($booking['payment_method'] ?? '') === 'cash' ? 'selected' : '' ?>>Espèces</option>
                                        <option value="bank_transfer" <?= ($booking['payment_method'] ?? '') === 'bank_transfer' ? 'selected' : '' ?>>Virement</option>
                                        <option value="stripe" <?= ($booking['payment_method'] ?? '') === 'stripe' ? 'selected' : '' ?>>Stripe</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Statut paiement:</label>
                                    <select name="payment_status" class="form-select">
                                        <option value="pending" <?= ($payment['status'] ?? '') === 'pending' ? 'selected' : '' ?>>En attente</option>
                                        <option value="completed" <?= ($payment['status'] ?? '') === 'completed' ? 'selected' : '' ?>>Payé</option>
                                        <option value="failed" <?= ($payment['status'] ?? '') === 'failed' ? 'selected' : '' ?>>Échoué</option>
                                        <option value="refunded" <?= ($payment['status'] ?? '') === 'refunded' ? 'selected' : '' ?>>Remboursé</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <strong>Montant:</strong>
                                    <div class="fs-4 text-primary"><?= number_format($booking['total_price'], 2) ?> TND</div>
                                </div>

                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-save"></i> Enregistrer paiement
                                </button>
                            </form>
                        </div>

                        <!-- QR Code Inscription -->
                        <?php if (!empty($booking['registration_token'])): ?>
                        <div class="detail-card text-center">
                            <h5><i class="bi bi-qr-code-scan"></i> QR Code d'Inscription</h5>
                            <hr>
                            <?php 
                            $registrationUrl = base_url('register/' . $booking['registration_token']);
                            $qrCodeUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=' . urlencode($registrationUrl);
                            ?>
                            <img src="<?= $qrCodeUrl ?>" alt="QR Code Inscription" class="img-fluid mb-3" style="max-width: 250px;">
                            <p class="small text-muted mb-2">Scannez pour vous inscrire</p>
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control" value="<?= $registrationUrl ?>" id="registration-link" readonly>
                                <button class="btn btn-outline-secondary" onclick="copyLink()">
                                    <i class="bi bi-clipboard"></i>
                                </button>
                            </div>
                            <small class="text-danger d-block mt-2">
                                <i class="bi bi-clock"></i> Expire à la fin de la session
                            </small>
                        </div>
                        <?php endif; ?>

                        <!-- QR Code Ticket -->
                        <?php if (!empty($booking['qr_code'])): ?>
                        <div class="detail-card text-center">
                            <h5><i class="bi bi-qr-code"></i> QR Code Ticket</h5>
                            <hr>
                            <img src="<?= esc($booking['qr_code']) ?>" alt="QR Code" class="img-fluid" style="max-width: 200px;">
                        </div>
                        <?php endif; ?>

                        <!-- Historique -->
                        <div class="detail-card">
                            <h5><i class="bi bi-clock-history"></i> Historique</h5>
                            <hr>
                            <small class="text-muted">
                                <strong>Créé le:</strong><br>
                                <?php
                                $createdAt = new DateTime($booking['created_at']);
                                echo $createdAt->format('d/m/Y à H:i');
                                ?>
                            </small>
                            <?php if (!empty($booking['updated_at'])): ?>
                            <br><br>
                            <small class="text-muted">
                                <strong>Modifié le:</strong><br>
                                <?php
                                $updatedAt = new DateTime($booking['updated_at']);
                                echo $updatedAt->format('d/m/Y à H:i');
                                ?>
                            </small>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

    <!-- Modal Ajouter Participant -->
    <div class="modal fade" id="addParticipantModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ajouter un Participant</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="<?= base_url('admin/bookings/add-participant/' . $booking['id']) ?>">
                    <?= csrf_field() ?>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Prénom *</label>
                            <input type="text" name="first_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nom *</label>
                            <input type="text" name="last_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Téléphone</label>
                            <input type="tel" name="phone" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Ajouter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php
$additionalJS = '
<script>
    function copyLink() {
        const input = document.getElementById("registration-link");
        input.select();
        document.execCommand("copy");
        
        const btn = event.target.closest("button");
        const originalHtml = btn.innerHTML;
        btn.innerHTML = "<i class=\"bi bi-check\"></i>";
        
        setTimeout(() => {
            btn.innerHTML = originalHtml;
        }, 2000);
    }
    
    function confirmDelete(url, title, text, confirmText = \'Oui, supprimer\') {
        Swal.fire({
            title: title,
            text: text,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: confirmText,
            cancelButtonText: "Annuler"
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
    }
</script>
';
?>
<?= view('admin/layouts/footer', compact('additionalJS')) ?>
