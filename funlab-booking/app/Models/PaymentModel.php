<?php

namespace App\Models;

use CodeIgniter\Model;

class PaymentModel extends Model
{
    protected $table            = 'payments';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'booking_id',
        'customer_id',
        'amount',
        'currency',
        'payment_method',
        'status',
        'transaction_id',
        'transaction_date',
        'stripe_payment_intent_id',
        'stripe_charge_id',
        'notes'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'booking_id'      => 'required|integer',
        'amount'          => 'required|decimal',
        'payment_method'  => 'required|in_list[card,cash,stripe,bank_transfer]',
        'status'          => 'required|in_list[pending,completed,failed,refunded]'
    ];

    protected $validationMessages = [
        'booking_id' => [
            'required' => 'L\'ID de réservation est requis'
        ],
        'amount' => [
            'required' => 'Le montant est requis'
        ],
        'payment_method' => [
            'required' => 'La méthode de paiement est requise',
            'in_list' => 'Méthode de paiement invalide'
        ],
        'status' => [
            'required' => 'Le statut est requis',
            'in_list' => 'Statut de paiement invalide'
        ]
    ];

    protected $skipValidation = false;
}
