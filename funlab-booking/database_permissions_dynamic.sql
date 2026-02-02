-- Système de permissions dynamique et extensible

-- Table des modules disponibles
CREATE TABLE IF NOT EXISTS `permission_modules` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `key` VARCHAR(50) NOT NULL UNIQUE COMMENT 'Clé unique du module (ex: bookings)',
    `name` VARCHAR(100) NOT NULL COMMENT 'Nom affiché',
    `description` TEXT NULL,
    `icon` VARCHAR(50) NULL COMMENT 'Icône Bootstrap',
    `is_active` TINYINT(1) DEFAULT 1,
    `sort_order` INT DEFAULT 0,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_key` (`key`),
    KEY `idx_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des actions disponibles
CREATE TABLE IF NOT EXISTS `permission_actions` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `key` VARCHAR(50) NOT NULL COMMENT 'Clé de l\'action (ex: view, create)',
    `name` VARCHAR(100) NOT NULL COMMENT 'Nom affiché',
    `description` TEXT NULL,
    `is_active` TINYINT(1) DEFAULT 1,
    `sort_order` INT DEFAULT 0,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_key` (`key`),
    KEY `idx_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des rôles
CREATE TABLE IF NOT EXISTS `roles` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `key` VARCHAR(50) NOT NULL UNIQUE COMMENT 'Clé du rôle (admin, staff, user)',
    `name` VARCHAR(100) NOT NULL COMMENT 'Nom affiché',
    `description` TEXT NULL,
    `is_active` TINYINT(1) DEFAULT 1,
    `is_system` TINYINT(1) DEFAULT 0 COMMENT 'Rôle système non supprimable',
    `sort_order` INT DEFAULT 0,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_key` (`key`),
    KEY `idx_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des permissions (liaison role-module-action)
CREATE TABLE IF NOT EXISTS `role_permissions` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `role_id` INT UNSIGNED NOT NULL,
    `module_id` INT UNSIGNED NOT NULL,
    `action_id` INT UNSIGNED NOT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_permission` (`role_id`, `module_id`, `action_id`),
    KEY `idx_role` (`role_id`),
    KEY `idx_module` (`module_id`),
    KEY `idx_action` (`action_id`),
    CONSTRAINT `fk_perm_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_perm_module` FOREIGN KEY (`module_id`) REFERENCES `permission_modules` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_perm_action` FOREIGN KEY (`action_id`) REFERENCES `permission_actions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insérer les actions de base
INSERT INTO `permission_actions` (`key`, `name`, `description`, `sort_order`) VALUES
('view', 'Voir', 'Consulter les données', 1),
('create', 'Créer', 'Ajouter de nouveaux éléments', 2),
('edit', 'Modifier', 'Éditer les éléments existants', 3),
('delete', 'Supprimer', 'Effacer des éléments', 4),
('approve', 'Approuver', 'Approuver/valider des éléments', 5),
('scan', 'Scanner', 'Scanner des QR codes', 6),
('export', 'Exporter', 'Exporter des données', 7),
('import', 'Importer', 'Importer des données', 8)
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);

-- Insérer les modules actuels
INSERT INTO `permission_modules` (`key`, `name`, `description`, `icon`, `sort_order`) VALUES
('dashboard', 'Dashboard', 'Tableau de bord', 'speedometer2', 1),
('bookings', 'Réservations', 'Gestion des réservations', 'calendar-check', 2),
('games', 'Jeux', 'Gestion des jeux', 'controller', 3),
('rooms', 'Salles', 'Gestion des salles', 'door-closed', 4),
('closures', 'Fermetures', 'Gestion des fermetures', 'x-circle', 5),
('reviews', 'Avis Clients', 'Gestion des avis', 'star-fill', 6),
('participants', 'Participants', 'Gestion des participants', 'person', 7),
('teams', 'Équipes', 'Gestion des équipes', 'people-fill', 8),
('scanner', 'Scanner', 'Scanner de tickets', 'qr-code-scan', 9),
('promo_codes', 'Codes Promo', 'Gestion des codes promo', 'ticket-perforated', 10),
('contacts', 'Communication', 'Messages et newsletter', 'envelope-at', 11),
('settings', 'Paramètres', 'Configuration système', 'gear-fill', 12),
('users', 'Utilisateurs', 'Gestion des utilisateurs', 'people', 13)
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`), `icon` = VALUES(`icon`);

-- Insérer les rôles système
INSERT INTO `roles` (`key`, `name`, `description`, `is_system`, `sort_order`) VALUES
('admin', 'Administrateur', 'Accès complet à toutes les fonctionnalités', 1, 1),
('staff', 'Staff', 'Accès aux opérations courantes', 1, 2),
('user', 'Utilisateur', 'Accès client standard', 1, 3)
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);

-- Donner toutes les permissions à l'admin
INSERT INTO `role_permissions` (`role_id`, `module_id`, `action_id`)
SELECT r.id, m.id, a.id
FROM `roles` r
CROSS JOIN `permission_modules` m
CROSS JOIN `permission_actions` a
WHERE r.key = 'admin' AND m.is_active = 1 AND a.is_active = 1
ON DUPLICATE KEY UPDATE `role_id` = VALUES(`role_id`);

-- Permissions pour staff (opérations courantes)
INSERT INTO `role_permissions` (`role_id`, `module_id`, `action_id`)
SELECT r.id, m.id, a.id
FROM `roles` r
CROSS JOIN `permission_modules` m
CROSS JOIN `permission_actions` a
WHERE r.key = 'staff' 
  AND m.key IN ('dashboard', 'bookings', 'games', 'rooms', 'closures', 'reviews', 'participants', 'teams', 'scanner', 'contacts')
  AND a.key IN ('view', 'create', 'edit', 'approve', 'scan')
ON DUPLICATE KEY UPDATE `role_id` = VALUES(`role_id`);

-- Permissions pour user (lecture seule limitée)
INSERT INTO `role_permissions` (`role_id`, `module_id`, `action_id`)
SELECT r.id, m.id, a.id
FROM `roles` r
CROSS JOIN `permission_modules` m
CROSS JOIN `permission_actions` a
WHERE r.key = 'user' 
  AND m.key IN ('dashboard', 'bookings', 'games')
  AND a.key = 'view'
ON DUPLICATE KEY UPDATE `role_id` = VALUES(`role_id`);
