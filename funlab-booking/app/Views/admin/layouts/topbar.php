        <!-- Main Content -->
        <div class="admin-content">
            <!-- Top Bar -->
            <div class="admin-topbar">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0"><?= $pageTitle ?? 'Admin' ?></h5>
                        <?php if (isset($breadcrumbs)): ?>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0 small">
                                <?php foreach ($breadcrumbs as $label => $url): ?>
                                    <?php if ($url): ?>
                                        <li class="breadcrumb-item"><a href="<?= $url ?>"><?= $label ?></a></li>
                                    <?php else: ?>
                                        <li class="breadcrumb-item active"><?= $label ?></li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </ol>
                        </nav>
                        <?php endif; ?>
                    </div>
                    
                    <div class="d-flex align-items-center gap-3">
                        <div class="dropdown">
                            <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i>
                                <?= session()->get('userName') ?? session()->get('userEmail') ?? 'Admin' ?>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="<?= base_url('admin/profile') ?>">
                                    <i class="bi bi-person"></i> Profil
                                </a></li>
                                <li><a class="dropdown-item" href="<?= base_url('admin/settings/general') ?>">
                                    <i class="bi bi-gear"></i> Paramètres
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="<?= base_url('logout') ?>">
                                    <i class="bi bi-box-arrow-right"></i> Déconnexion
                                </a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Main Content Area -->
            <div class="admin-main">
