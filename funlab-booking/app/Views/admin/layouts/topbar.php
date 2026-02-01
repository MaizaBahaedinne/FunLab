<?php helper('permission'); ?>
        <!-- Main Content -->
        <div class="admin-content">
            <!-- Top Bar -->
            <div class="admin-topbar">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0"><?= $pageTitle ?? 'Admin' ?></h5>
                        <?php if (isset($breadcrumbs) && is_array($breadcrumbs)): ?>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0 small">
                                <?php foreach ($breadcrumbs as $item): ?>
                                    <?php if (is_array($item)): ?>
                                        <?php if (!empty($item['url'])): ?>
                                            <li class="breadcrumb-item"><a href="<?= $item['url'] ?>"><?= $item['title'] ?></a></li>
                                        <?php else: ?>
                                            <li class="breadcrumb-item active"><?= $item['title'] ?></li>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </ol>
                        </nav>
                        <?php endif; ?>
                    </div>
                    
                    <div class="d-flex align-items-center gap-3">
                        <?php if (session()->get('impersonating_admin_id')): ?>
                            <div class="alert alert-warning mb-0 py-1 px-3 d-flex align-items-center gap-2">
                                <i class="bi bi-exclamation-triangle-fill"></i>
                                <span>Mode impersonation actif</span>
                                <a href="/admin/settings/stop-impersonation" class="btn btn-sm btn-warning">
                                    <i class="bi bi-arrow-left-circle"></i> Retour au compte admin
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <div class="dropdown">
                            <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i>
                                <?= session()->get('firstName') . ' ' . session()->get('lastName') ?>
                                <?php 
                                $role = session()->get('role');
                                $roleLabel = $role === 'admin' ? 'Admin' : ($role === 'staff' ? 'Staff' : 'Utilisateur');
                                ?>
                                <span class="badge bg-<?= $role === 'admin' ? 'danger' : 'warning' ?> ms-2"><?= $roleLabel ?></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li class="dropdown-header">
                                    <small class="text-muted"><?= session()->get('email') ?></small>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="<?= base_url('account/profile') ?>">
                                    <i class="bi bi-person"></i> Mon Profil
                                </a></li>
                                <?php if (hasPermission('settings', 'view')): ?>
                                <li><a class="dropdown-item" href="<?= base_url('admin/settings/general') ?>">
                                    <i class="bi bi-gear"></i> Paramètres
                                </a></li>
                                <?php endif; ?>
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
