<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<section class="wrap products-section"><a href="<?= site_url('orders') ?>">← Your orders</a><h1>Order <?= esc($order['order_number']) ?></h1><?php if (session('success')): ?><p role="status"><?= esc(session('success')) ?></p><?php endif ?>
<p>Order status: <?= esc(str_replace('_', ' ', ucfirst($order['order_status']))) ?></p><p>Payment status: <?= esc(str_replace('_', ' ', ucfirst($payment['payment_status'] ?? 'unpaid'))) ?></p>
<h2>Items</h2><?php foreach ($items as $item): ?><p><?= esc($item['product_name']) ?> × <?= esc($item['quantity']) ?> — <?= format_rupiah((string) $item['subtotal']) ?></p><?php endforeach ?><p>Total: <?= format_rupiah((string) $order['total']) ?></p>
<h2>Delivery address</h2><p><?= esc($order['recipient_name']) ?><br><?= esc($order['address']) ?><br><?= esc($order['city']) ?>, <?= esc($order['province']) ?> <?= esc($order['postal_code']) ?></p>
<?php if (session('error')): ?><p role="alert"><?= esc(session('error')) ?></p><?php endif ?>
<?php if ($payment && in_array($payment['payment_status'], ['unpaid', 'rejected'], true) && $order['order_status'] === 'pending_payment'): ?><h2>Upload payment proof</h2><?php if (! empty($payment['rejection_reason'])): ?><p>Reason: <?= esc($payment['rejection_reason']) ?></p><?php endif ?><form class="account-form" method="post" enctype="multipart/form-data" action="<?= site_url('orders/' . $order['id'] . '/payment') ?>"><?= csrf_field() ?><label for="proof">JPEG, PNG, or WebP, up to 2 MB</label><input id="proof" name="proof" type="file" accept="image/jpeg,image/png,image/webp" required><button class="button button-dark" type="submit">Submit proof</button></form><?php endif ?>
<?php if ($shipment): ?><h2>Shipping</h2><p><?= esc($shipment['courier']) ?> — <?= esc($shipment['tracking_number']) ?></p><?php endif ?>
<?php if ($settings && ! empty($settings['payment_instructions']) && in_array($order['order_status'], ['pending_payment', 'waiting_verification'], true)): ?><h2>Payment instructions</h2><p><?= nl2br(esc($settings['payment_instructions'])) ?></p><?php endif ?></section>
<?= $this->endSection() ?>
