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
    
    // Vérification email
    $routes->get('verify-email', 'AuthController::verifyEmail');
    $routes->post('attempt-verify-email', 'AuthController::attemptVerifyEmail');
    $routes->post('resend-verification-code', 'AuthController::resendVerificationCode');
    
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
$routes->post('games/(:num)/review', 'Front\GamesController::submitReview/$1');
$routes->get('contact', 'Front\ContactController::index');
$routes->post('contact/send', 'Front\ContactController::send');
$routes->post('contact/subscribe', 'Front\ContactController::subscribe');
$routes->get('newsletter/unsubscribe', 'Front\ContactController::unsubscribe');

// Routes de test pour les bots sociaux
$routes->get('social-bot-test', 'Front\SocialBotTestController::index');
$routes->get('og-test', 'Front\SocialBotTestController::ogTest');

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
// Route de login admin (non protégée)
$routes->get('admin/login', 'AuthController::login');
$routes->post('admin/login', 'AuthController::attemptLogin');

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
    
    // Gestion des avis
    $routes->get('reviews', 'ReviewsController::index');
    $routes->get('reviews/approve/(:num)', 'ReviewsController::approve/$1');
    $routes->get('reviews/reject/(:num)', 'ReviewsController::reject/$1');
    $routes->get('reviews/delete/(:num)', 'ReviewsController::delete/$1');
    $routes->get('reviews/game/(:num)', 'ReviewsController::gameReviews/$1');
    
    // Gestion des réservations
    $routes->get('bookings', 'BookingsController::index');
    $routes->post('bookings/create', 'BookingsController::create');
    $routes->get('bookings/rooms', 'BookingsController::rooms'); // API pour filtres
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
    
    // Documentation Wiki
    $routes->get('wiki', 'WikiController::index');
    $routes->get('wiki/(:segment)', 'WikiController::page/$1');
    
    // Templates d'emails
    $routes->get('email-templates', 'EmailTemplatesController::index');
    $routes->get('email-templates/edit/(:num)', 'EmailTemplatesController::edit/$1');
    $routes->post('email-templates/update/(:num)', 'EmailTemplatesController::update/$1');
    $routes->get('email-templates/preview/(:num)', 'EmailTemplatesController::preview/$1');
    $routes->post('email-templates/test/(:num)', 'EmailTemplatesController::test/$1');
    
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
    
    // Contacts & Newsletter
    $routes->get('contacts', 'ContactController::index');
    $routes->get('contacts/view/(:num)', 'ContactController::view/$1');
    $routes->post('contacts/markReplied/(:num)', 'ContactController::markReplied/$1');
    $routes->delete('contacts/delete/(:num)', 'ContactController::delete/$1');
    
    $routes->get('newsletters', 'NewsletterController::index');
    $routes->get('newsletters/export', 'NewsletterController::export');
    $routes->delete('newsletters/delete/(:num)', 'NewsletterController::delete/$1');
    
    // Codes Promo
    $routes->get('promo-codes', 'PromoCodesController::index');
    $routes->get('promo-codes/create', 'PromoCodesController::create');
    $routes->post('promo-codes/store', 'PromoCodesController::store');
    $routes->get('promo-codes/edit/(:num)', 'PromoCodesController::edit/$1');
    $routes->post('promo-codes/update/(:num)', 'PromoCodesController::update/$1');
    $routes->post('promo-codes/delete/(:num)', 'PromoCodesController::delete/$1');
    $routes->post('promo-codes/toggle-status/(:num)', 'PromoCodesController::toggleStatus/$1');
    $routes->get('promo-codes/validate', 'PromoCodesController::validate');
    
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
    
    // Gestion du thème (comme WordPress)
    $routes->get('theme', 'ThemeController::index');
    $routes->get('theme/branding', 'ThemeController::branding');
    $routes->get('theme/colors', 'ThemeController::colors');
    $routes->get('theme/typography', 'ThemeController::typography');
    $routes->get('theme/header', 'ThemeController::header');
    $routes->get('theme/footer', 'ThemeController::footer');
    $routes->post('theme/save', 'ThemeController::save');
    
    // Gestion des pages (comme WordPress)
    $routes->get('pages', 'PageController::index');
    $routes->get('pages/create', 'PageController::create');
    $routes->get('pages/edit/(:num)', 'PageController::edit/$1');
    $routes->post('pages/save/(:num)', 'PageController::save/$1');
    $routes->post('pages/save', 'PageController::save');
    $routes->get('pages/delete/(:num)', 'PageController::delete/$1');
    
    // Gestion du slider homepage
    $routes->get('slides', 'SlideController::index');
    $routes->get('slides/create', 'SlideController::create');
    $routes->get('slides/edit/(:num)', 'SlideController::edit/$1');
    $routes->post('slides/save/(:num)', 'SlideController::save/$1');
    $routes->post('slides/save', 'SlideController::save');
    $routes->get('slides/delete/(:num)', 'SlideController::delete/$1');
    $routes->post('slides/updateOrder', 'SlideController::updateOrder');
    
    $routes->get('settings/users', 'SettingsController::users');
    $routes->get('settings/roles', 'SettingsController::roles');
    $routes->post('settings/save', 'SettingsController::save');
    $routes->post('settings/upload-image', 'SettingsController::uploadImage');
    $routes->post('settings/test-email', 'SettingsController::testEmail');
    $routes->post('settings/create-user', 'SettingsController::createUser');
    $routes->post('settings/update-user/(:num)', 'SettingsController::updateUser/$1');
    $routes->get('settings/delete-user/(:num)', 'SettingsController::deleteUser/$1');
    $routes->get('settings/impersonate/(:num)', 'SettingsController::impersonate/$1');
    $routes->get('settings/stop-impersonation', 'SettingsController::stopImpersonation');
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
