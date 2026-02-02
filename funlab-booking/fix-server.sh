#!/bin/bash

##############################################################################
# SCRIPT DE DIAGNOSTIC ET RÉPARATION - FUNLAB BOOKING
# Ce script doit être exécuté sur le serveur de production
##############################################################################

echo "═══════════════════════════════════════════════════════════"
echo "  🔧 DIAGNOSTIC ET RÉPARATION - FUNLAB BOOKING"
echo "═══════════════════════════════════════════════════════════"
echo ""

# Couleurs
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Variables
PROJECT_ROOT="/home/faltaagency.com/funlab.faltaagency.com/funlab-booking"
OWNER="falta4808"
GROUP="falta4808"

echo "📂 Répertoire du projet: $PROJECT_ROOT"
echo ""

# Fonction pour afficher les résultats
check_result() {
    if [ $? -eq 0 ]; then
        echo -e "${GREEN}✅ $1${NC}"
    else
        echo -e "${RED}❌ $1${NC}"
    fi
}

##############################################################################
# 1. VÉRIFICATION DES DOSSIERS
##############################################################################
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "1️⃣  VÉRIFICATION DES DOSSIERS"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""

cd $PROJECT_ROOT

# Créer les dossiers writable s'ils n'existent pas
mkdir -p writable/{cache,logs,session,uploads,debugbar}
check_result "Création des dossiers writable"

# Créer le dossier logs pour LiteSpeed
mkdir -p ../logs
check_result "Création du dossier logs"

echo ""

##############################################################################
# 2. CORRECTION DES PERMISSIONS
##############################################################################
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "2️⃣  CORRECTION DES PERMISSIONS"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""

# Propriétaire
echo "🔧 Attribution du propriétaire $OWNER:$GROUP..."
chown -R $OWNER:$GROUP $PROJECT_ROOT
check_result "Propriétaire défini"

# Permissions de base
echo "🔧 Définition des permissions de base..."
find $PROJECT_ROOT -type f -exec chmod 644 {} \;
check_result "Permissions fichiers (644)"

find $PROJECT_ROOT -type d -exec chmod 755 {} \;
check_result "Permissions dossiers (755)"

# Permissions writable (CRITIQUE)
echo "🔧 Permissions writable (775)..."
chmod -R 775 $PROJECT_ROOT/writable
check_result "Permissions writable"

# Permissions logs
chmod -R 775 $PROJECT_ROOT/../logs
check_result "Permissions logs"

echo ""

##############################################################################
# 3. VÉRIFICATION DE LA CONFIGURATION
##############################################################################
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "3️⃣  VÉRIFICATION DE LA CONFIGURATION"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""

# Vérifier .env
if [ -f "$PROJECT_ROOT/.env" ]; then
    echo -e "${GREEN}✅ Fichier .env trouvé${NC}"
else
    echo -e "${YELLOW}⚠️  Fichier .env non trouvé - Création en cours...${NC}"
    if [ -f "$PROJECT_ROOT/env.example" ]; then
        cp $PROJECT_ROOT/env.example $PROJECT_ROOT/.env
        chmod 644 $PROJECT_ROOT/.env
        chown $OWNER:$GROUP $PROJECT_ROOT/.env
        echo -e "${GREEN}✅ Fichier .env créé depuis env.example${NC}"
        echo -e "${YELLOW}⚠️  IMPORTANT: Éditez $PROJECT_ROOT/.env avec vos identifiants${NC}"
    fi
fi

# Vérifier public/.htaccess
if [ -f "$PROJECT_ROOT/public/.htaccess" ]; then
    echo -e "${GREEN}✅ Fichier .htaccess trouvé${NC}"
else
    echo -e "${YELLOW}⚠️  Création du fichier .htaccess...${NC}"
    cat > $PROJECT_ROOT/public/.htaccess << 'EOF'
# Disable directory browsing
Options -Indexes

# Rewrite engine
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /

    # Redirect to HTTPS
    RewriteCond %{HTTPS} off
    RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

    # Remove index.php
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ index.php/$1 [L]
</IfModule>

# Deny access to sensitive files
<FilesMatch "^\.">
    Require all denied
</FilesMatch>

<FilesMatch "(^#.*#|\.(bak|conf|dist|fla|in[ci]|log|orig|psd|sh|sql|sw[op])|~)$">
    Require all denied
</FilesMatch>
EOF
    check_result "Fichier .htaccess créé"
fi

echo ""

##############################################################################
# 4. TEST PHP
##############################################################################
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "4️⃣  TEST PHP"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""

echo "🔍 Version PHP:"
/usr/local/lsws/lsphp85/bin/php -v | head -n 1

echo ""
echo "🔍 Extensions PHP requises:"
for ext in intl mbstring json mysqli xml curl gd zip; do
    if /usr/local/lsws/lsphp85/bin/php -m | grep -q "^$ext$"; then
        echo -e "  ${GREEN}✅ $ext${NC}"
    else
        echo -e "  ${RED}❌ $ext (MANQUANT)${NC}"
    fi
done

echo ""

##############################################################################
# 5. NETTOYAGE DU CACHE
##############################################################################
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "5️⃣  NETTOYAGE DU CACHE"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""

echo "🧹 Nettoyage du cache CodeIgniter..."
rm -rf $PROJECT_ROOT/writable/cache/*
check_result "Cache nettoyé"

echo "🧹 Nettoyage des logs anciens..."
find $PROJECT_ROOT/writable/logs/ -name "*.log" -mtime +30 -delete 2>/dev/null
check_result "Logs anciens supprimés"

echo ""

##############################################################################
# 6. REDÉMARRAGE DU SERVEUR
##############################################################################
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "6️⃣  REDÉMARRAGE DU SERVEUR LITESPEED"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""

echo "🔄 Redémarrage de LiteSpeed..."
/usr/local/lsws/bin/lswsctrl restart
check_result "LiteSpeed redémarré"

echo ""

##############################################################################
# 7. VÉRIFICATION DES LOGS
##############################################################################
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "7️⃣  DERNIÈRES ERREURS"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""

echo "📋 Dernières erreurs LiteSpeed:"
if [ -f "$PROJECT_ROOT/../logs/error.log" ]; then
    tail -10 $PROJECT_ROOT/../logs/error.log
else
    echo "Aucun log trouvé"
fi

echo ""
echo "📋 Dernières erreurs CodeIgniter:"
LATEST_LOG=$(ls -t $PROJECT_ROOT/writable/logs/log-*.log 2>/dev/null | head -1)
if [ -f "$LATEST_LOG" ]; then
    tail -10 "$LATEST_LOG"
else
    echo "Aucun log trouvé"
fi

echo ""

##############################################################################
# 8. RÉSUMÉ
##############################################################################
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "8️⃣  RÉSUMÉ ET TESTS"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""

echo "✅ Diagnostic et réparation terminés!"
echo ""
echo "🔗 Tests à effectuer:"
echo "   1. https://funlab.faltaagency.com/test.html (page statique)"
echo "   2. https://funlab.faltaagency.com/test.php (test PHP)"
echo "   3. https://funlab.faltaagency.com/test-db.php (test BDD)"
echo "   4. https://funlab.faltaagency.com/ (application)"
echo ""
echo "📊 Commandes de monitoring:"
echo "   • tail -f $PROJECT_ROOT/writable/logs/log-\$(date +%Y-%m-%d).log"
echo "   • tail -f $PROJECT_ROOT/../logs/error.log"
echo ""
echo "═══════════════════════════════════════════════════════════"
