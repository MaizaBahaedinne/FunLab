<?php
$title = 'Catégories de Jeux';
$activeMenu = 'categories';
$pageTitle = 'Catégories de Jeux';
$breadcrumbs = [
    ['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
    ['title' => 'Catégories', 'url' => '']
];
?>

<?= view('admin/layouts/header', compact('title')) ?>
<?= view('admin/layouts/sidebar', compact('activeMenu')) ?>
<?= view('admin/layouts/topbar', compact('pageTitle', 'breadcrumbs')) ?>

<div class="container-fluid">
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle"></i> <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle"></i> <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-grid"></i> Catégories de Jeux</h5>
            <a href="<?= base_url('admin/game-categories/create') ?>" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Nouvelle Catégorie
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Ordre</th>
                            <th>Icône</th>
                            <th>Nom</th>
                            <th>Description</th>
                            <th>Jeux</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($categories)): ?>
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                                    <p class="text-muted mt-2">Aucune catégorie</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($categories as $category): ?>
                                <tr>
                                    <td><?= $category['display_order'] ?></td>
                                    <td>
                                        <i class="<?= esc($category['icon']) ?>" 
                                           style="font-size: 1.5rem; color: <?= esc($category['color']) ?>"></i>
                                    </td>
                                    <td>
                                        <strong><?= esc($category['name']) ?></strong>
                                    </td>
                                    <td><?= esc($category['description']) ?></td>
                                    <td>
                                        <span class="badge bg-info"><?= $category['game_count'] ?> jeux</span>
                                    </td>
                                    <td>
                                        <?php if ($category['is_active']): ?>
                                            <span class="badge bg-success">Actif</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Inactif</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="<?= base_url('admin/game-categories/edit/' . $category['id']) ?>" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="<?= base_url('admin/game-categories/delete/' . $category['id']) ?>" 
                                           class="btn btn-sm btn-outline-danger"
                                           onclick="return confirm('Supprimer cette catégorie ?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
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

<?= view('admin/layouts/footer') ?>
