<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PromoCodeModel;
use App\Models\GameModel;

class PromoCodesController extends BaseController
{
    protected $promoCodeModel;
    protected $gameModel;

    public function __construct()
    {
        $this->promoCodeModel = new PromoCodeModel();
        $this->gameModel = new GameModel();
        helper('permission');
    }

    public function index()
    {
        if (!canAccessModule('promo_codes')) {
            return redirect()->to('/admin/dashboard')->with('error', 'Accès refusé');
        }

        $data = [
            'title' => 'Codes Promo',
            'activeMenu' => 'promo-codes',
            'pageTitle' => 'Gestion des Codes Promo',
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
                ['title' => 'Codes Promo']
            ],
            'promoCodes' => $this->promoCodeModel->orderBy('created_at', 'DESC')->findAll(),
            'statistics' => $this->promoCodeModel->getStatistics()
        ];

        return view('admin/layouts/header', $data)
             . view('admin/layouts/sidebar', $data)
             . view('admin/layouts/topbar', $data)
             . view('admin/promo_codes/index', $data)
             . view('admin/layouts/footer', $data);
    }

    public function create()
    {
        if (!canAccessModule('promo_codes')) {
            return redirect()->to('/admin/dashboard')->with('error', 'Accès refusé');
        }

        $data = [
            'title' => 'Créer un Code Promo',
            'activeMenu' => 'promo-codes',
            'pageTitle' => 'Créer un Code Promo',
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
                ['title' => 'Codes Promo', 'url' => base_url('admin/promo-codes')],
                ['title' => 'Créer']
            ],
            'games' => $this->gameModel->where('is_active', 1)->findAll()
        ];

        return view('admin/layouts/header', $data)
             . view('admin/layouts/sidebar', $data)
             . view('admin/layouts/topbar', $data)
             . view('admin/promo_codes/form', $data)
             . view('admin/layouts/footer', $data);
    }

    public function store()
    {
        if (!canAccessModule('promo_codes')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Accès refusé']);
        }

        $validation = \Config\Services::validation();
        $validation->setRules([
            'code' => 'required|min_length[3]|max_length[50]|is_unique[promo_codes.code]',
            'discount_type' => 'required|in_list[percentage,fixed]',
            'discount_value' => 'required|decimal|greater_than[0]',
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $validation->getErrors()
            ]);
        }

        $applicableGames = $this->request->getPost('applicable_games');
        
        $data = [
            'code' => $this->request->getPost('code'),
            'description' => $this->request->getPost('description'),
            'discount_type' => $this->request->getPost('discount_type'),
            'discount_value' => $this->request->getPost('discount_value'),
            'min_amount' => $this->request->getPost('min_amount') ?: null,
            'max_discount' => $this->request->getPost('max_discount') ?: null,
            'usage_limit' => $this->request->getPost('usage_limit') ?: null,
            'valid_from' => $this->request->getPost('valid_from') ?: null,
            'valid_until' => $this->request->getPost('valid_until') ?: null,
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
            'applicable_games' => !empty($applicableGames) ? json_encode($applicableGames) : null
        ];

        if ($this->promoCodeModel->insert($data)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Code promo créé avec succès'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Erreur lors de la création du code promo'
        ]);
    }

    public function edit($id)
    {
        if (!canAccessModule('promo_codes')) {
            return redirect()->to('/admin/dashboard')->with('error', 'Accès refusé');
        }

        $promoCode = $this->promoCodeModel->find($id);
        
        if (!$promoCode) {
            return redirect()->to('/admin/promo-codes')->with('error', 'Code promo non trouvé');
        }

        $data = [
            'title' => 'Modifier le Code Promo',
            'activeMenu' => 'promo-codes',
            'pageTitle' => 'Modifier le Code Promo',
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
                ['title' => 'Codes Promo', 'url' => base_url('admin/promo-codes')],
                ['title' => 'Modifier']
            ],
            'promoCode' => $promoCode,
            'games' => $this->gameModel->where('is_active', 1)->findAll()
        ];

        return view('admin/layouts/header', $data)
             . view('admin/layouts/sidebar', $data)
             . view('admin/layouts/topbar', $data)
             . view('admin/promo_codes/form', $data)
             . view('admin/layouts/footer', $data);
    }

    public function update($id)
    {
        if (!canAccessModule('promo_codes')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Accès refusé']);
        }

        $promoCode = $this->promoCodeModel->find($id);
        
        if (!$promoCode) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Code promo non trouvé'
            ]);
        }

        $validation = \Config\Services::validation();
        $validation->setRules([
            'code' => "required|min_length[3]|max_length[50]|is_unique[promo_codes.code,id,{$id}]",
            'discount_type' => 'required|in_list[percentage,fixed]',
            'discount_value' => 'required|decimal|greater_than[0]',
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $validation->getErrors()
            ]);
        }

        $applicableGames = $this->request->getPost('applicable_games');
        
        $data = [
            'code' => $this->request->getPost('code'),
            'description' => $this->request->getPost('description'),
            'discount_type' => $this->request->getPost('discount_type'),
            'discount_value' => $this->request->getPost('discount_value'),
            'min_amount' => $this->request->getPost('min_amount') ?: null,
            'max_discount' => $this->request->getPost('max_discount') ?: null,
            'usage_limit' => $this->request->getPost('usage_limit') ?: null,
            'valid_from' => $this->request->getPost('valid_from') ?: null,
            'valid_until' => $this->request->getPost('valid_until') ?: null,
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
            'applicable_games' => !empty($applicableGames) ? json_encode($applicableGames) : null
        ];

        if ($this->promoCodeModel->update($id, $data)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Code promo modifié avec succès'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Erreur lors de la modification du code promo'
        ]);
    }

    public function delete($id)
    {
        if (!canAccessModule('promo_codes')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Accès refusé']);
        }

        $promoCode = $this->promoCodeModel->find($id);
        
        if (!$promoCode) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Code promo non trouvé'
            ]);
        }

        if ($this->promoCodeModel->delete($id)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Code promo supprimé avec succès'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Erreur lors de la suppression du code promo'
        ]);
    }

    public function toggleStatus($id)
    {
        if (!canAccessModule('promo_codes')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Accès refusé']);
        }

        $promoCode = $this->promoCodeModel->find($id);
        
        if (!$promoCode) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Code promo non trouvé'
            ]);
        }

        $newStatus = $promoCode['is_active'] ? 0 : 1;

        if ($this->promoCodeModel->update($id, ['is_active' => $newStatus])) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Statut modifié avec succès',
                'new_status' => $newStatus
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Erreur lors de la modification du statut'
        ]);
    }

    public function validatePromoCode()
    {
        $code = $this->request->getGet('code');
        $amount = (float) $this->request->getGet('amount');

        $result = $this->promoCodeModel->validatePromoCode($code, $amount);

        if ($result['valid']) {
            $discount = $this->promoCodeModel->calculateDiscount($result['promo'], $amount);
            $result['discount'] = $discount;
            $result['final_amount'] = $amount - $discount;
        }

        return $this->response->setJSON($result);
    }
}
