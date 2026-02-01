-- Ajouter les paramètres de logo dans la table settings
INSERT INTO settings (`key`, `value`, `type`, `description`) VALUES
('site_logo', '/assets/images/logo.png', 'text', 'URL du logo du site'),
('logo_width', '150', 'number', 'Largeur du logo en pixels'),
('logo_height', '50', 'number', 'Hauteur du logo en pixels')
ON DUPLICATE KEY UPDATE 
    `value` = VALUES(`value`),
    `description` = VALUES(`description`);
