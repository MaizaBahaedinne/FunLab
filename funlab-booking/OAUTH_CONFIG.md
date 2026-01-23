# Configuration OAuth - FunLab Booking

Ce document explique comment configurer l'authentification OAuth avec Google et Facebook.

## Installation des dépendances

```bash
composer require league/oauth2-client
composer require league/oauth2-google
composer require league/oauth2-facebook
```

## Configuration .env

Ajoutez ces variables à votre fichier `.env` :

```env
#--------------------------------------------------------------------
# OAuth Configuration
#--------------------------------------------------------------------

# Google OAuth
GOOGLE_CLIENT_ID=your-google-client-id.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=your-google-client-secret

# Facebook OAuth
FACEBOOK_APP_ID=your-facebook-app-id
FACEBOOK_APP_SECRET=your-facebook-app-secret
```

## 1. Configuration Google OAuth

### Étapes :

1. **Créer un projet Google Cloud**
   - Allez sur https://console.cloud.google.com/
   - Créez un nouveau projet "FunLab Booking"

2. **Activer Google+ API**
   - Dans "APIs & Services" > "Library"
   - Cherchez "Google+ API" et activez-la

3. **Créer des identifiants OAuth 2.0**
   - Dans "APIs & Services" > "Credentials"
   - Cliquez sur "Create Credentials" > "OAuth client ID"
   - Type d'application : "Web application"
   - Nom : "FunLab Website"
   
4. **Configurer les URIs autorisés**
   - **Authorized JavaScript origins** :
     ```
     http://localhost:8080
     https://funlab.tn
     ```
   
   - **Authorized redirect URIs** :
     ```
     http://localhost:8080/auth/google/callback
     https://funlab.tn/auth/google/callback
     ```

5. **Récupérer les identifiants**
   - Copiez le `Client ID` et le `Client Secret`
   - Ajoutez-les dans votre `.env`

### Scopes demandés :
- `email` : Accéder à l'adresse email
- `profile` : Accéder au nom et photo de profil

---

## 2. Configuration Facebook OAuth

### Étapes :

1. **Créer une application Facebook**
   - Allez sur https://developers.facebook.com/
   - Cliquez sur "My Apps" > "Create App"
   - Type : "Consumer"
   - Nom : "FunLab Booking"

2. **Ajouter Facebook Login**
   - Dans le dashboard de votre app
   - Cliquez sur "Add Product"
   - Sélectionnez "Facebook Login" > "Set Up"
   - Choisissez "Web"

3. **Configurer les paramètres OAuth**
   - Dans "Facebook Login" > "Settings"
   - **Valid OAuth Redirect URIs** :
     ```
     http://localhost:8080/auth/facebook/callback
     https://funlab.tn/auth/facebook/callback
     ```

4. **Récupérer les identifiants**
   - Dans "Settings" > "Basic"
   - Copiez `App ID` et `App Secret`
   - Ajoutez-les dans votre `.env`

5. **Mettre l'app en mode Production**
   - Dans "App Review"
   - Activez "Make app public"

### Permissions demandées :
- `email` : Obligatoire
- `public_profile` : Inclus par défaut (nom, photo)

---

## 3. Configuration des Routes

Ajoutez ces routes dans `app/Config/Routes.php` :

```php
// Routes d'authentification
$routes->group('auth', function($routes) {
    // Login/Register natif
    $routes->get('login', 'AuthController::login');
    $routes->post('login', 'AuthController::attemptLogin');
    $routes->get('register', 'AuthController::register');
    $routes->post('register', 'AuthController::attemptRegister');
    $routes->get('logout', 'AuthController::logout');
    
    // Mot de passe oublié
    $routes->get('forgot-password', 'AuthController::forgotPassword');
    $routes->post('forgot-password', 'AuthController::sendResetLink');
    $routes->get('reset-password/(:any)', 'AuthController::resetPassword/$1');
    $routes->post('reset-password', 'AuthController::updatePassword');
    
    // OAuth Google
    $routes->get('google', 'SocialAuthController::redirectToGoogle');
    $routes->get('google/callback', 'SocialAuthController::handleGoogleCallback');
    
    // OAuth Facebook
    $routes->get('facebook', 'SocialAuthController::redirectToFacebook');
    $routes->get('facebook/callback', 'SocialAuthController::handleFacebookCallback');
});

// Routes protégées (nécessitent authentification)
$routes->group('account', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'AccountController::index');
    $routes->get('profile', 'AccountController::profile');
    $routes->post('profile', 'AccountController::updateProfile');
    $routes->get('bookings', 'AccountController::bookings');
    $routes->get('password', 'AccountController::changePassword');
    $routes->post('password', 'AccountController::updatePassword');
});
```

---

## 4. Création du filtre Auth

Créez `app/Filters/Auth.php` :

```php
<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class Auth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login')->with('error', 'Veuillez vous connecter');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Rien à faire après
    }
}
```

Ajoutez le filtre dans `app/Config/Filters.php` :

```php
public $aliases = [
    // ... autres filtres
    'auth' => \App\Filters\Auth::class,
];
```

---

## 5. Test de l'authentification

### Test Google OAuth :
1. Visitez : `http://localhost:8080/auth/login`
2. Cliquez sur "Continuer avec Google"
3. Sélectionnez votre compte Google
4. Autorisez l'accès
5. Vous devriez être redirigé vers `/account`

### Test Facebook OAuth :
1. Visitez : `http://localhost:8080/auth/login`
2. Cliquez sur "Continuer avec Facebook"
3. Connectez-vous à Facebook
4. Autorisez l'application
5. Vous devriez être redirigé vers `/account`

---

## 6. Sécurité

### Protection CSRF :
- Tous les formulaires utilisent `<?= csrf_field() ?>`
- Les callbacks OAuth utilisent un `state` token

### Validation des données :
- Email : Format valide
- Mot de passe : Minimum 8 caractères
- Hash : PASSWORD_DEFAULT (bcrypt)

### Sessions :
- Durée : 2 heures par défaut
- Cookie "Remember Me" : 30 jours
- Régénération de l'ID de session après login

---

## 7. Gestion des erreurs

Les erreurs courantes :

| Erreur | Cause | Solution |
|--------|-------|----------|
| "Invalid client ID" | Mauvais GOOGLE_CLIENT_ID | Vérifier les credentials Google |
| "Invalid redirect URI" | URI non autorisé | Ajouter l'URI dans la console Google/Facebook |
| "Email already exists" | Email déjà utilisé | L'utilisateur doit se connecter |
| "Invalid state" | Token CSRF invalide | Réessayer la connexion |

---

## 8. Base de données

La table `users` supporte à la fois l'authentification native et OAuth :

```sql
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(100),
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255), -- NULL pour les comptes OAuth
    first_name VARCHAR(100),
    last_name VARCHAR(100),
    phone VARCHAR(20),
    avatar TEXT,
    role ENUM('customer', 'staff', 'admin') DEFAULT 'customer',
    auth_provider ENUM('native', 'google', 'facebook') DEFAULT 'native',
    provider_id VARCHAR(255), -- ID de l'utilisateur chez Google/Facebook
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL
);
```

### Logique de liaison des comptes :
- Si l'utilisateur existe avec le même email → Liaison automatique
- Si l'utilisateur n'existe pas → Création d'un nouveau compte

---

## 9. URLs de test

### Développement (localhost) :
- Login : `http://localhost:8080/auth/login`
- Register : `http://localhost:8080/auth/register`
- Google OAuth : `http://localhost:8080/auth/google`
- Facebook OAuth : `http://localhost:8080/auth/facebook`

### Production :
- Login : `https://funlab.tn/auth/login`
- Register : `https://funlab.tn/auth/register`
- Google OAuth : `https://funlab.tn/auth/google`
- Facebook OAuth : `https://funlab.tn/auth/facebook`

---

## 10. Checklist de déploiement

- [ ] Installer les dépendances OAuth (`composer install`)
- [ ] Configurer les variables .env (GOOGLE_CLIENT_ID, etc.)
- [ ] Exécuter la migration de la base de données
- [ ] Configurer les URI de redirection dans Google Cloud Console
- [ ] Configurer les URI de redirection dans Facebook Developers
- [ ] Tester l'authentification native
- [ ] Tester Google OAuth
- [ ] Tester Facebook OAuth
- [ ] Vérifier les emails de réinitialisation de mot de passe
- [ ] Activer HTTPS en production

---

## Support

Pour toute question, consultez :
- Documentation Google OAuth : https://developers.google.com/identity/protocols/oauth2
- Documentation Facebook Login : https://developers.facebook.com/docs/facebook-login
- League OAuth2 Client : https://oauth2-client.thephpleague.com/
