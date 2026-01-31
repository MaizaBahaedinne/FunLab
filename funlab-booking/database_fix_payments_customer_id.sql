-- Permettre customer_id NULL dans la table payments
-- pour supporter les réservations sans utilisateur enregistré
ALTER TABLE `payments` MODIFY COLUMN `customer_id` INT(10) UNSIGNED NULL;
