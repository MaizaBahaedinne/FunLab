<?php

namespace App\Controllers\Front;

use App\Controllers\BaseController;

class AccountController extends BaseController
{
    public function index()
    {
        return view('front/account/index');
    }

    public function bookings()
    {
        return view('front/account/bookings');
    }

    public function profile()
    {
        return view('front/account/profile');
    }

    public function update()
    {
        // Mise à jour du profil
    }
}
