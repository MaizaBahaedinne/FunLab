<?= view('admin/layouts/header', compact('title')) ?>

<div class="container-fluid">
    <div class="row">
        <?= view('admin/layouts/sidebar', compact('activeMenu')) ?>
        
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 admin-content">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">
                    <i class="bi bi-chat-left-text text-primary"></i> Message de Contact
                </h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="<?= base_url('admin/contacts') ?>" class="btn btn-sm btn-secondary">
                        <i class="bi bi-arrow-left"></i> Retour
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    <i class="bi bi-envelope-open"></i> <?= esc($message['subject']) ?>
                                </h5>
                                <?php if ($message['status'] === 'new'): ?>
                                    <span class="badge bg-warning">Nouveau</span>
                                <?php elseif ($message['status'] === 'read'): ?>
                                    <span class="badge bg-info">Lu</span>
                                <?php else: ?>
                                    <span class="badge bg-success">Répondu</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="mb-4">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="mb-2">
                                            <i class="bi bi-person text-primary"></i>
                                            <strong>De :</strong> <?= esc($message['name']) ?>
                                        </p>
                                        <p class="mb-2">
                                            <i class="bi bi-envelope text-primary"></i>
                                            <strong>Email :</strong> 
                                            <a href="mailto:<?= esc($message['email']) ?>"><?= esc($message['email']) ?></a>
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <?php if (!empty($message['phone'])): ?>
                                        <p class="mb-2">
                                            <i class="bi bi-phone text-primary"></i>
                                            <strong>Téléphone :</strong> 
                                            <a href="tel:<?= esc($message['phone']) ?>"><?= esc($message['phone']) ?></a>
                                        </p>
                                        <?php endif; ?>
                                        <p class="mb-2">
                                            <i class="bi bi-calendar text-primary"></i>
                                            <strong>Date :</strong> 
                                            <?= date('d/m/Y à H:i', strtotime($message['created_at'])) ?>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <div class="message-content">
                                <h6 class="text-muted mb-3">Message :</h6>
                                <div class="p-3 bg-light rounded">
                                    <?= nl2br(esc($message['message'])) ?>
                                </div>
                            </div>

                            <?php if (!empty($message['ip_address'])): ?>
                            <div class="mt-3">
                                <small class="text-muted">
                                    <i class="bi bi-geo-alt"></i> Adresse IP: <?= esc($message['ip_address']) ?>
                                </small>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="card-footer bg-light">
                            <div class="d-flex justify-content-between">
                                <a href="mailto:<?= esc($message['email']) ?>?subject=Re: <?= urlencode($message['subject']) ?>" 
                                   class="btn btn-primary">
                                    <i class="bi bi-reply"></i> Répondre par Email
                                </a>
                                
                                <div class="btn-group">
                                    <?php if ($message['status'] !== 'replied'): ?>
                                    <button class="btn btn-success" onclick="markAsReplied(<?= $message['id'] ?>)">
                                        <i class="bi bi-check-circle"></i> Marquer comme répondu
                                    </button>
                                    <?php endif; ?>
                                    <button class="btn btn-danger" onclick="deleteMessage(<?= $message['id'] ?>)">
                                        <i class="bi bi-trash"></i> Supprimer
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header bg-secondary text-white">
                            <i class="bi bi-info-circle"></i> Informations
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <strong>Statut actuel :</strong><br>
                                <?php if ($message['status'] === 'new'): ?>
                                    <span class="badge bg-warning">Nouveau message</span>
                                <?php elseif ($message['status'] === 'read'): ?>
                                    <span class="badge bg-info">Message lu</span>
                                <?php else: ?>
                                    <span class="badge bg-success">Message répondu</span>
                                    <?php if (!empty($message['replied_at'])): ?>
                                    <br><small class="text-muted">
                                        Répondu le <?= date('d/m/Y à H:i', strtotime($message['replied_at'])) ?>
                                    </small>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>

                            <div class="alert alert-info">
                                <i class="bi bi-lightbulb"></i>
                                <small>
                                    Utilisez le bouton "Répondre par Email" pour ouvrir votre client de messagerie 
                                    avec l'adresse préremplie.
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
function markAsReplied(id) {
    fetch(`<?= base_url('admin/contacts/markReplied/') ?>${id}`, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || 'Erreur');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erreur');
    });
}

function deleteMessage(id) {
    if (!confirm('Êtes-vous sûr de vouloir supprimer ce message ?')) {
        return;
    }

    fetch(`<?= base_url('admin/contacts/delete/') ?>${id}`, {
        method: 'DELETE',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = '<?= base_url('admin/contacts') ?>';
        } else {
            alert(data.message || 'Erreur lors de la suppression');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erreur lors de la suppression');
    });
}
</script>

<?= view('admin/layouts/footer') ?>
