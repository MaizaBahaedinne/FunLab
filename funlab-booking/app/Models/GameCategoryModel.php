<?php

namespace App\Models;

use CodeIgniter\Model;

class GameCategoryModel extends Model
{
    protected $table            = 'game_categories';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'name',
        'description',
        'icon',
        'color',
        'display_order',
        'is_active'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'name' => 'required|min_length[3]|max_length[100]',
    ];

    protected $validationMessages = [
        'name' => [
            'required' => 'Le nom de la catégorie est requis',
            'min_length' => 'Le nom doit contenir au moins 3 caractères',
            'max_length' => 'Le nom ne peut dépasser 100 caractères'
        ]
    ];

    /**
     * Récupérer toutes les catégories actives triées
     */
    public function getActiveCategories()
    {
        return $this->where('is_active', 1)
                    ->orderBy('display_order', 'ASC')
                    ->findAll();
    }

    /**
     * Récupérer les catégories avec le nombre de jeux
     */
    public function getCategoriesWithGameCount()
    {
        return $this->select('game_categories.*, COUNT(games.id) as game_count')
                    ->join('games', 'games.category_id = game_categories.id', 'left')
                    ->groupBy('game_categories.id')
                    ->orderBy('game_categories.display_order', 'ASC')
                    ->findAll();
    }
}
