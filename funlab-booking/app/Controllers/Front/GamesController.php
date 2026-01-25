<?php

namespace App\Controllers\Front;

use App\Controllers\BaseController;
use App\Models\GameModel;
use App\Models\GameCategoryModel;

class GamesController extends BaseController
{
    protected $gameModel;
    protected $categoryModel;
    protected $reviewModel;

    public function __construct()
    {
        $this->gameModel = new GameModel();
        $this->categoryModel = new GameCategoryModel();
        $this->reviewModel = new \App\Models\ReviewModel();
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

        // Charger les avis
        $reviews = $this->reviewModel->getApprovedReviewsByGame($id);
        $averageRating = $this->reviewModel->getAverageRating($id);
        $reviewCount = $this->reviewModel->countApprovedReviews($id);
        
        // Vérifier si l'utilisateur a déjà laissé un avis
        $hasReviewed = false;
        if (session()->get('isLoggedIn') && session()->get('userId')) {
            $hasReviewed = $this->reviewModel->hasUserReviewed($id, session()->get('userId'));
        }

        // Préparer les données SEO
        $gameUrl = base_url('games/' . $game['id']);
        
        // Utiliser og_image si défini, sinon l'image du jeu, sinon l'image par défaut
        $gameImage = !empty($game['og_image']) 
            ? $game['og_image']
            : (!empty($game['image']) 
                ? base_url('uploads/games/' . $game['image']) 
                : base_url('assets/images/game-default.jpg'));
        
        // Utiliser meta_title si défini, sinon le nom du jeu
        $metaTitle = !empty($game['meta_title']) 
            ? esc($game['meta_title']) 
            : esc($game['name']) . ' - FunLab Tunisie';
        
        // Utiliser meta_description si définie, sinon générer depuis la description
        $metaDescription = !empty($game['meta_description']) 
            ? esc($game['meta_description'])
            : (!empty($game['description']) 
                ? mb_substr(strip_tags($game['description']), 0, 160) 
                : "Découvrez {$game['name']} chez FunLab Tunisie. Réservez dès maintenant !");
        
        // Utiliser meta_keywords si définis, sinon générer automatiquement
        $metaKeywords = !empty($game['meta_keywords'])
            ? esc($game['meta_keywords'])
            : esc($game['name']) . ', ' . ($game['category_name'] ?? '') . ', funlab tunisie, réservation, jeu';

        $data = [
            'title' => $metaTitle,
            'activeMenu' => 'games',
            'game' => $game,
            'reviews' => $reviews,
            'averageRating' => $averageRating,
            'reviewCount' => $reviewCount,
            'hasReviewed' => $hasReviewed,
            
            // SEO Meta Tags
            'metaTitle' => $metaTitle,
            'metaDescription' => $metaDescription,
            'metaKeywords' => $metaKeywords,
            'canonicalUrl' => $gameUrl,
            
            // Open Graph
            'ogType' => 'product',
            'ogUrl' => $gameUrl,
            'ogTitle' => $metaTitle,
            'ogDescription' => $metaDescription,
            'ogImage' => $gameImage,
            
            // Twitter Card
            'twitterUrl' => $gameUrl,
            'twitterTitle' => $metaTitle,
            'twitterDescription' => $metaDescription,
            'twitterImage' => $gameImage,
            
            // Partage social
            'shareUrl' => $gameUrl,
            'shareTitle' => esc($game['name']),
            'shareDescription' => $metaDescription
        ];

        return view('front/game_detail', $data);
    }

    /**
     * Soumettre un avis pour un jeu
     */
    public function submitReview($id)
    {
        $validation = \Config\Services::validation();
        
        $rules = [
            'rating' => 'required|integer|greater_than[0]|less_than[6]',
            'comment' => 'required|min_length[10]|max_length[1000]'
        ];

        // Si utilisateur non connecté, valider nom et email
        if (!session()->get('isLoggedIn')) {
            $rules['name'] = 'required|min_length[3]|max_length[100]';
            $rules['email'] = 'required|valid_email';
        }

        $validation->setRules($rules);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()
                           ->withInput()
                           ->with('review_error', 'Veuillez corriger les erreurs du formulaire');
        }

        // Vérifier si le jeu existe
        $game = $this->gameModel->find($id);
        if (!$game) {
            return redirect()->to('/games')->with('error', 'Jeu non trouvé');
        }

        // Vérifier si l'utilisateur a déjà laissé un avis
        if (session()->get('isLoggedIn') && session()->get('userId')) {
            $hasReviewed = $this->reviewModel->hasUserReviewed($id, session()->get('userId'));
            if ($hasReviewed) {
                return redirect()->back()
                               ->with('review_error', 'Vous avez déjà laissé un avis pour ce jeu');
            }
        }

        // Préparer les données
        $data = [
            'game_id' => $id,
            'rating' => $this->request->getPost('rating'),
            'comment' => $this->request->getPost('comment'),
            'is_approved' => 0 // Nécessite modération
        ];

        if (session()->get('isLoggedIn')) {
            $data['user_id'] = session()->get('userId');
        } else {
            $data['name'] = $this->request->getPost('name');
            $data['email'] = $this->request->getPost('email');
        }

        // Sauvegarder l'avis
        if ($this->reviewModel->insert($data)) {
            return redirect()->to('/games/' . $id)
                           ->with('review_success', 'Merci pour votre avis ! Il sera publié après modération.');
        } else {
            return redirect()->back()
                           ->withInput()
                           ->with('review_error', 'Une erreur est survenue. Veuillez réessayer.');
        }
    }
}
