-- Migration pour ajouter first_name et last_name dans participants
-- Date: 2026-01-24

-- Ajouter les colonnes first_name et last_name
ALTER TABLE participants 
ADD COLUMN first_name VARCHAR(100) NULL AFTER name,
ADD COLUMN last_name VARCHAR(100) NULL AFTER first_name;

-- Migrer les données existantes de name vers first_name/last_name
UPDATE participants 
SET first_name = SUBSTRING_INDEX(name, ' ', 1),
    last_name = SUBSTRING_INDEX(name, ' ', -1)
WHERE name IS NOT NULL;

-- Optionnel: Supprimer la colonne name si vous ne voulez garder que first_name/last_name
-- ALTER TABLE participants DROP COLUMN name;
