# 🎫 BOOKING SERVICE - Documentation Complète

## 📋 Vue d'ensemble

Le **BookingService** gère la création, confirmation et annulation des réservations de manière sécurisée. Il s'appuie sur l'**AvailabilityService** pour garantir qu'aucune double réservation ne peut se produire.

---

## 🔄 Flux de Réservation

```
1. Client sélectionne un jeu et une date
2. Frontend récupère les créneaux disponibles (API Availability)
3. Client sélectionne un créneau
4. Client remplit le formulaire
5. Frontend envoie la réservation (API Booking/create)
6. Service vérifie la disponibilité une dernière fois
7. Transaction DB : Création réservation + Participants + QR Code
8. Email de confirmation envoyé
9. Client reçoit son billet avec QR Code
```

---

## 🔌 API Endpoints

### 1. POST /api/booking/create

Crée une nouvelle réservation complète.

**Body (JSON) :**
```json
{
  "room_id": 1,
  "game_id": 1,
  "booking_date": "2026-01-26",
  "start_time": "14:00:00",
  "end_time": "15:00:00",
  "customer_name": "Ahmed Ben Ali",
  "customer_email": "ahmed@example.com",
  "customer_phone": "+216 20 123 456",
  "num_players": 4,
  "participants": [
    {
      "name": "Ahmed Ben Ali",
      "email": "ahmed@example.com",
      "phone": "+216 20 123 456",
      "age": 30
    },
    {
      "name": "Sara Trabelsi",
      "age": 28
    },
    {
      "name": "Youssef Gharbi",
      "age": 25
    },
    {
      "name": "Leila Mansour",
      "age": 27
    }
  ],
  "notes": "Anniversaire d'Ahmed"
}
```

**Réponse Succès (201 Created) :**
```json
{
  "status": "success",
  "message": "Réservation créée avec succès",
  "booking_id": 42,
  "data": {
    "confirmation_code": "FL20260126A3F5B2",
    "total_price": 100.00,
    "booking_date": "2026-01-26",
    "start_time": "14:00:00",
    "end_time": "15:00:00",
    "room_name": "Salle VR 1",
    "game_name": "Beat Saber VR",
    "qr_code": "eyJib29raW5nX2lkIjo0MiwiY29uZmlybWF0aW9uX2NvZGUiOiJGTDIwMjYw..."
  }
}
```

**Réponse Erreur (400 Bad Request) :**
```json
{
  "status": "error",
  "message": "Ce créneau est déjà réservé"
}
```

---

### 2. POST /api/booking/cancel/{id}

Annule une réservation existante.

**Body (JSON) :**
```json
{
  "reason": "Changement de plans"
}
```

**Réponse Succès :**
```json
{
  "status": "success",
  "message": "Réservation annulée avec succès"
}
```

---

### 3. POST /api/booking/confirm/{id}

Confirme une réservation (après paiement).

**Réponse Succès :**
```json
{
  "status": "success",
  "message": "Réservation confirmée"
}
```

---

### 4. GET /api/booking/{id}

Récupère les détails complets d'une réservation.

**Réponse :**
```json
{
  "status": "success",
  "data": {
    "booking": {
      "id": 42,
      "confirmation_code": "FL20260126A3F5B2",
      "booking_date": "2026-01-26",
      "start_time": "14:00:00",
      "end_time": "15:00:00",
      "customer_name": "Ahmed Ben Ali",
      "customer_email": "ahmed@example.com",
      "status": "confirmed",
      "total_price": 100.00
    },
    "room": {
      "id": 1,
      "name": "Salle VR 1",
      "capacity": 6
    },
    "game": {
      "id": 1,
      "name": "Beat Saber VR",
      "duration_minutes": 30
    },
    "participants": [
      {
        "id": 1,
        "name": "Ahmed Ben Ali",
        "checked_in": 0
      }
    ]
  }
}
```

---

### 5. GET /api/booking/customer?email=ahmed@example.com

Récupère toutes les réservations d'un client.

**Réponse :**
```json
{
  "status": "success",
  "data": [
    {
      "id": 42,
      "confirmation_code": "FL20260126A3F5B2",
      "booking_date": "2026-01-26",
      "start_time": "14:00:00",
      "status": "confirmed"
    }
  ],
  "count": 1
}
```

---

## 💻 Exemples d'Intégration Frontend

### Créer une réservation

```javascript
async function createBooking(bookingData) {
    try {
        const response = await fetch('/api/booking/create', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(bookingData)
        });

        const result = await response.json();

        if (response.ok && result.status === 'success') {
            // Succès !
            console.log('Réservation créée:', result.booking_id);
            console.log('Code de confirmation:', result.data.confirmation_code);
            
            // Afficher le QR Code
            showQRCode(result.data.qr_code);
            
            // Rediriger vers la page de confirmation
            window.location.href = `/booking/confirm/${result.booking_id}`;
        } else {
            // Erreur
            alert(result.message);
        }
    } catch (error) {
        console.error('Erreur:', error);
        alert('Une erreur est survenue');
    }
}

// Exemple d'utilisation
const bookingData = {
    room_id: 1,
    game_id: 1,
    booking_date: '2026-01-26',
    start_time: '14:00:00',
    end_time: '15:00:00',
    customer_name: 'Ahmed Ben Ali',
    customer_email: 'ahmed@example.com',
    customer_phone: '+216 20 123 456',
    num_players: 4,
    participants: [
        { name: 'Ahmed Ben Ali', email: 'ahmed@example.com' },
        { name: 'Sara Trabelsi' },
        { name: 'Youssef Gharbi' },
        { name: 'Leila Mansour' }
    ],
    notes: 'Anniversaire'
};

createBooking(bookingData);
```

---

### Annuler une réservation

```javascript
async function cancelBooking(bookingId, reason) {
    const response = await fetch(`/api/booking/cancel/${bookingId}`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ reason })
    });

    const result = await response.json();

    if (result.status === 'success') {
        alert('Réservation annulée');
    } else {
        alert(result.message);
    }
}
```

---

### Afficher le QR Code

```javascript
function showQRCode(qrCodeData) {
    // Option 1 : Utiliser une API externe
    const qrImageUrl = `https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=${encodeURIComponent(qrCodeData)}`;
    
    document.getElementById('qr-image').src = qrImageUrl;
    
    // Option 2 : Utiliser une bibliothèque JS comme qrcode.js
    // new QRCode(document.getElementById("qrcode"), qrCodeData);
}
```

---

## 🔒 Sécurité

### Validation des Données

Le BookingService effectue les validations suivantes :

1. ✅ Tous les champs requis présents
2. ✅ Email valide
3. ✅ Nombre de joueurs dans les limites du jeu
4. ✅ Vérification de disponibilité en temps réel
5. ✅ Transaction DB atomique
6. ✅ QR Code sécurisé avec hash HMAC

### Protection contre les doubles réservations

```php
// Vérification AVANT insertion
$availabilityCheck = $this->availabilityService->checkSlotAvailability(...);

if (!$availabilityCheck['available']) {
    return ['success' => false, 'message' => 'Créneau plus disponible'];
}

// Transaction DB pour garantir l'atomicité
$db->transStart();
// ... insertion
$db->transComplete();
```

---

## 🎯 Statuts de Réservation

| Statut | Description | Actions possibles |
|--------|-------------|-------------------|
| `pending` | En attente de paiement | Confirmer, Annuler |
| `confirmed` | Confirmée et payée | Annuler, Compléter |
| `cancelled` | Annulée | Aucune |
| `completed` | Terminée | Aucune |

---

## 📧 Notifications Email

Le système envoie automatiquement des emails pour :

- ✅ Confirmation de réservation (avec QR Code)
- ✅ Annulation de réservation
- 🔜 Rappels 24h avant
- 🔜 Demandes d'avis après la session

---

## 🧪 Tests

### Test cURL : Créer une réservation

```bash
curl -X POST "http://localhost:8080/api/booking/create" \
  -H "Content-Type: application/json" \
  -d '{
    "room_id": 1,
    "game_id": 1,
    "booking_date": "2026-01-26",
    "start_time": "14:00:00",
    "end_time": "15:00:00",
    "customer_name": "Ahmed Ben Ali",
    "customer_email": "ahmed@example.com",
    "customer_phone": "+216 20 123 456",
    "num_players": 4
  }'
```

### Test cURL : Récupérer une réservation

```bash
curl "http://localhost:8080/api/booking/42"
```

### Test cURL : Annuler une réservation

```bash
curl -X POST "http://localhost:8080/api/booking/cancel/42" \
  -H "Content-Type: application/json" \
  -d '{"reason": "Test annulation"}'
```

---

## 📊 Calcul du Prix

Le prix total est calculé selon la formule :

```
Prix Total = Prix du Jeu × Nombre de Joueurs
```

Exemple :
- Jeu : Beat Saber VR = 25 DT
- Nombre de joueurs : 4
- **Total : 100 DT**

Vous pouvez personnaliser cette logique dans `calculateTotalPrice()` pour :
- Ajouter des réductions de groupe
- Appliquer des promotions
- Calculer la TVA
- Etc.

---

## 🚀 Prochaines Étapes

1. ✅ BookingService créé
2. ✅ API complète
3. 🔜 TicketService (génération PDF)
4. 🔜 Système de paiement
5. 🔜 Interface frontend complète

---

**Phase 2 : BookingService - TERMINÉE ✅**

Le système peut maintenant créer des réservations sécurisées avec QR Codes !
