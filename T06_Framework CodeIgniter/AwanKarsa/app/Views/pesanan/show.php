<?= $this->extend('layouts/toko') ?>

<?= $this->section('content') ?>
<?php
use App\Libraries\OrderStatus;

$errors   = session('errors') ?? [];
$langkah  = [OrderStatus::MENUNGGU_PEMBAYARAN, OrderStatus::MENUNGGU_VERIFIKASI, OrderStatus::DIPROSES, OrderStatus::DIKIRIM, OrderStatus::SELESAI];
$posisi   = array_search($order['status'], $langkah, true);
$batal    = $order['status'] === OrderStatus::DIBATALKAN;
?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= site_url('pesanan') ?>">Pesanan saya</a></li>
        <li class="breadcrumb-item active" aria-current="page"><?= esc($order['kode']) ?></li>
    </ol>
</nav>
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <div>
        <h1 class="h2 fw-bold mb-1">Pesanan <?= esc($order['kode']) ?></h1>
        <span class="text-secondary">Dibuat <?= date('d M Y, H:i', strtotime($order['created_at'])) ?></span>
    </div>
    <?= badge_status($order['status']) ?>
</div>

<?php if (! $batal) : ?>
    <ol class="stepper mb-4" aria-label="Status pesanan">
        <?php foreach ($langkah as $i => $s) : ?>
            <li class="<?= $i < $posisi ? 'selesai' : ($i === $posisi ? 'aktif' : '') ?>" <?= $i === $posisi ? 'aria-current="step"' : '' ?>>
                <span class="dot"><i class="bi bi-<?= OrderStatus::ikon($s) ?>" aria-hidden="true"></i></span>
                <span class="label"><?= OrderStatus::label($s) ?></span>
            </li>
        <?php endforeach ?>
    </ol>
<?php endif ?>

<div class="row g-4">
    <div class="col-lg-7">
        <?php if ($order['status'] === OrderStatus::MENUNGGU_PEMBAYARAN) : ?>
            <div class="surface p-4 mb-4 border-cta">
                <h2 class="h5 fw-bold"><i class="bi bi-wallet2 text-cta"></i> Selesaikan pembayaran</h2>
                <?php if (! empty($order['catatan_admin'])) : ?>
                    <div class="alert alert-warning py-2"><strong>Bukti sebelumnya ditolak:</strong> <?= esc($order['catatan_admin']) ?></div>
                <?php endif ?>
                <p class="mb-2">Simulasi transfer (rekening demo): <strong class="text-toko fs-5"><?= rupiah($order['total']) ?></strong> ke salah satu rekening:</p>
                <ul class="small mb-3">
                    <?php foreach (config('Toko')->rekening as $r) : ?>
                        <li><strong><?= esc($r['bank']) ?></strong> <?= esc($r['nomor']) ?> a.n. <?= esc($r['atas_nama']) ?></li>
                    <?php endforeach ?>
                </ul>
                <form action="<?= site_url('pesanan/' . $order['kode'] . '/bukti') ?>" method="post" enctype="multipart/form-data" novalidate>
                    <?= csrf_field() ?>
                    <label for="bukti" class="form-label fw-bold">Bukti transfer</label>
                    <input type="file" id="bukti" name="bukti" accept="image/jpeg,image/png,image/webp" class="form-control <?= isset($errors['bukti']) ? 'is-invalid' : '' ?>">
                    <div class="invalid-feedback"><?= esc($errors['bukti'] ?? '') ?></div>
                    <div class="form-text">JPG, PNG, atau WEBP maksimal 2 MB.</div>
                    <img id="pratinjau" alt="Pratinjau bukti" class="bukti-preview mt-3 d-none">
                    <button type="submit" class="btn btn-cta mt-3"><i class="bi bi-upload"></i> Kirim bukti</button>
                </form>
            </div>
        <?php elseif (! empty($order['bukti_bayar'])) : ?>
            <div class="surface p-4 mb-4">
                <h2 class="h6 fw-bold">Bukti pembayaran</h2>
                <img src="<?= site_url('bukti/' . $order['bukti_bayar']) ?>" alt="Bukti pembayaran <?= esc($order['kode'], 'attr') ?>" class="bukti-preview">
            </div>
        <?php endif ?>

        <?php if (! empty($order['resi'])) : ?>
            <div class="surface p-4 mb-4 d-flex justify-content-between align-items-center">
                <div><div class="small text-secondary">Referensi layanan</div><div class="fw-bold fs-5 font-monospace"><?= esc($order['resi']) ?></div></div>
                
            </div>
        <?php endif ?>

        <div class="surface p-4">
            <h2 class="h5 fw-bold mb-3">Paket sewa</h2>
            <?php foreach ($items as $it) : ?>
                <div class="d-flex gap-3 align-items-center mb-3">
                    <div class="cart-thumb sm"><?= foto_produk($it + ['nama' => $it['nama_produk']]) ?></div>
                    <div class="flex-grow-1"><a href="<?= site_url('produk/' . $it['slug']) ?>" class="fw-bold text-body text-decoration-none"><?= esc($it['nama_produk']) ?></a><div class="small text-secondary"><?= $it['qty'] ?> × <?= rupiah($it['harga']) ?> / bulan × <?= (int) $order['durasi_bulan'] ?> bulan</div></div>
                    <div class="fw-bold"><?= rupiah($it['subtotal']) ?></div>
                </div>
            <?php endforeach ?>
        </div>
    </div>
    <div class="col-lg-5">
        <?= $this->setData(['ringkasan' => ['subtotal' => (int) $order['subtotal'], 'ongkir' => (int) $order['ongkir'], 'total' => (int) $order['total'], 'kurang_gratis' => 0]])->include('partials/ringkasan') ?>
        <div class="surface p-4 mt-3">
            <h2 class="h6 fw-bold">Periode sewa</h2>
            <p>Durasi: <?= (int) $order['durasi_bulan'] ?> bulan (<?= (int) $order['durasi_bulan'] * 30 ?> hari)<br>
            <?php if ($order['mulai_sewa']) : ?>Aktif: <?= date('d M Y H:i', strtotime($order['mulai_sewa'])) ?><br>Berakhir: <?= date('d M Y H:i', strtotime($order['akhir_sewa'])) ?><?php else : ?>Dimulai saat layanan diaktifkan admin.<?php endif ?></p>
            <h2 class="h6 fw-bold">Data pelanggan</h2>
            <p class="mb-1 fw-bold"><?= esc($order['nama_penerima']) ?> &middot; <?= esc($order['telepon']) ?></p>
            <p class="mb-0 text-secondary"><?= nl2br(esc($order['alamat'])) ?></p>
            <?php if (! empty($order['catatan'])) : ?><p class="small mt-2 mb-0"><i class="bi bi-chat-left-text"></i> <?= esc($order['catatan']) ?></p><?php endif ?>
        </div>
        <?php if (OrderStatus::izinkan($order['status'], OrderStatus::DIBATALKAN, 'pembeli')) : ?>
            <form action="<?= site_url('pesanan/' . $order['kode'] . '/batal') ?>" method="post" class="mt-3" onsubmit="return confirm('Batalkan pesanan ini? Stok akan dikembalikan.')">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-outline-danger w-100">Batalkan pesanan</button>
            </form>
        <?php endif ?>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    var bukti = document.getElementById('bukti');
    if (bukti) {
        bukti.addEventListener('change', function () {
            var img = document.getElementById('pratinjau');
            if (!this.files[0]) { img.classList.add('d-none'); return; }
            img.src = URL.createObjectURL(this.files[0]);
            img.classList.remove('d-none');
        });
    }
</script>
<?= $this->endSection() ?>
