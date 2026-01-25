<?php

namespace App\Controllers\Front;

use App\Controllers\BaseController;
use App\Models\GameModel;
use App\Models\GameCategoryModel;

class GamesController extends BaseController
{
    protected $gameModel;
    protected $categoryModel;

    public function __construct()
    {
        $this->gameModel = new GameModel();
        $this->categoryModel = new GameCategoryModel();
    }

    /**
     * Page principale des jeux
     */
    public function index()
    {
        // Récupérer toutes les catégories actives
        $categories = $this->categoryModel->getActiveCategories();

        // Récupérer tous les jeux actifs avec leurs catégories
        $games = $this->gameModel
            ->select('games.*, game_categories.name as category_name, game_categories.icon as category_icon, game_categories.color as category_color')
            ->join('game_categories', 'game_categories.id = games.category_id', 'left')
            ->where('games.status', 'active')
            ->orderBy('game_categories.display_order', 'ASC')
            ->orderBy('games.name', 'ASC')
            ->findAll();

        // Grouper les jeux par catégorie
        $gamesByCategory = [];
        foreach ($games as $game) {
            $categoryName = $game['category_name'] ?? 'Non catégorisé';
            if (!isset($gamesByCategory[$categoryName])) {
                $gamesByCategory[$categoryName] = [
                    'category' => [
                        'name' => $categoryName,
                        'icon' => $game['category_icon'] ?? 'bi-controller',
                        'color' => $game['category_color'] ?? '#667eea'
                    ],
                    'games' => []
                ];
            }
            $gamesByCategory[$categoryName]['games'][] = $game;
        }

        $data = [
            'title' => 'Nos Jeux',
            'activeMenu' => 'games',
            'categories' => $categories,
            'games' => $games,
            'gamesByCategory' => $gamesByCategory
        ];

        return view('front/games', $data);
    }

    /**
     * Détail d'un jeu spécifique
     */
    public function view($id)
    {
        $game = $this->gameModel
            ->select('games.*, game_categories.name as category_name, game_categories.icon as category_icon, game_categories.color as category_color')
            ->join('game_categories', 'game_categories.id = games.category_id', 'left')
            ->where('games.id', $id)
            ->where('games.status', 'active')
            ->first();

        if (!$game) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Jeu non trouvé');
        }

        $data = [
            'title' => esc($game['name']),
            'activeMenu' => 'games',
            'game' => $game
        ];

        return view('front/game_detail', $data);
    }
}
