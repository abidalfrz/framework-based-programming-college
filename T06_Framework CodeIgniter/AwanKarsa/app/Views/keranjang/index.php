<?= $this->extend('layouts/toko') ?>

<?= $this->section('content') ?>
<?php $maksToko = config('Toko')->maksQtyPerProduk; ?>
<h1 class="h2 fw-bold mb-1">Keranjang sewa</h1><p class="text-secondary mb-4">Harga berikut untuk satu bulan. Pilih durasi pada tahap checkout.</p>

<?php if ($isi === []) : ?>
    <div class="empty-state">
        <i class="bi bi-cart" aria-hidden="true"></i>
        <h2 class="h5 fw-bold">Keranjang masih kosong</h2>
        <p class="text-secondary">Yuk cari paket komputasi untuk proyekmu.</p>
        <a href="<?= site_url('katalog') ?>" class="btn btn-cta">Lihat paket</a>
    </div>
<?php else : ?>
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="surface">
                <?php foreach ($isi as $b) : ?>
                    <div class="cart-row <?= $b['masalah'] ? 'bermasalah' : '' ?>">
                        <a href="<?= site_url('produk/' . $b['slug']) ?>" class="cart-thumb"><?= foto_produk($b) ?></a>
                        <div class="flex-grow-1">
                            <a href="<?= site_url('produk/' . $b['slug']) ?>" class="fw-bold text-body text-decoration-none"><?= esc($b['nama']) ?></a>
                            <div class="small text-secondary"><?= rupiah($b['harga']) ?> / bulan &middot; kapasitas <?= (int) $b['stok'] ?></div>
                            <?php if ($b['masalah']) : ?><div class="small text-danger fw-bold mt-1"><i class="bi bi-exclamation-triangle"></i> <?= esc($b['masalah']) ?></div><?php endif ?>
                        </div>
                        <form action="<?= site_url('keranjang/' . $b['id']) ?>" method="post" class="d-flex gap-1 align-items-center">
                            <?= csrf_field() ?>
                            <input type="hidden" name="_method" value="PUT">
                            <label for="qty-<?= $b['id'] ?>" class="visually-hidden">Jumlah <?= esc($b['nama']) ?></label>
                            <input type="number" id="qty-<?= $b['id'] ?>" name="qty" value="<?= $b['qty'] ?>" min="0" max="<?= max(1, min((int) $b['stok'], $maksToko)) ?>" class="form-control form-control-sm qty-input">
                            <button type="submit" class="btn btn-sm btn-outline-toko">Ubah</button>
                        </form>
                        <div class="cart-subtotal fw-bold"><?= rupiah($b['subtotal']) ?></div>
                        <form action="<?= site_url('keranjang/' . $b['id']) ?>" method="post">
                            <?= csrf_field() ?>
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="btn btn-sm btn-link text-danger" aria-label="Hapus <?= esc($b['nama'], 'attr') ?>"><i class="bi bi-trash3"></i></button>
                        </form>
                    </div>
                <?php endforeach ?>
            </div>
        </div>
        <div class="col-lg-4">
            <?= $this->setData(['ringkasan' => $ringkasan])->include('partials/ringkasan') ?>
            <?php if ($adaMasalah) : ?>
                <button class="btn btn-cta btn-lg w-100 mt-3" disabled>Checkout</button>
                <small class="text-danger d-block mt-2">Perbaiki item yang ditandai merah terlebih dahulu.</small>
            <?php else : ?>
                <a href="<?= site_url('checkout') ?>" class="btn btn-cta btn-lg w-100 mt-3">Checkout <i class="bi bi-arrow-right"></i></a>
                <?php if (! session('isLoggedIn')) : ?><small class="text-secondary d-block mt-2 text-center">Anda akan diminta masuk terlebih dahulu.</small><?php endif ?>
            <?php endif ?>
        </div>
    </div>
<?php endif ?>
<?= $this->endSection() ?>
