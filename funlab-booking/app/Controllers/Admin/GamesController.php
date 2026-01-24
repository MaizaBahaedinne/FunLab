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
    }

    public function index()
    {
        $data['games'] = $this->gameModel->findAll();
        return view('admin/games/index', $data);
    }

    public function create()
    {
        $data['rooms'] = $this->roomModel->where('status', 'active')->findAll();
        return view('admin/games/create', $data);
    }

    public function store()
    {
        $rules = [
            'name' => 'required|min_length[3]|max_length[255]',
            'category' => 'required',
            'duration' => 'required|integer|greater_than[0]',
            'min_participants' => 'required|integer|greater_than[0]',
            'max_participants' => 'required|integer|greater_than[0]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'category' => $this->request->getPost('category'),
            'description' => $this->request->getPost('description'),
            'duration' => $this->request->getPost('duration'),
            'min_participants' => $this->request->getPost('min_participants'),
            'max_participants' => $this->request->getPost('max_participants'),
            'difficulty' => $this->request->getPost('difficulty'),
            'price' => $this->request->getPost('price') ?: 0,
            'price_per_person' => $this->request->getPost('price_per_person') ?: 0,
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

        return redirect()->back()->withInput()->with('error', 'Erreur lors de la création du jeu');
    }

    public function edit($id)
    {
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
        $rules = [
            'name' => 'required|min_length[3]|max_length[255]',
            'category' => 'required',
            'duration' => 'required|integer|greater_than[0]',
            'min_participants' => 'required|integer|greater_than[0]',
            'max_participants' => 'required|integer|greater_than[0]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'category' => $this->request->getPost('category'),
            'description' => $this->request->getPost('description'),
            'duration' => $this->request->getPost('duration'),
            'min_participants' => $this->request->getPost('min_participants'),
            'max_participants' => $this->request->getPost('max_participants'),
            'difficulty' => $this->request->getPost('difficulty'),
            'price' => $this->request->getPost('price') ?: 0,
            'price_per_person' => $this->request->getPost('price_per_person') ?: 0,
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
