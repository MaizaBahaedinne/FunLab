-- Mise à jour de la table users pour supporter l'authentification complète
-- Exécuter ce script pour ajouter les colonnes OAuth et profil

ALTER TABLE `users` 
ADD COLUMN `first_name` VARCHAR(100) NULL AFTER `password`,
ADD COLUMN `last_name` VARCHAR(100) NULL AFTER `first_name`,
ADD COLUMN `phone` VARCHAR(20) NULL AFTER `last_name`,
ADD COLUMN `auth_provider` ENUM('native', 'google', 'facebook') DEFAULT 'native' AFTER `role`,
ADD COLUMN `provider_id` VARCHAR(255) NULL AFTER `auth_provider`,
ADD COLUMN `avatar` VARCHAR(255) NULL AFTER `provider_id`,
ADD COLUMN `email_verified` TINYINT(1) DEFAULT 0 AFTER `avatar`,
ADD COLUMN `is_active` TINYINT(1) DEFAULT 1 AFTER `email_verified`,
ADD COLUMN `last_login` DATETIME NULL AFTER `is_active`,
MODIFY COLUMN `password` VARCHAR(255) NULL COMMENT 'NULL pour OAuth users',
MODIFY COLUMN `role` ENUM('customer', 'staff', 'admin') DEFAULT 'customer',
MODIFY COLUMN `status` ENUM('active','inactive') NOT NULL DEFAULT 'active',
ADD UNIQUE KEY `unique_provider` (`auth_provider`, `provider_id`);

-- Mettre à jour l'utilisateur admin existant
UPDATE `users` 
SET `first_name` = 'Admin', 
    `last_name` = 'FunLab',
    `email_verified` = 1,
    `is_active` = 1,
    `auth_provider` = 'native'
WHERE `email` = 'admin@funlab.tn';
