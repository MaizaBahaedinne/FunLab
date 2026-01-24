<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class ScannerController extends BaseController
{
    public function index()
    {
        return view('admin/scanner/index');
    }

    public function scan()
    {
        // Scanner un QR code
    }

    public function validateTicket()
    {
        // Valider un ticket
    }
}
