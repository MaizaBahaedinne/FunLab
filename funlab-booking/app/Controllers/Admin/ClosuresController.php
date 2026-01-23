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
        $data['closures'] = $this->closureModel->findAll();
        return view('admin/closures/index', $data);
    }

    public function create()
    {
        return view('admin/closures/create');
    }

    public function store()
    {
        // Création d'une fermeture
    }

    public function edit($id)
    {
        $data['closure'] = $this->closureModel->find($id);
        return view('admin/closures/edit', $data);
    }

    public function update($id)
    {
        // Mise à jour d'une fermeture
    }

    public function delete($id)
    {
        // Suppression d'une fermeture
    }
}
