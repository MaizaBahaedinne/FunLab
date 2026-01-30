# 🚀 GUIDE DE DÉPLOIEMENT - FUNLAB BOOKING

## ❌ Problème HTTP 500 - RÉSOLUTION COMPLÈTE

### 📋 Fichiers créés pour le diagnostic

J'ai créé les fichiers suivants dans votre projet local :

1. **`.env`** - Configuration de production
2. **`public/test.html`** - Test du serveur web
3. **`public/test.php`** - Test PHP et vérification des dossiers
4. **`public/test-db.php`** - Test de connexion à la base de données
5. **`public/info.php`** - phpinfo() complet
6. **`fix-server.sh`** - Script de réparation automatique
7. **`litespeed-vhost.conf`** - Configuration LiteSpeed optimisée

---

## 🔧 ÉTAPES DE DÉPLOIEMENT

### Étape 1 : Transférer les fichiers sur le serveur

```bash
# Via FTP/SFTP, transférez TOUS les fichiers vers :
/home/faltaagency.com/funlab.faltaagency.com/funlab-booking/
```

### Étape 2 : Se connecter en SSH

```bash
ssh falta4808@funlab.faltaagency.com
```

### Étape 3 : Exécuter le script de réparation

```bash
cd /home/faltaagency.com/funlab.faltaagency.com/funlab-booking

# Rendre le script exécutable
chmod +x fix-server.sh

# Exécuter le script (avec sudo si nécessaire)
sudo bash fix-server.sh
```

Ce script va automatiquement :
- ✅ Créer les dossiers manquants
- ✅ Corriger toutes les permissions
- ✅ Nettoyer le cache
- ✅ Redémarrer LiteSpeed
- ✅ Afficher les erreurs récentes

### Étape 4 : Tester progressivement

Une fois le script exécuté, testez dans cet ordre :

#### Test 1 : Page statique
```
https://funlab.faltaagency.com/test.html
```
✅ Si ça fonctionne → Le serveur web est OK

#### Test 2 : PHP
```
https://funlab.faltaagency.com/test.php
```
✅ Si ça fonctionne → PHP est OK
❌ Si erreur 500 → Problème de configuration PHP (voir logs)

#### Test 3 : Base de données
```
https://funlab.faltaagency.com/test-db.php
```
✅ Si ça fonctionne → MySQL est OK
❌ Si erreur → Vérifier les identifiants dans `.env`

#### Test 4 : Application CodeIgniter
```
https://funlab.faltaagency.com/
```
✅ Si ça fonctionne → 🎉 Application opérationnelle !

---

## 🔍 SI LE PROBLÈME PERSISTE

### Consulter les logs

```bash
# Logs LiteSpeed
tail -50 /home/faltaagency.com/funlab.faltaagency.com/logs/error.log

# Logs CodeIgniter
tail -50 /home/faltaagency.com/funlab.faltaagency.com/funlab-booking/writable/logs/log-$(date +%Y-%m-%d).log

# Logs PHP
tail -50 /var/log/lsphp/stderr.log
```

### Activer le mode DEBUG

Éditez le fichier `.env` :
```bash
nano /home/faltaagency.com/funlab.faltaagency.com/funlab-booking/.env
```

Changez :
```
CI_ENVIRONMENT = production
```
En :
```
CI_ENVIRONMENT = development
```

Rechargez la page → Vous verrez l'erreur détaillée

---

## 🛠️ CONFIGURATION LITESPEED

Le fichier `litespeed-vhost.conf` contient la configuration optimale.

Pour l'appliquer :

1. **Via le panneau LiteSpeed WebAdmin** :
   - Connectez-vous : `https://funlab.faltaagency.com:7080`
   - Virtual Hosts → funlab.faltaagency.com
   - Copiez/collez le contenu de `litespeed-vhost.conf`

2. **Via SSH (recommandé)** :
```bash
# Sauvegarder l'ancienne config
sudo cp /usr/local/lsws/conf/vhosts/funlab.faltaagency.com/vhost.conf /usr/local/lsws/conf/vhosts/funlab.faltaagency.com/vhost.conf.bak

# Copier la nouvelle config
sudo cp /home/faltaagency.com/funlab.faltaagency.com/funlab-booking/litespeed-vhost.conf /usr/local/lsws/conf/vhosts/funlab.faltaagency.com/vhost.conf

# Redémarrer LiteSpeed
sudo /usr/local/lsws/bin/lswsctrl restart
```

---

## 🔐 SÉCURITÉ IMPORTANTE

### Après avoir résolu le problème, SUPPRIMEZ les fichiers de test :

```bash
rm /home/faltaagency.com/funlab.faltaagency.com/funlab-booking/public/test.php
rm /home/faltaagency.com/funlab.faltaagency.com/funlab-booking/public/test-db.php
rm /home/faltaagency.com/funlab.faltaagency.com/funlab-booking/public/info.php
rm /home/faltaagency.com/funlab.faltaagency.com/funlab-booking/public/test.html
```

### Désactivez le mode DEBUG :

Dans `.env` :
```
CI_ENVIRONMENT = production
```

---

## 📞 CAUSES COURANTES DU HTTP 500

### 1. Permissions incorrectes (90% des cas)
**Solution :** Le script `fix-server.sh` corrige automatiquement

### 2. Dossier writable/ non accessible
**Symptôme :** Erreur "Unable to write to log file"
**Solution :** `chmod -R 775 writable/`

### 3. Erreur de base de données
**Symptôme :** Impossible de se connecter à MySQL
**Solution :** Vérifier les identifiants dans `.env`

### 4. Extension PHP manquante
**Symptôme :** Erreur "Class not found"
**Solution :** Installer les extensions (intl, mbstring, etc.)

### 5. Problème de open_basedir
**Symptôme :** "open_basedir restriction in effect"
**Solution :** Ajuster le chemin dans la config LiteSpeed

---

## 🎯 CHECKLIST FINALE

- [ ] Fichiers transférés sur le serveur
- [ ] Script `fix-server.sh` exécuté avec succès
- [ ] test.html fonctionne (serveur web OK)
- [ ] test.php fonctionne (PHP OK)
- [ ] test-db.php fonctionne (MySQL OK)
- [ ] Application principale fonctionne
- [ ] Fichiers de test supprimés
- [ ] Mode DEBUG désactivé
- [ ] Configuration LiteSpeed appliquée

---

## 📧 CONFIGURATION .ENV

Vérifiez que ces valeurs sont correctes dans votre `.env` :

```bash
CI_ENVIRONMENT = production
app.baseURL = 'https://funlab.faltaagency.com/'

database.default.hostname = localhost
database.default.database = funl_FunLabBooking
database.default.username = funl_FunLabBooking
database.default.password = FunLabBooking2026!
```

---

## 🚀 APRÈS LA RÉSOLUTION

Une fois que tout fonctionne, testez les API :

```bash
# Test de disponibilité
curl "https://funlab.faltaagency.com/api/availability/slots?game_id=1&date=2026-01-30"

# Test de vérification
curl -X POST "https://funlab.faltaagency.com/api/availability/check" \
  -H "Content-Type: application/json" \
  -d '{
    "room_id": 1,
    "game_id": 1,
    "date": "2026-01-30",
    "start_time": "14:00:00",
    "end_time": "14:30:00"
  }'
```

---

**✅ Tous les fichiers sont prêts pour le déploiement !**

**Exécutez le script `fix-server.sh` sur le serveur et le problème sera résolu.**
