<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">
            <i class="bi bi-star-fill text-warning"></i> Gestion des Avis
        </h1>
    </div>

    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">En attente</h6>
                            <h3 class="mb-0">
                                <?= count(array_filter($reviews, function($r) { return $r['is_approved'] == 0; })) ?>
                            </h3>
                        </div>
                        <i class="bi bi-clock-history fs-1 text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Approuvés</h6>
                            <h3 class="mb-0">
                                <?= count(array_filter($reviews, function($r) { return $r['is_approved'] == 1; })) ?>
                            </h3>
                        </div>
                        <i class="bi bi-check-circle fs-1 text-success"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Total</h6>
                            <h3 class="mb-0"><?= count($reviews) ?></h3>
                        </div>
                        <i class="bi bi-chat-quote fs-1 text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-outline-primary active" onclick="filterReviews('all')">
                    Tous (<?= count($reviews) ?>)
                </button>
                <button type="button" class="btn btn-outline-warning" onclick="filterReviews('pending')">
                    En attente (<?= count(array_filter($reviews, function($r) { return $r['is_approved'] == 0; })) ?>)
                </button>
                <button type="button" class="btn btn-outline-success" onclick="filterReviews('approved')">
                    Approuvés (<?= count(array_filter($reviews, function($r) { return $r['is_approved'] == 1; })) ?>)
                </button>
            </div>
        </div>
    </div>

    <!-- Reviews Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Jeu</th>
                            <th>Auteur</th>
                            <th>Note</th>
                            <th>Commentaire</th>
                            <th>Date</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($reviews)): ?>
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                                    <p class="text-muted">Aucun avis pour le moment</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($reviews as $review): ?>
                                <tr class="review-row" data-status="<?= $review['is_approved'] == 0 ? 'pending' : 'approved' ?>">
                                    <td>
                                        <a href="<?= base_url('games/' . $review['game_id']) ?>" target="_blank">
                                            <?= esc($review['game_name']) ?>
                                        </a>
                                    </td>
                                    <td>
                                        <?php if ($review['user_id']): ?>
                                            <i class="bi bi-person-circle me-1"></i>
                                            <?= esc($review['username']) ?>
                                        <?php else: ?>
                                            <i class="bi bi-person me-1"></i>
                                            <?= esc($review['name']) ?><br>
                                            <small class="text-muted"><?= esc($review['email']) ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="rating-display">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <i class="bi bi-star<?= $i <= $review['rating'] ? '-fill text-warning' : '' ?>"></i>
                                            <?php endfor; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div style="max-width: 300px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                            <?= esc($review['comment']) ?>
                                        </div>
                                    </td>
                                    <td>
                                        <small><?= date('d/m/Y H:i', strtotime($review['created_at'])) ?></small>
                                    </td>
                                    <td>
                                        <?php if ($review['is_approved']): ?>
                                            <span class="badge bg-success">Approuvé</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning">En attente</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <?php if (!$review['is_approved']): ?>
                                                <a href="<?= base_url('admin/reviews/approve/' . $review['id']) ?>" 
                                                   class="btn btn-success"
                                                   onclick="return confirm('Approuver cet avis ?')">
                                                    <i class="bi bi-check-lg"></i>
                                                </a>
                                            <?php else: ?>
                                                <a href="<?= base_url('admin/reviews/reject/' . $review['id']) ?>" 
                                                   class="btn btn-warning"
                                                   onclick="return confirm('Rejeter cet avis ?')">
                                                    <i class="bi bi-x-lg"></i>
                                                </a>
                                            <?php endif; ?>
                                            <button type="button" class="btn btn-info" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#viewModal<?= $review['id'] ?>">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <a href="<?= base_url('admin/reviews/delete/' . $review['id']) ?>" 
                                               class="btn btn-danger"
                                               onclick="return confirm('Supprimer cet avis définitivement ?')">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>

                                <!-- View Modal -->
                                <div class="modal fade" id="viewModal<?= $review['id'] ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Détails de l'avis</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <h6>Jeu :</h6>
                                                <p><?= esc($review['game_name']) ?></p>

                                                <h6>Auteur :</h6>
                                                <p>
                                                    <?php if ($review['user_id']): ?>
                                                        <?= esc($review['username']) ?> (Utilisateur inscrit)
                                                    <?php else: ?>
                                                        <?= esc($review['name']) ?> (<?= esc($review['email']) ?>)
                                                    <?php endif; ?>
                                                </p>

                                                <h6>Note :</h6>
                                                <div class="rating-display mb-3">
                                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                                        <i class="bi bi-star<?= $i <= $review['rating'] ? '-fill text-warning' : '' ?> fs-5"></i>
                                                    <?php endfor; ?>
                                                    <span class="ms-2"><?= $review['rating'] ?>/5</span>
                                                </div>

                                                <h6>Commentaire :</h6>
                                                <p><?= nl2br(esc($review['comment'])) ?></p>

                                                <h6>Date :</h6>
                                                <p><?= date('d/m/Y à H:i', strtotime($review['created_at'])) ?></p>

                                                <h6>Statut :</h6>
                                                <p>
                                                    <?php if ($review['is_approved']): ?>
                                                        <span class="badge bg-success">Approuvé</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-warning">En attente de modération</span>
                                                    <?php endif; ?>
                                                </p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function filterReviews(status) {
    const rows = document.querySelectorAll('.review-row');
    const buttons = document.querySelectorAll('.btn-group .btn');
    
    // Update active button
    buttons.forEach(btn => btn.classList.remove('active'));
    event.target.classList.add('active');
    
    // Filter rows
    rows.forEach(row => {
        if (status === 'all') {
            row.style.display = '';
        } else {
            row.style.display = row.dataset.status === status ? '' : 'none';
        }
    });
}
</script>

<?= $this->endSection() ?>
