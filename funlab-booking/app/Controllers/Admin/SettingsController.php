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
        
        // Charger le helper de permissions
        helper('permission');
    }

    /**
     * Page principale des paramètres - Rediriger vers général
     */
    public function index()
    {
        // Vérifier la permission d'accès aux paramètres
        if ($redirect = checkPermissionOrRedirect('settings', 'view')) {
            return $redirect;
        }
        
        return redirect()->to('/admin/settings/general');
    }

    /**
     * Paramètres généraux
     */
    public function general()
    {
        // Vérifier la permission d'accès aux paramètres
        if ($redirect = checkPermissionOrRedirect('settings', 'view')) {
            return $redirect;
        }
        
        $data = [
            'title' => 'Paramètres généraux',
            'settings' => $this->settingModel->getByCategoryAsArray('general')
        ];

        return view('admin/settings/general', $data);
    }

    /**
     * Horaires de travail
     */
    public function hours()
    {
        // Vérifier la permission d'accès aux paramètres
        if ($redirect = checkPermissionOrRedirect('settings', 'view')) {
            return $redirect;
        }
        
        $data = [
            'title' => 'Horaires de travail',
            'settings' => $this->settingModel->getByCategoryAsArray('hours')
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
            'settings' => $this->settingModel->getByCategoryAsArray('mail')
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
            'settings' => $this->settingModel->getByCategoryAsArray('sms')
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
            'settings' => $this->settingModel->getByCategoryAsArray('seo')
        ];

        return view('admin/settings/seo', $data);
    }

    /**
     * Configuration du Footer
     */
    public function footer()
    {
        // Vérifier si c'est une soumission POST
        if ($this->request->getMethod() === 'post' || $_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->request->getPost();
            
            // Si pas de données POST, afficher un message
            if (empty($data)) {
                return redirect()->to('/admin/settings/footer')
                               ->with('error', 'Aucune donnée reçue du formulaire');
            }
            
            // Debug : écrire dans les logs
            log_message('info', 'Footer POST data received: ' . count($data) . ' fields');
            
            unset($data['csrf_test_name']); // Retirer le token CSRF

            $updated = 0;
            $errors = [];
            
            foreach ($data as $key => $value) {
                // Ignorer les champs vides qui ne sont pas des strings
                if (!is_string($key)) continue;
                
                // Déterminer le type selon le champ
                $type = 'text';
                if (strpos($key, 'hours') !== false || strpos($key, 'description') !== false) {
                    $type = 'textarea';
                }
                
                try {
                    $result = $this->settingModel->setSetting($key, $value, $type, 'footer');
                    
                    if ($result) {
                        $updated++;
                    } else {
                        $errors[] = $key;
                    }
                } catch (\Exception $e) {
                    log_message('error', 'Error updating ' . $key . ': ' . $e->getMessage());
                    $errors[] = $key . ' (error)';
                }
            }

            if ($updated > 0) {
                return redirect()->to('/admin/settings/footer')
                               ->with('success', "Configuration du footer mise à jour avec succès ($updated champs modifiés)");
            } else {
                return redirect()->to('/admin/settings/footer')
                               ->with('error', 'Aucune modification effectuée. Erreurs: ' . implode(', ', $errors));
            }
        }

        $data = [
            'title' => 'Configuration Footer',
            'settings' => $this->settingModel->getByCategoryAsArray('footer')
        ];

        return view('admin/settings/footer', $data);
    }

    /**
     * Configuration de la page À Propos
     */
    public function about()
    {
        if ($this->request->getMethod() === 'post' || $_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->request->getPost();
            
            if (empty($data)) {
                return redirect()->to('/admin/settings/about')
                               ->with('error', 'Aucune donnée reçue du formulaire');
            }
            
            unset($data['csrf_test_name']);

            $updated = 0;
            foreach ($data as $key => $value) {
                if (!is_string($key)) continue;
                
                $type = (strpos($key, 'content') !== false || strpos($key, 'intro') !== false) ? 'textarea' : 'text';
                
                try {
                    $result = $this->settingModel->setSetting($key, $value, $type, 'about');
                    if ($result) {
                        $updated++;
                    }
                } catch (\Exception $e) {
                    log_message('error', 'Error updating ' . $key . ': ' . $e->getMessage());
                }
            }

            if ($updated > 0) {
                return redirect()->to('/admin/settings/about')
                               ->with('success', "Page À Propos mise à jour avec succès ($updated champs modifiés)");
            } else {
                return redirect()->to('/admin/settings/about')
                               ->with('error', 'Aucune modification effectuée');
            }
        }

        $data = [
            'title' => 'Configuration Page À Propos',
            'settings' => $this->settingModel->getByCategoryAsArray('about')
        ];

        return view('admin/settings/about', $data);
    }

    /**
     * Configuration de la page Contact
     */
    public function contact()
    {
        if ($this->request->getMethod() === 'post' || $_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->request->getPost();
            
            log_message('info', 'Contact form POST data received: ' . json_encode($data));
            
            if (empty($data)) {
                return redirect()->to('/admin/settings/contact')
                               ->with('error', 'Aucune donnée reçue du formulaire');
            }
            
            unset($data['csrf_test_name']);

            $updated = 0;
            $errors = [];
            
            // Charger les paramètres existants pour comparaison
            $existingSettings = [];
            $currentSettings = $this->settingModel->getByCategory('contact');
            foreach ($currentSettings as $setting) {
                if (is_array($setting) && isset($setting['key']) && isset($setting['value'])) {
                    $existingSettings[$setting['key']] = $setting['value'];
                }
            }
            
            log_message('info', 'Existing settings loaded: ' . json_encode($existingSettings));
            
            foreach ($data as $key => $value) {
                if (!is_string($key)) continue;
                
                // Normaliser les valeurs pour comparaison (trim et traiter null/empty comme équivalents)
                $newValue = trim($value);
                $oldValue = isset($existingSettings[$key]) ? trim($existingSettings[$key]) : '';
                
                // Vérifier si la valeur a réellement changé
                if ($oldValue === $newValue) {
                    log_message('debug', "Skipping unchanged setting: $key (both are: '{$oldValue}')");
                    continue; // Pas de changement, on saute
                }
                
                // Déterminer le type selon le champ
                $type = 'text';
                if (strpos($key, 'text') !== false || 
                    strpos($key, 'embed') !== false || 
                    strpos($key, 'address') !== false) {
                    $type = 'textarea';
                }
                
                try {
                    $result = $this->settingModel->setSetting($key, $newValue, $type, 'contact');
                    if ($result) {
                        $updated++;
                        log_message('info', "Updated setting: $key (changed from '{$oldValue}' to '{$newValue}')");
                    } else {
                        $errors[] = $key;
                        log_message('error', "Failed to update setting: $key");
                    }
                } catch (\Exception $e) {
                    $errors[] = $key;
                    log_message('error', 'Error updating ' . $key . ': ' . $e->getMessage());
                }
            }

            if ($updated > 0) {
                $message = "Page Contact mise à jour avec succès ($updated champs modifiés)";
                if (!empty($errors)) {
                    $message .= ". Erreurs: " . implode(', ', $errors);
                }
                return redirect()->to('/admin/settings/contact')
                               ->with('success', $message);
            } else {
                return redirect()->to('/admin/settings/contact')
                               ->with('info', 'Aucune modification détectée');
            }
        }

        $data = [
            'title' => 'Configuration Page Contact',
            'settings' => $this->settingModel->getByCategory('contact')
        ];

        return view('admin/settings/contact', $data);
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

        $updated = 0;
        $errors = [];

        foreach ($settings as $key => $value) {
            // Déterminer le type
            $type = 'text';
            if (is_array($value)) {
                $type = 'json';
                $value = json_encode($value);
            } elseif (in_array($value, ['0', '1', 'true', 'false'])) {
                $type = 'boolean';
            } elseif (is_numeric($value)) {
                $type = 'number';
            }

            // Trouver l'enregistrement existant
            $existing = $this->settingModel
                ->where('key', $key)
                ->where('category', $category)
                ->first();

            if ($existing) {
                // Update
                $result = $this->settingModel->update($existing['id'], [
                    'value' => $value,
                    'type' => $type
                ]);
                
                if ($result) {
                    $updated++;
                } else {
                    $errors[] = "Erreur mise à jour: $key";
                }
            } else {
                // Insert (désactiver validation temporairement)
                $this->settingModel->skipValidation(true);
                $result = $this->settingModel->insert([
                    'key' => $key,
                    'value' => $value,
                    'type' => $type,
                    'category' => $category
                ]);
                $this->settingModel->skipValidation(false);
                
                if ($result) {
                    $updated++;
                } else {
                    $errors[] = "Erreur insertion: $key";
                }
            }
        }

        if (!empty($errors)) {
            log_message('error', 'Erreurs sauvegarde settings: ' . implode(', ', $errors));
        }

        return redirect()->to('/admin/settings/' . $category)
            ->with('success', "$updated paramètre(s) sauvegardé(s) avec succès");
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
        // Vérifier la permission d'éditer les paramètres
        if ($redirect = checkPermissionOrRedirect('settings', 'edit')) {
            return $redirect;
        }
        
        $permissions = $this->request->getPost('permissions');
        
        if (!$permissions || !is_array($permissions)) {
            return redirect()->back()->with('error', 'Données de permissions invalides');
        }
        
        // Valider et nettoyer les permissions
        $validatedPermissions = [];
        $validRoles = ['admin', 'staff', 'user'];
        $validActions = ['view', 'create', 'edit', 'delete', 'scan', 'approve'];
        
        foreach ($permissions as $role => $modules) {
            if (!in_array($role, $validRoles)) {
                continue;
            }
            
            $validatedPermissions[$role] = [];
            
            foreach ($modules as $module => $actions) {
                if (!is_array($actions)) {
                    continue;
                }
                
                $validatedPermissions[$role][$module] = array_filter($actions, function($action) use ($validActions) {
                    return in_array($action, $validActions);
                });
            }
        }
        
        // Sauvegarder dans la base de données
        $permissionsJson = json_encode($validatedPermissions);
        $result = $this->settingModel->setSetting('role_permissions', $permissionsJson, 'text', 'permissions');
        
        if ($result) {
            return redirect()->back()->with('success', 'Permissions mises à jour avec succès');
        }
        
        return redirect()->back()->with('error', 'Erreur lors de la sauvegarde des permissions');
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
        $settings = $this->settingModel->getByCategoryAsArray('mail');

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

        // Activer le debug SMTP
        log_message('info', 'Tentative d\'envoi email de test vers: ' . $testEmail);
        log_message('info', 'Config SMTP: ' . $config['SMTPHost'] . ':' . $config['SMTPPort'] . ' (' . $config['SMTPCrypto'] . ')');
        log_message('info', 'User SMTP: ' . $config['SMTPUser']);

        if ($email->send()) {
            $debugInfo = $email->printDebugger(['headers']);
            log_message('info', 'Email envoyé avec succès. Debug: ' . $debugInfo);
            
            return redirect()->back()->with('success', 
                '✅ Email de test envoyé avec succès à ' . $testEmail . 
                '<br><small>⚠️ Si vous ne le recevez pas, vérifiez vos spams ou le compte email ' . $config['SMTPUser'] . '</small>' .
                '<br><details><summary>Détails SMTP</summary><pre style="font-size:10px;">' . 
                'Serveur: ' . $config['SMTPHost'] . ':' . $config['SMTPPort'] . '<br>' .
                'Utilisateur: ' . $config['SMTPUser'] . '<br>' .
                'Cryptage: ' . $config['SMTPCrypto'] . 
                '</pre></details>'
            );
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
