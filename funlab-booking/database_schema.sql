-- ============================================================
-- FUNLAB BOOKING SYSTEM - DATABASE SCHEMA
-- ============================================================
-- Version: 1.0.0
-- Date: 2026-01-23
-- Description: Structure complète de la base de données
-- ============================================================

-- Désactiver les vérifications de clés étrangères temporairement
SET FOREIGN_KEY_CHECKS = 0;

-- ============================================================
-- TABLE: rooms (Salles)
-- ============================================================
DROP TABLE IF EXISTS `rooms`;
CREATE TABLE `rooms` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `capacity` int(11) NOT NULL DEFAULT 4,
  `status` enum('active','inactive','maintenance') NOT NULL DEFAULT 'active',
  `image` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Données d'exemple
INSERT INTO `rooms` (`name`, `description`, `capacity`, `status`) VALUES
('Salle VR 1', 'Salle de réalité virtuelle équipée de casques HTC Vive', 6, 'active'),
('Escape Room - Le Mystère', 'Escape room thème mystère et enquête', 8, 'active'),
('Escape Room - L\'Égypte', 'Escape room thème égyptien', 6, 'active'),
('Laser Game Arena', 'Grande arène de laser game', 12, 'active'),
('Salle VR 2', 'Salle VR secondaire', 4, 'active');

-- ============================================================
-- TABLE: games (Jeux/Activités)
-- ============================================================
DROP TABLE IF EXISTS `games`;
CREATE TABLE `games` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `duration_minutes` int(11) NOT NULL DEFAULT 60,
  `min_players` int(11) NOT NULL DEFAULT 2,
  `max_players` int(11) NOT NULL DEFAULT 8,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `image` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Données d'exemple
INSERT INTO `games` (`name`, `description`, `duration_minutes`, `min_players`, `max_players`, `price`, `status`) VALUES
('Beat Saber VR', 'Jeu de rythme en réalité virtuelle', 30, 1, 2, 25.00, 'active'),
('Half-Life: Alyx VR', 'Aventure FPS en VR', 60, 1, 2, 35.00, 'active'),
('Le Mystère du Manoir', 'Escape room - Résolvez le mystère', 60, 4, 8, 120.00, 'active'),
('Le Trésor du Pharaon', 'Escape room - Thème égyptien', 60, 4, 6, 120.00, 'active'),
('Laser Game Classic', 'Partie de laser game classique', 30, 4, 12, 15.00, 'active'),
('Laser Game VIP', 'Partie de laser game prolongée', 60, 6, 12, 25.00, 'active');

-- ============================================================
-- TABLE: room_games (Association Salles-Jeux)
-- ============================================================
DROP TABLE IF EXISTS `room_games`;
CREATE TABLE `room_games` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `room_id` int(11) unsigned NOT NULL,
  `game_id` int(11) unsigned NOT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_room_game` (`room_id`, `game_id`),
  KEY `idx_game_id` (`game_id`),
  KEY `idx_is_available` (`is_available`),
  CONSTRAINT `fk_room_games_room` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_room_games_game` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Associations salles-jeux
INSERT INTO `room_games` (`room_id`, `game_id`, `is_available`) VALUES
(1, 1, 1), -- Salle VR 1 → Beat Saber
(1, 2, 1), -- Salle VR 1 → Half-Life Alyx
(5, 1, 1), -- Salle VR 2 → Beat Saber
(5, 2, 1), -- Salle VR 2 → Half-Life Alyx
(2, 3, 1), -- Escape Room Mystère → Le Mystère du Manoir
(3, 4, 1), -- Escape Room Égypte → Le Trésor du Pharaon
(4, 5, 1), -- Laser Game Arena → Classic
(4, 6, 1); -- Laser Game Arena → VIP

-- ============================================================
-- TABLE: bookings (Réservations)
-- ============================================================
DROP TABLE IF EXISTS `bookings`;
CREATE TABLE `bookings` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `room_id` int(11) unsigned NOT NULL,
  `game_id` int(11) unsigned NOT NULL,
  `booking_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_email` varchar(255) NOT NULL,
  `customer_phone` varchar(50) NOT NULL,
  `num_players` int(11) NOT NULL DEFAULT 1,
  `total_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` enum('pending','confirmed','cancelled','completed') NOT NULL DEFAULT 'pending',
  `confirmation_code` varchar(50) DEFAULT NULL,
  `qr_code` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_confirmation_code` (`confirmation_code`),
  KEY `idx_room_date` (`room_id`, `booking_date`),
  KEY `idx_booking_date` (`booking_date`),
  KEY `idx_start_time` (`start_time`),
  KEY `idx_status` (`status`),
  KEY `idx_customer_email` (`customer_email`),
  KEY `idx_game_id` (`game_id`),
  -- INDEX CRITIQUE pour la détection de conflits
  KEY `idx_room_date_time` (`room_id`, `booking_date`, `start_time`, `end_time`),
  CONSTRAINT `fk_bookings_room` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`),
  CONSTRAINT `fk_bookings_game` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Données de test
INSERT INTO `bookings` (`room_id`, `game_id`, `booking_date`, `start_time`, `end_time`, `customer_name`, `customer_email`, `customer_phone`, `num_players`, `total_price`, `status`, `confirmation_code`) VALUES
(1, 1, '2026-01-25', '10:00:00', '10:30:00', 'Jean Dupont', 'jean@example.com', '+216 20 123 456', 2, 50.00, 'confirmed', 'FUNLAB001'),
(1, 2, '2026-01-25', '14:00:00', '15:00:00', 'Marie Martin', 'marie@example.com', '+216 20 234 567', 2, 70.00, 'confirmed', 'FUNLAB002'),
(2, 3, '2026-01-25', '16:00:00', '17:00:00', 'Ahmed Ben Ali', 'ahmed@example.com', '+216 20 345 678', 6, 120.00, 'pending', 'FUNLAB003');

-- ============================================================
-- TABLE: participants (Participants)
-- ============================================================
DROP TABLE IF EXISTS `participants`;
CREATE TABLE `participants` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `booking_id` int(11) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `checked_in` tinyint(1) NOT NULL DEFAULT 0,
  `checked_in_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_booking_id` (`booking_id`),
  KEY `idx_checked_in` (`checked_in`),
  CONSTRAINT `fk_participants_booking` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: closures (Fermetures)
-- ============================================================
DROP TABLE IF EXISTS `closures`;
CREATE TABLE `closures` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `room_id` int(11) unsigned DEFAULT NULL,
  `closure_date` date NOT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `all_rooms` tinyint(1) NOT NULL DEFAULT 0,
  `reason` text DEFAULT NULL,
  `is_recurring` tinyint(1) NOT NULL DEFAULT 0,
  `recurring_pattern` varchar(255) DEFAULT NULL COMMENT 'weekly, monthly, etc.',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_closure_date` (`closure_date`),
  KEY `idx_room_id` (`room_id`),
  KEY `idx_all_rooms` (`all_rooms`),
  KEY `idx_room_date` (`room_id`, `closure_date`),
  CONSTRAINT `fk_closures_room` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Exemples de fermetures
INSERT INTO `closures` (`closure_date`, `all_rooms`, `reason`) VALUES
('2026-01-01', 1, 'Jour de l\'An - Fermé'),
('2026-05-01', 1, 'Fête du Travail - Fermé');

INSERT INTO `closures` (`room_id`, `closure_date`, `all_rooms`, `reason`) VALUES
(2, '2026-01-26', 0, 'Maintenance de la salle');

-- ============================================================
-- TABLE: users (Admin/Staff - optionnel pour l'instant)
-- ============================================================
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','staff','manager') NOT NULL DEFAULT 'staff',
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_username` (`username`),
  UNIQUE KEY `idx_email` (`email`),
  KEY `idx_role` (`role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Utilisateur admin par défaut (mot de passe: admin123)
INSERT INTO `users` (`username`, `email`, `password`, `role`) VALUES
('admin', 'admin@funlab.tn', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- ============================================================
-- RÉACTIVER LES VÉRIFICATIONS
-- ============================================================
SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
-- VUES UTILES (optionnel)
-- ============================================================

-- Vue : Réservations avec détails
CREATE OR REPLACE VIEW `v_bookings_full` AS
SELECT 
    b.id,
    b.booking_date,
    b.start_time,
    b.end_time,
    b.customer_name,
    b.customer_email,
    b.customer_phone,
    b.num_players,
    b.total_price,
    b.status,
    b.confirmation_code,
    r.name AS room_name,
    g.name AS game_name,
    g.duration_minutes,
    b.created_at
FROM bookings b
INNER JOIN rooms r ON b.room_id = r.id
INNER JOIN games g ON b.game_id = g.id;

-- ============================================================
-- TRIGGERS (optionnel)
-- ============================================================

-- Générer automatiquement un code de confirmation
DELIMITER //
CREATE TRIGGER `generate_confirmation_code` 
BEFORE INSERT ON `bookings`
FOR EACH ROW
BEGIN
    IF NEW.confirmation_code IS NULL THEN
        SET NEW.confirmation_code = CONCAT('FL', DATE_FORMAT(NOW(), '%Y%m%d'), LPAD(FLOOR(RAND() * 10000), 4, '0'));
    END IF;
END//
DELIMITER ;

-- ============================================================
-- PROCÉDURES STOCKÉES (optionnel - pour statistiques)
-- ============================================================

DELIMITER //
CREATE PROCEDURE `sp_get_daily_stats`(IN p_date DATE)
BEGIN
    SELECT 
        COUNT(*) as total_bookings,
        SUM(total_price) as total_revenue,
        SUM(num_players) as total_players
    FROM bookings
    WHERE booking_date = p_date
    AND status IN ('confirmed', 'completed');
END//
DELIMITER ;

-- ============================================================
-- FIN DU SCRIPT
-- ============================================================

-- Afficher un résumé
SELECT 'Base de données créée avec succès!' AS message;
SELECT COUNT(*) AS total_rooms FROM rooms;
SELECT COUNT(*) AS total_games FROM games;
SELECT COUNT(*) AS total_bookings FROM bookings;
