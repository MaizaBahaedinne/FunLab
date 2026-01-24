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
     * Page principale des paramètres - Rediriger vers général
     */
    public function index()
    {
        return redirect()->to('/admin/settings/general');
    }

    /**
     * Paramètres généraux
     */
    public function general()
    {
        $data = [
            'title' => 'Paramètres généraux',
            'settings' => $this->settingModel->getByCategory('general')
        ];

        return view('admin/settings/general', $data);
    }

    /**
     * Horaires de travail
     */
    public function hours()
    {
        $data = [
            'title' => 'Horaires de travail',
            'settings' => $this->settingModel->getByCategory('hours')
        ];

        return view('admin/settings/hours', $data);
    }

    /**
     * Configuration email
     */
    public function mail()
    {
        $data = [
            'title' => 'Configuration Email',
            'settings' => $this->settingModel->getByCategory('mail')
        ];

        return view('admin/settings/mail', $data);
    }

    /**
     * Configuration SMS
     */
    public function sms()
    {
        $data = [
            'title' => 'Configuration SMS',
            'settings' => $this->settingModel->getByCategory('sms')
        ];

        return view('admin/settings/sms', $data);
    }

    /**
     * Paramètres SEO
     */
    public function seo()
    {
        $data = [
            'title' => 'Référencement SEO',
            'settings' => $this->settingModel->getByCategory('seo')
        ];

        return view('admin/settings/seo', $data);
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

            $this->settingModel->setSetting($key, $value, $type, $category);
        }

        return redirect()->to('/admin/settings/' . $category)
            ->with('success', 'Paramètres sauvegardés avec succès');
    }

    /**
     * Gestion des rôles et permissions
     */
    public function roles()
    {
        $data = [
            'title' => 'Gestion des rôles et permissions',
            'roles' => [
                [
                    'name' => 'admin',
                    'label' => 'Administrateur',
                    'description' => 'Accès complet à toutes les fonctionnalités'
                ],
                [
                    'name' => 'staff',
                    'label' => 'Staff',
                    'description' => 'Accès aux réservations et scanner'
                ],
                [
                    'name' => 'user',
                    'label' => 'Utilisateur',
                    'description' => 'Accès client standard'
                ]
            ],
            'modules' => [
                'dashboard' => 'Tableau de bord',
                'bookings' => 'Réservations',
                'games' => 'Jeux',
                'rooms' => 'Salles',
                'closures' => 'Fermetures',
                'scanner' => 'Scanner QR',
                'settings' => 'Paramètres',
                'users' => 'Utilisateurs'
            ],
            'permissions' => $this->getRolePermissions()
        ];

        return view('admin/settings/roles', $data);
    }

    /**
     * Récupérer les permissions par rôle
     */
    private function getRolePermissions()
    {
        return [
            'admin' => [
                'dashboard' => ['view', 'create', 'edit', 'delete'],
                'bookings' => ['view', 'create', 'edit', 'delete'],
                'games' => ['view', 'create', 'edit', 'delete'],
                'rooms' => ['view', 'create', 'edit', 'delete'],
                'closures' => ['view', 'create', 'edit', 'delete'],
                'scanner' => ['view', 'scan'],
                'settings' => ['view', 'edit'],
                'users' => ['view', 'create', 'edit', 'delete']
            ],
            'staff' => [
                'dashboard' => ['view'],
                'bookings' => ['view', 'edit'],
                'games' => ['view'],
                'rooms' => ['view'],
                'closures' => ['view'],
                'scanner' => ['view', 'scan'],
                'settings' => [],
                'users' => []
            ],
            'user' => [
                'dashboard' => [],
                'bookings' => ['view'],
                'games' => ['view'],
                'rooms' => ['view'],
                'closures' => [],
                'scanner' => [],
                'settings' => [],
                'users' => []
            ]
        ];
    }

    /**
     * Mettre à jour les permissions d'un rôle
     */
    public function updateRolePermissions()
    {
        // TODO: Implémenter la sauvegarde des permissions dans la base de données
        return redirect()->back()->with('success', 'Permissions mises à jour');
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
        $this->settingModel->setSetting($key, $path, 'image', $category);

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
