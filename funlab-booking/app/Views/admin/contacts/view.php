<?php
$pageTitle = 'Message de ' . esc($message['name']);
$breadcrumbs = [
    'Admin' => base_url('admin'),
    'Contacts' => base_url('admin/contacts'),
    'Message' => null
];
?>

<?= view('admin/layouts/header', compact('title')) ?>
<?= view('admin/layouts/sidebar', compact('activeMenu')) ?>
<?= view('admin/layouts/topbar', compact('pageTitle', 'breadcrumbs')) ?>

            <div class="container-fluid p-4">
                <div class="mb-3">
                    <a href="<?= base_url('admin/contacts') ?>" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Retour à la liste
                    </a>
                </div>

                <div class="row">
                <div class="col-lg-8">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    <i class="bi bi-envelope-open"></i> <?= esc($message['subject']) ?>
                                </h5>
                                <?php if ($message['status'] === 'new'): ?>
                                    <span class="badge bg-warning text-dark">
                                        <i class="bi bi-exclamation-circle"></i> Nouveau
                                    </span>
                                <?php elseif ($message['status'] === 'read'): ?>
                                    <span class="badge bg-info">
                                        <i class="bi bi-eye"></i> Lu
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle"></i> Répondu
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="mb-4">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="mb-2">
                                            <i class="bi bi-person-fill text-primary"></i>
                                            <strong>De :</strong> <?= esc($message['name']) ?>
                                        </p>
                                        <p class="mb-2">
                                            <i class="bi bi-envelope-fill text-primary"></i>
                                            <strong>Email :</strong> 
                                            <a href="mailto:<?= esc($message['email']) ?>"><?= esc($message['email']) ?></a>
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <?php if (!empty($message['phone'])): ?>
                                        <p class="mb-2">
                                            <i class="bi bi-phone-fill text-primary"></i>
                                            <strong>Téléphone :</strong> 
                                            <a href="tel:<?= esc($message['phone']) ?>"><?= esc($message['phone']) ?></a>
                                        </p>
                                        <?php endif; ?>
                                        <p class="mb-2">
                                            <i class="bi bi-calendar3 text-primary"></i>
                                            <strong>Date :</strong> 
                                            <?= date('d/m/Y à H:i', strtotime($message['created_at'])) ?>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <div class="message-content">
                                <h6 class="text-muted mb-3"><i class="bi bi-chat-left-text"></i> Message :</h6>
                                <div class="p-4 bg-light rounded border">
                                    <?= nl2br(esc($message['message'])) ?>
                                </div>
                            </div>

                            <?php if (!empty($message['ip_address'])): ?>
                            <div class="mt-3">
                                <small class="text-muted font-monospace">
                                    <i class="bi bi-geo-alt"></i> Adresse IP: <?= esc($message['ip_address']) ?>
                                </small>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="card-footer bg-light">
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="mailto:<?= esc($message['email']) ?>?subject=Re: <?= urlencode($message['subject']) ?>" 
                                   class="btn btn-primary">
                                    <i class="bi bi-reply-fill"></i> Répondre par Email
                                </a>
                                
                                <div class="btn-group">
                                    <?php if ($message['status'] !== 'replied'): ?>
                                    <button class="btn btn-success" onclick="markAsReplied(<?= $message['id'] ?>)">
                                        <i class="bi bi-check-circle-fill"></i> Marquer comme répondu
                                    </button>
                                    <?php endif; ?>
                                    <button class="btn btn-danger" onclick="deleteMessage(<?= $message['id'] ?>)">
                                        <i class="bi bi-trash-fill"></i> Supprimer
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-secondary text-white">
                            <i class="bi bi-info-circle-fill"></i> Informations
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <strong>Statut actuel :</strong><br>
                                <?php if ($message['status'] === 'new'): ?>
                                    <span class="badge bg-warning text-dark mt-2">
                                        <i class="bi bi-exclamation-circle"></i> Nouveau message
                                    </span>
                                <?php elseif ($message['status'] === 'read'): ?>
                                    <span class="badge bg-info mt-2">
                                        <i class="bi bi-eye"></i> Message lu
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-success mt-2">
                                        <i class="bi bi-check-circle"></i> Message répondu
                                    </span>
                                    <?php if (!empty($message['replied_at'])): ?>
                                    <br><small class="text-muted">
                                        Répondu le <?= date('d/m/Y à H:i', strtotime($message['replied_at'])) ?>
                                    </small>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>

                            <div class="alert alert-info mb-0">
                                <i class="bi bi-lightbulb-fill"></i>
                                <small>
                                    Utilisez le bouton "Répondre par Email" pour ouvrir votre client de messagerie 
                                    avec l'adresse préremplie.
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

<?= view('admin/layouts/footer') ?>
