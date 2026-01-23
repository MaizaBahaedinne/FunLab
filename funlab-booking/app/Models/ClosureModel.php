<?php

namespace App\Models;

use CodeIgniter\Model;

class ClosureModel extends Model
{
    protected $table            = 'closures';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'room_id',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'reason',
        'is_recurring',
        'recurring_pattern'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'start_date' => 'required|valid_date',
        'end_date'   => 'required|valid_date',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;
}
