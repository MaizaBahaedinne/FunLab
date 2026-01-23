# 📧 TicketService - Génération & Emails

## 🎯 Vue d'ensemble

Le **TicketService** gère la génération de billets électroniques (HTML/PDF) et l'envoi d'emails de confirmation automatiques.

---

## 🔧 Fonctionnalités

### 1. Génération de Billets HTML

**Méthode :** `generateTicket($bookingId)`

Crée un billet HTML élégant avec :
- Code de confirmation en grand format
- Détails complets de la réservation
- QR code intégré (base64)
- Instructions pour le client
- Design responsive et imprimable

**Utilisation :**
```php
$ticketService = new TicketService();
$ticketHTML = $ticketService->generateTicket(123);
echo $ticketHTML; // Affichage dans le navigateur
```

### 2. Envoi d'Emails de Confirmation

**Méthode :** `sendTicketByEmail($bookingId, $email)`

Envoie automatiquement un email avec :
- ✅ Confirmation de réservation
- 📋 Tous les détails du booking
- 🎫 QR code du billet
- ⚠️ Instructions importantes
- 💌 Template professionnel HTML

**Utilisation :**
```php
$sent = $ticketService->sendTicketByEmail(123, 'client@example.com');

if ($sent) {
    echo "Email envoyé avec succès";
} else {
    echo "Échec d'envoi - Vérifier les logs";
}
```

### 3. Emails d'Annulation

**Méthode :** `sendCancellationEmail($bookingId, $reason)`

Notifie le client en cas d'annulation avec :
- Message clair d'annulation
- Raison de l'annulation (si fournie)
- Détails de la réservation annulée
- Contact pour questions

**Utilisation :**
```php
$ticketService->sendCancellationEmail(123, "Fermeture exceptionnelle");
```

---

## 📨 Configuration Email

### Prérequis CodeIgniter 4

Éditer `app/Config/Email.php` :

```php
public string $fromEmail = 'noreply@funlab.tn';
public string $fromName = 'FunLab Tunisie';

// Option 1: SMTP (Recommandé pour production)
public string $protocol = 'smtp';
public string $SMTPHost = 'smtp.gmail.com';
public string $SMTPUser = 'votre-email@gmail.com';
public string $SMTPPass = 'votre-mot-de-passe-app';
public string $SMTPPort = 587;
public string $SMTPCrypto = 'tls';

// Option 2: Mail PHP (Simple pour tests)
public string $protocol = 'mail';

// Option 3: Sendmail
public string $protocol = 'sendmail';
public string $mailPath = '/usr/sbin/sendmail';
```

### Test Email

```php
// Test rapide
$email = \Config\Services::email();
$email->setTo('test@example.com');
$email->setSubject('Test FunLab');
$email->setMessage('Email de test');
$email->send();

// Voir les erreurs
echo $email->printDebugger();
```

---

## 🎨 Templates HTML

### Template Billet

**Caractéristiques :**
- Design moderne avec gradient violet/rose
- Code de confirmation en gros caractères
- Tableau de détails avec emojis
- QR code centré avec bordure
- Section instructions avec checklist
- Footer avec coordonnées
- Responsive et imprimable

### Template Email

**Caractéristiques :**
- En-tête coloré FunLab
- Boîte de confirmation verte
- Tableau de détails structuré
- QR code intégré en base64
- Alerte jaune pour instructions
- Footer gris avec infos légales

---

## 🖼️ Génération QR Code

### API Externe (Par défaut)

Utilise **QR Server API** :
```php
$url = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . urlencode($qrData);
$imageData = file_get_contents($url);
$base64 = 'data:image/png;base64,' . base64_encode($imageData);
```

**Avantages :**
- ✅ Aucune dépendance PHP
- ✅ Images haute qualité
- ✅ Pas de stockage fichiers

**Alternatives :**
- Bibliothèque `endroid/qr-code` (Composer)
- Bibliothèque `phpqrcode` (Legacy)

---

## 📄 Génération PDF (À implémenter)

### Option 1 : Dompdf

```bash
composer require dompdf/dompdf
```

```php
public function generateTicketPDF($bookingId)
{
    $html = $this->generateTicket($bookingId);
    
    $dompdf = new \Dompdf\Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    
    // Téléchargement
    return $dompdf->stream("ticket-{$bookingId}.pdf");
    
    // Ou sauvegarde
    file_put_contents("tickets/ticket-{$bookingId}.pdf", $dompdf->output());
}
```

### Option 2 : TCPDF

```bash
composer require tecnickcom/tcpdf
```

### Option 3 : wkhtmltopdf (Ligne de commande)

```bash
apt-get install wkhtmltopdf
```

```php
$html = $this->generateTicket($bookingId);
file_put_contents('temp.html', $html);
exec('wkhtmltopdf temp.html ticket.pdf');
```

---

## 🔄 Intégration avec BookingService

### Envoi Automatique lors de Création

Modifier `BookingService::createBooking()` :

```php
// Après création réussie
if ($bookingId) {
    // Générer et envoyer le ticket
    $ticketService = new TicketService();
    $emailSent = $ticketService->sendTicketByEmail($bookingId, $bookingData['customer_email']);
    
    if ($emailSent) {
        log_message('info', "Email de confirmation envoyé pour booking $bookingId");
    } else {
        log_message('warning', "Échec envoi email pour booking $bookingId");
    }
}
```

### Envoi lors de Confirmation

Modifier `BookingService::confirmBooking()` :

```php
public function confirmBooking($bookingId)
{
    // ... mise à jour statut confirmed ...
    
    // Envoyer le ticket
    $ticketService = new TicketService();
    $booking = $this->bookingModel->find($bookingId);
    $ticketService->sendTicketByEmail($bookingId, $booking['customer_email']);
}
```

---

## 🧪 Tests

### Test Génération Billet

```php
// Dans un controller
public function testTicket()
{
    $ticketService = new TicketService();
    $html = $ticketService->generateTicket(1);
    
    return $this->response->setBody($html);
}
```

**URL :** `http://votresite.com/test/ticket`

### Test Envoi Email

```php
public function testEmail()
{
    $ticketService = new TicketService();
    $sent = $ticketService->sendTicketByEmail(1, 'votre-email@example.com');
    
    return $sent ? "Email envoyé ✓" : "Échec ✗";
}
```

---

## 📋 Checklist Déploiement

**Configuration Email :**
- [ ] Éditer `app/Config/Email.php`
- [ ] Tester SMTP ou mail()
- [ ] Vérifier from/reply-to valides
- [ ] Tester sur email réel

**Templates :**
- [ ] Personnaliser logo et couleurs
- [ ] Ajouter coordonnées réelles
- [ ] Tester rendu sur Gmail/Outlook
- [ ] Vérifier responsive mobile

**QR Codes :**
- [ ] Tester génération QR
- [ ] Vérifier lisibilité (scan smartphone)
- [ ] Alternative si API externe down

**Intégration :**
- [ ] Activer envoi auto dans BookingService
- [ ] Logger tous les envois/erreurs
- [ ] Gérer retry si échec SMTP
- [ ] Queue emails (optionnel pour gros volume)

---

## 🚨 Gestion Erreurs

### Logs
Tous les erreurs sont loggées :
```
ERROR: Échec envoi email pour réservation 123: SMTP connect() failed
ERROR: Erreur génération QR code: Connection timeout
```

### Retry Logique
```php
public function sendWithRetry($bookingId, $email, $maxAttempts = 3)
{
    for ($i = 0; $i < $maxAttempts; $i++) {
        if ($this->sendTicketByEmail($bookingId, $email)) {
            return true;
        }
        sleep(2); // Attendre 2 secondes entre tentatives
    }
    return false;
}
```

---

## 💡 Améliorations Futures

### Queue System
Pour gros volume, utiliser une queue :
```bash
composer require codeigniter4/queue
```

### Pièces Jointes PDF
Joindre le billet en PDF à l'email :
```php
$emailService->attach($pdfPath);
```

### Templates Dynamiques
Stocker les templates dans la BDD pour édition admin.

### Multi-Langue
Détecter la langue du client et adapter le template.

### Statistiques
Tracker taux d'ouverture emails (via pixels invisibles).

---

## ✅ Résumé

**TicketService complet avec :**
- ✅ Génération billets HTML professionnels
- ✅ Envoi emails automatiques
- ✅ QR codes intégrés base64
- ✅ Templates responsive et imprimables
- ✅ Emails d'annulation
- ✅ Logs et gestion erreurs
- 🔲 PDF (à implémenter selon besoin)

**Le système d'emailing est prêt pour la production !** 📧✨
