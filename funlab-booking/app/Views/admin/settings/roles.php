<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?> - FunLab Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body>
    <?= view('admin/partials/navbar') ?>

    <div class="container-fluid">
        <div class="row">
            <?= view('admin/partials/sidebar') ?>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2"><i class="bi bi-shield-check"></i> <?= esc($title) ?></h1>
                </div>

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
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
