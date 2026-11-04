<?= $this->extend('layouts/toko') ?>

<?= $this->section('hero') ?>
<section class="hero-toko">
    <div class="container py-5">
        <div class="row align-items-center g-4">
            <div class="col-lg-7">
                <span class="hero-eyebrow"><i class="bi bi-cloud" aria-hidden="true"></i> Academic Cloud &amp; VPS Hub</span>
                <h1 class="hero-title">Sumber daya komputasi, <span>untuk ide kampus.</span></h1>
                <p class="hero-lead">Pilih VPS, server GPU, hosting, dan penyimpanan cloud untuk proyek mahasiswa. Pilih durasi sewa, unggah bukti transfer, lalu pantau aktivasi layanan.</p>
                <div class="d-flex flex-wrap gap-2">
                    <a href="<?= site_url('katalog') ?>" class="btn btn-cta btn-lg">Jelajahi paket <i class="bi bi-arrow-right"></i></a>
                    <a href="#kategori" class="btn btn-outline-toko btn-lg">Lihat kategori</a>
                </div>
            </div>
            <div class="col-lg-5">
                <ol class="alur-belanja" aria-label="Alur sewa">
                    <li><i class="bi bi-cart-plus"></i><div><strong>Pilih paket</strong><span>Masukkan ke keranjang</span></div></li>
                    <li><i class="bi bi-credit-card-2-front"></i><div><strong>Checkout &amp; transfer</strong><span>Unggah bukti pembayaran</span></div></li>
                    <li><i class="bi bi-patch-check"></i><div><strong>Diverifikasi admin</strong><span>Layanan disiapkan</span></div></li>
                    <li><i class="bi bi-server"></i><div><strong>Sewa aktif</strong><span>Pantau status pesanan</span></div></li>
                </ol>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section id="kategori" class="mb-5" aria-labelledby="judul-kategori">
    <h2 id="judul-kategori" class="section-title">Jelajahi kategori</h2>
    <div class="row g-3">
        <?php foreach ($kategori as $k) : ?>
            <div class="col-6 col-lg-3">
                <a href="<?= site_url('katalog?kategori=' . $k['slug']) ?>" class="kategori-tile">
                    <i class="bi bi-<?= esc($k['ikon'], 'attr') ?>" aria-hidden="true"></i>
                    <span class="fw-bold"><?= esc($k['nama']) ?></span>
                    <small><?= $k['jumlah'] ?> paket</small>
                </a>
            </div>
        <?php endforeach ?>
    </div>
</section>

<section class="mb-5" aria-labelledby="judul-terlaris">
    <div class="d-flex justify-content-between align-items-end mb-3">
        <h2 id="judul-terlaris" class="section-title mb-0">Paling laris</h2>
        <a href="<?= site_url('katalog') ?>" class="link-toko">Semua paket <i class="bi bi-arrow-right"></i></a>
    </div>
    <div class="row g-3">
        <?php foreach ($terlaris as $p) : ?>
            <div class="col-6 col-md-3"><?= $this->setData(['p' => $p])->include('partials/produk_card') ?></div>
        <?php endforeach ?>
    </div>
</section>

<section aria-labelledby="judul-terbaru">
    <h2 id="judul-terbaru" class="section-title">Paket terbaru</h2>
    <div class="row g-3">
        <?php foreach ($terbaru as $p) : ?>
            <div class="col-6 col-md-3"><?= $this->setData(['p' => $p])->include('partials/produk_card') ?></div>
        <?php endforeach ?>
    </div>
</section>
<?= $this->endSection() ?>
