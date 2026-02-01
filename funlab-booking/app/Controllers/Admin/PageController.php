<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PageModel;

class PageController extends BaseController
{
    protected $pageModel;

    public function __construct()
    {
        helper('permission');
        $this->pageModel = new PageModel();
    }

    // Liste des pages
    public function index()
    {
        if ($redirect = checkPermissionOrRedirect('settings', 'view')) {
            return $redirect;
        }

        $data = [
            'title' => 'Gestion des pages',
            'pages' => $this->pageModel->orderBy('created_at', 'DESC')->findAll()
        ];

        return view('admin/pages/index', $data);
    }

    // Créer une page
    public function create()
    {
        if ($redirect = checkPermissionOrRedirect('settings', 'create')) {
            return $redirect;
        }

        $data = [
            'title' => 'Nouvelle page'
        ];

        return view('admin/pages/form', $data);
    }

    // Éditer une page
    public function edit($id)
    {
        if ($redirect = checkPermissionOrRedirect('settings', 'edit')) {
            return $redirect;
        }

        $page = $this->pageModel->find($id);
        
        if (!$page) {
            return redirect()->to('/admin/pages')->with('error', 'Page introuvable');
        }

        $data = [
            'title' => 'Modifier la page',
            'page' => $page
        ];

        return view('admin/pages/form', $data);
    }

    // Sauvegarder
    public function save($id = null)
    {
        if ($redirect = checkPermissionOrRedirect('settings', $id ? 'edit' : 'create')) {
            return $redirect;
        }

        $rules = [
            'title' => 'required|min_length[3]|max_length[255]',
            'slug' => 'required|alpha_dash|is_unique[pages.slug,id,' . ($id ?? 0) . ']',
            'content' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'title' => $this->request->getPost('title'),
            'slug' => $this->request->getPost('slug'),
            'content' => $this->request->getPost('content'),
            'meta_title' => $this->request->getPost('meta_title'),
            'meta_description' => $this->request->getPost('meta_description'),
            'status' => $this->request->getPost('status') ?? 'draft',
            'template' => $this->request->getPost('template') ?? 'default'
        ];

        if ($id) {
            $this->pageModel->update($id, $data);
            $message = 'Page mise à jour avec succès';
        } else {
            $this->pageModel->insert($data);
            $message = 'Page créée avec succès';
        }

        return redirect()->to('/admin/pages')->with('success', $message);
    }

    // Supprimer
    public function delete($id)
    {
        if ($redirect = checkPermissionOrRedirect('settings', 'delete')) {
            return $redirect;
        }

        $this->pageModel->delete($id);
        return redirect()->to('/admin/pages')->with('success', 'Page supprimée');
    }
}
