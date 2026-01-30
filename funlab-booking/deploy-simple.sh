#!/bin/bash
# DÉPLOIEMENT ULTRA-SIMPLE
# Ce script fait TOUT automatiquement

echo "🚀 Déploiement de FunLab Booking..."

# Transfert des fichiers
echo "📤 Transfert des fichiers..."
scp .env public/test.* public/info.php ssh-commands.sh public/.htaccess \
    falta4808@funlab.faltaagency.com:/home/faltaagency.com/funlab.faltaagency.com/funlab-booking/

# Exécution à distance
echo "⚙️  Configuration du serveur..."
ssh falta4808@funlab.faltaagency.com "cd /home/faltaagency.com/funlab.faltaagency.com/funlab-booking && bash ssh-commands.sh"

# Tests
echo "🧪 Tests..."
curl -I https://funlab.faltaagency.com/

echo "✅ Terminé ! Ouvrez : https://funlab.faltaagency.com/"
