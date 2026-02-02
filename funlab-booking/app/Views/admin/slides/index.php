<?php
$activeMenu = 'pages';
$pageTitle = 'Gestion du Slider';
$breadcrumbs = ['Admin' => base_url('admin/dashboard'), 'Pages & Contenu' => null, 'Slider' => null];
?>

<?= view('admin/layouts/header', compact('title')) ?>
<?= view('admin/layouts/sidebar', compact('activeMenu')) ?>
<?= view('admin/layouts/topbar', compact('pageTitle', 'breadcrumbs')) ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible">
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-images"></i> Slides du carousel</h5>
                    <a href="<?= base_url('admin/slides/create') ?>" class="btn btn-light btn-sm">
                        <i class="bi bi-plus-circle"></i> Nouvelle Slide
                    </a>
                </div>
                <div class="card-body">
                    <?php if (!empty($slides)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover" id="slidesTable">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">Ordre</th>
                                    <th style="width: 100px;">Image</th>
                                    <th>Titre</th>
                                    <th>Sous-titre</th>
                                    <th style="width: 100px;">Statut</th>
                                    <th style="width: 150px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($slides as $slide): ?>
                                <tr data-id="<?= $slide['id'] ?>">
                                    <td>
                                        <i class="bi bi-grip-vertical" style="cursor: move;"></i>
                                        <?= $slide['order'] ?>
                                    </td>
                                    <td>
                                        <?php if ($slide['image']): ?>
                                        <img src="<?= esc($slide['image']) ?>" alt="" style="width: 80px; height: 50px; object-fit: cover; border-radius: 4px;">
                                        <?php endif; ?>
                                    </td>
                                    <td><strong><?= esc($slide['title']) ?></strong></td>
                                    <td><?= esc($slide['subtitle']) ?></td>
                                    <td>
                                        <?php if ($slide['active']): ?>
                                        <span class="badge bg-success">Actif</span>
                                        <?php else: ?>
                                        <span class="badge bg-secondary">Inactif</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="<?= base_url('admin/slides/edit/' . $slide['id']) ?>" class="btn btn-sm btn-warning">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button onclick="deleteSlide(<?= $slide['id'] ?>)" class="btn btn-sm btn-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <div class="alert alert-info text-center">
                        <i class="bi bi-info-circle"></i> Aucune slide. Créez votre première slide pour le slider de la page d'accueil.
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
function deleteSlide(id) {
    Swal.fire({
        title: 'Êtes-vous sûr?',
        text: "Cette action est irréversible!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Oui, supprimer!',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '<?= base_url('admin/slides/delete/') ?>' + id;
        }
    });
}

// Drag and drop sorting
const tbody = document.querySelector('#slidesTable tbody');
if (tbody) {
    new Sortable(tbody, {
        handle: '.bi-grip-vertical',
        animation: 150,
        onEnd: function(evt) {
            const order = Array.from(tbody.querySelectorAll('tr')).map(tr => tr.dataset.id);
            
            fetch('<?= base_url('admin/slides/updateOrder') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(order)
            });
        }
    });
}
</script>

<?= view('admin/layouts/footer') ?>
