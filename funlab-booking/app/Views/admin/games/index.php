<?php
$title = 'Gestion des Jeux';
$pageTitle = 'Gestion des Jeux';
$activeMenu = 'games';
$breadcrumbs = ['Admin' => base_url('admin'), 'Jeux' => null];
$additionalStyles = '
.game-card {
    transition: transform 0.3s;
    border-left: 4px solid #667eea;
}
.game-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
}
.badge-status {
    padding: 6px 12px;
    border-radius: 15px;
}
';
?>

<?= view('admin/layouts/header', compact('title', 'additionalStyles')) ?>
<?= view('admin/layouts/sidebar', compact('activeMenu')) ?>
<?= view('admin/layouts/topbar', compact('pageTitle', 'breadcrumbs')) ?>

            <div class="container-fluid p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="bi bi-controller"></i> Gestion des Jeux</h2>
                    <a href="<?= base_url('admin/games/create') ?>" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Ajouter un jeu
                    </a>
                </div>

                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="bi bi-check-circle"></i>
                        <?= session()->getFlashdata('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="bi bi-exclamation-triangle"></i>
                        <?= session()->getFlashdata('error') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="row">
                    <?php if (empty($games)): ?>
                        <div class="col-12">
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle"></i>
                                Aucun jeu trouvé. <a href="<?= base_url('admin/games/create') ?>">Créer le premier jeu</a>
                            </div>
                        </div>
                    <?php else: ?>
                        <?php foreach ($games as $game): ?>
                            <div class="col-md-6 mb-4">
                                <div class="card game-card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <div>
                                                <h5 class="card-title mb-1">
                                                    <i class="bi bi-controller text-primary"></i>
                                                    <?= esc($game['name']) ?>
                                                </h5>
                                                <span class="badge bg-secondary"><?= esc($game['category'] ?? 'Non catégorisé') ?></span>
                                            </div>
                                            <span class="badge badge-status bg-<?= $game['status'] === 'active' ? 'success' : 'secondary' ?>">
                                                <?= $game['status'] === 'active' ? 'Actif' : 'Inactif' ?>
                                            </span>
                                        </div>

                                        <?php if (!empty($game['description'])): ?>
                                            <p class="card-text text-muted small mb-3">
                                                <?= mb_substr(esc($game['description']), 0, 120) ?>
                                                <?= mb_strlen($game['description']) > 120 ? '...' : '' ?>
                                            </p>
                                        <?php endif; ?>

                                        <div class="row mb-3">
                                            <?php if (isset($game['duration']) && $game['duration'] > 0): ?>
                                            <div class="col-6">
                                                <div class="d-flex justify-content-between">
                                                    <span class="text-muted small">
                                                        <i class="bi bi-clock"></i> Durée
                                                    </span>
                                                    <strong><?= $game['duration'] ?> min</strong>
                                                </div>
                                            </div>
                                            <?php endif; ?>
                                            <?php if (isset($game['min_participants']) && isset($game['max_participants'])): ?>
                                            <div class="col-6">
                                                <div class="d-flex justify-content-between">
                                                    <span class="text-muted small">
                                                        <i class="bi bi-people"></i> Participants
                                                    </span>
                                                    <strong><?= $game['min_participants'] ?>-<?= $game['max_participants'] ?></strong>
                                                </div>
                                            </div>
                                            <?php endif; ?>
                                        </div>

                                        <?php if (isset($game['price']) || isset($game['price_per_person'])): ?>
                                        <div class="row mb-3">
                                            <?php if (isset($game['price']) && $game['price'] > 0): ?>
                                            <div class="col-6">
                                                <div class="d-flex justify-content-between">
                                                    <span class="text-muted small">
                                                        <i class="bi bi-tag"></i> Prix
                                                    </span>
                                                    <strong><?= number_format($game['price'], 2) ?> TND</strong>
                                                </div>
                                            </div>
                                            <?php endif; ?>
                                            <?php if (isset($game['price_per_person']) && $game['price_per_person'] > 0): ?>
                                            <div class="col-6">
                                                <div class="d-flex justify-content-between">
                                                    <span class="text-muted small">
                                                        <i class="bi bi-person"></i> Par personne
                                                    </span>
                                                    <strong><?= number_format($game['price_per_person'], 2) ?> TND</strong>
                                                </div>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                        <?php endif; ?>

                                        <?php if (isset($game['deposit_required']) && $game['deposit_required']): ?>
                                            <div class="alert alert-info py-2 px-3 mb-3 small">
                                                <i class="bi bi-info-circle"></i>
                                                Acompte requis: <?= $game['deposit_percentage'] ?? 30 ?>%
                                            </div>
                                        <?php endif; ?>

                                        <div class="d-flex gap-2">
                                            <a href="<?= base_url('admin/games/edit/' . $game['id']) ?>" 
                                               class="btn btn-sm btn-outline-primary flex-fill">
                                                <i class="bi bi-pencil"></i> Modifier
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-danger" 
                                                    onclick="deleteGame(<?= $game['id'] ?>, '<?= esc($game['name']) ?>')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

<?php
$additionalJS = '
<script>
    function deleteGame(id, name) {
        if (confirm(`Êtes-vous sûr de vouloir supprimer le jeu "${name}" ?\n\nCette action est irréversible et supprimera toutes les réservations associées.`)) {
            fetch(`<?= base_url("admin/games/delete/") ?>${id}`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-Requested-With": "XMLHttpRequest"
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Erreur",
                        text: data.message || "Une erreur est survenue"
                    });
                }
            })
            .catch(err => {
                Swal.fire({
                    icon: "error",
                    title: "Erreur",
                    text: "Erreur lors de la suppression"
                });
                console.error(err);
            });
        }
    }
</script>
';
?>
<?= view('admin/layouts/footer', compact('additionalJS')) ?>
