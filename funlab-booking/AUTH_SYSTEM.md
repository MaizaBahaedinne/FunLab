# 🔐 Système d'Authentification - FunLab Booking

## ✅ Système Complet (Phase 6)

Le système d'authentification est maintenant **entièrement développé** avec :

### 🎯 Fonctionnalités Disponibles

#### 1. **Authentification Native** (Email + Mot de passe)
- ✅ Inscription avec validation
- ✅ Connexion avec "Se souvenir de moi" (cookie 30 jours)
- ✅ Mot de passe oublié avec email
- ✅ Réinitialisation de mot de passe (token 1h)
- ✅ Hashage bcrypt automatique

#### 2. **OAuth Social Login**
- ✅ Connexion Google OAuth 2.0
- ✅ Connexion Facebook OAuth 2.0
- ✅ Création automatique de compte
- ✅ Liaison de compte par email

#### 3. **Gestion du Compte**
- ✅ Tableau de bord utilisateur
- ✅ Modification du profil
- ✅ Historique des réservations
- ✅ Changement de mot de passe
- ✅ Statistiques personnelles

#### 4. **Sécurité**
- ✅ Protection CSRF
- ✅ Filtres d'authentification
- ✅ Sessions sécurisées
- ✅ Tokens de réinitialisation (expiration 1h)
- ✅ État OAuth (protection CSRF)

---

## 📁 Fichiers Créés

### Controllers
- ✅ **AuthController.php** - Authentification native (login, register, password reset)
- ✅ **SocialAuthController.php** - OAuth Google & Facebook
- ✅ **AccountController.php** - Gestion du compte utilisateur

### Models
- ✅ **UserModel.php** - Gestion des utilisateurs avec OAuth support

### Views
- ✅ **auth/login.php** - Page de connexion (native + OAuth)
- ✅ **auth/register.php** - Page d'inscription
- ✅ **auth/forgot_password.php** - Formulaire mot de passe oublié
- ✅ **auth/reset_password.php** - Formulaire de réinitialisation
- ✅ **account/index.php** - Tableau de bord utilisateur

### Filters
- ✅ **Auth.php** - Filtre d'authentification (protège les routes /account/*)
- ✅ **AdminAuth.php** - Filtre admin (déjà existant)

### Configuration
- ✅ **Routes.php** - Routes auth configurées
- ✅ **Filters.php** - Alias 'auth' ajouté
- ✅ **env.example** - Variables OAuth documentées

### Base de données
- ✅ **database_users.sql** - Tables users + password_resets

### Documentation
- ✅ **OAUTH_CONFIG.md** - Guide complet OAuth
- ✅ **AUTH_SYSTEM.md** - Ce fichier

---

## 🚀 Installation & Configuration

### 1. **Installation des dépendances OAuth**

```bash
cd /Users/mac/Documents/FunLab/funlab-booking
composer require league/oauth2-client
composer require league/oauth2-google
composer require league/oauth2-facebook
```

### 2. **Configuration de la base de données**

Exécutez le script SQL :

```bash
mysql -u root -p funlab_booking < database_users.sql
```

Cela créera :
- Table `users` avec support OAuth (auth_provider, provider_id)
- Table `password_resets` pour les tokens
- Un compte admin par défaut : `admin@funlab.tn` / `password`

### 3. **Configuration OAuth**

#### A) Google OAuth

1. Allez sur https://console.cloud.google.com/
2. Créez un projet "FunLab Booking"
3. Activez "Google+ API"
4. Créez des identifiants OAuth 2.0
5. Ajoutez les redirect URIs :
   - `http://localhost:8080/auth/google/callback`
   - `https://funlab.tn/auth/google/callback`

#### B) Facebook OAuth

1. Allez sur https://developers.facebook.com/
2. Créez une app "FunLab Booking"
3. Ajoutez le produit "Facebook Login"
4. Configurez les redirect URIs :
   - `http://localhost:8080/auth/facebook/callback`
   - `https://funlab.tn/auth/facebook/callback`

### 4. **Configuration .env**

Copiez `env.example` vers `env` :

```bash
cp env.example env
```

Modifiez ces variables dans le fichier `env` :

```env
# Google OAuth
GOOGLE_CLIENT_ID=your-client-id.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=your-client-secret

# Facebook OAuth
FACEBOOK_APP_ID=your-app-id
FACEBOOK_APP_SECRET=your-app-secret

# Email (pour password reset)
email.fromEmail = noreply@funlab.tn
email.fromName = FunLab Tunisie
email.SMTPHost = smtp.gmail.com
email.SMTPUser = your-email@gmail.com
email.SMTPPass = your-app-password
email.SMTPPort = 587
email.SMTPCrypto = tls
```

---

## 🔗 Routes Disponibles

### Authentification
```
GET  /auth/login              - Page de connexion
POST /auth/login              - Tentative de connexion
GET  /auth/register           - Page d'inscription
POST /auth/register           - Créer un compte
GET  /auth/logout             - Déconnexion

GET  /auth/forgot-password    - Formulaire mot de passe oublié
POST /auth/forgot-password    - Envoyer le lien de réinitialisation
GET  /auth/reset-password/{token} - Formulaire de réinitialisation
POST /auth/reset-password     - Réinitialiser le mot de passe
```

### OAuth
```
GET /auth/google              - Redirection vers Google
GET /auth/google/callback     - Callback Google
GET /auth/facebook            - Redirection vers Facebook
GET /auth/facebook/callback   - Callback Facebook
```

### Compte Utilisateur (Protégé - filtre 'auth')
```
GET  /account                 - Tableau de bord
GET  /account/profile         - Modifier le profil
POST /account/profile         - Enregistrer le profil
GET  /account/bookings        - Mes réservations
GET  /account/bookings/{id}   - Détails d'une réservation
GET  /account/password        - Changer le mot de passe
POST /account/password        - Enregistrer le nouveau mot de passe
```

---

## 🎨 Interfaces Disponibles

### 1. Page de Connexion (`/auth/login`)
- 🔵 Bouton "Continuer avec Google"
- 🔵 Bouton "Continuer avec Facebook"
- 📧 Formulaire email + mot de passe
- ☑️ Case "Se souvenir de moi"
- 🔗 Lien "Mot de passe oublié ?"
- 🔗 Lien "S'inscrire"

### 2. Page d'Inscription (`/auth/register`)
- 🔵 Bouton "S'inscrire avec Google"
- 🔵 Bouton "S'inscrire avec Facebook"
- 📝 Formulaire : Prénom, Nom, Email, Téléphone, Mot de passe
- ☑️ Case "J'accepte les conditions"
- 🔗 Lien "Se connecter"

### 3. Tableau de Bord (`/account`)
- 📊 Statistiques : Total réservations, À venir, Complétées
- 📋 Liste des réservations récentes
- 🔗 Liens : Profil, Réservations, Mot de passe, Déconnexion

---

## 🔐 Fonctionnement OAuth

### Flux Google :
1. Utilisateur clique "Continuer avec Google"
2. Redirection vers `/auth/google`
3. Redirection vers Google OAuth
4. Utilisateur autorise l'application
5. Callback vers `/auth/google/callback`
6. Création/liaison du compte dans la DB
7. Création de la session
8. Redirection vers `/account`

### Flux Facebook :
1. Utilisateur clique "Continuer avec Facebook"
2. Redirection vers `/auth/facebook`
3. Redirection vers Facebook OAuth
4. Utilisateur autorise l'application
5. Callback vers `/auth/facebook/callback`
6. Création/liaison du compte dans la DB
7. Création de la session
8. Redirection vers `/account`

### Logique de Liaison des Comptes

Le système utilise `UserModel::findOrCreateOAuthUser()` :

1. **Recherche par provider_id** : Si l'utilisateur s'est déjà connecté avec ce provider
2. **Recherche par email** : Si l'email existe → Liaison automatique du compte OAuth
3. **Création** : Si aucun compte trouvé → Nouveau compte avec `auth_provider` = 'google' ou 'facebook'

---

## 🛡️ Sécurité

### Mots de passe
- ✅ Hash bcrypt (PASSWORD_DEFAULT)
- ✅ Minimum 8 caractères
- ✅ Hash automatique via callbacks (beforeInsert, beforeUpdate)

### Sessions
- ✅ Durée : 2 heures
- ✅ Régénération après login
- ✅ Cookie httpOnly + secure (production)

### Remember Me
- ✅ Cookie séparé (30 jours)
- ✅ Vérification au chargement de page

### Password Reset
- ✅ Token aléatoire (64 caractères hex)
- ✅ Expiration : 1 heure
- ✅ Suppression après utilisation

### OAuth
- ✅ State token (protection CSRF)
- ✅ Validation du state dans le callback
- ✅ HTTPS en production (obligatoire)

---

## 📊 Structure de la Base de Données

### Table `users`
```sql
- id (PK)
- username
- email (UNIQUE)
- password (NULL pour OAuth)
- first_name
- last_name
- phone
- avatar (URL de la photo)
- role (ENUM: customer, staff, admin)
- auth_provider (ENUM: native, google, facebook)
- provider_id (ID chez Google/Facebook)
- created_at
- last_login
```

### Table `password_resets`
```sql
- id (PK)
- email
- token (64 caractères)
- expires_at (TIMESTAMP)
- created_at
```

---

## 🧪 Tests

### Test Connexion Native
1. Visitez `http://localhost:8080/auth/login`
2. Utilisez : `admin@funlab.tn` / `password`
3. ✅ Devrait rediriger vers `/admin/dashboard`

### Test Inscription
1. Visitez `http://localhost:8080/auth/register`
2. Remplissez le formulaire
3. ✅ Compte créé + auto-login + redirect vers `/account`

### Test Mot de passe oublié
1. Visitez `http://localhost:8080/auth/forgot-password`
2. Entrez votre email
3. ✅ Email envoyé avec lien de réinitialisation
4. Cliquez sur le lien (valide 1h)
5. Entrez le nouveau mot de passe
6. ✅ Mot de passe réinitialisé

### Test Google OAuth (après configuration)
1. Visitez `http://localhost:8080/auth/login`
2. Cliquez "Continuer avec Google"
3. Sélectionnez votre compte Google
4. Autorisez l'accès
5. ✅ Compte créé/lié + redirect vers `/account`

### Test Facebook OAuth (après configuration)
1. Visitez `http://localhost:8080/auth/login`
2. Cliquez "Continuer avec Facebook"
3. Connectez-vous à Facebook
4. Autorisez l'application
5. ✅ Compte créé/lié + redirect vers `/account`

---

## 🎯 Workflow Complet

### Nouveau Visiteur
1. Visite le site → Accueil
2. Clique "Réserver"
3. Sélectionne une activité
4. Redirigé vers `/auth/register`
5. S'inscrit (native ou OAuth)
6. Compte créé + auto-login
7. Retour sur la réservation
8. Réservation confirmée

### Utilisateur Existant
1. Visite le site → Accueil
2. Clique "Mon compte"
3. Redirigé vers `/auth/login`
4. Se connecte (native ou OAuth)
5. Accède à `/account`
6. Voit ses réservations, stats, profil

### Admin
1. Visite `/admin`
2. Filtre `adminAuth` vérifie le role
3. Si non connecté → redirect `/auth/login`
4. Connexion admin
5. Accès au dashboard admin

---

## 📝 TODO / Prochaines Étapes

### Phase 6 - Complétée ✅
- [x] AuthController (login, register, password reset)
- [x] SocialAuthController (Google, Facebook)
- [x] AccountController (dashboard, profile, bookings)
- [x] Views auth (login, register, forgot, reset)
- [x] View account (dashboard)
- [x] Filter Auth
- [x] Routes configuration
- [x] Documentation OAuth

### Phase 7 - Recommandations
- [ ] Ajouter 2FA (authentification à deux facteurs)
- [ ] Ajouter OAuth LinkedIn
- [ ] Ajouter OAuth Apple
- [ ] Email de bienvenue après inscription
- [ ] Email de confirmation de compte
- [ ] Historique des connexions
- [ ] Gestion des sessions actives
- [ ] Page de gestion des données personnelles (RGPD)
- [ ] Export des données utilisateur
- [ ] Suppression de compte

---

## 📚 Ressources

- [CodeIgniter 4 Authentication](https://codeigniter4.github.io/CodeIgniter4/)
- [League OAuth2 Client](https://oauth2-client.thephpleague.com/)
- [Google OAuth Documentation](https://developers.google.com/identity/protocols/oauth2)
- [Facebook Login Documentation](https://developers.facebook.com/docs/facebook-login)

---

## 🐛 Troubleshooting

### "Invalid client ID"
- ❌ Mauvais `GOOGLE_CLIENT_ID` dans .env
- ✅ Vérifiez les credentials dans Google Cloud Console

### "Redirect URI mismatch"
- ❌ URI non autorisé dans la console OAuth
- ✅ Ajoutez `http://localhost:8080/auth/google/callback` dans les URIs autorisés

### "Email already exists"
- ❌ Tentative d'inscription avec un email existant
- ✅ Utilisez la connexion ou un autre email

### "Invalid state"
- ❌ Token CSRF invalide
- ✅ Réessayez la connexion OAuth

### "Class Auth could not be found"
- ❌ Filtre non enregistré dans Filters.php
- ✅ Ajoutez `'auth' => \App\Filters\Auth::class,` dans `$aliases`

---

## 📞 Support

Pour toute question sur l'authentification :
- Email technique : dev@funlab.tn
- Documentation : `/OAUTH_CONFIG.md`
- Guide rapide : `/QUICK_START.md`

---

**Version**: 1.0.0  
**Date**: 2024  
**Auteur**: GitHub Copilot pour FunLab Tunisie 🇹🇳
