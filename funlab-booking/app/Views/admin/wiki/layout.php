<?php
$pageTitle = 'Documentation';
$activeMenu = 'wiki';
$breadcrumbs = ['Admin' => base_url('admin'), 'Documentation' => null];
$additionalStyles = '
.wiki-sidebar {
    position: sticky;
    top: 20px;
}
.wiki-content h1 {
    color: #667eea;
    margin-bottom: 1.5rem;
}
.wiki-content h2 {
    color: #764ba2;
    margin-top: 2rem;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #f0f0f0;
}
.wiki-content h3 {
    color: #555;
    margin-top: 1.5rem;
}
.wiki-content pre {
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 5px;
    overflow-x: auto;
}
.wiki-content code {
    background: #f8f9fa;
    padding: 2px 6px;
    border-radius: 3px;
    font-size: 0.9em;
}
.wiki-content table {
    margin: 1.5rem 0;
}
';
?>

<?= view('admin/layouts/header', compact('title', 'additionalStyles')) ?>
<?= view('admin/layouts/sidebar', compact('activeMenu')) ?>
<?= view('admin/layouts/topbar', compact('pageTitle', 'breadcrumbs')) ?>

<div class="container-fluid p-4">
    <div class="row">
        <!-- Sidebar Navigation -->
        <div class="col-md-3">
            <div class="card shadow-sm wiki-sidebar">
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
                <div class="card-body wiki-content">
                    <?= view('admin/wiki/pages/' . $currentPage) ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?= view('admin/layouts/footer') ?>
