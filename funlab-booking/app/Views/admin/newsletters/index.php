<?= view('admin/layouts/header', compact('title')) ?>

<div class="container-fluid">
    <div class="row">
        <?= view('admin/layouts/sidebar', compact('activeMenu')) ?>
        
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 admin-content">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">
                    <i class="bi bi-envelope-heart text-primary"></i> Abonnés Newsletter
                </h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <a href="<?= base_url('admin/newsletters/export') ?>" class="btn btn-sm btn-success">
                            <i class="bi bi-download"></i> Exporter (CSV)
                        </a>
                    </div>
                    <span class="badge bg-primary fs-6">
                        <?= $activeCount ?> abonné<?= $activeCount > 1 ? 's' : '' ?> actif<?= $activeCount > 1 ? 's' : '' ?>
                    </span>
                </div>
            </div>

            <?php if (session()->has('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= session('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->has('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= session('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-body">
                    <?php if (empty($subscribers)): ?>
                        <div class="text-center py-5">
                            <i class="bi bi-envelope-x text-muted" style="font-size: 4rem;"></i>
                            <p class="text-muted mt-3">Aucun abonné pour le moment</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Email</th>
                                        <th>Statut</th>
                                        <th>Date d'inscription</th>
                                        <th>Adresse IP</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($subscribers as $subscriber): ?>
                                        <tr>
                                            <td>
                                                <i class="bi bi-envelope me-2"></i>
                                                <?= esc($subscriber['email']) ?>
                                            </td>
                                            <td>
                                                <?php if ($subscriber['status'] === 'active'): ?>
                                                    <span class="badge bg-success">Actif</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Désabonné</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    <?= date('d/m/Y H:i', strtotime($subscriber['subscribed_at'])) ?>
                                                </small>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    <?= esc($subscriber['ip_address'] ?? '-') ?>
                                                </small>
                                            </td>
                                            <td class="text-end">
                                                <button class="btn btn-sm btn-danger" 
                                                        onclick="deleteSubscriber(<?= $subscriber['id'] ?>)">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
function deleteSubscriber(id) {
    if (!confirm('Êtes-vous sûr de vouloir supprimer cet abonné ?')) {
        return;
    }

    fetch(`<?= base_url('admin/newsletters/delete/') ?>${id}`, {
        method: 'DELETE',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || 'Erreur lors de la suppression');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erreur lors de la suppression');
    });
}
</script>

<?= view('admin/layouts/footer') ?>
