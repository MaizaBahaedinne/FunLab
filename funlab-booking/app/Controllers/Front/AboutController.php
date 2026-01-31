<?php

namespace App\Controllers\Front;

use App\Controllers\BaseController;
use App\Models\SettingModel;

class AboutController extends BaseController
{
    public function index()
    {
        $settingModel = new SettingModel();
        $settings = $settingModel->getByCategoryAsArray('about');
        
        $data = [
            'title' => $settings['about_title'] ?? 'À Propos - FunLab Tunisie',
            'settings' => $settings,
            'activeMenu' => 'about'
        ];
        
        return view('front/about', $data);
    }
}
