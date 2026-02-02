# SYSTÈME DE PERMISSIONS DYNAMIQUE ET EXTENSIBLE

## 🎯 Objectif

Créer un système de permissions qui s'adapte automatiquement aux évolutions du code sans nécessiter de modifications manuelles.

## ✨ Fonctionnalités

### 1. Détection Automatique des Modules
- **Scanner automatique** : Les contrôleurs dans `app/Controllers/Admin/` sont détectés automatiquement
- **Synchronisation** : Bouton "Synchroniser les Modules" dans l'interface admin
- **Aucune modification de code nécessaire** : Ajoutez simplement un nouveau contrôleur, il sera détecté

### 2. Gestion Dynamique en Base de Données
- **4 tables principales** :
  - `permission_modules` : Liste des modules disponibles
  - `permission_actions` : Actions possibles (view, create, edit, delete, approve, scan, export, import)
  - `roles` : Rôles du système (admin, staff, user, + possibilité d'en ajouter)
  - `role_permissions` : Liaison entre rôles, modules et actions

### 3. Interface Admin Intuitive
- **Gestion visuelle** : Tableau avec checkboxes pour chaque combinaison rôle/module/action
- **Par onglets** : Un onglet par rôle pour une navigation claire
- **Protection** : Les permissions admin sont non modifiables (sécurité)
- **En temps réel** : Sauvegarde AJAX sans rechargement de page

## 📦 Installation

### Option 1 : Script automatique
```bash
chmod +x install_permissions_v2.sh
./install_permissions_v2.sh
```

### Option 2 : Manuelle
```bash
mysql -u root -p votre_base < database_permissions_dynamic.sql
```

### Étape finale
Remplacer l'ancien helper de permissions :

**Méthode 1 - Renommer**
```bash
mv app/Helpers/permission_helper.php app/Helpers/permission_helper_old.php
mv app/Helpers/permission_helper_v2.php app/Helpers/permission_helper.php
```

**Méthode 2 - Dans app/Config/Autoload.php**
```php
public $helpers = ['permission_v2']; // au lieu de 'permission'
```

## 🚀 Utilisation

### Dans l'interface Admin

1. **Accéder à la gestion** : `/admin/permissions`

2. **Synchroniser les modules** : 
   - Cliquez sur "Synchroniser les Modules"
   - Les nouveaux contrôleurs sont automatiquement détectés

3. **Configurer les permissions** :
   - Sélectionnez un rôle (onglet)
   - Cochez les permissions souhaitées
   - Cliquez sur "Sauvegarder"

4. **Gérer les modules** :
   - Accédez à "Gérer les Modules"
   - Modifiez les noms, descriptions, icônes
   - Activez/désactivez des modules
   - Changez l'ordre d'affichage

### Dans le code

Le code reste identique :

```php
// Vérifier une permission spécifique
if (hasPermission('bookings', 'edit')) {
    // L'utilisateur peut éditer les réservations
}

// Vérifier l'accès à un module
if (canAccessModule('games')) {
    // L'utilisateur peut accéder aux jeux
}

// Redirection automatique si pas de permission
if ($redirect = checkPermissionOrRedirect('settings', 'edit')) {
    return $redirect;
}
```

## 🔧 Architecture

### Service Principal
`App\Services\PermissionService`
- `scanAdminControllers()` : Détecte les contrôleurs automatiquement
- `syncModules()` : Synchronise avec la base de données
- `checkPermission()` : Vérifie une permission
- `getRolePermissions()` : Récupère toutes les permissions d'un rôle

### Modèles
- `PermissionModuleModel` : Gestion des modules
- `PermissionActionModel` : Gestion des actions
- `RoleModel` : Gestion des rôles et permissions

### Helper
`permission_helper_v2.php` (ou `permission_helper.php` après remplacement)
- Fonctions pratiques pour vérifier les permissions
- Compatible avec l'ancien code

## 📊 Exemple de Flux

### Ajout d'un nouveau module

1. **Créer le contrôleur** :
```php
// app/Controllers/Admin/InvoicesController.php
class InvoicesController extends BaseController { ... }
```

2. **Synchroniser** :
   - Aller dans `/admin/permissions`
   - Cliquer sur "Synchroniser les Modules"
   - Le module "Invoices" apparaît automatiquement

3. **Configurer** :
   - Cocher les permissions pour chaque rôle
   - Sauvegarder

4. **Utiliser** :
```php
if (canAccessModule('invoices')) {
    // Le module est accessible
}
```

**C'est tout !** Aucune modification de fichier de configuration nécessaire.

## 🛡️ Sécurité

- **Admin protégé** : Les permissions admin ne peuvent pas être modifiées via l'interface
- **Validation** : Toutes les entrées sont validées
- **Clés étrangères** : Intégrité référentielle en base de données
- **Fallback** : En cas d'erreur, les admins gardent toutes les permissions

## 🎨 Personnalisation

### Ajouter une nouvelle action

```sql
INSERT INTO permission_actions (key, name, description, sort_order) 
VALUES ('duplicate', 'Dupliquer', 'Dupliquer des éléments', 9);
```

Puis synchronisez dans l'interface.

### Ajouter un nouveau rôle

```sql
INSERT INTO roles (key, name, description, is_system, sort_order) 
VALUES ('manager', 'Manager', 'Rôle de responsable', 0, 4);
```

### Changer l'icône d'un module

Via l'interface : `/admin/permissions/modules`
Ou en base :

```sql
UPDATE permission_modules 
SET icon = 'ticket-perforated' 
WHERE key = 'bookings';
```

## 📈 Avantages vs Ancien Système

| Critère | Ancien Système | Nouveau Système |
|---------|---------------|-----------------|
| **Ajout de module** | Modifier le code + fichier config | Automatique |
| **Configuration** | Fichier PHP hardcodé | Interface graphique |
| **Évolutivité** | Limitée | Infinie |
| **Maintenance** | Manuelle | Automatisée |
| **Actions custom** | Modifier le code | Ajouter en BDD |
| **Nouveaux rôles** | Modifier le code | Ajouter en BDD |

## 🔄 Migration depuis l'ancien système

Les anciennes fonctions restent compatibles. Le nouveau système remplace simplement l'implémentation interne tout en gardant la même API.

## 🆘 Dépannage

### Les modules ne s'affichent pas
```bash
# Vérifier que les tables existent
mysql -u root -p votre_base -e "SHOW TABLES LIKE 'permission_%'"

# Synchroniser manuellement
# Accéder à /admin/permissions et cliquer sur "Synchroniser"
```

### Permissions non prises en compte
```bash
# Vérifier le cache
php spark cache:clear

# Vérifier que le nouveau helper est chargé
grep -r "permission_helper" app/Config/
```

## 📝 Notes

- Le système est rétrocompatible
- Les anciennes permissions en JSON ne sont plus utilisées
- Le fallback assure la continuité en cas de problème
- Les performances sont optimisées (mise en cache possible)

## 🚀 Prochaines Évolutions

- [ ] Cache des permissions pour meilleures performances
- [ ] Export/Import de configurations de permissions
- [ ] Logs des changements de permissions
- [ ] Permissions au niveau utilisateur (en plus du rôle)
- [ ] Interface de création de rôles custom
