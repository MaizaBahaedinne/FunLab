# 🔧 Solution au problème Facebook Sharing (Erreur 403)

## 🚨 Problème identifié

Facebook retournait une **erreur 403 Forbidden** avec le message :
> "This response code could be due to a robots.txt block. Please allowlist facebookexternalhit"

Le bot de Facebook (`facebookexternalhit`) était **bloqué** et ne pouvait pas scraper les balises Open Graph.

---

## ✅ Solutions appliquées

### 1. **Filtre CodeIgniter pour autoriser les bots sociaux**

**Fichier créé :** `app/Filters/AllowSocialBots.php`

Ce filtre détecte les User-Agent des bots sociaux (Facebook, Twitter, LinkedIn, etc.) et les autorise automatiquement.

**Bots autorisés :**
- `facebookexternalhit` (Facebook)
- `Facebot` (Facebook)
- `Twitterbot` (Twitter)
- `LinkedInBot` (LinkedIn)
- `WhatsApp`
- `Slackbot`
- `TelegramBot`
- `Discordbot`
- `Pinterestbot`

**Configuration :** Activé globalement dans `app/Config/Filters.php`

---

### 2. **Mise à jour du robots.txt**

**Fichier modifié :** `public/robots.txt`

Autorise explicitement le bot Facebook et autres bots sociaux :

```txt
# Autoriser tous les robots par défaut
User-agent: *
Disallow: /admin/
Disallow: /api/v1/payment/
Allow: /

# Autoriser explicitement les bots des réseaux sociaux
User-agent: facebookexternalhit
Allow: /

User-agent: Facebot
Allow: /

User-agent: Twitterbot
Allow: /

# ... etc
```

---

### 3. **Mise à jour du .htaccess**

**Fichier modifié :** `public/.htaccess`

Ajout de règles Apache pour autoriser les bots sociaux au niveau du serveur web :

```apache
# Autoriser les bots des réseaux sociaux
<IfModule mod_rewrite.c>
    RewriteCond %{HTTP_USER_AGENT} facebookexternalhit [NC,OR]
    RewriteCond %{HTTP_USER_AGENT} Facebot [NC,OR]
    RewriteCond %{HTTP_USER_AGENT} Twitterbot [NC,OR]
    # ... etc
    RewriteRule .* - [E=SOCIAL_BOT:1]
</IfModule>
```

---

### 4. **Pages de test créées**

#### **Test 1 : User Agent Test**
**URL :** `https://funlab.faltaagency.com/social-bot-test`

Affiche les informations du User-Agent en JSON pour vérifier que le bot est détecté.

#### **Test 2 : Open Graph Test**
**URL :** `https://funlab.faltaagency.com/og-test`

Page complète avec :
- Toutes les balises Open Graph configurées
- Instructions de test
- Liens directs vers le Facebook Debugger

---

## 🧪 Comment tester maintenant

### Étape 1 : Tester la page de test

1. Va sur : **https://funlab.faltaagency.com/og-test**
2. Vérifie que la page s'affiche correctement
3. Fais "Afficher le code source" et cherche les balises `<meta property="og:...">` dans le `<head>`

### Étape 2 : Tester avec Facebook Debugger

1. Va sur : **https://developers.facebook.com/tools/debug/**
2. Entre l'URL : `https://funlab.faltaagency.com/og-test`
3. Clique sur **"Déboguer"** ou **"Debug"**
4. Vérifie que :
   - ✅ Le code de réponse est **200 OK** (et non plus 403)
   - ✅ Les balises Open Graph sont bien détectées
   - ✅ L'aperçu s'affiche correctement

### Étape 3 : Tester un vrai jeu

1. Va sur : **https://developers.facebook.com/tools/debug/**
2. Entre l'URL : `https://funlab.faltaagency.com/games/1`
3. Clique sur **"Debug"**
4. Si ça fonctionne, clique sur **"Scrape Again"** pour forcer le refresh

---

## ⚠️ Si le problème persiste

Si Facebook retourne toujours une erreur 403, le problème vient probablement de **ton hébergeur** :

### Causes possibles :

1. **Firewall / WAF (Web Application Firewall)**
   - CloudFlare, Sucuri, ou autre service de sécurité qui bloque les IPs de Facebook
   - **Solution :** Ajouter les IPs de Facebook en liste blanche

2. **mod_security**
   - Module Apache de sécurité qui bloque les requêtes suspectes
   - **Solution :** Désactiver mod_security pour les User-Agent des bots sociaux

3. **Restrictions IP**
   - L'hébergeur bloque les IPs de Facebook
   - **Solution :** Contacter le support de l'hébergeur

4. **Limite de rate limiting**
   - Trop de requêtes depuis l'IP de Facebook
   - **Solution :** Augmenter les limites ou ajouter une exception

### Comment contacter ton hébergeur

Envoie ce message au support :

```
Bonjour,

Le bot de Facebook (facebookexternalhit) reçoit une erreur 403 Forbidden
quand il tente d'accéder à mon site pour scraper les balises Open Graph.

Pouvez-vous vérifier si :
1. Les IPs de Facebook sont bloquées par le firewall ?
2. mod_security bloque le User-Agent "facebookexternalhit" ?
3. Des règles de sécurité empêchent l'accès au site depuis Facebook ?

URL à tester : https://funlab.faltaagency.com/games/1

Merci de whitelister le bot Facebook pour permettre le partage sur les
réseaux sociaux.
```

---

## 📋 Checklist finale

Avant de tester :

- [ ] Fichiers modifiés déployés sur le serveur
- [ ] Cache du serveur vidé (si applicable)
- [ ] Page de test accessible : `/og-test`
- [ ] Code source contient bien les balises Open Graph
- [ ] Test avec Facebook Debugger effectué
- [ ] "Scrape Again" cliqué pour forcer le refresh
- [ ] Test de partage sur Facebook réel

---

## 🎯 Image par défaut pour Open Graph

**Important :** Tu dois créer une image par défaut !

**Fichier attendu :** `public/assets/images/og-default.jpg`

**Spécifications :**
- Taille : **1200x630 pixels**
- Format : JPG ou PNG
- Contenu : Logo FunLab + texte attractif

**Instructions détaillées :** Voir `public/assets/images/README.md`

---

## 📞 Support

Si tu as besoin d'aide supplémentaire :

1. Vérifie les logs du serveur : `/writable/logs/`
2. Teste avec curl depuis un terminal :
   ```bash
   curl -A "facebookexternalhit/1.1" https://funlab.faltaagency.com/games/1
   ```
3. Contacte ton hébergeur avec les infos ci-dessus

---

**Date de modification :** 25 janvier 2026  
**Statut :** ✅ Configuration complète appliquée
