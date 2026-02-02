<?php

namespace App\Models;

use CodeIgniter\Model;

class PromoCodeModel extends Model
{
    protected $table            = 'promo_codes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'code',
        'description',
        'discount_type',
        'discount_value',
        'min_amount',
        'max_discount',
        'usage_limit',
        'usage_count',
        'valid_from',
        'valid_until',
        'is_active',
        'applicable_games'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules = [
        'code' => 'required|min_length[3]|max_length[50]|alpha_numeric_punct',
        'discount_type' => 'required|in_list[percentage,fixed]',
        'discount_value' => 'required|decimal|greater_than[0]',
    ];

    protected $validationMessages = [
        'code' => [
            'required' => 'Le code promo est requis',
            'min_length' => 'Le code doit contenir au moins 3 caractères',
            'alpha_numeric_punct' => 'Le code ne peut contenir que des lettres, chiffres et tirets'
        ],
        'discount_type' => [
            'required' => 'Le type de réduction est requis',
            'in_list' => 'Type de réduction invalide'
        ],
        'discount_value' => [
            'required' => 'La valeur de réduction est requise',
            'greater_than' => 'La valeur doit être supérieure à 0'
        ]
    ];

    protected $beforeInsert = ['normalizeCode'];
    protected $beforeUpdate = ['normalizeCode'];

    /**
     * Normaliser le code (majuscules)
     */
    protected function normalizeCode(array $data): array
    {
        if (isset($data['data']['code'])) {
            $data['data']['code'] = strtoupper(trim($data['data']['code']));
        }
        return $data;
    }

    /**
     * Valider un code promo
     */
    public function validatePromoCode(string $code, float $amount = 0): array
    {
        $code = strtoupper(trim($code));
        $promo = $this->where('code', $code)
                      ->where('is_active', 1)
                      ->first();

        if (!$promo) {
            return [
                'valid' => false,
                'message' => 'Code promo invalide'
            ];
        }

        $now = date('Y-m-d H:i:s');

        // Vérifier les dates de validité
        if ($promo['valid_from'] && $promo['valid_from'] > $now) {
            return [
                'valid' => false,
                'message' => 'Ce code promo n\'est pas encore actif'
            ];
        }

        if ($promo['valid_until'] && $promo['valid_until'] < $now) {
            return [
                'valid' => false,
                'message' => 'Ce code promo a expiré'
            ];
        }

        // Vérifier la limite d'utilisation
        if ($promo['usage_limit'] !== null && $promo['usage_count'] >= $promo['usage_limit']) {
            return [
                'valid' => false,
                'message' => 'Ce code promo a atteint sa limite d\'utilisation'
            ];
        }

        // Vérifier le montant minimum
        if ($promo['min_amount'] !== null && $amount < $promo['min_amount']) {
            return [
                'valid' => false,
                'message' => sprintf('Montant minimum requis: %.2f MAD', $promo['min_amount'])
            ];
        }

        return [
            'valid' => true,
            'promo' => $promo,
            'message' => 'Code promo valide'
        ];
    }

    /**
     * Calculer la réduction
     */
    public function calculateDiscount(array $promo, float $amount): float
    {
        if ($promo['discount_type'] === 'percentage') {
            $discount = ($amount * $promo['discount_value']) / 100;
            
            // Appliquer la réduction maximale si définie
            if ($promo['max_discount'] !== null && $discount > $promo['max_discount']) {
                $discount = $promo['max_discount'];
            }
        } else {
            $discount = $promo['discount_value'];
        }

        // Ne pas dépasser le montant total
        return min($discount, $amount);
    }

    /**
     * Incrémenter le compteur d'utilisation
     */
    public function incrementUsage(int $promoId): bool
    {
        $this->where('id', $promoId)
             ->set('usage_count', 'usage_count + 1', false)
             ->update();
        
        return true;
    }

    /**
     * Obtenir les statistiques des codes promo
     */
    public function getStatistics(): array
    {
        $db = \Config\Database::connect();
        
        return [
            'total' => $this->countAll(),
            'active' => $this->where('is_active', 1)->countAllResults(false),
            'expired' => $this->where('valid_until <', date('Y-m-d H:i:s'))
                              ->where('is_active', 1)
                              ->countAllResults(),
            'total_usage' => (int) $db->table($this->table)
                                      ->selectSum('usage_count')
                                      ->get()
                                      ->getRow()
                                      ->usage_count
        ];
    }
}
