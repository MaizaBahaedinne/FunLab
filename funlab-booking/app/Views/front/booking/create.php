<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réservation - FunLab Tunisie</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        .step-indicator {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .step {
            flex: 1;
            text-align: center;
            padding: 15px;
            border-bottom: 3px solid #e0e0e0;
            position: relative;
        }
        .step.active {
            border-color: #667eea;
            color: #667eea;
            font-weight: bold;
        }
        .step.completed {
            border-color: #28a745;
            color: #28a745;
        }
        .game-option {
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 20px;
            cursor: pointer;
            transition: all 0.3s;
            margin-bottom: 15px;
        }
        .game-option:hover {
            border-color: #667eea;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .game-option.selected {
            border-color: #667eea;
            background-color: #f0f4ff;
        }
        .slot-btn {
            min-width: 120px;
            margin: 5px;
        }
        .slot-btn.selected {
            background-color: #28a745;
            border-color: #28a745;
        }
        .booking-summary {
            position: sticky;
            top: 20px;
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
        }
        .qr-container {
            text-align: center;
            padding: 30px;
        }
        .qr-code-img {
            max-width: 300px;
            margin: 20px auto;
            display: block;
        }
        .payment-method {
            cursor: pointer;
            transition: all 0.3s;
            border: 2px solid #e0e0e0;
        }
        .payment-method:hover {
            border-color: #667eea;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .payment-method.selected {
            border-color: #667eea;
            background-color: #f0f4ff;
        }
        .game-card {
            cursor: pointer;
            transition: all 0.3s;
            border: 2px solid #e0e0e0;
        }
        .game-card:hover {
            border-color: #667eea;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="<?= base_url('/') ?>">
                <i class="bi bi-joystick"></i> FunLab Tunisie
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="<?= base_url('/') ?>">Accueil</a>
                <a class="nav-link active" href="<?= base_url('booking') ?>">Réserver</a>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <!-- Indicateur d'étapes -->
        <div class="step-indicator">
            <div class="step active" id="step1-indicator">
                <i class="bi bi-controller fs-3"></i>
                <div>Choisir un jeu</div>
            </div>
            <div class="step" id="step2-indicator">
                <i class="bi bi-calendar-check fs-3"></i>
                <div>Choisir un créneau</div>
            </div>
            <div class="step" id="step3-indicator">
                <i class="bi bi-person-fill fs-3"></i>
                <div>Vos informations</div>
            </div>
            <div class="step" id="step4-indicator">
                <i class="bi bi-credit-card fs-3"></i>
                <div>Paiement</div>
            </div>
            <div class="step" id="step5-indicator">
                <i class="bi bi-check-circle fs-3"></i>
                <div>Confirmation</div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <!-- ÉTAPE 1 : Sélection du jeu -->
                <div id="step1" class="booking-step">
                    <h2 class="mb-4">1. Choisissez votre activité</h2>
                    <div id="games-list">
                        <div class="text-center py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Chargement...</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ÉTAPE 2 : Sélection du créneau -->
                <div id="step2" class="booking-step" style="display: none;">
                    <h2 class="mb-4">2. Choisissez votre créneau</h2>
                    
                    <div class="mb-4">
                        <label for="booking-date" class="form-label">Date</label>
                        <input type="date" class="form-control" id="booking-date">
                    </div>

                    <div id="slots-container">
                        <p class="text-muted">Sélectionnez une date pour voir les créneaux disponibles</p>
                    </div>
                </div>

                <!-- ÉTAPE 3 : Formulaire d'informations -->
                <div id="step3" class="booking-step" style="display: none;">
                    <h2 class="mb-4">3. Vos informations</h2>
                    
                    <form id="booking-form">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nom complet *</label>
                                <input type="text" class="form-control" id="customer-name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email *</label>
                                <input type="email" class="form-control" id="customer-email" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Téléphone *</label>
                                <input type="tel" class="form-control" id="customer-phone" placeholder="+216 20 123 456" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nombre de joueurs *</label>
                                <input type="number" class="form-control" id="num-players" min="1" value="2" required>
                                <small class="text-muted" id="players-limit"></small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Notes (optionnel)</label>
                            <textarea class="form-control" id="booking-notes" rows="3" placeholder="Anniversaire, demandes spéciales..."></textarea>
                        </div>

                        <!-- Option de création de compte -->
                        <?php if (!isset($user)): ?>
                        <div class="card bg-light mb-3">
                            <div class="card-body">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="create-account" onchange="togglePasswordFields()">
                                    <label class="form-check-label" for="create-account">
                                        <strong>Créer un compte pour accéder à mes réservations</strong>
                                    </label>
                                </div>
                                <div id="password-fields" style="display: none;" class="mt-3">
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <label class="form-label">Mot de passe *</label>
                                            <input type="password" class="form-control" id="account-password" minlength="6">
                                            <small class="text-muted">Minimum 6 caractères</small>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label class="form-label">Confirmer le mot de passe *</label>
                                            <input type="password" class="form-control" id="account-password-confirm" minlength="6">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>

                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-secondary" onclick="previousStep()">
                                <i class="bi bi-arrow-left"></i> Retour
                            </button>
                            <button type="button" class="btn btn-primary flex-grow-1" onclick="proceedToPayment()">
                                <i class="bi bi-arrow-right"></i> Continuer vers le paiement
                            </button>
                        </div>
                    </form>
                </div>

                <!-- ÉTAPE 4 : Paiement -->
                <div id="step4" class="booking-step" style="display: none;">
                    <h2 class="mb-4">4. Mode de paiement</h2>
                    
                    <div class="alert alert-info mb-4">
                        <i class="bi bi-info-circle"></i>
                        <strong>Montant total à payer : <span id="payment-total">0</span> DT</strong>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="card payment-method" onclick="selectPaymentMethod('card')">
                                <div class="card-body text-center">
                                    <i class="bi bi-credit-card fs-1 text-primary"></i>
                                    <h5 class="mt-3">Carte bancaire</h5>
                                    <p class="text-muted mb-0">Paiement sécurisé en ligne</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <div class="card payment-method" onclick="selectPaymentMethod('cash')">
                                <div class="card-body text-center">
                                    <i class="bi bi-cash-stack fs-1 text-success"></i>
                                    <h5 class="mt-3">Sur place</h5>
                                    <p class="text-muted mb-0">Espèces ou carte sur place</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Code promo -->
                    <div class="card mt-3">
                        <div class="card-body">
                            <h6 class="card-title">Vous avez un code promo ?</h6>
                            <div class="input-group">
                                <input type="text" class="form-control" id="promo-code" placeholder="Entrez votre code">
                                <button class="btn btn-outline-primary" type="button" onclick="applyPromoCode()">
                                    <i class="bi bi-tag"></i> Appliquer
                                </button>
                            </div>
                            <div id="promo-result" class="mt-2"></div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="button" class="btn btn-secondary" onclick="previousStep()">
                            <i class="bi bi-arrow-left"></i> Retour
                        </button>
                        <button type="button" class="btn btn-primary flex-grow-1" id="confirm-payment-btn" disabled>
                            <i class="bi bi-check-circle"></i> Confirmer la réservation
                        </button>
                    </div>
                </div>

                <!-- ÉTAPE 5 : Confirmation -->
                <div id="step5" class="booking-step" style="display: none;">
                    <div class="text-center">
                        <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>
                        <h2 class="mt-3">Réservation confirmée !</h2>
                        <p class="lead">Votre réservation a été enregistrée avec succès</p>
                    </div>

                    <div class="card mt-4">
                        <div class="card-body">
                            <h5 class="card-title">Détails de votre réservation</h5>
                            <div id="confirmation-details"></div>
                        </div>
                    </div>

                    <div class="qr-container mt-4">
                        <h5>Votre billet électronique</h5>
                        <p class="text-muted">Présentez ce QR code à votre arrivée</p>
                        <img id="qr-code-image" class="qr-code-img" src="" alt="QR Code">
                        <div class="mt-3">
                            <button class="btn btn-primary" onclick="downloadTicket()">
                                <i class="bi bi-download"></i> Télécharger le billet
                            </button>
                            <a href="<?= base_url('/') ?>" class="btn btn-outline-secondary">
                                <i class="bi bi-house"></i> Retour à l'accueil
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Résumé de la réservation -->
            <div class="col-lg-4">
                <div class="booking-summary">
                    <h5 class="mb-3">Récapitulatif</h5>
                    <div id="summary-content">
                        <p class="text-muted">Sélectionnez un jeu pour commencer</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const API_BASE_URL = '/api';
        let currentStep = 1;
        let bookingData = {
            game: null,
            room: null,
            date: null,
            slot: null,
            user_id: <?= isset($user) ? $user['id'] : 'null' ?>,
            payment_method: null,
            promo_code: null
        };

        // Données utilisateur si connecté
        const userData = <?= isset($user) ? json_encode($user) : 'null' ?>;

        // Initialisation
        document.addEventListener('DOMContentLoaded', function() {
            loadGames();
            setupDatePicker();
            setupForm();
            prefillUserData();
        });

        // Charger les jeux disponibles
        async function loadGames() {
            try {
                const response = await fetch('/api/games');
                const result = await response.json();
                
                if (result.status === 'success' && result.data.length > 0) {
                    displayGames(result.data);
                } else {
                    // Fallback: afficher un message si aucun jeu n'est disponible
                    document.getElementById('games-list').innerHTML = `
                        <div class="col-12">
                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle"></i>
                                Aucun jeu disponible pour le moment. Veuillez réessayer plus tard.
                            </div>
                        </div>
                    `;
                }

            } catch (error) {
                console.error('Erreur chargement jeux:', error);
                document.getElementById('games-list').innerHTML = `
                    <div class="col-12">
                        <div class="alert alert-danger">
                            <i class="bi bi-x-circle"></i>
                            Erreur lors du chargement des jeux. Veuillez rafraîchir la page.
                        </div>
                    </div>
                `;
            }
        }

        function displayGames(games) {
            const container = document.getElementById('games-list');
            container.innerHTML = '';

            games.forEach(game => {
                // Icône par défaut basée sur le nom du jeu
                let icon = 'bi-controller';
                if (game.name.toLowerCase().includes('vr')) {
                    icon = 'bi-headset-vr';
                } else if (game.name.toLowerCase().includes('escape')) {
                    icon = 'bi-door-closed';
                }

                const gameCard = document.createElement('div');
                gameCard.className = 'col-md-4 mb-4';
                gameCard.innerHTML = `
                    <div class="card game-card h-100" onclick="selectGame(${game.id})">
                        <div class="card-body text-center">
                            <i class="${icon}" style="font-size: 3rem; color: var(--primary);"></i>
                            <h5 class="card-title mt-3">${game.name}</h5>
                            <p class="card-text text-muted">${game.description || ''}</p>
                            <div class="game-info mt-3">
                                <small class="d-block">
                                    <i class="bi bi-clock"></i> ${game.duration_minutes} minutes
                                </small>
                                <small class="d-block">
                                    <i class="bi bi-people"></i> ${game.min_players}-${game.max_players} joueurs
                                </small>
                                <strong class="d-block mt-2" style="font-size: 1.2rem; color: var(--primary);">
                                    ${game.price} DT
                                </strong>
                            </div>
                        </div>
                    </div>
                `;
                container.appendChild(gameCard);
            });
        }

        function selectGame(gameId) {
            // Récupérer les détails du jeu depuis l'API ou le DOM
            const gameCard = event.currentTarget;
            const gameName = gameCard.querySelector('.card-title').textContent;
            const gamePrice = parseFloat(gameCard.querySelector('strong').textContent);
            const durationText = gameCard.querySelector('.bi-clock').nextSibling.textContent.trim();
            const duration = parseInt(durationText);
            const playersText = gameCard.querySelector('.bi-people').nextSibling.textContent.trim();
            const [minPlayers, maxPlayers] = playersText.split('-').map(p => parseInt(p));

            bookingData.game = {
                id: gameId,
                name: gameName,
                min_players: minPlayers,
                max_players: maxPlayers,
                price: gamePrice,
                duration: duration
            };

            updateSummary();
            nextStep();
        }

        function setupDatePicker() {
            const dateInput = document.getElementById('booking-date');
            const today = new Date().toISOString().split('T')[0];
            dateInput.setAttribute('min', today);
            dateInput.value = today;

            dateInput.addEventListener('change', loadAvailableSlots);
            
            // Charger les créneaux automatiquement après sélection du jeu
            // (sera appelé dans selectGame via nextStep qui déclenche l'étape 2)
        }

        function prefillUserData() {
            if (userData) {
                // Pré-remplir les champs client avec les données utilisateur
                document.getElementById('customer-name').value = userData.name || '';
                document.getElementById('customer-email').value = userData.email || '';
                document.getElementById('customer-phone').value = userData.phone || '';
                
                // Rendre les champs en lecture seule si c'est un compte vérifié
                if (userData.email) {
                    document.getElementById('customer-email').setAttribute('readonly', true);
                    document.getElementById('customer-email').classList.add('bg-light');
                }
            }
        }

        function togglePasswordFields() {
            const checkbox = document.getElementById('create-account');
            const passwordFields = document.getElementById('password-fields');
            const passwordInput = document.getElementById('account-password');
            const passwordConfirm = document.getElementById('account-password-confirm');
            
            if (checkbox && checkbox.checked) {
                passwordFields.style.display = 'block';
                passwordInput.setAttribute('required', 'required');
                passwordConfirm.setAttribute('required', 'required');
            } else {
                passwordFields.style.display = 'none';
                passwordInput.removeAttribute('required');
                passwordConfirm.removeAttribute('required');
                passwordInput.value = '';
                passwordConfirm.value = '';
            }
        }

        async function loadAvailableSlots() {
            const date = document.getElementById('booking-date').value;
            const gameId = bookingData.game.id;

            if (!date || !gameId) return;

            bookingData.date = date;

            const container = document.getElementById('slots-container');
            container.innerHTML = '<div class="text-center"><div class="spinner-border text-primary"></div></div>';

            try {
                const response = await fetch(`${API_BASE_URL}/availability/slots?game_id=${gameId}&date=${date}`);
                const result = await response.json();

                if (result.status === 'success' && Object.keys(result.data).length > 0) {
                    displaySlots(result.data);
                } else {
                    container.innerHTML = '<div class="alert alert-warning">Aucun créneau disponible pour cette date</div>';
                }
            } catch (error) {
                console.error('Erreur:', error);
                container.innerHTML = '<div class="alert alert-danger">Erreur lors du chargement</div>';
            }
        }

        function displaySlots(slotsData) {
            const container = document.getElementById('slots-container');
            container.innerHTML = '';

            Object.keys(slotsData).forEach(roomKey => {
                const roomSlots = slotsData[roomKey];
                if (roomSlots.length > 0) {
                    const roomName = roomSlots[0].room_name;
                    const roomId = roomSlots[0].room_id;

                    let roomSection = `<h5 class="mt-4 mb-3">${roomName}</h5><div class="d-flex flex-wrap">`;
                    
                    roomSlots.forEach(slot => {
                        roomSection += `
                            <button class="btn btn-outline-primary slot-btn" 
                                    onclick="selectSlot(${roomId}, '${roomName}', '${slot.start}', '${slot.end}', '${slot.start_formatted}', '${slot.end_formatted}')">
                                ${slot.start_formatted} - ${slot.end_formatted}
                            </button>
                        `;
                    });
                    
                    roomSection += '</div>';
                    container.innerHTML += roomSection;
                }
            });
        }

        function selectSlot(roomId, roomName, startTime, endTime, startFormatted, endFormatted) {
            // Désélectionner tous les créneaux
            document.querySelectorAll('.slot-btn').forEach(btn => btn.classList.remove('selected'));
            event.target.classList.add('selected');

            bookingData.room = { id: roomId, name: roomName };
            bookingData.slot = { start: startTime, end: endTime, start_formatted: startFormatted, end_formatted: endFormatted };

            updateSummary();
            
            // Activer le bouton suivant
            setTimeout(() => nextStep(), 300);
        }

        function setupForm() {
            // Bouton de confirmation dans l'étape paiement
            document.getElementById('confirm-payment-btn').addEventListener('click', async () => {
                await createBooking();
            });

            document.getElementById('num-players').addEventListener('input', updateSummary);
        }

        function proceedToPayment() {
            // Valider le formulaire d'informations
            const name = document.getElementById('customer-name').value.trim();
            const email = document.getElementById('customer-email').value.trim();
            const phone = document.getElementById('customer-phone').value.trim();
            const numPlayers = parseInt(document.getElementById('num-players').value);

            if (!name || !email || !phone) {
                alert('Veuillez remplir tous les champs obligatoires');
                return;
            }

            if (numPlayers < bookingData.game.min_players || numPlayers > bookingData.game.max_players) {
                alert(`Le nombre de joueurs doit être entre ${bookingData.game.min_players} et ${bookingData.game.max_players}`);
                return;
            }

            // Valider la création de compte si demandée
            const createAccountCheckbox = document.getElementById('create-account');
            if (createAccountCheckbox && createAccountCheckbox.checked) {
                const password = document.getElementById('account-password').value;
                const passwordConfirm = document.getElementById('account-password-confirm').value;

                if (!password || password.length < 6) {
                    alert('Le mot de passe doit contenir au moins 6 caractères');
                    return;
                }

                if (password !== passwordConfirm) {
                    alert('Les mots de passe ne correspondent pas');
                    return;
                }

                // Stocker le mot de passe pour création du compte
                bookingData.create_account = true;
                bookingData.account_password = password;
            }

            // Afficher le montant total dans l'étape paiement
            const totalPrice = bookingData.game.price * numPlayers;
            document.getElementById('payment-total').textContent = totalPrice.toFixed(2);

            nextStep();
        }

        function selectPaymentMethod(method) {
            bookingData.payment_method = method;

            // Mettre à jour les styles
            document.querySelectorAll('.payment-method').forEach(el => {
                el.classList.remove('selected');
            });
            event.currentTarget.classList.add('selected');

            // Activer le bouton de confirmation
            document.getElementById('confirm-payment-btn').disabled = false;
        }

        async function applyPromoCode() {
            const code = document.getElementById('promo-code').value.trim();
            const resultDiv = document.getElementById('promo-result');

            if (!code) {
                resultDiv.innerHTML = '<div class="alert alert-warning">Veuillez entrer un code</div>';
                return;
            }

            resultDiv.innerHTML = '<div class="spinner-border spinner-border-sm"></div>';

            try {
                const response = await fetch(`${API_BASE_URL}/payment/validate-promo`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ code: code })
                });

                const result = await response.json();

                if (result.status === 'success') {
                    bookingData.promo_code = code;
                    const discount = result.data.discount_amount || 0;
                    resultDiv.innerHTML = `<div class="alert alert-success">
                        <i class="bi bi-check-circle"></i> Code appliqué ! Réduction de ${discount} DT
                    </div>`;
                    
                    // Mettre à jour le montant total
                    const numPlayers = parseInt(document.getElementById('num-players').value);
                    const totalPrice = (bookingData.game.price * numPlayers) - discount;
                    document.getElementById('payment-total').textContent = totalPrice.toFixed(2);
                } else {
                    resultDiv.innerHTML = `<div class="alert alert-danger">
                        <i class="bi bi-x-circle"></i> ${result.message || 'Code invalide'}
                    </div>`;
                }
            } catch (error) {
                console.error('Erreur:', error);
                resultDiv.innerHTML = '<div class="alert alert-danger">Erreur lors de la vérification</div>';
            }
        }

        async function createBooking() {
            const submitBtn = document.getElementById('confirm-payment-btn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Création...';

            const bookingPayload = {
                room_id: bookingData.room.id,
                game_id: bookingData.game.id,
                booking_date: bookingData.date,
                start_time: bookingData.slot.start,
                end_time: bookingData.slot.end,
                customer_name: document.getElementById('customer-name').value,
                customer_email: document.getElementById('customer-email').value,
                customer_phone: document.getElementById('customer-phone').value,
                num_players: parseInt(document.getElementById('num-players').value),
                notes: document.getElementById('booking-notes').value,
                payment_method: bookingData.payment_method
            };

            // Ajouter user_id si l'utilisateur est connecté
            if (bookingData.user_id) {
                bookingPayload.user_id = bookingData.user_id;
            }

            // Ajouter le code promo si appliqué
            if (bookingData.promo_code) {
                bookingPayload.promo_code = bookingData.promo_code;
            }

            // Ajouter les données de création de compte si demandé
            if (bookingData.create_account && bookingData.account_password) {
                bookingPayload.create_account = true;
                bookingPayload.account_password = bookingData.account_password;
            }

            try {
                const response = await fetch(`${API_BASE_URL}/booking/create`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(bookingPayload)
                });

                const result = await response.json();

                if (response.ok && result.status === 'success') {
                    showConfirmation(result);
                } else {
                    alert(result.message || 'Erreur lors de la création de la réservation');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="bi bi-check-circle"></i> Confirmer la réservation';
                }
            } catch (error) {
                console.error('Erreur:', error);
                alert('Une erreur est survenue');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="bi bi-check-circle"></i> Confirmer la réservation';
            }
        }

        function showConfirmation(result) {
            const details = `
                <dl class="row">
                    <dt class="col-sm-5">Code de confirmation</dt>
                    <dd class="col-sm-7"><strong class="text-primary">${result.data.confirmation_code}</strong></dd>
                    
                    <dt class="col-sm-5">Jeu</dt>
                    <dd class="col-sm-7">${result.data.game_name}</dd>
                    
                    <dt class="col-sm-5">Salle</dt>
                    <dd class="col-sm-7">${result.data.room_name}</dd>
                    
                    <dt class="col-sm-5">Date</dt>
                    <dd class="col-sm-7">${formatDate(result.data.booking_date)}</dd>
                    
                    <dt class="col-sm-5">Horaire</dt>
                    <dd class="col-sm-7">${result.data.start_time.substring(0,5)} - ${result.data.end_time.substring(0,5)}</dd>
                    
                    <dt class="col-sm-5">Prix total</dt>
                    <dd class="col-sm-7"><strong>${result.data.total_price} DT</strong></dd>
                </dl>
            `;

            document.getElementById('confirmation-details').innerHTML = details;

            // Afficher le QR code
            const qrUrl = `https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=${encodeURIComponent(result.data.qr_code)}`;
            document.getElementById('qr-code-image').src = qrUrl;

            nextStep();
        }

        function updateSummary() {
            let html = '';

            if (bookingData.game) {
                html += `<div class="mb-3">
                    <strong>Jeu :</strong><br>${bookingData.game.name}
                    <br><small class="text-muted">${bookingData.game.duration} minutes</small>
                </div>`;
            }

            if (bookingData.date) {
                html += `<div class="mb-3"><strong>Date :</strong><br>${formatDate(bookingData.date)}</div>`;
            }

            if (bookingData.slot) {
                html += `<div class="mb-3"><strong>Horaire :</strong><br>${bookingData.slot.start_formatted} - ${bookingData.slot.end_formatted}</div>`;
            }

            if (bookingData.room) {
                html += `<div class="mb-3"><strong>Salle :</strong><br>${bookingData.room.name}</div>`;
            }

            const numPlayers = document.getElementById('num-players')?.value || 1;
            if (bookingData.game && numPlayers) {
                const total = bookingData.game.price * numPlayers;
                html += `<div class="mb-3">
                    <strong>Nombre de joueurs :</strong><br>${numPlayers}
                </div>`;
                html += `<hr><div class="mb-3">
                    <strong>Prix total :</strong><br>
                    <span class="fs-4 text-primary">${total} DT</span>
                </div>`;
            }

            document.getElementById('summary-content').innerHTML = html || '<p class="text-muted">Aucune sélection</p>';

            if (bookingData.game) {
                document.getElementById('players-limit').textContent = 
                    `Min: ${bookingData.game.min_players}, Max: ${bookingData.game.max_players} joueurs`;
            }
        }

        function nextStep() {
            document.getElementById(`step${currentStep}`).style.display = 'none';
            document.getElementById(`step${currentStep}-indicator`).classList.remove('active');
            document.getElementById(`step${currentStep}-indicator`).classList.add('completed');
            
            currentStep++;
            
            document.getElementById(`step${currentStep}`).style.display = 'block';
            document.getElementById(`step${currentStep}-indicator`).classList.add('active');

            // Charger les créneaux automatiquement quand on arrive à l'étape 2
            if (currentStep === 2 && bookingData.game) {
                loadAvailableSlots();
            }

            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function previousStep() {
            document.getElementById(`step${currentStep}`).style.display = 'none';
            document.getElementById(`step${currentStep}-indicator`).classList.remove('active');
            
            currentStep--;
            
            document.getElementById(`step${currentStep}`).style.display = 'block';
            document.getElementById(`step${currentStep}-indicator`).classList.add('active');
            document.getElementById(`step${currentStep}-indicator`).classList.remove('completed');

            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('fr-FR', { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            });
        }

        function downloadTicket() {
            alert('Fonctionnalité de téléchargement en cours de développement');
        }
    </script>
</body>
</html>
