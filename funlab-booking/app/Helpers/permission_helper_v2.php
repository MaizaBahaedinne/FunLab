<?php

/**
 * SYSTÈME DE PERMISSIONS DYNAMIQUE ET EXTENSIBLE
 * 
 * Ce système utilise la base de données pour gérer les permissions de manière flexible.
 * Les modules sont détectés automatiquement et les permissions sont configurables via l'interface admin.
 */

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
        
        try {
            // Utiliser le service de permissions (système dynamique)
            $permissionService = new \App\Services\PermissionService();
            return $permissionService->checkPermission($role, $module, $action);
        } catch (\Exception $e) {
            // Fallback : les admins ont toutes les permissions
            return $role === 'admin';
        }
    }
}

/**
 * Récupérer toutes les permissions par rôle depuis la base de données
 * 
 * @return array
 */
if (!function_exists('getRolePermissions')) {
    function getRolePermissions(): array
    {
        try {
            $roleModel = new \App\Models\RoleModel();
            $roles = $roleModel->getActiveRoles();
            
            $permissions = [];
            
            foreach ($roles as $role) {
                $permissions[$role['key']] = $roleModel->getRolePermissions($role['id']);
            }
            
            return $permissions;
        } catch (\Exception $e) {
            // Fallback en cas d'erreur
            return [
                'admin' => [],
                'staff' => [],
                'user' => []
            ];
        }
    }
}

/**
 * Vérifier si l'utilisateur peut accéder à un module
 * 
 * @param string $module Le module à vérifier
 * @return bool
 */
if (!function_exists('canAccessModule')) {
    function canAccessModule(string $module): bool
    {
        // Vérifier au minimum la permission "view"
        return hasPermission($module, 'view');
    }
}

/**
 * Rediriger si pas de permission
 * 
 * @param string $module
 * @param string $action
 * @return mixed|null
 */
if (!function_exists('checkPermissionOrRedirect')) {
    function checkPermissionOrRedirect(string $module, string $action)
    {
        if (!hasPermission($module, $action)) {
            return redirect()->to('/admin/dashboard')->with('error', 'Accès refusé : vous n\'avez pas les permissions nécessaires.');
        }
        
        return null;
    }
}
