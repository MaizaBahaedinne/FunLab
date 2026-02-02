#!/bin/bash

echo "🚀 Installation du système Contact & Newsletter"
echo "================================================"
echo ""

# Charger les variables d'environnement
if [ -f "env" ]; then
    source env
    DB_NAME="${database_default_database}"
    DB_USER="${database_default_username}"
    DB_PASS="${database_default_password}"
    DB_HOST="${database_default_hostname}"
else
    echo "⚠️  Fichier 'env' non trouvé"
    read -p "Nom de la base de données: " DB_NAME
    read -p "Utilisateur MySQL: " DB_USER
    read -sp "Mot de passe MySQL: " DB_PASS
    echo ""
    DB_HOST="localhost"
fi

echo "📊 Création des tables dans la base de données..."

# Exécuter le script SQL
mysql -h"$DB_HOST" -u"$DB_USER" -p"$DB_PASS" "$DB_NAME" < database_newsletter_contact.sql

if [ $? -eq 0 ]; then
    echo "✅ Tables créées avec succès!"
    echo ""
    echo "📋 Tables installées:"
    echo "  - newsletter_subscribers"
    echo "  - contact_messages"
    echo ""
    echo "🎉 Installation terminée!"
    echo ""
    echo "👉 Vous pouvez maintenant accéder à:"
    echo "   - Admin Contacts: https://funlab.faltaagency.com/admin/contacts"
    echo "   - Admin Newsletter: https://funlab.faltaagency.com/admin/newsletters"
    echo "   - Page About avec Newsletter: https://funlab.faltaagency.com/about"
else
    echo "❌ Erreur lors de la création des tables"
    echo "Vérifiez vos identifiants MySQL"
    exit 1
fi
