<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Shop electronics, fashion, and accessories at ShopEase.">
    <title><?= esc($title) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/store.css') ?>">
</head>
<body>
<a class="skip" href="#main">Skip to content</a>
<div class="announcement">Electronics, fashion, and accessories for every day</div>
<header class="header">
    <div class="wrap nav">
        <a class="logo" href="<?= site_url('/') ?>" aria-label="ShopEase home"><span class="logo-mark">s</span> shop<span>ease</span><span class="logo-dot">.</span></a>
        <button class="menu-toggle" type="button" aria-controls="main-menu" aria-expanded="false">Menu</button>
        <nav id="main-menu" class="nav-links" aria-label="Main navigation">
            <a href="<?= site_url('/') ?>">Home</a>
            <a href="<?= site_url('/') ?>#kategori">Categories</a>
            <a href="<?= site_url('catalog') ?>">Catalog</a>
            <a href="<?= site_url('about') ?>">About</a>
            <?php if (session('logged_in') && session('role') === 'admin'): ?><a href="<?= site_url('admin') ?>">Dashboard</a><?php elseif (session('logged_in') && session('role') === 'operator'): ?><a href="<?= site_url('operator/orders') ?>">Orders</a><?php elseif (session('logged_in') && session('role') === 'customer'): ?><a href="<?= site_url('cart') ?>">Cart</a><a href="<?= site_url('orders') ?>">Orders</a><a href="<?= site_url('account') ?>">Account</a><a href="<?= site_url('account/addresses') ?>">Addresses</a><?php elseif (! session('logged_in')): ?><a class="sign-in-link" href="<?= site_url('login') ?>">Sign in</a><?php endif ?>
        </nav>
        <?php if (session('logged_in')): ?><form method="post" action="<?= site_url('logout') ?>"><?= csrf_field() ?><button class="nav-cta" type="submit">Sign out</button></form><?php else: ?><a class="nav-cta" href="<?= site_url('catalog') ?>">Shop products</a><?php endif ?>
    </div>
</header>
<main id="main"><?= $this->renderSection('content') ?></main>
<footer class="footer">
    <div class="wrap footer-grid">
        <div><a class="logo footer-logo" href="<?= site_url('/') ?>"><span class="logo-mark">s</span> shop<span>ease</span><span class="logo-dot">.</span></a><p>Things you love,<br>for every day.</p></div>
        <div><strong>Explore</strong><a href="<?= site_url('/') ?>#kategori">Categories</a><a href="<?= site_url('/') ?>#produk">Products</a></div>
        <div><strong>ShopEase</strong><a href="<?= site_url('about') ?>">About us</a><a href="<?= site_url('contact') ?>">Contact</a></div>
        <div class="footer-note"><strong>About this store</strong><p>Find electronics, fashion, and accessories for your everyday.</p></div>
    </div>
    <div class="wrap footer-bottom"><span>© <?= date('Y') ?> ShopEase.</span><a href="<?= site_url('photo-credits') ?>">Photo credits</a></div>
</footer>
<script src="<?= base_url('assets/js/navigation.js') ?>" defer></script>
<script src="<?= base_url('assets/js/featured-carousel.js') ?>" defer></script>
</body>
</html>
