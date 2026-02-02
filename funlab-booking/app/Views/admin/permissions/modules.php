<div class="admin-main">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <p class="text-muted mb-0">
                <i class="bi bi-info-circle"></i> 
                Personnalisez l'affichage et les informations des modules de permissions
            </p>
        </div>
        <a href="<?= base_url('admin/permissions') ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Retour aux Permissions
        </a>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Liste des modules -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <h5 class="card-title mb-4">
                <i class="bi bi-grid-3x3"></i> Modules de Permissions
            </h5>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th style="width: 80px;">Icône</th>
                            <th>Clé</th>
                            <th>Nom</th>
                            <th>Description</th>
                            <th style="width: 100px;">Ordre</th>
                            <th style="width: 100px;">Statut</th>
                            <th style="width: 100px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($modules)): ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                                    <p class="mt-2">Aucun module trouvé</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($modules as $module): ?>
                                <tr id="module-<?= $module['id'] ?>">
                                    <td><?= $module['id'] ?></td>
                                    <td class="text-center">
                                        <i class="bi bi-<?= esc($module['icon'] ?? 'circle') ?>" style="font-size: 1.5rem;"></i>
                                    </td>
                                    <td>
                                        <code><?= esc($module['key']) ?></code>
                                    </td>
                                    <td>
                                        <strong><?= esc($module['name']) ?></strong>
                                    </td>
                                    <td>
                                        <small class="text-muted"><?= esc($module['description'] ?? '-') ?></small>
                                    </td>
                                    <td>
                                        <input type="number" 
                                               class="form-control form-control-sm" 
                                               value="<?= $module['sort_order'] ?>"
                                               onchange="updateModule(<?= $module['id'] ?>, 'sort_order', this.value)"
                                               style="width: 70px;">
                                    </td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" 
                                                   type="checkbox" 
                                                   <?= $module['is_active'] ? 'checked' : '' ?>
                                                   onchange="updateModule(<?= $module['id'] ?>, 'is_active', this.checked ? 1 : 0)">
                                        </div>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary" 
                                                onclick="editModule(<?= $module['id'] ?>)">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Aide -->
    <div class="card border-0 shadow-sm mt-4">
        <div class="card-body">
            <h6><i class="bi bi-info-circle"></i> À propos des modules</h6>
            <ul class="mb-0 small text-muted">
                <li><strong>Clé</strong> : Identifiant unique utilisé dans le code (non modifiable)</li>
                <li><strong>Nom</strong> : Libellé affiché dans l'interface</li>
                <li><strong>Icône</strong> : Icône Bootstrap Icons (sans le préfixe "bi-")</li>
                <li><strong>Ordre</strong> : Ordre d'affichage dans les listes</li>
                <li><strong>Statut</strong> : Actif/Inactif - Les modules inactifs ne sont pas utilisables</li>
            </ul>
        </div>
    </div>
</div>

<!-- Modal d'édition -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modifier le Module</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editForm">
                    <input type="hidden" id="edit_id">
                    
                    <div class="mb-3">
                        <label class="form-label">Clé</label>
                        <input type="text" class="form-control" id="edit_key" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Nom <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_name" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" id="edit_description" rows="2"></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Icône Bootstrap</label>
                        <div class="input-group">
                            <span class="input-group-text">bi-</span>
                            <input type="text" class="form-control" id="edit_icon" placeholder="controller">
                        </div>
                        <small class="text-muted">
                            Voir les icônes sur <a href="https://icons.getbootstrap.com/" target="_blank">icons.getbootstrap.com</a>
                        </small>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Ordre d'affichage</label>
                            <input type="number" class="form-control" id="edit_sort_order" value="0">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Statut</label>
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" id="edit_is_active">
                                <label class="form-check-label">Actif</label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" onclick="saveModule()">
                    <i class="bi bi-save"></i> Sauvegarder
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let editModal;

document.addEventListener('DOMContentLoaded', function() {
    editModal = new bootstrap.Modal(document.getElementById('editModal'));
});

function editModule(id) {
    // Récupérer les données de la ligne
    const row = document.querySelector('#module-' + id);
    if (!row) return;
    
    const cells = row.querySelectorAll('td');
    const key = cells[2].querySelector('code').textContent;
    const name = cells[3].querySelector('strong').textContent;
    const description = cells[4].querySelector('small').textContent;
    const icon = cells[1].querySelector('i').className.replace('bi bi-', '');
    const sortOrder = cells[5].querySelector('input').value;
    const isActive = cells[6].querySelector('input').checked;
    
    // Remplir le formulaire
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_key').value = key;
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_description').value = description === '-' ? '' : description;
    document.getElementById('edit_icon').value = icon;
    document.getElementById('edit_sort_order').value = sortOrder;
    document.getElementById('edit_is_active').checked = isActive;
    
    editModal.show();
}

function saveModule() {
    const id = document.getElementById('edit_id').value;
    const formData = new FormData();
    
    formData.append('name', document.getElementById('edit_name').value);
    formData.append('description', document.getElementById('edit_description').value);
    formData.append('icon', document.getElementById('edit_icon').value);
    formData.append('sort_order', document.getElementById('edit_sort_order').value);
    formData.append('is_active', document.getElementById('edit_is_active').checked ? 1 : 0);
    
    fetch('<?= base_url('admin/permissions/modules/update/') ?>' + id, {
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
                timer: 1500,
                showConfirmButton: false
            }).then(() => {
                editModal.hide();
                location.reload();
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

function updateModule(id, field, value) {
    const formData = new FormData();
    formData.append(field, value);
    
    fetch('<?= base_url('admin/permissions/modules/update/') ?>' + id, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Notification discrète
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true
            });
            Toast.fire({
                icon: 'success',
                title: 'Mis à jour'
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
</script>
