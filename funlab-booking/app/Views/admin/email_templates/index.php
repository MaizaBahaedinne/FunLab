<?php
$title = 'Templates d\'emails';
$pageTitle = 'Templates d\'emails';
$activeMenu = 'email-templates';
?>

<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-envelope-paper"></i> Templates d'emails</h2>
    </div>

    <?php if (session()->has('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle"></i> <?= session('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->has('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle"></i> <?= session('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Nom du template</th>
                            <th>Sujet</th>
                            <th>Description</th>
                            <th>Statut</th>
                            <th>Dernière modification</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($templates)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                                    <p>Aucun template trouvé</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($templates as $template): ?>
                                <tr>
                                    <td>
                                        <strong><?= esc($template['name']) ?></strong>
                                    </td>
                                    <td><?= esc($template['subject']) ?></td>
                                    <td>
                                        <small class="text-muted">
                                            <?= esc($template['description'] ?? '-') ?>
                                        </small>
                                    </td>
                                    <td>
                                        <?php if ($template['isActive']): ?>
                                            <span class="badge bg-success">Actif</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Inactif</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            <?= date('d/m/Y H:i', strtotime($template['updatedAt'])) ?>
                                        </small>
                                    </td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-info" 
                                                onclick="previewTemplate(<?= $template['id'] ?>)"
                                                title="Aperçu">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <a href="<?= base_url('admin/email-templates/edit/' . $template['id']) ?>" 
                                           class="btn btn-sm btn-primary"
                                           title="Modifier">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button class="btn btn-sm btn-success" 
                                                onclick="testTemplate(<?= $template['id'] ?>)"
                                                title="Envoyer un test">
                                            <i class="bi bi-send"></i>
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
</div>

<!-- Modal Aperçu -->
<div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Aperçu du template</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="previewContent">
                <div class="text-center py-5">
                    <div class="spinner-border" role="status"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Test -->
<div class="modal fade" id="testModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Envoyer un email de test</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Entrez l'adresse email où envoyer le test :</p>
                <input type="email" class="form-control" id="testEmail" placeholder="exemple@email.com">
                <input type="hidden" id="testTemplateId">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" onclick="sendTestEmail()">
                    <i class="bi bi-send"></i> Envoyer
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function previewTemplate(id) {
    const modal = new bootstrap.Modal(document.getElementById('previewModal'));
    modal.show();
    
    document.getElementById('previewContent').innerHTML = '<div class="text-center py-5"><div class="spinner-border"></div></div>';
    
    fetch(`<?= base_url('admin/email-templates/preview/') ?>${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('previewContent').innerHTML = data.html;
            } else {
                document.getElementById('previewContent').innerHTML = 
                    '<div class="alert alert-danger">' + data.error + '</div>';
            }
        })
        .catch(error => {
            document.getElementById('previewContent').innerHTML = 
                '<div class="alert alert-danger">Erreur lors du chargement</div>';
        });
}

function testTemplate(id) {
    document.getElementById('testTemplateId').value = id;
    const modal = new bootstrap.Modal(document.getElementById('testModal'));
    modal.show();
}

function sendTestEmail() {
    const email = document.getElementById('testEmail').value;
    const templateId = document.getElementById('testTemplateId').value;
    
    if (!email) {
        alert('Veuillez entrer une adresse email');
        return;
    }
    
    fetch(`<?= base_url('admin/email-templates/test/') ?>${templateId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `email=${encodeURIComponent(email)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            bootstrap.Modal.getInstance(document.getElementById('testModal')).hide();
        } else {
            alert('Erreur : ' + data.message);
        }
    })
    .catch(error => {
        alert('Erreur lors de l\'envoi');
    });
}
</script>
