<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SettingModel;

class ThemeController extends BaseController
{
    protected $settingModel;

    public function __construct()
    {
        helper('permission');
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
                if ($key !== 'csrf_test_name' && $key !== 'csrf_token_name') {
                    // Déterminer le type en fonction de la valeur
                    $type = 'text';
                    if (is_numeric($value) && strpos($key, 'width') !== false || strpos($key, 'height') !== false || strpos($key, 'columns') !== false || strpos($key, 'size') !== false) {
                        $type = 'number';
                    } elseif ($value === '0' || $value === '1' || strpos($key, 'show_') !== false || strpos($key, '_sticky') !== false) {
                        $type = 'boolean';
                    }
                    
                    $this->settingModel->updateSetting($key, $value, $type);
                }
            }

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Options sauvegardées avec succès'
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Theme save error: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Erreur: ' . $e->getMessage()
            ]);
        }
    }
}
