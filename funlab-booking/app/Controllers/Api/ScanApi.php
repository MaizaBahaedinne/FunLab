<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Services\QRCodeService;
use App\Models\BookingModel;
use App\Models\ParticipantModel;

/**
 * API de Scan des QR Codes
 * 
 * Gestion du scanner d'entrée pour valider les billets
 * et enregistrer le check-in des participants
 */
class ScanApi extends ResourceController
{
    protected $format = 'json';
    protected $qrCodeService;
    protected $bookingModel;
    protected $participantModel;

    public function __construct()
    {
        $this->qrCodeService = new QRCodeService();
        $this->bookingModel = new BookingModel();
        $this->participantModel = new ParticipantModel();
    }

    /**
     * POST /api/scan/validate
     * 
     * Valider un QR code et récupérer les informations de la réservation
     * 
     * @return Response JSON
     */
    public function validate()
    {
        $qrCodeData = $this->request->getJSON(true);

        if (!$qrCodeData) {
            return $this->fail('QR code invalide ou données manquantes', 400);
        }

        try {
            $validation = $this->qrCodeService->validateQRCode($qrCodeData);

            if (!$validation['valid']) {
                return $this->respond([
                    'status' => 'error',
                    'message' => $validation['error'],
                    'data' => null
                ], 400);
            }

            // Récupérer les détails complets de la réservation
            $booking = $this->bookingModel
                ->select('bookings.*, rooms.name as room_name, games.name as game_name')
                ->join('rooms', 'rooms.id = bookings.room_id')
                ->join('games', 'games.id = bookings.game_id')
                ->find($validation['booking_id']);

            if (!$booking) {
                return $this->fail('Réservation non trouvée', 404);
            }

            // Récupérer les participants
            $participants = $this->participantModel
                ->where('booking_id', $booking['id'])
                ->findAll();

            // Compter les participants déjà entrés
            $checkedInCount = 0;
            foreach ($participants as $participant) {
                if ($participant['checked_in'] == 1) {
                    $checkedInCount++;
                }
            }

            // Déterminer le statut d'accès
            $accessStatus = $this->determineAccessStatus($booking);

            return $this->respond([
                'status' => 'success',
                'message' => 'QR code valide',
                'data' => [
                    'valid' => true,
                    'access_granted' => $accessStatus['granted'],
                    'access_message' => $accessStatus['message'],
                    'booking' => [
                        'id' => $booking['id'],
                        'confirmation_code' => $booking['confirmation_code'],
                        'status' => $booking['status'],
                        'game_name' => $booking['game_name'],
                        'room_name' => $booking['room_name'],
                        'booking_date' => $booking['booking_date'],
                        'start_time' => substr($booking['start_time'], 0, 5),
                        'end_time' => substr($booking['end_time'], 0, 5),
                        'customer_name' => $booking['customer_name'],
                        'num_players' => $booking['num_players'],
                        'total_price' => $booking['total_price']
                    ],
                    'participants' => [
                        'total' => count($participants),
                        'checked_in' => $checkedInCount,
                        'remaining' => count($participants) - $checkedInCount,
                        'list' => array_map(function($p) {
                            return [
                                'id' => $p['id'],
                                'name' => $p['name'],
                                'checked_in' => $p['checked_in'] == 1,
                                'check_in_time' => $p['check_in_time']
                            ];
                        }, $participants)
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Erreur validation QR code: ' . $e->getMessage());
            return $this->fail('Erreur lors de la validation: ' . $e->getMessage(), 500);
        }
    }

    /**
     * POST /api/scan/checkin
     * 
     * Enregistrer le check-in d'un ou plusieurs participants
     * 
     * Body: {
     *   "booking_id": 123,
     *   "participant_ids": [1, 2, 3]  // Optionnel, si vide = tous les participants
     * }
     * 
     * @return Response JSON
     */
    public function checkin()
    {
        $data = $this->request->getJSON(true);

        if (!isset($data['booking_id'])) {
            return $this->fail('booking_id requis', 400);
        }

        $bookingId = $data['booking_id'];

        // Vérifier que la réservation existe et est confirmée
        $booking = $this->bookingModel->find($bookingId);

        if (!$booking) {
            return $this->fail('Réservation non trouvée', 404);
        }

        if ($booking['status'] !== 'confirmed' && $booking['status'] !== 'in_progress') {
            return $this->fail('Réservation non confirmée', 400);
        }

        try {
            $db = \Config\Database::connect();
            $db->transStart();

            // Si des IDs de participants sont spécifiés
            if (isset($data['participant_ids']) && !empty($data['participant_ids'])) {
                $participantIds = $data['participant_ids'];
            } else {
                // Sinon, récupérer tous les participants non encore entrés
                $allParticipants = $this->participantModel
                    ->where('booking_id', $bookingId)
                    ->where('checked_in', 0)
                    ->findAll();
                
                $participantIds = array_column($allParticipants, 'id');
            }

            if (empty($participantIds)) {
                return $this->respond([
                    'status' => 'info',
                    'message' => 'Tous les participants sont déjà enregistrés',
                    'data' => ['checked_in_count' => 0]
                ]);
            }

            // Enregistrer le check-in
            $checkInTime = date('Y-m-d H:i:s');
            $checkedInCount = 0;

            foreach ($participantIds as $participantId) {
                $updated = $this->participantModel->update($participantId, [
                    'checked_in' => 1,
                    'check_in_time' => $checkInTime
                ]);

                if ($updated) {
                    $checkedInCount++;
                }
            }

            // Mettre à jour le statut de la réservation si tous les participants sont entrés
            $totalParticipants = $this->participantModel
                ->where('booking_id', $bookingId)
                ->countAllResults();

            $totalCheckedIn = $this->participantModel
                ->where('booking_id', $bookingId)
                ->where('checked_in', 1)
                ->countAllResults();

            if ($totalCheckedIn >= $totalParticipants && $booking['status'] === 'confirmed') {
                $this->bookingModel->update($bookingId, [
                    'status' => 'in_progress',
                    'check_in_time' => $checkInTime
                ]);
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                return $this->fail('Erreur lors de l\'enregistrement', 500);
            }

            return $this->respond([
                'status' => 'success',
                'message' => 'Check-in enregistré avec succès',
                'data' => [
                    'checked_in_count' => $checkedInCount,
                    'total_participants' => $totalParticipants,
                    'all_checked_in' => $totalCheckedIn >= $totalParticipants
                ]
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Erreur check-in: ' . $e->getMessage());
            return $this->fail('Erreur lors du check-in: ' . $e->getMessage(), 500);
        }
    }

    /**
     * POST /api/scan/complete
     * 
     * Marquer une réservation comme terminée
     * 
     * Body: { "booking_id": 123 }
     * 
     * @return Response JSON
     */
    public function complete()
    {
        $data = $this->request->getJSON(true);

        if (!isset($data['booking_id'])) {
            return $this->fail('booking_id requis', 400);
        }

        $bookingId = $data['booking_id'];

        $booking = $this->bookingModel->find($bookingId);

        if (!$booking) {
            return $this->fail('Réservation non trouvée', 404);
        }

        if ($booking['status'] === 'completed') {
            return $this->respond([
                'status' => 'info',
                'message' => 'Réservation déjà marquée comme terminée',
                'data' => ['booking_id' => $bookingId]
            ]);
        }

        try {
            $updated = $this->bookingModel->update($bookingId, [
                'status' => 'completed',
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            if (!$updated) {
                return $this->fail('Erreur lors de la mise à jour', 500);
            }

            return $this->respond([
                'status' => 'success',
                'message' => 'Réservation marquée comme terminée',
                'data' => [
                    'booking_id' => $bookingId,
                    'status' => 'completed'
                ]
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Erreur completion: ' . $e->getMessage());
            return $this->fail('Erreur: ' . $e->getMessage(), 500);
        }
    }

    /**
     * GET /api/scan/stats
     * 
     * Statistiques du scanner en temps réel
     * 
     * @return Response JSON
     */
    public function stats()
    {
        try {
            $today = date('Y-m-d');

            // Réservations du jour
            $todayBookings = $this->bookingModel
                ->where('booking_date', $today)
                ->where('status !=', 'cancelled')
                ->findAll();

            $stats = [
                'total_bookings' => count($todayBookings),
                'confirmed' => 0,
                'in_progress' => 0,
                'completed' => 0,
                'pending' => 0,
                'total_participants' => 0,
                'checked_in_participants' => 0
            ];

            foreach ($todayBookings as $booking) {
                $stats[$booking['status']]++;
                $stats['total_participants'] += $booking['num_players'];
            }

            // Compter les participants déjà entrés
            $checkedIn = $this->participantModel
                ->join('bookings', 'bookings.id = participants.booking_id')
                ->where('bookings.booking_date', $today)
                ->where('participants.checked_in', 1)
                ->countAllResults();

            $stats['checked_in_participants'] = $checkedIn;

            // Prochaines réservations (3 prochaines heures)
            $currentTime = date('H:i:s');
            $nextThreeHours = date('H:i:s', strtotime('+3 hours'));

            $upcoming = $this->bookingModel
                ->select('bookings.*, rooms.name as room_name, games.name as game_name')
                ->join('rooms', 'rooms.id = bookings.room_id')
                ->join('games', 'games.id = bookings.game_id')
                ->where('booking_date', $today)
                ->where('start_time >=', $currentTime)
                ->where('start_time <=', $nextThreeHours)
                ->where('status', 'confirmed')
                ->orderBy('start_time', 'ASC')
                ->findAll(5);

            return $this->respond([
                'status' => 'success',
                'data' => [
                    'stats' => $stats,
                    'upcoming_bookings' => array_map(function($b) {
                        return [
                            'id' => $b['id'],
                            'confirmation_code' => $b['confirmation_code'],
                            'customer_name' => $b['customer_name'],
                            'game_name' => $b['game_name'],
                            'room_name' => $b['room_name'],
                            'start_time' => substr($b['start_time'], 0, 5),
                            'num_players' => $b['num_players']
                        ];
                    }, $upcoming)
                ]
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Erreur stats scanner: ' . $e->getMessage());
            return $this->fail('Erreur: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Déterminer si l'accès doit être accordé selon le statut et l'heure
     * 
     * @param array $booking
     * @return array ['granted' => bool, 'message' => string]
     */
    private function determineAccessStatus(array $booking): array
    {
        // Vérifier le statut
        if ($booking['status'] === 'cancelled') {
            return [
                'granted' => false,
                'message' => 'Réservation annulée - Accès refusé'
            ];
        }

        if ($booking['status'] === 'pending') {
            return [
                'granted' => false,
                'message' => 'Réservation en attente de confirmation - Accès refusé'
            ];
        }

        if ($booking['status'] === 'completed') {
            return [
                'granted' => false,
                'message' => 'Réservation déjà utilisée - Accès refusé'
            ];
        }

        // Vérifier la date
        $bookingDate = $booking['booking_date'];
        $today = date('Y-m-d');

        if ($bookingDate < $today) {
            return [
                'granted' => false,
                'message' => 'Date de réservation dépassée - Accès refusé'
            ];
        }

        if ($bookingDate > $today) {
            return [
                'granted' => false,
                'message' => 'Réservation prévue pour le ' . date('d/m/Y', strtotime($bookingDate)) . ' - Trop tôt'
            ];
        }

        // Vérifier l'heure (tolérance de 15 minutes avant)
        $currentTime = date('H:i:s');
        $startTime = $booking['start_time'];
        $endTime = $booking['end_time'];

        $startTimestamp = strtotime($bookingDate . ' ' . $startTime);
        $endTimestamp = strtotime($bookingDate . ' ' . $endTime);
        $currentTimestamp = strtotime($today . ' ' . $currentTime);

        $toleranceMinutes = 15 * 60; // 15 minutes

        if ($currentTimestamp < ($startTimestamp - $toleranceMinutes)) {
            $minutesEarly = round(($startTimestamp - $currentTimestamp) / 60);
            return [
                'granted' => false,
                'message' => "Trop tôt - Votre créneau commence dans {$minutesEarly} minutes"
            ];
        }

        if ($currentTimestamp > $endTimestamp) {
            return [
                'granted' => false,
                'message' => 'Créneau terminé - Accès refusé'
            ];
        }

        // Tout est OK
        if ($booking['status'] === 'in_progress') {
            return [
                'granted' => true,
                'message' => 'Session en cours - Accès accordé'
            ];
        }

        return [
            'granted' => true,
            'message' => 'Bienvenue ! Accès accordé'
        ];
    }
}
