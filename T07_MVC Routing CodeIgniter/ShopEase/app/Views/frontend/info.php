<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<section class="info wrap"><div class="eyebrow"><span></span> SHOPEASE ONLINE STORE</div>
<?php if ($page === 'about'): ?>
<h1>Shopping made <em>simpler.</em></h1><p>ShopEase brings electronics, fashion, and accessories together in one place.</p><p>Browse the collection, save your favorites to your cart, and keep track of your orders through your account.</p>
<?php else: ?>
<h1>Let’s <em>connect.</em></h1><p>Our contact information is not available yet.</p><p>In the meantime, you can browse products and check your orders through your account.</p>
<?php endif ?>
<a class="button button-dark" href="<?= site_url('/') ?>">Back to home <span aria-hidden="true">↗</span></a></section>
<?= $this->endSection() ?>
