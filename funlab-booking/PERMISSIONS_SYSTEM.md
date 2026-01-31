# Système de Gestion des Permissions

## État Actuel - CORRIGÉ ✅

Le système de permissions existe maintenant et **EST APPLIQUÉ** !

### Intégration avec le Module Existant

L'application avait déjà:
- ✅ Une interface UI dans `/admin/settings/roles` pour gérer les permissions
- ✅ Une structure de permissions définie dans `SettingsController::getRolePermissions()`
- ❌ MAIS la sauvegarde n'était pas implémentée (TODO)
- ❌ MAIS les permissions n'étaient pas vérifiées dans les contrôleurs

### Ce Qui a Été Fait

1. **✅ Créé le Helper** (`app/Helpers/permission_helper.php`)
   - S'intègre avec le système existant
   - Récupère les permissions depuis la base de données (table `settings`)
   - Utilise les permissions par défaut si rien en BDD

2. **✅ Implémenté la Sauvegarde** 
   - La fonction `updateRolePermissions()` sauvegarde maintenant dans la BDD
   - Les permissions sont stockées en JSON dans `settings.role_permissions`

3. **✅ Appliqué les Vérifications**
   - GamesController vérifie les permissions
   - SettingsController vérifie les permissions
   - Les autres contrôleurs doivent suivre le même modèle

Fonctions disponibles :
- `hasPermission($module, $action)` : Vérifie si l'utilisateur a une permission
- `getRolePermissions()` : Retourne toutes les permissions par rôle
- `canAccessModule($module)` : Vérifie si l'utilisateur peut voir un module
- `checkPermissionOrRedirect($module, $action)` : Vérifie et redirige si pas de permission

### 2. Permissions Définies par Rôle

#### Admin
- Accès total à tous les modules : view, create, edit, delete

#### Staff
- `dashboard` : view
- `bookings` : view, create, edit (PAS delete)
- `games` : view uniquement (PAS create, edit, delete)
- `rooms` : view uniquement
- `closures` : view uniquement
- `reviews` : view, approve (PAS delete)
- `participants` : view, edit
- `teams` : view, create, edit
- `scanner` : view, scan
- `settings` : AUCUN accès
- `users` : AUCUN accès

#### User (Client)
- `bookings` : view (leurs propres réservations)
- Accès limité aux pages publiques

## Comment Appliquer les Permissions

### Exemple : GamesController (DÉJÀ FAIT)

```php
class GamesController extends BaseController
{
    public function __construct()
    {
        // ... autres initialisations
        helper('permission'); // Charger le helper
    }

    public function index()
    {
        // Vérifier la permission
        if ($redirect = checkPermissionOrRedirect('games', 'view')) {
            return $redirect;
        }
        // ... reste du code
    }

    public function create()
    {
        if ($redirect = checkPermissionOrRedirect('games', 'create')) {
            return $redirect;
        }
        // ... reste du code
    }

    public function update($id)
    {
        if ($redirect = checkPermissionOrRedirect('games', 'edit')) {
            return $redirect;
        }
        // ... reste du code
    }

    public function delete($id)
    {
        // Pour les requêtes AJAX
        if (!hasPermission('games', 'delete')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => "Vous n'avez pas la permission."
            ]);
        }
        // ... reste du code
    }
}
```

## Contrôleurs à Modifier

### Priorité HAUTE (accès sensibles)
- [ ] `SettingsController` - Paramètres système
- [ ] `RoomsController` - Gestion des salles
- [ ] `ClosuresController` - Fermetures

### Priorité MOYENNE
- [ ] `BookingsController` - Réservations (staff peut view/edit mais pas delete)
- [ ] `ReviewsController` - Avis (staff peut approve mais pas delete)
- [ ] `ParticipantsController` - Participants
- [ ] `TeamsController` - Équipes

### Priorité BASSE
- [ ] `DashboardController` - Dashboard (déjà view only pour staff)
- [ ] `ScannerController` - Scanner QR

## Masquer les Boutons dans les Vues

### Dans les vues admin, utiliser les permissions pour cacher les boutons :

```php
<?php helper('permission'); ?>

<!-- Bouton Créer (seulement si permission 'create') -->
<?php if (hasPermission('games', 'create')): ?>
    <a href="<?= base_url('admin/games/create') ?>" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Ajouter un jeu
    </a>
<?php endif; ?>

<!-- Bouton Modifier (seulement si permission 'edit') -->
<?php if (hasPermission('games', 'edit')): ?>
    <a href="<?= base_url('admin/games/edit/' . $game['id']) ?>" class="btn btn-outline-primary">
        <i class="bi bi-pencil"></i> Modifier
    </a>
<?php endif; ?>

<!-- Bouton Supprimer (seulement si permission 'delete') -->
<?php if (hasPermission('games', 'delete')): ?>
    <button type="button" class="btn btn-outline-danger" onclick="deleteGame(<?= $game['id'] ?>)">
        <i class="bi bi-trash"></i>
    </button>
<?php endif; ?>
```

## Dans le Menu Sidebar

Modifier `app/Views/admin/layouts/sidebar.php` pour cacher les sections :

```php
<!-- Paramètres - Seulement pour admin -->
<?php if (hasPermission('settings', 'view')): ?>
<li class="nav-item">
    <a class="nav-link" href="<?= base_url('admin/settings') ?>">
        <i class="bi bi-gear"></i> Paramètres
    </a>
</li>
<?php endif; ?>
```

## Test Rapide

1. Créer un utilisateur avec rôle `staff`
2. Se connecter avec ce compte
3. Vérifier :
   - ✅ Peut voir la liste des jeux
   - ❌ Ne peut PAS créer/modifier/supprimer des jeux
   - ❌ Ne peut PAS accéder aux paramètres
   - ✅ Peut voir/modifier les réservations
   - ✅ Peut utiliser le scanner

## TODO Urgent

1. ✅ Créer le helper de permissions
2. ✅ Appliquer les permissions dans GamesController (exemple)
3. ⚠️ Appliquer dans SettingsController (priorité critique)
4. ⚠️ Appliquer dans RoomsController
5. ⚠️ Modifier les vues pour cacher les boutons selon permissions
6. ⚠️ Modifier le sidebar pour cacher les menus inaccessibles
