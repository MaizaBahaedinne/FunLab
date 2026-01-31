<h1><i class="bi bi-shield-fill text-danger"></i> Guide Administrateur</h1>

<div class="alert alert-danger">
    <strong>Accès Administrateur uniquement</strong> - Ce guide contient des informations sensibles sur la gestion complète du système.
</div>

## 🎯 Vue d'ensemble

En tant qu'administrateur, vous avez un **accès complet** à toutes les fonctionnalités :
- Gestion des jeux, salles et réservations
- Configuration système
- Gestion des utilisateurs et permissions
- Statistiques et rapports
- Paramètres de paiement et email

## 📊 Dashboard

Le tableau de bord affiche :
- **Statistiques du jour** : Réservations, revenus, taux d'occupation
- **Graphiques** : Évolution sur 7/30 jours
- **Réservations récentes** : À traiter en priorité
- **Alertes** : Conflits, fermetures, paiements en attente

## 🎮 Gestion des Jeux

### Créer un jeu
1. **Jeux → Ajouter un jeu**
2. Remplissez :
   - Nom et description
   - Catégorie
   - Durée (minutes)
   - Min/Max joueurs
   - Prix par joueur
   - Image principale
3. **Sauvegardez**

### Modifier un jeu
- Cliquez sur l'icône **Modifier**
- Mettez à jour les informations
- Gérez la disponibilité (Actif/Inactif)

### Supprimer un jeu
<div class="alert alert-warning">
    ⚠️ Impossible de supprimer un jeu avec des réservations existantes
</div>

## 🏢 Gestion des Salles

### Ajouter une salle
1. **Salles → Ajouter une salle**
2. Informations :
   - Nom de la salle
   - Capacité maximale
   - Jeux associés
   - Équipements disponibles

### Associer jeux et salles
Chaque jeu doit être lié à une ou plusieurs salles pour la gestion automatique des disponibilités.

## 📅 Gestion des Réservations

### Voir toutes les réservations
- **Réservations** : Liste complète avec filtres
- Filtres disponibles :
  - Par date
  - Par statut (En attente, Confirmée, Annulée)
  - Par paiement (Payé, Non payé)
  - Par jeu

### Modifier une réservation
Vous pouvez :
- Changer la date/heure
- Modifier le nombre de joueurs
- Ajouter/supprimer des participants
- Mettre à jour le statut de paiement

### Annuler une réservation
1. Ouvrez la réservation
2. Cliquez sur **"Annuler"**
3. Choisissez le motif
4. Le client est notifié automatiquement

### Gestion des équipes
Pour les jeux compétitifs :
1. Ouvrez une réservation
2. **Gérer les équipes**
3. Créez 2+ équipes
4. Assignez les participants
5. Enregistrez les scores

## 💳 Paiements

### Configuration Stripe
**Paramètres → Paiement**
- Clé publique Stripe
- Clé secrète Stripe
- Webhook secret
- Mode (Test/Production)

### Vérifier les paiements
- **Réservations** : Colonne statut paiement
- Filtrer par **"Non payé"** pour les relances
- Marquer manuellement comme payé si paiement cash

### Remboursements
1. Ouvrez la réservation
2. **Actions → Rembourser**
3. Montant (total ou partiel)
4. Le remboursement Stripe est automatique

## 🔒 Fermetures et Indisponibilités

### Créer une fermeture
1. **Fermetures → Ajouter**
2. Sélectionnez :
   - Date(s)
   - Jeu concerné (ou tous)
   - Motif
   - Créneaux spécifiques (optionnel)

### Types de fermetures
- **Maintenance** : Jeu indisponible
- **Événement privé** : Toutes les salles
- **Jour férié** : Fermeture complète
- **Horaires réduits** : Créneaux spécifiques

## 👥 Gestion des Utilisateurs

### Créer un utilisateur
1. **Utilisateurs → Gestion → Créer**
2. Informations :
   - Email (unique)
   - Nom et prénom
   - Rôle (Admin/Staff/User)
   - Mot de passe temporaire
3. L'utilisateur reçoit un email d'activation

### Rôles et permissions
**Utilisateurs → Rôles & Permissions**

Définissez pour chaque rôle :
- **Modules accessibles** : Dashboard, Jeux, Réservations, etc.
- **Actions autorisées** : Voir, Créer, Modifier, Supprimer

<div class="alert alert-danger">
    <strong>Sécurité :</strong>
    - Un staff ne peut jamais créer/modifier/supprimer un admin
    - Impossible de supprimer le dernier administrateur
    - Les modifications de permissions sont enregistrées dans l'historique
</div>

### Désactiver un compte
Au lieu de supprimer :
1. Ouvrez le compte utilisateur
2. **Actions → Désactiver**
3. L'utilisateur ne peut plus se connecter
4. Ses données restent en base

## ⚙️ Configuration Système

### Paramètres généraux
**Paramètres → Général**
- Nom du site
- Logo et favicon
- Adresse et contact
- Réseaux sociaux
- Fuseau horaire

### Horaires d'ouverture
**Paramètres → Horaires**
- Horaires par jour de la semaine
- Intervalles de créneaux (ex: 30 min)
- Fermetures hebdomadaires

### Configuration Email
**Paramètres → Communications → Email**
- Serveur SMTP
- Authentification
- Templates d'emails :
  - Confirmation réservation
  - Rappel 24h avant
  - Annulation
  - Code de vérification

### Configuration SMS
**Paramètres → Communications → SMS**
- Fournisseur (Twilio, etc.)
- Clés API
- Templates SMS

### SEO et métadonnées
**Paramètres → Pages & Contenu → SEO**
- Meta title et description
- Open Graph pour Facebook
- Twitter Cards
- Analytics (Google, Facebook Pixel)

## 📊 Rapports et Statistiques

### Tableaux de bord
- **Revenus** : Jour, semaine, mois, année
- **Taux d'occupation** : Par jeu et par salle
- **Top jeux** : Les plus réservés
- **Heures de pointe** : Optimiser les créneaux

### Exporter des données
- Export CSV des réservations
- Export Excel des statistiques
- Période personnalisée

## 🔍 Scanner de Tickets

### Utiliser le scanner
1. **Scanner** dans le menu admin
2. Activez la webcam
3. Scannez le QR code du client
4. Validation automatique :
   - ✅ Réservation valide et à l'heure
   - ⚠️ Réservation en avance/retard
   - ❌ Réservation invalide ou annulée

### Scanner mobile
Le scanner fonctionne aussi sur tablette/smartphone pour l'accueil à l'entrée.

## 🔐 Sécurité et Maintenance

### Sauvegardes
- **Automatique** : Quotidienne (si configurée sur le serveur)
- **Manuelle** : Via phpMyAdmin ou CLI

### Logs système
Consultez `/writable/logs/` pour :
- Erreurs système
- Tentatives de connexion échouées
- Actions administratives

### Mise à jour
1. Sauvegarde complète
2. Mode maintenance
3. Mise à jour via Git ou FTP
4. Test en environnement de staging
5. Migration base de données si nécessaire

---

<div class="alert alert-info">
    💡 <strong>Bonnes pratiques :</strong>
    - Vérifiez les réservations 2 fois par jour
    - Répondez aux avis clients
    - Mettez à jour les jeux régulièrement
    - Sauvegardez avant toute modification majeure
</div>
