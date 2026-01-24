<?php

namespace App\Services;

use App\Models\BookingModel;
use App\Models\ParticipantModel;
use App\Models\GameModel;
use App\Models\RoomModel;
use CodeIgniter\Database\Exceptions\DatabaseException;

/**
 * BookingService
 * 
 * Service de gestion des réservations.
 * Crée, modifie et annule les réservations en toute sécurité.
 * 
 * @package App\Services
 * @author FunLab Team
 * @version 1.0.0
 */
class BookingService
{
    protected $bookingModel;
    protected $participantModel;
    protected $gameModel;
    protected $roomModel;
    protected $ticketService;
    protected $qrCodeService;
    protected $availabilityService;

    public function __construct()
    {
        $this->bookingModel = new BookingModel();
        $this->participantModel = new ParticipantModel();
        $this->gameModel = new GameModel();
        $this->roomModel = new RoomModel();
        $this->ticketService = new TicketService();
        $this->qrCodeService = new QRCodeService();
        $this->availabilityService = new AvailabilityService();
    }

    /**
     * Crée une nouvelle réservation complète
     * 
     * @param array $data Données de la réservation
     * @return array ['success' => bool, 'booking_id' => int|null, 'message' => string, 'data' => array]
     * 
     * Structure attendue de $data :
     * [
     *   'room_id' => int,
     *   'game_id' => int,
     *   'booking_date' => 'YYYY-MM-DD',
     *   'start_time' => 'HH:MM:SS',
     *   'end_time' => 'HH:MM:SS',
     *   'customer_name' => string,
     *   'customer_email' => string,
     *   'customer_phone' => string,
     *   'num_players' => int,
     *   'participants' => array (optionnel),
     *   'notes' => string (optionnel)
     * ]
     */
    public function createBooking(array $data): array
    {
        $db = \Config\Database::connect();
        
        try {
            // ÉTAPE 1 : Validation des données
            $validation = $this->validateBookingData($data);
            if (!$validation['valid']) {
                return [
                    'success' => false,
                    'booking_id' => null,
                    'message' => $validation['message']
                ];
            }

            // ÉTAPE 2 : Vérifier la disponibilité (CRITIQUE)
            $availabilityCheck = $this->availabilityService->checkSlotAvailability(
                $data['room_id'],
                $data['game_id'],
                $data['booking_date'],
                $data['start_time'],
                $data['end_time']
            );

            if (!$availabilityCheck['available']) {
                return [
                    'success' => false,
                    'booking_id' => null,
                    'message' => $availabilityCheck['message']
                ];
            }

            // ÉTAPE 3 : Calculer le prix total
            $game = $this->gameModel->find($data['game_id']);
            $totalPrice = $this->calculateTotalPrice(
                $game['price'],
                $data['num_players']
            );

            // ÉTAPE 4 : Générer le code de confirmation unique
            $confirmationCode = $this->generateConfirmationCode();

            // ÉTAPE 4.5 : Générer le token d'inscription unique
            $registrationToken = bin2hex(random_bytes(32));

            // ÉTAPE 5 : Préparer les données de la réservation
            $bookingData = [
                'room_id' => $data['room_id'],
                'game_id' => $data['game_id'],
                'booking_date' => $data['booking_date'],
                'start_time' => $data['start_time'],
                'end_time' => $data['end_time'],
                'customer_name' => $data['customer_name'],
                'customer_email' => $data['customer_email'],
                'customer_phone' => $data['customer_phone'],
                'num_players' => $data['num_players'],
                'total_price' => $totalPrice,
                'status' => 'pending', // pending jusqu'à confirmation de paiement
                'confirmation_code' => $confirmationCode,
                'registration_token' => $registrationToken,
                'notes' => $data['notes'] ?? null
            ];

            // Ajouter user_id si fourni (relation avec le compte utilisateur)
            if (isset($data['user_id']) && $data['user_id']) {
                $bookingData['user_id'] = $data['user_id'];
            } elseif (isset($data['create_account']) && $data['create_account'] && isset($data['account_password'])) {
                // Créer un compte utilisateur automatiquement pendant la réservation
                $userModel = model('UserModel');
                
                // Vérifier si l'email existe déjà
                $existingUser = $userModel->where('email', $data['customer_email'])->first();
                
                if (!$existingUser) {
                    // Séparer le nom complet en prénom et nom
                    $nameParts = explode(' ', $data['customer_name'], 2);
                    $firstName = $nameParts[0];
                    $lastName = isset($nameParts[1]) ? $nameParts[1] : '';
                    
                    $userId = $userModel->insert([
                        'email' => $data['customer_email'],
                        'password' => password_hash($data['account_password'], PASSWORD_DEFAULT),
                        'first_name' => $firstName,
                        'last_name' => $lastName,
                        'phone' => $data['customer_phone'],
                        'role' => 'customer',
                        'auth_provider' => 'native',
                        'email_verified' => 0,
                        'is_active' => 1
                    ]);
                    
                    if ($userId) {
                        $bookingData['user_id'] = $userId;
                    }
                } else {
                    // L'utilisateur existe déjà, lier la réservation à ce compte
                    $bookingData['user_id'] = $existingUser['id'];
                }
            }

            // Ajouter payment_method si fourni
            if (isset($data['payment_method'])) {
                $bookingData['payment_method'] = $data['payment_method'];
            }

            // ÉTAPE 6 : Démarrer une transaction
            $db->transStart();

            // ÉTAPE 7 : Insérer la réservation
            $bookingId = $this->bookingModel->insert($bookingData);

            if (!$bookingId) {
                $db->transRollback();
                return [
                    'success' => false,
                    'booking_id' => null,
                    'message' => 'Erreur lors de la création de la réservation'
                ];
            }

            // ÉTAPE 8 : Ajouter les participants si fournis
            if (isset($data['participants']) && is_array($data['participants'])) {
                foreach ($data['participants'] as $participant) {
                    $this->participantModel->insert([
                        'booking_id' => $bookingId,
                        'name' => $participant['name'],
                        'email' => $participant['email'] ?? null,
                        'phone' => $participant['phone'] ?? null,
                        'age' => $participant['age'] ?? null
                    ]);
                }
            }

            // ÉTAPE 9 : Générer le QR Code
            $qrCode = $this->qrCodeService->generateQRCode($bookingId, $confirmationCode);
            
            // Mettre à jour la réservation avec le QR code
            $this->bookingModel->update($bookingId, ['qr_code' => $qrCode]);

            // ÉTAPE 10 : Valider la transaction
            $db->transComplete();

            if ($db->transStatus() === false) {
                return [
                    'success' => false,
                    'booking_id' => null,
                    'message' => 'Erreur lors de la transaction'
                ];
            }

            // ÉTAPE 11 : Envoyer l'email de confirmation
            try {
                $this->ticketService->sendTicketByEmail($bookingId, $data['customer_email']);
            } catch (\Exception $e) {
                log_message('error', 'Erreur envoi email : ' . $e->getMessage());
                // Ne pas faire échouer la réservation si l'email échoue
            }

            // ÉTAPE 12 : Retourner le succès avec les détails
            $room = $this->roomModel->find($data['room_id']);
            
            return [
                'success' => true,
                'booking_id' => $bookingId,
                'message' => 'Réservation créée avec succès',
                'data' => [
                    'confirmation_code' => $confirmationCode,
                    'total_price' => $totalPrice,
                    'booking_date' => $data['booking_date'],
                    'start_time' => $data['start_time'],
                    'end_time' => $data['end_time'],
                    'room_name' => $room['name'],
                    'game_name' => $game['name'],
                    'qr_code' => $qrCode
                ]
            ];

        } catch (DatabaseException $e) {
            $db->transRollback();
            log_message('error', 'Database error in createBooking: ' . $e->getMessage());
            
            return [
                'success' => false,
                'booking_id' => null,
                'message' => 'Erreur de base de données'
            ];
            
        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Error in createBooking: ' . $e->getMessage());
            
            return [
                'success' => false,
                'booking_id' => null,
                'message' => 'Une erreur est survenue'
            ];
        }
    }

    /**
     * Valide les données de réservation
     * 
     * @param array $data
     * @return array ['valid' => bool, 'message' => string]
     */
    private function validateBookingData(array $data): array
    {
        $requiredFields = [
            'room_id', 'game_id', 'booking_date', 'start_time', 'end_time',
            'customer_name', 'customer_email', 'customer_phone', 'num_players'
        ];

        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                return [
                    'valid' => false,
                    'message' => "Le champ {$field} est requis"
                ];
            }
        }

        // Valider l'email
        if (!filter_var($data['customer_email'], FILTER_VALIDATE_EMAIL)) {
            return [
                'valid' => false,
                'message' => 'Email invalide'
            ];
        }

        // Valider le nombre de joueurs
        if ($data['num_players'] < 1) {
            return [
                'valid' => false,
                'message' => 'Le nombre de joueurs doit être au moins 1'
            ];
        }

        // Valider que le nombre de joueurs est dans les limites du jeu
        $game = $this->gameModel->find($data['game_id']);
        if ($game) {
            if ($data['num_players'] < $game['min_players']) {
                return [
                    'valid' => false,
                    'message' => "Ce jeu nécessite au moins {$game['min_players']} joueurs"
                ];
            }
            if ($data['num_players'] > $game['max_players']) {
                return [
                    'valid' => false,
                    'message' => "Ce jeu accepte maximum {$game['max_players']} joueurs"
                ];
            }
        }

        return ['valid' => true, 'message' => ''];
    }

    /**
     * Annule une réservation
     * 
     * @param int $bookingId
     * @param string $reason Raison de l'annulation
     * @return array ['success' => bool, 'message' => string]
     */
    public function cancelBooking(int $bookingId, string $reason = ''): array
    {
        try {
            $booking = $this->bookingModel->find($bookingId);

            if (!$booking) {
                return [
                    'success' => false,
                    'message' => 'Réservation introuvable'
                ];
            }

            // Vérifier que la réservation n'est pas déjà annulée ou terminée
            if ($booking['status'] === 'cancelled') {
                return [
                    'success' => false,
                    'message' => 'Cette réservation est déjà annulée'
                ];
            }

            if ($booking['status'] === 'completed') {
                return [
                    'success' => false,
                    'message' => 'Impossible d\'annuler une réservation terminée'
                ];
            }

            // Vérifier que la réservation est dans le futur
            $bookingDateTime = strtotime($booking['booking_date'] . ' ' . $booking['start_time']);
            if ($bookingDateTime < time()) {
                return [
                    'success' => false,
                    'message' => 'Impossible d\'annuler une réservation passée'
                ];
            }

            // Mettre à jour le statut
            $updated = $this->bookingModel->update($bookingId, [
                'status' => 'cancelled',
                'notes' => ($booking['notes'] ?? '') . "\nAnnulée: " . $reason
            ]);

            if ($updated) {
                // Envoyer un email d'annulation
                try {
                    $this->sendCancellationEmail($bookingId);
                } catch (\Exception $e) {
                    log_message('error', 'Erreur envoi email annulation : ' . $e->getMessage());
                }

                return [
                    'success' => true,
                    'message' => 'Réservation annulée avec succès'
                ];
            }

            return [
                'success' => false,
                'message' => 'Erreur lors de l\'annulation'
            ];

        } catch (\Exception $e) {
            log_message('error', 'Error in cancelBooking: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Une erreur est survenue'
            ];
        }
    }

    /**
     * Confirme une réservation (après paiement par exemple)
     * 
     * @param int $bookingId
     * @return array ['success' => bool, 'message' => string]
     */
    public function confirmBooking(int $bookingId): array
    {
        try {
            $booking = $this->bookingModel->find($bookingId);

            if (!$booking) {
                return ['success' => false, 'message' => 'Réservation introuvable'];
            }

            if ($booking['status'] === 'confirmed') {
                return ['success' => false, 'message' => 'Réservation déjà confirmée'];
            }

            $updated = $this->bookingModel->update($bookingId, [
                'status' => 'confirmed'
            ]);

            if ($updated) {
                return ['success' => true, 'message' => 'Réservation confirmée'];
            }

            return ['success' => false, 'message' => 'Erreur lors de la confirmation'];

        } catch (\Exception $e) {
            log_message('error', 'Error in confirmBooking: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Une erreur est survenue'];
        }
    }

    /**
     * Calcule le prix total d'une réservation
     * 
     * @param float $basePrice Prix de base du jeu
     * @param int $numPlayers Nombre de joueurs
     * @return float Prix total
     */
    public function calculateTotalPrice(float $basePrice, int $numPlayers): float
    {
        // Logique simple : prix de base × nombre de joueurs
        // Vous pouvez ajouter des règles plus complexes ici
        return round($basePrice * $numPlayers, 2);
    }

    /**
     * Génère un code de confirmation unique
     * 
     * @return string Code de confirmation
     */
    public function generateConfirmationCode(): string
    {
        $code = 'FL' . date('Ymd') . strtoupper(substr(bin2hex(random_bytes(3)), 0, 6));
        
        // Vérifier l'unicité
        $existing = $this->bookingModel->where('confirmation_code', $code)->first();
        
        if ($existing) {
            // Si le code existe déjà, régénérer
            return $this->generateConfirmationCode();
        }
        
        return $code;
    }

    /**
     * Récupère une réservation par son ID avec tous les détails
     * 
     * @param int $bookingId
     * @return array|null
     */
    public function getBookingDetails(int $bookingId): ?array
    {
        // Récupérer les détails avec JOINs pour avoir toutes les données nécessaires
        // Note: bookings a déjà customer_name, customer_email, customer_phone (pas de user_id)
        $builder = $this->bookingModel->builder();
        $booking = $builder
            ->select('bookings.*, 
                     games.name as game_name, 
                     games.duration_minutes,
                     rooms.name as room_name')
            ->join('games', 'games.id = bookings.game_id', 'left')
            ->join('rooms', 'rooms.id = bookings.room_id', 'left')
            ->where('bookings.id', $bookingId)
            ->get()
            ->getRowArray();
        
        if (!$booking) {
            return null;
        }

        // Récupérer les participants si nécessaire
        $participants = $this->participantModel->where('booking_id', $bookingId)->findAll();
        $booking['participants'] = $participants;
        $booking['participants_count'] = count($participants);

        return $booking;
    }

    /**
     * Récupère les réservations d'un client par email
     * 
     * @param string $email
     * @return array
     */
    public function getCustomerBookings(string $email): array
    {
        return $this->bookingModel
            ->where('customer_email', $email)
            ->orderBy('booking_date', 'DESC')
            ->orderBy('start_time', 'DESC')
            ->findAll();
    }

    /**
     * Envoie un email d'annulation
     * 
     * @param int $bookingId
     */
    private function sendCancellationEmail(int $bookingId): void
    {
        // Cette méthode sera implémentée plus tard avec le système d'email
        log_message('info', "Email d'annulation pour la réservation #{$bookingId}");
    }

    /**
     * Marque une réservation comme terminée
     * 
     * @param int $bookingId
     * @return array
     */
    public function completeBooking(int $bookingId): array
    {
        try {
            $booking = $this->bookingModel->find($bookingId);

            if (!$booking) {
                return ['success' => false, 'message' => 'Réservation introuvable'];
            }

            $updated = $this->bookingModel->update($bookingId, [
                'status' => 'completed'
            ]);

            if ($updated) {
                return ['success' => true, 'message' => 'Réservation marquée comme terminée'];
            }

            return ['success' => false, 'message' => 'Erreur lors de la mise à jour'];

        } catch (\Exception $e) {
            log_message('error', 'Error in completeBooking: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Une erreur est survenue'];
        }
    }
}
