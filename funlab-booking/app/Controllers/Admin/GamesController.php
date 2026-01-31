<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\GameModel;
use App\Models\RoomModel;
use App\Models\RoomGameModel;
use App\Models\BookingModel;

class GamesController extends BaseController
{
    protected $gameModel;
    protected $roomModel;
    protected $roomGameModel;
    protected $bookingModel;

    public function __construct()
    {
        $this->gameModel = new GameModel();
        $this->roomModel = new RoomModel();
        $this->roomGameModel = new RoomGameModel();
        $this->bookingModel = new BookingModel();
        
        // Charger le helper de permissions
        helper('permission');
    }

    public function index()
    {
        // Vérifier la permission de voir les jeux
        if ($redirect = checkPermissionOrRedirect('games', 'view')) {
            return $redirect;
        }
        
        $data['games'] = $this->gameModel->findAll();
        return view('admin/games/index', $data);
    }

    public function create()
    {
        // Vérifier la permission de créer des jeux
        if ($redirect = checkPermissionOrRedirect('games', 'create')) {
            return $redirect;
        }
        
        $data['rooms'] = $this->roomModel->where('status', 'active')->findAll();
        return view('admin/games/create', $data);
    }

    public function store()
    {
        // Vérifier la permission de créer des jeux
        if ($redirect = checkPermissionOrRedirect('games', 'create')) {
            return $redirect;
        }
        
        $rules = [
            'name' => 'required|min_length[3]|max_length[255]',
            'duration_minutes' => 'required|integer|greater_than[0]',
            'min_players' => 'required|integer|greater_than[0]',
            'max_players' => 'required|integer|greater_than[0]',
            'price' => 'required|decimal',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'duration_minutes' => $this->request->getPost('duration_minutes'),
            'min_players' => $this->request->getPost('min_players'),
            'max_players' => $this->request->getPost('max_players'),
            'price' => $this->request->getPost('price') ?: 0,
            'price_per_person' => $this->request->getPost('price_per_person') ?: null,
            'deposit_required' => $this->request->getPost('deposit_required') ? 1 : 0,
            'deposit_percentage' => $this->request->getPost('deposit_percentage') ?: 30,
            'status' => $this->request->getPost('status') === 'active' ? 'active' : 'inactive',
        ];

        $gameId = $this->gameModel->insert($data);
        
        if ($gameId) {
            // Associate rooms
            $rooms = $this->request->getPost('rooms') ?? [];
            foreach ($rooms as $roomId) {
                $this->roomGameModel->insert([
                    'room_id' => $roomId,
                    'game_id' => $gameId
                ]);
            }
            
            return redirect()->to('admin/games')->with('success', 'Jeu créé avec succès');
        }

        // Afficher les erreurs du modèle
        $errors = $this->gameModel->errors();
        $errorMessage = !empty($errors) ? implode(', ', $errors) : 'Erreur lors de la création du jeu';
        
        return redirect()->back()->withInput()->with('error', $errorMessage);
    }

    public function edit($id)
    {
        // Vérifier la permission de modifier des jeux
        if ($redirect = checkPermissionOrRedirect('games', 'edit')) {
            return $redirect;
        }
        
        $data['game'] = $this->gameModel->find($id);
        
        if (!$data['game']) {
            return redirect()->to('admin/games')->with('error', 'Jeu introuvable');
        }
        
        $data['rooms'] = $this->roomModel->findAll();
        $data['game_rooms'] = $this->roomGameModel->where('game_id', $id)->findAll();
        
        return view('admin/games/edit', $data);
    }

    public function update($id)
    {
        // Vérifier la permission de modifier des jeux
        if ($redirect = checkPermissionOrRedirect('games', 'edit')) {
            return $redirect;
        }
        
        $rules = [
            'name' => 'required|min_length[3]|max_length[255]',
            'duration_minutes' => 'required|integer|greater_than[0]',
            'min_players' => 'required|integer|greater_than[0]',
            'max_players' => 'required|integer|greater_than[0]',
            'price' => 'required|decimal',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'duration_minutes' => $this->request->getPost('duration_minutes'),
            'min_players' => $this->request->getPost('min_players'),
            'max_players' => $this->request->getPost('max_players'),
            'price' => $this->request->getPost('price') ?: 0,
            'price_per_person' => $this->request->getPost('price_per_person') ?: null,
            'deposit_required' => $this->request->getPost('deposit_required') ? 1 : 0,
            'deposit_percentage' => $this->request->getPost('deposit_percentage') ?: 30,
            'status' => $this->request->getPost('status') === 'active' ? 'active' : 'inactive',
        ];

        if ($this->gameModel->update($id, $data)) {
            // Update room associations
            $this->roomGameModel->where('game_id', $id)->delete();
            $rooms = $this->request->getPost('rooms') ?? [];
            foreach ($rooms as $roomId) {
                $this->roomGameModel->insert([
                    'room_id' => $roomId,
                    'game_id' => $id
                ]);
            }
            
            return redirect()->to('admin/games')->with('success', 'Jeu mis à jour avec succès');
        }

        return redirect()->back()->withInput()->with('error', 'Erreur lors de la mise à jour');
    }

    public function delete($id)
    {
        // Vérifier la permission de supprimer des jeux
        if (!hasPermission('games', 'delete')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => "Vous n'avez pas la permission de supprimer des jeux."
            ]);
        }
        
        if ($this->request->isAJAX()) {
            try {
                // Check if game has bookings
                $bookingsCount = $this->bookingModel->where('game_id', $id)->countAllResults();
                
                if ($bookingsCount > 0) {
                    return $this->response->setJSON([
                        'success' => false, 
                        'message' => "Impossible de supprimer ce jeu : {$bookingsCount} réservation(s) y sont associées. Veuillez d'abord supprimer ces réservations."
                    ]);
                }
                
                // Delete room associations first
                $this->roomGameModel->where('game_id', $id)->delete();
                
                if ($this->gameModel->delete($id)) {
                    return $this->response->setJSON(['success' => true, 'message' => 'Jeu supprimé avec succès']);
                }
                return $this->response->setJSON(['success' => false, 'message' => 'Erreur lors de la suppression']);
            } catch (\Exception $e) {
                return $this->response->setJSON(['success' => false, 'message' => $e->getMessage()]);
            }
        }
        
        return redirect()->to('admin/games')->with('error', 'Méthode non autorisée');
    }
}
