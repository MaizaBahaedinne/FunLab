<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Services\BookingService;

class BookingApi extends ResourceController
{
    protected $format = 'json';
    protected $bookingService;

    public function __construct()
    {
        $this->bookingService = new BookingService();
    }

    public function create()
    {
        // Créer une réservation via API
        return $this->respond([
            'status' => 'success',
            'message' => 'Réservation créée'
        ]);
    }

    public function cancel($id)
    {
        // Annuler une réservation via API
        return $this->respond([
            'status' => 'success',
            'message' => 'Réservation annulée'
        ]);
    }

    public function get($id)
    {
        // Récupérer une réservation
        return $this->respond([
            'status' => 'success',
            'data' => []
        ]);
    }
}
