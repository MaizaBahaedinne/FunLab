<?php
$title = 'Configuration Email';
$pageTitle = 'Configuration Email';
$activeMenu = 'settings-mail';
$breadcrumbs = ['Admin' => base_url('admin'), 'Param\u00e8tres' => base_url('admin/settings'), 'Email' => null];
?>

<?= view('admin/layouts/header', compact('title')) ?>
<?= view('admin/layouts/sidebar', compact('activeMenu')) ?>
<?= view('admin/layouts/topbar', compact('pageTitle', 'breadcrumbs')) ?>

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

            <?= view('admin/settings/tabs/mail', ['settings' => $settings]) ?>

<?= view('admin/layouts/footer') ?>
