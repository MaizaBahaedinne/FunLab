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
        'payment_type',
        'status',
        'transaction_id',
        'paid_at',
        'stripe_payment_intent',
        'stripe_charge_id',
        'refunded_at',
        'refund_amount',
        'refund_reason',
        'metadata',
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
