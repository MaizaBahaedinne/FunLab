<h1><i class="bi bi-code-square text-primary"></i> Documentation API</h1>

<div class="alert alert-info">
    FunLab Booking expose plusieurs endpoints API REST pour l'intégration avec des applications tierces.
</div>

## 🔐 Authentification

Toutes les requêtes API nécessitent une authentification via token JWT.

### Obtenir un token
```http
POST /api/auth/login
Content-Type: application/json

{
    "email": "user@example.com",
    "password": "password"
}
```

**Réponse :**
```json
{
    "success": true,
    "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
    "user": {
        "id": 1,
        "email": "user@example.com",
        "role": "user"
    }
}
```

### Utiliser le token
```http
GET /api/bookings
Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...
```

## 📅 API Disponibilités

### Vérifier les disponibilités
```http
GET /api/availability?game_id=1&date=2026-02-15
```

**Paramètres :**
- `game_id` (requis) : ID du jeu
- `date` (requis) : Date au format YYYY-MM-DD

**Réponse :**
```json
{
    "success": true,
    "date": "2026-02-15",
    "game": {
        "id": 1,
        "name": "Escape Room Mystère"
    },
    "available_slots": [
        {
            "time": "10:00",
            "available": true,
            "remaining_spots": 6
        },
        {
            "time": "14:00",
            "available": false,
            "remaining_spots": 0
        }
    ]
}
```

## 🎮 API Jeux

### Liste des jeux
```http
GET /api/games
```

**Paramètres optionnels :**
- `category_id` : Filtrer par catégorie
- `active` : 1 pour jeux actifs uniquement
- `limit` : Nombre de résultats (défaut: 10)
- `page` : Page de pagination

**Réponse :**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "name": "Escape Room Mystère",
            "description": "Une aventure mystérieuse...",
            "duration": 60,
            "min_players": 2,
            "max_players": 6,
            "price": 25.00,
            "image": "https://example.com/images/game1.jpg",
            "category": {
                "id": 1,
                "name": "Escape Game"
            }
        }
    ],
    "pagination": {
        "total": 45,
        "per_page": 10,
        "current_page": 1,
        "last_page": 5
    }
}
```

### Détails d'un jeu
```http
GET /api/games/1
```

## 📝 API Réservations

### Créer une réservation
```http
POST /api/bookings
Content-Type: application/json
Authorization: Bearer {token}

{
    "game_id": 1,
    "booking_date": "2026-02-15",
    "booking_time": "14:00",
    "number_of_players": 4,
    "customer_name": "Ahmed Ben Ali",
    "customer_email": "ahmed@example.com",
    "customer_phone": "+216 20 123 456",
    "participants": [
        {"name": "Ahmed Ben Ali", "email": "ahmed@example.com"},
        {"name": "Fatma Trabelsi", "email": "fatma@example.com"}
    ]
}
```

**Réponse :**
```json
{
    "success": true,
    "booking": {
        "id": 123,
        "reference": "FL20260215-123",
        "status": "pending",
        "total_amount": 100.00,
        "payment_url": "https://checkout.stripe.com/..."
    }
}
```

### Mes réservations
```http
GET /api/my-bookings
Authorization: Bearer {token}
```

### Détails d'une réservation
```http
GET /api/bookings/123
Authorization: Bearer {token}
```

### Annuler une réservation
```http
DELETE /api/bookings/123
Authorization: Bearer {token}
```

## 💳 API Paiements

### Créer une session de paiement
```http
POST /api/payment/create-session
Content-Type: application/json

{
    "booking_id": 123,
    "amount": 100.00
}
```

### Webhook Stripe
```http
POST /api/payment/webhook
Stripe-Signature: {signature}
```

## 🎫 API Scanner

### Valider un ticket
```http
POST /api/scanner/validate
Content-Type: application/json
Authorization: Bearer {token}

{
    "qr_code": "FL20260215-123"
}
```

**Réponse :**
```json
{
    "success": true,
    "booking": {
        "reference": "FL20260215-123",
        "game": "Escape Room Mystère",
        "date": "2026-02-15",
        "time": "14:00",
        "customer": "Ahmed Ben Ali",
        "players": 4,
        "status": "validated"
    }
}
```

## ⭐ API Avis

### Soumettre un avis
```http
POST /api/reviews
Content-Type: application/json

{
    "game_id": 1,
    "rating": 5,
    "comment": "Expérience incroyable !",
    "customer_name": "Ahmed Ben Ali"
}
```

### Liste des avis d'un jeu
```http
GET /api/games/1/reviews
```

## 🔍 Codes de réponse

| Code | Signification |
|------|---------------|
| 200 | Succès |
| 201 | Créé avec succès |
| 400 | Requête invalide |
| 401 | Non authentifié |
| 403 | Permission refusée |
| 404 | Ressource introuvable |
| 422 | Validation échouée |
| 500 | Erreur serveur |

## 📝 Format des erreurs

```json
{
    "success": false,
    "error": {
        "code": "VALIDATION_ERROR",
        "message": "Les données fournies sont invalides",
        "details": {
            "email": "Le format de l'email est invalide",
            "phone": "Le numéro de téléphone est requis"
        }
    }
}
```

## 🔧 Rate Limiting

- **Limite par défaut** : 60 requêtes par minute
- **Header de réponse** : `X-RateLimit-Remaining`

Si la limite est dépassée :
```json
{
    "success": false,
    "error": {
        "code": "RATE_LIMIT_EXCEEDED",
        "message": "Trop de requêtes, réessayez dans 60 secondes"
    }
}
```

---

<div class="alert alert-warning">
    <i class="bi bi-exclamation-triangle"></i> <strong>Note :</strong> L'API est actuellement en version beta. Certains endpoints peuvent évoluer.
</div>
