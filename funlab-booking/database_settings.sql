-- Table des paramètres de configuration
CREATE TABLE IF NOT EXISTS `settings` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `key` VARCHAR(100) NOT NULL UNIQUE,
    `value` TEXT NULL,
    `type` ENUM('text', 'textarea', 'number', 'boolean', 'image', 'json') DEFAULT 'text',
    `category` VARCHAR(50) DEFAULT 'general',
    `description` TEXT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `idx_key` (`key`),
    KEY `idx_category` (`category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Paramètres par défaut
INSERT INTO `settings` (`key`, `value`, `type`, `category`, `description`) VALUES
-- Général
('app_name', 'FunLab', 'text', 'general', 'Nom de l\'application'),
('app_description', 'Système de réservation d\'escape game', 'textarea', 'general', 'Description de l\'application'),
('app_logo', NULL, 'image', 'general', 'Logo de l\'application'),
('timezone', 'Africa/Tunis', 'text', 'general', 'Fuseau horaire'),
('date_format', 'd/m/Y', 'text', 'general', 'Format de date'),
('time_format', 'H:i', 'text', 'general', 'Format d\'heure'),

-- Horaires de travail
('business_hours_monday', '{"open":"09:00","close":"22:00","enabled":true}', 'json', 'hours', 'Horaires du lundi'),
('business_hours_tuesday', '{"open":"09:00","close":"22:00","enabled":true}', 'json', 'hours', 'Horaires du mardi'),
('business_hours_wednesday', '{"open":"09:00","close":"22:00","enabled":true}', 'json', 'hours', 'Horaires du mercredi'),
('business_hours_thursday', '{"open":"09:00","close":"22:00","enabled":true}', 'json', 'hours', 'Horaires du jeudi'),
('business_hours_friday', '{"open":"09:00","close":"22:00","enabled":true}', 'json', 'hours', 'Horaires du vendredi'),
('business_hours_saturday', '{"open":"10:00","close":"23:00","enabled":true}', 'json', 'hours', 'Horaires du samedi'),
('business_hours_sunday', '{"open":"10:00","close":"20:00","enabled":true}', 'json', 'hours', 'Horaires du dimanche'),

-- Email
('mail_protocol', 'smtp', 'text', 'mail', 'Protocole email (smtp, sendmail, mail)'),
('mail_smtp_host', '', 'text', 'mail', 'Serveur SMTP'),
('mail_smtp_port', '587', 'number', 'mail', 'Port SMTP'),
('mail_smtp_user', '', 'text', 'mail', 'Utilisateur SMTP'),
('mail_smtp_pass', '', 'text', 'mail', 'Mot de passe SMTP'),
('mail_smtp_crypto', 'tls', 'text', 'mail', 'Cryptage (tls, ssl)'),
('mail_from_email', 'noreply@funlab.com', 'text', 'mail', 'Email expéditeur'),
('mail_from_name', 'FunLab', 'text', 'mail', 'Nom expéditeur'),

-- Templates email
('mail_template_booking_confirmation', NULL, 'textarea', 'mail_template', 'Template confirmation de réservation'),
('mail_template_booking_reminder', NULL, 'textarea', 'mail_template', 'Template rappel de réservation'),
('mail_template_booking_cancellation', NULL, 'textarea', 'mail_template', 'Template annulation de réservation'),

-- SMS
('sms_enabled', '0', 'boolean', 'sms', 'Activer les notifications SMS'),
('sms_provider', '', 'text', 'sms', 'Fournisseur SMS'),
('sms_api_key', '', 'text', 'sms', 'Clé API SMS'),
('sms_sender_name', 'FunLab', 'text', 'sms', 'Nom de l\'expéditeur SMS'),

-- SEO
('seo_title', 'FunLab - Escape Game', 'text', 'seo', 'Titre SEO'),
('seo_description', 'Réservez votre escape game en ligne', 'textarea', 'seo', 'Description SEO'),
('seo_keywords', 'escape game, réservation, tunisie', 'textarea', 'seo', 'Mots-clés SEO'),
('seo_og_image', NULL, 'image', 'seo', 'Image Open Graph');
