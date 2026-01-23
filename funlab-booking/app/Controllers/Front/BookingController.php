<?php

namespace App\Controllers\Front;

use App\Controllers\BaseController;

class BookingController extends BaseController
{
    public function index()
    {
        return view('front/booking/index');
    }

    public function create()
    {
        return view('front/booking/create');
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
