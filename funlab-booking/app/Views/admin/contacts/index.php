<?= view('admin/layouts/header', compact('title')) ?>

<div class="container-fluid">
    <div class="row">
        <?= view('admin/layouts/sidebar', compact('activeMenu')) ?>
        
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 admin-content">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">
                    <i class="bi bi-chat-left-text text-primary"></i> Messages de Contact
                </h1>
                <span class="badge bg-warning fs-6">
                    <?= $unreadCount ?> non lu<?= $unreadCount > 1 ? 's' : '' ?>
                </span>
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
                    <?php if (empty($messages)): ?>
                        <div class="text-center py-5">
                            <i class="bi bi-chat-left-dots text-muted" style="font-size: 4rem;"></i>
                            <p class="text-muted mt-3">Aucun message pour le moment</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Statut</th>
                                        <th>Nom</th>
                                        <th>Email</th>
                                        <th>Sujet</th>
                                        <th>Date</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($messages as $message): ?>
                                        <tr class="<?= $message['status'] === 'new' ? 'table-warning' : '' ?>">
                                            <td>
                                                <?php if ($message['status'] === 'new'): ?>
                                                    <span class="badge bg-warning">Nouveau</span>
                                                <?php elseif ($message['status'] === 'read'): ?>
                                                    <span class="badge bg-info">Lu</span>
                                                <?php else: ?>
                                                    <span class="badge bg-success">Répondu</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <i class="bi bi-person me-2"></i>
                                                <?= esc($message['name']) ?>
                                            </td>
                                            <td>
                                                <small><?= esc($message['email']) ?></small>
                                            </td>
                                            <td>
                                                <strong><?= esc($message['subject']) ?></strong>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    <?= date('d/m/Y H:i', strtotime($message['created_at'])) ?>
                                                </small>
                                            </td>
                                            <td class="text-end">
                                                <div class="btn-group btn-group-sm">
                                                    <a href="<?= base_url('admin/contacts/view/' . $message['id']) ?>" 
                                                       class="btn btn-primary">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <button class="btn btn-danger" 
                                                            onclick="deleteMessage(<?= $message['id'] ?>)">
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
        </main>
    </div>
</div>

<script>
function deleteMessage(id) {
    if (!confirm('Êtes-vous sûr de vouloir supprimer ce message ?')) {
        return;
    }

    fetch(`<?= base_url('admin/contacts/delete/') ?>${id}`, {
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
