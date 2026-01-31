<h1><i class="bi bi-shield-lock text-primary"></i> Système de Permissions</h1>

<div class="alert alert-info">
    Le système de permissions de FunLab Booking permet de contrôler l'accès aux différentes fonctionnalités selon le rôle de l'utilisateur.
</div>

## 🎯 Vue d'ensemble

Le système utilise une approche **module-action** où chaque permission est définie par :
- **Module** : La section de l'application (ex: `games`, `bookings`, `settings`)
- **Action** : L'opération autorisée (ex: `view`, `create`, `edit`, `delete`)

## 👥 Rôles disponibles

### 🔴 Admin (Administrateur)
- **Accès complet** à tous les modules et toutes les actions
- Peut gérer les utilisateurs et leurs rôles
- Peut modifier les permissions des autres rôles
- Accès à tous les paramètres système

### 🟡 Staff (Personnel)
- Accès limité aux modules opérationnels
- Peut consulter et modifier les réservations
- Peut consulter (mais pas modifier) les jeux et salles
- **Aucun accès** aux paramètres système et gestion utilisateurs
- Peut utiliser le scanner de tickets

### 🔵 User (Client)
- Accès minimal, côté client uniquement
- Peut consulter les jeux disponibles
- Peut créer et consulter ses propres réservations
- Aucun accès à l'administration

## 📋 Matrice des permissions

<div class="table-responsive">
<table class="table table-bordered">
    <thead class="table-light">
        <tr>
            <th>Module</th>
            <th>Admin</th>
            <th>Staff</th>
            <th>User</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><strong>Dashboard</strong></td>
            <td><span class="badge bg-success">Complet</span></td>
            <td><span class="badge bg-warning">Consultation</span></td>
            <td><span class="badge bg-danger">Aucun</span></td>
        </tr>
        <tr>
            <td><strong>Réservations</strong></td>
            <td><span class="badge bg-success">Complet</span></td>
            <td><span class="badge bg-info">Voir, Créer, Modifier</span></td>
            <td><span class="badge bg-warning">Voir (ses réservations)</span></td>
        </tr>
        <tr>
            <td><strong>Jeux</strong></td>
            <td><span class="badge bg-success">Complet</span></td>
            <td><span class="badge bg-warning">Consultation</span></td>
            <td><span class="badge bg-warning">Consultation</span></td>
        </tr>
        <tr>
            <td><strong>Salles</strong></td>
            <td><span class="badge bg-success">Complet</span></td>
            <td><span class="badge bg-warning">Consultation</span></td>
            <td><span class="badge bg-warning">Consultation</span></td>
        </tr>
        <tr>
            <td><strong>Fermetures</strong></td>
            <td><span class="badge bg-success">Complet</span></td>
            <td><span class="badge bg-warning">Consultation</span></td>
            <td><span class="badge bg-danger">Aucun</span></td>
        </tr>
        <tr>
            <td><strong>Avis</strong></td>
            <td><span class="badge bg-success">Complet</span></td>
            <td><span class="badge bg-info">Voir, Approuver</span></td>
            <td><span class="badge bg-danger">Aucun</span></td>
        </tr>
        <tr>
            <td><strong>Scanner</strong></td>
            <td><span class="badge bg-success">Complet</span></td>
            <td><span class="badge bg-success">Complet</span></td>
            <td><span class="badge bg-danger">Aucun</span></td>
        </tr>
        <tr>
            <td><strong>Équipes</strong></td>
            <td><span class="badge bg-success">Complet</span></td>
            <td><span class="badge bg-info">Voir, Créer, Modifier</span></td>
            <td><span class="badge bg-danger">Aucun</span></td>
        </tr>
        <tr>
            <td><strong>Paramètres</strong></td>
            <td><span class="badge bg-success">Complet</span></td>
            <td><span class="badge bg-danger">Aucun</span></td>
            <td><span class="badge bg-danger">Aucun</span></td>
        </tr>
        <tr>
            <td><strong>Utilisateurs</strong></td>
            <td><span class="badge bg-success">Complet</span></td>
            <td><span class="badge bg-danger">Aucun</span></td>
            <td><span class="badge bg-danger">Aucun</span></td>
        </tr>
    </tbody>
</table>
</div>

## 🔐 Sécurité renforcée

### Protection contre les modifications non autorisées

Le système implémente plusieurs couches de sécurité :

1. **Vérification au niveau contrôleur**
   ```php
   if ($redirect = checkPermissionOrRedirect('games', 'create')) {
       return $redirect;
   }
   ```

2. **Vérification au niveau vue**
   ```php
   <?php if (hasPermission('games', 'create')): ?>
       <button>Créer un jeu</button>
   <?php endif; ?>
   ```

3. **Protections spéciales pour la gestion des utilisateurs**
   - Un staff ne peut **jamais** créer de compte admin
   - Un staff ne peut **jamais** modifier un admin
   - Un staff ne peut **jamais** supprimer un admin
   - Impossible de supprimer le dernier administrateur

### Exemples de messages de sécurité

<div class="alert alert-danger">
    <strong>Staff tentant de créer un admin :</strong><br>
    "Vous ne pouvez pas créer un compte administrateur"
</div>

<div class="alert alert-danger">
    <strong>Staff tentant de modifier un admin :</strong><br>
    "Vous ne pouvez pas modifier un administrateur"
</div>

<div class="alert alert-danger">
    <strong>Tentative de suppression du dernier admin :</strong><br>
    "Impossible de supprimer le dernier administrateur"
</div>

## 🛠️ Utilisation dans le code

### Vérifier une permission
```php
// Vérification simple
if (hasPermission('bookings', 'create')) {
    // L'utilisateur peut créer des réservations
}

// Vérification avec redirection automatique
if ($redirect = checkPermissionOrRedirect('settings', 'view')) {
    return $redirect;
}

// Vérifier l'accès à un module
if (canAccessModule('games')) {
    // L'utilisateur peut accéder au module jeux
}
```

### Dans les contrôleurs
```php
public function create()
{
    helper('permission');
    
    if ($redirect = checkPermissionOrRedirect('games', 'create')) {
        return $redirect;
    }
    
    // Code de création...
}
```

### Dans les vues
```php
<?php helper('permission'); ?>

<?php if (hasPermission('games', 'edit')): ?>
    <a href="/admin/games/edit/<?= $game['id'] ?>" class="btn btn-primary">
        <i class="bi bi-pencil"></i> Modifier
    </a>
<?php endif; ?>

<?php if (hasPermission('games', 'delete')): ?>
    <button class="btn btn-danger" onclick="confirmDelete(<?= $game['id'] ?>)">
        <i class="bi bi-trash"></i> Supprimer
    </button>
<?php endif; ?>
```

## ⚙️ Modification des permissions

Les permissions peuvent être modifiées depuis l'interface admin :

1. Accédez à **Utilisateurs → Rôles & Permissions**
2. Sélectionnez le rôle à modifier
3. Cochez/décochez les permissions souhaitées
4. Cliquez sur **Enregistrer**

<div class="alert alert-warning">
    <i class="bi bi-exclamation-triangle"></i> <strong>Attention :</strong> Les modifications de permissions affectent immédiatement tous les utilisateurs du rôle concerné. Assurez-vous de bien comprendre l'impact avant de valider.
</div>

## 📁 Fichiers système

- **Helper** : `/app/Helpers/permission_helper.php`
- **Stockage** : Base de données, table `settings`, clé `role_permissions`
- **Configuration** : Interface admin `/admin/settings/roles`

## 🔍 Actions disponibles

- **view** : Consultation
- **create** : Création
- **edit** : Modification
- **delete** : Suppression
- **approve** : Approbation (pour les avis)
- **scan** : Scanner (pour le scanner de tickets)

---

<div class="alert alert-success">
    💡 <strong>Conseil :</strong> En cas de doute sur les permissions, connectez-vous avec un compte test du rôle concerné pour vérifier les restrictions.
</div>
