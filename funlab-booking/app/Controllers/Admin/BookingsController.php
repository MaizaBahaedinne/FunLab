<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\BookingModel;
use App\Models\ParticipantModel;
use App\Models\PaymentModel;

class BookingsController extends BaseController
{
    protected $bookingModel;
    protected $participantModel;
    protected $paymentModel;

    public function __construct()
    {
        $this->bookingModel = new BookingModel();
        $this->participantModel = new ParticipantModel();
        $this->paymentModel = new PaymentModel();
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

        // Récupérer les participants
        $participants = $this->participantModel->where('booking_id', $id)->findAll();

        // Récupérer le paiement associé
        $payment = $this->paymentModel->where('booking_id', $id)->first();

        $data = [
            'booking' => $booking,
            'participants' => $participants,
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
                'transaction_date' => date('Y-m-d H:i:s')
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
}
