<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ParticipantModel;

class ParticipantsController extends BaseController
{
    protected $participantModel;

    public function __construct()
    {
        $this->participantModel = new ParticipantModel();
    }

    public function index()
    {
        $data['participants'] = $this->participantModel->findAll();
        return view('admin/participants/index', $data);
    }

    public function view($id)
    {
        $data['participant'] = $this->participantModel->find($id);
        return view('admin/participants/view', $data);
    }

    public function edit($id)
    {
        $data['participant'] = $this->participantModel->find($id);
        return view('admin/participants/edit', $data);
    }

    public function update($id)
    {
        // Mise à jour d'un participant
    }
}
