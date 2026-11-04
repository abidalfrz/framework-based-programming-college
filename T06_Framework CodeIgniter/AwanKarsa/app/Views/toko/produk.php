<?= $this->extend('layouts/toko') ?>

<?= $this->section('content') ?>
<?php
$stok = (int) $produk['stok'];
$maks = min($stok, config('Toko')->maksQtyPerProduk);
?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= site_url('katalog') ?>">Katalog</a></li>
        <li class="breadcrumb-item"><a href="<?= site_url('katalog?kategori=' . $produk['slug_kategori']) ?>"><?= esc($produk['nama_kategori']) ?></a></li>
        <li class="breadcrumb-item active" aria-current="page"><?= esc($produk['nama']) ?></li>
    </ol>
</nav>

<div class="row g-4 g-lg-5 mb-5">
    <div class="col-md-6"><div class="detail-media"><?= foto_produk($produk) ?></div></div>
    <div class="col-md-6">
        <div class="produk-kategori mb-2"><?= esc($produk['nama_kategori']) ?></div>
        <h1 class="h2 fw-bold"><?= esc($produk['nama']) ?></h1>
        <div class="detail-harga my-3"><?= rupiah($produk['harga']) ?> <small class="fs-6 text-secondary">/ bulan</small></div>
        <p class="text-secondary"><?= nl2br(esc($produk['deskripsi'])) ?></p>

        <div class="detail-stok mb-4">
            <?php if ($stok === 0) : ?>
                <span class="text-danger fw-bold"><i class="bi bi-x-circle"></i> Kapasitas penuh</span>
            <?php elseif ($stok <= 5) : ?>
                <span class="text-warning-emphasis fw-bold"><i class="bi bi-exclamation-circle"></i> Tinggal <?= $stok ?> lagi</span>
            <?php else : ?>
                <span class="text-success fw-bold"><i class="bi bi-check-circle"></i> Kapasitas tersedia (<?= $stok ?>)</span>
            <?php endif ?>
        </div>

        <?php if ($stok > 0) : ?>
            <form action="<?= site_url('keranjang') ?>" method="post" class="d-flex gap-2 align-items-end flex-wrap">
                <?= csrf_field() ?>
                <input type="hidden" name="produk_id" value="<?= $produk['id'] ?>">
                <div>
                    <label for="qty" class="form-label small text-secondary mb-1">Jumlah</label>
                    <input type="number" id="qty" name="qty" value="1" min="1" max="<?= $maks ?>" class="form-control qty-input">
                </div>
                <button type="submit" class="btn btn-cta btn-lg"><i class="bi bi-cart-plus"></i> Tambah ke keranjang</button>
            </form>
            <small class="text-secondary d-block mt-2">Maksimal <?= $maks ?> unit per pesanan.</small>
        <?php endif ?>
    </div>
</div>

<?php if ($terkait !== []) : ?>
    <section aria-labelledby="judul-terkait">
        <h2 id="judul-terkait" class="section-title">Paket serupa</h2>
        <div class="row g-3">
            <?php foreach ($terkait as $p) : ?>
                <div class="col-6 col-md-3"><?= $this->setData(['p' => $p])->include('partials/produk_card') ?></div>
            <?php endforeach ?>
        </div>
    </section>
<?php endif ?>
<?= $this->endSection() ?>
