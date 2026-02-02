<?php
$pageTitle = 'Messages de Contact';
$breadcrumbs = ['Admin' => base_url('admin'), 'Contacts' => null];
?>

<?= view('admin/layouts/header', compact('title')) ?>
<?= view('admin/layouts/sidebar', compact('activeMenu')) ?>
<?= view('admin/layouts/topbar', compact('pageTitle', 'breadcrumbs')) ?>

            <div class="container-fluid p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2><i class="bi bi-chat-left-text text-primary"></i> Messages de Contact</h2>
                        <span class="badge bg-warning fs-6">
                            <?= $unreadCount ?> non lu<?= $unreadCount > 1 ? 's' : '' ?>
                        </span>
                    </div>
                </div>

            <?php if (session()->has('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle"></i>
                    <?= session('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->has('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle"></i>
                    <?= session('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="card shadow-sm">
                <div class="card-body">
                    <?php if (empty($messages)): ?>
                        <div class="text-center py-5">
                            <i class="bi bi-chat-left-dots text-muted" style="font-size: 4rem;"></i>
                            <p class="text-muted mt-3">Aucun message pour le moment</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Statut</th>
                                        <th><i class="bi bi-person me-2"></i>Nom</th>
                                        <th>Email</th>
                                        <th>Sujet</th>
                                        <th>Date</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($messages as $message): ?>
                                        <tr class="<?= $message['status'] === 'new' ? 'table-warning' : '' ?>">
                                            <td>
                                                <?php if ($message['status'] === 'new'): ?>
                                                    <span class="badge bg-warning">
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
                                            </td>
                                            <td>
                                                <i class="bi bi-person-fill text-primary me-2"></i>
                                                <?= esc($message['name']) ?>
                                            </td>
                                            <td>
                                                <small class="text-muted"><?= esc($message['email']) ?></small>
                                            </td>
                                            <td>
                                                <strong><?= esc($message['subject']) ?></strong>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    <i class="bi bi-calendar3"></i>
                                                    <?= date('d/m/Y H:i', strtotime($message['created_at'])) ?>
                                                </small>
                                            </td>
                                            <td class="text-end">
                                                <div class="btn-group btn-group-sm">
                                                    <a href="<?= base_url('admin/contacts/view/' . $message['id']) ?>" 
                                                       class="btn btn-primary"
                                                       title="Voir">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <button class="btn btn-danger" 
                                                            onclick="deleteMessage(<?= $message['id'] ?>)"
                                                            title="Supprimer">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

<?= view('admin/layouts/footer') ?>
