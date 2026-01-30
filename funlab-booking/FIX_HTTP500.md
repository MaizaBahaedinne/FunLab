# 🚨 ERREUR HTTP 500 - RÉSOLUTION IMMÉDIATE

## 🎯 SOLUTION RAPIDE (3 ÉTAPES)

### 1️⃣ Transférer les fichiers via FTP

Connectez-vous à votre serveur FTP et allez dans :
```
/home/faltaagency.com/funlab.faltaagency.com/funlab-booking/
```

Transférez ces fichiers depuis votre Mac :
- ✅ `.env`
- ✅ `public/test.html`
- ✅ `public/test.php`
- ✅ `public/test-db.php`
- ✅ `public/info.php`
- ✅ `ssh-commands.sh`

### 2️⃣ Connectez-vous en SSH et exécutez

```bash
ssh falta4808@funlab.faltaagency.com
cd /home/faltaagency.com/funlab.faltaagency.com/funlab-booking
bash ssh-commands.sh
```

### 3️⃣ Testez

Ouvrez dans votre navigateur :
1. https://funlab.faltaagency.com/test.html
2. https://funlab.faltaagency.com/test.php
3. https://funlab.faltaagency.com/test-db.php
4. https://funlab.faltaagency.com/ ✅

---

## 📋 Si vous préférez copier/coller les commandes une par une

```bash
cd /home/faltaagency.com/funlab.faltaagency.com/funlab-booking
mkdir -p writable/{cache,logs,session,uploads,debugbar}
chown -R falta4808:falta4808 .
find . -type f -exec chmod 644 {} \;
find . -type d -exec chmod 755 {} \;
chmod -R 775 writable/
rm -rf writable/cache/*
sudo /usr/local/lsws/bin/lswsctrl restart
```

---

## 🔍 Voir les erreurs détaillées

```bash
# Logs LiteSpeed
tail -50 /home/faltaagency.com/funlab.faltaagency.com/logs/error.log

# Logs CodeIgniter
tail -50 /home/faltaagency.com/funlab.faltaagency.com/funlab-booking/writable/logs/log-$(date +%Y-%m-%d).log
```

---

## 📚 Documentation complète

- **SOLUTION_HTTP500.txt** → Guide visuel étape par étape
- **DEPLOYMENT_GUIDE.md** → Documentation complète
- **QUICK_START.md** → Guide de démarrage

---

## ✅ Une fois résolu, supprimez les fichiers de test

```bash
cd /home/faltaagency.com/funlab.faltaagency.com/funlab-booking/public
rm test.php test-db.php info.php test.html
```

---

**🚀 C'est tout ! Votre site devrait maintenant fonctionner.**
