<h1><i class="bi bi-tools text-primary"></i> Maintenance & Sauvegarde</h1>

## 💾 Sauvegardes

### Types de sauvegardes

#### 1. Base de données
**Fréquence** : Quotidienne (automatique)

**Commande manuelle** :
```bash
mysqldump -u root -p funl_FunLabBooking > backup_$(date +%Y%m%d_%H%M%S).sql
```

**Avec compression** :
```bash
mysqldump -u root -p funl_FunLabBooking | gzip > backup_$(date +%Y%m%d).sql.gz
```

#### 2. Fichiers uploads
**Dossier** : `/public/uploads/`

```bash
tar -czf uploads_backup_$(date +%Y%m%d).tar.gz public/uploads/
```

#### 3. Sauvegarde complète
```bash
# Exclure les dossiers non nécessaires
tar --exclude='vendor' --exclude='writable' --exclude='.git' \
    -czf funlab_full_backup_$(date +%Y%m%d).tar.gz .
```

### Automatisation (Cron)

#### Script de sauvegarde
Créez `/scripts/backup.sh` :
```bash
#!/bin/bash
BACKUP_DIR="/backups/funlab"
DATE=$(date +%Y%m%d)

# Créer dossier si nécessaire
mkdir -p $BACKUP_DIR

# Backup DB
mysqldump -u root -pMOT_DE_PASSE funl_FunLabBooking | gzip > $BACKUP_DIR/db_$DATE.sql.gz

# Backup uploads
tar -czf $BACKUP_DIR/uploads_$DATE.tar.gz public/uploads/

# Garder seulement les 30 derniers jours
find $BACKUP_DIR -type f -mtime +30 -delete

echo "Backup terminé : $DATE"
```

#### Crontab
```bash
# Ouvrir crontab
crontab -e

# Ajouter (backup quotidien à 2h du matin)
0 2 * * * /path/to/funlab-booking/scripts/backup.sh >> /var/log/funlab_backup.log 2>&1
```

### Stockage externe
Recommandations :
- **Cloud** : Google Drive, Dropbox, AWS S3
- **Serveur distant** : rsync vers serveur de backup
- **Local** : NAS, disque externe

#### Sync vers serveur distant
```bash
rsync -avz /backups/funlab/ user@backup-server:/backups/funlab/
```

## 🔄 Restauration

### Restaurer la base de données
```bash
# Décompresser si nécessaire
gunzip backup_20260131.sql.gz

# Restaurer
mysql -u root -p funl_FunLabBooking < backup_20260131.sql
```

### Restaurer les fichiers
```bash
tar -xzf uploads_backup_20260131.tar.gz
```

### Test de restauration
**Important** : Testez vos sauvegardes régulièrement !

1. Créez une base de test
2. Restaurez la sauvegarde
3. Vérifiez l'intégrité des données
4. Supprimez la base de test

## 🔧 Maintenance régulière

### Quotidienne
- [ ] Vérifier les réservations du jour
- [ ] Consulter les logs d'erreur
- [ ] Surveiller l'espace disque

```bash
# Vérifier l'espace disque
df -h

# Taille des logs
du -sh writable/logs/
```

### Hebdomadaire
- [ ] Nettoyer les logs anciens (> 7 jours)
- [ ] Vérifier les sauvegardes
- [ ] Consulter les statistiques
- [ ] Modérer les avis en attente

```bash
# Nettoyer les logs
find writable/logs/ -name "*.php" -mtime +7 -delete
```

### Mensuelle
- [ ] Optimiser la base de données
- [ ] Mettre à jour les dépendances
- [ ] Audit de sécurité
- [ ] Revue des performances
- [ ] Export comptable

```bash
# Optimiser la DB
mysql -u root -p -e "OPTIMIZE TABLE bookings, games, participants, payments;" funl_FunLabBooking
```

### Trimestrielle
- [ ] Mise à jour CodeIgniter
- [ ] Test de restauration complète
- [ ] Revue des permissions utilisateurs
- [ ] Archivage des données anciennes

## 📊 Monitoring

### Logs système
Fichiers à surveiller :
- `/writable/logs/log-*.php` : Erreurs PHP
- `/var/log/apache2/error.log` : Erreurs Apache
- `/var/log/mysql/error.log` : Erreurs MySQL

### Logs applicatifs
```bash
# Consulter les dernières erreurs
tail -n 50 writable/logs/log-$(date +%Y-%m-%d).php

# Surveiller en temps réel
tail -f writable/logs/log-$(date +%Y-%m-%d).php
```

### Alertes email
Configurez des alertes pour :
- Espace disque < 10%
- Erreurs critiques
- Échecs de sauvegarde
- Tentatives d'intrusion

## 🚀 Mises à jour

### CodeIgniter
```bash
# Vérifier la version actuelle
php spark --version

# Mettre à jour
composer update codeigniter4/framework

# Migrer la base de données si nécessaire
php spark migrate
```

### Dépendances Composer
```bash
# Lister les mises à jour disponibles
composer outdated

# Mettre à jour toutes les dépendances
composer update

# Mettre à jour une dépendance spécifique
composer update phpmailer/phpmailer
```

### Procédure de mise à jour sécurisée
1. **Sauvegarde complète**
2. **Mode maintenance** :
```bash
touch public/.maintenance
```
3. **Mise à jour** :
```bash
git pull origin main
composer install --no-dev
php spark migrate
```
4. **Tests** : Vérifier fonctionnalités critiques
5. **Désactiver maintenance** :
```bash
rm public/.maintenance
```

## 🔍 Diagnostic

### Espace disque
```bash
# Vérifier l'espace
df -h

# Trouver les gros fichiers
du -sh * | sort -hr | head -10

# Taille des logs
du -sh writable/logs/

# Taille des uploads
du -sh public/uploads/
```

### Performance MySQL
```bash
mysql -u root -p -e "SHOW PROCESSLIST;" funl_FunLabBooking
mysql -u root -p -e "SHOW STATUS LIKE 'Slow_queries';" funl_FunLabBooking
```

### Logs PHP
```bash
# Activer le mode debug temporairement
# Dans .env
CI_ENVIRONMENT = development

# Consulter les erreurs
tail -f writable/logs/log-*.php
```

## 🧹 Nettoyage

### Sessions expirées
```bash
# Nettoyer les sessions (si stockage fichier)
find writable/session/ -name "ci_session*" -mtime +1 -delete
```

### Cache
```bash
# Vider le cache applicatif
php spark cache:clear

# Vider le cache de vues
rm -rf writable/cache/*
```

### Uploads temporaires
```bash
# Nettoyer les uploads orphelins (non liés à des jeux)
# Script SQL custom ou script PHP
```

### Base de données
```sql
-- Supprimer les réservations annulées > 1 an
DELETE FROM bookings 
WHERE status = 'cancelled' 
  AND updatedAt < DATE_SUB(NOW(), INTERVAL 1 YEAR);

-- Anonymiser les anciennes réservations (RGPD)
UPDATE bookings 
SET customerName = 'Anonyme',
    customerEmail = CONCAT('deleted_', id, '@funlab.com'),
    customerPhone = NULL,
    notes = NULL
WHERE createdAt < DATE_SUB(NOW(), INTERVAL 3 YEAR)
  AND status IN ('completed', 'cancelled');
```

## 📋 Checklist maintenance

### Avant départ en vacances
- [ ] Sauvegarde complète récente
- [ ] Mises à jour appliquées
- [ ] Contact d'urgence défini
- [ ] Accès admin de secours créé
- [ ] Documentation à jour
- [ ] Logs vérifiés (pas d'erreur critique)

### Après incident
- [ ] Analyser les logs
- [ ] Identifier la cause
- [ ] Corriger la faille
- [ ] Tester la correction
- [ ] Documenter l'incident
- [ ] Mettre à jour les procédures

---

<div class="alert alert-warning">
    ⚠️ <strong>Règle d'or :</strong> 3-2-1 - 3 copies, 2 supports différents, 1 hors site
</div>
