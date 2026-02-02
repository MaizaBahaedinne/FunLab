<?php

/**
 * Vérifier si l'utilisateur a une permission spécifique
 * 
 * @param string $module Le module (ex: 'bookings', 'games', 'settings')
 * @param string $action L'action (ex: 'view', 'create', 'edit', 'delete')
 * @return bool
 */
if (!function_exists('hasPermission')) {
    function hasPermission(string $module, string $action): bool
    {
        $session = session();
        
        // Si pas connecté, pas de permission
        if (!$session->get('isLoggedIn')) {
            return false;
        }
        
        $role = $session->get('role');
        
        // Les admins ont toutes les permissions
        if ($role === 'admin') {
            return true;
        }
        
        // Récupérer les permissions définies
        $permissions = getRolePermissions();
        
        // Vérifier si le rôle a la permission
        if (isset($permissions[$role][$module])) {
            return in_array($action, $permissions[$role][$module]);
        }
        
        return false;
    }
}

/**
 * Récupérer toutes les permissions par rôle
 * 
 * @return array
 */
if (!function_exists('getRolePermissions')) {
    function getRolePermissions(): array
    {
        // Essayer de récupérer depuis la base de données
        $settingModel = new \App\Models\SettingModel();
        $permissionsJson = $settingModel->getSetting('role_permissions', 'permissions');
        
        if ($permissionsJson) {
            $permissions = json_decode($permissionsJson, true);
            if ($permissions && is_array($permissions)) {
                return $permissions;
            }
        }
        
        // Sinon, retourner les permissions par défaut
        return [
            'admin' => [
                'dashboard' => ['view', 'create', 'edit', 'delete'],
                'bookings' => ['view', 'create', 'edit', 'delete'],
                'games' => ['view', 'create', 'edit', 'delete'],
                'rooms' => ['view', 'create', 'edit', 'delete'],
                'closures' => ['view', 'create', 'edit', 'delete'],
                'reviews' => ['view', 'approve', 'delete'],
                'participants' => ['view', 'edit', 'delete'],
                'teams' => ['view', 'create', 'edit', 'delete'],
                'scanner' => ['view', 'scan'],
                'promo_codes' => ['view', 'create', 'edit', 'delete'],
                'contacts' => ['view', 'delete'],
                'settings' => ['view', 'edit'],
                'users' => ['view', 'create', 'edit', 'delete']
            ],
            'staff' => [
                'dashboard' => ['view'],
                'bookings' => ['view', 'create', 'edit'],
                'games' => ['view'],
                'rooms' => ['view'],
                'closures' => ['view'],
                'reviews' => ['view', 'approve'],
                'participants' => ['view', 'edit'],
                'teams' => ['view', 'create', 'edit'],
                'scanner' => ['view', 'scan'],
                'promo_codes' => ['view'],
                'contacts' => ['view'],
                'settings' => [],
                'users' => []
            ],
            'user' => [
                'dashboard' => [],
                'bookings' => ['view'],
                'games' => ['view'],
                'rooms' => ['view'],
                'closures' => [],
                'reviews' => [],
                'participants' => [],
                'teams' => [],
                'scanner' => [],
                'promo_codes' => [],
                'contacts' => [],
                'settings' => [],
                'users' => []
            ]
        ];
    }
}

/**
 * Vérifier si l'utilisateur peut accéder à un module
 * 
 * @param string $module
 * @return bool
 */
if (!function_exists('canAccessModule')) {
    function canAccessModule(string $module): bool
    {
        return hasPermission($module, 'view');
    }
}

/**
 * Rediriger avec erreur si pas de permission
 * 
 * @param string $module
 * @param string $action
 * @return \CodeIgniter\HTTP\RedirectResponse|null
 */
if (!function_exists('checkPermissionOrRedirect')) {
    function checkPermissionOrRedirect(string $module, string $action)
    {
        if (!hasPermission($module, $action)) {
            return redirect()->to('/admin/dashboard')
                ->with('error', "Vous n'avez pas la permission d'effectuer cette action.");
        }
        return null;
    }
}
