<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class DashboardController extends BaseController
{
    public function index()
    {
        return view('admin/dashboard/index');
    }

    public function stats()
    {
        // Statistiques du tableau de bord
    }
}
