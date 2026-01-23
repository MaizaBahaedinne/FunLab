<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Services\AvailabilityService;

/**
 * AvailabilityApi
 * 
 * API REST pour la gestion des disponibilités en temps réel.
 * Utilisée par le frontend via AJAX pour afficher les créneaux disponibles.
 * 
 * @package App\Controllers\Api
 * @author FunLab Team
 * @version 1.0.0
 */
class AvailabilityApi extends ResourceController
{
    protected $format = 'json';
    protected $availabilityService;

    public function __construct()
    {
        $this->availabilityService = new AvailabilityService();
        
        // Headers CORS si nécessaire
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type');
    }

    /**
     * Récupère tous les créneaux disponibles pour un jeu à une date donnée
     * 
     * Endpoint : GET /api/availability/slots
     * 
     * Paramètres requis :
     * - game_id (int) : ID du jeu
     * - date (string) : Date au format YYYY-MM-DD
     * 
     * Exemple de requête :
     * GET /api/availability/slots?game_id=1&date=2026-01-25
     * 
     * Exemple de réponse :
     * {
     *   "status": "success",
     *   "data": {
     *     "room_1": [
     *       {
     *         "start": "10:00:00",
     *         "end": "11:00:00",
     *         "start_formatted": "10:00",
     *         "end_formatted": "11:00",
     *         "room_id": 1,
     *         "room_name": "Salle VR"
     *       }
     *     ]
     *   },
     *   "message": "Créneaux récupérés avec succès"
     * }
     * 
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function slots()
    {
        try {
            // Récupération des paramètres
            $gameId = $this->request->getGet('game_id');
            $date = $this->request->getGet('date');

            // Validation des paramètres
            if (!$gameId || !$date) {
                return $this->failValidationErrors('Paramètres manquants : game_id et date sont requis');
            }

            // Validation du format de la date
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
                return $this->failValidationErrors('Format de date invalide. Utilisez YYYY-MM-DD');
            }

            // Validation que game_id est un entier
            if (!is_numeric($gameId)) {
                return $this->failValidationErrors('game_id doit être un nombre entier');
            }

            $gameId = (int) $gameId;

            // Appel au service pour récupérer les créneaux
            $availableSlots = $this->availabilityService->getAvailableSlots($gameId, $date);

            // Vérifier si des créneaux sont disponibles
            if (empty($availableSlots)) {
                return $this->respond([
                    'status' => 'success',
                    'data' => [],
                    'message' => 'Aucun créneau disponible pour cette date'
                ], 200);
            }

            // Retour des créneaux disponibles
            return $this->respond([
                'status' => 'success',
                'data' => $availableSlots,
                'message' => 'Créneaux récupérés avec succès',
                'count' => count($availableSlots)
            ], 200);

        } catch (\Exception $e) {
            log_message('error', 'Erreur API Availability/slots : ' . $e->getMessage());
            
            return $this->failServerError('Une erreur est survenue lors de la récupération des créneaux');
        }
    }

    /**
     * Vérifie la disponibilité d'un créneau spécifique
     * 
     * Endpoint : POST /api/availability/check
     * 
     * Paramètres requis (JSON body) :
     * - room_id (int)
     * - game_id (int)
     * - date (string) : YYYY-MM-DD
     * - start_time (string) : HH:MM:SS
     * - end_time (string) : HH:MM:SS
     * 
     * Exemple de requête :
     * POST /api/availability/check
     * {
     *   "room_id": 1,
     *   "game_id": 2,
     *   "date": "2026-01-25",
     *   "start_time": "14:00:00",
     *   "end_time": "15:30:00"
     * }
     * 
     * Exemple de réponse :
     * {
     *   "status": "success",
     *   "available": true,
     *   "message": "Créneau disponible"
     * }
     * 
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function check()
    {
        try {
            // Récupération des données JSON
            $json = $this->request->getJSON();

            // Validation des paramètres requis
            $requiredFields = ['room_id', 'game_id', 'date', 'start_time', 'end_time'];
            foreach ($requiredFields as $field) {
                if (!isset($json->$field) || empty($json->$field)) {
                    return $this->failValidationErrors("Le champ {$field} est requis");
                }
            }

            // Extraction et conversion des données
            $roomId = (int) $json->room_id;
            $gameId = (int) $json->game_id;
            $date = $json->date;
            $startTime = $json->start_time;
            $endTime = $json->end_time;

            // Appel au service pour vérifier la disponibilité
            $result = $this->availabilityService->checkSlotAvailability(
                $roomId,
                $gameId,
                $date,
                $startTime,
                $endTime
            );

            // Retour de la réponse
            if ($result['available']) {
                return $this->respond([
                    'status' => 'success',
                    'available' => true,
                    'message' => $result['message']
                ], 200);
            } else {
                return $this->respond([
                    'status' => 'error',
                    'available' => false,
                    'message' => $result['message']
                ], 200); // 200 car ce n'est pas une erreur serveur
            }

        } catch (\Exception $e) {
            log_message('error', 'Erreur API Availability/check : ' . $e->getMessage());
            
            return $this->failServerError('Une erreur est survenue lors de la vérification');
        }
    }

    /**
     * Récupère les salles disponibles pour un jeu donné
     * 
     * Endpoint : GET /api/availability/rooms
     * 
     * Paramètres requis :
     * - game_id (int)
     * 
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function rooms()
    {
        try {
            $gameId = $this->request->getGet('game_id');

            if (!$gameId || !is_numeric($gameId)) {
                return $this->failValidationErrors('game_id requis et doit être un entier');
            }

            $rooms = $this->availabilityService->getRoomsForGame((int) $gameId);

            return $this->respond([
                'status' => 'success',
                'data' => $rooms,
                'message' => 'Salles récupérées avec succès',
                'count' => count($rooms)
            ], 200);

        } catch (\Exception $e) {
            log_message('error', 'Erreur API Availability/rooms : ' . $e->getMessage());
            
            return $this->failServerError('Une erreur est survenue');
        }
    }

    /**
     * Vérifie si une date est fermée (globalement ou pour une salle)
     * 
     * Endpoint : GET /api/availability/closure
     * 
     * Paramètres requis :
     * - date (string) : YYYY-MM-DD
     * 
     * Paramètres optionnels :
     * - room_id (int)
     * 
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function closure()
    {
        try {
            $date = $this->request->getGet('date');
            $roomId = $this->request->getGet('room_id');

            if (!$date) {
                return $this->failValidationErrors('Le paramètre date est requis');
            }

            $roomId = $roomId ? (int) $roomId : null;
            $isClosed = $this->availabilityService->isClosed($date, $roomId);

            return $this->respond([
                'status' => 'success',
                'is_closed' => $isClosed,
                'message' => $isClosed ? 'Fermé ce jour-là' : 'Ouvert'
            ], 200);

        } catch (\Exception $e) {
            log_message('error', 'Erreur API Availability/closure : ' . $e->getMessage());
            
            return $this->failServerError('Une erreur est survenue');
        }
    }

    /**
     * Récupère les créneaux occupés pour une salle et une date
     * (Utile pour afficher le planning dans l'admin)
     * 
     * Endpoint : GET /api/availability/occupied
     * 
     * Paramètres requis :
     * - room_id (int)
     * - date (string) : YYYY-MM-DD
     * 
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function occupied()
    {
        try {
            $roomId = $this->request->getGet('room_id');
            $date = $this->request->getGet('date');

            if (!$roomId || !$date) {
                return $this->failValidationErrors('room_id et date sont requis');
            }

            if (!is_numeric($roomId)) {
                return $this->failValidationErrors('room_id doit être un entier');
            }

            $occupiedSlots = $this->availabilityService->getOccupiedSlots((int) $roomId, $date);

            return $this->respond([
                'status' => 'success',
                'data' => $occupiedSlots,
                'count' => count($occupiedSlots)
            ], 200);

        } catch (\Exception $e) {
            log_message('error', 'Erreur API Availability/occupied : ' . $e->getMessage());
            
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
