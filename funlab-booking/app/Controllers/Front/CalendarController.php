<?php

namespace App\Controllers\Front;

use App\Controllers\BaseController;

class CalendarController extends BaseController
{
    public function index()
    {
        return view('front/calendar/index');
    }

    public function getAvailability()
    {
        // Récupération des disponibilités
    }
}
