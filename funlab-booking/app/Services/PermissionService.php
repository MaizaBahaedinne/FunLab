<?php

namespace App\Services;

use App\Models\PermissionModuleModel;
use App\Models\PermissionActionModel;
use App\Models\RoleModel;

class PermissionService
{
    protected $moduleModel;
    protected $actionModel;
    protected $roleModel;

    public function __construct()
    {
        $this->moduleModel = new PermissionModuleModel();
        $this->actionModel = new PermissionActionModel();
        $this->roleModel = new RoleModel();
    }

    /**
     * Scanner automatiquement les contrôleurs Admin pour détecter les modules
     */
    public function scanAdminControllers(): array
    {
        $controllersPath = APPPATH . 'Controllers/Admin/';
        $modules = [];
        
        if (!is_dir($controllersPath)) {
            return $modules;
        }
        
        $files = scandir($controllersPath);
        
        foreach ($files as $file) {
            if ($file === '.' || $file === '..' || !str_ends_with($file, '.php')) {
                continue;
            }
            
            // Extraire le nom du contrôleur
            $controllerName = str_replace('.php', '', $file);
            
            // Ignorer certains contrôleurs
            if (in_array($controllerName, ['BaseController', 'DashboardController', 'SettingsController'])) {
                continue;
            }
            
            // Générer la clé du module (ex: BookingsController -> bookings)
            $moduleKey = $this->controllerToModuleKey($controllerName);
            
            // Générer le nom lisible
            $moduleName = $this->moduleKeyToName($moduleKey);
            
            $modules[] = [
                'key' => $moduleKey,
                'name' => $moduleName,
                'controller' => $controllerName,
                'file' => $file
            ];
        }
        
        return $modules;
    }

    /**
     * Convertir un nom de contrôleur en clé de module
     */
    protected function controllerToModuleKey(string $controllerName): string
    {
        // Retirer "Controller" de la fin
        $key = str_replace('Controller', '', $controllerName);
        
        // Convertir en snake_case
        $key = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $key));
        
        return $key;
    }

    /**
     * Convertir une clé de module en nom lisible
     */
    protected function moduleKeyToName(string $key): string
    {
        // Remplacer les underscores par des espaces
        $name = str_replace('_', ' ', $key);
        
        // Mettre en majuscule la première lettre de chaque mot
        $name = ucwords($name);
        
        return $name;
    }

    /**
     * Synchroniser les modules détectés avec la base de données
     */
    public function syncModules(): array
    {
        $scannedModules = $this->scanAdminControllers();
        $synced = [];
        
        foreach ($scannedModules as $module) {
            $existing = $this->moduleModel->getByKey($module['key']);
            
            if (!$existing) {
                // Nouveau module détecté
                $this->moduleModel->insert([
                    'key' => $module['key'],
                    'name' => $module['name'],
                    'description' => 'Module détecté automatiquement',
                    'is_active' => 1,
                    'sort_order' => 99
                ]);
                
                $synced[] = [
                    'key' => $module['key'],
                    'action' => 'created'
                ];
            }
        }
        
        return $synced;
    }

    /**
     * Vérifier si un utilisateur a une permission
     */
    public function checkPermission(string $roleKey, string $moduleKey, string $actionKey): bool
    {
        $role = $this->roleModel->getByKey($roleKey);
        
        if (!$role) {
            return false;
        }
        
        // Les admins ont toutes les permissions
        if ($role['key'] === 'admin') {
            return true;
        }
        
        $permissions = $this->roleModel->getRolePermissions($role['id']);
        
        return isset($permissions[$moduleKey]) && in_array($actionKey, $permissions[$moduleKey]);
    }

    /**
     * Obtenir toutes les permissions d'un rôle
     */
    public function getRolePermissions(string $roleKey): array
    {
        $role = $this->roleModel->getByKey($roleKey);
        
        if (!$role) {
            return [];
        }
        
        return $this->roleModel->getRolePermissions($role['id']);
    }
}
