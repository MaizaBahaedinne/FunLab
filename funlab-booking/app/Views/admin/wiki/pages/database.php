<h1><i class="bi bi-database text-primary"></i> Structure Base de Données</h1>

## 🗄️ Vue d'ensemble

Base de données : **funl_FunLabBooking** (MariaDB/MySQL)
Encodage : **utf8mb4_unicode_ci**

Total : **15 tables principales**

## 📋 Tables

### 👥 users
Gestion des utilisateurs (clients, staff, admin)

```sql
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    firstName VARCHAR(100),
    lastName VARCHAR(100),
    phone VARCHAR(20),
    role ENUM('user', 'staff', 'admin') DEFAULT 'user',
    isActive TINYINT(1) DEFAULT 1,
    isVerified TINYINT(1) DEFAULT 0,
    verificationCode VARCHAR(10),
    verificationExpiry DATETIME,
    googleId VARCHAR(255),
    facebookId VARCHAR(255),
    profilePicture VARCHAR(255),
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

**Index** : email, role, googleId, facebookId

### 🎮 games
Catalogue des jeux

```sql
CREATE TABLE games (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE,
    description TEXT,
    longDescription TEXT,
    duration INT, -- en minutes
    minPlayers INT DEFAULT 2,
    maxPlayers INT DEFAULT 6,
    difficulty ENUM('easy', 'medium', 'hard'),
    price DECIMAL(10,2),
    categoryId INT,
    image VARCHAR(255),
    gallery TEXT, -- JSON array
    isActive TINYINT(1) DEFAULT 1,
    features TEXT, -- JSON array
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (categoryId) REFERENCES game_categories(id)
);
```

### 🏷️ game_categories
Catégories de jeux

```sql
CREATE TABLE game_categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) UNIQUE,
    description TEXT,
    icon VARCHAR(50),
    displayOrder INT DEFAULT 0
);
```

### 🏢 rooms
Salles de jeu

```sql
CREATE TABLE rooms (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    capacity INT,
    equipment TEXT, -- JSON array
    isActive TINYINT(1) DEFAULT 1
);
```

### 🔗 room_games
Relation many-to-many (salles ↔ jeux)

```sql
CREATE TABLE room_games (
    id INT PRIMARY KEY AUTO_INCREMENT,
    roomId INT,
    gameId INT,
    FOREIGN KEY (roomId) REFERENCES rooms(id),
    FOREIGN KEY (gameId) REFERENCES games(id),
    UNIQUE KEY (roomId, gameId)
);
```

### 📅 bookings
Réservations

```sql
CREATE TABLE bookings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    reference VARCHAR(50) UNIQUE, -- FL20260215-123
    userId INT,
    gameId INT,
    roomId INT,
    bookingDate DATE NOT NULL,
    bookingTime TIME NOT NULL,
    numberOfPlayers INT,
    totalAmount DECIMAL(10,2),
    customerName VARCHAR(255),
    customerEmail VARCHAR(255),
    customerPhone VARCHAR(20),
    status ENUM('pending', 'confirmed', 'validated', 'completed', 'cancelled') DEFAULT 'pending',
    paymentStatus ENUM('unpaid', 'pending', 'paid', 'refunded', 'failed') DEFAULT 'unpaid',
    paymentMethod ENUM('card', 'cash', 'transfer', 'other'),
    notes TEXT,
    registrationToken VARCHAR(100) UNIQUE,
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (userId) REFERENCES users(id),
    FOREIGN KEY (gameId) REFERENCES games(id),
    FOREIGN KEY (roomId) REFERENCES rooms(id)
);
```

**Index** : reference, userId, gameId, bookingDate, status, paymentStatus

### 👤 participants
Participants aux réservations

```sql
CREATE TABLE participants (
    id INT PRIMARY KEY AUTO_INCREMENT,
    bookingId INT NOT NULL,
    name VARCHAR(255),
    email VARCHAR(255),
    phone VARCHAR(20),
    isRegistered TINYINT(1) DEFAULT 0,
    FOREIGN KEY (bookingId) REFERENCES bookings(id) ON DELETE CASCADE
);
```

### 🏆 teams
Équipes pour jeux compétitifs

```sql
CREATE TABLE teams (
    id INT PRIMARY KEY AUTO_INCREMENT,
    bookingId INT NOT NULL,
    name VARCHAR(100), -- Équipe Rouge, Bleue, etc.
    color VARCHAR(50),
    score INT DEFAULT 0,
    FOREIGN KEY (bookingId) REFERENCES bookings(id) ON DELETE CASCADE
);
```

### 👥 team_participants
Assignation participants → équipes

```sql
CREATE TABLE team_participants (
    teamId INT,
    participantId INT,
    PRIMARY KEY (teamId, participantId),
    FOREIGN KEY (teamId) REFERENCES teams(id) ON DELETE CASCADE,
    FOREIGN KEY (participantId) REFERENCES participants(id) ON DELETE CASCADE
);
```

### 💳 payments
Historique des paiements

```sql
CREATE TABLE payments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    bookingId INT NOT NULL,
    userId INT,
    amount DECIMAL(10,2),
    currency VARCHAR(3) DEFAULT 'TND',
    method ENUM('card', 'cash', 'transfer'),
    stripePaymentIntentId VARCHAR(255),
    stripeChargeId VARCHAR(255),
    status ENUM('pending', 'succeeded', 'failed', 'refunded'),
    refundAmount DECIMAL(10,2),
    customerId VARCHAR(255),
    metadata TEXT, -- JSON
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (bookingId) REFERENCES bookings(id),
    FOREIGN KEY (userId) REFERENCES users(id)
);
```

### ⭐ game_reviews
Avis clients sur les jeux

```sql
CREATE TABLE game_reviews (
    id INT PRIMARY KEY AUTO_INCREMENT,
    gameId INT NOT NULL,
    userId INT,
    customerName VARCHAR(255),
    customerEmail VARCHAR(255),
    rating INT CHECK (rating BETWEEN 1 AND 5),
    comment TEXT,
    isApproved TINYINT(1) DEFAULT 0,
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (gameId) REFERENCES games(id),
    FOREIGN KEY (userId) REFERENCES users(id)
);
```

### 🚫 closures
Fermetures et indisponibilités

```sql
CREATE TABLE closures (
    id INT PRIMARY KEY AUTO_INCREMENT,
    gameId INT, -- NULL = tous les jeux
    closureDate DATE NOT NULL,
    startTime TIME,
    endTime TIME,
    reason VARCHAR(255),
    type ENUM('maintenance', 'event', 'holiday', 'other'),
    FOREIGN KEY (gameId) REFERENCES games(id)
);
```

### ⚙️ settings
Configuration système

```sql
CREATE TABLE settings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    settingKey VARCHAR(100) UNIQUE NOT NULL,
    settingValue TEXT,
    category VARCHAR(50), -- general, email, payment, etc.
    description TEXT,
    updatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

**Exemples de clés** :
- `site_name`
- `smtp_host`, `smtp_user`, `smtp_pass`
- `stripe_publishable_key`, `stripe_secret_key`
- `role_permissions` (JSON)
- `opening_hours` (JSON)

### 📧 email_logs
Historique des emails envoyés

```sql
CREATE TABLE email_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    recipient VARCHAR(255),
    subject VARCHAR(255),
    status ENUM('sent', 'failed'),
    errorMessage TEXT,
    sentAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### 📝 activity_logs
Logs d'activité admin

```sql
CREATE TABLE activity_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    userId INT,
    action VARCHAR(100), -- create, update, delete
    targetType VARCHAR(50), -- booking, game, user
    targetId INT,
    ipAddress VARCHAR(45),
    userAgent TEXT,
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (userId) REFERENCES users(id)
);
```

## 🔗 Relations

### Diagramme simplifié
```
users (1) ----< (N) bookings
games (1) ----< (N) bookings
rooms (1) ----< (N) bookings
bookings (1) ----< (N) participants
bookings (1) ----< (N) teams
bookings (1) ----< (N) payments
games (1) ----< (N) game_reviews
game_categories (1) ----< (N) games
rooms (N) >----< (N) games (via room_games)
teams (N) >----< (N) participants (via team_participants)
```

## 📊 Requêtes utiles

### Statistiques du jour
```sql
SELECT 
    COUNT(*) as total_bookings,
    SUM(totalAmount) as total_revenue,
    COUNT(CASE WHEN status = 'confirmed' THEN 1 END) as confirmed
FROM bookings 
WHERE bookingDate = CURDATE();
```

### Top 5 jeux
```sql
SELECT g.name, COUNT(b.id) as bookings_count
FROM games g
LEFT JOIN bookings b ON g.id = b.gameId
WHERE b.status != 'cancelled'
GROUP BY g.id
ORDER BY bookings_count DESC
LIMIT 5;
```

### Taux d'occupation
```sql
SELECT 
    bookingDate,
    COUNT(*) as total_slots,
    (COUNT(*) / (SELECT COUNT(*) FROM rooms)) as occupation_rate
FROM bookings
WHERE bookingDate BETWEEN '2026-01-01' AND '2026-01-31'
GROUP BY bookingDate;
```

## 🔧 Maintenance

### Sauvegarde
```bash
mysqldump -u root -p funl_FunLabBooking > backup_$(date +%Y%m%d).sql
```

### Restauration
```bash
mysql -u root -p funl_FunLabBooking < backup_20260131.sql
```

### Optimisation
```sql
OPTIMIZE TABLE bookings, games, participants, payments;
```

---

<div class="alert alert-warning">
    ⚠️ <strong>Important :</strong> Effectuez des sauvegardes quotidiennes automatiques de votre base de données.
</div>
