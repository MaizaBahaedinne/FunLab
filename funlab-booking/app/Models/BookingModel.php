<?php

namespace App\Models;

use CodeIgniter\Model;

class BookingModel extends Model
{
    protected $table            = 'bookings';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id',
        'room_id',
        'game_id',
        'booking_date',
        'start_time',
        'end_time',
        'customer_name',
        'customer_email',
        'customer_phone',
        'num_players',
        'total_price',
        'payment_method',
        'status',
        'confirmation_code',
        'qr_code',
        'registration_token',
        'notes'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'room_id'        => 'required|integer',
        'game_id'        => 'required|integer',
        'booking_date'   => 'required|valid_date',
        'start_time'     => 'required',
        'customer_name'  => 'required|min_length[3]|max_length[255]',
        'customer_email' => 'required|valid_email',
        'num_players'    => 'required|integer|greater_than[0]',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;
}
