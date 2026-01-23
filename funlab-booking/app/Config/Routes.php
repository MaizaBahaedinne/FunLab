<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ============================================================
// ROUTES FRONT-END
// ============================================================
$routes->get('/', 'Front\HomeController::index');

// Réservation client
$routes->group('booking', ['namespace' => 'App\Controllers\Front'], function($routes) {
    $routes->get('/', 'BookingController::index');
    $routes->get('create', 'BookingController::create');
    $routes->post('store', 'BookingController::store');
    $routes->get('confirm/(:num)', 'BookingController::confirm/$1');
});

// Calendrier
$routes->get('calendar', 'Front\CalendarController::index');

// Compte client
$routes->group('account', ['namespace' => 'App\Controllers\Front'], function($routes) {
    $routes->get('/', 'AccountController::index');
    $routes->get('bookings', 'AccountController::bookings');
    $routes->get('profile', 'AccountController::profile');
    $routes->post('update', 'AccountController::update');
});

// ============================================================
// ROUTES ADMINISTRATION
// ============================================================
$routes->group('admin', ['namespace' => 'App\Controllers\Admin', 'filter' => 'adminAuth'], function($routes) {
    
    // Dashboard
    $routes->get('/', 'DashboardController::index');
    $routes->get('dashboard', 'DashboardController::index');
    $routes->get('dashboard/stats', 'DashboardController::stats');
    
    // Gestion des salles
    $routes->get('rooms', 'RoomsController::index');
    $routes->get('rooms/create', 'RoomsController::create');
    $routes->post('rooms/store', 'RoomsController::store');
    $routes->get('rooms/edit/(:num)', 'RoomsController::edit/$1');
    $routes->post('rooms/update/(:num)', 'RoomsController::update/$1');
    $routes->post('rooms/delete/(:num)', 'RoomsController::delete/$1');
    
    // Gestion des jeux
    $routes->get('games', 'GamesController::index');
    $routes->get('games/create', 'GamesController::create');
    $routes->post('games/store', 'GamesController::store');
    $routes->get('games/edit/(:num)', 'GamesController::edit/$1');
    $routes->post('games/update/(:num)', 'GamesController::update/$1');
    $routes->post('games/delete/(:num)', 'GamesController::delete/$1');
    
    // Gestion des réservations
    $routes->get('bookings', 'BookingsController::index');
    $routes->get('bookings/view/(:num)', 'BookingsController::view/$1');
    $routes->get('bookings/edit/(:num)', 'BookingsController::edit/$1');
    $routes->post('bookings/update/(:num)', 'BookingsController::update/$1');
    $routes->post('bookings/cancel/(:num)', 'BookingsController::cancel/$1');
    $routes->post('bookings/delete/(:num)', 'BookingsController::delete/$1');
    
    // Gestion des participants
    $routes->get('participants', 'ParticipantsController::index');
    $routes->get('participants/view/(:num)', 'ParticipantsController::view/$1');
    $routes->get('participants/edit/(:num)', 'ParticipantsController::edit/$1');
    $routes->post('participants/update/(:num)', 'ParticipantsController::update/$1');
    
    // Gestion des fermetures
    $routes->get('closures', 'ClosuresController::index');
    $routes->get('closures/create', 'ClosuresController::create');
    $routes->post('closures/store', 'ClosuresController::store');
    $routes->get('closures/edit/(:num)', 'ClosuresController::edit/$1');
    $routes->post('closures/update/(:num)', 'ClosuresController::update/$1');
    $routes->post('closures/delete/(:num)', 'ClosuresController::delete/$1');
    
    // Scanner QR Code
    $routes->get('scanner', 'ScannerController::index');
    $routes->post('scanner/scan', 'ScannerController::scan');
    $routes->post('scanner/validate', 'ScannerController::validate');
});

// ============================================================
// API REST (AJAX)
// ============================================================
$routes->group('api', ['namespace' => 'App\Controllers\Api'], function($routes) {
    
    // API Disponibilités (CRITIQUE - Availability Engine)
    $routes->get('availability/slots', 'AvailabilityApi::slots');
    $routes->post('availability/check', 'AvailabilityApi::check');
    $routes->get('availability/rooms', 'AvailabilityApi::rooms');
    $routes->get('availability/closure', 'AvailabilityApi::closure');
    $routes->get('availability/occupied', 'AvailabilityApi::occupied');
    $routes->options('availability/(:any)', 'AvailabilityApi::options');
    
    // API Réservations (Phase 2 - BookingService)
    $routes->post('booking/create', 'BookingApi::create');
    $routes->post('booking/cancel/(:num)', 'BookingApi::cancel/$1');
    $routes->post('booking/confirm/(:num)', 'BookingApi::confirm/$1');
    $routes->post('booking/complete/(:num)', 'BookingApi::complete/$1');
    $routes->get('booking/(:num)', 'BookingApi::get/$1');
    $routes->get('booking/customer', 'BookingApi::customer');
    $routes->options('booking/(:any)', 'BookingApi::options');
    
    // API Scanner
    $routes->post('scan/validate', 'ScanApi::validate');
    $routes->post('scan/checkin', 'ScanApi::checkIn');
    $routes->options('scan/(:any)', 'ScanApi::options');
});
