<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Services\BookingService;

/**
 * BookingApi
 * 
 * API REST pour la gestion des réservations.
 * 
 * @package App\Controllers\Api
 * @author FunLab Team
 * @version 1.0.0
 */
class BookingApi extends ResourceController
{
    protected $format = 'json';
    protected $bookingService;

    public function __construct()
    {
        $this->bookingService = new BookingService();
        
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type');
    }

    /**
     * Récupère toutes les réservations avec filtres optionnels
     * 
     * Endpoint : GET /api/booking
     * 
     * Query params :
     * - status: Filtrer par statut (pending, confirmed, cancelled, completed)
     * - room_id: Filtrer par salle
     * - game_id: Filtrer par jeu
     * - start: Date de début (YYYY-MM-DD) pour le calendrier
     * - end: Date de fin (YYYY-MM-DD) pour le calendrier
     * 
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function index()
    {
        try {
            $bookingModel = model('BookingModel');
            $builder = $bookingModel->builder();
            
            // SELECT avec JOINs pour récupérer les noms
            $builder->select('bookings.*, 
                             games.name as game_name, 
                             rooms.name as room_name')
                    ->join('games', 'games.id = bookings.game_id', 'left')
                    ->join('rooms', 'rooms.id = bookings.room_id', 'left');
            
            // Filtres
            $status = $this->request->getGet('status');
            if ($status) {
                $builder->where('bookings.status', $status);
            }
            
            $roomId = $this->request->getGet('room_id');
            if ($roomId) {
                $builder->where('bookings.room_id', $roomId);
            }
            
            $gameId = $this->request->getGet('game_id');
            if ($gameId) {
                $builder->where('bookings.game_id', $gameId);
            }
            
            // Filtres de date pour le calendrier
            $start = $this->request->getGet('start');
            $end = $this->request->getGet('end');
            if ($start && $end) {
                $builder->where('bookings.booking_date >=', $start);
                $builder->where('bookings.booking_date <=', $end);
            }
            
            // Trier par date et heure
            $builder->orderBy('bookings.booking_date', 'ASC');
            $builder->orderBy('bookings.start_time', 'ASC');
            
            $bookings = $builder->get()->getResultArray();
            
            return $this->respond([
                'status' => 'success',
                'data' => $bookings,
                'count' => count($bookings)
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Erreur API Booking/index : ' . $e->getMessage());
            return $this->failServerError('Erreur lors de la récupération des réservations');
        }
    }

    /**
     * Crée une nouvelle réservation
     * 
     * Endpoint : POST /api/booking/create
     * 
     * Body (JSON) :
     * {
     *   "room_id": 1,
     *   "game_id": 1,
     *   "booking_date": "2026-01-25",
     *   "start_time": "14:00:00",
     *   "end_time": "15:00:00",
     *   "customer_name": "John Doe",
     *   "customer_email": "john@example.com",
     *   "customer_phone": "+216 20 123 456",
     *   "num_players": 4,
     *   "participants": [
     *     {"name": "Player 1", "email": "p1@example.com"},
     *     {"name": "Player 2"}
     *   ],
     *   "notes": "Anniversaire"
     * }
     * 
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function create()
    {
        try {
            // Récupérer les données JSON
            $json = $this->request->getJSON(true);

            if (!$json) {
                return $this->failValidationErrors('Données JSON invalides');
            }

            // Créer la réservation via le service
            $result = $this->bookingService->createBooking($json);

            if ($result['success']) {
                return $this->respondCreated([
                    'status' => 'success',
                    'message' => $result['message'],
                    'booking_id' => $result['booking_id'],
                    'data' => $result['data']
                ]);
            } else {
                return $this->fail([
                    'status' => 'error',
                    'message' => $result['message']
                ], 400);
            }

        } catch (\Exception $e) {
            log_message('error', 'Erreur API Booking/create : ' . $e->getMessage());
            
            return $this->failServerError('Une erreur est survenue lors de la création de la réservation');
        }
    }

    /**
     * Annule une réservation
     * 
     * Endpoint : POST /api/booking/cancel/{id}
     * 
     * Body (JSON) :
     * {
     *   "reason": "Changement de plans"
     * }
     * 
     * @param int $id ID de la réservation
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function cancel($id)
    {
        try {
            if (!$id || !is_numeric($id)) {
                return $this->failValidationErrors('ID de réservation invalide');
            }

            $json = $this->request->getJSON(true);
            $reason = $json['reason'] ?? 'Annulation par le client';

            $result = $this->bookingService->cancelBooking((int) $id, $reason);

            if ($result['success']) {
                return $this->respond([
                    'status' => 'success',
                    'message' => $result['message']
                ]);
            } else {
                return $this->fail([
                    'status' => 'error',
                    'message' => $result['message']
                ], 400);
            }

        } catch (\Exception $e) {
            log_message('error', 'Erreur API Booking/cancel : ' . $e->getMessage());
            
            return $this->failServerError('Une erreur est survenue');
        }
    }

    /**
     * Confirme une réservation (après paiement)
     * 
     * Endpoint : POST /api/booking/confirm/{id}
     * 
     * @param int $id
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function confirm($id)
    {
        try {
            if (!$id || !is_numeric($id)) {
                return $this->failValidationErrors('ID de réservation invalide');
            }

            $result = $this->bookingService->confirmBooking((int) $id);

            if ($result['success']) {
                return $this->respond([
                    'status' => 'success',
                    'message' => $result['message']
                ]);
            } else {
                return $this->fail([
                    'status' => 'error',
                    'message' => $result['message']
                ], 400);
            }

        } catch (\Exception $e) {
            log_message('error', 'Erreur API Booking/confirm : ' . $e->getMessage());
            
            return $this->failServerError('Une erreur est survenue');
        }
    }

    /**
     * Récupère les détails d'une réservation (méthode REST standard)
     * 
     * Endpoint : GET /api/booking/{id}
     * 
     * @param int $id
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function show($id = null)
    {
        try {
            if (!$id || !is_numeric($id)) {
                return $this->failValidationErrors('ID de réservation invalide');
            }

            $details = $this->bookingService->getBookingDetails((int) $id);

            if ($details) {
                return $this->respond([
                    'status' => 'success',
                    'data' => $details
                ]);
            } else {
                return $this->failNotFound('Réservation introuvable');
            }

        } catch (\Exception $e) {
            log_message('error', 'Erreur API Booking/show : ' . $e->getMessage());
            
            return $this->failServerError('Une erreur est survenue');
        }
    }

    /**
     * Récupère les détails d'une réservation
     * 
     * Endpoint : GET /api/booking/{id}
     * 
     * @param int $id
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function get($id)
    {
        try {
            if (!$id || !is_numeric($id)) {
                return $this->failValidationErrors('ID de réservation invalide');
            }

            $details = $this->bookingService->getBookingDetails((int) $id);

            if ($details) {
                return $this->respond([
                    'status' => 'success',
                    'data' => $details
                ]);
            } else {
                return $this->failNotFound('Réservation introuvable');
            }

        } catch (\Exception $e) {
            log_message('error', 'Erreur API Booking/get : ' . $e->getMessage());
            
            return $this->failServerError('Une erreur est survenue');
        }
    }

    /**
     * Récupère les réservations d'un client par email
     * 
     * Endpoint : GET /api/booking/customer?email=john@example.com
     * 
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function customer()
    {
        try {
            $email = $this->request->getGet('email');

            if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return $this->failValidationErrors('Email invalide');
            }

            $bookings = $this->bookingService->getCustomerBookings($email);

            return $this->respond([
                'status' => 'success',
                'data' => $bookings,
                'count' => count($bookings)
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Erreur API Booking/customer : ' . $e->getMessage());
            
            return $this->failServerError('Une erreur est survenue');
        }
    }

    /**
     * Marque une réservation comme terminée
     * 
     * Endpoint : POST /api/booking/complete/{id}
     * 
     * @param int $id
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function complete($id)
    {
        try {
            if (!$id || !is_numeric($id)) {
                return $this->failValidationErrors('ID de réservation invalide');
            }

            $result = $this->bookingService->completeBooking((int) $id);

            if ($result['success']) {
                return $this->respond([
                    'status' => 'success',
                    'message' => $result['message']
                ]);
            } else {
                return $this->fail([
                    'status' => 'error',
                    'message' => $result['message']
                ], 400);
            }

        } catch (\Exception $e) {
            log_message('error', 'Erreur API Booking/complete : ' . $e->getMessage());
            
            return $this->failServerError('Une erreur est survenue');
        }
    }

    /**
     * Gère les requêtes OPTIONS (CORS preflight)
     */
    public function options()
    {
        return $this->respond(['status' => 'ok'], 200);
    }
}
