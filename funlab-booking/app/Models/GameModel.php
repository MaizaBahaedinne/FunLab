<?php

namespace App\Models;

use CodeIgniter\Model;

class GameModel extends Model
{
    protected $table            = 'games';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'name',
        'description',
        'duration_minutes',
        'min_players',
        'max_players',
        'price',
        'price_per_person',
        'deposit_required',
        'deposit_percentage',
        'image',
        'status'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'name'        => 'required|min_length[3]|max_length[255]',
        'duration'    => 'required|integer|greater_than[0]',
        'min_players' => 'required|integer|greater_than[0]',
        'max_players' => 'required|integer|greater_than[0]',
        'price'       => 'required|decimal',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;
}
