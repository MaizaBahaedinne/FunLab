<?php

namespace App\Controllers\Front;

use App\Controllers\BaseController;
use App\Models\GameModel;
use App\Models\SettingModel;
use App\Models\BookingModel;
use App\Models\ReviewModel;

class HomeController extends BaseController
{
    public function index()
    {
        $gameModel = new GameModel();
        $settingModel = new SettingModel();
        $bookingModel = new BookingModel();
        $reviewModel = new ReviewModel();
        
        // Récupérer les paramètres du site
        $siteSettings = $settingModel->getByCategoryAsArray('site');
        
        // Récupérer les jeux populaires (limité à 6)
        $games = $gameModel
            ->select('games.*, game_categories.name as category_name, game_categories.icon as category_icon')
            ->join('game_categories', 'game_categories.id = games.category_id', 'left')
            ->where('games.status', 'active')
            ->orderBy('games.id', 'DESC')
            ->limit(6)
            ->findAll();
        
        // Statistiques
        $stats = [
            'total_bookings' => $bookingModel->countAllResults(),
            'total_games' => $gameModel->where('status', 'active')->countAllResults(),
            'happy_customers' => $bookingModel->where('status', 'completed')->countAllResults(),
        ];
        
        // Récupérer les derniers avis (limité à 3)
        $reviews = $reviewModel
            ->select('reviews.*, users.first_name, users.last_name')
            ->join('users', 'users.id = reviews.user_id', 'left')
            ->where('reviews.status', 'approved')
            ->orderBy('reviews.created_at', 'DESC')
            ->limit(3)
            ->findAll();
        
        $data = [
            'title' => $siteSettings['site_name'] ?? 'FunLab Tunisie',
            'siteSettings' => $siteSettings,
            'games' => $games,
            'stats' => $stats,
            'reviews' => $reviews,
            'activeMenu' => 'home'
        ];
        
        return view('front/home', $data);
    }
}
