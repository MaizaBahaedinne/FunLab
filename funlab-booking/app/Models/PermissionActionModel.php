<?php

namespace App\Models;

use CodeIgniter\Model;

class PermissionActionModel extends Model
{
    protected $table            = 'permission_actions';
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
        'sort_order'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Obtenir toutes les actions actives triées
     */
    public function getActiveActions(): array
    {
        return $this->where('is_active', 1)
                    ->orderBy('sort_order', 'ASC')
                    ->findAll();
    }

    /**
     * Obtenir une action par sa clé
     */
    public function getByKey(string $key): ?array
    {
        return $this->where('key', $key)->first();
    }
}
