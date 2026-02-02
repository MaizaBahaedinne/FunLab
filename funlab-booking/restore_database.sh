#!/bin/bash

# Script de restauration de la base de données FunLab
# Usage: ./restore_database.sh

set -e

SERVER="51.77.146.167"
USER="almalinux"
DB_USER="funl_FunLabBooking"
DB_PASS="FunLabBooking2026!"
DB_NAME="funl_FunLabBooking"

echo "=========================================="
echo "Restauration de la base de données FunLab"
echo "=========================================="
echo ""

# 1. Transférer les fichiers SQL vers le serveur
echo "📤 Transfert des fichiers SQL vers le serveur..."
scp database_schema.sql ${USER}@${SERVER}:/tmp/
scp database_users.sql ${USER}@${SERVER}:/tmp/
scp database_settings.sql ${USER}@${SERVER}:/tmp/
scp database_teams.sql ${USER}@${SERVER}:/tmp/
scp database_payments.sql ${USER}@${SERVER}:/tmp/
scp database_participants_names.sql ${USER}@${SERVER}:/tmp/
scp database_registration_token.sql ${USER}@${SERVER}:/tmp/
scp database_update_users.sql ${USER}@${SERVER}:/tmp/
scp database_add_user_id_payment.sql ${USER}@${SERVER}:/tmp/

echo "✅ Fichiers transférés avec succès"
echo ""

# 2. Exécuter la restauration sur le serveur
echo "🔧 Connexion au serveur et restauration de la base de données..."
ssh ${USER}@${SERVER} << 'ENDSSH'
set -e

DB_USER="funl_FunLabBooking"
DB_PASS="FunLabBooking2026!"
DB_NAME="funl_FunLabBooking"

echo "Passage en mode root..."
sudo su - << 'ROOTSSH'
set -e

DB_USER="funl_FunLabBooking"
DB_PASS="FunLabBooking2026!"
DB_NAME="funl_FunLabBooking"

echo "Vérification de MySQL..."
systemctl status mysql >/dev/null 2>&1 || systemctl status mariadb >/dev/null 2>&1
if [ $? -ne 0 ]; then
    echo "⚠️  MySQL n'est pas démarré, tentative de démarrage..."
    systemctl start mysql || systemctl start mariadb
    sleep 2
fi

echo "✅ MySQL est actif"
echo ""

echo "📊 État actuel de la base de données:"
mysql -u ${DB_USER} -p"${DB_PASS}" ${DB_NAME} -e "SHOW TABLES;" 2>&1 | head -20
echo ""

echo "⚠️  ATTENTION: Cette opération va restaurer la base de données"
echo "Appuyez sur Ctrl+C dans les 5 secondes pour annuler..."
sleep 5

echo "🔄 Restauration en cours..."
echo ""

echo "1/9 - Importation du schéma principal..."
mysql -u ${DB_USER} -p"${DB_PASS}" ${DB_NAME} < /tmp/database_schema.sql
echo "✅ Schéma importé"

echo "2/9 - Importation des utilisateurs..."
mysql -u ${DB_USER} -p"${DB_PASS}" ${DB_NAME} < /tmp/database_users.sql 2>/dev/null || echo "⚠️  Fichier users déjà appliqué ou non applicable"

echo "3/9 - Importation des paramètres..."
mysql -u ${DB_USER} -p"${DB_PASS}" ${DB_NAME} < /tmp/database_settings.sql 2>/dev/null || echo "⚠️  Fichier settings déjà appliqué ou non applicable"

echo "4/9 - Importation des équipes..."
mysql -u ${DB_USER} -p"${DB_PASS}" ${DB_NAME} < /tmp/database_teams.sql 2>/dev/null || echo "⚠️  Fichier teams déjà appliqué ou non applicable"

echo "5/9 - Importation des paiements..."
mysql -u ${DB_USER} -p"${DB_PASS}" ${DB_NAME} < /tmp/database_payments.sql 2>/dev/null || echo "⚠️  Fichier payments déjà appliqué ou non applicable"

echo "6/9 - Importation des participants..."
mysql -u ${DB_USER} -p"${DB_PASS}" ${DB_NAME} < /tmp/database_participants_names.sql 2>/dev/null || echo "⚠️  Fichier participants déjà appliqué ou non applicable"

echo "7/9 - Importation des tokens..."
mysql -u ${DB_USER} -p"${DB_PASS}" ${DB_NAME} < /tmp/database_registration_token.sql 2>/dev/null || echo "⚠️  Fichier tokens déjà appliqué ou non applicable"

echo "8/9 - Mise à jour des utilisateurs..."
mysql -u ${DB_USER} -p"${DB_PASS}" ${DB_NAME} < /tmp/database_update_users.sql 2>/dev/null || echo "⚠️  Mise à jour users déjà appliquée ou non applicable"

echo "9/9 - Mise à jour des paiements..."
mysql -u ${DB_USER} -p"${DB_PASS}" ${DB_NAME} < /tmp/database_add_user_id_payment.sql 2>/dev/null || echo "⚠️  Mise à jour payments déjà appliquée ou non applicable"

echo ""
echo "📊 État final de la base de données:"
mysql -u ${DB_USER} -p"${DB_PASS}" ${DB_NAME} -e "SHOW TABLES;"

echo ""
echo "✅ Restauration terminée avec succès!"

# Nettoyage
rm -f /tmp/database_*.sql
echo "🧹 Fichiers temporaires nettoyés"

ROOTSSH

ENDSSH

echo ""
echo "=========================================="
echo "✅ Restauration complète terminée!"
echo "=========================================="
echo ""
echo "Votre base de données a été restaurée."
echo "Vous pouvez maintenant accéder à https://funlab.faltaagency.com/"
