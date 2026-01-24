<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SettingModel;
use App\Models\UserModel;

class SettingsController extends BaseController
{
    protected $settingModel;
    protected $userModel;

    public function __construct()
    {
        $this->settingModel = new SettingModel();
        $this->userModel = new UserModel();
    }

    /**
     * Page principale des paramètres
     */
    public function index()
    {
        $tab = $this->request->getGet('tab') ?? 'general';

        $data = [
            'title' => 'Paramètres',
            'activeTab' => $tab,
            'settings' => [
                'general' => $this->settingModel->getByCategory('general'),
                'hours' => $this->settingModel->getByCategory('hours'),
                'mail' => $this->settingModel->getByCategory('mail'),
                'mail_template' => $this->settingModel->getByCategory('mail_template'),
                'sms' => $this->settingModel->getByCategory('sms'),
                'seo' => $this->settingModel->getByCategory('seo')
            ]
        ];

        return view('admin/settings/index', $data);
    }

    /**
     * Sauvegarder les paramètres
     */
    public function save()
    {
        $category = $this->request->getPost('category');
        $settings = $this->request->getPost('settings');

        if (!$category || !$settings) {
            return redirect()->back()->with('error', 'Données invalides');
        }

        foreach ($settings as $key => $value) {
            // Déterminer le type
            $type = 'text';
            if (is_array($value)) {
                $type = 'json';
            } elseif (in_array($value, ['0', '1', 'true', 'false'])) {
                $type = 'boolean';
            } elseif (is_numeric($value)) {
                $type = 'number';
            }

            $this->settingModel->set($key, $value, $type, $category);
        }

        return redirect()->to('/admin/settings?tab=' . $category)
            ->with('success', 'Paramètres sauvegardés avec succès');
    }

    /**
     * Upload d'image (logo, etc.)
     */
    public function uploadImage()
    {
        $key = $this->request->getPost('key');
        $category = $this->request->getPost('category');
        $file = $this->request->getFile('image');

        if (!$file->isValid()) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Fichier invalide'
            ]);
        }

        // Valider le type d'image
        if (!in_array($file->getMimeType(), ['image/jpeg', 'image/png', 'image/gif'])) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Type de fichier non autorisé'
            ]);
        }

        // Déplacer le fichier
        $newName = $file->getRandomName();
        $file->move(FCPATH . 'uploads/settings', $newName);

        // Sauvegarder dans les paramètres
        $path = '/uploads/settings/' . $newName;
        $this->settingModel->set($key, $path, 'image', $category);

        return $this->response->setJSON([
            'status' => 'success',
            'path' => $path
        ]);
    }

    /**
     * Gestion des utilisateurs
     */
    public function users()
    {
        $users = $this->userModel->findAll();

        $data = [
            'title' => 'Gestion des utilisateurs',
            'users' => $users
        ];

        return view('admin/settings/users', $data);
    }

    /**
     * Créer un utilisateur
     */
    public function createUser()
    {
        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'name' => 'required|min_length[3]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[8]',
            'role' => 'required|in_list[admin,staff,user]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $validation->getErrors());
        }

        $this->userModel->insert([
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role' => $this->request->getPost('role')
        ]);

        return redirect()->to('/admin/settings/users')
            ->with('success', 'Utilisateur créé avec succès');
    }

    /**
     * Modifier un utilisateur
     */
    public function updateUser($id)
    {
        $data = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'role' => $this->request->getPost('role')
        ];

        // Mettre à jour le mot de passe si fourni
        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $this->userModel->update($id, $data);

        return redirect()->back()
            ->with('success', 'Utilisateur mis à jour');
    }

    /**
     * Supprimer un utilisateur
     */
    public function deleteUser($id)
    {
        // Ne pas supprimer son propre compte
        if ($id == session()->get('userId')) {
            return redirect()->back()
                ->with('error', 'Vous ne pouvez pas supprimer votre propre compte');
        }

        $this->userModel->delete($id);

        return redirect()->back()
            ->with('success', 'Utilisateur supprimé');
    }

    /**
     * Tester la configuration email
     */
    public function testEmail()
    {
        $email = \Config\Services::email();
        
        $testEmail = $this->request->getPost('test_email');
        
        if (!$testEmail) {
            return redirect()->back()->with('error', 'Email de test requis');
        }

        $email->setTo($testEmail);
        $email->setSubject('Test de configuration email - FunLab');
        $email->setMessage('Ceci est un email de test. Si vous recevez ce message, la configuration email fonctionne correctement.');

        if ($email->send()) {
            return redirect()->back()->with('success', 'Email de test envoyé avec succès');
        } else {
            return redirect()->back()->with('error', 'Erreur lors de l\'envoi: ' . $email->printDebugger());
        }
    }
}
