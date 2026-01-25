<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\GameCategoryModel;

class GameCategoriesController extends BaseController
{
    protected $categoryModel;

    public function __construct()
    {
        $this->categoryModel = new GameCategoryModel();
    }

    /**
     * Liste des catégories
     */
    public function index()
    {
        $data = [
            'title' => 'Catégories de Jeux',
            'categories' => $this->categoryModel->getCategoriesWithGameCount()
        ];

        return view('admin/game_categories/index', $data);
    }

    /**
     * Formulaire de création
     */
    public function create()
    {
        $data = [
            'title' => 'Nouvelle Catégorie',
        ];

        return view('admin/game_categories/create', $data);
    }

    /**
     * Enregistrer une nouvelle catégorie
     */
    public function store()
    {
        $data = [
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'icon' => $this->request->getPost('icon'),
            'color' => $this->request->getPost('color'),
            'display_order' => $this->request->getPost('display_order') ?: 0,
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
        ];

        if ($this->categoryModel->insert($data)) {
            return redirect()->to('/admin/game-categories')
                           ->with('success', 'Catégorie créée avec succès');
        }

        return redirect()->back()
                       ->withInput()
                       ->with('error', 'Erreur lors de la création: ' . implode(', ', $this->categoryModel->errors()));
    }

    /**
     * Formulaire d'édition
     */
    public function edit($id)
    {
        $category = $this->categoryModel->find($id);

        if (!$category) {
            return redirect()->to('/admin/game-categories')
                           ->with('error', 'Catégorie introuvable');
        }

        $data = [
            'title' => 'Modifier Catégorie',
            'category' => $category
        ];

        return view('admin/game_categories/edit', $data);
    }

    /**
     * Mettre à jour une catégorie
     */
    public function update($id)
    {
        $data = [
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'icon' => $this->request->getPost('icon'),
            'color' => $this->request->getPost('color'),
            'display_order' => $this->request->getPost('display_order') ?: 0,
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
        ];

        if ($this->categoryModel->update($id, $data)) {
            return redirect()->to('/admin/game-categories')
                           ->with('success', 'Catégorie mise à jour avec succès');
        }

        return redirect()->back()
                       ->withInput()
                       ->with('error', 'Erreur lors de la mise à jour: ' . implode(', ', $this->categoryModel->errors()));
    }

    /**
     * Supprimer une catégorie
     */
    public function delete($id)
    {
        if ($this->categoryModel->delete($id)) {
            return redirect()->to('/admin/game-categories')
                           ->with('success', 'Catégorie supprimée avec succès');
        }

        return redirect()->to('/admin/game-categories')
                       ->with('error', 'Erreur lors de la suppression');
    }
}
