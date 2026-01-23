# 💳 Système de Paiement - FunLab Booking

## ✅ Phase 7 Complète

Le système de paiement hybride est maintenant entièrement développé avec :

### 🎯 Fonctionnalités Implémentées

#### 1. **Calcul Automatique des Prix**
- ✅ Prix par session ou par personne
- ✅ TVA 19% (Tunisie)
- ✅ Codes promo (pourcentage ou montant fixe)
- ✅ Acompte configurable (par défaut 30%)
- ✅ Calcul du solde restant

#### 2. **Méthodes de Paiement**
- ✅ **Stripe** - Carte bancaire en ligne (sécurisé)
- ✅ **Sur place** - Cash ou carte au centre
- ✅ Acompte en ligne + solde sur place
- ✅ Paiement complet en ligne

#### 3. **Gestion des Paiements**
- ✅ Création de paiement avec PaymentIntent (Stripe)
- ✅ Confirmation automatique via webhook
- ✅ Suivi des transactions
- ✅ Statuts : pending, completed, failed, refunded
- ✅ Historique complet des paiements

#### 4. **Codes Promo**
- ✅ Réduction en pourcentage ou fixe
- ✅ Montant minimum requis
- ✅ Plafond de réduction
- ✅ Limite d'utilisation
- ✅ Période de validité
- ✅ Application par jeu spécifique

#### 5. **Factures**
- ✅ Génération automatique
- ✅ Numéro unique (INV-2026-00001)
- ✅ Détails complets (items, TVA, total)
- ✅ Statuts : draft, sent, paid, cancelled
- ✅ Export PDF (à développer)

#### 6. **Remboursements**
- ✅ Remboursement total ou partiel
- ✅ Via Stripe API
- ✅ Tracking des remboursements
- ✅ Raison du remboursement

---

## 📁 Fichiers Créés

### Base de données
- ✅ **database_payments.sql** - Tables payments, invoices, promo_codes, promo_code_usage
  - Vues : v_financial_stats, v_payments_full
  - Codes promo par défaut : WELCOME10, SUMMER2026, VIP50

### Services
- ✅ **PaymentService.php** - Logique métier paiement (500+ lignes)
  - `calculateBookingTotal()` - Calcul prix avec promo + TVA
  - `calculatePromoDiscount()` - Validation code promo
  - `createStripePayment()` - Création PaymentIntent
  - `confirmStripePayment()` - Confirmation webhook
  - `createCashPayment()` - Paiement sur place
  - `confirmCashPayment()` - Validation staff
  - `generateInvoice()` - Génération facture
  - `refundPayment()` - Remboursement Stripe

### API
- ✅ **PaymentApi.php** - 10 endpoints REST
  - `POST /api/payment/calculate` - Calculer total
  - `POST /api/payment/validate-promo` - Valider code promo
  - `POST /api/payment/stripe/create` - Créer PaymentIntent
  - `POST /api/payment/stripe/webhook` - Webhook Stripe
  - `POST /api/payment/onsite` - Paiement sur place
  - `POST /api/payment/confirm/{id}` - Confirmer paiement cash
  - `POST /api/payment/refund/{id}` - Rembourser
  - `GET /api/payment/history` - Historique client
  - `POST /api/payment/invoice/generate` - Générer facture

### Views
- ✅ **booking/payment.php** - Interface de paiement complète
  - Sélection méthode (Stripe / Sur place)
  - Intégration Stripe Elements
  - Champ code promo avec validation AJAX
  - Récapitulatif dynamique (sous-total, TVA, total)
  - Gestion acompte si requis

### Configuration
- ✅ **Routes.php** - Routes API paiement ajoutées
- ✅ **env_payment.example** - Variables Stripe

---

## 🚀 Installation & Configuration

### 1. **Installation de Stripe PHP**

```bash
cd /Users/mac/Documents/FunLab/funlab-booking
composer require stripe/stripe-php
```

### 2. **Exécuter le script SQL**

```bash
mysql -u root -p funlab_booking < database_payments.sql
```

Cela créera :
- Table `payments` avec colonnes Stripe
- Table `invoices` avec génération auto du numéro
- Table `promo_codes` avec 3 codes par défaut
- Table `promo_code_usage` pour tracking
- Colonnes `price`, `deposit_required` dans `games`
- Colonnes `total_price`, `payment_status` dans `bookings`

### 3. **Configuration Stripe**

#### A) Créer un compte Stripe
1. Allez sur https://dashboard.stripe.com/register
2. Créez un compte (mode test gratuit)

#### B) Récupérer les clés API
1. Dans le Dashboard Stripe → **Developers** → **API keys**
2. Copiez :
   - **Publishable key** (commence par `pk_test_`)
   - **Secret key** (commence par `sk_test_`)

#### C) Configurer le Webhook
1. Dans **Developers** → **Webhooks** → **Add endpoint**
2. URL du endpoint : `https://votre-domaine.com/api/payment/stripe/webhook`
3. Événements à écouter :
   - `payment_intent.succeeded`
   - `payment_intent.payment_failed`
4. Copiez le **Signing secret** (commence par `whsec_`)

#### D) Ajouter dans le fichier `.env`

```env
# Stripe Configuration
STRIPE_PUBLISHABLE_KEY=pk_test_your_key_here
STRIPE_SECRET_KEY=sk_test_your_key_here
STRIPE_WEBHOOK_SECRET=whsec_your_webhook_secret_here
```

### 4. **Mettre à jour les prix des jeux**

```sql
UPDATE games SET 
    price = 80.00,  -- Prix par session
    price_per_person = 20.00,  -- Ou prix par personne
    deposit_required = 1,  -- Acompte obligatoire
    deposit_percentage = 30.00  -- 30%
WHERE id = 1;  -- Pour chaque jeu
```

---

## 🎨 Workflow de Paiement

### Parcours Client

1. **Sélection activité** → `/booking/create`
2. **Formulaire réservation** → Saisie infos
3. **Page de paiement** → `/booking/payment/{id}`
   - Récapitulatif de la réservation
   - Saisie code promo (optionnel)
   - Choix méthode :
     - **Carte bancaire** : Stripe Elements
     - **Sur place** : Acompte ou réservation sans paiement
4. **Confirmation** → Paiement traité
5. **Succès** → `/booking/success/{id}`
   - Confirmation email
   - QR Code
   - Facture

### Flux Stripe

```
Client → Formulaire carte
    ↓
API: POST /api/payment/stripe/create
    ↓
Stripe: Création PaymentIntent
    ↓
Client: Confirmation carte (3D Secure si requis)
    ↓
Stripe Webhook: payment_intent.succeeded
    ↓
API: POST /api/payment/stripe/webhook
    ↓
PaymentService: confirmStripePayment()
    ↓
Update: payment.status = 'completed'
    ↓
Update: booking.payment_status = 'paid'
    ↓
Génération: Facture + Email confirmation
```

### Flux Sur Place

```
Client → Choix "Payer sur place"
    ↓
API: POST /api/payment/onsite
    ↓
Création: payment (status = 'pending')
    ↓
Update: booking.status = 'confirmed'
    ↓
Email: Confirmation avec rappel paiement
    ↓
Client arrive au centre
    ↓
Staff scan QR Code
    ↓
Staff: Confirmation paiement cash
    ↓
API: POST /api/payment/confirm/{id}
    ↓
Update: payment.status = 'completed'
```

---

## 💡 Codes Promo Par Défaut

| Code | Type | Réduction | Conditions | Validité |
|------|------|-----------|------------|----------|
| **WELCOME10** | Pourcentage | 10% | Min 50 TND, Max 20 TND | 1 an |
| **SUMMER2026** | Pourcentage | 15% | Min 100 TND, Max 50 TND, Limite 100 | 30/09/2026 |
| **VIP50** | Fixe | 50 TND | Min 200 TND, Limite 50 | 6 mois |

### Créer un nouveau code promo

```sql
INSERT INTO promo_codes (code, description, discount_type, discount_value, min_amount, max_discount, valid_until) 
VALUES ('NOEL2026', 'Offre de Noël -20%', 'percentage', 20.00, 80.00, 40.00, '2026-12-31 23:59:59');
```

---

## 🔐 Sécurité

### Paiements Stripe
- ✅ HTTPS obligatoire en production
- ✅ Clés API secrètes côté serveur
- ✅ Webhook signature verification
- ✅ 3D Secure automatique si requis
- ✅ PCI Compliance (Stripe gère les cartes)

### Codes Promo
- ✅ Validation serveur (pas client)
- ✅ Limite d'utilisation
- ✅ Vérification période validité
- ✅ Montant minimum

### Remboursements
- ✅ Réservé aux admins
- ✅ Tracking complet
- ✅ Raison obligatoire
- ✅ Remboursement via Stripe API

---

## 📊 Base de Données

### Table `payments`
```sql
- id, booking_id, customer_id
- amount, currency (TND)
- payment_method (stripe, cash, card, bank_transfer)
- payment_type (full, deposit, balance)
- status (pending, completed, failed, refunded, cancelled)
- transaction_id, stripe_payment_intent, stripe_charge_id
- paid_at, refunded_at, refund_amount, refund_reason
```

### Table `invoices`
```sql
- id, invoice_number (INV-2026-00001)
- booking_id, customer_id
- amount_subtotal, amount_tax, amount_discount, amount_total
- tax_rate (19.00)
- items (JSON), status, issued_at, due_at, paid_at
```

### Table `promo_codes`
```sql
- id, code, description
- discount_type (percentage, fixed)
- discount_value, min_amount, max_discount
- usage_limit, usage_count
- valid_from, valid_until, is_active
```

---

## 🧪 Tests

### Test 1: Paiement Carte (Stripe Test Mode)

**Cartes de test Stripe** :
- ✅ Succès : `4242 4242 4242 4242`
- ❌ Échec : `4000 0000 0000 0002`
- 🔐 3D Secure requis : `4000 0025 0000 3155`

**Procédure** :
1. Créez une réservation
2. Page paiement → Sélectionnez "Carte bancaire"
3. Entrez carte test + expiration future + CVC 123
4. Cliquez "Payer"
5. ✅ Devrait rediriger vers page succès

### Test 2: Code Promo

1. Page paiement
2. Entrez code : `WELCOME10`
3. Cliquez "Appliquer"
4. ✅ Réduction de 10% appliquée (max 20 TND)

### Test 3: Paiement Sur Place

1. Page paiement → Sélectionnez "Payer sur place"
2. Si acompte requis : Montant réduit
3. Cliquez "Confirmer la réservation"
4. ✅ Réservation confirmée, paiement pending

### Test 4: Webhook Stripe

```bash
# Installer Stripe CLI
brew install stripe/stripe-cli/stripe

# Se connecter
stripe login

# Écouter les webhooks localement
stripe listen --forward-to localhost:8080/api/payment/stripe/webhook

# Déclencher un événement test
stripe trigger payment_intent.succeeded
```

---

## 📈 Statistiques Financières

### Vue SQL : `v_financial_stats`

```sql
SELECT * FROM v_financial_stats WHERE payment_date >= '2026-01-01';
```

Affiche par jour :
- Nombre de paiements
- Nombre de réservations
- Revenu total
- Revenu Stripe
- Revenu cash
- Montant moyen

### Requête : Top clients

```sql
SELECT 
    u.first_name, u.last_name, u.email,
    COUNT(p.id) as total_payments,
    SUM(p.amount) as total_spent
FROM users u
JOIN payments p ON u.id = p.customer_id
WHERE p.status = 'completed'
GROUP BY u.id
ORDER BY total_spent DESC
LIMIT 10;
```

---

## 🔧 Intégrations Futures

### PayPal
- Ajouter `PayPalService.php`
- Nouvelle méthode dans `payment.php`
- Routes API PayPal

### Paiement Mobile
- Flouci (Tunisie)
- D17 (Tunisie)
- API REST similaire à Stripe

### Carte Cadeau
- Table `gift_cards`
- Code unique
- Solde déductible

### Programme de Fidélité
- Points par réservation
- Réductions automatiques
- Table `loyalty_points`

---

## 📝 Checklist de Déploiement

- [ ] Installer Stripe PHP : `composer require stripe/stripe-php`
- [ ] Exécuter `database_payments.sql`
- [ ] Configurer clés Stripe dans `.env`
- [ ] Activer HTTPS en production
- [ ] Configurer webhook Stripe en production
- [ ] Mettre à jour les prix des jeux
- [ ] Tester paiement carte (mode test)
- [ ] Tester codes promo
- [ ] Tester webhook Stripe CLI
- [ ] Passer en mode Live Stripe (production)
- [ ] Créer codes promo personnalisés
- [ ] Configurer emails de confirmation paiement

---

## 🐛 Troubleshooting

### "Stripe API key required"
- ❌ `.env` non configuré
- ✅ Vérifiez `STRIPE_SECRET_KEY` dans `.env`

### "Payment intent creation failed"
- ❌ Clé Stripe invalide
- ✅ Vérifiez que vous utilisez la bonne clé (test/live)

### "Webhook signature verification failed"
- ❌ Mauvais `STRIPE_WEBHOOK_SECRET`
- ✅ Copiez le secret depuis Stripe Dashboard

### "Promo code not found"
- ❌ Code n'existe pas ou expiré
- ✅ Vérifiez `valid_until` dans `promo_codes`

### "Payment already processed"
- ❌ Tentative de double paiement
- ✅ Vérifiez `payment.status` avant de créer

---

## 📞 Support

Pour toute question sur le système de paiement :
- Documentation Stripe : https://stripe.com/docs
- Dashboard Stripe : https://dashboard.stripe.com/
- Test des cartes : https://stripe.com/docs/testing

---

**Version**: 1.0.0  
**Date**: Janvier 2026  
**Auteur**: GitHub Copilot pour FunLab Tunisie 🇹🇳
