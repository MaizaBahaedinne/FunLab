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
     * Configuration du Footer
     */
    public function footer()
    {
        if ($this->request->getMethod() === 'post') {
            $data = $this->request->getPost();
            
            // Debug : écrire dans les logs
            log_message('info', 'Footer POST data: ' . print_r($data, true));
            
            unset($data['csrf_test_name']); // Retirer le token CSRF

            $updated = 0;
            $errors = [];
            
            foreach ($data as $key => $value) {
                // Déterminer le type selon le champ
                $type = 'text';
                if (strpos($key, 'hours') !== false || strpos($key, 'description') !== false) {
                    $type = 'textarea';
                }
                
                log_message('info', "Updating $key = $value (type: $type, category: footer)");
                
                $result = $this->settingModel->setSetting($key, $value, $type, 'footer');
                
                if ($result) {
                    $updated++;
                } else {
                    $errors[] = $key;
                }
            }

            log_message('info', "Footer update complete: $updated updated, errors: " . implode(', ', $errors));

            if ($updated > 0) {
                return redirect()->to('/admin/settings/footer')
                               ->with('success', "Configuration du footer mise à jour avec succès ($updated champs modifiés)");
            } else {
                return redirect()->to('/admin/settings/footer')
                               ->with('error', 'Aucune modification effectuée. Champs en erreur: ' . implode(', ', $errors));
            }
        }

        $data = [
            'title' => 'Configuration Footer',
            'settings' => $this->settingModel->getByCategory('footer')
        ];

        return view('admin/settings/footer', $data);
    }

    /**
     * Configuration OAuth (Google, Facebook)
     */
    public function oauth()
    {
        $data = [
            'title' => 'Authentification OAuth',
            'settings' => $this->settingModel->getByCategory('oauth')
        ];

        return view('admin/settings/oauth', $data);
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
            'username' => 'required|min_length[3]|is_unique[users.username]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[8]',
            'role' => 'required|in_list[admin,staff,customer]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $validation->getErrors());
        }

        $this->userModel->insert([
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role' => $this->request->getPost('role'),
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name')
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
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'role' => $this->request->getPost('role'),
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name')
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
        $testEmail = $this->request->getPost('test_email');
        
        if (!$testEmail) {
            return redirect()->back()->with('error', 'Email de test requis');
        }

        // Charger les paramètres email depuis la base de données
        $settings = $this->settingModel->getByCategory('mail');

        // S'assurer que le mot de passe est bien une chaîne
        $smtpPass = isset($settings['mail_smtp_pass']) ? trim($settings['mail_smtp_pass']) : '';

        // Configurer l'email avec les paramètres de la base de données
        $config = [
            'protocol'     => $settings['mail_protocol'] ?? 'mail',
            'SMTPHost'     => $settings['mail_smtp_host'] ?? '',
            'SMTPPort'     => (int)($settings['mail_smtp_port'] ?? 587),
            'SMTPUser'     => $settings['mail_smtp_user'] ?? '',
            'SMTPPass'     => $smtpPass,
            'SMTPCrypto'   => $settings['mail_smtp_crypto'] ?? 'tls',
            'SMTPAuth'     => true,
            'mailType'     => 'html',
            'charset'      => 'utf-8',
            'newline'      => "\r\n",
            'SMTPTimeout'  => 30,
            'validation'   => true,
            'wordWrap'     => true
        ];

        // Si port 465, SSL sans STARTTLS
        if ($config['SMTPPort'] == 465) {
            $config['SMTPCrypto'] = 'ssl';
        } elseif ($config['SMTPPort'] == 587) {
            $config['SMTPCrypto'] = 'tls';
        }

        $email = \Config\Services::email($config);
        
        $email->setFrom(
            $settings['mail_from_email'] ?? 'noreply@funlab.tn',
            $settings['mail_from_name'] ?? 'FunLab'
        );
        $email->setTo($testEmail);
        $email->setSubject('Test de configuration email - FunLab');
        
        $message = '
        <html>
        <body style="font-family: Arial, sans-serif; padding: 20px;">
            <h2 style="color: #667eea;">✅ Test de configuration email</h2>
            <p>Ceci est un email de test envoyé depuis FunLab.</p>
            <p>Si vous recevez ce message, cela signifie que la configuration email fonctionne correctement.</p>
            <hr>
            <p style="color: #666; font-size: 12px;">
                Envoyé le ' . date('d/m/Y à H:i:s') . '<br>
                Depuis: ' . ($settings['mail_from_email'] ?? 'noreply@funlab.tn') . '
            </p>
        </body>
        </html>
        ';
        
        $email->setMessage($message);

        if ($email->send()) {
            return redirect()->back()->with('success', '✅ Email de test envoyé avec succès à ' . $testEmail);
        } else {
            $debugInfo = $email->printDebugger(['headers', 'subject', 'body']);
            
            // Informations de configuration pour le debug
            $configInfo = "Config: " . $config['protocol'] . " | " . 
                         $config['SMTPHost'] . ":" . $config['SMTPPort'] . " | " . 
                         $config['SMTPCrypto'];
            
            // Extraire l'erreur principale
            $errorMessage = 'Configuration incorrecte';
            if (strpos($debugInfo, 'fsockopen') !== false || strpos($debugInfo, 'Connection') !== false) {
                $errorMessage = 'Impossible de se connecter au serveur SMTP. Vérifiez le serveur et le port';
            } elseif (strpos($debugInfo, 'authentication failed') !== false || strpos($debugInfo, 'Username and Password') !== false) {
                $errorMessage = 'Authentification échouée. Vérifiez l\'utilisateur et le mot de passe';
            } elseif (strpos($debugInfo, 'STARTTLS') !== false) {
                $errorMessage = 'Erreur TLS/SSL. Essayez de changer le cryptage (TLS↔SSL)';
            }
            
            return redirect()->back()->with('error', 
                '❌ ' . $errorMessage . 
                '<br><small>' . $configInfo . '</small>' .
                '<br><details><summary>Détails techniques</summary><pre style="font-size:10px;max-height:200px;overflow:auto;">' . 
                esc($debugInfo) . '</pre></details>'
            );
        }
    }
}
