<h1><i class="bi bi-credit-card text-primary"></i> Système de Paiement</h1>

## 💳 Intégration Stripe

FunLab utilise **Stripe** pour les paiements en ligne sécurisés.

### Avantages Stripe
- ✅ Paiements 100% sécurisés (PCI-DSS)
- ✅ Cartes : Visa, Mastercard, Amex
- ✅ 3D Secure automatique
- ✅ Remboursements en un clic
- ✅ Dashboard de gestion
- ✅ Webhooks temps réel

## ⚙️ Configuration

### Clés API
**Paramètres → Paiement** ou fichier `.env` :

```ini
# Mode Test
stripe.publishableKey = pk_test_xxxxxxxxxxxxx
stripe.secretKey = sk_test_xxxxxxxxxxxxx

# Mode Production
stripe.publishableKey = pk_live_xxxxxxxxxxxxx
stripe.secretKey = sk_live_xxxxxxxxxxxxx

# Webhook
stripe.webhookSecret = whsec_xxxxxxxxxxxxx
```

### Obtenir les clés
1. Créez un compte sur [stripe.com](https://stripe.com)
2. **Développeurs → Clés API**
3. Copiez la clé publique et secrète
4. Activez le mode Production après tests

### Webhooks
URL à configurer dans Stripe Dashboard :
```
https://votre-domaine.com/api/payment/webhook
```

Événements à surveiller :
- `checkout.session.completed` : Paiement réussi
- `payment_intent.succeeded` : Confirmation paiement
- `charge.refunded` : Remboursement effectué

## 💰 Processus de paiement

### Flux client
1. Client crée une réservation
2. Système génère une **Checkout Session** Stripe
3. Client redirigé vers page de paiement Stripe
4. Client entre ses informations bancaires
5. Stripe valide le paiement (3D Secure si nécessaire)
6. Redirection vers page de confirmation
7. Webhook notifie le système
8. Réservation marquée "Payée"
9. Email de confirmation envoyé

### Sécurité
- ❌ Aucune donnée bancaire stockée sur vos serveurs
- ✅ Tokenisation Stripe
- ✅ SSL/TLS obligatoire
- ✅ 3D Secure 2 (SCA)

## 🔄 Gestion des remboursements

### Remboursement total
1. Ouvrez la réservation
2. **Actions → Rembourser**
3. Sélectionnez "Total"
4. Motif : Annulation, Problème technique, etc.
5. **Confirmer**

Le remboursement est :
- Immédiat sur Stripe
- Visible sur compte client sous 5-10 jours

### Remboursement partiel
Même procédure, mais entrez un montant personnalisé :
```
Montant réservation : 100 TND
Remboursement : 50 TND (pénalité 50%)
```

### Politique de remboursement
Configuration recommandée :
- **+24h avant** : Remboursement total
- **12-24h avant** : Remboursement 50%
- **-12h avant** : Aucun remboursement
- **No-show** : Aucun remboursement

## 📊 Suivi des paiements

### Dashboard Stripe
Accédez à [dashboard.stripe.com](https://dashboard.stripe.com) pour :
- Transactions en temps réel
- Rapports financiers
- Gestion des litiges
- Export comptable

### Dans FunLab Admin
**Réservations** → Filtrer par statut paiement :
- **Payé** : Tout est OK
- **En attente** : À vérifier
- **Non payé** : Relance client
- **Remboursé** : Archivé

## 💵 Paiements alternatifs

### Paiement sur place
Pour les réservations téléphoniques :
1. Créez la réservation
2. Mode de paiement : **Sur place**
3. Statut : **En attente**
4. Client paie à l'arrivée (cash ou TPE)
5. Marquez manuellement comme payé

### Espèces
1. Encaissez l'argent
2. **Paiement → Marquer comme payé**
3. Mode : **Espèces**
4. Notez le montant
5. Enregistrez en caisse

### Virement bancaire
Pour groupes/entreprises :
1. Envoyez RIB par email
2. Client effectue le virement
3. Vérifiez réception (1-3 jours)
4. Marquez comme payé
5. Joignez référence virement

## 📝 Factures

### Génération automatique
À chaque paiement :
- Facture PDF générée
- Envoyée par email
- Téléchargeable depuis le compte client

### Informations légales
Configurez dans **Paramètres → Général** :
- Raison sociale
- Numéro SIRET/TVA
- Adresse complète
- Mentions légales

### Comptabilité
Export mensuel :
1. **Rapports → Paiements**
2. Période : Mois X
3. **Export CSV**
4. Importez dans votre logiciel comptable

## 🔒 Sécurité et Conformité

### PCI-DSS
- Stripe est certifié PCI Level 1
- Vous n'avez pas besoin de certification
- Aucune donnée bancaire sur vos serveurs

### RGPD
- Données bancaires : Stockées par Stripe uniquement
- Historique paiements : Anonymisé après 3 ans
- Droit à l'effacement : Respect automatique

### SSL/TLS
Obligatoire pour accepter des paiements :
```
https://votre-domaine.com (✅)
http://votre-domaine.com (❌)
```

## 🐛 Dépannage

### Paiement refusé
Causes fréquentes :
- Fonds insuffisants
- Carte expirée
- 3D Secure échoué
- Limite de paiement dépassée

Action : Le client doit contacter sa banque

### Webhook non reçu
Vérifiez :
1. URL webhook correcte dans Stripe
2. Certificat SSL valide
3. Logs serveur : `/writable/logs/`
4. Whitelist IP Stripe si firewall

### Double paiement
Si webhook reçu 2 fois :
- Le système ignore les doublons
- Vérifiez `payment_intent_id` unique

---

<div class="alert alert-success">
    💳 Stripe traite des milliards de transactions par an. Votre système de paiement est entre de bonnes mains !
</div>
