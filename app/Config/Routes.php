<?php namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php'))
{
	require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);

/**
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');

// API Endpoint untuk cek slot waktu yang tersedia
$routes->get('get-available-slot', 'BookingController::getAvailableSlot');

// Auth Routes
$routes->group('auth', function ($routes) {
    $routes->get('/', 'Auth::index');
    $routes->post('login', 'Auth::login');
    $routes->get('logout', 'Auth::logout');
    
    // Register Routes
    $routes->get('register', 'Auth::registerForm');
    $routes->post('register', 'Auth::register');
    $routes->post('verify-register-otp', 'Auth::verifyRegisterOTP');
    
    // Forgot Password Routes
    $routes->get('forgot-password', 'Auth::forgotPassword');
    $routes->post('forgot-password', 'Auth::forgotPassword');
    $routes->post('verify-forgot-password-otp', 'Auth::verifyForgotPasswordOTP');
    $routes->post('reset-password', 'Auth::resetPassword');
    
    // Resend OTP
    $routes->post('resend-otp', 'Auth::resendOTP');
});

// Admin Routes - Filter diatur di Filters.php
$routes->group('admin', function ($routes) {
    $routes->get('/', 'Admin::index');
    $routes->get('users', 'Admin::users');
    $routes->get('profile', 'Admin::profile');
    $routes->get('settings', 'Admin::settings');
});

// Pasien Routes
$routes->group('pasien', function ($routes) {
    $routes->get('/', 'PasienController::index');
    $routes->get('datatables', 'PasienController::getDataTables');
    $routes->get('new', 'PasienController::new');
    $routes->post('/', 'PasienController::create');
    $routes->get('(:segment)', 'PasienController::show/$1');
    $routes->get('(:segment)/edit', 'PasienController::edit/$1');
    $routes->put('(:segment)', 'PasienController::update/$1');
    $routes->delete('(:segment)/delete', 'PasienController::delete/$1');
    $routes->post('(:segment)/create-user', 'PasienController::createUser/$1');
    $routes->post('(:segment)/update-password', 'PasienController::updatePassword/$1');
});

// Dokter Routes
$routes->group('dokter', function ($routes) {
    $routes->get('/', 'DokterController::index');
    $routes->get('datatables', 'DokterController::getDataTables');
    $routes->get('new', 'DokterController::new');
    $routes->post('/', 'DokterController::create');
    $routes->get('(:segment)', 'DokterController::show/$1');
    $routes->get('(:segment)/edit', 'DokterController::edit/$1');
    $routes->put('(:segment)', 'DokterController::update/$1');
    $routes->delete('(:segment)/delete', 'DokterController::delete/$1');
    $routes->post('(:segment)/create-user', 'DokterController::createUser/$1');
});

// Jadwal Routes
$routes->group('jadwal', function ($routes) {
    $routes->get('/', 'JadwalController::index');
    $routes->get('datatables', 'JadwalController::datatables');
    $routes->get('new', 'JadwalController::new');
    $routes->post('/', 'JadwalController::create');
    $routes->get('(:segment)', 'JadwalController::show/$1');
    $routes->get('(:segment)/edit', 'JadwalController::edit/$1');
    $routes->put('(:segment)', 'JadwalController::update/$1');
    $routes->delete('(:segment)/delete', 'JadwalController::delete/$1');
    $routes->post('(:segment)/toggleActive', 'JadwalController::toggleActive/$1');
});

// Obat Routes
$routes->group('obat', function ($routes) {
    $routes->get('/', 'ObatController::index');
    $routes->get('datatables', 'ObatController::getDataTables');
    $routes->get('new', 'ObatController::new');
    $routes->post('/', 'ObatController::create');
    $routes->get('(:segment)', 'ObatController::show/$1');
    $routes->get('(:segment)/edit', 'ObatController::edit/$1');
    $routes->put('(:segment)', 'ObatController::update/$1');
    $routes->delete('(:segment)/delete', 'ObatController::delete/$1');
});

// Jenis Perawatan Gigi Routes
$routes->group('jenis', function ($routes) {
    $routes->get('/', 'JenisController::index');
    $routes->get('datatables', 'JenisController::getDataTables');
    $routes->get('new', 'JenisController::new');
    $routes->post('/', 'JenisController::create');
    $routes->get('(:segment)', 'JenisController::show/$1');
    $routes->get('(:segment)/edit', 'JenisController::edit/$1');
    $routes->put('(:segment)', 'JenisController::update/$1');
    $routes->delete('(:segment)/delete', 'JenisController::delete/$1');
});

// Booking Routes
$routes->group('booking', function ($routes) {
    $routes->get('/', 'BookingController::index');
    $routes->get('datatables', 'BookingController::getDataTables');
    $routes->get('new', 'BookingController::new');
    $routes->post('/', 'BookingController::create');
    $routes->get('(:segment)', 'BookingController::show/$1');
    $routes->get('(:segment)/edit', 'BookingController::edit/$1');
    $routes->put('(:segment)', 'BookingController::update/$1');
    $routes->delete('(:segment)/delete', 'BookingController::delete/$1');
});

/**
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php'))
{
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
