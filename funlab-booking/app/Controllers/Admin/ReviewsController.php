<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ReviewModel;
use App\Models\GameModel;

class ReviewsController extends BaseController
{
    protected $reviewModel;
    protected $gameModel;

    public function __construct()
    {
        $this->reviewModel = new ReviewModel();
        $this->gameModel = new GameModel();
    }

    /**
     * Liste de tous les avis
     */
    public function index()
    {
        $data = [
            'title' => 'Gestion des Avis',
            'activeMenu' => 'reviews'
        ];

        // Récupérer tous les avis avec les informations du jeu
        $builder = $this->reviewModel->builder();
        $builder->select('game_reviews.*, games.name as game_name, games.id as game_id, users.username')
                ->join('games', 'games.id = game_reviews.game_id')
                ->join('users', 'users.id = game_reviews.user_id', 'left')
                ->orderBy('game_reviews.created_at', 'DESC');
        
        $data['reviews'] = $builder->get()->getResultArray();

        return view('admin/reviews/index', $data);
    }

    /**
     * Approuver un avis
     */
    public function approve($id)
    {
        $review = $this->reviewModel->find($id);
        
        if (!$review) {
            return redirect()->to('/admin/reviews')
                           ->with('error', 'Avis non trouvé');
        }

        if ($this->reviewModel->update($id, ['is_approved' => 1])) {
            return redirect()->to('/admin/reviews')
                           ->with('success', 'Avis approuvé avec succès');
        } else {
            return redirect()->to('/admin/reviews')
                           ->with('error', 'Erreur lors de l\'approbation');
        }
    }

    /**
     * Rejeter un avis
     */
    public function reject($id)
    {
        $review = $this->reviewModel->find($id);
        
        if (!$review) {
            return redirect()->to('/admin/reviews')
                           ->with('error', 'Avis non trouvé');
        }

        if ($this->reviewModel->update($id, ['is_approved' => 0])) {
            return redirect()->to('/admin/reviews')
                           ->with('success', 'Avis rejeté');
        } else {
            return redirect()->to('/admin/reviews')
                           ->with('error', 'Erreur lors du rejet');
        }
    }

    /**
     * Supprimer un avis
     */
    public function delete($id)
    {
        $review = $this->reviewModel->find($id);
        
        if (!$review) {
            return redirect()->to('/admin/reviews')
                           ->with('error', 'Avis non trouvé');
        }

        if ($this->reviewModel->delete($id)) {
            return redirect()->to('/admin/reviews')
                           ->with('success', 'Avis supprimé avec succès');
        } else {
            return redirect()->to('/admin/reviews')
                           ->with('error', 'Erreur lors de la suppression');
        }
    }

    /**
     * Voir les avis d'un jeu spécifique
     */
    public function gameReviews($gameId)
    {
        $game = $this->gameModel->find($gameId);
        
        if (!$game) {
            return redirect()->to('/admin/reviews')
                           ->with('error', 'Jeu non trouvé');
        }

        $data = [
            'title' => 'Avis pour ' . $game['name'],
            'activeMenu' => 'reviews',
            'game' => $game
        ];

        // Récupérer les avis du jeu
        $builder = $this->reviewModel->builder();
        $builder->select('game_reviews.*, users.username')
                ->join('users', 'users.id = game_reviews.user_id', 'left')
                ->where('game_reviews.game_id', $gameId)
                ->orderBy('game_reviews.created_at', 'DESC');
        
        $data['reviews'] = $builder->get()->getResultArray();

        return view('admin/reviews/game_reviews', $data);
    }
}
