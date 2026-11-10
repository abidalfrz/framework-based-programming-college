<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'HomeController::index');
$routes->get('about', 'HomeController::about');
$routes->get('contact', 'HomeController::contact');
$routes->get('photo-credits', 'HomeController::credits');

$routes->get('login', 'AuthController::login');
$routes->post('login', 'AuthController::attempt');
$routes->get('register', 'AuthController::register');
$routes->post('register', 'AuthController::create');
$routes->post('logout', 'AuthController::logout', ['filter' => 'auth']);
$routes->get('catalog', 'ProductController::index');
$routes->get('product/(:segment)', 'ProductController::detail/$1');
$routes->group('cart', ['filter' => ['auth', 'role:customer']], static function ($routes) {
    $routes->get('/', 'CartController::index');
    $routes->post('items', 'CartController::add');
    $routes->post('items/(:num)/update', 'CartController::update/$1');
    $routes->post('items/(:num)/delete', 'CartController::remove/$1');
});
$routes->group('account/addresses', ['filter' => ['auth', 'role:customer']], static function ($routes) {
    $routes->get('/', 'AddressController::index');
    $routes->post('/', 'AddressController::create');
    $routes->post('(:num)/delete', 'AddressController::delete/$1');
    $routes->post('(:num)/update', 'AddressController::update/$1');
    $routes->post('(:num)/default', 'AddressController::setDefault/$1');
});
$routes->group('checkout', ['filter' => ['auth', 'role:customer']], static function ($routes) {
    $routes->get('/', 'CheckoutController::index');
    $routes->post('/', 'CheckoutController::place');
});
$routes->group('orders', ['filter' => ['auth', 'role:customer']], static function ($routes) {
    $routes->get('/', 'OrderController::index');
    $routes->get('(:num)', 'OrderController::show/$1');
    $routes->post('(:num)/payment', 'PaymentController::upload/$1');
});

$routes->get('orders/(:num)/payment/proof', 'PaymentController::proof/$1', ['filter' => 'auth']);
$routes->get('admin', 'Admin\DashboardController::index', ['filter' => ['auth', 'role:admin']]);
$routes->group('admin', ['namespace' => 'App\Controllers\Admin', 'filter' => ['auth', 'role:admin']], static function ($routes) {
    $routes->get('categories', 'CategoryController::index');
    $routes->post('categories', 'CategoryController::save');
    $routes->post('categories/(:num)', 'CategoryController::save/$1');
    $routes->post('categories/(:num)/toggle', 'CategoryController::toggle/$1');
    $routes->get('products', 'ProductController::index');
    $routes->post('products', 'ProductController::save');
    $routes->post('products/(:num)', 'ProductController::save/$1');
    $routes->post('products/(:num)/toggle', 'ProductController::toggle/$1');
});
$routes->group('admin/settings', ['namespace' => 'App\Controllers\Admin', 'filter' => ['auth', 'role:admin']], static function ($routes) {
    $routes->get('/', 'SettingsController::index');
    $routes->post('/', 'SettingsController::save');
});
$routes->group('admin/orders', ['namespace' => 'App\Controllers\Admin', 'filter' => ['auth', 'role:admin']], static function ($routes) {
    $routes->get('/', 'OrderController::index');
    $routes->get('(:num)', 'OrderController::show/$1');
    $routes->post('(:num)/verify', 'OrderController::verify/$1');
    $routes->post('(:num)/cancel', 'OrderController::cancel/$1');
    $routes->post('(:num)/ship', 'OrderController::ship/$1');
    $routes->post('(:num)/complete', 'OrderController::complete/$1');
});
$routes->group('operator/orders', ['namespace' => 'App\Controllers\Admin', 'filter' => ['auth', 'role:operator']], static function ($routes) {
    $routes->get('/', 'OrderController::index');
    $routes->get('(:num)', 'OrderController::show/$1');
    $routes->post('(:num)/ship', 'OrderController::ship/$1');
    $routes->post('(:num)/complete', 'OrderController::complete/$1');
});
$routes->group('admin', ['namespace' => 'App\Controllers\Admin', 'filter' => ['auth', 'role:admin']], static function ($routes) {
    $routes->get('reports', 'ReportController::index');
    $routes->get('customers', 'CustomerController::index');
});
$routes->get('account', 'AccountController::index', ['filter' => 'auth']);
$routes->post('account/password', 'AccountController::password', ['filter' => 'auth']);
