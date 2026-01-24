-- Migration: Ajouter user_id et payment_method à la table bookings
-- Date: 2026-01-24
-- Description: Permet de lier une réservation à un compte utilisateur et d'enregistrer la méthode de paiement

-- Vérifier et ajouter la colonne user_id (relation optionnelle avec users)
SET @dbname = DATABASE();
SET @tablename = 'bookings';
SET @columnname = 'user_id';
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE
      (table_name = @tablename)
      AND (table_schema = @dbname)
      AND (column_name = @columnname)
  ) > 0,
  'SELECT 1',
  CONCAT('ALTER TABLE `', @tablename, '` ADD COLUMN `user_id` INT UNSIGNED NULL AFTER `id`, ADD INDEX `idx_user_id` (`user_id`), ADD CONSTRAINT `fk_bookings_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;')
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- Vérifier et ajouter la colonne payment_method
SET @columnname = 'payment_method';
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE
      (table_name = @tablename)
      AND (table_schema = @dbname)
      AND (column_name = @columnname)
  ) > 0,
  'SELECT 1',
  CONCAT('ALTER TABLE `', @tablename, '` ADD COLUMN `payment_method` ENUM(''card'', ''cash'', ''stripe'', ''bank_transfer'') NULL DEFAULT ''cash'' AFTER `total_price`;')
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- Note: 
-- - user_id est NULL si la réservation est faite sans compte (guest)
-- - user_id est renseigné si l'utilisateur est connecté lors de la réservation
-- - Cela permet de tracer toutes les réservations d'un utilisateur
-- - Les données client (name, email, phone) restent dans bookings pour les cas sans compte
-- - Cette migration vérifie si les colonnes existent avant de les ajouter (idempotente)

