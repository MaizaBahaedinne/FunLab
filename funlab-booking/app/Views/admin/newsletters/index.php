<?php
$pageTitle = 'Abonnés Newsletter';
$breadcrumbs = ['Admin' => base_url('admin'), 'Newsletter' => null];
?>

<?= view('admin/layouts/header', compact('title')) ?>
<?= view('admin/layouts/sidebar', compact('activeMenu')) ?>
<?= view('admin/layouts/topbar', compact('pageTitle', 'breadcrumbs')) ?>

            <div class="container-fluid p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2><i class="bi bi-envelope-heart text-primary"></i> Abonnés Newsletter</h2>
                        <span class="badge bg-primary fs-6">
                            <?= $activeCount ?> abonné<?= $activeCount > 1 ? 's' : '' ?> actif<?= $activeCount > 1 ? 's' : '' ?>
                        </span>
                    </div>
                    <a href="<?= base_url('admin/newsletters/export') ?>" class="btn btn-success">
                        <i class="bi bi-download"></i> Exporter (CSV)
                    </a>
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
                    <?php if (empty($subscribers)): ?>
                        <div class="text-center py-5">
                            <i class="bi bi-envelope-x text-muted" style="font-size: 4rem;"></i>
                            <p class="text-muted mt-3">Aucun abonné pour le moment</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th><i class="bi bi-envelope me-2"></i>Email</th>
                                        <th>Statut</th>
                                        <th>Date d'inscription</th>
                                        <th>Adresse IP</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($subscribers as $subscriber): ?>
                                        <tr>
                                            <td>
                                                <i class="bi bi-envelope-fill text-primary me-2"></i>
                                                <?= esc($subscriber['email']) ?>
                                            </td>
                                            <td>
                                                <?php if ($subscriber['status'] === 'active'): ?>
                                                    <span class="badge bg-success">
                                                        <i class="bi bi-check-circle"></i> Actif
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">
                                                        <i class="bi bi-x-circle"></i> Désabonné
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    <i class="bi bi-calendar3"></i>
                                                    <?= date('d/m/Y H:i', strtotime($subscriber['subscribed_at'])) ?>
                                                </small>
                                            </td>
                                            <td>
                                                <small class="text-muted font-monospace">
                                                    <?= esc($subscriber['ip_address'] ?? '-') ?>
                                                </small>
                                            </td>
                                            <td class="text-end">
                                                <button class="btn btn-sm btn-danger" 
                                                        onclick="deleteSubscriber(<?= $subscriber['id'] ?>)"
                                                        title="Supprimer">
                                                    <i class="bi bi-trash"></i>
                                                </button>
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
