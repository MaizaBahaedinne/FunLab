# 📧 Configuration Email - Guide Complet

## 🎯 Configuration dans CodeIgniter 4

### Fichier : `app/Config/Email.php`

```php
<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Email extends BaseConfig
{
    /**
     * Adresse email de l'expéditeur
     */
    public string $fromEmail = 'noreply@funlab.tn';
    public string $fromName = 'FunLab Tunisie';

    /**
     * Adresse de réponse (reply-to)
     */
    public string $replyTo = 'contact@funlab.tn';
    public string $replyToName = 'Service Client FunLab';

    /**
     * ===========================================
     * OPTION 1 : SMTP (Recommandé pour Production)
     * ===========================================
     */
    
    // Gmail SMTP
    public string $protocol = 'smtp';
    public string $SMTPHost = 'smtp.gmail.com';
    public string $SMTPUser = 'votre-email@gmail.com';
    public string $SMTPPass = 'votre-mot-de-passe-application'; // Pas le mot de passe Gmail normal !
    public int $SMTPPort = 587;
    public string $SMTPCrypto = 'tls'; // ou 'ssl' pour port 465
    public int $SMTPTimeout = 5;
    public bool $SMTPKeepAlive = false;

    /**
     * ===========================================
     * OPTION 2 : Serveur SMTP Professionnel
     * ===========================================
     */
    /*
    public string $protocol = 'smtp';
    public string $SMTPHost = 'mail.votredomaine.com';
    public string $SMTPUser = 'noreply@funlab.tn';
    public string $SMTPPass = 'mot-de-passe-securise';
    public int $SMTPPort = 587;
    public string $SMTPCrypto = 'tls';
    */

    /**
     * ===========================================
     * OPTION 3 : SendGrid (Service Cloud)
     * ===========================================
     */
    /*
    public string $protocol = 'smtp';
    public string $SMTPHost = 'smtp.sendgrid.net';
    public string $SMTPUser = 'apikey';
    public string $SMTPPass = 'votre-api-key-sendgrid';
    public int $SMTPPort = 587;
    public string $SMTPCrypto = 'tls';
    */

    /**
     * ===========================================
     * OPTION 4 : Mail PHP (Pour Tests Locaux)
     * ===========================================
     */
    /*
    public string $protocol = 'mail'; // Utilise mail() de PHP
    */

    /**
     * Type de mail
     */
    public string $mailType = 'html'; // 'text' ou 'html'

    /**
     * Charset
     */
    public string $charset = 'UTF-8';

    /**
     * Priorité (1 = élevée, 5 = basse)
     */
    public int $priority = 3;

    /**
     * Mode debug
     */
    public bool $SMTPDebug = 0; // 0 = pas de debug, 1 = erreurs, 2 = tout
}
```

---

## 🔑 Configuration Gmail (Mot de passe d'application)

### Étapes :

1. **Activer la validation en 2 étapes** sur votre compte Gmail
2. Aller sur : https://myaccount.google.com/apppasswords
3. Sélectionner "Autre (nom personnalisé)"
4. Entrer "FunLab Booking"
5. Copier le mot de passe de 16 caractères généré
6. Utiliser ce mot de passe dans `SMTPPass`

### Exemple :
```php
public string $SMTPUser = 'funlab.tn@gmail.com';
public string $SMTPPass = 'abcd efgh ijkl mnop'; // Mot de passe d'application
```

---

## 🧪 Test de Configuration

### Test Simple dans Controller

```php
<?php

namespace App\Controllers;

class TestEmail extends BaseController
{
    public function send()
    {
        $email = \Config\Services::email();

        $email->setTo('destinataire@example.com');
        $email->setSubject('Test Email FunLab');
        $email->setMessage('<h1>Test réussi !</h1><p>La configuration email fonctionne.</p>');

        if ($email->send()) {
            return "Email envoyé avec succès !";
        } else {
            return $email->printDebugger();
        }
    }
}
```

**URL :** `http://votresite.com/test-email/send`

---

## 🚀 Services Email Cloud (Recommandés)

### 1. SendGrid
- **Gratuit :** 100 emails/jour
- **Configuration :** Simple via SMTP
- **Bonus :** Analytics, templates
- **Site :** https://sendgrid.com

```php
public string $SMTPHost = 'smtp.sendgrid.net';
public string $SMTPUser = 'apikey';
public string $SMTPPass = 'SG.xxxxxxxxxxxxxxxxxxxxxxxx';
public int $SMTPPort = 587;
```

### 2. Mailgun
- **Gratuit :** 5000 emails/mois (3 premiers mois)
- **Configuration :** SMTP ou API
- **Site :** https://www.mailgun.com

### 3. Amazon SES
- **Prix :** $0.10 / 1000 emails
- **Fiable :** Infrastructure AWS
- **Site :** https://aws.amazon.com/ses/

### 4. Postmark
- **Gratuit :** 100 emails/mois
- **Rapidité :** Excellente délivrabilité
- **Site :** https://postmarkapp.com

---

## ⚠️ Problèmes Courants

### Erreur : "SMTP connect() failed"

**Solutions :**
```php
// 1. Vérifier port et crypto
public int $SMTPPort = 587;
public string $SMTPCrypto = 'tls'; // Essayer 'ssl' si échec

// 2. Augmenter timeout
public int $SMTPTimeout = 10;

// 3. Activer debug
public bool $SMTPDebug = 2; // Voir les détails
```

### Erreur : "Authentication failed"

**Causes :**
- Mot de passe incorrect
- Gmail : Besoin mot de passe d'application
- Compte bloqué par le provider

### Emails en SPAM

**Solutions :**
- Utiliser un vrai domaine (pas Gmail)
- Configurer SPF/DKIM/DMARC
- Éviter mots-clés spam
- Utiliser service professionnel (SendGrid)

---

## 📝 Template Email Variables

Dans `TicketService.php`, vous pouvez personnaliser :

```php
// Changer les couleurs
background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);

// Modifier le footer
<p>FunLab Tunisie - Centre de Loisirs Interactifs</p>
<p>Adresse : [VOTRE ADRESSE RÉELLE]</p>
<p>Tél : +216 XX XXX XXX</p>
<p>Email : contact@funlab.tn</p>

// Ajouter logo
<img src="https://votresite.com/logo.png" alt="FunLab" width="150">
```

---

## 🔒 Sécurité

### Variables d'Environnement (.env)

**Ne jamais commit les credentials SMTP !**

```env
# .env
email.SMTPHost = smtp.gmail.com
email.SMTPUser = votre-email@gmail.com
email.SMTPPass = votre-mot-de-passe-app
email.SMTPPort = 587
```

**Dans Email.php :**
```php
public string $SMTPHost = env('email.SMTPHost', 'smtp.gmail.com');
public string $SMTPUser = env('email.SMTPUser');
public string $SMTPPass = env('email.SMTPPass');
```

---

## 📊 Monitoring

### Logs à Surveiller

```bash
# Succès
grep "Email de confirmation envoyé" writable/logs/*.log

# Échecs
grep "Échec envoi email" writable/logs/*.log | tail -20
```

### Métriques à Suivre
- Taux d'envoi réussi
- Temps moyen d'envoi
- Emails en attente (si queue)

---

## ✅ Checklist Déploiement

- [ ] Configurer SMTP dans `app/Config/Email.php`
- [ ] Tester envoi avec `/test-email/send`
- [ ] Vérifier réception (inbox + spam)
- [ ] Personnaliser templates (logo, adresse)
- [ ] Configurer SPF/DKIM si domaine propre
- [ ] Activer logs email
- [ ] Tester email annulation
- [ ] Configurer limites d'envoi (rate limiting)
- [ ] Backup configuration
- [ ] Documentation équipe

---

## 🎉 Email Prêt !

Votre système d'emailing est maintenant configuré et prêt à envoyer :
- ✅ Emails de confirmation avec QR code
- ✅ Emails d'annulation
- ✅ Templates HTML professionnels
- ✅ Gestion erreurs + logs

**Bon envoi ! 📧✨**
