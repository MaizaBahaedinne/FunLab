# 🎮 FunLab Tunisie - Système de Réservation

![CodeIgniter](https://img.shields.io/badge/CodeIgniter-4.x-EE4623?logo=codeigniter)
![PHP](https://img.shields.io/badge/PHP-8.0%2B-777BB4?logo=php)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?logo=mysql)
![License](https://img.shields.io/badge/License-MIT-green)

Système complet de réservation de créneaux horaires pour centre d'activités indoor (Escape Game, VR, Laser Game) avec gestion anti-double-réservation.

---

## 🌟 Fonctionnalités

### ✅ Disponible (Phase 1)

- **Availability Engine** : Moteur de disponibilité robuste anti-double-réservation
- **API REST** : 5 endpoints pour la gestion des disponibilités
- **Détection de conflits** : Algorithme précis de détection de chevauchements
- **Gestion des fermetures** : Fermetures globales ou par salle
- **Créneaux dynamiques** : Génération intelligente basée sur la durée du jeu
- **Validation complète** : Respect des horaires, compatibilité salle/jeu, etc.

### 🔜 En développement (Phases suivantes)

- **BookingService** : Création et gestion des réservations complètes
- **TicketService** : Génération de billets avec QR Code
- **QRCodeService** : Scan et validation des tickets
- **Interface Admin** : Dashboard avec FullCalendar
- **Interface Client** : Sélection de créneaux et réservation en ligne
- **Notifications** : Emails de confirmation automatiques
- **Statistiques** : Rapports et analytics

---

## 🏗️ Architecture

```
┌─────────────────────────────────────────────────┐
│              FRONTEND (Bootstrap)                │
│  • Interface Client                              │
│  • Interface Admin (FullCalendar)                │
└────────────────┬────────────────────────────────┘
                 │ AJAX
                 ▼
┌─────────────────────────────────────────────────┐
│         API REST (Controllers/Api)               │
│  • AvailabilityApi ✅                            │
│  • BookingApi 🔜                                 │
│  • ScanApi 🔜                                    │
└────────────────┬────────────────────────────────┘
                 │
                 ▼
┌─────────────────────────────────────────────────┐
│           SERVICES (Business Logic)              │
│  • AvailabilityService ✅                        │
│  • BookingService 🔜                             │
│  • TicketService 🔜                              │
│  • QRCodeService 🔜                              │
│  • StatsService 🔜                               │
└────────────────┬────────────────────────────────┘
                 │
                 ▼
┌─────────────────────────────────────────────────┐
│         MODELS (CodeIgniter 4 ORM)               │
│  • RoomModel ✅                                  │
│  • GameModel ✅                                  │
│  • BookingModel ✅                               │
│  • ClosureModel ✅                               │
└─────────────────────────────────────────────────┘
```

---

## 🚀 Installation

### Prérequis

- PHP 8.0 ou supérieur
- MySQL 8.0 ou supérieur
- Composer
- Extension PHP : intl, mbstring, json, mysqlnd

### Étape 1 : Cloner le projet

```bash
git clone https://github.com/votre-repo/funlab-booking.git
cd funlab-booking
```

### Étape 2 : Installer les dépendances

```bash
composer install
```

### Étape 3 : Configuration

```bash
# Copier le fichier d'environnement
cp .env.example .env

# Éditer .env et configurer la base de données
nano .env
```

### Étape 4 : Créer la base de données

```bash
mysql -u root -p
```

```sql
CREATE DATABASE funlab_booking CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE funlab_booking;
source database_schema.sql;
exit;
```

### Étape 5 : Démarrer le serveur

```bash
php spark serve
```

Application disponible sur : **http://localhost:8080**

---

## 📚 Documentation

- **[QUICK_START.md](QUICK_START.md)** - Guide de démarrage rapide
- **[AVAILABILITY_API.md](AVAILABILITY_API.md)** - Documentation complète de l'API
- **[database_schema.sql](database_schema.sql)** - Structure de la base de données

---

## 🧪 Tests

### Exécuter les tests unitaires

```bash
vendor/bin/phpunit tests/unit/AvailabilityServiceTest.php
```

### Tester l'API avec curl

```bash
# Test 1 : Récupérer les créneaux disponibles
curl "http://localhost:8080/api/availability/slots?game_id=1&date=2026-01-26"

# Test 2 : Vérifier un créneau spécifique
curl -X POST "http://localhost:8080/api/availability/check" \
  -H "Content-Type: application/json" \
  -d '{
    "room_id": 1,
    "game_id": 1,
    "date": "2026-01-26",
    "start_time": "14:00:00",
    "end_time": "14:30:00"
  }'
```

### Interface de test

Ouvrez votre navigateur : **http://localhost:8080/availability-example.html**

---

## 📊 Structure de la base de données

```
rooms               → Salles d'activités
games               → Jeux/Activités disponibles
room_games          → Association salles ↔ jeux
bookings            → Réservations (avec détection de conflits)
participants        → Participants aux sessions
closures            → Fermetures (globales ou par salle)
users               → Administrateurs et staff
```

---

## 🔑 Endpoints API

| Méthode | Endpoint | Description | Statut |
|---------|----------|-------------|--------|
| GET | `/api/availability/slots` | Créneaux disponibles | ✅ |
| POST | `/api/availability/check` | Vérifier un créneau | ✅ |
| GET | `/api/availability/rooms` | Salles pour un jeu | ✅ |
| GET | `/api/availability/closure` | Vérifier fermetures | ✅ |
| GET | `/api/availability/occupied` | Créneaux occupés | ✅ |
| POST | `/api/booking/create` | Créer une réservation | 🔜 |
| POST | `/api/scan/validate` | Valider un QR code | 🔜 |

---

## 🛡️ Sécurité

### Mesures implémentées

- ✅ Validation de toutes les entrées utilisateur
- ✅ Protection contre les injections SQL (ORM CodeIgniter)
- ✅ Échappement des sorties
- ✅ Protection CSRF (à activer)
- ✅ Logs des erreurs et accès
- ✅ Hachage sécurisé des mots de passe (password_hash)

### Configuration CSRF

Dans `app/Config/Filters.php` :

```php
public array $globals = [
    'before' => [
        'csrf', // Activer cette ligne
    ],
];
```

---

## 🎨 Technologies utilisées

### Backend

- **Framework** : CodeIgniter 4
- **Langage** : PHP 8+
- **Base de données** : MySQL 8
- **Architecture** : MVC + Services

### Frontend (à venir)

- **Framework CSS** : Bootstrap 5
- **JavaScript** : Vanilla JS + AJAX
- **Calendrier** : FullCalendar
- **Scanner QR** : html5-qrcode

---

## 📈 Roadmap

### Phase 1 : Availability Engine ✅ (Terminé)

- [x] Service de disponibilité
- [x] API REST
- [x] Détection de conflits
- [x] Tests unitaires
- [x] Documentation

### Phase 2 : Booking System 🔜 (En cours)

- [ ] BookingService complet
- [ ] Création de réservations
- [ ] Validation des paiements
- [ ] Génération de billets

### Phase 3 : Interface Client 🔜

- [ ] Page de sélection de jeux
- [ ] Calendrier de disponibilités
- [ ] Formulaire de réservation
- [ ] Confirmation et paiement

### Phase 4 : Interface Admin 🔜

- [ ] Dashboard avec statistiques
- [ ] Gestion des salles et jeux
- [ ] Calendrier FullCalendar
- [ ] Gestion des réservations
- [ ] Scanner QR Code

### Phase 5 : Notifications 🔜

- [ ] Emails de confirmation
- [ ] Rappels automatiques
- [ ] Notifications SMS (optionnel)

---

## 🤝 Contribution

Ce projet est développé pour FunLab Tunisie.

---

## 📝 License

MIT License - Voir le fichier [LICENSE](LICENSE)

---

## 📧 Contact

- **Email** : contact@funlab.tn
- **Téléphone** : +216 70 123 456
- **Site web** : https://www.funlab.tn

---

## 🙏 Remerciements

Développé avec ❤️ pour FunLab Tunisie

- CodeIgniter 4 Framework
- Bootstrap
- FullCalendar
- html5-qrcode

---

**Version actuelle : 1.0.0 - Availability Engine**

**Dernière mise à jour : 23 janvier 2026**

When updating, check the release notes to see if there are any changes you might need to apply
to your `app` folder. The affected files can be copied or merged from
`vendor/codeigniter4/framework/app`.

## Setup

Copy `env` to `.env` and tailor for your app, specifically the baseURL
and any database settings.

## Important Change with index.php

`index.php` is no longer in the root of the project! It has been moved inside the *public* folder,
for better security and separation of components.

This means that you should configure your web server to "point" to your project's *public* folder, and
not to the project root. A better practice would be to configure a virtual host to point there. A poor practice would be to point your web server to the project root and expect to enter *public/...*, as the rest of your logic and the
framework are exposed.

**Please** read the user guide for a better explanation of how CI4 works!

## Repository Management

We use GitHub issues, in our main repository, to track **BUGS** and to track approved **DEVELOPMENT** work packages.
We use our [forum](http://forum.codeigniter.com) to provide SUPPORT and to discuss
FEATURE REQUESTS.

This repository is a "distribution" one, built by our release preparation script.
Problems with it can be raised on our forum, or as issues in the main repository.

## Server Requirements

PHP version 8.1 or higher is required, with the following extensions installed:

- [intl](http://php.net/manual/en/intl.requirements.php)
- [mbstring](http://php.net/manual/en/mbstring.installation.php)

> [!WARNING]
> - The end of life date for PHP 7.4 was November 28, 2022.
> - The end of life date for PHP 8.0 was November 26, 2023.
> - If you are still using PHP 7.4 or 8.0, you should upgrade immediately.
> - The end of life date for PHP 8.1 will be December 31, 2025.

Additionally, make sure that the following extensions are enabled in your PHP:

- json (enabled by default - don't turn it off)
- [mysqlnd](http://php.net/manual/en/mysqlnd.install.php) if you plan to use MySQL
- [libcurl](http://php.net/manual/en/curl.requirements.php) if you plan to use the HTTP\CURLRequest library
