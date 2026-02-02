<div class="admin-main">
    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="bi bi-ticket-perforated text-primary" style="font-size: 2rem;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="text-muted small">Total Codes</div>
                            <h4 class="mb-0"><?= $statistics['total'] ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="bi bi-check-circle text-success" style="font-size: 2rem;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="text-muted small">Actifs</div>
                            <h4 class="mb-0"><?= $statistics['active'] ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="bi bi-clock-history text-warning" style="font-size: 2rem;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="text-muted small">Expirés</div>
                            <h4 class="mb-0"><?= $statistics['expired'] ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="bi bi-graph-up text-info" style="font-size: 2rem;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="text-muted small">Utilisations</div>
                            <h4 class="mb-0"><?= $statistics['total_usage'] ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Bar -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">Liste des Codes Promo</h5>
                    <p class="text-muted small mb-0">Gérez vos codes de réduction</p>
                </div>
                <a href="<?= base_url('admin/promo-codes/create') ?>" class="btn btn-primary">
                    <i class="bi bi-plus-lg"></i> Créer un Code
                </a>
            </div>
        </div>
    </div>

    <!-- Promo Codes Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Code</th>
                            <th>Description</th>
                            <th>Type</th>
                            <th>Réduction</th>
                            <th>Validité</th>
                            <th>Utilisation</th>
                            <th>Statut</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($promoCodes)): ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    <i class="bi bi-ticket-perforated" style="font-size: 3rem;"></i>
                                    <p class="mt-2">Aucun code promo</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($promoCodes as $promo): ?>
                                <?php 
                                $now = date('Y-m-d H:i:s');
                                $isExpired = $promo['valid_until'] && $promo['valid_until'] < $now;
                                $isLimitReached = $promo['usage_limit'] && $promo['usage_count'] >= $promo['usage_limit'];
                                ?>
                                <tr>
                                    <td>
                                        <span class="badge bg-dark fs-6"><?= esc($promo['code']) ?></span>
                                    </td>
                                    <td>
                                        <small><?= esc($promo['description']) ?: '-' ?></small>
                                    </td>
                                    <td>
                                        <?php if ($promo['discount_type'] === 'percentage'): ?>
                                            <span class="badge bg-info">Pourcentage</span>
                                        <?php else: ?>
                                            <span class="badge bg-success">Fixe</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <strong>
                                            <?php if ($promo['discount_type'] === 'percentage'): ?>
                                                <?= number_format($promo['discount_value'], 0) ?>%
                                                <?php if ($promo['max_discount']): ?>
                                                    <small class="text-muted">(max <?= number_format($promo['max_discount'], 2) ?> MAD)</small>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <?= number_format($promo['discount_value'], 2) ?> MAD
                                            <?php endif; ?>
                                        </strong>
                                    </td>
                                    <td>
                                        <?php if ($promo['valid_from'] || $promo['valid_until']): ?>
                                            <small>
                                                <?php if ($promo['valid_from']): ?>
                                                    Du <?= date('d/m/Y', strtotime($promo['valid_from'])) ?>
                                                <?php endif; ?>
                                                <?php if ($promo['valid_until']): ?>
                                                    <br>Au <?= date('d/m/Y', strtotime($promo['valid_until'])) ?>
                                                <?php endif; ?>
                                            </small>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Illimitée</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge <?= $isLimitReached ? 'bg-danger' : 'bg-primary' ?>">
                                            <?= $promo['usage_count'] ?><?= $promo['usage_limit'] ? ' / ' . $promo['usage_limit'] : '' ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($isExpired): ?>
                                            <span class="badge bg-warning">Expiré</span>
                                        <?php elseif ($isLimitReached): ?>
                                            <span class="badge bg-danger">Limite atteinte</span>
                                        <?php elseif ($promo['is_active']): ?>
                                            <span class="badge bg-success">Actif</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Inactif</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary" 
                                                    onclick="toggleStatus(<?= $promo['id'] ?>)"
                                                    title="<?= $promo['is_active'] ? 'Désactiver' : 'Activer' ?>">
                                                <i class="bi bi-<?= $promo['is_active'] ? 'toggle-on' : 'toggle-off' ?>"></i>
                                            </button>
                                            <a href="<?= base_url('admin/promo-codes/edit/' . $promo['id']) ?>" 
                                               class="btn btn-outline-secondary"
                                               title="Modifier">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button class="btn btn-outline-danger" 
                                                    onclick="deletePromo(<?= $promo['id'] ?>)"
                                                    title="Supprimer">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function toggleStatus(id) {
    Swal.fire({
        title: 'Changer le statut ?',
        text: 'Voulez-vous activer/désactiver ce code promo ?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Oui',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`<?= base_url('admin/promo-codes/toggle-status/') ?>${id}`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Succès!', data.message, 'success')
                        .then(() => location.reload());
                } else {
                    Swal.fire('Erreur!', data.message, 'error');
                }
            })
            .catch(error => {
                Swal.fire('Erreur!', 'Une erreur est survenue', 'error');
            });
        }
    });
}

function deletePromo(id) {
    Swal.fire({
        title: 'Supprimer ce code ?',
        text: 'Cette action est irréversible!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Oui, supprimer',
        cancelButtonText: 'Annuler',
        confirmButtonColor: '#dc3545'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`<?= base_url('admin/promo-codes/delete/') ?>${id}`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Supprimé!', data.message, 'success')
                        .then(() => location.reload());
                } else {
                    Swal.fire('Erreur!', data.message, 'error');
                }
            })
            .catch(error => {
                Swal.fire('Erreur!', 'Une erreur est survenue', 'error');
            });
        }
    });
}
</script>
