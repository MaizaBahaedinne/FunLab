# 📱 Scanner QR Code - API Documentation

## 🎯 Vue d'ensemble

L'API Scanner permet de valider les billets électroniques (QR codes), gérer le check-in des participants et suivre les statistiques en temps réel.

---

## 🔐 Endpoints API

### 1. Valider un QR Code

**POST** `/api/scan/validate`

Valide un QR code et récupère les informations de la réservation.

#### Request Body
```json
{
    "booking_id": 123,
    "confirmation_code": "FL202601231234",
    "customer_name": "Ahmed Ben Ali",
    "hash": "abc123def456..."
}
```

#### Response Success (200)
```json
{
    "status": "success",
    "message": "QR code valide",
    "data": {
        "valid": true,
        "access_granted": true,
        "access_message": "Bienvenue ! Accès accordé",
        "booking": {
            "id": 123,
            "confirmation_code": "FL202601231234",
            "status": "confirmed",
            "game_name": "Beat Saber VR",
            "room_name": "Salle VR 1",
            "booking_date": "2026-01-23",
            "start_time": "14:00",
            "end_time": "15:00",
            "customer_name": "Ahmed Ben Ali",
            "num_players": 2,
            "total_price": 50
        },
        "participants": {
            "total": 2,
            "checked_in": 0,
            "remaining": 2,
            "list": [
                {
                    "id": 1,
                    "name": "Ahmed",
                    "checked_in": false,
                    "check_in_time": null
                },
                {
                    "id": 2,
                    "name": "Sara",
                    "checked_in": false,
                    "check_in_time": null
                }
            ]
        }
    }
}
```

#### Response Error (400)
```json
{
    "status": "error",
    "message": "QR code invalide ou corrompu",
    "data": null
}
```

#### Logique de Validation

**Accès accordé si :**
- ✅ Statut = `confirmed` ou `in_progress`
- ✅ Date = Aujourd'hui
- ✅ Heure actuelle dans [start_time - 15min, end_time]
- ✅ Hash HMAC valide

**Accès refusé si :**
- ❌ Statut = `cancelled` → "Réservation annulée"
- ❌ Statut = `completed` → "Réservation déjà utilisée"
- ❌ Date < Aujourd'hui → "Date dépassée"
- ❌ Date > Aujourd'hui → "Trop tôt"
- ❌ Heure trop tôt → "Votre créneau commence dans X minutes"
- ❌ Heure dépassée → "Créneau terminé"

---

### 2. Enregistrer le Check-In

**POST** `/api/scan/checkin`

Enregistre l'arrivée d'un ou plusieurs participants.

#### Request Body
```json
{
    "booking_id": 123,
    "participant_ids": [1, 2]  // Optionnel : si vide, tous les participants
}
```

#### Response Success (200)
```json
{
    "status": "success",
    "message": "Check-in enregistré avec succès",
    "data": {
        "checked_in_count": 2,
        "total_participants": 2,
        "all_checked_in": true
    }
}
```

**Comportement :**
- Si `participant_ids` fourni → Check-in des IDs spécifiés
- Si vide → Check-in de tous les participants non encore entrés
- Si tous les participants sont check-in → Statut réservation passe à `in_progress`

---

### 3. Terminer une Réservation

**POST** `/api/scan/complete`

Marque une réservation comme terminée.

#### Request Body
```json
{
    "booking_id": 123
}
```

#### Response Success (200)
```json
{
    "status": "success",
    "message": "Réservation marquée comme terminée",
    "data": {
        "booking_id": 123,
        "status": "completed"
    }
}
```

---

### 4. Statistiques du Scanner

**GET** `/api/scan/stats`

Récupère les statistiques en temps réel pour la journée.

#### Response (200)
```json
{
    "status": "success",
    "data": {
        "stats": {
            "total_bookings": 15,
            "confirmed": 8,
            "in_progress": 3,
            "completed": 2,
            "pending": 2,
            "total_participants": 45,
            "checked_in_participants": 12
        },
        "upcoming_bookings": [
            {
                "id": 124,
                "confirmation_code": "FL202601231500",
                "customer_name": "Sara Mansour",
                "game_name": "Escape Room",
                "room_name": "Salle Escape 1",
                "start_time": "15:00",
                "num_players": 6
            }
        ]
    }
}
```

**Note :** `upcoming_bookings` retourne les 5 prochaines réservations (3 heures à venir).

---

## 🖥️ Interface Scanner

### Accès
**URL :** `/admin/scanner`

### Fonctionnalités

#### 1. Scanner QR Code
- Utilise **html5-qrcode** (bibliothèque JavaScript)
- Accès caméra automatique
- Décodage en temps réel
- Cooldown de 3 secondes entre scans

#### 2. Affichage Résultat
**Accès Accordé (Vert) :**
```
✓ Bienvenue ! Accès accordé

Ahmed Ben Ali
Beat Saber VR - Salle VR 1
14:00 - 15:00
Joueurs: 0/2
```

**Accès Refusé (Rouge) :**
```
✗ Réservation annulée - Accès refusé

Ahmed Ben Ali
Beat Saber VR - Salle VR 1
14:00 - 15:00
```

**Trop Tôt (Orange) :**
```
⚠ Trop tôt - Votre créneau commence dans 25 minutes
```

#### 3. Statistiques Live
- Réservations aujourd'hui
- Sessions en cours
- Sessions terminées
- Participants (entrés/total)

#### 4. Prochaines Arrivées
Liste des 5 prochaines réservations (3h à venir)

#### 5. Son & Notifications
- ✅ Son de succès (accès accordé)
- ❌ Son d'erreur (accès refusé)
- Toggle on/off en haut à droite

---

## 🔄 Workflow Complet

### Arrivée du Client

1. **Client présente son QR code** (reçu par email)
2. **Scanner décode le QR** → JSON avec booking_id, hash
3. **POST /api/scan/validate** → Vérifie validité, statut, timing
4. **Si accès accordé :**
   - Affichage écran vert
   - Son de succès
   - **Automatique :** POST /api/scan/checkin
   - Mise à jour BDD : `participants.checked_in = 1`
   - Si tous entrés : `bookings.status = 'in_progress'`
5. **Si accès refusé :**
   - Affichage écran rouge
   - Son d'erreur
   - Message explicite du problème

### Fin de Session

1. **Admin clique "Terminer"** (ou automatique après end_time)
2. **POST /api/scan/complete**
3. `bookings.status = 'completed'`

---

## 🧪 Tests cURL

### Valider un QR Code
```bash
curl -X POST http://votresite.com/api/scan/validate \
  -H "Content-Type: application/json" \
  -d '{
    "booking_id": 123,
    "confirmation_code": "FL202601231234",
    "customer_name": "Ahmed Ben Ali",
    "hash": "abc123def456"
  }'
```

### Check-In Tous les Participants
```bash
curl -X POST http://votresite.com/api/scan/checkin \
  -H "Content-Type: application/json" \
  -d '{
    "booking_id": 123
  }'
```

### Statistiques
```bash
curl -X GET http://votresite.com/api/scan/stats
```

---

## 🛡️ Sécurité

### Validation HMAC
Chaque QR code contient un hash HMAC-SHA256 :
```php
$hash = hash_hmac('sha256', 
    $bookingId . $confirmationCode . $customerName,
    config('Encryption')->key
);
```

**Vérification côté serveur :**
- Recalcul du hash avec les données reçues
- Comparaison stricte avec le hash du QR
- Rejet si différent → QR falsifié

### Protection Double-Scan
- Cooldown de 3 secondes entre scans
- État `in_progress` empêche re-check-in

---

## 📊 Cas d'Usage

### Scénario 1 : Arrivée Normale
```
Client arrive → QR scanné → Validé ✅
→ Check-in automatique → Accès accordé
```

### Scénario 2 : Arrivée en Avance (20 min)
```
Client arrive → QR scanné → Refusé ⚠
Message: "Trop tôt - Votre créneau commence dans 20 minutes"
→ Client attend → Re-scan après 10 min → Validé ✅ (tolérance 15 min)
```

### Scénario 3 : Réservation Annulée
```
Client arrive → QR scanné → Refusé ❌
Message: "Réservation annulée - Accès refusé"
→ Client contacte réception
```

### Scénario 4 : Groupe Incomplet
```
4 joueurs réservés → 2 arrivent → QR scanné → Validé ✅
→ Check-in 2/4 → Statut reste "confirmed"
→ 2 autres arrivent → Re-scan → Check-in 4/4 → Statut → "in_progress"
```

---

## 🎨 Interface Utilisateur

### Layout Scanner
```
┌─────────────────────────────────────┐
│  📱 Scanner QR                      │
│  FunLab Tunisie - Contrôle d'Accès │
├─────────────────────────────────────┤
│  [Caméra Live - Zone de Scan]      │
│                                     │
│  ✅ Scanner actif                   │
│  ℹ️  Présentez le QR code          │
├─────────────────────────────────────┤
│  [15] Aujourd'hui  [3] En cours    │
│  [2]  Terminées    [12/45] Part.   │
├─────────────────────────────────────┤
│  ⏰ Prochaines arrivées             │
│  • 15:00 - Sara Mansour (VR)       │
│  • 16:30 - Ali Trabelsi (Escape)   │
└─────────────────────────────────────┘
```

### Feedback Visuel
- **Vert + ✓** : Accès accordé
- **Rouge + ✗** : Accès refusé
- **Orange + ⚠** : Trop tôt / Attention
- **Animation** : Slide down sur résultat
- **Auto-clear** : 3 secondes après validation

---

## 🔧 Intégration

### Prérequis
- Caméra accessible (HTTPS recommandé)
- Navigateur moderne (Chrome, Firefox, Safari)
- Permissions caméra accordées

### Installation
```html
<!-- html5-qrcode -->
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

<!-- Initialisation -->
<script>
const html5QrCode = new Html5Qrcode("reader");
html5QrCode.start(
    { facingMode: "environment" },
    { fps: 10, qrbox: 250 },
    onScanSuccess,
    onScanError
);
</script>
```

---

## ✅ Checklist Déploiement

- [ ] Tester la caméra sur l'appareil cible
- [ ] Vérifier les permissions navigateur
- [ ] Configurer HTTPS (requis pour caméra)
- [ ] Tester avec QR codes réels
- [ ] Ajuster le cooldown si nécessaire
- [ ] Configurer les sons (optionnel)
- [ ] Former le personnel au scanner
- [ ] Préparer plan B (vérification manuelle code)

---

**🎉 Le système de scan est opérationnel et prêt pour la production !**
