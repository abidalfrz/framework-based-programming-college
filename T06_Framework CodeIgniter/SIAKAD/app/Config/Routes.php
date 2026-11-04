<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// Beranda: diarahkan ke dashboard sesuai role
$routes->get('/', 'Home::index', ['filter' => 'auth']);

$routes->get('login', 'Auth::login', ['filter' => 'guest']);
$routes->post('login', 'Auth::attempt', ['filter' => 'guest']);
$routes->post('logout', 'Auth::logout');
$routes->get('password', 'Auth::password', ['filter' => 'auth']);
$routes->put('password', 'Auth::gantiPassword', ['filter' => 'auth']);

// ---------- Admin akademik ----------
$routes->group('admin', ['namespace' => 'App\Controllers\Admin', 'filter' => ['auth', 'role:admin']], static function ($routes) {
    $routes->get('/', 'Dashboard::index');

    foreach (['mahasiswa' => 'Mahasiswa', 'dosen' => 'Dosen', 'matakuliah' => 'Matakuliah'] as $uri => $ctrl) {
        $routes->get($uri, "{$ctrl}::index");
        $routes->get("{$uri}/new", "{$ctrl}::new");
        $routes->post($uri, "{$ctrl}::create");
        $routes->get("{$uri}/(:num)/edit", "{$ctrl}::edit/$1");
        $routes->put("{$uri}/(:num)", "{$ctrl}::update/$1");
        $routes->delete("{$uri}/(:num)", "{$ctrl}::delete/$1");
    }
    $routes->post('mahasiswa/(:num)/reset-password', 'Mahasiswa::resetPassword/$1');
    $routes->post('dosen/(:num)/reset-password', 'Dosen::resetPassword/$1');
});

// ---------- Dosen ----------
$routes->group('dosen', ['namespace' => 'App\Controllers\Dosen', 'filter' => ['auth', 'role:dosen']], static function ($routes) {
    $routes->get('/', 'Kelas::index');
    $routes->get('kelas/(:num)', 'Kelas::nilai/$1');
    $routes->put('kelas/(:num)', 'Kelas::simpanNilai/$1');
    $routes->get('perwalian', 'Perwalian::index');
    $routes->get('perwalian/(:num)', 'Perwalian::show/$1');
});

// ---------- Mahasiswa ----------
$routes->group('mahasiswa', ['namespace' => 'App\Controllers\Mahasiswa', 'filter' => ['auth', 'role:mahasiswa']], static function ($routes) {
    $routes->get('/', 'Dashboard::index');
    $routes->get('krs', 'Krs::index');
    $routes->post('krs', 'Krs::ambil');
    $routes->delete('krs/(:num)', 'Krs::batal/$1');
    $routes->get('krs/cetak', 'Krs::cetak');
    $routes->get('khs', 'Khs::index');
    $routes->get('transkrip', 'Transkrip::index');
    $routes->get('transkrip/cetak', 'Transkrip::cetak');
});
