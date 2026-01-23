CREATE TABLE IF NOT EXISTS `users` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(100) NULL,
    `email` VARCHAR(255) NOT NULL,
    `password` VARCHAR(255) NULL COMMENT 'NULL pour OAuth users',
    `first_name` VARCHAR(100) NULL,
    `last_name` VARCHAR(100) NULL,
    `phone` VARCHAR(20) NULL,
    `role` ENUM('customer', 'staff', 'admin') DEFAULT 'customer',
    `auth_provider` ENUM('native', 'google', 'facebook') DEFAULT 'native',
    `provider_id` VARCHAR(255) NULL COMMENT 'ID from OAuth provider',
    `avatar` VARCHAR(255) NULL,
    `email_verified` TINYINT(1) DEFAULT 0,
    `is_active` TINYINT(1) DEFAULT 1,
    `last_login` DATETIME NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_email` (`email`),
    UNIQUE KEY `unique_provider` (`auth_provider`, `provider_id`),
    INDEX `idx_role` (`role`),
    INDEX `idx_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table pour les tokens de réinitialisation de mot de passe
CREATE TABLE IF NOT EXISTS `password_resets` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `email` VARCHAR(255) NOT NULL,
    `token` VARCHAR(255) NOT NULL,
    `expires_at` DATETIME NOT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_email` (`email`),
    INDEX `idx_token` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Créer un admin par défaut
INSERT INTO `users` (`username`, `email`, `password`, `first_name`, `last_name`, `role`, `email_verified`, `is_active`)
VALUES ('admin', 'admin@funlab.tn', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin', 'FunLab', 'admin', 1, 1);
-- Mot de passe : password (à changer en production !)
