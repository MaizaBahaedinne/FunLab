<h1><i class="bi bi-envelope text-primary"></i> Configuration Email</h1>

## 📧 Système d'emails

FunLab utilise **PHPMailer** avec serveur SMTP pour envoyer :
- Confirmations de réservation
- Codes de vérification
- Rappels 24h avant
- Notifications d'annulation
- Réinitialisation mot de passe

## ⚙️ Configuration SMTP

### Fichier de configuration
**Paramètres → Communications → Email** ou `/app/Config/Email.php` :

```php
public string $SMTPHost = 'mail.faltaagency.com';
public string $SMTPUser = 'noreply@funlab.com';
public string $SMTPPass = 'votre_mot_de_passe';
public int $SMTPPort = 587;
public string $SMTPCrypto = 'tls'; // ou 'ssl'
```

### Ports SMTP
- **Port 25** : Non sécurisé (déconseillé)
- **Port 587** : TLS/STARTTLS (recommandé) ✅
- **Port 465** : SSL/TLS

### Fournisseurs SMTP populaires

#### Gmail
```php
$SMTPHost = 'smtp.gmail.com';
$SMTPPort = 587;
$SMTPCrypto = 'tls';
$SMTPUser = 'votre-email@gmail.com';
$SMTPPass = 'mot-de-passe-application';
```
⚠️ Activez "Applications moins sécurisées" ou utilisez un mot de passe d'application

#### Office 365 / Outlook
```php
$SMTPHost = 'smtp.office365.com';
$SMTPPort = 587;
$SMTPCrypto = 'tls';
```

#### SendGrid
```php
$SMTPHost = 'smtp.sendgrid.net';
$SMTPPort = 587;
$SMTPUser = 'apikey';
$SMTPPass = 'votre-clé-api-sendgrid';
```

#### Serveur dédié (recommandé)
```php
$SMTPHost = 'mail.votre-domaine.com';
$SMTPPort = 587;
$SMTPUser = 'noreply@votre-domaine.com';
$SMTPPass = 'mot-de-passe-fort';
```

## 📨 Types d'emails

### 1. Confirmation de réservation
**Déclenché** : Après paiement réussi

**Contenu** :
- Référence réservation
- QR Code
- Détails du jeu (nom, date, heure, durée)
- Nombre de joueurs
- Prix total
- Adresse FunLab avec plan
- Lien d'auto-inscription participants
- Conditions d'annulation

**Template** : `/app/Views/emails/booking_confirmation.php`

### 2. Code de vérification
**Déclenché** : Après inscription ou demande de vérification

**Contenu** :
- Code à 6 chiffres
- Validité : 15 minutes
- Lien direct de vérification

**Template** : `/app/Views/emails/verification_code.php`

### 3. Rappel 24h
**Déclenché** : 24h avant la session (cron job)

**Contenu** :
- Rappel de la réservation demain
- Heure et lieu
- QR Code
- Recommandations (arriver 10 min avant)

**Template** : `/app/Views/emails/booking_reminder.php`

### 4. Annulation
**Déclenché** : Annulation par client ou admin

**Contenu** :
- Confirmation d'annulation
- Montant remboursé (si applicable)
- Délai de remboursement
- Lien pour re-réserver

**Template** : `/app/Views/emails/booking_cancellation.php`

### 5. Réinitialisation mot de passe
**Déclenché** : "Mot de passe oublié"

**Contenu** :
- Lien de réinitialisation
- Validité : 1 heure
- Avertissement sécurité

**Template** : `/app/Views/emails/password_reset.php`

## 🎨 Personnalisation des templates

### Structure d'un template
```php
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; }
        .header { background: #667eea; color: white; }
        .button { background: #667eea; color: white; }
    </style>
</head>
<body>
    <div class="header">
        <h1><?= $siteName ?></h1>
    </div>
    <div class="content">
        <p>Bonjour <?= $customerName ?>,</p>
        <p><?= $message ?></p>
    </div>
</body>
</html>
```

### Variables disponibles
Templates ont accès à :
- `$siteName` : Nom du site
- `$customerName` : Nom du client
- `$booking` : Objet réservation
- `$game` : Objet jeu
- `$qrCode` : Image QR code base64

## 📊 Logs et suivi

### Activer les logs
Dans `/app/Config/Email.php` :
```php
public string $SMTPDebug = '2'; // 0=off, 1=client, 2=server
```

### Consulter les logs
Fichier : `/writable/logs/email-*.log`

Contenu :
```
[2026-01-31 14:30:00] Email envoyé à ahmed@example.com
Sujet: Confirmation de réservation #FL20260215-123
Statut: Succès
```

## 🚨 Dépannage

### Emails non reçus

#### 1. Vérifier les SPAM
Demandez au client de vérifier :
- Dossier Spam/Indésirables
- Quarantaine antivirus

#### 2. SPF Record
Configurez dans votre DNS :
```
v=spf1 include:_spf.faltaagency.com ~all
```

#### 3. DKIM
Ajoutez l'enregistrement DKIM fourni par votre hébergeur

#### 4. DMARC
```
v=DMARC1; p=none; rua=mailto:admin@funlab.com
```

### Erreurs courantes

#### Erreur 535 : Authentication failed
- Vérifiez username/password
- Vérifiez que SMTP auth est activé

#### Erreur 550 : Relay access denied
- Vérifiez que l'email expéditeur correspond au compte SMTP

#### Connection timeout
- Vérifiez le port (587 vs 465)
- Vérifiez firewall serveur
- Testez avec telnet :
```bash
telnet mail.faltaagency.com 587
```

## 📧 Bonnes pratiques

### Expéditeur
```php
$fromEmail = 'noreply@funlab.com'; // ✅
$fromName = 'FunLab Booking';

// Évitez
$fromEmail = 'admin@gmail.com'; // ❌ Risque spam
```

### Contenu
- ✅ Texte clair et concis
- ✅ Appels à l'action visibles
- ✅ Responsive (mobile-friendly)
- ❌ Trop d'images (risque spam)
- ❌ Mots comme "GRATUIT", "URGENT"

### Fréquence
- Confirmation : Immédiate
- Rappel : 24h avant (pas plus)
- Marketing : 1 fois / semaine max

## 🔧 Tests

### Test SMTP
Créez `/app/Controllers/TestController.php` :
```php
public function testEmail()
{
    $email = \Config\Services::email();
    
    $email->setTo('votre-email@example.com');
    $email->setSubject('Test Email FunLab');
    $email->setMessage('Si vous recevez ceci, SMTP fonctionne !');
    
    if ($email->send()) {
        return 'Email envoyé avec succès !';
    } else {
        return $email->printDebugger(['headers']);
    }
}
```

---

<div class="alert alert-info">
    💡 <strong>Astuce :</strong> Utilisez un service SMTP dédié (SendGrid, Mailgun) pour garantir une délivrabilité optimale.
</div>
