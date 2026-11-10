<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<section class="wrap products-section"><h1>Your orders</h1><?php if (! $orders): ?><p>You have no orders yet.</p><a href="<?= site_url('catalog') ?>">Explore products</a><?php endif ?><?php foreach ($orders as $order): ?><article class="cart-item"><div><h2><a href="<?= site_url('orders/' . $order['id']) ?>"><?= esc($order['order_number']) ?></a></h2><p><?= esc(str_replace('_', ' ', ucfirst($order['order_status']))) ?></p></div><p><?= format_rupiah((string) $order['total']) ?></p></article><?php endforeach ?></section>
<?= $this->endSection() ?>
