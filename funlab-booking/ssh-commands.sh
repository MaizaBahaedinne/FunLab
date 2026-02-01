#!/bin/bash
##############################################################################
# COMMANDES SSH À EXÉCUTER SUR LE SERVEUR
# Copiez/collez ces commandes une par une
##############################################################################

echo "🚀 RÉSOLUTION HTTP 500 - FUNLAB BOOKING"
echo ""

# ═══════════════════════════════════════════════════════════
# ÉTAPE 1 : ALLER DANS LE DOSSIER DU PROJET
# ═══════════════════════════════════════════════════════════
cd /home/faltaagency.com/funlab.faltaagency.com/funlab-booking
pwd

# ═══════════════════════════════════════════════════════════
# ÉTAPE 2 : CRÉER LES DOSSIERS NÉCESSAIRES
# ═══════════════════════════════════════════════════════════
mkdir -p writable/cache
mkdir -p writable/logs
mkdir -p writable/session
mkdir -p writable/uploads
mkdir -p writable/debugbar
mkdir -p ../logs
echo "✅ Dossiers créés"

# ═══════════════════════════════════════════════════════════
# ÉTAPE 3 : CORRIGER LES PERMISSIONS (CRITIQUE)
# ═══════════════════════════════════════════════════════════
# Propriétaire
chown -R falta4808:falta4808 .
echo "✅ Propriétaire défini"

# Permissions fichiers
find . -type f -exec chmod 644 {} \;
echo "✅ Permissions fichiers"

# Permissions dossiers
find . -type d -exec chmod 755 {} \;
echo "✅ Permissions dossiers"

# Writable DOIT être en 775 (LE PLUS IMPORTANT)
chmod -R 775 writable/
echo "✅ Permissions writable"

# ═══════════════════════════════════════════════════════════
# ÉTAPE 4 : VÉRIFIER LE FICHIER .ENV
# ═══════════════════════════════════════════════════════════
if [ ! -f ".env" ]; then
    echo "⚠️  Fichier .env manquant - vérifiez le transfert FTP"
else
    echo "✅ Fichier .env présent"
    # Afficher les premières lignes (sans les mots de passe)
    head -5 .env
fi

# ═══════════════════════════════════════════════════════════
# ÉTAPE 5 : NETTOYER LE CACHE
# ═══════════════════════════════════════════════════════════
rm -rf writable/cache/*
echo "✅ Cache nettoyé"

# ═══════════════════════════════════════════════════════════
# ÉTAPE 6 : REDÉMARRER LITESPEED
# ═══════════════════════════════════════════════════════════
sudo /usr/local/lsws/bin/lswsctrl restart
echo "✅ LiteSpeed redémarré"

# ═══════════════════════════════════════════════════════════
# ÉTAPE 7 : CONSULTER LES DERNIÈRES ERREURS
# ═══════════════════════════════════════════════════════════
echo ""
echo "📋 DERNIÈRES ERREURS LITESPEED:"
tail -20 ../logs/error.log 2>/dev/null || echo "Aucun log trouvé"

echo ""
echo "📋 DERNIÈRES ERREURS CODEIGNITER:"
tail -20 writable/logs/log-$(date +%Y-%m-%d).log 2>/dev/null || echo "Aucun log du jour"

echo ""
echo "═══════════════════════════════════════════════════════════"
echo "✅ CONFIGURATION TERMINÉE"
echo ""
echo "🔗 TESTEZ MAINTENANT:"
echo "   1. https://funlab.faltaagency.com/test.html"
echo "   2. https://funlab.faltaagency.com/test.php"
echo "   3. https://funlab.faltaagency.com/test-db.php"
echo "   4. https://funlab.faltaagency.com/"
echo "═══════════════════════════════════════════════════════════"
