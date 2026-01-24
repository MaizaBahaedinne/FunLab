<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\SettingModel;

/**
 * Controller pour l'authentification via OAuth (Google, Facebook)
 * 
 * Nécessite l'installation de la bibliothèque OAuth2 Client:
 * composer require league/oauth2-client league/oauth2-google league/oauth2-facebook
 */
class SocialAuthController extends BaseController
{
    protected $userModel;
    protected $settingModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->settingModel = new SettingModel();
    }

    /**
     * Redirection vers Google OAuth
     */
    public function redirectToGoogle()
    {
        // Vérifier si Google OAuth est activé
        $settings = $this->settingModel->getByCategory('oauth');
        if (($settings['oauth_google_enabled'] ?? '0') !== '1') {
            return redirect()->to('/auth/login')->with('error', 'Authentification Google désactivée');
        }

        $provider = $this->getGoogleProvider();
        
        $authUrl = $provider->getAuthorizationUrl([
            'scope' => ['email', 'profile']
        ]);

        session()->set('oauth2state', $provider->getState());

        return redirect()->to($authUrl);
    }

    /**
     * Callback Google OAuth
     */
    public function handleGoogleCallback()
    {
        $provider = $this->getGoogleProvider();

        if (!$this->request->getGet('code')) {
            return redirect()->to('/auth/login')->with('error', 'Authentification Google annulée');
        }

        // Vérifier le state pour prévenir CSRF
        if (empty($this->request->getGet('state')) || 
            ($this->request->getGet('state') !== session()->get('oauth2state'))) {
            session()->remove('oauth2state');
            return redirect()->to('/auth/login')->with('error', 'État invalide');
        }

        try {
            // Obtenir le token d'accès
            $token = $provider->getAccessToken('authorization_code', [
                'code' => $this->request->getGet('code')
            ]);

            // Obtenir les informations de l'utilisateur
            $googleUser = $provider->getResourceOwner($token);
            $userData = $googleUser->toArray();

            // Créer ou trouver l'utilisateur
            $user = $this->userModel->findOrCreateOAuthUser('google', [
                'id' => $userData['sub'],
                'email' => $userData['email'],
                'first_name' => $userData['given_name'] ?? '',
                'last_name' => $userData['family_name'] ?? '',
                'avatar' => $userData['picture'] ?? null,
                'username' => $userData['email']
            ]);

            // Créer la session
            $this->createUserSession($user);

            return redirect()->to('/account')->with('success', 'Connexion réussie via Google');

        } catch (\Exception $e) {
            log_message('error', 'Google OAuth Error: ' . $e->getMessage());
            return redirect()->to('/auth/login')->with('error', 'Erreur lors de la connexion avec Google');
        }
    }

    /**
     * Redirection vers Facebook OAuth
     */
    public function redirectToFacebook()
    {
        // Vérifier si Facebook OAuth est activé
        $settings = $this->settingModel->getByCategory('oauth');
        if (($settings['oauth_facebook_enabled'] ?? '0') !== '1') {
            return redirect()->to('/auth/login')->with('error', 'Authentification Facebook désactivée');
        }

        $provider = $this->getFacebookProvider();
        
        $authUrl = $provider->getAuthorizationUrl([
            'scope' => ['email', 'public_profile']
        ]);

        session()->set('oauth2state', $provider->getState());

        return redirect()->to($authUrl);
    }

    /**
     * Callback Facebook OAuth
     */
    public function handleFacebookCallback()
    {
        $provider = $this->getFacebookProvider();

        if (!$this->request->getGet('code')) {
            return redirect()->to('/auth/login')->with('error', 'Authentification Facebook annulée');
        }

        // Vérifier le state
        if (empty($this->request->getGet('state')) || 
            ($this->request->getGet('state') !== session()->get('oauth2state'))) {
            session()->remove('oauth2state');
            return redirect()->to('/auth/login')->with('error', 'État invalide');
        }

        try {
            // Obtenir le token d'accès
            $token = $provider->getAccessToken('authorization_code', [
                'code' => $this->request->getGet('code')
            ]);

            // Obtenir les informations de l'utilisateur
            $facebookUser = $provider->getResourceOwner($token);
            $userData = $facebookUser->toArray();

            // Créer ou trouver l'utilisateur
            $user = $this->userModel->findOrCreateOAuthUser('facebook', [
                'id' => $userData['id'],
                'email' => $userData['email'] ?? '',
                'first_name' => $userData['first_name'] ?? '',
                'last_name' => $userData['last_name'] ?? '',
                'avatar' => $userData['picture']['data']['url'] ?? null,
                'username' => $userData['email'] ?? 'fb_' . $userData['id']
            ]);

            // Créer la session
            $this->createUserSession($user);

            return redirect()->to('/account')->with('success', 'Connexion réussie via Facebook');

        } catch (\Exception $e) {
            log_message('error', 'Facebook OAuth Error: ' . $e->getMessage());
            return redirect()->to('/auth/login')->with('error', 'Erreur lors de la connexion avec Facebook');
        }
    }
$settings = $this->settingModel->getByCategory('oauth');
        
        return new \League\OAuth2\Client\Provider\Google([
            'clientId'     => $settings['oauth_google_client_id'] ?? getenv('GOOGLE_CLIENT_ID'),
            'clientSecret' => $settings['oauth_google_client_secret'] ?? getenv('GOOGLE_CLIENT_SECRET'),
            'redirectUri'  => base_url('auth/google/callback'),
        ]);
    }

    /**
     * Crée le provider Facebook OAuth
     */
    protected function getFacebookProvider()
    {
        $settings = $this->settingModel->getByCategory('oauth');
        
        return new \League\OAuth2\Client\Provider\Facebook([
            'clientId'     => $settings['oauth_facebook_app_id'] ?? getenv('FACEBOOK_APP_ID'),
            'clientSecret' => $settings['oauth_facebook_app_secret'] ??ook OAuth
     */
    protected function getFacebookProvider()
    {
        return new \League\OAuth2\Client\Provider\Facebook([
            'clientId'     => getenv('FACEBOOK_APP_ID'),
            'clientSecret' => getenv('FACEBOOK_APP_SECRET'),
            'redirectUri'  => base_url('auth/facebook/callback'),
            'graphApiVersion' => 'v18.0',
        ]);
    }

    /**
     * Crée la session utilisateur
     */
    protected function createUserSession($user)
    {
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
    }
}
