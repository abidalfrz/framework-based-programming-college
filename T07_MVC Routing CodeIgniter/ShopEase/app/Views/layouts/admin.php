<?php
$isOperator = session('role') === 'operator';
$current = trim(uri_string(), '/');
$links = $isOperator ? ['operator/orders' => 'Orders'] : [
    'admin' => 'Dashboard', 'admin/orders' => 'Orders', 'admin/products' => 'Products',
    'admin/categories' => 'Categories', 'admin/customers' => 'Customers',
    'admin/reports' => 'Reports', 'admin/settings' => 'Settings',
];
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?= esc($title) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/admin.css') ?>">
</head>
<body>
<a class="skip" href="#content">Skip to content</a>
<div class="admin-shell">
    <aside class="sidebar">
        <div class="sidebar-top"><a class="brand" href="<?= site_url($isOperator ? 'operator/orders' : 'admin') ?>"><span class="brand-mark">s</span>shop<span class="brand-light">ease</span><span class="brand-dot">.</span></a><button class="menu-toggle" type="button" aria-controls="main-menu" aria-expanded="false">Menu</button></div>
        <p class="sidebar-label">STORE MANAGEMENT</p>
        <nav id="main-menu" aria-label="Store management">
            <?php foreach ($links as $path => $label): ?>
                <?php $active = $current === $path || ($path !== 'admin' && str_starts_with($current, $path . '/')); ?>
                <a href="<?= site_url($path) ?>" <?= $active ? 'class="is-active" aria-current="page"' : '' ?>><?= esc($label) ?></a>
            <?php endforeach ?>
        </nav>
        <a class="store-link" href="<?= site_url('/') ?>">&larr; View store</a>
    </aside>
    <main id="content">
        <header class="admin-header"><div><span class="header-overline">SHOPEASE / <?= $isOperator ? 'OPERATIONS' : 'MANAGEMENT' ?></span><strong><?= $isOperator ? 'Order workspace' : 'Store workspace' ?></strong></div><div class="header-actions"><span class="role-label"><?= $isOperator ? 'Operator' : 'Admin' ?></span><form action="<?= site_url('logout') ?>" method="post"><?= csrf_field() ?><button class="sign-out" type="submit">Sign out</button></form></div></header>
        <?= $this->renderSection('content') ?>
    </main>
</div>
<script src="<?= base_url('assets/js/navigation.js') ?>" defer></script>
</body></html>
