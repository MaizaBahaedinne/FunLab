<?php
$title = 'Param\u00e8tres G\u00e9n\u00e9raux';
$pageTitle = 'Param\u00e8tres G\u00e9n\u00e9raux';
$activeMenu = 'settings-general';
$breadcrumbs = ['Admin' => base_url('admin'), 'Param\u00e8tres' => null];
?>

<?= view('admin/layouts/header', compact('title')) ?>
<?= view('admin/layouts/sidebar', compact('activeMenu')) ?>
<?= view('admin/layouts/topbar', compact('pageTitle', 'breadcrumbs')) ?>

            <div class="container-fluid p-4">
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

                <?= view('admin/settings/tabs/general', ['settings' => $settings]) ?>
            </div>

<?= view('admin/layouts/footer') ?>
