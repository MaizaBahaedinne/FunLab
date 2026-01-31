<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid mt-4">
    <div class="row">
        <!-- Sidebar Navigation -->
        <div class="col-md-3">
            <div class="card shadow-sm sticky-top" style="top: 20px;">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-book"></i> Documentation</h5>
                </div>
                <div class="list-group list-group-flush">
                    <?php foreach ($pages as $slug => $name): ?>
                    <a href="<?= base_url('admin/wiki/' . $slug) ?>" 
                       class="list-group-item list-group-item-action <?= $currentPage === $slug ? 'active' : '' ?>">
                        <?= esc($name) ?>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Content Area -->
        <div class="col-md-9">
            <div class="card shadow-sm">
                <div class="card-body">
                    <?= $this->include('admin/wiki/pages/' . $currentPage) ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
