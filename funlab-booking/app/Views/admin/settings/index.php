<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?> - FunLab Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body>
    <?= view('admin/partials/navbar') ?>

    <div class="container-fluid">
        <div class="row">
            <?= view('admin/partials/sidebar') ?>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2"><i class="bi bi-gear"></i> Paramètres</h1>
                </div>

                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <?= session()->getFlashdata('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <?= session()->getFlashdata('error') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Tabs Navigation -->
                <ul class="nav nav-tabs mb-4" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link <?= $activeTab === 'general' ? 'active' : '' ?>" href="?tab=general">
                            <i class="bi bi-info-circle"></i> Général
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $activeTab === 'hours' ? 'active' : '' ?>" href="?tab=hours">
                            <i class="bi bi-clock"></i> Horaires
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $activeTab === 'mail' ? 'active' : '' ?>" href="?tab=mail">
                            <i class="bi bi-envelope"></i> Email
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $activeTab === 'mail_template' ? 'active' : '' ?>" href="?tab=mail_template">
                            <i class="bi bi-file-earmark-text"></i> Templates Email
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $activeTab === 'sms' ? 'active' : '' ?>" href="?tab=sms">
                            <i class="bi bi-phone"></i> SMS
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $activeTab === 'seo' ? 'active' : '' ?>" href="?tab=seo">
                            <i class="bi bi-search"></i> SEO
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/settings/users">
                            <i class="bi bi-people"></i> Utilisateurs
                        </a>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content">
                    <?php if ($activeTab === 'general'): ?>
                        <?= view('admin/settings/tabs/general', ['settings' => $settings['general']]) ?>
                    <?php elseif ($activeTab === 'hours'): ?>
                        <?= view('admin/settings/tabs/hours', ['settings' => $settings['hours']]) ?>
                    <?php elseif ($activeTab === 'mail'): ?>
                        <?= view('admin/settings/tabs/mail', ['settings' => $settings['mail']]) ?>
                    <?php elseif ($activeTab === 'mail_template'): ?>
                        <?= view('admin/settings/tabs/mail_template', ['settings' => $settings['mail_template']]) ?>
                    <?php elseif ($activeTab === 'sms'): ?>
                        <?= view('admin/settings/tabs/sms', ['settings' => $settings['sms']]) ?>
                    <?php elseif ($activeTab === 'seo'): ?>
                        <?= view('admin/settings/tabs/seo', ['settings' => $settings['seo']]) ?>
                    <?php endif; ?>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
