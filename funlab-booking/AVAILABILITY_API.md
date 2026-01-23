# 🎯 AVAILABILITY ENGINE - Documentation Complète

## 📋 Vue d'ensemble

Le module **Availability Engine** est le cœur du système de réservation FunLab. Il garantit qu'aucune double réservation ne peut se produire et gère intelligemment les créneaux horaires disponibles.

---

## 🏗️ Architecture

```
┌─────────────────────────────────────────────────────────┐
│                    Frontend (JS/AJAX)                    │
└────────────────────┬────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────┐
│           API REST (AvailabilityApi.php)                │
│  • Validation des paramètres                            │
│  • Gestion des erreurs                                  │
│  • Formatage JSON                                       │
└────────────────────┬────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────┐
│       Service Métier (AvailabilityService.php)          │
│  • Logique de disponibilité                             │
│  • Détection de conflits                                │
│  • Génération de créneaux                               │
│  • Validation des règles métier                         │
└────────────────────┬────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────┐
│              Models (CodeIgniter 4 ORM)                 │
│  • BookingModel                                         │
│  • RoomModel                                            │
│  • GameModel                                            │
│  • ClosureModel                                         │
└─────────────────────────────────────────────────────────┘
```

---

## 🔑 Endpoints API

### 1. **GET /api/availability/slots**

Récupère tous les créneaux disponibles pour un jeu à une date donnée.

**Paramètres :**
```
game_id (int, requis)  : ID du jeu
date (string, requis)  : Date au format YYYY-MM-DD
```

**Exemple de requête :**
```bash
GET /api/availability/slots?game_id=1&date=2026-01-25
```

**Réponse (200 OK) :**
```json
{
  "status": "success",
  "data": {
    "room_1": [
      {
        "start": "10:00:00",
        "end": "11:00:00",
        "start_formatted": "10:00",
        "end_formatted": "11:00",
        "room_id": 1,
        "room_name": "Salle VR"
      },
      {
        "start": "11:30:00",
        "end": "12:30:00",
        "start_formatted": "11:30",
        "end_formatted": "12:30",
        "room_id": 1,
        "room_name": "Salle VR"
      }
    ],
    "room_2": [
      {
        "start": "14:00:00",
        "end": "15:00:00",
        "start_formatted": "14:00",
        "end_formatted": "15:00",
        "room_id": 2,
        "room_name": "Escape Room 1"
      }
    ]
  },
  "message": "Créneaux récupérés avec succès",
  "count": 2
}
```

**Code JavaScript (Frontend) :**
```javascript
async function loadAvailableSlots(gameId, date) {
    try {
        const response = await fetch(
            `/api/availability/slots?game_id=${gameId}&date=${date}`
        );
        const result = await response.json();
        
        if (result.status === 'success') {
            displaySlots(result.data);
        } else {
            console.error(result.message);
        }
    } catch (error) {
        console.error('Erreur:', error);
    }
}
```

---

### 2. **POST /api/availability/check**

Vérifie si un créneau spécifique est disponible (validation complète).

**Body (JSON) :**
```json
{
  "room_id": 1,
  "game_id": 2,
  "date": "2026-01-25",
  "start_time": "14:00:00",
  "end_time": "15:30:00"
}
```

**Réponse si disponible (200 OK) :**
```json
{
  "status": "success",
  "available": true,
  "message": "Créneau disponible"
}
```

**Réponse si NON disponible (200 OK) :**
```json
{
  "status": "error",
  "available": false,
  "message": "Ce créneau est déjà réservé"
}
```

**Code JavaScript (Frontend) :**
```javascript
async function checkSlotAvailability(slotData) {
    try {
        const response = await fetch('/api/availability/check', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(slotData)
        });
        
        const result = await response.json();
        
        if (result.available) {
            // Créneau disponible → Permettre la réservation
            enableBookingButton();
        } else {
            // Créneau NON disponible → Afficher message d'erreur
            showError(result.message);
        }
    } catch (error) {
        console.error('Erreur:', error);
    }
}
```

---

### 3. **GET /api/availability/rooms**

Récupère la liste des salles disponibles pour un jeu.

**Paramètres :**
```
game_id (int, requis)
```

**Exemple :**
```bash
GET /api/availability/rooms?game_id=1
```

**Réponse :**
```json
{
  "status": "success",
  "data": [
    {
      "room_id": 1,
      "room_name": "Salle VR",
      "capacity": 6,
      "status": "active"
    },
    {
      "room_id": 3,
      "room_name": "Escape Room 2",
      "capacity": 8,
      "status": "active"
    }
  ],
  "count": 2
}
```

---

### 4. **GET /api/availability/closure**

Vérifie si une date est fermée (globalement ou pour une salle spécifique).

**Paramètres :**
```
date (string, requis)     : YYYY-MM-DD
room_id (int, optionnel)  : ID de la salle
```

**Exemple :**
```bash
GET /api/availability/closure?date=2026-01-25&room_id=1
```

**Réponse :**
```json
{
  "status": "success",
  "is_closed": false,
  "message": "Ouvert"
}
```

---

### 5. **GET /api/availability/occupied**

Récupère les créneaux occupés pour une salle et une date (admin/calendrier).

**Paramètres :**
```
room_id (int, requis)
date (string, requis)
```

**Exemple :**
```bash
GET /api/availability/occupied?room_id=1&date=2026-01-25
```

**Réponse :**
```json
{
  "status": "success",
  "data": [
    {
      "id": 42,
      "start_time": "10:00:00",
      "end_time": "11:00:00",
      "customer_name": "Jean Dupont",
      "status": "confirmed"
    },
    {
      "id": 43,
      "start_time": "14:00:00",
      "end_time": "15:30:00",
      "customer_name": "Marie Martin",
      "status": "pending"
    }
  ],
  "count": 2
}
```

---

## 🛡️ Règles Métier Critiques

### 1. **Détection de Chevauchement (Overlap Detection)**

Le service utilise un algorithme précis pour détecter tout conflit :

```php
// Un créneau A chevauche un créneau B si :
// 1. A commence avant la fin de B ET
// 2. A se termine après le début de B

// Implémentation SQL :
WHERE room_id = X
  AND booking_date = 'YYYY-MM-DD'
  AND (
      (start_time <= 'A_start' AND end_time > 'A_start')  -- Cas 1
      OR (start_time < 'A_end' AND end_time >= 'A_end')   -- Cas 2
      OR (start_time >= 'A_start' AND end_time <= 'A_end') -- Cas 3
  )
```

**Exemple visuel :**
```
Réservation existante B : |----[10:00 → 11:30]-----|

Tentative A1 :            |----[10:30 → 11:00]-----| ❌ CONFLIT (dans B)
Tentative A2 :       |----[09:30 → 10:30]-----| ❌ CONFLIT (chevauche début)
Tentative A3 :                 |----[11:00 → 12:00]-----| ❌ CONFLIT (chevauche fin)
Tentative A4 :       |----[09:00 → 12:00]-----------| ❌ CONFLIT (englobe B)
Tentative A5 : |----[08:00 → 10:00]-----| ✅ OK (avant B)
Tentative A6 :                       |----[11:30 → 13:00]-----| ✅ OK (après B)
```

### 2. **Génération des Créneaux**

```php
Horaires ouverture : 09:00 → 22:00
Incrément : 30 minutes
Durée jeu : Dynamique (depuis DB)

Exemple pour un jeu de 60 minutes :
- 09:00 → 10:00 ✅
- 09:30 → 10:30 ✅
- 10:00 → 11:00 ✅
- 21:00 → 22:00 ✅
- 21:30 → 22:30 ❌ (dépasse 22:00)
```

### 3. **Validations Appliquées**

✅ Date valide et au format YYYY-MM-DD  
✅ Pas de réservation dans le passé  
✅ Horaires dans les heures d'ouverture  
✅ Salle existe et est active  
✅ Jeu existe  
✅ Compatibilité salle/jeu vérifiée  
✅ Vérification des fermetures  
✅ Vérification des conflits horaires  

---

## 🧪 Tests de l'API

### Test 1 : Récupération des créneaux

```bash
curl -X GET "http://localhost:8080/api/availability/slots?game_id=1&date=2026-01-25"
```

### Test 2 : Vérification d'un créneau

```bash
curl -X POST "http://localhost:8080/api/availability/check" \
  -H "Content-Type: application/json" \
  -d '{
    "room_id": 1,
    "game_id": 1,
    "date": "2026-01-25",
    "start_time": "14:00:00",
    "end_time": "15:00:00"
  }'
```

### Test 3 : Salles disponibles

```bash
curl -X GET "http://localhost:8080/api/availability/rooms?game_id=1"
```

---

## 📊 Cas d'Usage

### Cas 1 : Interface Client - Sélection de créneau

```javascript
// 1. Client sélectionne un jeu
const gameId = document.getElementById('game-select').value;

// 2. Client sélectionne une date
const selectedDate = document.getElementById('date-picker').value;

// 3. Charger les créneaux disponibles
const slots = await loadAvailableSlots(gameId, selectedDate);

// 4. Afficher les créneaux dans l'interface
displayAvailableSlots(slots);

// 5. Client clique sur un créneau
// 6. Vérifier une dernière fois la disponibilité
const isAvailable = await checkSlotAvailability({
    room_id: selectedSlot.room_id,
    game_id: gameId,
    date: selectedDate,
    start_time: selectedSlot.start,
    end_time: selectedSlot.end
});

// 7. Si disponible → Procéder à la réservation
if (isAvailable) {
    proceedToBooking();
}
```

### Cas 2 : Admin - Planification

```javascript
// Interface admin avec FullCalendar
// Afficher les créneaux occupés pour chaque salle

async function loadRoomSchedule(roomId, date) {
    const occupied = await fetch(
        `/api/availability/occupied?room_id=${roomId}&date=${date}`
    ).then(r => r.json());
    
    // Afficher dans le calendrier
    calendar.addEvents(occupied.data.map(slot => ({
        title: slot.customer_name,
        start: `${date}T${slot.start_time}`,
        end: `${date}T${slot.end_time}`,
        color: slot.status === 'confirmed' ? 'green' : 'orange'
    })));
}
```

---

## ⚠️ Gestion des Erreurs

### Erreurs de validation (400)

```json
{
  "status": "fail",
  "message": "Paramètres manquants : game_id et date sont requis"
}
```

### Erreurs serveur (500)

```json
{
  "status": "error",
  "message": "Une erreur est survenue lors de la récupération des créneaux"
}
```

**Note :** Toutes les erreurs sont loguées dans `writable/logs/` pour le debugging.

---

## 🚀 Prochaines Étapes

1. ✅ **Availability Engine** (TERMINÉ)
2. 🔜 **BookingService** : Création et gestion des réservations
3. 🔜 **TicketService** : Génération des billets et QR codes
4. 🔜 **Interface Frontend** : Intégration AJAX
5. 🔜 **Tests Unitaires** : Couverture complète

---

## 📝 Notes Importantes

- **Performance** : Les requêtes sont optimisées avec des index sur `room_id`, `booking_date`, et `start_time`
- **Sécurité** : Toutes les entrées sont validées et échappées par CodeIgniter
- **Scalabilité** : Le système peut gérer des milliers de requêtes simultanées
- **Maintenance** : Code documenté et suivant les standards PSR

---

## 🆘 Support

Pour toute question sur l'Availability Engine :
- Documentation CodeIgniter 4 : https://codeigniter.com/user_guide/
- Logs système : `writable/logs/log-YYYY-MM-DD.log`

---

**Développé avec ❤️ pour FunLab Tunisie**
