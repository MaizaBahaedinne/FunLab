<?php
$activeMenu = 'pages';
$pageTitle = 'Gestion des pages';
$breadcrumbs = ['Admin' => base_url('admin/dashboard'), 'Pages' => null];
?>

<?= view('admin/layouts/header', compact('title')) ?>
<?= view('admin/layouts/sidebar', compact('activeMenu')) ?>
<?= view('admin/layouts/topbar', compact('pageTitle', 'breadcrumbs')) ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-file-text"></i> Gestion des pages</h5>
                    <a href="<?= base_url('admin/pages/create') ?>" class="btn btn-light btn-sm">
                        <i class="bi bi-plus-circle"></i> Nouvelle page
                    </a>
                </div>
                <div class="card-body">
                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success alert-dismissible">
                            <?= session()->getFlashdata('success') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (empty($pages)): ?>
                        <div class="text-center py-5">
                            <i class="bi bi-file-earmark-x display-1 text-muted"></i>
                            <p class="text-muted mt-3">Aucune page créée</p>
                            <a href="<?= base_url('admin/pages/create') ?>" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Créer votre première page
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Titre</th>
                                        <th>Slug</th>
                                        <th>Template</th>
                                        <th>Statut</th>
                                        <th>Date</th>
                                        <th width="150">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($pages as $page): ?>
                                    <tr>
                                        <td>
                                            <strong><?= esc($page['title']) ?></strong>
                                        </td>
                                        <td>
                                            <code>/<?= esc($page['slug']) ?></code>
                                        </td>
                                        <td><?= esc($page['template']) ?></td>
                                        <td>
                                            <?php if ($page['status'] === 'published'): ?>
                                                <span class="badge bg-success">Publié</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Brouillon</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                <?= date('d/m/Y H:i', strtotime($page['created_at'])) ?>
                                            </small>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <?php if ($page['status'] === 'published'): ?>
                                                <a href="<?= base_url($page['slug']) ?>" target="_blank" 
                                                   class="btn btn-info" title="Voir">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <?php endif; ?>
                                                <a href="<?= base_url('admin/pages/edit/' . $page['id']) ?>" 
                                                   class="btn btn-warning" title="Modifier">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <button onclick="deletePage(<?= $page['id'] ?>)" 
                                                        class="btn btn-danger" title="Supprimer">
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
    </div>
</div>

<script>
function deletePage(id) {
    Swal.fire({
        title: 'Confirmer la suppression',
        text: 'Êtes-vous sûr de vouloir supprimer cette page ?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Oui, supprimer',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '<?= base_url('admin/pages/delete/') ?>' + id;
        }
    });
}
</script>

<?= view('admin/layouts/footer') ?>
