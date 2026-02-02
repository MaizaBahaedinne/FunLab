<div class="admin-main">
    <!-- Header avec bouton sync -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <p class="text-muted mb-0">
                <i class="bi bi-info-circle"></i> 
                Système de permissions dynamique - Les modules sont détectés automatiquement
            </p>
        </div>
        <button class="btn btn-outline-primary" onclick="syncModules()">
            <i class="bi bi-arrow-clockwise"></i> Synchroniser les Modules
        </button>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Tabs pour chaque rôle -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <ul class="nav nav-tabs mb-4" role="tablist">
                <?php foreach ($roles as $index => $role): ?>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link <?= $index === 0 ? 'active' : '' ?>" 
                                id="role-<?= $role['id'] ?>-tab" 
                                data-bs-toggle="tab" 
                                data-bs-target="#role-<?= $role['id'] ?>" 
                                type="button">
                            <i class="bi bi-person-badge"></i> <?= esc($role['name']) ?>
                            <?php if ($role['is_system']): ?>
                                <span class="badge bg-secondary ms-1">Système</span>
                            <?php endif; ?>
                        </button>
                    </li>
                <?php endforeach; ?>
                <li class="nav-item ms-auto">
                    <a href="<?= base_url('admin/permissions/modules') ?>" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-gear"></i> Gérer les Modules
                    </a>
                </li>
            </ul>

            <div class="tab-content">
                <?php foreach ($roles as $index => $role): ?>
                    <div class="tab-pane fade <?= $index === 0 ? 'show active' : '' ?>" 
                         id="role-<?= $role['id'] ?>" 
                         role="tabpanel">
                        
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> 
                            <strong><?= esc($role['name']) ?>:</strong> <?= esc($role['description']) ?>
                        </div>

                        <?php if ($role['key'] === 'admin'): ?>
                            <div class="alert alert-warning">
                                <i class="bi bi-shield-lock"></i> 
                                Les permissions administrateur sont complètes et non modifiables pour des raisons de sécurité.
                            </div>
                        <?php endif; ?>

                        <form id="permissionForm<?= $role['id'] ?>" onsubmit="savePermissions(event, <?= $role['id'] ?>)" <?= $role['key'] === 'admin' ? 'style="pointer-events: none; opacity: 0.6;"' : '' ?>>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 200px;">Module</th>
                                            <?php foreach ($actions as $action): ?>
                                                <th class="text-center" style="width: 100px;">
                                                    <?= esc($action['name']) ?>
                                                    <small class="d-block text-muted" style="font-weight: normal;">
                                                        <?= esc($action['key']) ?>
                                                    </small>
                                                </th>
                                            <?php endforeach; ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($modules as $module): ?>
                                            <tr>
                                                <td>
                                                    <i class="bi bi-<?= esc($module['icon'] ?? 'circle') ?>"></i>
                                                    <strong><?= esc($module['name']) ?></strong>
                                                    <br>
                                                    <small class="text-muted"><?= esc($module['key']) ?></small>
                                                </td>
                                                <?php 
                                                $rolePerms = $permissions[$role['id']][$module['key']] ?? [];
                                                foreach ($actions as $action): 
                                                ?>
                                                    <td class="text-center">
                                                        <input type="checkbox" 
                                                               class="form-check-input" 
                                                               name="permissions[<?= $module['id'] ?>][]" 
                                                               value="<?= $action['id'] ?>"
                                                               <?= in_array($action['key'], $rolePerms) ? 'checked' : '' ?>
                                                               <?= $role['key'] === 'admin' ? 'disabled' : '' ?>>
                                                    </td>
                                                <?php endforeach; ?>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>

                            <?php if ($role['key'] !== 'admin'): ?>
                            <div class="text-end mt-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i> Sauvegarder les Permissions
                                </button>
                            </div>
                            <?php endif; ?>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<script>
function savePermissions(event, roleId) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    formData.append('role_id', roleId);
    
    fetch('<?= base_url('admin/permissions/update') ?>', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Succès!',
                text: data.message,
                timer: 2000,
                showConfirmButton: false
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Erreur',
                text: data.message
            });
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Erreur',
            text: 'Une erreur est survenue'
        });
    });
}

function syncModules() {
    Swal.fire({
        title: 'Synchronisation...',
        text: 'Détection des nouveaux modules',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    fetch('<?= base_url('admin/permissions/sync') ?>', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        Swal.fire({
            icon: 'success',
            title: 'Synchronisation terminée',
            text: data.message,
            showConfirmButton: true
        }).then(() => {
            if (data.synced && data.synced.length > 0) {
                location.reload();
            }
        });
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Erreur',
            text: 'Erreur lors de la synchronisation'
        });
    });
}
</script>
