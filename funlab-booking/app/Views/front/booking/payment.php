<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement - FunLab Tunisie</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <script src="https://js.stripe.com/v3/"></script>
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .payment-container {
            max-width: 800px;
            margin: 0 auto;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
            border: none;
        }
        .payment-method {
            border: 2px solid #dee2e6;
            border-radius: 10px;
            padding: 20px;
            cursor: pointer;
            transition: all 0.3s;
            margin-bottom: 15px;
        }
        .payment-method:hover {
            border-color: #667eea;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.2);
        }
        .payment-method.selected {
            border-color: #667eea;
            background: #f8f9ff;
        }
        .summary-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #dee2e6;
        }
        .summary-item.total {
            font-size: 1.5rem;
            font-weight: bold;
            border-bottom: none;
            padding-top: 15px;
            color: #667eea;
        }
        .promo-input {
            display: flex;
            gap: 10px;
        }
        #card-element {
            padding: 15px;
            border: 1px solid #ced4da;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="payment-container">
        <!-- Header -->
        <div class="text-center mb-4">
            <h2 class="text-white">
                <i class="bi bi-credit-card"></i> Paiement sécurisé
            </h2>
            <p class="text-white">Réservation #<?= $booking['id'] ?></p>
        </div>

        <div class="row">
            <!-- Résumé de la réservation -->
            <div class="col-md-5 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-4">
                            <i class="bi bi-receipt"></i> Récapitulatif
                        </h5>

                        <div class="mb-3">
                            <strong><?= esc($booking['game_name']) ?></strong>
                            <div class="text-muted small">
                                <i class="bi bi-calendar3"></i>
                                <?= date('d/m/Y', strtotime($booking['booking_date'])) ?>
                                à <?= date('H:i', strtotime($booking['start_time'])) ?>
                            </div>
                            <div class="text-muted small">
                                <i class="bi bi-people"></i>
                                <?= $booking['num_participants'] ?> participants
                            </div>
                        </div>

                        <hr>

                        <div class="summary-item">
                            <span>Sous-total</span>
                            <span id="subtotal"><?= number_format($pricing['subtotal'], 2) ?> TND</span>
                        </div>

                        <div class="summary-item" id="discount-row" style="display: none;">
                            <span class="text-success">
                                <i class="bi bi-tag"></i> Réduction
                            </span>
                            <span class="text-success" id="discount">0.00 TND</span>
                        </div>

                        <div class="summary-item">
                            <span>TVA (19%)</span>
                            <span id="tax"><?= number_format($pricing['tax'], 2) ?> TND</span>
                        </div>

                        <div class="summary-item total">
                            <span>Total</span>
                            <span id="total"><?= number_format($pricing['total'], 2) ?> TND</span>
                        </div>

                        <?php if ($pricing['deposit_required']): ?>
                            <div class="alert alert-info mt-3">
                                <i class="bi bi-info-circle"></i>
                                <strong>Acompte requis:</strong> <?= number_format($pricing['deposit_amount'], 2) ?> TND<br>
                                <small>Solde à payer sur place: <?= number_format($pricing['remaining_amount'], 2) ?> TND</small>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Formulaire de paiement -->
            <div class="col-md-7">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-4">
                            <i class="bi bi-wallet2"></i> Mode de paiement
                        </h5>

                        <!-- Code promo -->
                        <div class="mb-4">
                            <label class="form-label">Code promo</label>
                            <div class="promo-input">
                                <input type="text" id="promo-code" class="form-control" placeholder="Entrez votre code">
                                <button type="button" class="btn btn-outline-primary" onclick="applyPromo()">
                                    Appliquer
                                </button>
                            </div>
                            <div id="promo-message" class="mt-2"></div>
                        </div>

                        <hr>

                        <!-- Méthodes de paiement -->
                        <form id="payment-form">
                            <!-- Stripe -->
                            <div class="payment-method" onclick="selectPaymentMethod('stripe')">
                                <input type="radio" name="payment_method" value="stripe" id="method-stripe" checked>
                                <label for="method-stripe" class="ms-2">
                                    <i class="bi bi-credit-card text-primary"></i>
                                    <strong>Carte bancaire</strong>
                                    <div class="text-muted small">Paiement sécurisé via Stripe</div>
                                </label>
                            </div>

                            <!-- Élément de carte Stripe -->
                            <div id="stripe-card-container" class="mb-3">
                                <div id="card-element"></div>
                                <div id="card-errors" class="text-danger mt-2"></div>
                            </div>

                            <!-- Paiement sur place -->
                            <div class="payment-method" onclick="selectPaymentMethod('onsite')">
                                <input type="radio" name="payment_method" value="onsite" id="method-onsite">
                                <label for="method-onsite" class="ms-2">
                                    <i class="bi bi-shop text-success"></i>
                                    <strong>Payer sur place</strong>
                                    <div class="text-muted small">
                                        <?php if ($pricing['deposit_required']): ?>
                                            Acompte <?= number_format($pricing['deposit_amount'], 2) ?> TND en ligne
                                        <?php else: ?>
                                            Réservation sans paiement immédiat
                                        <?php endif; ?>
                                    </div>
                                </label>
                            </div>

                            <div class="d-grid gap-2 mt-4">
                                <button type="submit" class="btn btn-primary btn-lg" id="submit-button">
                                    <i class="bi bi-lock"></i>
                                    <span id="button-text">Payer <?= number_format($pricing['total'], 2) ?> TND</span>
                                </button>
                                <a href="<?= base_url('booking/confirm/' . $booking['id']) ?>" class="btn btn-outline-secondary">
                                    Retour
                                </a>
                            </div>
                        </form>

                        <div class="text-center mt-3">
                            <small class="text-muted">
                                <i class="bi bi-shield-check"></i>
                                Paiement 100% sécurisé - Vos données sont protégées
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const bookingId = <?= $booking['id'] ?>;
        let currentTotal = <?= $pricing['total'] ?>;
        let discountAmount = 0;
        let selectedMethod = 'stripe';

        // Configuration Stripe
        const stripe = Stripe('<?= getenv('STRIPE_PUBLISHABLE_KEY') ?>');
        const elements = stripe.elements();
        const cardElement = elements.create('card', {
            style: {
                base: {
                    fontSize: '16px',
                    color: '#32325d',
                    fontFamily: '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto',
                    '::placeholder': { color: '#aab7c4' }
                }
            }
        });
        cardElement.mount('#card-element');

        cardElement.on('change', (event) => {
            const displayError = document.getElementById('card-errors');
            if (event.error) {
                displayError.textContent = event.error.message;
            } else {
                displayError.textContent = '';
            }
        });

        // Sélection méthode de paiement
        function selectPaymentMethod(method) {
            selectedMethod = method;
            document.getElementById('method-' + method).checked = true;
            
            document.querySelectorAll('.payment-method').forEach(el => {
                el.classList.remove('selected');
            });
            event.currentTarget.classList.add('selected');

            // Afficher/masquer élément carte
            const cardContainer = document.getElementById('stripe-card-container');
            cardContainer.style.display = method === 'stripe' ? 'block' : 'none';

            // Mettre à jour le bouton
            updateButton();
        }

        // Appliquer code promo
        async function applyPromo() {
            const code = document.getElementById('promo-code').value;
            const messageEl = document.getElementById('promo-message');

            if (!code) {
                messageEl.innerHTML = '<div class="alert alert-warning">Entrez un code promo</div>';
                return;
            }

            try {
                const response = await fetch('<?= base_url('api/payment/validate-promo') ?>', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        code: code,
                        subtotal: <?= $pricing['subtotal'] ?>
                    })
                });

                const data = await response.json();

                if (data.success) {
                    discountAmount = data.discount;
                    updateTotals();
                    messageEl.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
                } else {
                    messageEl.innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
                }
            } catch (error) {
                messageEl.innerHTML = '<div class="alert alert-danger">Erreur de connexion</div>';
            }
        }

        // Mettre à jour les totaux
        function updateTotals() {
            const subtotal = <?= $pricing['subtotal'] ?>;
            const taxRate = <?= $pricing['tax_rate'] ?>;
            
            const newSubtotal = subtotal - discountAmount;
            const newTax = newSubtotal * taxRate;
            const newTotal = newSubtotal + newTax;

            document.getElementById('discount').textContent = discountAmount.toFixed(2) + ' TND';
            document.getElementById('discount-row').style.display = discountAmount > 0 ? 'flex' : 'none';
            document.getElementById('tax').textContent = newTax.toFixed(2) + ' TND';
            document.getElementById('total').textContent = newTotal.toFixed(2) + ' TND';

            currentTotal = newTotal;
            updateButton();
        }

        // Mettre à jour le bouton
        function updateButton() {
            const buttonText = document.getElementById('button-text');
            <?php if ($pricing['deposit_required']): ?>
                const amount = selectedMethod === 'stripe' ? <?= $pricing['deposit_amount'] ?> : 0;
            <?php else: ?>
                const amount = selectedMethod === 'stripe' ? currentTotal : 0;
            <?php endif; ?>

            if (selectedMethod === 'onsite') {
                buttonText.textContent = 'Confirmer la réservation';
            } else {
                buttonText.textContent = 'Payer ' + amount.toFixed(2) + ' TND';
            }
        }

        // Soumettre le paiement
        document.getElementById('payment-form').addEventListener('submit', async (e) => {
            e.preventDefault();

            const submitButton = document.getElementById('submit-button');
            submitButton.disabled = true;
            submitButton.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Traitement...';

            try {
                if (selectedMethod === 'stripe') {
                    await processStripePayment();
                } else {
                    await processOnsitePayment();
                }
            } catch (error) {
                alert('Erreur: ' + error.message);
                submitButton.disabled = false;
                updateButton();
            }
        });

        // Traiter paiement Stripe
        async function processStripePayment() {
            const response = await fetch('<?= base_url('api/payment/stripe/create') ?>', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    booking_id: bookingId,
                    amount: <?= $pricing['deposit_required'] ? $pricing['deposit_amount'] : $pricing['total'] ?>,
                    payment_type: '<?= $pricing['deposit_required'] ? 'deposit' : 'full' ?>'
                })
            });

            const data = await response.json();

            if (!data.success) {
                throw new Error(data.message);
            }

            const { error } = await stripe.confirmCardPayment(data.client_secret, {
                payment_method: { card: cardElement }
            });

            if (error) {
                throw new Error(error.message);
            }

            window.location.href = '<?= base_url('booking/success/' . $booking['id']) ?>';
        }

        // Traiter paiement sur place
        async function processOnsitePayment() {
            const response = await fetch('<?= base_url('api/payment/onsite') ?>', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    booking_id: bookingId,
                    amount: <?= $pricing['deposit_required'] ? $pricing['deposit_amount'] : 0 ?>,
                    payment_type: '<?= $pricing['deposit_required'] ? 'deposit' : 'full' ?>'
                })
            });

            const data = await response.json();

            if (!data.success) {
                throw new Error(data.message);
            }

            window.location.href = '<?= base_url('booking/success/' . $booking['id']) ?>';
        }

        // Initialiser
        document.querySelector('.payment-method').classList.add('selected');
        updateButton();
    </script>
</body>
</html>
