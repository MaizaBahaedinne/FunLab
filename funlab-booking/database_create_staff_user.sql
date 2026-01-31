-- Créer un utilisateur STAFF pour tester les permissions
-- Mot de passe: Staff2026!

INSERT INTO `users` (
    `username`, 
    `email`, 
    `password`, 
    `first_name`, 
    `last_name`, 
    `role`, 
    `is_active`, 
    `email_verified`, 
    `auth_provider`,
    `created_at`
) VALUES (
    'staff_test',
    'staff@funlab.com',
    '$2y$10$vXE0K5BZm7YLzLHYn8sUGeQDQMPvV6tJZxHqKjKvAX9qYqZBZvJHK', -- Staff2026!
    'Mohamed',
    'Staff',
    'staff',
    1,
    1,
    'native',
    NOW()
);

-- Vérifier que l'utilisateur a été créé
SELECT id, username, email, role, is_active FROM users WHERE email = 'staff@funlab.com';
