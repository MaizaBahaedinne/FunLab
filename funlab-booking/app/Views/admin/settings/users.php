<?php
$title = 'Gestion des utilisateurs';
$pageTitle = 'Gestion des utilisateurs';
$activeMenu = 'settings-users';
$breadcrumbs = ['Admin' => base_url('admin'), 'Paramètres' => base_url('admin/settings'), 'Utilisateurs' => null];
?>

<?= view('admin/layouts/header', compact('title')) ?>
<?= view('admin/layouts/sidebar', compact('activeMenu')) ?>
<?= view('admin/layouts/topbar', compact('pageTitle', 'breadcrumbs')) ?>

                <div class="mb-3">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createUserModal">
                        <i class="bi bi-plus-circle"></i> Nouvel utilisateur
                    </button>
                </div>

                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <?= session()->getFlashdata('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <?= session()->getFlashdata('error') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nom</th>
                                    <th>Email</th>
                                    <th>Rôle</th>
                                    <th>Créé le</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $user): ?>
                                    <?php 
                                    // Gérer les différents formats de nom
                                    $userName = '';
                                    if (!empty($user['name'])) {
                                        $userName = $user['name'];
                                    } elseif (!empty($user['first_name']) || !empty($user['last_name'])) {
                                        $userName = trim(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? ''));
                                    } elseif (!empty($user['username'])) {
                                        $userName = $user['username'];
                                    } else {
                                        $userName = $user['email'];
                                    }
                                    ?>
                                    <tr>
                                        <td><?= $user['id'] ?></td>
                                        <td><?= esc($userName) ?></td>
                                        <td><?= esc($user['email']) ?></td>
                                        <td>
                                            <span class="badge bg-<?= $user['role'] === 'admin' ? 'danger' : ($user['role'] === 'staff' ? 'warning' : 'secondary') ?>">
                                                <?= ucfirst($user['role']) ?>
                                            </span>
                                        </td>
                                        <td><?= date('d/m/Y', strtotime($user['created_at'])) ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editUserModal<?= $user['id'] ?>">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <?php if ($user['id'] != session()->get('userId')): ?>
                                                <a href="/admin/settings/impersonate/<?= $user['id'] ?>" 
                                                    class="btn btn-sm btn-outline-info"
                                                    title="Se connecter en tant que">
                                                    <i class="bi bi-person-badge"></i>
                                                </a>
                                                <a href="/admin/settings/delete-user/<?= $user['id'] ?>" 
                                                    class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>

                                    <!-- Modal Edit User -->
                                    <div class="modal fade" id="editUserModal<?= $user['id'] ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="/admin/settings/update-user/<?= $user['id'] ?>" method="POST">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Modifier l'utilisateur</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label">Nom d'utilisateur</label>
                                                            <input type="text" class="form-control" name="username" 
                                                                value="<?= esc($user['username']) ?>" required>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Prénom</label>
                                                                    <input type="text" class="form-control" name="first_name" 
                                                                        value="<?= esc($user['first_name'] ?? '') ?>">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Nom</label>
                                                                    <input type="text" class="form-control" name="last_name" 
                                                                        value="<?= esc($user['last_name'] ?? '') ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Email</label>
                                                            <input type="email" class="form-control" name="email" 
                                                                value="<?= esc($user['email']) ?>" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Rôle</label>
                                                            <select class="form-select" name="role" required>
                                                                <option value="customer" <?= $user['role'] === 'customer' ? 'selected' : '' ?>>Client</option>
                                                                <option value="staff" <?= $user['role'] === 'staff' ? 'selected' : '' ?>>Staff</option>
                                                                <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Nouveau mot de passe</label>
                                                            <input type="password" class="form-control" name="password" 
                                                                placeholder="Laisser vide pour ne pas changer">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                        <button type="submit" class="btn btn-primary">Sauvegarder</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Modal Create User -->
    <div class="modal fade" id="createUserModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="/admin/settings/create-user" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">Nouvel utilisateur</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nom d'utilisateur</label>
                            <input type="text" class="form-control" name="username" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Prénom</label>
                                    <input type="text" class="form-control" name="first_name">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Nom</label>
                                    <input type="text" class="form-control" name="last_name">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Mot de passe</label>
                            <input type="password" class="form-control" name="password" required minlength="8">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Rôle</label>
                            <select class="form-select" name="role" required>
                                <option value="customer">Client</option>
                                <option value="staff">Staff</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                    </div>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Créer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?= view('admin/layouts/footer') ?>
