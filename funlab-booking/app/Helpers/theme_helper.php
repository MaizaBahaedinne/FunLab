<?php

if (!function_exists('get_theme_settings')) {
    /**
     * Récupérer tous les paramètres du thème depuis la base de données
     * 
     * @return array
     */
    function get_theme_settings()
    {
        static $settings = null;
        
        if ($settings === null) {
            $settingModel = new \App\Models\SettingModel();
            $allSettings = $settingModel->findAll();
            
            $settings = [];
            foreach ($allSettings as $setting) {
                $settings[$setting['key']] = $setting['value'];
            }
        }
        
        return $settings;
    }
}

if (!function_exists('theme_setting')) {
    /**
     * Récupérer un paramètre spécifique du thème
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function theme_setting($key, $default = null)
    {
        $settings = get_theme_settings();
        return $settings[$key] ?? $default;
    }
}

if (!function_exists('theme_color')) {
    /**
     * Récupérer une couleur du thème
     * 
     * @param string $colorName (primary, secondary, dark, light, text, link)
     * @param string $default
     * @return string
     */
    function theme_color($colorName, $default = '#333333')
    {
        return theme_setting('color_' . $colorName, $default);
    }
}

if (!function_exists('theme_font')) {
    /**
     * Récupérer une police du thème
     * 
     * @param string $fontType (heading, body)
     * @param string $default
     * @return string
     */
    function theme_font($fontType, $default = 'Roboto')
    {
        return theme_setting('font_' . $fontType, $default);
    }
}
