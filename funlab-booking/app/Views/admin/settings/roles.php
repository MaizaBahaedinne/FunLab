<?php
$pageTitle = $title ?? 'Rôles & Permissions';
$activeMenu = 'settings-roles';
$breadcrumbs = ['Admin' => base_url('admin'), 'Paramètres' => base_url('admin/settings'), 'Rôles & Permissions' => null];
?>

<?= view('admin/layouts/header', compact('title')) ?>
<?= view('admin/layouts/sidebar', compact('activeMenu')) ?>
<?= view('admin/layouts/topbar', compact('pageTitle', 'breadcrumbs')) ?>

                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <?= session()->getFlashdata('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-body">
                        <p class="text-muted">Gérez les permissions d'accès aux différents modules pour chaque rôle utilisateur.</p>

                        <form action="/admin/settings/update-role-permissions" method="POST">
                            <?php foreach ($roles as $role): ?>
                                <div class="card mb-3">
                                    <div class="card-header bg-<?= $role['name'] === 'admin' ? 'danger' : ($role['name'] === 'staff' ? 'warning' : 'secondary') ?> text-white">
                                        <h5 class="mb-0">
                                            <i class="bi bi-person-badge"></i> <?= esc($role['label']) ?>
                                            <small class="float-end"><?= esc($role['description']) ?></small>
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Module</th>
                                                    <th class="text-center">Voir</th>
                                                    <th class="text-center">Créer</th>
                                                    <th class="text-center">Modifier</th>
                                                    <th class="text-center">Supprimer</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($modules as $moduleKey => $moduleName): ?>
                                                    <tr>
                                                        <td><strong><?= esc($moduleName) ?></strong></td>
                                                        <?php 
                                                        $rolePerms = $permissions[$role['name']][$moduleKey] ?? [];
                                                        ?>
                                                        <td class="text-center">
                                                            <input type="checkbox" class="form-check-input" 
                                                                name="permissions[<?= $role['name'] ?>][<?= $moduleKey ?>][]" 
                                                                value="view"
                                                                <?= in_array('view', $rolePerms) ? 'checked' : '' ?>>
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="checkbox" class="form-check-input" 
                                                                name="permissions[<?= $role['name'] ?>][<?= $moduleKey ?>][]" 
                                                                value="create"
                                                                <?= in_array('create', $rolePerms) ? 'checked' : '' ?>>
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="checkbox" class="form-check-input" 
                                                                name="permissions[<?= $role['name'] ?>][<?= $moduleKey ?>][]" 
                                                                value="edit"
                                                                <?= in_array('edit', $rolePerms) ? 'checked' : '' ?>>
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="checkbox" class="form-check-input" 
                                                                name="permissions[<?= $role['name'] ?>][<?= $moduleKey ?>][]" 
                                                                value="delete"
                                                                <?= in_array('delete', $rolePerms) ? 'checked' : '' ?>>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            <?php endforeach; ?>

                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i> Sauvegarder les permissions
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

<?= view('admin/layouts/footer') ?>
