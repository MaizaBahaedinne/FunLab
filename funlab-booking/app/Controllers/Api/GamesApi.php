<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;

/**
 * GamesApi
 * 
 * API REST pour récupérer les jeux
 * 
 * @package App\Controllers\Api
 */
class GamesApi extends ResourceController
{
    protected $format = 'json';
    protected $gameModel;

    public function __construct()
    {
        $this->gameModel = model('GameModel');
        
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type');
    }

    /**
     * Récupère tous les jeux actifs
     * 
     * Endpoint : GET /api/games
     * 
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function index()
    {
        try {
            $games = $this->gameModel
                ->where('status', 'active')
                ->orderBy('name', 'ASC')
                ->findAll();
            
            return $this->respond([
                'status' => 'success',
                'data' => $games,
                'count' => count($games)
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Erreur API Games/index : ' . $e->getMessage());
            return $this->failServerError('Erreur lors de la récupération des jeux');
        }
    }

    /**
     * Gère les requêtes OPTIONS (CORS)
     */
    public function options()
    {
        return $this->respond(['status' => 'ok']);
    }
}
