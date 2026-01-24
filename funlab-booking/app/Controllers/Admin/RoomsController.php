<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\RoomModel;

class RoomsController extends BaseController
{
    protected $roomModel;

    public function __construct()
    {
        $this->roomModel = new RoomModel();
    }

    public function index()
    {
        $data['rooms'] = $this->roomModel->findAll();
        return view('admin/rooms/index', $data);
    }

    public function create()
    {
        return view('admin/rooms/create');
    }

    public function store()
    {
        $rules = [
            'name' => 'required|min_length[3]|max_length[255]',
            'capacity' => 'required|integer|greater_than[0]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'capacity' => $this->request->getPost('capacity'),
            'description' => $this->request->getPost('description'),
            'location' => $this->request->getPost('location'),
            'equipment' => $this->request->getPost('equipment'),
            'status' => $this->request->getPost('status') === 'active' ? 'active' : 'inactive',
        ];

        if ($this->roomModel->insert($data)) {
            return redirect()->to('admin/rooms')->with('success', 'Salle créée avec succès');
        }

        return redirect()->back()->withInput()->with('error', 'Erreur lors de la création de la salle');
    }

    public function edit($id)
    {
        $data['room'] = $this->roomModel->find($id);
        
        if (!$data['room']) {
            return redirect()->to('admin/rooms')->with('error', 'Salle introuvable');
        }
        
        return view('admin/rooms/edit', $data);
    }

    public function update($id)
    {
        $rules = [
            'name' => 'required|min_length[3]|max_length[255]',
            'capacity' => 'required|integer|greater_than[0]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'capacity' => $this->request->getPost('capacity'),
            'description' => $this->request->getPost('description'),
            'location' => $this->request->getPost('location'),
            'equipment' => $this->request->getPost('equipment'),
            'status' => $this->request->getPost('status') === 'active' ? 'active' : 'inactive',
        ];

        if ($this->roomModel->update($id, $data)) {
            return redirect()->to('admin/rooms')->with('success', 'Salle mise à jour avec succès');
        }

        return redirect()->back()->withInput()->with('error', 'Erreur lors de la mise à jour');
    }

    public function delete($id)
    {
        if ($this->request->isAJAX()) {
            try {
                if ($this->roomModel->delete($id)) {
                    return $this->response->setJSON(['success' => true, 'message' => 'Salle supprimée']);
                }
                return $this->response->setJSON(['success' => false, 'message' => 'Erreur lors de la suppression']);
            } catch (\Exception $e) {
                return $this->response->setJSON(['success' => false, 'message' => $e->getMessage()]);
            }
        }
        
        return redirect()->to('admin/rooms')->with('error', 'Méthode non autorisée');
    }
}
