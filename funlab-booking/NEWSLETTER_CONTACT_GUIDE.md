# Guide d'Installation - Système Contact & Newsletter

## 📋 Étapes d'installation

### 1. Créer les tables dans la base de données

Exécutez le fichier SQL suivant sur votre base de données :

```bash
mysql -u votre_utilisateur -p votre_base_de_donnees < database_newsletter_contact.sql
```

Ou via phpMyAdmin/Adminer en important le fichier `database_newsletter_contact.sql`.

### 2. Vérifier les permissions

Les permissions pour le module `contacts` ont été ajoutées automatiquement :
- **Admin** : accès complet (view, delete)
- **Staff** : lecture seule (view)
- **User** : aucun accès

### 3. Tester le système

#### Frontend (Visiteurs)
1. Visitez la page "À Propos" : `https://funlab.faltaagency.com/about`
2. Scrollez jusqu'au bloc Newsletter en bas de page
3. Entrez un email et cliquez sur "S'inscrire"
4. Vérifiez que vous recevez un message de confirmation

#### Backend (Administration)
1. Connectez-vous à l'admin : `https://funlab.faltaagency.com/admin`
2. Dans le menu latéral, cliquez sur "Contacts & Newsletter"
3. Vous verrez deux sous-menus :
   - **Messages Contact** : tous les messages du formulaire de contact
   - **Abonnés Newsletter** : liste des emails inscrits

## ✨ Fonctionnalités

### Gestion Newsletter
- ✅ Liste des abonnés avec statut (actif/désabonné)
- ✅ Export CSV des emails actifs
- ✅ Suppression d'abonnés
- ✅ Affichage de l'adresse IP et date d'inscription
- ✅ Réabonnement automatique si quelqu'un se réinscrit

### Gestion Messages Contact
- ✅ Liste des messages avec statuts (nouveau/lu/répondu)
- ✅ Vue détaillée de chaque message
- ✅ Bouton "Répondre par Email" (ouvre le client email)
- ✅ Marquer comme répondu
- ✅ Suppression de messages
- ✅ Badge "non lus" dans le menu

## 🎨 Interface

### Page About - Bloc Newsletter
Le bloc newsletter a été ajouté juste avant le footer sur la page "À Propos", avec le même design que sur la page d'accueil.

### Menu Admin
Une nouvelle section "Contacts & Newsletter" a été ajoutée dans le menu admin avec :
- Icône d'enveloppe
- Deux sous-menus cliquables
- Design cohérent avec le reste de l'interface

## 🔧 Configuration

### Routes ajoutées

**Frontend :**
```php
POST /contact/subscribe          // S'abonner à la newsletter
GET  /newsletter/unsubscribe     // Se désabonner
```

**Admin :**
```php
GET    /admin/contacts                    // Liste messages
GET    /admin/contacts/view/:id          // Voir message
POST   /admin/contacts/markReplied/:id   // Marquer répondu
DELETE /admin/contacts/delete/:id        // Supprimer

GET    /admin/newsletters                // Liste abonnés
GET    /admin/newsletters/export         // Exporter CSV
DELETE /admin/newsletters/delete/:id     // Supprimer
```

## 📊 Structure des tables

### `newsletter_subscribers`
- id
- email (unique)
- status (active/unsubscribed)
- subscribed_at
- unsubscribed_at
- ip_address

### `contact_messages`
- id
- name
- email
- phone
- subject
- message
- status (new/read/replied)
- replied_at
- ip_address
- created_at

## 🚀 Prochaines étapes suggérées

1. **Campagnes Email** : Créer une interface pour envoyer des newsletters à tous les abonnés
2. **Statistiques** : Ajouter des graphiques sur le dashboard
3. **Templates** : Système de templates pour les newsletters
4. **Segmentation** : Filtrer les abonnés par date, statut, etc.

## 📝 Notes

- Le formulaire newsletter utilise AJAX pour une meilleure expérience utilisateur
- SweetAlert2 est utilisé pour les notifications
- Les emails sont validés côté serveur
- Protection contre les doublons d'inscription
- Logs des adresses IP pour tracking
