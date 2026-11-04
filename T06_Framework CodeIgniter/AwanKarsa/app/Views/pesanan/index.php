<?= $this->extend('layouts/toko') ?>

<?= $this->section('content') ?>
<h1 class="h2 fw-bold mb-4">Sewa saya</h1>
<?php if ($pesanan === []) : ?>
    <div class="empty-state">
        <i class="bi bi-receipt" aria-hidden="true"></i>
        <h2 class="h5 fw-bold">Belum ada pesanan</h2>
        <a href="<?= site_url('katalog') ?>" class="btn btn-cta">Lihat paket</a>
    </div>
<?php else : ?>
    <div class="surface">
        <?php foreach ($pesanan as $o) : ?>
            <a href="<?= site_url('pesanan/' . $o['kode']) ?>" class="order-row">
                <div>
                    <div class="fw-bold"><?= esc($o['kode']) ?></div>
                    <small class="text-secondary"><?= date('d M Y, H:i', strtotime($o['created_at'])) ?></small>
                </div>
                <div><?= badge_status($o['status']) ?></div>
                <div class="fw-bold text-end"><?= rupiah($o['total']) ?></div>
                <i class="bi bi-chevron-right text-secondary" aria-hidden="true"></i>
            </a>
        <?php endforeach ?>
    </div>
<?php endif ?>
<?= $this->endSection() ?>
