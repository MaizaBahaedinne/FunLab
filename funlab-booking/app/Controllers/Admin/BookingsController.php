<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\BookingModel;
use App\Models\ParticipantModel;
use App\Models\PaymentModel;
use App\Models\GameModel;
use App\Models\RoomModel;
use App\Models\UserModel;

class BookingsController extends BaseController
{
    protected $bookingModel;
    protected $participantModel;
    protected $paymentModel;
    protected $gameModel;
    protected $roomModel;
    protected $userModel;

    public function __construct()
    {
        $this->bookingModel = new BookingModel();
        $this->participantModel = new ParticipantModel();
        $this->paymentModel = new PaymentModel();
        $this->gameModel = new GameModel();
        $this->roomModel = new RoomModel();
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $data['bookings'] = $this->bookingModel->findAll();
        return view('admin/bookings/index', $data);
    }

    /**
     * Afficher les détails complets d'une réservation
     */
    public function view($id)
    {
        // Récupérer la réservation avec tous les détails
        $booking = $this->bookingModel
            ->select('bookings.*, games.name as game_name, games.price, rooms.name as room_name, users.first_name, users.last_name, users.email as user_email')
            ->join('games', 'games.id = bookings.game_id')
            ->join('rooms', 'rooms.id = bookings.room_id')
            ->join('users', 'users.id = bookings.user_id', 'left')
            ->find($id);

        if (!$booking) {
            return redirect()->to('/admin/bookings')->with('error', 'Réservation introuvable');
        }

        // Récupérer les équipes avec leurs participants
        $teamModel = new \App\Models\TeamModel();
        $teams = $teamModel->getTeamsWithParticipants($id);

        // Récupérer les participants non assignés (sans équipe)
        $unassignedParticipants = $this->participantModel
            ->where('booking_id', $id)
            ->where('team_id', null)
            ->findAll();

        // Récupérer le paiement associé
        $payment = $this->paymentModel->where('booking_id', $id)->first();

        $data = [
            'booking' => $booking,
            'teams' => $teams,
            'unassignedParticipants' => $unassignedParticipants,
            'payment' => $payment
        ];

        return view('admin/bookings/view', $data);
    }

    /**
     * Mettre à jour le statut d'une réservation
     */
    public function updateStatus($id)
    {
        $status = $this->request->getPost('status');
        $allowedStatuses = ['pending', 'confirmed', 'in_progress', 'completed', 'cancelled'];

        if (!in_array($status, $allowedStatuses)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Statut invalide'
            ]);
        }

        $this->bookingModel->update($id, ['status' => $status]);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Statut mis à jour'
        ]);
    }

    /**
     * Mettre à jour les informations de paiement
     */
    public function updatePayment($id)
    {
        $paymentStatus = $this->request->getPost('payment_status');
        $paymentMethod = $this->request->getPost('payment_method');

        $booking = $this->bookingModel->find($id);
        if (!$booking) {
            return redirect()->back()->with('error', 'Réservation introuvable');
        }

        // Mettre à jour le payment_method dans bookings
        if ($paymentMethod) {
            $this->bookingModel->update($id, ['payment_method' => $paymentMethod]);
        }

        // Mettre à jour ou créer l'entrée de paiement
        $payment = $this->paymentModel->where('booking_id', $id)->first();

        if ($payment) {
            // Mise à jour
            $this->paymentModel->update($payment['id'], [
                'status' => $paymentStatus,
                'payment_method' => $paymentMethod
            ]);
        } else {
            // Création
            $this->paymentModel->insert([
                'booking_id' => $id,
                'customer_id' => $booking['user_id'],
                'amount' => $booking['total_price'],
                'currency' => 'TND',
                'payment_method' => $paymentMethod,
                'status' => $paymentStatus,
                'paid_at' => date('Y-m-d H:i:s')
            ]);
        }

        return redirect()->back()->with('success', 'Paiement mis à jour');
    }

    /**
     * Ajouter un participant à une réservation
     */
    public function addParticipant($id)
    {
        $firstName = $this->request->getPost('first_name');
        $lastName = $this->request->getPost('last_name');
        $email = $this->request->getPost('email');
        $phone = $this->request->getPost('phone');

        if (!$firstName || !$lastName) {
            return redirect()->back()->with('error', 'Nom et prénom requis');
        }

        $this->participantModel->insert([
            'booking_id' => $id,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
            'phone' => $phone,
            'attendance_status' => 'registered'
        ]);

        return redirect()->back()->with('success', 'Participant ajouté');
    }

    /**
     * Supprimer un participant
     */
    public function deleteParticipant($participantId)
    {
        $this->participantModel->delete($participantId);
        return redirect()->back()->with('success', 'Participant supprimé');
    }

    /**
     * Annuler une réservation
     */
    public function cancel($id)
    {
        $this->bookingModel->update($id, [
            'status' => 'cancelled',
            'cancellation_reason' => 'Annulé par l\'administrateur',
            'cancelled_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->back()->with('success', 'Réservation annulée');
    }

    /**
     * Supprimer une réservation
     */
    public function delete($id)
    {
        $this->bookingModel->delete($id);
        return redirect()->to('/admin/bookings')->with('success', 'Réservation supprimée');
    }

    /**
     * Créer une nouvelle réservation depuis l'admin
     */
    public function create()
    {
        helper('permission');
        
        // Vérifier la permission de créer des réservations
        if ($redirect = checkPermissionOrRedirect('bookings', 'create')) {
            return $redirect;
        }

        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'game_id' => 'required|integer',
            'room_id' => 'required|integer',
            'booking_date' => 'required|valid_date',
            'start_time' => 'required',
            'num_participants' => 'required|integer|greater_than[0]',
            'customer_first_name' => 'required',
            'customer_last_name' => 'required',
            'customer_email' => 'required|valid_email',
            'customer_phone' => 'required',
            'total_price' => 'required|decimal'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('error', implode('<br>', $validation->getErrors()));
        }

        $gameId = $this->request->getPost('game_id');
        $roomId = $this->request->getPost('room_id');
        $bookingDate = $this->request->getPost('booking_date');
        $startTime = $this->request->getPost('start_time');
        $numParticipants = $this->request->getPost('num_participants');
        $status = $this->request->getPost('status') ?? 'confirmed';

        // Récupérer les infos du jeu pour calculer l'heure de fin
        $game = $this->gameModel->find($gameId);
        if (!$game) {
            return redirect()->back()->with('error', 'Jeu introuvable');
        }

        // Calculer l'heure de fin (durée du jeu en minutes)
        $duration = $game['duration'] ?? 60;
        $endTime = date('H:i:s', strtotime($startTime) + ($duration * 60));

        // Vérifier la disponibilité de la salle
        $existingBookings = $this->bookingModel
            ->where('room_id', $roomId)
            ->where('booking_date', $bookingDate)
            ->where('status !=', 'cancelled')
            ->where("((start_time <= '$startTime' AND end_time > '$startTime') OR (start_time < '$endTime' AND end_time >= '$endTime') OR (start_time >= '$startTime' AND end_time <= '$endTime'))")
            ->findAll();

        if (!empty($existingBookings)) {
            return redirect()->back()->withInput()->with('error', 'Cette salle est déjà réservée sur ce créneau horaire');
        }

        // Chercher ou créer l'utilisateur
        $customerEmail = $this->request->getPost('customer_email');
        $user = $this->userModel->where('email', $customerEmail)->first();
        
        if (!$user) {
            // Créer un nouvel utilisateur
            $firstName = $this->request->getPost('customer_first_name');
            $lastName = $this->request->getPost('customer_last_name');
            
            $inserted = $this->userModel->insert([
                'username' => strtolower($firstName . '_' . $lastName . '_' . substr(uniqid(), -4)),
                'email' => $customerEmail,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'phone' => $this->request->getPost('customer_phone'),
                'password' => password_hash(bin2hex(random_bytes(16)), PASSWORD_DEFAULT), // Mot de passe aléatoire
                'role' => 'user',
                'email_verified' => 1
            ]);
            
            if (!$inserted) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Erreur lors de la création de l\'utilisateur'
                ]);
            }
            
            $userId = $this->userModel->getInsertID();
        } else {
            $userId = $user['id'];
        }

        // Générer un code de réservation unique
        $bookingCode = 'BK-' . strtoupper(substr(uniqid(), -8));
        
        // Construire le nom complet du client
        $customerName = $this->request->getPost('customer_first_name') . ' ' . $this->request->getPost('customer_last_name');

        // Créer la réservation
        $bookingData = [
            'user_id' => $userId,
            'game_id' => $gameId,
            'room_id' => $roomId,
            'booking_date' => $bookingDate,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'num_players' => $numParticipants,
            'total_price' => $this->request->getPost('total_price'),
            'status' => $status,
            'customer_name' => $customerName,
            'customer_email' => $customerEmail,
            'customer_phone' => $this->request->getPost('customer_phone'),
            'booking_code' => $bookingCode,
            'qr_code' => $bookingCode,
            'notes' => $this->request->getPost('notes') ?? null,
            'created_by' => 'admin'
        ];

        $bookingId = $this->bookingModel->insert($bookingData);

        if ($bookingId) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Réservation créée avec succès',
                'booking_id' => $bookingId
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Erreur lors de la création de la réservation'
        ]);
    }

    /**
     * API : Récupérer toutes les salles (pour les filtres)
     */
    public function rooms()
    {
        try {
            $rooms = $this->roomModel
                ->where('status', 'active')
                ->orderBy('name', 'ASC')
                ->findAll();

            return $this->response->setJSON([
                'success' => true,
                'data' => $rooms
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erreur lors du chargement des salles'
            ])->setStatusCode(500);
        }
    }
}
