<h1><i class="bi bi-calendar-check text-primary"></i> Gestion des Réservations</h1>

<div class="alert alert-info">
    Guide complet sur la gestion du système de réservations FunLab.
</div>

## 🎯 Vue d'ensemble

Le système de réservations gère :
- Disponibilités en temps réel
- Multi-salles et multi-jeux
- Créneaux horaires configurables
- Gestion des participants
- Équipes et scores
- Historique complet

## 📋 Statuts de réservation

<div class="table-responsive">
<table class="table table-bordered">
    <thead class="table-light">
        <tr>
            <th>Statut</th>
            <th>Description</th>
            <th>Actions possibles</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><span class="badge bg-warning">En attente</span></td>
            <td>Réservation créée, en attente de paiement</td>
            <td>Modifier, Annuler, Marquer payé</td>
        </tr>
        <tr>
            <td><span class="badge bg-success">Confirmée</span></td>
            <td>Paiement reçu, réservation confirmée</td>
            <td>Modifier, Annuler (avec remboursement)</td>
        </tr>
        <tr>
            <td><span class="badge bg-primary">Validée</span></td>
            <td>Client présent, ticket scanné</td>
            <td>Gérer équipes, Terminer</td>
        </tr>
        <tr>
            <td><span class="badge bg-info">Terminée</span></td>
            <td>Session terminée</td>
            <td>Consulter uniquement</td>
        </tr>
        <tr>
            <td><span class="badge bg-danger">Annulée</span></td>
            <td>Réservation annulée</td>
            <td>Consulter uniquement</td>
        </tr>
    </tbody>
</table>
</div>

## 🔍 Recherche et Filtres

### Filtres disponibles
- **Par date** : Aujourd'hui, Cette semaine, Ce mois, Période personnalisée
- **Par statut** : En attente, Confirmée, Terminée, Annulée
- **Par paiement** : Payé, Non payé, Remboursé
- **Par jeu** : Sélection dans la liste
- **Par client** : Recherche par nom/email

### Recherche rapide
Utilisez la barre de recherche pour trouver :
- Numéro de référence : `FL20260215-123`
- Nom du client
- Email
- Téléphone

## 📅 Calendrier de disponibilités

### Algorithme de disponibilité
Le système vérifie automatiquement :
1. **Horaires d'ouverture** : Configuration par jour
2. **Durée du jeu** : Ex: Jeu de 60 min ne peut pas commencer à 19h30 si fermeture à 20h
3. **Salles disponibles** : Une salle = Une session à la fois
4. **Fermetures** : Jours fériés, maintenance

### Créneaux horaires
Configuration dans **Paramètres → Horaires** :
- Intervalle par défaut : 30 minutes
- Personnalisable : 15, 30, 45, 60 minutes

Exemple (intervalle 30 min, ouverture 10h-20h) :
```
10:00, 10:30, 11:00, 11:30, 12:00, ...
```

### Vérification de conflits
Le système empêche :
- ❌ Double réservation d'une salle
- ❌ Réservation hors horaires
- ❌ Réservation sur fermeture
- ❌ Nombre de joueurs > capacité

## 👥 Gestion des Participants

### Ajouter des participants
Lors de la création :
```
Participant 1 (Chef de réservation)
- Nom : Ahmed Ben Ali
- Email : ahmed@example.com
- Téléphone : +216 XX XXX XXX

Participant 2
- Nom : Fatma Trabelsi
- Email : fatma@example.com

Participant 3...
```

### Auto-inscription des participants
Après réservation, le chef de groupe reçoit un **lien d'inscription** :
```
https://funlab.com/register/ABC123TOKEN
```

Les participants peuvent :
- S'inscrire avec nom et email
- Voir la liste des inscrits
- Limite : Nombre de joueurs réservés

### Modifier les participants
Dans une réservation existante :
- **Ajouter** : Si places disponibles
- **Supprimer** : Libère une place
- **Modifier** : Corriger nom/email

## 🏆 Gestion des Équipes

### Quand utiliser les équipes ?
Pour les jeux compétitifs :
- Laser game
- Escape room en compétition
- Jeux d'équipe

### Créer les équipes
1. Ouvrez la réservation
2. **Équipes → Gérer**
3. Créez 2 équipes ou plus :
   - Équipe Rouge
   - Équipe Bleue
   - Équipe Verte
4. **Répartir les participants** :
   - Drag & drop des participants
   - Ou sélection manuelle
5. **Sauvegardez**

### Enregistrer les scores
À la fin du jeu :
1. **Équipes → Scores**
2. Entrez les scores :
   - Équipe Rouge : 450 points
   - Équipe Bleue : 380 points
3. Le système détermine le vainqueur
4. **Sauvegardez**

Les scores sont :
- Affichés sur le ticket final
- Envoyés par email aux participants
- Archivés dans l'historique

## 💳 Gestion des Paiements

### Statuts de paiement
- **Non payé** : En attente de paiement
- **En cours** : Paiement Stripe en traitement
- **Payé** : Paiement confirmé
- **Remboursé** : Total ou partiel
- **Échoué** : Paiement refusé

### Modes de paiement
1. **Carte bancaire en ligne** (Stripe)
   - Automatique via le formulaire
   - Confirmation immédiate
   
2. **Paiement sur place**
   - Cash
   - TPE sur place
   - À marquer manuellement

3. **Virement bancaire**
   - Pour groupes ou entreprises
   - Confirmation manuelle requise

### Marquer comme payé manuellement
Si paiement hors ligne :
1. Ouvrez la réservation
2. **Paiement → Marquer comme payé**
3. Sélectionnez le mode
4. Ajoutez une note
5. **Sauvegardez**

### Remboursements
**Pour administrateurs uniquement**

1. Ouvrez la réservation
2. **Actions → Rembourser**
3. Choisissez :
   - **Total** : 100% du montant
   - **Partiel** : Montant personnalisé
4. Motif (obligatoire)
5. **Confirmer**

Si paiement Stripe :
- Remboursement automatique
- Délai : 5-10 jours bancaires

Si paiement cash :
- Remboursement manuel
- Comptabilité à jour manuellement

## 📧 Notifications automatiques

### Email de confirmation
Envoyé immédiatement après réservation :
- Référence de réservation
- QR Code
- Détails du jeu
- Date et heure
- Adresse FunLab
- Lien d'auto-inscription participants

### SMS de rappel
Envoyé **24h avant** la session :
```
Rappel FunLab : Votre réservation "Escape Room" 
demain à 14:00. Arrivez 10 min avant. 
Ref: FL20260215-123
```

### Email d'annulation
Si annulation :
- Confirmation d'annulation
- Montant remboursé (si applicable)
- Lien pour re-réserver

## 📊 Rapports

### Statistiques de réservations
- **Taux d'occupation** : % de créneaux réservés
- **Revenus par période**
- **Top jeux** : Les plus réservés
- **Heures de pointe**
- **Taux d'annulation**

### Export des données
Format CSV/Excel :
- Période personnalisée
- Filtres appliqués
- Colonnes sélectionnées

## 🔧 Cas d'usage spécifiques

### Réservation de groupe (10+ personnes)
1. Créez plusieurs réservations simultanées
2. Ou contactez directement pour tarif groupe
3. Utilisez les équipes pour organiser

### Événement privé
1. **Fermetures → Ajouter**
2. Type : Événement privé
3. Date et créneaux
4. Créez la réservation manuellement
5. Les créneaux sont bloqués pour le public

### No-show (client absent)
1. Ouvrez la réservation
2. **Statut → Annulée**
3. Motif : "Client absent"
4. Pas de remboursement si politique stricte

### Retard client
- **< 10 min** : Session maintenue
- **10-20 min** : Décision staff (durée réduite)
- **> 20 min** : Annulation sans remboursement

---

<div class="alert alert-success">
    ✅ Le système de réservations est optimisé pour gérer des centaines de réservations par jour sans conflit.
</div>
