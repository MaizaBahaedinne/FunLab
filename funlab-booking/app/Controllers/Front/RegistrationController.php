<?php

namespace App\Controllers\Front;

use App\Controllers\BaseController;
use App\Models\BookingModel;
use App\Models\ParticipantModel;

class RegistrationController extends BaseController
{
    protected $bookingModel;
    protected $participantModel;

    public function __construct()
    {
        $this->bookingModel = new BookingModel();
        $this->participantModel = new ParticipantModel();
    }

    /**
     * Afficher le formulaire d'inscription pour une réservation
     */
    public function index($token)
    {
        // Récupérer la réservation par token
        $booking = $this->bookingModel
            ->select('bookings.*, games.name as game_name, games.description, rooms.name as room_name')
            ->join('games', 'games.id = bookings.game_id')
            ->join('rooms', 'rooms.id = bookings.room_id')
            ->where('bookings.registration_token', $token)
            ->first();

        if (!$booking) {
            return view('front/registration/invalid', [
                'message' => 'Lien d\'inscription invalide ou expiré'
            ]);
        }

        // Vérifier si la réservation est encore valide (pas encore terminée)
        $bookingDateTime = strtotime($booking['booking_date'] . ' ' . $booking['end_time']);
        $now = time();

        if ($bookingDateTime < $now) {
            return view('front/registration/expired', [
                'booking' => $booking,
                'message' => 'Cette session est terminée. L\'inscription n\'est plus possible.'
            ]);
        }

        // Vérifier si la réservation est annulée
        if ($booking['status'] === 'cancelled') {
            return view('front/registration/invalid', [
                'message' => 'Cette réservation a été annulée'
            ]);
        }

        // Récupérer les participants déjà inscrits
        $participants = $this->participantModel
            ->where('booking_id', $booking['id'])
            ->findAll();

        $data = [
            'booking' => $booking,
            'token' => $token,
            'participants' => $participants,
            'participantsCount' => count($participants)
        ];

        return view('front/registration/form', $data);
    }

    /**
     * Traiter l'inscription d'un participant
     */
    public function submit($token)
    {
        // Récupérer la réservation
        $booking = $this->bookingModel->where('registration_token', $token)->first();

        if (!$booking) {
            return $this->response
                ->setStatusCode(404)
                ->setContentType('application/json')
                ->setJSON([
                    'status' => 'error',
                    'message' => 'Lien invalide'
                ]);
        }

        // Vérifier si la réservation est encore valide
        $bookingDateTime = strtotime($booking['booking_date'] . ' ' . $booking['end_time']);
        if ($bookingDateTime < time()) {
            return $this->response
                ->setContentType('application/json')
                ->setJSON([
                    'status' => 'error',
                    'message' => 'La session est terminée'
                ]);
        }

        // Valider les données
        $firstName = trim($this->request->getPost('first_name'));
        $lastName = trim($this->request->getPost('last_name'));
        $email = trim($this->request->getPost('email'));
        $phone = trim($this->request->getPost('phone'));

        if (empty($firstName) || empty($lastName)) {
            return $this->response
                ->setContentType('application/json')
                ->setJSON([
                    'status' => 'error',
                    'message' => 'Le prénom et le nom sont requis'
                ]);
        }

        // Vérifier le nombre de participants
        $currentCount = $this->participantModel->where('booking_id', $booking['id'])->countAllResults();
        if ($currentCount >= $booking['num_players']) {
            return $this->response
                ->setContentType('application/json')
                ->setJSON([
                    'status' => 'error',
                    'message' => 'Le nombre maximum de participants est atteint'
                ]);
        }

        // Insérer le participant
        $participantId = $this->participantModel->insert([
            'booking_id' => $booking['id'],
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email ?: null,
            'phone' => $phone ?: null,
            'attendance_status' => 'registered'
        ]);

        if (!$participantId) {
            return $this->response
                ->setContentType('application/json')
                ->setJSON([
                    'status' => 'error',
                    'message' => 'Erreur lors de l\'inscription'
                ]);
        }

        return $this->response
            ->setContentType('application/json')
            ->setJSON([
                'status' => 'success',
                'message' => 'Inscription réussie !',
                'participant_id' => $participantId
            ]);
    }

    /**
     * Récupérer la liste des participants (pour mise à jour en temps réel)
     */
    public function participants($token)
    {
        $booking = $this->bookingModel->where('registration_token', $token)->first();

        if (!$booking) {
            return $this->response
                ->setContentType('application/json')
                ->setJSON([
                    'status' => 'error',
                    'participants' => []
                ]);
        }

        $participants = $this->participantModel
            ->where('booking_id', $booking['id'])
            ->findAll();

        return $this->response
            ->setContentType('application/json')
            ->setJSON([
                'status' => 'success',
                'participants' => $participants,
                'total' => count($participants),
                'max' => $booking['num_players']
            ]);
    }
}
