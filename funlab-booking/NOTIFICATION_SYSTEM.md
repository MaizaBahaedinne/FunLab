# Système de Notifications - FunLab

## État Actuel

### Notifications dans le Menu Admin ✅
- **Réservations en attente** : Badge rouge sur "Réservations"
- **Avis non approuvés** : Badge jaune sur "Avis"
- Compteurs en temps réel chargés à chaque affichage du menu

## À Développer

### 1. Notifications Email pour le Staff

#### Déclencheurs
- **Nouvelle réservation** → Email au staff avec détails
- **Réservation annulée** → Notification d'annulation
- **Nouveau avis** → Email pour modération
- **Modification de réservation** → Notification du changement

#### Configuration Email
- Templates personnalisables dans `/admin/email-templates`
- Configuration SMTP dans `/admin/settings/mail`
- Liste des destinataires staff configurables

### 2. Centre de Notifications dans l'Admin

#### Interface
- Icône cloche dans la topbar avec compteur
- Dropdown avec liste des notifications récentes
- Page `/admin/notifications` avec historique complet
- Marquer comme lu/non lu
- Filtres par type et date

#### Base de Données
```sql
CREATE TABLE notifications (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    type VARCHAR(50),
    title VARCHAR(255),
    message TEXT,
    data JSON,
    read_at DATETIME NULL,
    created_at DATETIME,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
```

#### Types de Notifications
- `booking.new` : Nouvelle réservation
- `booking.cancelled` : Annulation
- `booking.modified` : Modification
- `review.new` : Nouvel avis
- `review.approved` : Avis approuvé
- `payment.received` : Paiement reçu
- `payment.failed` : Échec paiement
- `system.alert` : Alerte système

### 3. Notifications Temps Réel

#### Technologies
- **Option 1**: Server-Sent Events (SSE)
- **Option 2**: WebSockets avec Ratchet PHP
- **Option 3**: Polling AJAX toutes les 30 secondes

#### Implémentation Recommandée: SSE
```php
// app/Controllers/Admin/NotificationController.php
public function stream() {
    header('Content-Type: text/event-stream');
    header('Cache-Control: no-cache');
    
    while (true) {
        $notifications = $this->getNewNotifications();
        if (!empty($notifications)) {
            echo "data: " . json_encode($notifications) . "\n\n";
            ob_flush();
            flush();
        }
        sleep(5);
    }
}
```

### 4. Paramètres de Notification

#### Interface Admin (`/admin/settings/notifications`)
- Activer/désactiver les notifications par type
- Choisir les canaux (email, système, SMS)
- Configurer les destinataires par rôle
- Horaires de notification (ne pas déranger)

#### Configuration
```php
'notifications' => [
    'booking.new' => [
        'enabled' => true,
        'channels' => ['email', 'system'],
        'recipients' => ['admin', 'staff'],
    ],
    'review.new' => [
        'enabled' => true,
        'channels' => ['system'],
        'recipients' => ['admin'],
    ],
]
```

### 5. Notifications SMS (Optionnel)

#### Intégration SMS API
- Twilio
- Nexmo/Vonage
- SMS locale Tunisie

#### Cas d'usage
- Confirmation de réservation au client
- Rappel 24h avant la session
- Alerte d'urgence au staff

### 6. Notification Push (Futur)

#### Progressive Web App (PWA)
- Service Worker
- Push API
- Notifications navigateur même hors ligne

## Architecture du Système

### Service de Notification
```php
// app/Services/NotificationService.php
class NotificationService {
    public function send($users, $type, $data) {
        // Créer la notification en DB
        // Envoyer email si configuré
        // Envoyer SMS si configuré
        // Trigger temps réel si actif
    }
    
    public function sendToStaff($type, $data) {
        $staff = $this->getStaffUsers();
        $this->send($staff, $type, $data);
    }
}
```

### Utilisation
```php
// Exemple: Nouvelle réservation
$notificationService = new NotificationService();
$notificationService->sendToStaff('booking.new', [
    'booking_id' => $booking['id'],
    'customer' => $booking['customer_name'],
    'game' => $booking['game_name'],
    'date' => $booking['booking_date'],
]);
```

## Priorités de Développement

1. **Phase 1** (Urgent) ✅
   - Badges dans le menu admin (FAIT)

2. **Phase 2** (Court terme)
   - Emails automatiques pour réservations
   - Centre de notifications basique
   - Marquer comme lu

3. **Phase 3** (Moyen terme)
   - Notifications temps réel (SSE)
   - Paramètres configurables
   - Historique complet

4. **Phase 4** (Long terme)
   - SMS
   - Push notifications
   - Analytics des notifications

## Fichiers à Créer/Modifier

### Nouveaux Fichiers
- `app/Services/NotificationService.php`
- `app/Models/NotificationModel.php`
- `app/Controllers/Admin/NotificationController.php`
- `app/Views/admin/notifications/index.php`
- `app/Views/admin/settings/notifications.php`
- `database_notifications.sql`

### Fichiers à Modifier
- `app/Controllers/Admin/BookingController.php` → Trigger notifications
- `app/Controllers/Admin/ReviewController.php` → Trigger notifications
- `app/Views/admin/layouts/topbar.php` → Ajouter icône cloche
- `app/Config/Routes.php` → Routes notifications

## Notes

- Les badges actuels sont chargés à chaque page load
- Pour optimiser, mettre en cache avec Redis (expiration 30s)
- Les emails doivent être en queue pour ne pas ralentir l'app
- Prévoir rate limiting pour éviter spam de notifications
