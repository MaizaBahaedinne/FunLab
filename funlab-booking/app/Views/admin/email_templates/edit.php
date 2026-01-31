<?php
$title = 'Modifier le template';
?>

<div class="container-fluid p-4">
    <div class="mb-4">
        <a href="<?= base_url('admin/email-templates') ?>" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0"><i class="bi bi-pencil"></i> Modifier le template : <?= esc($template['name']) ?></h4>
        </div>
        <div class="card-body">
            <?php if (session()->has('errors')): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach (session('errors') as $error): ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?= base_url('admin/email-templates/update/' . $template['id']) ?>">
                <?= csrf_field() ?>

                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label class="form-label">Nom technique <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" value="<?= esc($template['name']) ?>" disabled>
                            <small class="text-muted">Le nom technique ne peut pas être modifié</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Sujet de l'email <span class="text-danger">*</span></label>
                            <input type="text" name="subject" class="form-control" 
                                   value="<?= esc($template['subject']) ?>" required>
                            <small class="text-muted">Utilisez {{variable}} pour insérer des variables dynamiques</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="2"><?= esc($template['description']) ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Corps HTML <span class="text-danger">*</span></label>
                            <textarea name="body" id="emailBody" class="form-control" rows="20" required><?= esc($template['body']) ?></textarea>
                            <small class="text-muted">HTML complet du template</small>
                        </div>

                        <div class="form-check mb-3">
                            <input type="checkbox" name="isActive" class="form-check-input" id="isActive" 
                                   <?= $template['isActive'] ? 'checked' : '' ?> value="1">
                            <label class="form-check-label" for="isActive">
                                Template actif
                            </label>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card bg-light">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="bi bi-code-square"></i> Variables disponibles</h6>
                            </div>
                            <div class="card-body">
                                <?php if (!empty($variables)): ?>
                                    <p class="small">Cliquez pour copier :</p>
                                    <?php foreach ($variables as $var): ?>
                                        <button type="button" class="btn btn-sm btn-outline-primary mb-2 w-100 text-start"
                                                onclick="copyVariable('<?= $var ?>')">
                                            <i class="bi bi-clipboard"></i> {{<?= $var ?>}}
                                        </button>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p class="text-muted small mb-0">Aucune variable définie</p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="card bg-light mt-3">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="bi bi-info-circle"></i> Aide</h6>
                            </div>
                            <div class="card-body">
                                <p class="small mb-2"><strong>Variables :</strong></p>
                                <p class="small">Utilisez {{nomVariable}} dans le sujet ou le corps</p>
                                
                                <p class="small mb-2 mt-3"><strong>HTML :</strong></p>
                                <p class="small">Le template supporte le HTML complet avec CSS inline</p>
                                
                                <p class="small mb-2 mt-3"><strong>Test :</strong></p>
                                <p class="small">Sauvegardez puis utilisez le bouton "Test" pour envoyer un email de démonstration</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="<?= base_url('admin/email-templates') ?>" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Annuler
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function copyVariable(varName) {
    const text = '{{' + varName + '}}';
    navigator.clipboard.writeText(text).then(() => {
        // Feedback visuel
        const btn = event.target.closest('button');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="bi bi-check"></i> Copié !';
        btn.classList.remove('btn-outline-primary');
        btn.classList.add('btn-success');
        
        setTimeout(() => {
            btn.innerHTML = originalText;
            btn.classList.remove('btn-success');
            btn.classList.add('btn-outline-primary');
        }, 1500);
    });
}

// Auto-resize textarea
document.getElementById('emailBody').addEventListener('input', function() {
    this.style.height = 'auto';
    this.style.height = (this.scrollHeight) + 'px';
});
</script>
