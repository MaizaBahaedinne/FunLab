<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\GameModel;

class GamesController extends BaseController
{
    protected $gameModel;

    public function __construct()
    {
        $this->gameModel = new GameModel();
    }

    public function index()
    {
        $data['games'] = $this->gameModel->findAll();
        return view('admin/games/index', $data);
    }

    public function create()
    {
        return view('admin/games/create');
    }

    public function store()
    {
        // Création d'un jeu
    }

    public function edit($id)
    {
        $data['game'] = $this->gameModel->find($id);
        return view('admin/games/edit', $data);
    }

    public function update($id)
    {
        // Mise à jour d'un jeu
    }

    public function delete($id)
    {
        // Suppression d'un jeu
    }
}
