-- Table pour la gestion des pages (comme WordPress)
CREATE TABLE IF NOT EXISTS `pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL UNIQUE,
  `content` longtext NOT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `featured_image` varchar(500) DEFAULT NULL,
  `template` varchar(50) DEFAULT 'default',
  `status` enum('draft','published') DEFAULT 'draft',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Settings pour les options du thème
INSERT INTO settings (`key`, `value`, `type`, `category`, `description`) VALUES
-- Branding
('site_logo', '/assets/images/logo.png', 'text', 'branding', 'URL du logo'),
('logo_width', '60', 'number', 'branding', 'Largeur du logo (px)'),
('logo_height', '60', 'number', 'branding', 'Hauteur du logo (px)'),
('site_favicon', '/assets/images/favicon.ico', 'text', 'branding', 'Favicon du site'),

-- Couleurs
('color_primary', '#ff6b35', 'text', 'colors', 'Couleur primaire'),
('color_secondary', '#004e89', 'text', 'colors', 'Couleur secondaire'),
('color_dark', '#1a1a1a', 'text', 'colors', 'Couleur sombre'),
('color_light', '#f7f7f7', 'text', 'colors', 'Couleur claire'),
('color_text', '#333333', 'text', 'colors', 'Couleur du texte'),
('color_link', '#ff6b35', 'text', 'colors', 'Couleur des liens'),

-- Typographie
('font_heading', 'Oswald', 'text', 'typography', 'Police des titres'),
('font_body', 'Roboto', 'text', 'typography', 'Police du texte'),
('font_size_base', '16', 'number', 'typography', 'Taille de police de base (px)'),

-- Header
('header_sticky', '1', 'boolean', 'header', 'En-tête fixe'),
('header_show_topbar', '1', 'boolean', 'header', 'Afficher la barre du haut'),
('header_topbar_phone', '', 'text', 'header', 'Téléphone (barre du haut)'),
('header_topbar_email', '', 'text', 'header', 'Email (barre du haut)'),

-- Footer
('footer_show_social', '1', 'boolean', 'footer', 'Afficher les réseaux sociaux'),
('footer_columns', '4', 'number', 'footer', 'Nombre de colonnes'),
('footer_copyright', '© {year} FunLab Tunisie. Tous droits réservés.', 'text', 'footer', 'Texte copyright')

ON DUPLICATE KEY UPDATE 
    `value` = VALUES(`value`),
    `description` = VALUES(`description`);
