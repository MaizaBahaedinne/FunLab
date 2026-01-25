<?php

namespace App\Models;

use CodeIgniter\Model;

class ReviewModel extends Model
{
    protected $table = 'game_reviews';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'game_id',
        'user_id',
        'name',
        'email',
        'rating',
        'comment',
        'is_approved'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'game_id' => 'required|integer',
        'rating' => 'required|integer|greater_than[0]|less_than[6]',
        'comment' => 'required|min_length[10]|max_length[1000]'
    ];

    protected $validationMessages = [
        'rating' => [
            'required' => 'La note est requise',
            'greater_than' => 'La note doit être entre 1 et 5',
            'less_than' => 'La note doit être entre 1 et 5'
        ],
        'comment' => [
            'required' => 'Le commentaire est requis',
            'min_length' => 'Le commentaire doit contenir au moins 10 caractères',
            'max_length' => 'Le commentaire ne peut pas dépasser 1000 caractères'
        ]
    ];

    /**
     * Récupérer les avis approuvés pour un jeu
     */
    public function getApprovedReviewsByGame($gameId)
    {
        return $this->select('game_reviews.*, users.username')
                    ->join('users', 'users.id = game_reviews.user_id', 'left')
                    ->where('game_reviews.game_id', $gameId)
                    ->where('game_reviews.is_approved', 1)
                    ->orderBy('game_reviews.created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Récupérer tous les avis pour un jeu (admin)
     */
    public function getAllReviewsByGame($gameId)
    {
        return $this->select('game_reviews.*, users.username')
                    ->join('users', 'users.id = game_reviews.user_id', 'left')
                    ->where('game_reviews.game_id', $gameId)
                    ->orderBy('game_reviews.created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Calculer la moyenne des notes pour un jeu
     */
    public function getAverageRating($gameId)
    {
        $result = $this->selectAvg('rating', 'average')
                       ->where('game_id', $gameId)
                       ->where('is_approved', 1)
                       ->first();
        
        return $result ? round($result['average'], 1) : 0;
    }

    /**
     * Compter les avis approuvés pour un jeu
     */
    public function countApprovedReviews($gameId)
    {
        return $this->where('game_id', $gameId)
                    ->where('is_approved', 1)
                    ->countAllResults();
    }

    /**
     * Vérifier si un utilisateur a déjà laissé un avis
     */
    public function hasUserReviewed($gameId, $userId)
    {
        return $this->where('game_id', $gameId)
                    ->where('user_id', $userId)
                    ->countAllResults() > 0;
    }
}
