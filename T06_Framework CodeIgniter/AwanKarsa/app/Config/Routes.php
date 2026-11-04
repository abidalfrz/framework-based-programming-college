<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// ---------- Toko (publik) ----------
$routes->get('/', 'Toko::index');
$routes->get('katalog', 'Toko::katalog');
$routes->get('produk/(:segment)', 'Toko::produk/$1');

// Keranjang berbasis session, boleh dipakai sebelum login
$routes->get('keranjang', 'Keranjang::index');
$routes->post('keranjang', 'Keranjang::tambah');
$routes->put('keranjang/(:num)', 'Keranjang::ubah/$1');
$routes->delete('keranjang/(:num)', 'Keranjang::hapus/$1');

// ---------- Akun ----------
$routes->group('', ['filter' => 'guest'], static function ($routes) {
    $routes->get('login', 'Auth::login');
    $routes->post('login', 'Auth::attempt');
    $routes->get('daftar', 'Auth::daftar');
    $routes->post('daftar', 'Auth::simpan');
});
$routes->post('logout', 'Auth::logout');

// ---------- Pembeli ----------
$routes->group('', ['filter' => ['auth', 'role:pembeli']], static function ($routes) {
    $routes->get('checkout', 'Checkout::index');
    $routes->post('checkout', 'Checkout::proses');
    $routes->get('pesanan', 'Pesanan::index');
    $routes->get('pesanan/(:segment)', 'Pesanan::show/$1');
    $routes->post('pesanan/(:segment)/bukti', 'Pesanan::uploadBukti/$1');
    $routes->post('pesanan/(:segment)/batal', 'Pesanan::batal/$1');
});

// Bukti bayar disimpan di writable/ (tidak publik); hanya pemilik pesanan atau admin yang bisa melihat.
$routes->get('bukti/(:segment)', 'Bukti::show/$1', ['filter' => 'auth']);

// ---------- Admin ----------
$routes->group('admin', ['namespace' => 'App\Controllers\Admin', 'filter' => ['auth', 'role:admin']], static function ($routes) {
    $routes->get('/', 'Dashboard::index');

    foreach (['produk' => 'Produk', 'kategori' => 'Kategori'] as $uri => $ctrl) {
        $routes->get($uri, "{$ctrl}::index");
        $routes->get("{$uri}/new", "{$ctrl}::new");
        $routes->post($uri, "{$ctrl}::create");
        $routes->get("{$uri}/(:num)/edit", "{$ctrl}::edit/$1");
        $routes->put("{$uri}/(:num)", "{$ctrl}::update/$1");
        $routes->delete("{$uri}/(:num)", "{$ctrl}::delete/$1");
    }

    $routes->get('pesanan', 'Pesanan::index');
    $routes->get('pesanan/(:segment)', 'Pesanan::show/$1');
    $routes->put('pesanan/(:segment)/status', 'Pesanan::status/$1');
});
