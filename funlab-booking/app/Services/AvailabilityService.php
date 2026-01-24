<?php

namespace App\Services;

use App\Models\BookingModel;
use App\Models\ClosureModel;
use App\Models\RoomModel;
use App\Models\GameModel;
use App\Models\RoomGameModel;

/**
 * AvailabilityService
 * 
 * Service central pour la gestion des disponibilités des salles.
 * Empêche toute double réservation et gère les conflits horaires.
 * 
 * @package App\Services
 * @author FunLab Team
 * @version 1.0.0
 */
class AvailabilityService
{
    protected $bookingModel;
    protected $closureModel;
    protected $roomModel;
    protected $gameModel;
    protected $roomGameModel;

    // Constantes de configuration
    const OPENING_TIME = '09:00:00';
    const CLOSING_TIME = '22:00:00';
    const SLOT_INCREMENT = 30; // minutes
    const TIME_FORMAT = 'H:i:s';
    const DATE_FORMAT = 'Y-m-d';

    public function __construct()
    {
        $this->bookingModel = new BookingModel();
        $this->closureModel = new ClosureModel();
        $this->roomModel = new RoomModel();
        $this->gameModel = new GameModel();
        $this->roomGameModel = new RoomGameModel();
    }

    /**
     * Récupère tous les créneaux disponibles pour un jeu à une date donnée
     * 
     * @param int $gameId ID du jeu
     * @param string $date Date au format YYYY-MM-DD
     * @return array Tableau des créneaux disponibles par salle
     * 
     * Format de retour :
     * [
     *   'room_1' => [
     *     ['start' => '10:00', 'end' => '11:00', 'room_id' => 1, 'room_name' => 'Salle VR'],
     *     ['start' => '11:30', 'end' => '12:30', 'room_id' => 1, 'room_name' => 'Salle VR']
     *   ],
     *   'room_2' => [...]
     * ]
     */
    public function getAvailableSlots(int $gameId, string $date): array
    {
        // Validation de la date
        if (!$this->isValidDate($date)) {
            return [];
        }

        // Ne pas autoriser les réservations dans le passé
        if (strtotime($date) < strtotime('today')) {
            return [];
        }

        // Récupérer les informations du jeu
        $game = $this->gameModel->find($gameId);
        if (!$game) {
            return [];
        }

        $durationMinutes = (int) $game['duration_minutes'];

        // Récupérer les salles compatibles avec ce jeu
        $rooms = $this->getRoomsForGame($gameId);
        if (empty($rooms)) {
            return [];
        }

        $availableSlots = [];

        foreach ($rooms as $room) {
            $roomId = (int) $room['room_id'];
            
            // Vérifier si la salle est fermée ce jour-là
            if ($this->isClosed($date, $roomId)) {
                continue;
            }

            // Générer tous les créneaux possibles pour cette salle
            $slots = $this->generatePossibleSlots($durationMinutes, $date);

            // Filtrer les créneaux disponibles
            $availableSlotsForRoom = [];
            foreach ($slots as $slot) {
                if ($this->isSlotFree($roomId, $date, $slot['start'], $slot['end'])) {
                    $slot['room_id'] = $roomId;
                    $slot['room_name'] = $room['room_name'];
                    $availableSlotsForRoom[] = $slot;
                }
            }

            if (!empty($availableSlotsForRoom)) {
                $availableSlots['room_' . $roomId] = $availableSlotsForRoom;
            }
        }

        return $availableSlots;
    }

    /**
     * Récupère TOUS les créneaux (disponibles ET indisponibles) pour un jeu à une date donnée
     * 
     * @param int $gameId ID du jeu
     * @param string $date Date au format YYYY-MM-DD
     * @return array Tableau des créneaux avec leur statut par salle
     * 
     * Format de retour :
     * [
     *   'room_1' => [
     *     ['start' => '10:00', 'end' => '11:00', 'available' => true, 'room_id' => 1, 'room_name' => 'Salle VR'],
     *     ['start' => '11:30', 'end' => '12:30', 'available' => false, 'room_id' => 1, 'room_name' => 'Salle VR']
     *   ],
     *   'room_2' => [...]
     * ]
     */
    public function getAllSlotsWithStatus(int $gameId, string $date): array
    {
        // Validation de la date
        if (!$this->isValidDate($date)) {
            return [];
        }

        // Ne pas autoriser les réservations dans le passé
        if (strtotime($date) < strtotime('today')) {
            return [];
        }

        // Récupérer les informations du jeu
        $game = $this->gameModel->find($gameId);
        if (!$game) {
            return [];
        }

        $durationMinutes = (int) $game['duration_minutes'];

        // Récupérer les salles compatibles avec ce jeu
        $rooms = $this->getRoomsForGame($gameId);
        if (empty($rooms)) {
            return [];
        }

        $allSlots = [];

        foreach ($rooms as $room) {
            $roomId = (int) $room['room_id'];
            
            // Générer tous les créneaux possibles pour cette salle
            $slots = $this->generatePossibleSlots($durationMinutes, $date);

            // Ajouter le statut disponible/indisponible à chaque créneau
            $slotsWithStatus = [];
            foreach ($slots as $slot) {
                $isClosed = $this->isClosed($date, $roomId);
                $isFree = !$isClosed && $this->isSlotFree($roomId, $date, $slot['start'], $slot['end']);
                
                $slot['room_id'] = $roomId;
                $slot['room_name'] = $room['room_name'];
                $slot['available'] = $isFree;
                $slotsWithStatus[] = $slot;
            }

            if (!empty($slotsWithStatus)) {
                $allSlots['room_' . $roomId] = $slotsWithStatus;
            }
        }

        return $allSlots;
    }

    /**
     * Génère tous les créneaux possibles pour une durée donnée
     * 
     * @param int $durationMinutes Durée du jeu en minutes
     * @param string $date Date de la réservation
     * @return array Tableau des créneaux possibles
     */
    private function generatePossibleSlots(int $durationMinutes, string $date): array
    {
        $slots = [];
        $currentTime = strtotime($date . ' ' . self::OPENING_TIME);
        $closingTime = strtotime($date . ' ' . self::CLOSING_TIME);

        while ($currentTime < $closingTime) {
            $slotEnd = $currentTime + ($durationMinutes * 60);

            // Le créneau doit se terminer avant l'heure de fermeture
            if ($slotEnd <= $closingTime) {
                $slots[] = [
                    'start' => date(self::TIME_FORMAT, $currentTime),
                    'end' => date(self::TIME_FORMAT, $slotEnd),
                    'start_formatted' => date('H:i', $currentTime),
                    'end_formatted' => date('H:i', $slotEnd)
                ];
            }

            // Incrémenter par pas de 30 minutes
            $currentTime += (self::SLOT_INCREMENT * 60);
        }

        return $slots;
    }

    /**
     * Vérifie si un créneau est libre (sans conflit)
     * 
     * RÈGLE CRITIQUE : Détecte tout chevauchement de créneaux
     * Un créneau A chevauche un créneau B si :
     * - A commence avant la fin de B ET
     * - A se termine après le début de B
     * 
     * @param int $roomId ID de la salle
     * @param string $date Date au format YYYY-MM-DD
     * @param string $startTime Heure de début (HH:MM:SS)
     * @param string $endTime Heure de fin (HH:MM:SS)
     * @return bool True si le créneau est libre
     */
    public function isSlotFree(int $roomId, string $date, string $startTime, string $endTime): bool
    {
        // Vérifier les conflits avec les réservations existantes
        $conflicts = $this->bookingModel
            ->where('room_id', $roomId)
            ->where('booking_date', $date)
            ->whereIn('status', ['confirmed', 'pending']) // Ignorer les annulées
            ->groupStart()
                // Cas 1 : La nouvelle réservation commence pendant une réservation existante
                ->groupStart()
                    ->where('start_time <=', $startTime)
                    ->where('end_time >', $startTime)
                ->groupEnd()
                // Cas 2 : La nouvelle réservation se termine pendant une réservation existante
                ->orGroupStart()
                    ->where('start_time <', $endTime)
                    ->where('end_time >=', $endTime)
                ->groupEnd()
                // Cas 3 : La nouvelle réservation englobe complètement une réservation existante
                ->orGroupStart()
                    ->where('start_time >=', $startTime)
                    ->where('end_time <=', $endTime)
                ->groupEnd()
            ->groupEnd()
            ->countAllResults();

        return $conflicts === 0;
    }

    /**
     * Vérifie si une salle ou toutes les salles sont fermées à une date donnée
     * 
     * @param string $date Date au format YYYY-MM-DD
     * @param int|null $roomId ID de la salle (null pour vérification globale)
     * @return bool True si fermé
     */
    public function isClosed(string $date, ?int $roomId = null): bool
    {
        $builder = $this->closureModel
            ->where('closure_date', $date);

        if ($roomId !== null) {
            // Vérifier les fermetures spécifiques à cette salle OU globales
            $builder->groupStart()
                ->where('room_id', $roomId)
                ->orWhere('all_rooms', 1)
            ->groupEnd();
        } else {
            // Vérifier uniquement les fermetures globales
            $builder->where('all_rooms', 1);
        }

        $closures = $builder->countAllResults();

        return $closures > 0;
    }

    /**
     * Récupère les salles compatibles avec un jeu donné
     * 
     * @param int $gameId ID du jeu
     * @return array Liste des salles avec leurs informations
     */
    public function getRoomsForGame(int $gameId): array
    {
        $rooms = $this->roomGameModel
            ->select('room_games.room_id, rooms.name as room_name, rooms.capacity, rooms.status')
            ->join('rooms', 'rooms.id = room_games.room_id')
            ->where('room_games.game_id', $gameId)
            ->where('rooms.status', 'active')
            ->where('room_games.is_available', 1)
            ->findAll();

        return $rooms;
    }

    /**
     * Vérifie si un créneau spécifique est disponible pour une réservation
     * (Méthode complète avec toutes les validations)
     * 
     * @param int $roomId ID de la salle
     * @param int $gameId ID du jeu
     * @param string $date Date de réservation
     * @param string $startTime Heure de début
     * @param string $endTime Heure de fin
     * @return array ['available' => bool, 'message' => string]
     */
    public function checkSlotAvailability(int $roomId, int $gameId, string $date, string $startTime, string $endTime): array
    {
        // Validation 1 : Date valide
        if (!$this->isValidDate($date)) {
            return ['available' => false, 'message' => 'Date invalide'];
        }

        // Validation 2 : Pas de réservation dans le passé
        if (strtotime($date) < strtotime('today')) {
            return ['available' => false, 'message' => 'Impossible de réserver dans le passé'];
        }

        // Validation 3 : Horaires valides
        if (!$this->isWithinOpeningHours($startTime, $endTime)) {
            return ['available' => false, 'message' => 'Horaires hors des heures d\'ouverture (09:00-22:00)'];
        }

        // Validation 4 : Salle existe et est active
        $room = $this->roomModel->find($roomId);
        if (!$room || $room['status'] !== 'active') {
            return ['available' => false, 'message' => 'Salle non disponible'];
        }

        // Validation 5 : Jeu existe
        $game = $this->gameModel->find($gameId);
        if (!$game) {
            return ['available' => false, 'message' => 'Jeu introuvable'];
        }

        // Validation 6 : La salle est compatible avec ce jeu
        $roomGameCompatibility = $this->roomGameModel
            ->where('room_id', $roomId)
            ->where('game_id', $gameId)
            ->where('is_available', 1)
            ->first();

        if (!$roomGameCompatibility) {
            return ['available' => false, 'message' => 'Ce jeu n\'est pas disponible dans cette salle'];
        }

        // Validation 7 : Vérifier la fermeture
        if ($this->isClosed($date, $roomId)) {
            return ['available' => false, 'message' => 'La salle est fermée à cette date'];
        }

        // Validation 8 : Vérifier les conflits horaires
        if (!$this->isSlotFree($roomId, $date, $startTime, $endTime)) {
            return ['available' => false, 'message' => 'Ce créneau est déjà réservé'];
        }

        // Toutes les validations passées ✓
        return ['available' => true, 'message' => 'Créneau disponible'];
    }

    /**
     * Vérifie si les horaires sont dans les heures d'ouverture
     * 
     * @param string $startTime Heure de début
     * @param string $endTime Heure de fin
     * @return bool
     */
    private function isWithinOpeningHours(string $startTime, string $endTime): bool
    {
        $start = strtotime('today ' . $startTime);
        $end = strtotime('today ' . $endTime);
        $opening = strtotime('today ' . self::OPENING_TIME);
        $closing = strtotime('today ' . self::CLOSING_TIME);

        return $start >= $opening && $end <= $closing && $start < $end;
    }

    /**
     * Valide le format d'une date
     * 
     * @param string $date Date à valider
     * @return bool
     */
    private function isValidDate(string $date): bool
    {
        $d = \DateTime::createFromFormat(self::DATE_FORMAT, $date);
        return $d && $d->format(self::DATE_FORMAT) === $date;
    }

    /**
     * Récupère le nombre de réservations pour une période donnée
     * (Utile pour les statistiques)
     * 
     * @param string $startDate Date de début
     * @param string $endDate Date de fin
     * @param int|null $roomId ID de la salle (optionnel)
     * @return int Nombre de réservations
     */
    public function getBookingCount(string $startDate, string $endDate, ?int $roomId = null): int
    {
        $builder = $this->bookingModel
            ->where('booking_date >=', $startDate)
            ->where('booking_date <=', $endDate)
            ->whereIn('status', ['confirmed', 'pending']);

        if ($roomId !== null) {
            $builder->where('room_id', $roomId);
        }

        return $builder->countAllResults();
    }

    /**
     * Récupère les créneaux occupés pour une salle et une date
     * (Utile pour l'affichage dans le calendrier)
     * 
     * @param int $roomId ID de la salle
     * @param string $date Date
     * @return array Liste des créneaux occupés
     */
    public function getOccupiedSlots(int $roomId, string $date): array
    {
        return $this->bookingModel
            ->select('id, start_time, end_time, customer_name, status')
            ->where('room_id', $roomId)
            ->where('booking_date', $date)
            ->whereIn('status', ['confirmed', 'pending'])
            ->orderBy('start_time', 'ASC')
            ->findAll();
    }
}
