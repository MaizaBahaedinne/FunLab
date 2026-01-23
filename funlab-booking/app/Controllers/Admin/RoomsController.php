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
        // Création d'une salle
    }

    public function edit($id)
    {
        $data['room'] = $this->roomModel->find($id);
        return view('admin/rooms/edit', $data);
    }

    public function update($id)
    {
        // Mise à jour d'une salle
    }

    public function delete($id)
    {
        // Suppression d'une salle
    }
}
