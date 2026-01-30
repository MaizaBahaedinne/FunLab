<?php
$title = 'Gestion des Salles';
$pageTitle = 'Gestion des Salles';
$activeMenu = 'rooms';
$breadcrumbs = ['Admin' => base_url('admin'), 'Salles' => null];
$additionalStyles = '
.room-card {
    transition: transform 0.3s;
}
.room-card:hover {
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
                    <h2><i class="bi bi-door-open"></i> Gestion des Salles</h2>
                    <a href="<?= base_url('admin/rooms/create') ?>" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Ajouter une salle
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
                    <?php if (empty($rooms)): ?>
                        <div class="col-12">
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle"></i>
                                Aucune salle trouvée. <a href="<?= base_url('admin/rooms/create') ?>">Créer la première salle</a>
                            </div>
                        </div>
                    <?php else: ?>
                        <?php foreach ($rooms as $room): ?>
                            <div class="col-md-4 mb-4">
                                <div class="card room-card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <h5 class="card-title mb-0"><?= esc($room['name']) ?></h5>
                                            <span class="badge badge-status bg-<?= $room['status'] === 'active' ? 'success' : 'secondary' ?>">
                                                <?= $room['status'] === 'active' ? 'Active' : 'Inactive' ?>
                                            </span>
                                        </div>

                                        <?php if (!empty($room['description'])): ?>
                                            <p class="card-text text-muted small"><?= esc($room['description']) ?></p>
                                        <?php endif; ?>

                                        <div class="mb-3">
                                            <div class="d-flex justify-content-between mb-2">
                                                <span class="text-muted">
                                                    <i class="bi bi-people"></i> Capacité
                                                </span>
                                                <strong><?= $room['capacity'] ?> personnes</strong>
                                            </div>
                                            
                                            <?php if (isset($room['game_count'])): ?>
                                                <div class="d-flex justify-content-between">
                                                    <span class="text-muted">
                                                        <i class="bi bi-controller"></i> Jeux
                                                    </span>
                                                    <strong><?= $room['game_count'] ?? 0 ?></strong>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <div class="d-flex gap-2">
                                            <a href="<?= base_url('admin/rooms/edit/' . $room['id']) ?>" 
                                               class="btn btn-sm btn-outline-primary flex-fill">
                                                <i class="bi bi-pencil"></i> Modifier
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-danger" 
                                                    onclick="deleteRoom(<?= $room['id'] ?>, '<?= esc($room['name']) ?>')">
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
    function deleteRoom(id, name) {
        if (confirm(`Êtes-vous sûr de vouloir supprimer la salle "${name}" ?\n\nCette action est irréversible.`)) {
            fetch(`<?= base_url("admin/rooms/delete/") ?>${id}`, {
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
                        icon: 'error',
                        title: 'Erreur',
                        text: 'Erreur: ' + data.message
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: 'Erreur lors de la suppression'
                });
                console.error(error);
            });
        }
    }
</script>
';
?>
<?= view('admin/layouts/footer', compact('additionalJS')) ?>
