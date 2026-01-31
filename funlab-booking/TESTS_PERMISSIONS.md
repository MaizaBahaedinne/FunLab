# Tests Fonctionnels - Système de Permissions

## Comptes de Test

### Compte Admin
- Email: `admin@funlab.com` 
- Mot de passe: (votre mot de passe admin existant)
- Doit avoir: **Accès total à tout**

### Compte Staff
- Email: `staff@funlab.com`
- Mot de passe: `Staff2026!`
- Doit avoir: **Accès limité selon les permissions**

---

## 🧪 Tests à Effectuer

### TEST 1: Connexion et Identification
**En tant que Staff:**
- [ ] Se connecter avec `staff@funlab.com` / `Staff2026!`
- [ ] Vérifier l'en-tête: doit afficher **"Staff"** en badge jaune/orange
- [ ] Vérifier que le nom s'affiche correctement (pas "Admin")

**✅ Résultat attendu:** Badge "Staff" visible, nom du staff affiché

---

### TEST 2: Menu Sidebar - Éléments Visibles
**En tant que Staff, vérifier que ces éléments SONT visibles:**
- [ ] 📊 Dashboard
- [ ] 📅 Réservations
- [ ] 🎮 Jeux (lecture seule)
- [ ] 🚪 Salles (lecture seule)
- [ ] 🔒 Fermetures (lecture seule)
- [ ] ⭐ Avis
- [ ] 📱 Scanner QR

**En tant que Staff, vérifier que ces éléments NE SONT PAS visibles:**
- [ ] ⚙️ Paramètres (menu entier)
- [ ] 👥 Utilisateurs
- [ ] 🛡️ Rôles & Permissions

**✅ Résultat attendu:** Staff ne voit PAS les sections sensibles

---

### TEST 3: Jeux - Permissions Limitées
**En tant que Staff:**
1. [ ] Aller sur `/admin/games`
2. [ ] **VÉRIFIER:** Le bouton "➕ Ajouter un jeu" ne doit PAS être visible
3. [ ] **VÉRIFIER:** Les boutons "✏️ Modifier" ne doivent PAS être visibles
4. [ ] **VÉRIFIER:** Les boutons "🗑️ Supprimer" ne doivent PAS être visibles
5. [ ] Peut seulement **voir** la liste des jeux

**Test d'accès direct:**
- [ ] Essayer d'aller sur `/admin/games/create` directement
- **✅ Résultat attendu:** Redirection vers dashboard avec message d'erreur

---

### TEST 4: Réservations - Permissions Partielles
**En tant que Staff:**
1. [ ] Aller sur `/admin/bookings`
2. [ ] **VÉRIFIER:** Peut voir la liste des réservations
3. [ ] **VÉRIFIER:** Peut cliquer sur "Voir" une réservation
4. [ ] **VÉRIFIER:** Peut modifier les détails (statut, paiement, participants)
5. [ ] **TEST CRITIQUE:** Essayer de supprimer une réservation
   - **✅ Résultat attendu:** Le bouton supprimer ne doit PAS être visible OU message d'erreur si tenté

---

### TEST 5: Paramètres - Accès INTERDIT
**En tant que Staff, essayer d'accéder à ces URLs:**

1. [ ] `/admin/settings` 
2. [ ] `/admin/settings/general`
3. [ ] `/admin/settings/roles`
4. [ ] `/admin/settings/users`
5. [ ] `/admin/settings/mail`

**✅ Résultat attendu pour TOUTES:** Redirection vers `/admin/dashboard` avec message "Vous n'avez pas la permission"

---

### TEST 6: Gestion Utilisateurs - Tests de Sécurité Critiques

#### 6.1 Accès à la liste
- [ ] Essayer `/admin/settings/users`
- **✅ Résultat attendu:** Accès refusé (pas de permission `users.view`)

#### 6.2 Tentative de création d'admin
**Si le staff arrive à accéder (ne devrait pas):**
- [ ] Essayer de créer un utilisateur avec rôle "Admin"
- **✅ Résultat attendu:** Message "Vous ne pouvez pas créer un compte administrateur"

#### 6.3 Tentative de modification d'admin
- [ ] Essayer d'accéder à `/admin/settings/update-user/{id_admin}`
- **✅ Résultat attendu:** Message "Vous ne pouvez pas modifier un administrateur"

#### 6.4 Tentative de suppression d'admin
- [ ] Essayer d'accéder à `/admin/settings/delete-user/{id_admin}`
- **✅ Résultat attendu:** Message "Vous ne pouvez pas supprimer un administrateur"

---

### TEST 7: Menu Dropdown Utilisateur
**En tant que Staff:**
1. [ ] Cliquer sur le nom en haut à droite
2. [ ] **VÉRIFIER:** L'option "⚙️ Paramètres" ne doit PAS être visible
3. [ ] **VÉRIFIER:** L'option "👤 Mon Profil" doit être visible
4. [ ] **VÉRIFIER:** L'option "🚪 Déconnexion" doit être visible

**✅ Résultat attendu:** Pas d'accès aux paramètres depuis le menu

---

### TEST 8: Scanner QR - Permission OK
**En tant que Staff:**
1. [ ] Aller sur `/admin/scanner`
2. [ ] **VÉRIFIER:** La page se charge correctement
3. [ ] **VÉRIFIER:** Peut scanner des QR codes
4. [ ] **VÉRIFIER:** Peut valider des tickets

**✅ Résultat attendu:** Accès complet au scanner (staff a cette permission)

---

### TEST 9: Avis - Permissions Partielles
**En tant que Staff:**
1. [ ] Aller sur `/admin/reviews`
2. [ ] **VÉRIFIER:** Peut voir la liste des avis
3. [ ] **VÉRIFIER:** Peut **approuver** des avis
4. [ ] **TEST CRITIQUE:** Essayer de supprimer un avis
   - **✅ Résultat attendu:** Bouton supprimer absent OU message d'erreur

---

### TEST 10: Comparaison Admin vs Staff

**Se connecter en Admin et vérifier:**
1. [ ] Tous les menus sont visibles (Paramètres, Utilisateurs, etc.)
2. [ ] Tous les boutons d'action sont présents (Créer, Modifier, Supprimer)
3. [ ] Accès à `/admin/settings/roles` fonctionne
4. [ ] Peut modifier les permissions des rôles

**Se reconnecter en Staff et vérifier:**
1. [ ] Différences visuelles claires (moins de menus, moins de boutons)
2. [ ] Badge "Staff" au lieu de "Admin"
3. [ ] Accès restreints fonctionnent

---

## 🎯 Résumé des Permissions Staff

### ✅ CE QUE LE STAFF PEUT FAIRE:
- Voir le dashboard
- Voir/créer/modifier les réservations (PAS supprimer)
- Voir les jeux (lecture seule)
- Voir les salles (lecture seule)
- Voir/approuver les avis (PAS supprimer)
- Utiliser le scanner QR
- Modifier son propre profil

### ❌ CE QUE LE STAFF NE PEUT PAS FAIRE:
- Accéder aux paramètres système
- Créer/modifier/supprimer des jeux
- Créer/modifier/supprimer des salles
- Gérer les utilisateurs
- Voir/modifier les permissions
- Supprimer des réservations
- Supprimer des avis
- Créer des comptes admin
- Modifier/supprimer des admins

---

## 📝 Instructions pour les Tests

1. **Ouvrir deux navigateurs/fenêtres privées:**
   - Fenêtre 1: Connecté en Admin
   - Fenêtre 2: Connecté en Staff

2. **Tester chaque section systématiquement:**
   - Cocher ✅ si le test passe
   - Noter ❌ si problème détecté
   - Noter les détails des erreurs

3. **Me communiquer les résultats:**
   - "TEST 3: ❌ Le bouton Ajouter est visible pour le staff"
   - "TEST 5: ✅ Tous les accès paramètres sont bloqués"
   - etc.

4. **On corrigera ensemble** les problèmes détectés

---

## 🚨 Tests de Sécurité Critiques (Priorité Maximale)

Ces tests DOIVENT absolument être bloqués:

1. [ ] **TEST CRITIQUE 1:** Staff ne peut PAS créer un admin
2. [ ] **TEST CRITIQUE 2:** Staff ne peut PAS modifier un admin
3. [ ] **TEST CRITIQUE 3:** Staff ne peut PAS supprimer un admin
4. [ ] **TEST CRITIQUE 4:** Staff ne peut PAS accéder à `/admin/settings/roles`
5. [ ] **TEST CRITIQUE 5:** Staff ne peut PAS modifier les permissions

**Si UN SEUL de ces tests échoue = FAILLE DE SÉCURITÉ MAJEURE**

---

## 📊 Comment Me Rapporter les Résultats

Pour chaque test, me dire:
```
TEST X: [✅ PASS / ❌ FAIL]
Description: [ce qui s'est passé]
Attendu: [ce qui devrait se passer]
Problème: [si échec, détails de l'erreur]
```

**Commençons par TEST 1 - Dites-moi ce que vous voyez quand vous vous connectez en staff!** 🚀
