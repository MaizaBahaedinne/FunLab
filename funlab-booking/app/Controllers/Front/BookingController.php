<?php

namespace App\Controllers\Front;

use App\Controllers\BaseController;

class BookingController extends BaseController
{
    public function index()
    {
        $data = [];
        
        // Vérifier si l'utilisateur est connecté
        if (session()->has('user_id')) {
            $userModel = model('UserModel');
            $user = $userModel->find(session()->get('user_id'));
            
            if ($user) {
                $data['user'] = [
                    'id' => $user['id'],
                    'name' => trim($user['first_name'] . ' ' . $user['last_name']),
                    'email' => $user['email'],
                    'phone' => $user['phone'] ?? ''
                ];
            }
        }
        
        return view('front/booking/create', $data);
    }

    public function create()
    {
        return $this->index();
    }

    public function store()
    {
        // Traitement de la réservation
    }

    public function confirm($bookingId)
    {
        return view('front/booking/confirm');
    }
}
