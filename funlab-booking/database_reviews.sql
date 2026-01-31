-- Table des avis/reviews pour les jeux
CREATE TABLE IF NOT EXISTS `game_reviews` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `game_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `rating` tinyint(1) NOT NULL,
  `comment` text NOT NULL,
  `is_approved` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `game_id` (`game_id`),
  KEY `user_id` (`user_id`),
  KEY `is_approved` (`is_approved`),
  CONSTRAINT `game_reviews_game_fk` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`) ON DELETE CASCADE,
  CONSTRAINT `game_reviews_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insérer quelques exemples d'avis approuvés pour tester
INSERT INTO `game_reviews` (`game_id`, `user_id`, `name`, `email`, `rating`, `comment`, `is_approved`, `created_at`) VALUES
(1, NULL, 'Ahmed Mansour', 'ahmed.mansour@example.com', 5, 'Expérience incroyable ! Les énigmes étaient bien pensées et l''ambiance était immersive. Je recommande vivement !', 1, '2026-01-25 14:30:00'),
(1, NULL, 'Amira Ben Salem', 'amira.bensalem@example.com', 4, 'Très bon jeu d''évasion. Quelques énigmes un peu difficiles mais c''était amusant en équipe.', 1, '2026-01-20 16:45:00'),
(1, NULL, 'Youssef Trabelsi', 'youssef.trabelsi@example.com', 5, 'Meilleur escape game que j''ai fait ! Le game master était excellent et nous a bien guidés.', 1, '2026-01-15 11:20:00'),
(2, NULL, 'Leila Gharbi', 'leila.gharbi@example.com', 5, 'Magnifique expérience ! L''équipe est très professionnelle et l''ambiance est top !', 1, '2026-01-18 10:15:00'),
(2, NULL, 'Mehdi Karoui', 'mehdi.karoui@example.com', 4, 'Super moment entre amis. Les énigmes sont bien pensées et le décor est soigné.', 1, '2026-01-22 15:30:00'),
(3, NULL, 'Yasmine Slimani', 'yasmine.slimani@example.com', 5, 'Je recommande à 100% ! C''est notre deuxième escape game ici et on adore !', 1, '2026-01-26 18:20:00');
