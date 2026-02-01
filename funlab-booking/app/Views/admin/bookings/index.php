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

    <!-- Modal Nouvelle Réservation -->
    <div class="modal fade" id="addBookingModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="/admin/bookings/create" method="POST" id="createBookingForm">
                    <div class="modal-header">
                        <h5 class="modal-title">Nouvelle Réservation</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <!-- Jeu -->
                            <div class="col-md-6">
                                <label class="form-label">Jeu <span class="text-danger">*</span></label>
                                <select class="form-select" name="game_id" id="game_id" required>
                                    <option value="">Sélectionner un jeu</option>
                                </select>
                            </div>

                            <!-- Salle -->
                            <div class="col-md-6">
                                <label class="form-label">Salle <span class="text-danger">*</span></label>
                                <select class="form-select" name="room_id" id="room_id" required>
                                    <option value="">Sélectionner une salle</option>
                                </select>
                            </div>

                            <!-- Date -->
                            <div class="col-md-6">
                                <label class="form-label">Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="booking_date" id="booking_date" required>
                            </div>

                            <!-- Heure de début -->
                            <div class="col-md-6">
                                <label class="form-label">Heure de début <span class="text-danger">*</span></label>
                                <input type="time" class="form-control" name="start_time" id="start_time" required>
                            </div>

                            <!-- Nombre de participants -->
                            <div class="col-md-6">
                                <label class="form-label">Nombre de participants <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="num_participants" id="num_participants" min="1" required>
                            </div>

                            <!-- Statut -->
                            <div class="col-md-6">
                                <label class="form-label">Statut</label>
                                <select class="form-select" name="status">
                                    <option value="pending">En attente</option>
                                    <option value="confirmed" selected>Confirmé</option>
                                </select>
                            </div>

                            <!-- Informations client -->
                            <div class="col-12">
                                <h6 class="border-top pt-3">Informations du client</h6>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Nom <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="customer_name" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" name="customer_email" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Téléphone <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control" name="customer_phone" required>
                            </div>

                            <!-- Prix total (calculé automatiquement) -->
                            <div class="col-md-6">
                                <label class="form-label">Prix total (TND)</label>
                                <input type="number" class="form-control" name="total_price" id="total_price" step="0.01" readonly>
                            </div>

                            <!-- Notes -->
                            <div class="col-12">
                                <label class="form-label">Notes</label>
                                <textarea class="form-control" name="notes" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Créer la réservation
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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

    document.addEventListener("DOMContentLoaded", function() {
        initCalendar();
        setupFilters();
        loadFilterOptions();
    });

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
            const roomsResponse = await fetch(`${API_BASE_URL}/availability/rooms`);
            const roomsResult = await roomsResponse.json();
            
            if (roomsResult.status === "success") {
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

    // Charger les jeux et salles dans le formulaire de création
    async function loadCreateFormOptions() {
        try {
            // Charger les jeux
            const gamesResponse = await fetch(`${API_BASE_URL}/games`);
            const gamesResult = await gamesResponse.json();
            
            if (gamesResult.status === "success") {
                const gameSelect = document.getElementById("game_id");
                gamesResult.data.forEach(game => {
                    const option = document.createElement("option");
                    option.value = game.id;
                    option.textContent = `${game.name} (${game.price} TND)`;
                    option.dataset.price = game.price;
                    option.dataset.duration = game.duration;
                    gameSelect.appendChild(option);
                });
            }

            // Charger les salles
            const roomsResponse = await fetch(`${API_BASE_URL}/rooms`);
            const roomsResult = await roomsResponse.json();
            
            if (roomsResult.status === "success") {
                const roomSelect = document.getElementById("room_id");
                roomsResult.data.forEach(room => {
                    const option = document.createElement("option");
                    option.value = room.id;
                    option.textContent = `${room.name} (Capacité: ${room.capacity})`;
                    roomSelect.appendChild(option);
                });
            }
        } catch (error) {
            console.error("Erreur lors du chargement des options:", error);
        }
    }

    // Calculer le prix total automatiquement
    document.addEventListener("DOMContentLoaded", function() {
        loadCreateFormOptions();

        const gameSelect = document.getElementById("game_id");
        const numParticipants = document.getElementById("num_participants");
        const totalPriceInput = document.getElementById("total_price");

        function updatePrice() {
            const selectedOption = gameSelect.options[gameSelect.selectedIndex];
            if (selectedOption && selectedOption.dataset.price) {
                const price = parseFloat(selectedOption.dataset.price);
                const participants = parseInt(numParticipants.value) || 1;
                totalPriceInput.value = (price * participants).toFixed(2);
            }
        }

        gameSelect.addEventListener("change", updatePrice);
        numParticipants.addEventListener("input", updatePrice);

        // Définir la date minimale à aujourd\'hui
        const bookingDateInput = document.getElementById("booking_date");
        const today = new Date().toISOString().split("T")[0];
        bookingDateInput.min = today;
        bookingDateInput.value = today;

        // Définir l\'heure par défaut
        document.getElementById("start_time").value = "10:00";
    });
</script>
';
?>
<?= view('admin/layouts/footer', compact('additionalJS')) ?>
