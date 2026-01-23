-- ============================================================
-- SYSTÈME DE PAIEMENT - FunLab Booking
-- ============================================================

-- Table des paiements
CREATE TABLE IF NOT EXISTS `payments` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `booking_id` INT UNSIGNED NOT NULL,
    `customer_id` INT UNSIGNED NOT NULL,
    `amount` DECIMAL(10,2) NOT NULL,
    `currency` VARCHAR(3) DEFAULT 'TND',
    `payment_method` ENUM('stripe', 'cash', 'card', 'bank_transfer', 'paypal') NOT NULL,
    `payment_type` ENUM('full', 'deposit', 'balance') DEFAULT 'full',
    `status` ENUM('pending', 'completed', 'failed', 'refunded', 'cancelled') DEFAULT 'pending',
    `transaction_id` VARCHAR(255) NULL COMMENT 'ID de la transaction Stripe/PayPal',
    `stripe_payment_intent` VARCHAR(255) NULL,
    `stripe_charge_id` VARCHAR(255) NULL,
    `paid_at` DATETIME NULL,
    `refunded_at` DATETIME NULL,
    `refund_amount` DECIMAL(10,2) NULL,
    `refund_reason` TEXT NULL,
    `metadata` JSON NULL COMMENT 'Données supplémentaires (receipt_url, etc.)',
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_booking` (`booking_id`),
    KEY `idx_customer` (`customer_id`),
    KEY `idx_status` (`status`),
    KEY `idx_transaction` (`transaction_id`),
    CONSTRAINT `fk_payment_booking` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_payment_customer` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des factures
CREATE TABLE IF NOT EXISTS `invoices` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `invoice_number` VARCHAR(50) NOT NULL UNIQUE COMMENT 'Format: INV-2026-00001',
    `booking_id` INT UNSIGNED NOT NULL,
    `customer_id` INT UNSIGNED NOT NULL,
    `customer_name` VARCHAR(255) NOT NULL,
    `customer_email` VARCHAR(255) NOT NULL,
    `customer_phone` VARCHAR(20) NULL,
    `customer_address` TEXT NULL,
    `amount_subtotal` DECIMAL(10,2) NOT NULL,
    `amount_tax` DECIMAL(10,2) DEFAULT 0.00,
    `amount_discount` DECIMAL(10,2) DEFAULT 0.00,
    `amount_total` DECIMAL(10,2) NOT NULL,
    `tax_rate` DECIMAL(5,2) DEFAULT 0.00 COMMENT 'Pourcentage TVA',
    `discount_code` VARCHAR(50) NULL,
    `items` JSON NOT NULL COMMENT 'Liste des items de la facture',
    `status` ENUM('draft', 'sent', 'paid', 'cancelled', 'refunded') DEFAULT 'draft',
    `issued_at` DATETIME NULL,
    `due_at` DATETIME NULL,
    `paid_at` DATETIME NULL,
    `pdf_path` VARCHAR(255) NULL,
    `notes` TEXT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_invoice_number` (`invoice_number`),
    KEY `idx_booking` (`booking_id`),
    KEY `idx_customer` (`customer_id`),
    KEY `idx_status` (`status`),
    CONSTRAINT `fk_invoice_booking` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_invoice_customer` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des codes promo
CREATE TABLE IF NOT EXISTS `promo_codes` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `code` VARCHAR(50) NOT NULL UNIQUE,
    `description` TEXT NULL,
    `discount_type` ENUM('percentage', 'fixed') NOT NULL,
    `discount_value` DECIMAL(10,2) NOT NULL,
    `min_amount` DECIMAL(10,2) NULL COMMENT 'Montant minimum de commande',
    `max_discount` DECIMAL(10,2) NULL COMMENT 'Réduction maximale (pour pourcentage)',
    `usage_limit` INT NULL COMMENT 'Nombre max d\'utilisations',
    `usage_count` INT DEFAULT 0,
    `valid_from` DATETIME NULL,
    `valid_until` DATETIME NULL,
    `is_active` TINYINT(1) DEFAULT 1,
    `applicable_games` JSON NULL COMMENT 'IDs des jeux concernés (null = tous)',
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_code` (`code`),
    KEY `idx_active` (`is_active`),
    KEY `idx_validity` (`valid_from`, `valid_until`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des utilisations de codes promo
CREATE TABLE IF NOT EXISTS `promo_code_usage` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `promo_code_id` INT UNSIGNED NOT NULL,
    `booking_id` INT UNSIGNED NOT NULL,
    `customer_id` INT UNSIGNED NOT NULL,
    `discount_amount` DECIMAL(10,2) NOT NULL,
    `used_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_promo_code` (`promo_code_id`),
    KEY `idx_booking` (`booking_id`),
    KEY `idx_customer` (`customer_id`),
    CONSTRAINT `fk_usage_promo` FOREIGN KEY (`promo_code_id`) REFERENCES `promo_codes` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_usage_booking` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_usage_customer` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Ajouter colonnes pricing dans la table games si elles n'existent pas
ALTER TABLE `games` 
ADD COLUMN IF NOT EXISTS `price` DECIMAL(10,2) NOT NULL DEFAULT 0.00 COMMENT 'Prix par session',
ADD COLUMN IF NOT EXISTS `price_per_person` DECIMAL(10,2) NULL COMMENT 'Prix par personne (optionnel)',
ADD COLUMN IF NOT EXISTS `deposit_required` TINYINT(1) DEFAULT 0 COMMENT 'Acompte obligatoire',
ADD COLUMN IF NOT EXISTS `deposit_percentage` DECIMAL(5,2) DEFAULT 30.00 COMMENT 'Pourcentage acompte';

-- Ajouter colonnes payment dans la table bookings
ALTER TABLE `bookings` 
ADD COLUMN IF NOT EXISTS `total_price` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
ADD COLUMN IF NOT EXISTS `paid_amount` DECIMAL(10,2) DEFAULT 0.00,
ADD COLUMN IF NOT EXISTS `remaining_amount` DECIMAL(10,2) DEFAULT 0.00,
ADD COLUMN IF NOT EXISTS `payment_status` ENUM('unpaid', 'partial', 'paid', 'refunded') DEFAULT 'unpaid',
ADD COLUMN IF NOT EXISTS `payment_method` VARCHAR(50) NULL,
ADD COLUMN IF NOT EXISTS `promo_code_id` INT UNSIGNED NULL,
ADD COLUMN IF NOT EXISTS `discount_amount` DECIMAL(10,2) DEFAULT 0.00,
ADD KEY IF NOT EXISTS `idx_payment_status` (`payment_status`);

-- Codes promo par défaut
INSERT INTO `promo_codes` (`code`, `description`, `discount_type`, `discount_value`, `min_amount`, `max_discount`, `usage_limit`, `valid_from`, `valid_until`, `is_active`) 
VALUES 
('WELCOME10', 'Réduction 10% pour nouveaux clients', 'percentage', 10.00, 50.00, 20.00, NULL, NOW(), DATE_ADD(NOW(), INTERVAL 1 YEAR), 1),
('SUMMER2026', 'Offre d\'été -15%', 'percentage', 15.00, 100.00, 50.00, 100, NOW(), '2026-09-30 23:59:59', 1),
('VIP50', 'Réduction fixe 50 TND', 'fixed', 50.00, 200.00, NULL, 50, NOW(), DATE_ADD(NOW(), INTERVAL 6 MONTH), 1);

-- Mettre à jour les prix des jeux existants
UPDATE `games` SET 
    `price` = 80.00,
    `price_per_person` = 20.00,
    `deposit_required` = 1,
    `deposit_percentage` = 30.00
WHERE `price` = 0.00;

-- Vue : Statistiques financières
CREATE OR REPLACE VIEW `v_financial_stats` AS
SELECT 
    DATE(p.paid_at) as payment_date,
    COUNT(DISTINCT p.id) as total_payments,
    COUNT(DISTINCT p.booking_id) as total_bookings,
    SUM(CASE WHEN p.status = 'completed' THEN p.amount ELSE 0 END) as total_revenue,
    SUM(CASE WHEN p.payment_method = 'stripe' THEN p.amount ELSE 0 END) as stripe_revenue,
    SUM(CASE WHEN p.payment_method = 'cash' THEN p.amount ELSE 0 END) as cash_revenue,
    AVG(CASE WHEN p.status = 'completed' THEN p.amount ELSE NULL END) as avg_payment_amount
FROM `payments` p
WHERE p.paid_at IS NOT NULL
GROUP BY DATE(p.paid_at)
ORDER BY payment_date DESC;

-- Vue : Paiements avec détails
CREATE OR REPLACE VIEW `v_payments_full` AS
SELECT 
    p.id as payment_id,
    p.amount,
    p.currency,
    p.payment_method,
    p.payment_type,
    p.status as payment_status,
    p.transaction_id,
    p.paid_at,
    b.id as booking_id,
    b.booking_date,
    b.customer_name,
    b.total_price as booking_total,
    b.payment_status as booking_payment_status,
    g.name as game_name,
    u.email as customer_email
FROM `payments` p
JOIN `bookings` b ON p.booking_id = b.id
JOIN `games` g ON b.game_id = g.id
JOIN `users` u ON p.customer_id = u.id
ORDER BY p.created_at DESC;
