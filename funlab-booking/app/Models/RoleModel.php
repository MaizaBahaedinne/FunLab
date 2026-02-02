<?php

namespace App\Models;

use CodeIgniter\Model;

class RoleModel extends Model
{
    protected $table            = 'roles';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'key',
        'name',
        'description',
        'is_active',
        'is_system',
        'sort_order'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Obtenir tous les rôles actifs
     */
    public function getActiveRoles(): array
    {
        return $this->where('is_active', 1)
                    ->orderBy('sort_order', 'ASC')
                    ->findAll();
    }

    /**
     * Obtenir un rôle par sa clé
     */
    public function getByKey(string $key): ?array
    {
        return $this->where('key', $key)->first();
    }

    /**
     * Obtenir les permissions d'un rôle
     */
    public function getRolePermissions(int $roleId): array
    {
        $db = \Config\Database::connect();
        
        $query = $db->table('role_permissions rp')
                    ->select('pm.key as module_key, pa.key as action_key')
                    ->join('permission_modules pm', 'pm.id = rp.module_id')
                    ->join('permission_actions pa', 'pa.id = rp.action_id')
                    ->where('rp.role_id', $roleId)
                    ->where('pm.is_active', 1)
                    ->where('pa.is_active', 1)
                    ->get();
        
        $permissions = [];
        foreach ($query->getResultArray() as $row) {
            if (!isset($permissions[$row['module_key']])) {
                $permissions[$row['module_key']] = [];
            }
            $permissions[$row['module_key']][] = $row['action_key'];
        }
        
        return $permissions;
    }

    /**
     * Mettre à jour les permissions d'un rôle
     */
    public function updateRolePermissions(int $roleId, array $permissions): bool
    {
        $db = \Config\Database::connect();
        
        // Supprimer les anciennes permissions
        $db->table('role_permissions')->where('role_id', $roleId)->delete();
        
        // Insérer les nouvelles permissions
        $data = [];
        foreach ($permissions as $moduleId => $actionIds) {
            foreach ($actionIds as $actionId) {
                $data[] = [
                    'role_id' => $roleId,
                    'module_id' => $moduleId,
                    'action_id' => $actionId
                ];
            }
        }
        
        if (empty($data)) {
            return true;
        }
        
        return $db->table('role_permissions')->insertBatch($data);
    }
}
