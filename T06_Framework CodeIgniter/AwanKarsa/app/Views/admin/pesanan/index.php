<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<?php use App\Libraries\OrderStatus; ?>
<h1 class="h3 mb-4 text-gray-800">Pesanan</h1>

<nav class="status-tabs mb-3" aria-label="Filter status">
    <a href="<?= site_url('admin/pesanan') ?>" class="<?= $status === '' ? 'aktif' : '' ?>">Semua <span><?= array_sum($perStatus) ?></span></a>
    <?php foreach (OrderStatus::semua() as $s) : ?>
        <a href="<?= site_url('admin/pesanan?status=' . $s) ?>" class="<?= $status === $s ? 'aktif' : '' ?>"><?= OrderStatus::label($s) ?> <span><?= $perStatus[$s] ?? 0 ?></span></a>
    <?php endforeach ?>
</nav>

<div class="card shadow mb-4">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th class="pl-4">Kode</th><th>Tanggal</th><th>Pembeli</th><th>Status</th><th class="text-right">Total</th><th class="pr-4"></th></tr></thead>
            <tbody>
            <?php foreach ($pesanan as $o) : ?>
                <tr>
                    <td class="pl-4 font-weight-bold"><?= esc($o['kode']) ?></td>
                    <td class="small"><?= date('d M Y H:i', strtotime($o['created_at'])) ?></td>
                    <td><?= esc($o['nama_pembeli']) ?><div class="small text-gray-600"><?= esc($o['email']) ?></div></td>
                    <td><?= badge_status($o['status']) ?></td>
                    <td class="text-right tabular"><?= rupiah($o['total']) ?></td>
                    <td class="pr-4 text-right"><a href="<?= site_url('admin/pesanan/' . $o['kode']) ?>" class="btn btn-sm btn-outline-primary">Detail</a></td>
                </tr>
            <?php endforeach ?>
            <?php if ($pesanan === []) : ?>
                <tr><td colspan="6" class="text-center text-gray-600 py-4">Tidak ada pesanan dengan status ini.</td></tr>
            <?php endif ?>
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white"><?= $pager->links('default', 'bootstrap') ?></div>
</div>
<?= $this->endSection() ?>
