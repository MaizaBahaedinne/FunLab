<h1><i class="bi bi-wrench text-primary"></i> Dépannage</h1>

## 🔍 Problèmes courants

### 🚫 Erreur 404 - Page Not Found

#### Symptôme
Page blanche avec "404 - File Not Found"

#### Causes possibles
1. **Problème .htaccess**
2. **mod_rewrite désactivé**
3. **Route non définie**

#### Solutions
```bash
# 1. Vérifier que .htaccess existe dans /public/
ls -la public/.htaccess

# 2. Activer mod_rewrite (Apache)
sudo a2enmod rewrite
sudo systemctl restart apache2

# 3. Vérifier les routes dans app/Config/Routes.php
```

### 💥 Erreur 500 - Internal Server Error

#### Symptôme
Page d'erreur serveur générique

#### Diagnostic
```bash
# Activer le mode debug
# Dans .env
CI_ENVIRONMENT = development

# Consulter les logs
tail -50 writable/logs/log-$(date +%Y-%m-%d).php
tail -50 /var/log/apache2/error.log
```

#### Causes fréquentes
1. **Erreur de syntaxe PHP**
2. **Permissions fichiers**
3. **Memory limit dépassée**

```bash
# Vérifier les permissions
chmod -R 755 writable/
chmod 644 .env

# Augmenter memory limit dans php.ini
memory_limit = 256M
```

### 🗄️ Database Connection Failed

#### Symptôme
```
Unable to connect to the database.
```

#### Solutions
```php
// Vérifier dans .env
database.default.hostname = localhost  // ou 127.0.0.1
database.default.database = funl_FunLabBooking
database.default.username = votre_user
database.default.password = votre_password
database.default.port = 3306
```

```bash
# Tester la connexion MySQL
mysql -h localhost -u root -p funl_FunLabBooking

# Vérifier que MySQL est démarré
sudo systemctl status mysql

# Redémarrer si nécessaire
sudo systemctl restart mysql
```

### 📧 Emails non envoyés

#### Symptôme
Emails de confirmation/vérification non reçus

#### Diagnostic
```php
// Activer le debug SMTP
// Dans app/Config/Email.php
public string $SMTPDebug = '2';

// Tester manuellement
php spark email:test destinataire@example.com
```

#### Solutions
1. **Vérifier les identifiants SMTP**
```php
// app/Config/Email.php
public string $SMTPHost = 'mail.faltaagency.com';
public string $SMTPUser = 'noreply@funlab.com';
public string $SMTPPass = 'mot_de_passe_correct';
public int $SMTPPort = 587;
public string $SMTPCrypto = 'tls';
```

2. **Vérifier le dossier spam** du destinataire

3. **Tester la connexion SMTP**
```bash
telnet mail.faltaagency.com 587
```

4. **Configurer SPF/DKIM** dans votre DNS

### 💳 Paiements Stripe échouent

#### Symptôme
Redirection Stripe mais paiement non validé

#### Vérifier
1. **Clés API correctes**
```ini
# .env
stripe.publishableKey = pk_test_... (ou pk_live_...)
stripe.secretKey = sk_test_... (ou sk_live_...)
```

2. **Webhook configuré**
```
URL: https://funlab.com/api/payment/webhook
Événements: checkout.session.completed, payment_intent.succeeded
```

3. **Logs Stripe**
Consultez [dashboard.stripe.com](https://dashboard.stripe.com) → Logs

4. **SSL actif**
```bash
# Paiements HTTPS obligatoire
curl -I https://funlab.com | grep "200 OK"
```

### 🔐 Impossible de se connecter

#### Symptôme
"Email ou mot de passe incorrect" même avec bon mot de passe

#### Solutions
1. **Réinitialiser le mot de passe**
Via "Mot de passe oublié"

2. **Vérifier le compte en base**
```sql
SELECT id, email, role, isActive, isVerified 
FROM users 
WHERE email = 'user@example.com';

-- Activer manuellement si nécessaire
UPDATE users 
SET isActive = 1, isVerified = 1 
WHERE email = 'user@example.com';
```

3. **Vérifier les sessions**
```bash
# Nettoyer les sessions
rm -rf writable/session/*
```

### 📅 Disponibilités incorrectes

#### Symptôme
Créneaux affichés comme disponibles alors qu'ils ne le sont pas

#### Solutions
1. **Vérifier les horaires d'ouverture**
**Paramètres → Horaires**

2. **Vérifier les fermetures**
**Fermetures** → Liste active

3. **Vérifier la capacité des salles**
```sql
SELECT r.name, r.capacity, COUNT(b.id) as current_bookings
FROM rooms r
LEFT JOIN bookings b ON r.id = b.roomId AND b.bookingDate = CURDATE()
GROUP BY r.id;
```

4. **Effacer le cache**
```bash
php spark cache:clear
```

### 🎮 Images de jeux non affichées

#### Symptôme
Placeholder affiché au lieu des images

#### Solutions
1. **Vérifier les permissions**
```bash
chmod -R 755 public/uploads/
```

2. **Vérifier le chemin**
```php
// Dans la base de données
SELECT id, name, image FROM games WHERE image IS NOT NULL;

// L'image doit être un chemin relatif comme:
// uploads/games/image123.jpg
```

3. **Vérifier que le fichier existe**
```bash
ls -la public/uploads/games/
```

### ⚠️ Erreurs de permissions (Staff)

#### Symptôme
Staff voit "Vous n'avez pas la permission d'effectuer cette action"

#### Solutions
1. **Vérifier le rôle**
```sql
SELECT email, role FROM users WHERE email = 'staff@funlab.com';
```

2. **Vérifier les permissions configurées**
**Utilisateurs → Rôles & Permissions**

3. **Effacer le cache des permissions**
```sql
DELETE FROM settings WHERE settingKey = 'role_permissions';
-- Les permissions par défaut seront rechargées
```

## 🛠️ Outils de diagnostic

### Logs applicatifs
```bash
# Consulter les erreurs récentes
tail -100 writable/logs/log-$(date +%Y-%m-%d).php | grep ERROR

# Surveiller en temps réel
tail -f writable/logs/log-$(date +%Y-%m-%d).php
```

### Logs serveur
```bash
# Apache
tail -100 /var/log/apache2/error.log

# Nginx
tail -100 /var/log/nginx/error.log

# MySQL
tail -100 /var/log/mysql/error.log
```

### État des services
```bash
# Vérifier Apache
sudo systemctl status apache2

# Vérifier MySQL
sudo systemctl status mysql

# Vérifier l'espace disque
df -h

# Vérifier la RAM
free -h
```

### Tests de connectivité
```bash
# Tester MySQL
mysql -h localhost -u root -p -e "SELECT 1;"

# Tester SMTP
telnet mail.faltaagency.com 587

# Tester HTTPS
curl -I https://funlab.com
```

## 🔧 Réparations d'urgence

### Site inaccessible
```bash
# 1. Mode maintenance
echo "Site en maintenance" > public/.maintenance

# 2. Consulter les logs
tail -50 writable/logs/log-*.php

# 3. Restaurer depuis backup si nécessaire
mysql -u root -p funl_FunLabBooking < backup_latest.sql

# 4. Redémarrer les services
sudo systemctl restart apache2
sudo systemctl restart mysql

# 5. Désactiver maintenance
rm public/.maintenance
```

### Base de données corrompue
```bash
# Vérifier les tables
mysql -u root -p -e "CHECK TABLE bookings, games, users;" funl_FunLabBooking

# Réparer si nécessaire
mysql -u root -p -e "REPAIR TABLE bookings;" funl_FunLabBooking
```

### Espace disque plein
```bash
# Identifier les gros fichiers
du -sh /* | sort -hr | head -10

# Nettoyer les logs
find writable/logs/ -name "*.php" -mtime +7 -delete

# Nettoyer les sessions
find writable/session/ -name "ci_session*" -mtime +1 -delete

# Nettoyer le cache
rm -rf writable/cache/*
```

## 📞 Obtenir de l'aide

### Informations à fournir
Lors d'une demande d'aide, incluez :
- **Version** : CodeIgniter, PHP, MySQL
- **Message d'erreur** : Complet avec trace
- **Logs** : Dernières lignes pertinentes
- **Contexte** : Quand/comment l'erreur apparaît
- **Actions effectuées** : Ce que vous avez déjà essayé

### Commandes utiles
```bash
# Versions
php -v
mysql --version
apache2 -v

# Configuration PHP
php -i | grep "memory_limit"
php -i | grep "upload_max_filesize"

# CodeIgniter
php spark --version
```

### Ressources
- **Documentation CodeIgniter** : [codeigniter.com/user_guide](https://codeigniter.com/user_guide)
- **Forum CodeIgniter** : [forum.codeigniter.com](https://forum.codeigniter.com)
- **Stack Overflow** : Tag `codeigniter-4`
- **Stripe Support** : [support.stripe.com](https://support.stripe.com)

---

<div class="alert alert-info">
    💡 <strong>Conseil :</strong> Gardez toujours une sauvegarde récente avant toute opération de dépannage !
</div>
