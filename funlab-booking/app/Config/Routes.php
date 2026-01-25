<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ============================================================
// ROUTES AUTHENTIFICATION
// ============================================================
$routes->group('auth', function($routes) {
    // Login/Register natif
    $routes->get('login', 'AuthController::login');
    $routes->post('login', 'AuthController::attemptLogin');
    $routes->get('register', 'AuthController::register');
    $routes->post('register', 'AuthController::attemptRegister');
    $routes->get('logout', 'AuthController::logout');
    
    // Mot de passe oublié
    $routes->get('forgot-password', 'AuthController::forgotPassword');
    $routes->post('forgot-password', 'AuthController::sendResetLink');
    $routes->get('reset-password/(:any)', 'AuthController::resetPassword/$1');
    $routes->post('reset-password', 'AuthController::updatePassword');
    
    // OAuth Google
    $routes->get('google', 'SocialAuthController::redirectToGoogle');
    $routes->get('google/callback', 'SocialAuthController::handleGoogleCallback');
    
    // OAuth Facebook
    $routes->get('facebook', 'SocialAuthController::redirectToFacebook');
    $routes->get('facebook/callback', 'SocialAuthController::handleFacebookCallback');
});

// Route de déconnexion directe (alias)
$routes->get('logout', 'AuthController::logout');
$routes->get('login', 'AuthController::login');
$routes->get('register', 'AuthController::register');

// ============================================================
// ROUTES FRONT-END
// ============================================================
$routes->get('/', 'Front\HomeController::index');
$routes->get('about', 'Front\AboutController::index');
$routes->get('games', 'Front\GamesController::index');
$routes->get('games/(:num)', 'Front\GamesController::view/$1');
$routes->get('contact', 'Front\ContactController::index');
$routes->post('contact/send', 'Front\ContactController::send');

// Réservation client
$routes->group('booking', ['namespace' => 'App\Controllers\Front'], function($routes) {
    $routes->get('/', 'BookingController::index');
    $routes->get('create', 'BookingController::create');
    $routes->post('store', 'BookingController::store');
    $routes->get('confirm/(:num)', 'BookingController::confirm/$1');
});

// Auto-inscription participants (PUBLIC - Sans authentification)
$routes->group('register', ['namespace' => 'App\Controllers\Front'], function($routes) {
    $routes->get('(:any)', 'RegistrationController::index/$1');
    $routes->post('(:any)/submit', 'RegistrationController::submit/$1');
    $routes->get('(:any)/participants', 'RegistrationController::participants/$1');
});

// Calendrier
$routes->get('calendar', 'Front\CalendarController::index');

// Compte client (PROTÉGÉ - Nécessite authentification)
$routes->group('account', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'AccountController::index');
    $routes->get('profile', 'AccountController::profile');
    $routes->post('profile', 'AccountController::updateProfile');
    $routes->get('bookings', 'AccountController::bookings');
    $routes->get('bookings/(:num)', 'AccountController::bookingDetails/$1');
    $routes->get('password', 'AccountController::changePassword');
    $routes->post('password', 'AccountController::updatePassword');
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
    
    // Gestion des catégories de jeux
    $routes->get('game-categories', 'GameCategoriesController::index');
    $routes->get('game-categories/create', 'GameCategoriesController::create');
    $routes->post('game-categories/store', 'GameCategoriesController::store');
    $routes->get('game-categories/edit/(:num)', 'GameCategoriesController::edit/$1');
    $routes->post('game-categories/update/(:num)', 'GameCategoriesController::update/$1');
    $routes->get('game-categories/delete/(:num)', 'GameCategoriesController::delete/$1');
    
    // Gestion des réservations
    $routes->get('bookings', 'BookingsController::index');
    $routes->get('bookings/view/(:num)', 'BookingsController::view/$1');
    $routes->get('bookings/edit/(:num)', 'BookingsController::edit/$1');
    $routes->post('bookings/update/(:num)', 'BookingsController::update/$1');
    $routes->post('bookings/update-status/(:num)', 'BookingsController::updateStatus/$1');
    $routes->post('bookings/update-payment/(:num)', 'BookingsController::updatePayment/$1');
    $routes->post('bookings/add-participant/(:num)', 'BookingsController::addParticipant/$1');
    $routes->get('bookings/delete-participant/(:num)', 'BookingsController::deleteParticipant/$1');
    $routes->get('bookings/cancel/(:num)', 'BookingsController::cancel/$1');
    $routes->get('bookings/delete/(:num)', 'BookingsController::delete/$1');
    
    // Gestion des équipes
    $routes->get('teams/manage/(:num)', 'TeamsController::manage/$1'); // Booking ID
    $routes->post('teams/create', 'TeamsController::create');
    $routes->post('teams/update/(:num)', 'TeamsController::update/$1');
    $routes->post('teams/delete/(:num)', 'TeamsController::delete/$1');
    $routes->post('teams/assign-participant', 'TeamsController::assignParticipant');
    $routes->post('teams/reorder', 'TeamsController::reorder');
    
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
    $routes->post('scanner/validate', 'ScannerController::validateTicket');
    
    // Paramètres et configuration
    $routes->get('settings', 'SettingsController::index');
    $routes->get('settings/general', 'SettingsController::general');
    $routes->get('settings/hours', 'SettingsController::hours');
    $routes->get('settings/mail', 'SettingsController::mail');
    $routes->get('settings/sms', 'SettingsController::sms');
    $routes->get('settings/seo', 'SettingsController::seo');
    $routes->get('settings/footer', 'SettingsController::footer');
    $routes->post('settings/footer', 'SettingsController::footer');
    $routes->get('settings/about', 'SettingsController::about');
    $routes->post('settings/about', 'SettingsController::about');
    $routes->get('settings/contact', 'SettingsController::contact');
    $routes->post('settings/contact', 'SettingsController::contact');
    $routes->get('settings/oauth', 'SettingsController::oauth');
    $routes->get('settings/users', 'SettingsController::users');
    $routes->get('settings/roles', 'SettingsController::roles');
    $routes->post('settings/save', 'SettingsController::save');
    $routes->post('settings/upload-image', 'SettingsController::uploadImage');
    $routes->post('settings/test-email', 'SettingsController::testEmail');
    $routes->post('settings/create-user', 'SettingsController::createUser');
    $routes->post('settings/update-user/(:num)', 'SettingsController::updateUser/$1');
    $routes->get('settings/delete-user/(:num)', 'SettingsController::deleteUser/$1');
    $routes->post('settings/update-role-permissions', 'SettingsController::updateRolePermissions');
});

// ============================================================
// API REST (AJAX)
// ============================================================
$routes->group('api', ['namespace' => 'App\Controllers\Api'], function($routes) {
    
    // API Disponibilités (CRITIQUE - Availability Engine)
    $routes->get('availability/slots', 'AvailabilityApi::slots');
    $routes->get('availability/all-slots', 'AvailabilityApi::allSlots'); // Tous les créneaux avec statut
    $routes->post('availability/check', 'AvailabilityApi::check');
    $routes->get('availability/rooms', 'AvailabilityApi::rooms');
    $routes->get('availability/closure', 'AvailabilityApi::closure');
    $routes->get('availability/occupied', 'AvailabilityApi::occupied');
    $routes->options('availability/(:any)', 'AvailabilityApi::options');
    
    // API Réservations (Phase 2 - BookingService)
    $routes->get('booking', 'BookingApi::index'); // Liste toutes les réservations
    $routes->post('booking/create', 'BookingApi::create');
    $routes->post('booking/cancel/(:num)', 'BookingApi::cancel/$1');
    $routes->post('booking/confirm/(:num)', 'BookingApi::confirm/$1');
    $routes->post('booking/complete/(:num)', 'BookingApi::complete/$1');
    $routes->get('booking/(:num)', 'BookingApi::show/$1');
    $routes->get('booking/customer', 'BookingApi::customer');
    $routes->options('booking/(:any)', 'BookingApi::options');
    
    // API Jeux
    $routes->get('games', 'GamesApi::index');
    $routes->options('games', 'GamesApi::options');
    
    // API Scanner
    $routes->post('scan/validate', 'ScanApi::validate');
    $routes->post('scan/checkin', 'ScanApi::checkIn');
    $routes->options('scan/(:any)', 'ScanApi::options');
    
    // API Paiements (Phase 7)
    $routes->post('payment/calculate', 'PaymentApi::calculate');
    $routes->post('payment/validate-promo', 'PaymentApi::validatePromo');
    $routes->post('payment/stripe/create', 'PaymentApi::createStripePayment');
    $routes->post('payment/stripe/webhook', 'PaymentApi::stripeWebhook');
    $routes->post('payment/onsite', 'PaymentApi::createOnsitePayment');
    $routes->post('payment/confirm/(:num)', 'PaymentApi::confirmPayment/$1');
    $routes->post('payment/refund/(:num)', 'PaymentApi::refund/$1');
    $routes->get('payment/history', 'PaymentApi::history');
    $routes->post('payment/invoice/generate', 'PaymentApi::generateInvoice');
    $routes->options('payment/(:any)', 'PaymentApi::options');
});
