<h1><i class="bi bi-controller text-primary"></i> Gestion des Jeux</h1>

## 🎮 Structure d'un jeu

Chaque jeu contient :
- **Informations de base** : Nom, description, catégorie
- **Configuration** : Durée, min/max joueurs, difficulté
- **Tarification** : Prix par joueur
- **Médias** : Image principale, galerie
- **Disponibilité** : Actif/Inactif, salles associées

## 📝 Créer un jeu

### Informations obligatoires
```
Nom du jeu : Escape Room Mystère
Catégorie : Escape Game
Description : Une aventure captivante où vous devez 
résoudre des énigmes pour vous échapper en 60 minutes.

Durée : 60 minutes
Joueurs min : 2
Joueurs max : 6
Difficulté : Moyen

Prix par joueur : 25.00 TND
```

### Image principale
- Format : JPG, PNG
- Taille recommandée : 1200x800px
- Poids max : 2MB
- Ratio : 3:2

### SEO et métadonnées
- **URL slug** : escape-room-mystere
- **Meta description** : Pour le référencement
- **Tags** : aventure, énigmes, teambuilding

## 🏢 Association aux salles

Chaque jeu doit être lié à une ou plusieurs salles :
```
Jeu : Escape Room Mystère
Salles associées :
  - Salle A (principale)
  - Salle B (si grosse affluence)
```

Cela permet au système de :
- Calculer les disponibilités
- Éviter les conflits de réservation
- Gérer plusieurs sessions simultanées

## 🎭 Catégories de jeux

### Catégories par défaut
- **Escape Game** : Énigmes et évasion
- **Réalité Virtuelle** : Expérience VR
- **Laser Game** : Jeu de tir laser
- **Jeux de société** : Plateaux géants
- **Aventure** : Parcours et défis

### Créer une catégorie
1. **Catégories → Ajouter**
2. Nom et description
3. Icône (optionnel)
4. Ordre d'affichage

## ⚙️ Configuration avancée

### Durée du jeu
Inclut :
- Briefing : 5-10 min
- Jeu : 45-60 min
- Debriefing : 5 min

Exemple : Jeu de 60 min = 10 briefing + 45 jeu + 5 debriefing

### Tarification flexible
Options :
- **Prix fixe par joueur**
- **Prix dégressif** : 25 TND/joueur si 2-3, 20 TND si 4+
- **Prix forfaitaire** : 100 TND peu importe le nombre

### Compléments
Ajoutez des options payantes :
- Photos souvenir : +10 TND
- Indice supplémentaire : +5 TND
- Snacks & boissons : +15 TND

## 📊 Statistiques par jeu

Consultez :
- **Réservations totales**
- **Taux d'occupation** : % de créneaux réservés
- **Revenus générés**
- **Note moyenne** : Avis clients
- **Taux de réussite** : Pour escape games

## ⭐ Avis et notes

Les clients peuvent noter après leur session :
- **Note** : 1 à 5 étoiles
- **Commentaire** : Texte libre
- **Modération** : Approuvé par staff/admin

Affichage sur la page du jeu :
```
⭐⭐⭐⭐⭐ 4.8/5 (142 avis)
```

## 🔄 Modifier un jeu existant

Vous pouvez mettre à jour :
- ✅ Description, prix, durée
- ✅ Images et galerie
- ✅ Disponibilité (actif/inactif)
- ⚠️ Pas de modification si réservations en cours

### Désactiver temporairement
Pour maintenance ou mise à jour :
1. Ouvrez le jeu
2. **Statut → Inactif**
3. Le jeu n'apparaît plus en réservation
4. Les réservations existantes sont maintenues

## 🗑️ Supprimer un jeu

<div class="alert alert-danger">
    ⚠️ Impossible de supprimer un jeu avec des réservations existantes ou passées
</div>

Alternative : Désactivez le jeu définitivement

## 📷 Galerie d'images

Ajoutez plusieurs photos :
- Salle de jeu
- Détails et décors
- Clients en action (avec accord)
- Certificats et récompenses

Limite : 10 images par jeu

---

<div class="alert alert-info">
    💡 Mettez régulièrement à jour vos jeux avec de nouvelles photos et descriptions pour maintenir l'intérêt.
</div>
