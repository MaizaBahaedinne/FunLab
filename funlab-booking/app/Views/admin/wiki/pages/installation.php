<h1><i class="bi bi-download text-primary"></i> Installation & Configuration</h1>

## 🔧 Prérequis

Avant d'installer FunLab Booking, assurez-vous d'avoir :

<div class="row mt-3">
    <div class="col-md-6">
        <div class="card mb-3">
            <div class="card-body">
                <h5><i class="bi bi-server text-info"></i> Serveur Web</h5>
                <ul>
                    <li>Apache 2.4+ ou Nginx</li>
                    <li>PHP 8.1 ou supérieur</li>
                    <li>Intl Extension</li>
                    <li>MySQL ou MariaDB 10.3+</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card mb-3">
            <div class="card-body">
                <h5><i class="bi bi-code-square text-success"></i> Extensions PHP</h5>
                <ul>
                    <li>php-intl</li>
                    <li>php-mbstring</li>
                    <li>php-json</li>
                    <li>php-mysqlnd</li>
                    <li>php-curl</li>
                </ul>
            </div>
        </div>
    </div>
</div>

## 📦 Installation

### Étape 1 : Téléchargement
```bash
# Cloner le dépôt
git clone https://github.com/yourusername/funlab-booking.git
cd funlab-booking

# Installer les dépendances Composer
composer install
```

### Étape 2 : Configuration de la base de données

1. Créez une base de données MySQL :
```sql
CREATE DATABASE funl_FunLabBooking CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

2. Importez le schéma :
```bash
mysql -u root -p funl_FunLabBooking < database_schema.sql
```

3. Importez les données de base :
```bash
mysql -u root -p funl_FunLabBooking < database_settings.sql
mysql -u root -p funl_FunLabBooking < database_users.sql
```

### Étape 3 : Configuration environnement

Copiez le fichier d'exemple et configurez :
```bash
cp env.example .env
```

Éditez le fichier `.env` :

```ini
#--------------------------------------------------------------------
# ENVIRONMENT
#--------------------------------------------------------------------
CI_ENVIRONMENT = production

#--------------------------------------------------------------------
# APP
#--------------------------------------------------------------------
app.baseURL = 'https://votre-domaine.com/'
app.appTimezone = 'Africa/Tunis'

#--------------------------------------------------------------------
# DATABASE
#--------------------------------------------------------------------
database.default.hostname = localhost
database.default.database = funl_FunLabBooking
database.default.username = votre_utilisateur
database.default.password = votre_mot_de_passe
database.default.DBDriver = MySQLi
database.default.DBPrefix = 
database.default.port = 3306
```

### Étape 4 : Permissions fichiers
```bash
# Donner les permissions d'écriture
chmod -R 777 writable/
chmod 644 .env
```

### Étape 5 : Créer le compte administrateur

Exécutez le script SQL :
```bash
mysql -u root -p funl_FunLabBooking < database_users.sql
```

Ou créez manuellement :
```sql
INSERT INTO users (email, password, firstName, lastName, role, isActive, isVerified)
VALUES (
    'admin@funlab.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password: Admin2026!
    'Admin',
    'FunLab',
    'admin',
    1,
    1
);
```

## 🔐 Configuration des services

### Email (PHPMailer)

Éditez `/app/Config/Email.php` :
```php
public string $SMTPHost = 'mail.votre-domaine.com';
public string $SMTPUser = 'noreply@votre-domaine.com';
public string $SMTPPass = 'votre_mot_de_passe';
public int $SMTPPort = 587;
public string $SMTPCrypto = 'tls';
```

### Paiement Stripe

Copiez et configurez :
```bash
cp env_payment.example .env.payment
```

Ajoutez vos clés Stripe dans `.env` :
```ini
# Stripe Keys
stripe.publishableKey = 'pk_test_xxxxxxxxxxxxx'
stripe.secretKey = 'sk_test_xxxxxxxxxxxxx'
stripe.webhookSecret = 'whsec_xxxxxxxxxxxxx'
```

### OAuth Social (Optionnel)

Configuration dans l'interface admin : `/admin/settings/oauth`

Ou via `.env` :
```ini
# Google OAuth
oauth.google.clientId = 'votre-client-id.apps.googleusercontent.com'
oauth.google.clientSecret = 'votre-client-secret'

# Facebook OAuth
oauth.facebook.appId = 'votre-app-id'
oauth.facebook.appSecret = 'votre-app-secret'
```

## 🚀 Déploiement

### Option 1 : Apache (.htaccess inclus)
Le fichier `.htaccess` est déjà configuré dans `/public/`.

### Option 2 : Nginx
Configuration exemple :
```nginx
server {
    listen 80;
    server_name votre-domaine.com;
    root /var/www/funlab-booking/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

### Option 3 : CyberPanel (déjà configuré)
Le site utilise actuellement CyberPanel avec OpenLiteSpeed.

## ✅ Vérification

Accédez à votre site :
- **Frontend** : `https://votre-domaine.com/`
- **Admin** : `https://votre-domaine.com/admin`
- **Login** : `admin@funlab.com` / `Admin2026!`

## 🔍 Dépannage Installation

### Erreur "Database connection failed"
- Vérifiez les identifiants dans `.env`
- Assurez-vous que MySQL est démarré
- Vérifiez les permissions de l'utilisateur MySQL

### Erreur 500 lors de l'accès
- Vérifiez les permissions du dossier `writable/`
- Consultez les logs : `writable/logs/log-*.php`

### Page blanche
- Activez le mode debug dans `.env` :
```ini
CI_ENVIRONMENT = development
```
- Consultez les erreurs PHP

---

<div class="alert alert-success">
    ✅ Installation réussie ! Vous pouvez maintenant accéder au panneau d'administration et configurer votre système.
</div>
