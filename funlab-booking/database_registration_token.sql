-- Migration pour ajouter le système d'auto-inscription
-- Date: 2026-01-24

-- Ajouter le champ registration_token dans bookings
SET @col_exists = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
                   WHERE TABLE_SCHEMA = DATABASE() 
                   AND TABLE_NAME = 'bookings' 
                   AND COLUMN_NAME = 'registration_token');

SET @sql = IF(@col_exists = 0,
    'ALTER TABLE bookings ADD COLUMN registration_token VARCHAR(64) UNIQUE NULL AFTER qr_code',
    'SELECT "Column registration_token already exists" AS message');

PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Ajouter un index sur registration_token
SET @idx_exists = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.STATISTICS 
                   WHERE TABLE_SCHEMA = DATABASE() 
                   AND TABLE_NAME = 'bookings' 
                   AND INDEX_NAME = 'idx_registration_token');

SET @sql = IF(@idx_exists = 0,
    'ALTER TABLE bookings ADD INDEX idx_registration_token (registration_token)',
    'SELECT "Index idx_registration_token already exists" AS message');

PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Générer des tokens pour les réservations existantes (optionnel)
UPDATE bookings 
SET registration_token = MD5(CONCAT(id, UNIX_TIMESTAMP(), RAND()))
WHERE registration_token IS NULL 
AND status IN ('confirmed', 'pending');
