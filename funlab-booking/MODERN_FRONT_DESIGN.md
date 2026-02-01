# Design Moderne du Front Client

## Vue d'ensemble
Modernisation complète de l'interface front client avec un design unifié, animé et responsive pour toutes les pages.

## Caractéristiques principales

### 1. Header Fixed Moderne
- **Position fixe** en haut de la page
- **Logo configurable** depuis l'interface admin (dimensions et URL)
- **Effet glass/blur** avec transparence
- **Animation au scroll** : réduction de padding et augmentation de l'ombre
- **Design moderne** avec Poppins font

### 2. Navigation Unifiée
- **Menu principal** : Accueil, À Propos, Jeux, Contact
- **Dropdown jeux** : Liste dynamique des jeux actifs
- **Menu utilisateur** : Profil, Réservations, Admin (si applicable), Déconnexion
- **Bouton "Réserver"** : CTA prominent avec gradient
- **Responsive** : Menu burger sur mobile

### 3. Footer Moderne
- **3 colonnes** : À Propos, Contact, Liens Rapides
- **Icônes sociales** avec effet hover
- **Horaires d'ouverture** depuis les settings
- **Design gradient** : #1a1a2e → #16213e
- **Animations** : Hover effects sur tous les liens

### 4. Animations CSS
- **fadeInUp** : Animation d'apparition au scroll
- **Hover effects** : Transform et shadow sur les cards
- **Transitions** : Smooth sur tous les éléments interactifs
- **Pulse animation** : Sur les icônes importantes

## Pages concernées

### Pages utilisant le layout unifié
✅ **Accueil** (`app/Views/front/home.php`)
✅ **À Propos** (`app/Views/front/about.php`)
✅ **Jeux** (`app/Views/front/games.php`)
✅ **Contact** (`app/Views/front/contact.php`)
✅ **Réservation** (système wizard)
✅ **Mon Compte** (`app/Views/account/index.php`)
✅ **Mes Réservations** (`app/Views/account/bookings.php`)
✅ **Mot de passe** (`app/Views/account/change_password.php`)

## Structure des layouts

```
app/Views/front/layouts/
├── header.php     → HTML head, meta tags, CSS, styles modernes
├── navbar.php     → Navigation fixed avec logo admin
└── footer.php     → Footer moderne avec animations
```

## Configuration du logo (Admin)

### SQL Settings
```sql
-- Déjà créé dans database_settings_logo.sql
site_logo       → URL du logo (/assets/images/logo.png)
logo_width      → Largeur en pixels (150)
logo_height     → Hauteur en pixels (50)
```

### Utilisation dans le code
```php
$settingModel = new \App\Models\SettingModel();
$siteLogo = $settingModel->getSetting('site_logo');
$logoWidth = $settingModel->getSetting('logo_width') ?: 50;
$logoHeight = $settingModel->getSetting('logo_height') ?: 50;
```

## Palette de couleurs

### Gradients principaux
- **Primary** : `#667eea` → `#764ba2`
- **Dark** : `#1a1a2e` → `#16213e`
- **Light** : `#f5f7fa` → `#e8ebf2`

### États
- **Hover** : Transform translateY(-2px) + shadow augmentée
- **Active** : Gradient primary + white text
- **Focus** : Border color primary

## Typographie

- **Font famille** : Poppins (300, 400, 500, 600, 700, 800)
- **Headers** : Font-weight 700-800
- **Body** : Font-weight 400
- **Small text** : Font-weight 300

## Responsive Breakpoints

```css
/* Mobile First */
xs: < 576px
sm: ≥ 576px
md: ≥ 768px
lg: ≥ 992px
xl: ≥ 1200px
xxl: ≥ 1400px
```

## JavaScript Animations

### Navbar Scroll
```javascript
window.addEventListener('scroll', function() {
    const navbar = document.querySelector('.modern-navbar');
    if (window.scrollY > 50) {
        navbar.classList.add('scrolled');
    }
});
```

### Smooth Scroll
```javascript
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        target.scrollIntoView({ behavior: 'smooth' });
    });
});
```

## Utilisation dans une nouvelle page

### Template minimal
```php
<?php
$title = 'Titre de ma page - FunLab Tunisie';
$activeMenu = 'menuItem'; // home, about, games, contact, account
$additionalStyles = '
    /* Styles spécifiques à la page */
';
$additionalJS = '
    <script>
        // JS spécifique à la page
    </script>
';
?>

<?= view('front/layouts/header', compact('title', 'additionalStyles')) ?>
<?= view('front/layouts/navbar', compact('activeMenu')) ?>

<!-- Contenu de la page -->
<div class="container mt-5">
    <!-- Votre contenu ici -->
</div>

<?= view('front/layouts/footer', compact('additionalJS')) ?>
```

## Classes CSS utiles

### Boutons
- `.btn-modern` : Base moderne
- `.btn-primary-modern` : Bouton principal avec gradient
- `.btn-reserve-modern` : CTA réservation

### Cards
- `.game-card` : Card de jeu avec hover
- `.content-card` : Card de contenu
- `.booking-card` : Card de réservation

### Navigation
- `.nav-link-modern` : Lien de navigation
- `.dropdown-menu-modern` : Menu dropdown
- `.dropdown-item-modern` : Item de dropdown

### Animations
- `.animate-on-scroll` : Animation fadeInUp
- `.feature-icon` : Icône avec pulse animation

## Next Steps

### À faire
1. ✅ Header fixed moderne
2. ✅ Logo depuis admin
3. ✅ Navigation unifiée
4. ✅ Footer moderne
5. ✅ Unifier toutes les pages account
6. ⏳ Ajouter interface admin pour gérer le logo
7. ⏳ Tester sur mobile/tablette

### Améliorations futures
- [ ] Animations au scroll (Intersection Observer)
- [ ] Dark mode toggle
- [ ] Transitions de page
- [ ] Lazy loading images
- [ ] PWA features

## Notes importantes

### ⚠️ Un seul layout pour tout le front
Toutes les pages front (accueil, à propos, contact, jeux, réservation, mon compte) utilisent **LES MÊMES layouts** :
- `front/layouts/header.php`
- `front/layouts/navbar.php`
- `front/layouts/footer.php`

### ⚠️ Pas de layouts séparés
Les anciens layouts `layouts/account_header.php` et `layouts/account_footer.php` ont été **supprimés**.

### ⚠️ Active menu
N'oubliez pas de passer `$activeMenu` pour highlighter le bon item :
```php
$activeMenu = 'home'; // ou 'about', 'games', 'contact', 'account'
```

## Support

Pour toute question ou problème, référez-vous à ce document ou consultez les fichiers de layouts existants.

---

**Dernière mise à jour** : <?= date('d/m/Y H:i') ?>
**Version** : 1.0.0
