<h1><i class="bi bi-shield-lock text-danger"></i> Sécurité</h1>

## 🛡️ Vue d'ensemble

FunLab Booking implémente plusieurs couches de sécurité pour protéger les données et prévenir les attaques.

## 🔐 Authentification

### Hachage des mots de passe
```php
// Utilisation de password_hash() avec bcrypt
$hashedPassword = password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);
```

**Caractéristiques** :
- Algorithme : bcrypt
- Cost : 10 (équilibre sécurité/performance)
- Salt : Généré automatiquement
- ❌ Jamais de mots de passe en clair en base

### Politique de mot de passe
Exigences minimales :
- **Longueur** : 8 caractères minimum
- **Complexité** : 1 majuscule, 1 minuscule, 1 chiffre
- **Interdit** : Mots de passe communs (123456, password)

### Vérification email
- Code à 6 chiffres aléatoire
- Validité : 15 minutes
- Stockage temporaire seulement
- Expiration automatique

### Réinitialisation mot de passe
- Token unique et aléatoire (64 caractères)
- Validité : 1 heure
- Usage unique (invalidé après utilisation)
- Lien envoyé par email sécurisé

## 🔒 Autorisation et Permissions

### Système de rôles
3 niveaux hiérarchiques :
1. **Admin** : Accès complet
2. **Staff** : Accès opérationnel limité
3. **User** : Accès client uniquement

### Vérifications multiples
Chaque action protégée passe par :
1. **Authentification** : L'utilisateur est-il connecté ?
2. **Autorisation de rôle** : A-t-il le bon rôle ?
3. **Permission spécifique** : A-t-il la permission pour cette action ?
4. **Validation de la ressource** : Peut-il accéder à cette ressource précise ?

### Protections spéciales
```php
// Staff ne peut JAMAIS modifier un admin
if ($targetUser['role'] === 'admin' && $currentUser['role'] !== 'admin') {
    throw new Exception('Vous ne pouvez pas modifier un administrateur');
}

// Impossible de supprimer le dernier admin
$adminCount = $userModel->where('role', 'admin')->countAllResults();
if ($adminCount <= 1 && $targetUser['role'] === 'admin') {
    throw new Exception('Impossible de supprimer le dernier administrateur');
}
```

## 🛡️ Protection contre les attaques

### SQL Injection
**Protection** : Query Builder de CodeIgniter avec paramètres liés

✅ **Sécurisé** :
```php
$builder->where('email', $email);
$builder->where('id', $id);
```

❌ **Dangereux** (évité) :
```php
$query = "SELECT * FROM users WHERE email = '$email'";
```

### XSS (Cross-Site Scripting)
**Protection** : Échappement automatique dans les vues

✅ **Sécurisé** :
```php
<?= esc($userInput) ?> // HTML entities
<?= esc($userInput, 'js') ?> // JavaScript
<?= esc($userInput, 'url') ?> // URL
```

### CSRF (Cross-Site Request Forgery)
**Protection** : Token CSRF automatique

Formulaires :
```php
<?= csrf_field() ?> // Génère input hidden avec token
```

Vérification automatique par CodeIgniter.

### Brute Force
**Protection** : Rate limiting

- **Tentatives max** : 5 par 15 minutes
- **Blocage temporaire** : 30 minutes après 5 échecs
- **Captcha** : Après 3 tentatives (à implémenter)

### Session Hijacking
**Protections** :
- Session ID régénéré après connexion
- Cookie sécurisé (HttpOnly, SameSite)
- Timeout : 2 heures d'inactivité
- IP binding (optionnel)

### Injection de fichiers
**Protections upload** :
- Vérification extension (whitelist)
- Vérification MIME type
- Taille max : 2 MB
- Renommage aléatoire
- Stockage hors webroot (recommandé)

```php
$allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
$allowedMimes = ['image/jpeg', 'image/png', 'image/gif'];
```

## 🔑 Gestion des secrets

### Variables d'environnement
**Fichier** : `.env` (JAMAIS commité sur Git)

```ini
# Sensibles
database.default.password = MotDePasseSecret
stripe.secretKey = sk_live_xxxxx
smtp.password = MotDePasse

# .gitignore inclut .env
```

### Clés API
- **Stripe** : Mode test/production séparé
- **OAuth** : Redirect URI whitelist
- **Webhooks** : Signature vérifiée

## 🌐 Sécurité réseau

### HTTPS/SSL
**Obligatoire** pour :
- Authentification
- Paiements
- Toutes les pages (recommandé)

Vérification :
```php
if (!is_https()) {
    return redirect()->to('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
}
```

### Headers de sécurité
Configuration recommandée :
```apache
# .htaccess
Header set X-Content-Type-Options "nosniff"
Header set X-Frame-Options "SAMEORIGIN"
Header set X-XSS-Protection "1; mode=block"
Header set Referrer-Policy "strict-origin-when-cross-origin"
Header set Content-Security-Policy "default-src 'self'"
```

### CORS
Configuration dans `/app/Config/Cors.php` :
```php
'allowedOrigins' => ['https://votre-domaine.com'],
'allowedMethods' => ['GET', 'POST', 'PUT', 'DELETE'],
'allowedHeaders' => ['Content-Type', 'Authorization'],
```

## 📊 Monitoring et Logs

### Logs de sécurité
Événements enregistrés :
- Tentatives de connexion échouées
- Modifications de permissions
- Suppressions d'utilisateurs
- Accès refusés
- Erreurs d'authentification

Fichier : `/writable/logs/log-*.php`

### Activité admin
Table `activity_logs` enregistre :
- Qui a fait quoi et quand
- IP et User-Agent
- Ressource cible (ID, type)

### Alertes
Configurez des alertes pour :
- Multiples échecs de connexion
- Modification de permissions
- Suppression d'admin
- Paiements suspects

## 🔍 Audit de sécurité

### Checklist régulière
- [ ] Mots de passe admins forts et uniques
- [ ] SSL/TLS actif et valide
- [ ] Sauvegardes quotidiennes fonctionnelles
- [ ] CodeIgniter à jour (dernière version)
- [ ] Dépendances Composer à jour
- [ ] Logs consultés régulièrement
- [ ] Permissions fichiers correctes (644 files, 755 dirs)
- [ ] `.env` non accessible via web
- [ ] phpMyAdmin protégé ou désactivé

### Scan de vulnérabilités
Outils recommandés :
- **PHP** : [Snyk](https://snyk.io/)
- **Composer** : `composer audit`
- **Serveur** : [Lynis](https://cisofy.com/lynis/)

## 🚨 En cas de compromission

### Actions immédiates
1. **Isoler** : Mettre le site en maintenance
2. **Analyser** : Consulter les logs
3. **Changer** : Tous les mots de passe et clés API
4. **Restaurer** : Depuis sauvegarde propre
5. **Patcher** : Corriger la faille
6. **Notifier** : Utilisateurs si données exposées (RGPD)

### Prévention
- Sauvegardes quotidiennes automatiques
- Mises à jour régulières
- Monitoring actif
- Plan de réponse aux incidents

## 📜 Conformité RGPD

### Données personnelles collectées
- Email, nom, téléphone
- Historique de réservations
- Adresse IP (logs)
- ❌ Pas de données bancaires (gérées par Stripe)

### Droits utilisateurs
- **Accès** : `/account/profile`
- **Modification** : Via l'interface compte
- **Suppression** : Demande à admin (avec anonymisation)
- **Portabilité** : Export JSON/CSV

### Durée de conservation
- Comptes actifs : Illimitée
- Comptes inactifs : 3 ans puis anonymisation
- Logs : 1 an

---

<div class="alert alert-danger">
    🔴 <strong>Critique :</strong> Ne JAMAIS commiter le fichier `.env` sur Git. Il contient des informations sensibles.
</div>
