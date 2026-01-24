<?php
$title = 'Horaires d\u0027ouverture';
$pageTitle = 'Horaires d\u0027ouverture';
$activeMenu = 'settings-hours';
$breadcrumbs = ['Admin' => base_url('admin'), 'Param\u00e8tres' => base_url('admin/settings'), 'Horaires' => null];
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

                <?= view('admin/settings/tabs/hours', ['settings' => $settings]) ?>
            </div>

<?= view('admin/layouts/footer') ?>
