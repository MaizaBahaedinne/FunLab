<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Services\QRCodeService;

class ScanApi extends ResourceController
{
    protected $format = 'json';
    protected $qrCodeService;

    public function __construct()
    {
        $this->qrCodeService = new QRCodeService();
    }

    public function validate()
    {
        // Valider un QR code
        return $this->respond([
            'status' => 'success',
            'valid' => true
        ]);
    }

    public function checkIn()
    {
        // Enregistrer l'arrivée d'un participant
        return $this->respond([
            'status' => 'success',
            'message' => 'Check-in réussi'
        ]);
    }
}
