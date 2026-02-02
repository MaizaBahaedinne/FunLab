<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\RoleModel;
use App\Models\PermissionModuleModel;
use App\Models\PermissionActionModel;
use App\Services\PermissionService;

class PermissionsController extends BaseController
{
    protected $roleModel;
    protected $moduleModel;
    protected $actionModel;
    protected $permissionService;

    public function __construct()
    {
        $this->roleModel = new RoleModel();
        $this->moduleModel = new PermissionModuleModel();
        $this->actionModel = new PermissionActionModel();
        $this->permissionService = new PermissionService();
        helper('permission');
    }

    /**
     * Gestion des permissions
     */
    public function index()
    {
        if (!canAccessModule('settings')) {
            return redirect()->to('/admin/dashboard')->with('error', 'Accès refusé');
        }

        $data = [
            'title' => 'Gestion des Permissions',
            'activeMenu' => 'settings-roles',
            'pageTitle' => 'Permissions & Rôles',
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
                ['title' => 'Paramètres', 'url' => base_url('admin/settings')],
                ['title' => 'Permissions']
            ],
            'roles' => $this->roleModel->getActiveRoles(),
            'modules' => $this->moduleModel->getActiveModules(),
            'actions' => $this->actionModel->getActiveActions(),
            'permissions' => $this->getAllPermissions()
        ];

        return view('admin/layouts/header', $data)
             . view('admin/layouts/sidebar', $data)
             . view('admin/layouts/topbar', $data)
             . view('admin/permissions/index', $data)
             . view('admin/layouts/footer', $data);
    }

    /**
     * Obtenir toutes les permissions structurées
     */
    protected function getAllPermissions(): array
    {
        $roles = $this->roleModel->getActiveRoles();
        $permissions = [];
        
        foreach ($roles as $role) {
            $rolePerms = $this->roleModel->getRolePermissions($role['id']);
            $permissions[$role['id']] = $rolePerms;
        }
        
        return $permissions;
    }

    /**
     * Mettre à jour les permissions
     */
    public function update()
    {
        if (!canAccessModule('settings')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Accès refusé'
            ]);
        }

        $roleId = $this->request->getPost('role_id');
        $permissions = $this->request->getPost('permissions');

        if (!$roleId || !is_array($permissions)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Données invalides'
            ]);
        }

        $role = $this->roleModel->find($roleId);
        
        if (!$role) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Rôle non trouvé'
            ]);
        }

        // Ne pas permettre de modifier les permissions admin via l'interface
        if ($role['key'] === 'admin') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Les permissions administrateur ne peuvent pas être modifiées'
            ]);
        }

        if ($this->roleModel->updateRolePermissions($roleId, $permissions)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Permissions mises à jour avec succès'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Erreur lors de la mise à jour'
        ]);
    }

    /**
     * Synchroniser les modules (scanner les contrôleurs)
     */
    public function sync()
    {
        if (!canAccessModule('settings')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Accès refusé'
            ]);
        }

        $synced = $this->permissionService->syncModules();

        return $this->response->setJSON([
            'success' => true,
            'message' => count($synced) > 0 
                ? count($synced) . ' nouveau(x) module(s) détecté(s)' 
                : 'Aucun nouveau module détecté',
            'synced' => $synced
        ]);
    }

    /**
     * Gestion des modules
     */
    public function modules()
    {
        if (!canAccessModule('settings')) {
            return redirect()->to('/admin/dashboard')->with('error', 'Accès refusé');
        }

        $data = [
            'title' => 'Gestion des Modules',
            'activeMenu' => 'settings-roles',
            'pageTitle' => 'Modules de Permissions',
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
                ['title' => 'Permissions', 'url' => base_url('admin/permissions')],
                ['title' => 'Modules']
            ],
            'modules' => $this->moduleModel->orderBy('sort_order', 'ASC')->findAll()
        ];

        return view('admin/layouts/header', $data)
             . view('admin/layouts/sidebar', $data)
             . view('admin/layouts/topbar', $data)
             . view('admin/permissions/modules', $data)
             . view('admin/layouts/footer', $data);
    }

    /**
     * Mettre à jour un module
     */
    public function updateModule($id)
    {
        if (!canAccessModule('settings')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Accès refusé']);
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'icon' => $this->request->getPost('icon'),
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
            'sort_order' => $this->request->getPost('sort_order')
        ];

        if ($this->moduleModel->update($id, $data)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Module mis à jour'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Erreur lors de la mise à jour'
        ]);
    }
}
