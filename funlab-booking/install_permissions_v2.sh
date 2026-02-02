#!/bin/bash

# Script d'installation du nouveau système de permissions dynamique

echo "======================================"
echo "Installation du système de permissions"
echo "======================================"
echo ""

# Lire les informations de connexion
read -p "Hôte MySQL (default: localhost): " DB_HOST
DB_HOST=${DB_HOST:-localhost}

read -p "Base de données: " DB_NAME

read -p "Utilisateur MySQL: " DB_USER

read -sp "Mot de passe MySQL: " DB_PASS
echo ""

echo ""
echo "Connexion à la base de données..."

# Exécuter le script SQL
mysql -h "$DB_HOST" -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" < database_permissions_dynamic.sql

if [ $? -eq 0 ]; then
    echo "✅ Installation réussie!"
    echo ""
    echo "Le nouveau système de permissions est opérationnel:"
    echo ""
    echo "📊 Fonctionnalités:"
    echo "  - Détection automatique des modules (scanner les contrôleurs)"
    echo "  - Gestion dynamique des permissions via l'interface admin"
    echo "  - Plus besoin de modifier le code pour ajouter des modules"
    echo ""
    echo "🔗 Accès:"
    echo "  Interface: /admin/permissions"
    echo "  Synchronisation: Bouton 'Synchroniser les Modules' dans l'interface"
    echo ""
    echo "⚠️  Important:"
    echo "  - Remplacez 'permission_helper.php' par 'permission_helper_v2.php' dans app/Config/Autoload.php"
    echo "  - Ou supprimez l'ancien et renommez le nouveau"
    echo ""
else
    echo "❌ Erreur lors de l'installation"
    exit 1
fi
