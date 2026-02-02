<?php

namespace App\Models;

use CodeIgniter\Model;

class PermissionModuleModel extends Model
{
    protected $table            = 'permission_modules';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'key',
        'name',
        'description',
        'icon',
        'is_active',
        'sort_order'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Obtenir tous les modules actifs triés
     */
    public function getActiveModules(): array
    {
        return $this->where('is_active', 1)
                    ->orderBy('sort_order', 'ASC')
                    ->findAll();
    }

    /**
     * Obtenir un module par sa clé
     */
    public function getByKey(string $key): ?array
    {
        return $this->where('key', $key)->first();
    }

    /**
     * Créer ou mettre à jour un module
     */
    public function upsertModule(array $data): bool
    {
        $existing = $this->getByKey($data['key']);
        
        if ($existing) {
            return $this->update($existing['id'], $data);
        }
        
        return (bool) $this->insert($data);
    }
}
