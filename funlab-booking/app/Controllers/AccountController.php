<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\BookingModel;

/**
 * Controller pour la gestion du compte utilisateur
 */
class AccountController extends BaseController
{
    protected $userModel;
    protected $bookingModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->bookingModel = new BookingModel();
    }

    /**
     * Page d'accueil du compte (tableau de bord)
     */
    public function index()
    {
        $userId = session()->get('userId');

        // Statistiques de l'utilisateur
        $stats = [
            'total_bookings' => $this->bookingModel->where('customer_id', $userId)->countAllResults(),
            'upcoming_bookings' => $this->bookingModel->where('customer_id', $userId)
                                                      ->where('booking_date >=', date('Y-m-d'))
                                                      ->whereIn('status', ['confirmed', 'pending'])
                                                      ->countAllResults(),
            'completed_bookings' => $this->bookingModel->where('customer_id', $userId)
                                                       ->where('status', 'completed')
                                                       ->countAllResults()
        ];

        // Réservations récentes (5 dernières)
        $recentBookings = $this->bookingModel->select('bookings.*, games.name as game_name')
                                             ->join('games', 'games.id = bookings.game_id')
                                             ->where('bookings.customer_id', $userId)
                                             ->orderBy('bookings.booking_date', 'DESC')
                                             ->limit(5)
                                             ->findAll();

        $data = [
            'stats' => $stats,
            'recent_bookings' => $recentBookings
        ];

        return view('account/index', $data);
    }

    /**
     * Page de profil utilisateur
     */
    public function profile()
    {
        $userId = session()->get('userId');
        $user = $this->userModel->find($userId);

        return view('account/profile', ['user' => $user]);
    }

    /**
     * Mise à jour du profil
     */
    public function updateProfile()
    {
        $userId = session()->get('userId');

        $rules = [
            'first_name' => 'required|min_length[2]',
            'last_name' => 'required|min_length[2]',
            'phone' => 'permit_empty|min_length[8]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'phone' => $this->request->getPost('phone')
        ];

        if ($this->userModel->update($userId, $data)) {
            // Mettre à jour la session
            session()->set([
                'firstName' => $data['first_name'],
                'lastName' => $data['last_name']
            ]);

            return redirect()->to('/account/profile')->with('success', 'Profil mis à jour avec succès');
        }

        return redirect()->back()->with('error', 'Erreur lors de la mise à jour');
    }

    /**
     * Page des réservations
     */
    public function bookings()
    {
        $userId = session()->get('userId');

        $bookings = $this->bookingModel->select('bookings.*, games.name as game_name, rooms.name as room_name')
                                       ->join('games', 'games.id = bookings.game_id')
                                       ->join('rooms', 'rooms.id = bookings.room_id')
                                       ->where('bookings.customer_id', $userId)
                                       ->orderBy('bookings.booking_date', 'DESC')
                                       ->findAll();

        return view('account/bookings', ['bookings' => $bookings]);
    }

    /**
     * Détails d'une réservation
     */
    public function bookingDetails($id)
    {
        $userId = session()->get('userId');

        $booking = $this->bookingModel->select('bookings.*, games.name as game_name, rooms.name as room_name, games.description')
                                      ->join('games', 'games.id = bookings.game_id')
                                      ->join('rooms', 'rooms.id = bookings.room_id')
                                      ->where('bookings.id', $id)
                                      ->where('bookings.customer_id', $userId)
                                      ->first();

        if (!$booking) {
            return redirect()->to('/account/bookings')->with('error', 'Réservation introuvable');
        }

        return view('account/booking_details', ['booking' => $booking]);
    }

    /**
     * Page de changement de mot de passe
     */
    public function changePassword()
    {
        return view('account/change_password');
    }

    /**
     * Mise à jour du mot de passe
     */
    public function updatePassword()
    {
        $userId = session()->get('userId');
        $user = $this->userModel->find($userId);

        // Si c'est un compte OAuth sans mot de passe
        if ($user['auth_provider'] !== 'native') {
            return redirect()->back()->with('error', 'Les comptes Google/Facebook ne peuvent pas changer de mot de passe');
        }

        $rules = [
            'current_password' => 'required',
            'new_password' => 'required|min_length[8]',
            'confirm_password' => 'required|matches[new_password]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('errors', $this->validator->getErrors());
        }

        // Vérifier le mot de passe actuel
        if (!password_verify($this->request->getPost('current_password'), $user['password'])) {
            return redirect()->back()->with('error', 'Le mot de passe actuel est incorrect');
        }

        // Mettre à jour le mot de passe
        $newPassword = password_hash($this->request->getPost('new_password'), PASSWORD_DEFAULT);
        
        if ($this->userModel->update($userId, ['password' => $newPassword])) {
            return redirect()->to('/account/password')->with('success', 'Mot de passe changé avec succès');
        }

        return redirect()->back()->with('error', 'Erreur lors du changement de mot de passe');
    }
}
