<?= $this->extend('layouts/admin') ?><?= $this->section('content') ?>
<div class="page">
    <div class="page-heading"><div><p class="eyebrow">STORE OVERVIEW</p><h1>Dashboard</h1><p>Keep track of your store at a glance.</p></div><a class="secondary-button" href="<?= site_url('admin/orders') ?>">View all orders</a></div>
    <div class="metrics">
        <article><span>Products</span><strong><?= esc($products) ?></strong><a href="<?= site_url('admin/products') ?>">Manage products</a></article>
        <article><span>Customers</span><strong><?= esc($customers) ?></strong><a href="<?= site_url('admin/customers') ?>">View customers</a></article>
        <article><span>Orders</span><strong><?= esc($orders) ?></strong><a href="<?= site_url('admin/orders') ?>">View orders</a></article>
        <article><span>Sales</span><strong><?= format_rupiah((string) $sales) ?></strong><a href="<?= site_url('admin/reports') ?>">View report</a></article>
        <article class="metric-attention"><span>Payments awaiting review</span><strong><?= esc($pending) ?></strong><a href="<?= site_url('admin/orders') ?>?status=waiting_verification">Review orders</a></article>
    </div>
    <p class="supporting-text">Sales include processing, shipped, and completed orders.</p>
    <div class="section-heading"><div><p class="eyebrow">LATEST ACTIVITY</p><h2>Recent orders</h2></div><a href="<?= site_url('admin/orders') ?>">All orders</a></div>
    <?php if (! $recent): ?><div class="empty-state"><strong>No orders yet</strong><p>New orders will appear here.</p></div><?php endif ?>
    <?php foreach ($recent as $order): ?><article class="admin-item order-row"><div><a class="item-link" href="<?= site_url('admin/orders/' . $order['id']) ?>"><?= esc($order['order_number']) ?></a><span class="status-text"><?= esc(str_replace('_', ' ', ucfirst($order['order_status']))) ?></span></div><strong><?= format_rupiah((string) $order['total']) ?></strong></article><?php endforeach ?>
</div>
<?= $this->endSection() ?>
