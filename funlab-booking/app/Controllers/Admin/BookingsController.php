<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\BookingModel;

class BookingsController extends BaseController
{
    protected $bookingModel;

    public function __construct()
    {
        $this->bookingModel = new BookingModel();
    }

    public function index()
    {
        $data['bookings'] = $this->bookingModel->findAll();
        return view('admin/bookings/index', $data);
    }

    public function view($id)
    {
        $data['booking'] = $this->bookingModel->find($id);
        return view('admin/bookings/view', $data);
    }

    public function edit($id)
    {
        $data['booking'] = $this->bookingModel->find($id);
        return view('admin/bookings/edit', $data);
    }

    public function update($id)
    {
        // Mise à jour d'une réservation
    }

    public function cancel($id)
    {
        // Annulation d'une réservation
    }

    public function delete($id)
    {
        // Suppression d'une réservation
    }
}
