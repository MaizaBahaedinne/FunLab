<?php
$title = 'Gestion des Réservations';
$pageTitle = 'Gestion des Réservations';
$activeMenu = 'bookings';
$breadcrumbs = ['Admin' => base_url('admin'), 'Réservations' => null];
$additionalStyles = '
#calendar {
    max-width: 100%;
    margin: 0 auto;
}
.fc-event {
    cursor: pointer;
}
.status-badge {
    font-size: 0.75rem;
    padding: 2px 6px;
}
.booking-modal .modal-body {
    max-height: 70vh;
    overflow-y: auto;
}
';
$additionalCSS = '<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet">';
?>

<?= view('admin/layouts/header', compact('title', 'additionalCSS', 'additionalStyles')) ?>
<?= view('admin/layouts/sidebar', compact('activeMenu')) ?>
<?= view('admin/layouts/topbar', compact('pageTitle', 'breadcrumbs')) ?>

            <div class="container-fluid p-4">
                <div class="mb-3">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBookingModal">
                        <i class="bi bi-plus-circle"></i> Nouvelle réservation
                    </button>
                </div>
                <!-- Filtres -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Statut</label>
                                <select class="form-select" id="filter-status">
                                    <option value="">Tous les statuts</option>
                                    <option value="pending">En attente</option>
                                    <option value="confirmed">Confirmé</option>
                                    <option value="in_progress">En cours</option>
                                    <option value="completed">Terminé</option>
                                    <option value="cancelled">Annulé</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Salle</label>
                                <select class="form-select" id="filter-room">
                                    <option value="">Toutes les salles</option>
                                    <!-- Chargé dynamiquement -->
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Jeu</label>
                                <select class="form-select" id="filter-game">
                                    <option value="">Tous les jeux</option>
                                    <!-- Chargé dynamiquement -->
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">&nbsp;</label>
                                <button class="btn btn-secondary w-100" onclick="resetFilters()">
                                    <i class="bi bi-x-circle"></i> Réinitialiser
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Calendrier -->
                <div class="card">
                    <div class="card-body">
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Détails Réservation -->
    <div class="modal fade booking-modal" id="bookingDetailsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Détails de la Réservation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="booking-details-content">
                    <div class="text-center py-4">
                        <div class="spinner-border" role="status"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <button type="button" class="btn btn-danger" id="btn-cancel-booking">
                        <i class="bi bi-x-circle"></i> Annuler la réservation
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Nouvelle Réservation - Wizard -->
    <div class="modal fade" id="addBookingModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nouvelle Réservation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- Progress Steps -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between">
                            <div class="wizard-step active" data-step="1">
                                <div class="wizard-step-number">1</div>
                                <div class="wizard-step-label">Jeu</div>
                            </div>
                            <div class="wizard-step" data-step="2">
                                <div class="wizard-step-number">2</div>
                                <div class="wizard-step-label">Salle</div>
                            </div>
                            <div class="wizard-step" data-step="3">
                                <div class="wizard-step-number">3</div>
                                <div class="wizard-step-label">Date & Heure</div>
                            </div>
                            <div class="wizard-step" data-step="4">
                                <div class="wizard-step-number">4</div>
                                <div class="wizard-step-label">Participants</div>
                            </div>
                            <div class="wizard-step" data-step="5">
                                <div class="wizard-step-number">5</div>
                                <div class="wizard-step-label">Confirmation</div>
                            </div>
                        </div>
                        <div class="progress mt-2" style="height: 4px;">
                            <div class="progress-bar" id="wizardProgress" role="progressbar" style="width: 20%"></div>
                        </div>
                    </div>

                    <form action="/admin/bookings/create" method="POST" id="createBookingForm">
                        <!-- Step 1: Choix du jeu -->
                        <div class="wizard-content" data-step="1">
                            <h5 class="mb-3">Choisissez un jeu</h5>
                            <div class="row" id="gamesGrid"></div>
                            <input type="hidden" name="game_id" id="selected_game_id" required>
                        </div>

                        <!-- Step 2: Choix de la salle -->
                        <div class="wizard-content d-none" data-step="2">
                            <h5 class="mb-3">Choisissez une salle</h5>
                            <div class="row" id="roomsGrid"></div>
                            <input type="hidden" name="room_id" id="selected_room_id" required>
                        </div>

                        <!-- Step 3: Date et Heure -->
                        <div class="wizard-content d-none" data-step="3">
                            <h5 class="mb-3">Sélectionnez la date et l\'heure</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label">Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control form-control-lg" name="booking_date" id="booking_date" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Créneaux disponibles</label>
                                    <div id="timeSlotsContainer" class="mt-2"></div>
                                    <input type="hidden" name="start_time" id="selected_time" required>
                                </div>
                            </div>
                        </div>

                        <!-- Step 4: Nombre de participants -->
                        <div class="wizard-content d-none" data-step="4">
                            <h5 class="mb-3">Nombre de participants et informations</h5>
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label">Nombre de participants <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control form-control-lg" name="num_participants" id="num_participants" min="1" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Prénom <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="customer_first_name" id="customer_first_name" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Nom <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="customer_last_name" id="customer_last_name" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" name="customer_email" id="customer_email" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Téléphone <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control" name="customer_phone" id="customer_phone" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Notes (optionnel)</label>
                                    <textarea class="form-control" name="notes" id="notes" rows="2"></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Step 5: Confirmation -->
                        <div class="wizard-content d-none" data-step="5">
                            <h5 class="mb-3">Récapitulatif de la réservation</h5>
                            <div class="card">
                                <div class="card-body">
                                    <div id="bookingSummary"></div>
                                </div>
                            </div>
                            <input type="hidden" name="total_price" id="total_price">
                            <input type="hidden" name="status" value="confirmed">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="wizardPrevBtn" style="display:none;">
                        <i class="bi bi-arrow-left"></i> Précédent
                    </button>
                    <button type="button" class="btn btn-primary" id="wizardNextBtn">
                        Suivant <i class="bi bi-arrow-right"></i>
                    </button>
                    <button type="submit" form="createBookingForm" class="btn btn-success d-none" id="wizardSubmitBtn">
                        <i class="bi bi-check-circle"></i> Confirmer la réservation
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
    .wizard-step {
        text-align: center;
        flex: 1;
        position: relative;
    }
    .wizard-step-number {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #e9ecef;
        color: #6c757d;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        margin-bottom: 5px;
    }
    .wizard-step.active .wizard-step-number,
    .wizard-step.completed .wizard-step-number {
        background: #0d6efd;
        color: white;
    }
    .wizard-step-label {
        font-size: 12px;
        color: #6c757d;
    }
    .wizard-step.active .wizard-step-label {
        color: #0d6efd;
        font-weight: 600;
    }
    .game-card, .room-card {
        cursor: pointer;
        transition: all 0.3s;
        border: 2px solid #dee2e6;
    }
    .game-card:hover, .room-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    .game-card.selected, .room-card.selected {
        border-color: #0d6efd;
        background: #e7f1ff;
    }
    .time-slot {
        padding: 10px;
        margin: 5px;
        border: 2px solid #dee2e6;
        border-radius: 8px;
        cursor: pointer;
        text-align: center;
        transition: all 0.3s;
    }
    .time-slot:hover {
        border-color: #0d6efd;
        background: #f8f9fa;
    }
    .time-slot.selected {
        border-color: #0d6efd;
        background: #0d6efd;
        color: white;
    }
    .time-slot.disabled {
        opacity: 0.5;
        cursor: not-allowed;
        background: #f8f9fa;
    }
    </style>

<?php
$additionalJS = '
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/locales/fr.global.min.js"></script>
<script>
    const API_BASE_URL = "/api";
    let calendar;
    let currentFilters = {
        status: "",
        room: "",
        game: ""
    };

    function initCalendar() {
        const calendarEl = document.getElementById("calendar");
        
        calendar = new FullCalendar.Calendar(calendarEl, {
            locale: "fr",
            initialView: "timeGridWeek",
            headerToolbar: {
                left: "prev,next today",
                center: "title",
                right: "dayGridMonth,timeGridWeek,timeGridDay,listWeek"
            },
            slotMinTime: "09:00:00",
            slotMaxTime: "22:00:00",
            allDaySlot: false,
            height: "auto",
            events: loadBookings,
            eventClick: handleEventClick,
            eventDidMount: function(info) {
                info.el.setAttribute("title", info.event.extendedProps.description);
            }
        });

        calendar.render();
    }

    async function loadBookings(fetchInfo, successCallback, failureCallback) {
        try {
            const params = new URLSearchParams({
                start: fetchInfo.startStr.split("T")[0],
                end: fetchInfo.endStr.split("T")[0]
            });
            
            if (currentFilters.status) {
                params.append("status", currentFilters.status);
            }
            if (currentFilters.room) {
                params.append("room_id", currentFilters.room);
            }
            if (currentFilters.game) {
                params.append("game_id", currentFilters.game);
            }

            const response = await fetch(`${API_BASE_URL}/booking?${params}`);
            const result = await response.json();

            if (result.status === "success") {
                const statusColors = {
                    "pending": "#ffc107",
                    "confirmed": "#28a745",
                    "in_progress": "#17a2b8",
                    "completed": "#6c757d",
                    "cancelled": "#dc3545"
                };

                const events = result.data.map(booking => {
                    return {
                        id: booking.id,
                        title: `${booking.game_name || "Jeu"} - ${booking.customer_name}`,
                        start: `${booking.booking_date}T${booking.start_time}`,
                        end: `${booking.booking_date}T${booking.end_time}`,
                        backgroundColor: statusColors[booking.status] || "#667eea",
                        borderColor: statusColors[booking.status] || "#667eea",
                        extendedProps: {
                            status: booking.status,
                            room: booking.room_name,
                            customer: booking.customer_name,
                            players: booking.num_players,
                            price: booking.total_price,
                            description: `${booking.num_players} joueurs - ${booking.room_name} - ${booking.total_price} DT`
                        }
                    };
                });

                successCallback(events);
            } else {
                console.error("Erreur API:", result.message);
                failureCallback(new Error(result.message));
            }

        } catch (error) {
            console.error("Erreur chargement réservations:", error);
            failureCallback(error);
        }
    }

    async function loadFilterOptions() {
        try {
            // Charger toutes les salles via l'endpoint admin
            const roomsResponse = await fetch("/admin/bookings/rooms");
            const roomsResult = await roomsResponse.json();
            
            if (roomsResult.success && roomsResult.data) {
                const roomSelect = document.getElementById("filter-room");
                roomsResult.data.forEach(room => {
                    const option = document.createElement("option");
                    option.value = room.id;
                    option.textContent = room.name;
                    roomSelect.appendChild(option);
                });
            }

            const gamesResponse = await fetch(`${API_BASE_URL}/games`);
            const gamesResult = await gamesResponse.json();
            
            if (gamesResult.status === "success") {
                const gameSelect = document.getElementById("filter-game");
                gamesResult.data.forEach(game => {
                    const option = document.createElement("option");
                    option.value = game.id;
                    option.textContent = game.name;
                    gameSelect.appendChild(option);
                });
            }
        } catch (error) {
            console.error("Erreur chargement filtres:", error);
        }
    }

    function handleEventClick(info) {
        const bookingId = info.event.id;
        showBookingDetails(bookingId);
    }

    async function showBookingDetails(bookingId) {
        const modal = new bootstrap.Modal(document.getElementById("bookingDetailsModal"));
        const content = document.getElementById("booking-details-content");
        
        modal.show();
        content.innerHTML = "<div class=\"text-center py-4\"><div class=\"spinner-border\"></div></div>";

        try {
            const response = await fetch(`${API_BASE_URL}/booking/${bookingId}`);
            const result = await response.json();

            if (result.status === "success") {
                displayBookingDetails(result.data);
            } else {
                content.innerHTML = "<div class=\"alert alert-danger\">Erreur: " + result.message + "</div>";
            }

        } catch (error) {
            console.error("Erreur:", error);
            content.innerHTML = "<div class=\"alert alert-danger\">Erreur de chargement</div>";
        }
    }

    function displayBookingDetails(booking) {
        const content = document.getElementById("booking-details-content");
        
        const statusColors = {
            "pending": "warning",
            "confirmed": "success",
            "in_progress": "info",
            "completed": "secondary",
            "cancelled": "danger"
        };

        const statusLabels = {
            "pending": "En attente",
            "confirmed": "Confirmé",
            "in_progress": "En cours",
            "completed": "Terminé",
            "cancelled": "Annulé"
        };

        content.innerHTML = `
            <div class="mb-3">
                <span class="badge bg-${statusColors[booking.status]} mb-2">
                    ${statusLabels[booking.status]}
                </span>
                <h4>${booking.confirmation_code}</h4>
            </div>

            <dl class="row">
                <dt class="col-sm-4">Client</dt>
                <dd class="col-sm-8">${booking.customer_name || "N/A"}</dd>

                <dt class="col-sm-4">Email</dt>
                <dd class="col-sm-8">${booking.customer_email || "N/A"}</dd>

                <dt class="col-sm-4">Téléphone</dt>
                <dd class="col-sm-8">${booking.customer_phone || "N/A"}</dd>

                <dt class="col-sm-4">Jeu</dt>
                <dd class="col-sm-8">${booking.game_name || "N/A"}</dd>

                <dt class="col-sm-4">Salle</dt>
                <dd class="col-sm-8">${booking.room_name || "N/A"}</dd>

                <dt class="col-sm-4">Date</dt>
                <dd class="col-sm-8">${booking.booking_date ? formatDate(booking.booking_date) : "N/A"}</dd>

                <dt class="col-sm-4">Horaire</dt>
                <dd class="col-sm-8">${booking.start_time ? booking.start_time.substring(0,5) : "N/A"} - ${booking.end_time ? booking.end_time.substring(0,5) : "N/A"}</dd>

                <dt class="col-sm-4">Nombre de joueurs</dt>
                <dd class="col-sm-8">${booking.num_players || 0}</dd>

                <dt class="col-sm-4">Prix total</dt>
                <dd class="col-sm-8"><strong>${booking.total_price || 0} DT</strong></dd>

                ${booking.notes ? `
                <dt class="col-sm-4">Notes</dt>
                <dd class="col-sm-8">${booking.notes}</dd>
                ` : ""}
            </dl>

            <div class="text-center mt-3">
                <a href="/admin/bookings/view/${booking.id}" class="btn btn-primary">
                    <i class="bi bi-eye"></i> Voir tous les détails
                </a>
            </div>

            ${booking.qr_code ? `
            <div class="text-center mt-3">
                <h6>QR Code</h6>
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${encodeURIComponent(booking.qr_code)}" 
                     alt="QR Code" class="img-fluid" style="max-width: 200px;">
            </div>
            ` : ""}
        `;

        const cancelBtn = document.getElementById("btn-cancel-booking");
        cancelBtn.onclick = () => cancelBooking(booking.id);
        
        // Vérifier si la réservation est passée
        const bookingDateTime = new Date(booking.booking_date + " " + booking.start_time);
        const isPastBooking = bookingDateTime < new Date();
        
        // Cacher le bouton si annulé ou passé
        if (booking.status === "cancelled" || isPastBooking) {
            cancelBtn.style.display = "none";
        } else {
            cancelBtn.style.display = "inline-block";
        }
    }

    async function cancelBooking(bookingId) {
        const result = await Swal.fire({
            title: "Confirmer l\'annulation",
            text: "Êtes-vous sûr de vouloir annuler cette réservation ?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Oui, annuler",
            cancelButtonText: "Non, garder"
        });

        if (!result.isConfirmed) {
            return;
        }

        try {
            const response = await fetch(`${API_BASE_URL}/booking/cancel/${bookingId}`, {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({
                    cancellation_reason: "Annulé par l\'administrateur"
                })
            });

            const data = await response.json();

            if (data.status === "success") {
                await Swal.fire({
                    icon: "success",
                    title: "Annulée !",
                    text: "Réservation annulée avec succès",
                    timer: 2000
                });
                bootstrap.Modal.getInstance(document.getElementById("bookingDetailsModal")).hide();
                calendar.refetchEvents();
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Erreur",
                    text: data.message || "Erreur lors de l\'annulation"
                });
            }

        } catch (error) {
            console.error("Erreur:", error);
            Swal.fire({
                icon: "error",
                title: "Erreur",
                text: "Erreur lors de l\'annulation"
            });
        }
    }

    function setupFilters() {
        ["filter-status", "filter-room", "filter-game"].forEach(id => {
            document.getElementById(id).addEventListener("change", function() {
                currentFilters[id.replace("filter-", "")] = this.value;
                calendar.refetchEvents();
            });
        });
    }

    function resetFilters() {
        document.getElementById("filter-status").value = "";
        document.getElementById("filter-room").value = "";
        document.getElementById("filter-game").value = "";
        currentFilters = { status: "", room: "", game: "" };
        calendar.refetchEvents();
    }

    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString("fr-FR", { 
            weekday: "long", 
            year: "numeric", 
            month: "long", 
            day: "numeric" 
        });
    }

    // Wizard state
    let wizardCurrentStep = 1;
    let wizardData = {
        game: null,
        room: null,
        date: null,
        time: null,
        participants: 1
    };

    // Charger les jeux dans le wizard
    async function loadCreateFormOptions() {
        try {
            const gamesResponse = await fetch(`${API_BASE_URL}/games`);
            const gamesResult = await gamesResponse.json();
            
            if (gamesResult.status === "success") {
                const gamesGrid = document.getElementById("gamesGrid");
                gamesGrid.innerHTML = "";
                
                gamesResult.data.forEach(game => {
                    const col = document.createElement("div");
                    col.className = "col-md-4 mb-3";
                    
                    const card = document.createElement("div");
                    card.className = "card game-card h-100";
                    card.dataset.gameId = game.id;
                    card.dataset.gameName = game.name;
                    card.dataset.gamePrice = game.price;
                    card.dataset.gameDuration = game.duration;
                    
                    card.innerHTML = `
                        <div class="card-body text-center">
                            <h5 class="card-title">${game.name}</h5>
                            <p class="card-text">
                                <strong>${game.price} TND</strong><br>
                                <small class="text-muted">${game.duration} minutes</small>
                            </p>
                        </div>
                    `;
                    
                    card.addEventListener("click", function() {
                        selectGame(
                            parseInt(this.dataset.gameId),
                            this.dataset.gameName,
                            parseFloat(this.dataset.gamePrice),
                            parseInt(this.dataset.gameDuration)
                        );
                    });
                    
                    col.appendChild(card);
                    gamesGrid.appendChild(col);
                });
            }
        } catch (error) {
            console.error("Erreur lors du chargement des jeux:", error);
        }
    }

    // Sélectionner un jeu
    function selectGame(id, name, price, duration) {
        wizardData.game = { id, name, price, duration };
        document.getElementById("selected_game_id").value = id;
        
        document.querySelectorAll(".game-card").forEach(card => card.classList.remove("selected"));
        
        // Trouver et marquer la carte sélectionnée
        const selectedCard = document.querySelector(`.game-card[data-game-id="${id}"]`);
        if (selectedCard) {
            selectedCard.classList.add("selected");
        }
        
        document.getElementById("wizardNextBtn").disabled = false;
    }

    // Charger les salles disponibles pour un jeu
    async function loadRoomsForGame(gameId) {
        try {
            const response = await fetch(`${API_BASE_URL}/availability/rooms?game_id=${gameId}`);
            const result = await response.json();
            
            if (result.status === "success" && result.data) {
                const roomsGrid = document.getElementById("roomsGrid");
                roomsGrid.innerHTML = "";
                
                result.data.forEach(room => {
                    const col = document.createElement("div");
                    col.className = "col-md-4 mb-3";
                    
                    const card = document.createElement("div");
                    card.className = "card room-card h-100";
                    card.dataset.roomId = room.id;
                    card.dataset.roomName = room.name;
                    card.dataset.roomCapacity = room.capacity;
                    
                    card.innerHTML = `
                        <div class="card-body text-center">
                            <h5 class="card-title">${room.name}</h5>
                            <p class="card-text">
                                <i class="bi bi-people"></i> Capacité: ${room.capacity}
                            </p>
                        </div>
                    `;
                    
                    card.addEventListener("click", function() {
                        selectRoom(
                            parseInt(this.dataset.roomId),
                            this.dataset.roomName,
                            parseInt(this.dataset.roomCapacity)
                        );
                    });
                    
                    col.appendChild(card);
                    roomsGrid.appendChild(col);
                });
            }
        } catch (error) {
            console.error("Erreur lors du chargement des salles:", error);
        }
    }

    // Sélectionner une salle
    function selectRoom(id, name, capacity) {
        wizardData.room = { id, name, capacity };
        document.getElementById("selected_room_id").value = id;
        
        document.querySelectorAll(".room-card").forEach(card => card.classList.remove("selected"));
        
        // Trouver et marquer la carte sélectionnée
        const selectedCard = document.querySelector(`.room-card[data-room-id="${id}"]`);
        if (selectedCard) {
            selectedCard.classList.add("selected");
        }
        
        document.getElementById("wizardNextBtn").disabled = false;
    }

    // Charger les créneaux disponibles
    async function loadTimeSlots() {
        const date = document.getElementById("booking_date").value;
        if (!date || !wizardData.game || !wizardData.room) return;

        try {
            const response = await fetch(`${API_BASE_URL}/availability/all-slots?game_id=${wizardData.game.id}&date=${date}`);
            const result = await response.json();
            
            if (result.status === "success") {
                const container = document.getElementById("timeSlotsContainer");
                container.innerHTML = "";
                
                const roomSlots = result.data[`room_${wizardData.room.id}`] || [];
                
                if (roomSlots.length === 0) {
                    container.innerHTML = \'<div class="alert alert-warning">Aucun créneau disponible pour cette date</div>\';
                    return;
                }
                
                // Filtrer uniquement les créneaux disponibles
                const availableSlots = roomSlots.filter(slot => slot.available === true);
                
                if (availableSlots.length === 0) {
                    container.innerHTML = \'<div class="alert alert-warning">Aucun créneau disponible pour cette date</div>\';
                    return;
                }
                
                availableSlots.forEach(slot => {
                    const timeSlot = document.createElement("div");
                    timeSlot.className = "time-slot d-inline-block";
                    timeSlot.textContent = slot.start_formatted;
                    timeSlot.dataset.time = slot.start;
                    
                    timeSlot.addEventListener("click", function() {
                        selectTimeSlot(this.dataset.time);
                    });
                    
                    container.appendChild(timeSlot);
                });
            }
        } catch (error) {
            console.error("Erreur lors du chargement des créneaux:", error);
        }
    }

    // Sélectionner un créneau
    function selectTimeSlot(time) {
        wizardData.time = time;
        document.getElementById("selected_time").value = time;
        
        document.querySelectorAll(".time-slot").forEach(slot => slot.classList.remove("selected"));
        
        // Trouver et marquer le créneau sélectionné
        const selectedSlot = document.querySelector(`.time-slot[data-time="${time}"]`);
        if (selectedSlot) {
            selectedSlot.classList.add("selected");
        }
        
        document.getElementById("wizardNextBtn").disabled = false;
    }

    // Navigation du wizard
    function wizardNext() {
        if (!validateWizardStep(wizardCurrentStep)) {
            return;
        }
        
        if (wizardCurrentStep < 5) {
            wizardCurrentStep++;
            showWizardStep(wizardCurrentStep);
            
            if (wizardCurrentStep === 2) {
                loadRoomsForGame(wizardData.game.id);
            } else if (wizardCurrentStep === 3) {
                const today = new Date().toISOString().split("T")[0];
                document.getElementById("booking_date").min = today;
                document.getElementById("booking_date").value = today;
                loadTimeSlots();
            } else if (wizardCurrentStep === 5) {
                showBookingSummary();
            }
        }
    }

    function wizardPrev() {
        if (wizardCurrentStep > 1) {
            wizardCurrentStep--;
            showWizardStep(wizardCurrentStep);
        }
    }

    function showWizardStep(step) {
        document.querySelectorAll(".wizard-content").forEach(content => {
            content.classList.add("d-none");
        });
        
        document.querySelector(`.wizard-content[data-step="${step}"]`).classList.remove("d-none");
        
        document.querySelectorAll(".wizard-step").forEach((stepEl, index) => {
            stepEl.classList.remove("active", "completed");
            if (index + 1 < step) {
                stepEl.classList.add("completed");
            } else if (index + 1 === step) {
                stepEl.classList.add("active");
            }
        });
        
        const progress = (step / 5) * 100;
        document.getElementById("wizardProgress").style.width = progress + "%";
        
        document.getElementById("wizardPrevBtn").style.display = step > 1 ? "inline-block" : "none";
        document.getElementById("wizardNextBtn").style.display = step < 5 ? "inline-block" : "none";
        document.getElementById("wizardSubmitBtn").classList.toggle("d-none", step !== 5);
        
        if (step === 1 || step === 2 || step === 3) {
            document.getElementById("wizardNextBtn").disabled = true;
        }
    }

    function validateWizardStep(step) {
        switch(step) {
            case 1:
                if (!wizardData.game) {
                    Swal.fire("Attention", "Veuillez sélectionner un jeu", "warning");
                    return false;
                }
                break;
            case 2:
                if (!wizardData.room) {
                    Swal.fire("Attention", "Veuillez sélectionner une salle", "warning");
                    return false;
                }
                break;
            case 3:
                if (!wizardData.time) {
                    Swal.fire("Attention", "Veuillez sélectionner un créneau horaire", "warning");
                    return false;
                }
                break;
            case 4:
                const firstName = document.getElementById("customer_first_name").value;
                const lastName = document.getElementById("customer_last_name").value;
                const email = document.getElementById("customer_email").value;
                const phone = document.getElementById("customer_phone").value;
                const participants = document.getElementById("num_participants").value;
                
                if (!firstName || !lastName || !email || !phone || !participants) {
                    Swal.fire("Attention", "Veuillez remplir tous les champs obligatoires", "warning");
                    return false;
                }
                
                wizardData.participants = parseInt(participants);
                break;
        }
        return true;
    }

    function showBookingSummary() {
        const summary = document.getElementById("bookingSummary");
        const totalPrice = wizardData.game.price * wizardData.participants;
        document.getElementById("total_price").value = totalPrice;
        
        const firstName = document.getElementById("customer_first_name").value;
        const lastName = document.getElementById("customer_last_name").value;
        const email = document.getElementById("customer_email").value;
        const phone = document.getElementById("customer_phone").value;
        const date = document.getElementById("booking_date").value;
        
        summary.innerHTML = `
            <h5 class="mb-3">Détails de la réservation</h5>
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Jeu:</strong> ${wizardData.game.name}</p>
                    <p><strong>Salle:</strong> ${wizardData.room.name}</p>
                    <p><strong>Date:</strong> ${new Date(date).toLocaleDateString("fr-FR")}</p>
                    <p><strong>Heure:</strong> ${wizardData.time.substring(0,5)}</p>
                    <p><strong>Durée:</strong> ${wizardData.game.duration} minutes</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Client:</strong> ${firstName} ${lastName}</p>
                    <p><strong>Email:</strong> ${email}</p>
                    <p><strong>Téléphone:</strong> ${phone}</p>
                    <p><strong>Participants:</strong> ${wizardData.participants}</p>
                    <p><strong>Prix total:</strong> <span class="text-primary fs-4">${totalPrice} TND</span></p>
                </div>
            </div>
        `;
    }

    // Vérifier la disponibilité d\'un créneau (fonction conservée pour compatibilité)
    async function checkAvailability() {
        return true;
        const endTime = startDate.toTimeString().substring(0, 5);

        try {
            const response = await fetch(`${API_BASE_URL}/availability/check`, {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({
                    game_id: parseInt(gameId),
                    room_id: parseInt(roomId),
                    date: bookingDate,
                    start_time: startTime + ":00",
                    end_time: endTime + ":00"
                })
            });

            const result = await response.json();
            console.log("Vérification disponibilité:", result);
            
            if (result.status === "success" || result.available === true) {
                return true;
            }
            
            return false;
        } catch (error) {
            console.error("Erreur lors de la vérification de disponibilité:", error);
            return true; // En cas d\'erreur, laisser passer pour ne pas bloquer
        }
    }

    // Calculer le prix total automatiquement
    function updatePrice() {
        const gameSelect = document.getElementById("game_id");
        const numParticipants = document.getElementById("num_participants");
        const totalPriceInput = document.getElementById("total_price");
        
        const selectedOption = gameSelect.options[gameSelect.selectedIndex];
        if (selectedOption && selectedOption.dataset.price) {
            const price = parseFloat(selectedOption.dataset.price);
            const participants = parseInt(numParticipants.value) || 1;
            totalPriceInput.value = (price * participants).toFixed(2);
        }
    }

    // Initialiser le formulaire de création au chargement de la page
    document.addEventListener("DOMContentLoaded", function() {
        initCalendar();
        setupFilters();
        loadFilterOptions();
        loadCreateFormOptions();

        const numParticipants = document.getElementById("num_participants");
        const bookingForm = document.getElementById("createBookingForm");
        const bookingDateInput = document.getElementById("booking_date");

        // Boutons du wizard
        document.getElementById("wizardNextBtn").addEventListener("click", wizardNext);
        document.getElementById("wizardPrevBtn").addEventListener("click", wizardPrev);

        // Mettre à jour le prix quand le nombre de participants change
        numParticipants.addEventListener("input", function() {
            wizardData.participants = parseInt(this.value) || 1;
        });

        // Recharger les créneaux quand la date change
        bookingDateInput.addEventListener("change", loadTimeSlots);

        // Gérer la soumission du formulaire wizard
        bookingForm.addEventListener("submit", async function(e) {
            e.preventDefault();
            
            // Soumettre directement le formulaire
            const formData = new FormData(this);
            
            try {
                const response = await fetch("/admin/bookings/create", {
                    method: "POST",
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success) {
                    await Swal.fire({
                        icon: "success",
                        title: "Succès",
                        text: "La réservation a été créée avec succès"
                    });
                    
                    // Fermer le modal et recharger le calendrier
                    bootstrap.Modal.getInstance(document.getElementById("addBookingModal")).hide();
                    calendar.refetchEvents();
                    
                    // Réinitialiser le wizard
                    wizardCurrentStep = 1;
                    wizardData = { game: null, room: null, date: null, time: null, participants: 1 };
                    bookingForm.reset();
                    showWizardStep(1);
                } else {
                    await Swal.fire({
                        icon: "error",
                        title: "Erreur",
                        text: result.message || "Une erreur est survenue"
                    });
                }
            } catch (error) {
                console.error("Erreur:", error);
                await Swal.fire({
                    icon: "error",
                    title: "Erreur",
                    text: "Une erreur est survenue lors de la création"
                });
            }
        });
    });
    
    // Réinitialiser le wizard quand le modal est ouvert
    document.getElementById("addBookingModal").addEventListener("show.bs.modal", function() {
        wizardCurrentStep = 1;
        wizardData = { game: null, room: null, date: null, time: null, participants: 1 };
        showWizardStep(1);
        loadCreateFormOptions();
    });
    
    // Recharger le calendrier après fermeture du modal
    document.getElementById("addBookingModal").addEventListener("hidden.bs.modal", function() {
        if (calendar) {
            calendar.refetchEvents();
        }
    });
</script>
';
?>
<?= view('admin/layouts/footer', compact('additionalJS')) ?>
