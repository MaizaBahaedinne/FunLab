<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ClosureModel;

class ClosuresController extends BaseController
{
    protected $closureModel;

    public function __construct()
    {
        $this->closureModel = new ClosureModel();
    }

    public function index()
    {
        // Get closures with room information
        $builder = $this->closureModel->builder();
        $builder->select('closures.*, rooms.name as room_name')
                ->join('rooms', 'rooms.id = closures.room_id', 'left')
                ->orderBy('closures.closure_date', 'DESC');
        
        $data['closures'] = $builder->get()->getResultArray();
        return view('admin/closures/index', $data);
    }

    public function create()
    {
        $roomModel = new \App\Models\RoomModel();
        $data['rooms'] = $roomModel->where('status', 'active')->findAll();
        return view('admin/closures/create', $data);
    }

    public function store()
    {
        $rules = [
            'closure_date' => 'required|valid_date',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $data = [
            'room_id' => $this->request->getPost('room_id') ?: null,
            'closure_date' => $this->request->getPost('closure_date'),
            'start_time' => $this->request->getPost('start_time'),
            'end_time' => $this->request->getPost('end_time'),
            'all_rooms' => $this->request->getPost('all_rooms') ? 1 : 0,
            'reason' => $this->request->getPost('reason'),
        ];

        if ($this->closureModel->insert($data)) {
            return redirect()->to('admin/closures')->with('success', 'Fermeture créée avec succès');
        }

        return redirect()->back()->withInput()->with('error', 'Erreur lors de la création');
    }

    public function edit($id)
    {
        $data['closure'] = $this->closureModel->find($id);
        
        if (!$data['closure']) {
            return redirect()->to('admin/closures')->with('error', 'Fermeture introuvable');
        }
        
        $roomModel = new \App\Models\RoomModel();
        $data['rooms'] = $roomModel->findAll();
        
        return view('admin/closures/edit', $data);
    }

    public function update($id)
    {
        $rules = [
            'closure_date' => 'required|valid_date',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $data = [
            'room_id' => $this->request->getPost('room_id') ?: null,
            'closure_date' => $this->request->getPost('closure_date'),
            'start_time' => $this->request->getPost('start_time'),
            'end_time' => $this->request->getPost('end_time'),
            'all_rooms' => $this->request->getPost('all_rooms') ? 1 : 0,
            'reason' => $this->request->getPost('reason'),
        ];

        if ($this->closureModel->update($id, $data)) {
            return redirect()->to('admin/closures')->with('success', 'Fermeture mise à jour');
        }

        return redirect()->back()->withInput()->with('error', 'Erreur lors de la mise à jour');
    }

    public function delete($id)
    {
        if ($this->request->isAJAX()) {
            try {
                if ($this->closureModel->delete($id)) {
                    return $this->response->setJSON(['success' => true, 'message' => 'Fermeture supprimée']);
                }
                return $this->response->setJSON(['success' => false, 'message' => 'Erreur lors de la suppression']);
            } catch (\Exception $e) {
                return $this->response->setJSON(['success' => false, 'message' => $e->getMessage()]);
            }
        }
        
        return redirect()->to('admin/closures')->with('error', 'Méthode non autorisée');
    }
}
