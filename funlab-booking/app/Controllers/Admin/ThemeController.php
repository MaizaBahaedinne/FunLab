<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SettingModel;

class ThemeController extends BaseController
{
    protected $settingModel;

    public function __construct()
    {
        $this->settingModel = new SettingModel();
    }

    // Options générales du thème
    public function index()
    {
        if ($redirect = checkPermissionOrRedirect('settings', 'view')) {
            return $redirect;
        }

        $data = [
            'title' => 'Options du thème',
            'settings' => $this->settingModel->getByCategoryAsArray('theme')
        ];

        return view('admin/theme/index', $data);
    }

    // Logo & Branding
    public function branding()
    {
        if ($redirect = checkPermissionOrRedirect('settings', 'view')) {
            return $redirect;
        }

        $data = [
            'title' => 'Logo & Branding',
            'settings' => $this->settingModel->getByCategoryAsArray('branding')
        ];

        return view('admin/theme/branding', $data);
    }

    // Couleurs
    public function colors()
    {
        if ($redirect = checkPermissionOrRedirect('settings', 'view')) {
            return $redirect;
        }

        $data = [
            'title' => 'Couleurs du thème',
            'settings' => $this->settingModel->getByCategoryAsArray('colors')
        ];

        return view('admin/theme/colors', $data);
    }

    // Typographie
    public function typography()
    {
        if ($redirect = checkPermissionOrRedirect('settings', 'view')) {
            return $redirect;
        }

        $data = [
            'title' => 'Typographie',
            'settings' => $this->settingModel->getByCategoryAsArray('typography')
        ];

        return view('admin/theme/typography', $data);
    }

    // Header
    public function header()
    {
        if ($redirect = checkPermissionOrRedirect('settings', 'view')) {
            return $redirect;
        }

        $data = [
            'title' => 'En-tête',
            'settings' => $this->settingModel->getByCategoryAsArray('header')
        ];

        return view('admin/theme/header', $data);
    }

    // Footer
    public function footer()
    {
        if ($redirect = checkPermissionOrRedirect('settings', 'view')) {
            return $redirect;
        }

        $data = [
            'title' => 'Pied de page',
            'settings' => $this->settingModel->getByCategoryAsArray('footer')
        ];

        return view('admin/theme/footer', $data);
    }

    // Sauvegarder les options
    public function save()
    {
        if ($redirect = checkPermissionOrRedirect('settings', 'edit')) {
            return $redirect;
        }

        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        $postData = $this->request->getPost();
        
        try {
            foreach ($postData as $key => $value) {
                if ($key !== 'csrf_test_name') {
                    $this->settingModel->updateSetting($key, $value);
                }
            }

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Options sauvegardées avec succès'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}
