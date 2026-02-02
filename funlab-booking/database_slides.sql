-- Table pour le slider de la page d'accueil
CREATE TABLE IF NOT EXISTS `slides` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `subtitle` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(500) NOT NULL,
  `button_text` varchar(100) DEFAULT NULL,
  `button_link` varchar(500) DEFAULT NULL,
  `button_style` varchar(50) DEFAULT 'primary',
  `text_color` varchar(7) DEFAULT '#ffffff',
  `overlay_opacity` tinyint(1) DEFAULT 6,
  `order` int(11) DEFAULT 0,
  `active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `active` (`active`),
  KEY `order` (`order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Slides par défaut
INSERT INTO `slides` (`title`, `subtitle`, `description`, `image`, `button_text`, `button_link`, `button_style`, `text_color`, `overlay_opacity`, `order`, `active`) VALUES
('Bienvenue chez FunLab', 'Escape Game • Réalité Virtuelle • Laser Game', 'Vivez des expériences inoubliables avec vos amis et votre famille', '/assets/images/slides/slide1.jpg', 'Réserver Maintenant', '/booking', 'primary', '#ffffff', 6, 1, 1),
('Nos Jeux d\'Escape Game', 'Plus de 10 salles thématiques', 'Résolvez des énigmes et évadez-vous en équipe', '/assets/images/slides/slide2.jpg', 'Découvrir', '/games', 'light', '#ffffff', 7, 2, 1),
('Réalité Virtuelle', 'Plongez dans un monde virtuel', 'Expérience VR immersive avec les dernières technologies', '/assets/images/slides/slide3.jpg', 'En Savoir Plus', '/games', 'primary', '#ffffff', 6, 3, 1);
