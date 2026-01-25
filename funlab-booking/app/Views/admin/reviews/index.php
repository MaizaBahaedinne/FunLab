<?php
$title = 'Gestion des Avis';
$pageTitle = 'Gestion des Avis';
$activeMenu = 'reviews';
$breadcrumbs = ['Admin' => base_url('admin'), 'Avis' => null];
$additionalStyles = '
    .stat-card {
        transition: transform 0.2s, box-shadow 0.2s;
        border-left: 4px solid;
    }
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .stat-card.pending { border-left-color: #ffc107; }
    .stat-card.approved { border-left-color: #28a745; }
    .stat-card.total { border-left-color: #667eea; }
    
    .filter-btn {
        border-radius: 20px;
        padding: 8px 20px;
        font-weight: 500;
        transition: all 0.3s;
    }
    .filter-btn.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-color: #667eea;
        color: white !important;
    }
    
    .review-card {
        transition: transform 0.2s;
        border-left: 3px solid transparent;
    }
    .review-card:hover {
        transform: translateX(5px);
        box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    }
    .review-card.pending { border-left-color: #ffc107; background: #fff9e6; }
    .review-card.approved { border-left-color: #28a745; }
    
    .rating-stars i {
        font-size: 1.1rem;
    }
    
    .action-btn {
        padding: 5px 10px;
        border-radius: 5px;
        transition: all 0.2s;
    }
    .action-btn:hover {
        transform: scale(1.1);
    }
';
?>

<?= view('admin/layouts/header', compact('title', 'additionalStyles')) ?>
<?= view('admin/layouts/sidebar', compact('activeMenu')) ?>
<?= view('admin/layouts/topbar', compact('pageTitle', 'breadcrumbs')) ?>

<div class="container-fluid p-4">
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
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card stat-card pending h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small text-uppercase fw-semibold">En attente</p>
                            <h2 class="mb-0 fw-bold">
                                <?= count(array_filter($reviews, function($r) { return $r['is_approved'] == 0; })) ?>
                            </h2>
                        </div>
                        <div class="bg-warning bg-opacity-10 p-3 rounded-circle">
                            <i class="bi bi-clock-history fs-1 text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card approved h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small text-uppercase fw-semibold">Approuvés</p>
                            <h2 class="mb-0 fw-bold">
                                <?= count(array_filter($reviews, function($r) { return $r['is_approved'] == 1; })) ?>
                            </h2>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded-circle">
                            <i class="bi bi-check-circle fs-1 text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card total h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small text-uppercase fw-semibold">Total</p>
                            <h2 class="mb-0 fw-bold"><?= count($reviews) ?></h2>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                            <i class="bi bi-chat-quote fs-1" style="color: #667eea;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-outline-primary filter-btn active" onclick="filterReviews('all')">
                        <i class="bi bi-grid me-1"></i> Tous (<?= count($reviews) ?>)
                    </button>
                    <button type="button" class="btn btn-outline-warning filter-btn" onclick="filterReviews('pending')">
                        <i class="bi bi-clock me-1"></i> En attente (<?= count(array_filter($reviews, function($r) { return $r['is_approved'] == 0; })) ?>)
                    </button>
                    <button type="button" class="btn btn-outline-success filter-btn" onclick="filterReviews('approved')">
                        <i class="bi bi-check-circle me-1"></i> Approuvés (<?= count(array_filter($reviews, function($r) { return $r['is_approved'] == 1; })) ?>)
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Reviews List -->
    <?php if (empty($reviews)): ?>
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <div class="mb-3">
                    <i class="bi bi-inbox fs-1 text-muted"></i>
                </div>
                <h5 class="text-muted">Aucun avis pour le moment</h5>
                <p class="text-muted small">Les avis des clients apparaîtront ici</p>
            </div>
        </div>
    <?php else: ?>
        <div class="row g-3">
            <?php foreach ($reviews as $review): ?>
                <div class="col-12 review-row" data-status="<?= $review['is_approved'] == 0 ? 'pending' : 'approved' ?>">
                    <div class="card review-card <?= $review['is_approved'] == 0 ? 'pending' : 'approved' ?> border-0 shadow-sm">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <!-- Game Info -->
                                <div class="col-md-2">
                                    <h6 class="mb-1">
                                        <a href="<?= base_url('games/' . $review['game_id']) ?>" target="_blank" class="text-decoration-none">
                                            <i class="bi bi-controller me-1"></i>
                                            <?= esc($review['game_name']) ?>
                                        </a>
                                    </h6>
                                    <small class="text-muted">
                                        <i class="bi bi-calendar3 me-1"></i>
                                        <?= date('d/m/Y', strtotime($review['created_at'])) ?>
                                    </small>
                                </div>

                                <!-- Author -->
                                <div class="col-md-2">
                                    <?php if ($review['user_id']): ?>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-2">
                                                <i class="bi bi-person-circle text-primary"></i>
                                            </div>
                                            <div>
                                                <div class="fw-semibold"><?= esc($review['username']) ?></div>
                                                <small class="text-muted">Membre</small>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-secondary bg-opacity-10 rounded-circle p-2 me-2">
                                                <i class="bi bi-person text-secondary"></i>
                                            </div>
                                            <div>
                                                <div class="fw-semibold"><?= esc($review['name']) ?></div>
                                                <small class="text-muted"><?= esc($review['email']) ?></small>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Rating -->
                                <div class="col-md-1 text-center">
                                    <div class="rating-stars">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <i class="bi bi-star<?= $i <= $review['rating'] ? '-fill text-warning' : ' text-muted' ?>"></i>
                                        <?php endfor; ?>
                                    </div>
                                    <div class="mt-1">
                                        <span class="badge bg-warning text-dark"><?= $review['rating'] ?>/5</span>
                                    </div>
                                </div>

                                <!-- Comment -->
                                <div class="col-md-4">
                                    <div class="comment-preview" style="max-height: 60px; overflow: hidden;">
                                        <i class="bi bi-chat-left-quote text-muted me-1"></i>
                                        <span class="text-muted"><?= esc(substr($review['comment'], 0, 120)) ?><?= strlen($review['comment']) > 120 ? '...' : '' ?></span>
                                    </div>
                                </div>

                                <!-- Status -->
                                <div class="col-md-1 text-center">
                                    <?php if ($review['is_approved']): ?>
                                        <span class="badge bg-success">
                                            <i class="bi bi-check-circle me-1"></i> Publié
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark">
                                            <i class="bi bi-clock me-1"></i> En attente
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <!-- Actions -->
                                <div class="col-md-2 text-end">
                                    <div class="btn-group" role="group">
                                        <?php if (!$review['is_approved']): ?>
                                            <a href="<?= base_url('admin/reviews/approve/' . $review['id']) ?>" 
                                               class="btn btn-sm btn-success action-btn"
                                               onclick="return confirm('Approuver cet avis ?')"
                                               title="Approuver">
                                                <i class="bi bi-check-lg"></i>
                                            </a>
                                        <?php else: ?>
                                            <a href="<?= base_url('admin/reviews/reject/' . $review['id']) ?>" 
                                               class="btn btn-sm btn-warning action-btn"
                                               onclick="return confirm('Rejeter cet avis ?')"
                                               title="Rejeter">
                                                <i class="bi bi-x-lg"></i>
                                            </a>
                                        <?php endif; ?>
                                        <button type="button" class="btn btn-sm btn-info action-btn" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#viewModal<?= $review['id'] ?>"
                                                title="Voir détails">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <a href="<?= base_url('admin/reviews/delete/' . $review['id']) ?>" 
                                           class="btn btn-sm btn-danger action-btn"
                                           onclick="return confirm('Supprimer cet avis définitivement ?')"
                                           title="Supprimer">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- View Modal -->
                <div class="modal fade" id="viewModal<?= $review['id'] ?>" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content border-0 shadow">
                            <div class="modal-header bg-light">
                                <h5 class="modal-title">
                                    <i class="bi bi-chat-quote me-2"></i>
                                    Détails de l'avis
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body p-4">
                                <!-- Game -->
                                <div class="mb-4">
                                    <label class="text-muted small text-uppercase fw-semibold mb-2">Jeu</label>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-controller fs-4 text-primary me-2"></i>
                                        <h6 class="mb-0"><?= esc($review['game_name']) ?></h6>
                                    </div>
                                </div>

                                <!-- Author -->
                                <div class="mb-4">
                                    <label class="text-muted small text-uppercase fw-semibold mb-2">Auteur</label>
                                    <?php if ($review['user_id']): ?>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                                                <i class="bi bi-person-circle fs-4 text-primary"></i>
                                            </div>
                                            <div>
                                                <div class="fw-semibold"><?= esc($review['username']) ?></div>
                                                <small class="text-muted">Utilisateur inscrit</small>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-secondary bg-opacity-10 rounded-circle p-3 me-3">
                                                <i class="bi bi-person fs-4 text-secondary"></i>
                                            </div>
                                            <div>
                                                <div class="fw-semibold"><?= esc($review['name']) ?></div>
                                                <small class="text-muted"><?= esc($review['email']) ?></small>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Rating -->
                                <div class="mb-4">
                                    <label class="text-muted small text-uppercase fw-semibold mb-2">Note</label>
                                    <div class="d-flex align-items-center">
                                        <div class="rating-stars me-3">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <i class="bi bi-star<?= $i <= $review['rating'] ? '-fill text-warning' : ' text-muted' ?>" style="font-size: 1.5rem;"></i>
                                            <?php endfor; ?>
                                        </div>
                                        <span class="badge bg-warning text-dark fs-6"><?= $review['rating'] ?>/5</span>
                                    </div>
                                </div>

                                <!-- Comment -->
                                <div class="mb-4">
                                    <label class="text-muted small text-uppercase fw-semibold mb-2">Commentaire</label>
                                    <div class="p-3 bg-light rounded">
                                        <i class="bi bi-chat-left-quote text-muted me-2"></i>
                                        <?= nl2br(esc($review['comment'])) ?>
                                    </div>
                                </div>

                                <!-- Date & Status -->
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="text-muted small text-uppercase fw-semibold mb-2">Date</label>
                                        <div>
                                            <i class="bi bi-calendar3 me-2 text-muted"></i>
                                            <?= date('d/m/Y à H:i', strtotime($review['created_at'])) ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="text-muted small text-uppercase fw-semibold mb-2">Statut</label>
                                        <div>
                                            <?php if ($review['is_approved']): ?>
                                                <span class="badge bg-success">
                                                    <i class="bi bi-check-circle me-1"></i> Approuvé et publié
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-warning text-dark">
                                                    <i class="bi bi-clock me-1"></i> En attente de modération
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer bg-light">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    <i class="bi bi-x-lg me-1"></i> Fermer
                                </button>
                                <?php if (!$review['is_approved']): ?>
                                    <a href="<?= base_url('admin/reviews/approve/' . $review['id']) ?>" 
                                       class="btn btn-success"
                                       onclick="return confirm('Approuver cet avis ?')">
                                        <i class="bi bi-check-lg me-1"></i> Approuver
                                    </a>
                                <?php else: ?>
                                    <a href="<?= base_url('admin/reviews/reject/' . $review['id']) ?>" 
                                       class="btn btn-warning"
                                       onclick="return confirm('Rejeter cet avis ?')">
                                        <i class="bi bi-x-lg me-1"></i> Rejeter
                                    </a>
                                <?php endif; ?>
                                <a href="<?= base_url('admin/reviews/delete/' . $review['id']) ?>" 
                                   class="btn btn-danger"
                                   onclick="return confirm('Supprimer cet avis définitivement ?')">
                                    <i class="bi bi-trash me-1"></i> Supprimer
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
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

</div> <!-- .container-fluid -->

<?= view('admin/layouts/footer') ?>
