<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// ---------------------------------------------------------------
// Halaman Publik (Civitas Akademika & Pengunjung)
// ---------------------------------------------------------------
$routes->get('/', 'Home::index');
$routes->get('about', 'Pages::about');
$routes->get('contact', 'Pages::contact');
$routes->post('contact', 'Pages::kirimPesan');
$routes->get('direktori', 'Direktori::index');
$routes->get('fasilitas/(:segment)', 'Direktori::detail/$1');
$routes->get('kategori/(:segment)', 'Kategori::detail/$1');

// ---------------------------------------------------------------
// Otentikasi Petugas
// ---------------------------------------------------------------
$routes->get('login', 'Auth::login', ['filter' => 'guest']);
$routes->post('login', 'Auth::attempt', ['filter' => 'guest']);
$routes->post('logout', 'Auth::logout');

// ---------------------------------------------------------------
// Panel Admin & Petugas Sarpras (Wajib Login)
// ---------------------------------------------------------------
$routes->group('admin', ['namespace' => 'App\Controllers\Admin', 'filter' => 'auth'], static function ($routes) {
    $routes->get('/', 'Dashboard::index');

    // Fasilitas: Staff & Admin boleh melihat, menambah, dan mengubah data
    $routes->get('fasilitas', 'Fasilitas::index');
    $routes->get('fasilitas/new', 'Fasilitas::new');
    $routes->post('fasilitas', 'Fasilitas::create');
    $routes->get('fasilitas/(:num)/edit', 'Fasilitas::edit/$1');
    $routes->put('fasilitas/(:num)', 'Fasilitas::update/$1');
    
    // Hapus Fasilitas hanya boleh dilakukan oleh Administrator
    $routes->delete('fasilitas/(:num)', 'Fasilitas::delete/$1', ['filter' => 'role:admin']);

    // Pesan & Aduan Masuk: Semua petugas dapat melihat & update status
    $routes->get('pesan', 'Pesan::index');
    $routes->post('pesan/(:num)/status', 'Pesan::updateStatus/$1');

    // Fitur Khusus Administrator: Kategori Fasilitas, Kelola Petugas, & Hapus Pesan
    $routes->group('', ['filter' => 'role:admin'], static function ($routes) {
        $routes->get('kategori', 'Kategori::index');
        $routes->get('kategori/new', 'Kategori::new');
        $routes->post('kategori', 'Kategori::create');
        $routes->get('kategori/(:num)/edit', 'Kategori::edit/$1');
        $routes->put('kategori/(:num)', 'Kategori::update/$1');
        $routes->delete('kategori/(:num)', 'Kategori::delete/$1');

        $routes->get('users', 'Users::index');
        $routes->get('users/new', 'Users::new');
        $routes->post('users', 'Users::create');
        $routes->get('users/(:num)/edit', 'Users::edit/$1');
        $routes->put('users/(:num)', 'Users::update/$1');
        $routes->delete('users/(:num)', 'Users::delete/$1');

        $routes->delete('pesan/(:num)', 'Pesan::delete/$1');
    });
});
