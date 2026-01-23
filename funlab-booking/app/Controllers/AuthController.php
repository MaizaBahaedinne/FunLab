<?php

namespace App\Controllers;

use App\Models\UserModel;

class AuthController extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * Affiche la page de login
     */
    public function login()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/account');
        }

        return view('auth/login');
    }

    /**
     * Traite le login
     */
    public function attemptLogin()
    {
        $validation = \Config\Services::validation();

        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required|min_length[8]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $remember = $this->request->getPost('remember');

        $user = $this->userModel->verifyPassword($email, $password);

        if (!$user) {
            return redirect()->back()->withInput()->with('error', 'Email ou mot de passe incorrect');
        }

        if (!$user['is_active']) {
            return redirect()->back()->withInput()->with('error', 'Votre compte est désactivé');
        }

        // Créer la session
        $sessionData = [
            'userId' => $user['id'],
            'email' => $user['email'],
            'username' => $user['username'],
            'firstName' => $user['first_name'],
            'lastName' => $user['last_name'],
            'role' => $user['role'],
            'avatar' => $user['avatar'],
            'isLoggedIn' => true,
            'isAdmin' => $user['role'] === 'admin',
            'isStaff' => in_array($user['role'], ['staff', 'admin'])
        ];

        session()->set($sessionData);

        // Cookie "Remember Me"
        if ($remember) {
            $this->setRememberMeCookie($user['id']);
        }

        // Redirection selon le rôle
        if ($user['role'] === 'admin' || $user['role'] === 'staff') {
            return redirect()->to('/admin/dashboard')->with('success', 'Bienvenue ' . $user['first_name']);
        }

        return redirect()->to('/account')->with('success', 'Connexion réussie');
    }

    /**
     * Affiche la page de register
     */
    public function register()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/account');
        }

        return view('auth/register');
    }

    /**
     * Traite l'inscription
     */
    public function attemptRegister()
    {
        $validation = \Config\Services::validation();

        $rules = [
            'first_name' => 'required|min_length[2]|max_length[100]',
            'last_name' => 'required|min_length[2]|max_length[100]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[8]',
            'password_confirm' => 'required|matches[password]',
            'phone' => 'permit_empty|min_length[8]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $userData = [
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'email' => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'phone' => $this->request->getPost('phone'),
            'username' => $this->generateUsername($this->request->getPost('email')),
            'role' => 'customer',
            'auth_provider' => 'native',
            'is_active' => 1
        ];

        $userId = $this->userModel->insert($userData);

        if (!$userId) {
            return redirect()->back()->withInput()->with('error', 'Erreur lors de la création du compte');
        }

        // Connexion automatique
        $user = $this->userModel->find($userId);
        
        $sessionData = [
            'userId' => $user['id'],
            'email' => $user['email'],
            'username' => $user['username'],
            'firstName' => $user['first_name'],
            'lastName' => $user['last_name'],
            'role' => $user['role'],
            'isLoggedIn' => true,
            'isAdmin' => false,
            'isStaff' => false
        ];

        session()->set($sessionData);

        return redirect()->to('/account')->with('success', 'Compte créé avec succès !');
    }

    /**
     * Déconnexion
     */
    public function logout()
    {
        session()->destroy();
        
        // Supprimer le cookie Remember Me
        if (isset($_COOKIE['remember_me'])) {
            setcookie('remember_me', '', time() - 3600, '/');
        }

        return redirect()->to('/')->with('success', 'Déconnexion réussie');
    }

    /**
     * Mot de passe oublié
     */
    public function forgotPassword()
    {
        return view('auth/forgot_password');
    }

    /**
     * Envoie l'email de réinitialisation
     */
    public function sendResetLink()
    {
        $email = $this->request->getPost('email');

        if (!$this->userModel->emailExists($email)) {
            return redirect()->back()->with('error', 'Aucun compte avec cet email');
        }

        $token = $this->userModel->createPasswordResetToken($email);
        
        // Envoyer l'email
        $resetLink = base_url("auth/reset-password/$token");
        
        $emailService = \Config\Services::email();
        $emailService->setTo($email);
        $emailService->setSubject('Réinitialisation de mot de passe - FunLab');
        $emailService->setMessage($this->getResetEmailTemplate($resetLink));
        $emailService->setMailType('html');

        if ($emailService->send()) {
            return redirect()->back()->with('success', 'Un email de réinitialisation a été envoyé');
        }

        return redirect()->back()->with('error', 'Erreur lors de l\'envoi de l\'email');
    }

    /**
     * Formulaire de réinitialisation
     */
    public function resetPassword($token)
    {
        $reset = $this->userModel->verifyPasswordResetToken($token);

        if (!$reset) {
            return redirect()->to('/auth/login')->with('error', 'Lien de réinitialisation invalide ou expiré');
        }

        return view('auth/reset_password', ['token' => $token, 'email' => $reset['email']]);
    }

    /**
     * Traite la réinitialisation
     */
    public function updatePassword()
    {
        $token = $this->request->getPost('token');
        $password = $this->request->getPost('password');
        $passwordConfirm = $this->request->getPost('password_confirm');

        if ($password !== $passwordConfirm) {
            return redirect()->back()->with('error', 'Les mots de passe ne correspondent pas');
        }

        if (strlen($password) < 8) {
            return redirect()->back()->with('error', 'Le mot de passe doit contenir au moins 8 caractères');
        }

        $reset = $this->userModel->verifyPasswordResetToken($token);

        if (!$reset) {
            return redirect()->to('/auth/login')->with('error', 'Lien de réinitialisation invalide');
        }

        // Mettre à jour le mot de passe
        $user = $this->userModel->where('email', $reset['email'])->first();
        $this->userModel->update($user['id'], ['password' => $password]);

        // Supprimer le token
        $this->userModel->deletePasswordResetToken($token);

        return redirect()->to('/auth/login')->with('success', 'Mot de passe réinitialisé avec succès');
    }

    /**
     * Génère un username unique
     */
    protected function generateUsername($email)
    {
        $username = explode('@', $email)[0];
        $baseUsername = preg_replace('/[^a-zA-Z0-9]/', '', $username);
        $counter = 1;

        while ($this->userModel->where('username', $baseUsername)->first()) {
            $baseUsername = $username . $counter;
            $counter++;
        }

        return $baseUsername;
    }

    /**
     * Cookie Remember Me
     */
    protected function setRememberMeCookie($userId)
    {
        $token = bin2hex(random_bytes(32));
        setcookie('remember_me', $token, time() + (86400 * 30), '/'); // 30 jours
        
        // Stocker le token en BDD (optionnel pour plus de sécurité)
    }

    /**
     * Template email réinitialisation
     */
    protected function getResetEmailTemplate($resetLink)
    {
        return <<<HTML
<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; text-align: center; }
        .content { padding: 30px; background: white; }
        .button { display: inline-block; padding: 12px 30px; background: #667eea; color: white; text-decoration: none; border-radius: 5px; margin: 20px 0; }
        .footer { text-align: center; padding: 20px; color: #666; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>FunLab Tunisie</h1>
        </div>
        <div class="content">
            <h2>Réinitialisation de mot de passe</h2>
            <p>Vous avez demandé à réinitialiser votre mot de passe.</p>
            <p>Cliquez sur le bouton ci-dessous pour créer un nouveau mot de passe :</p>
            <a href="$resetLink" class="button">Réinitialiser mon mot de passe</a>
            <p>Ce lien expire dans 1 heure.</p>
            <p>Si vous n'avez pas demandé cette réinitialisation, ignorez cet email.</p>
        </div>
        <div class="footer">
            <p>FunLab Tunisie | contact@funlab.tn</p>
        </div>
    </div>
</body>
</html>
HTML;
    }
}
