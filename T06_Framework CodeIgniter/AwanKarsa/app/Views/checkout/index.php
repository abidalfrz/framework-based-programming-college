<?= $this->extend('layouts/toko') ?>

<?= $this->section('content') ?>
<?php
$errors = session('errors') ?? [];
$cls    = static fn (string $f): string => isset($errors[$f]) ? 'is-invalid' : '';
?>
<h1 class="h2 fw-bold mb-1">Konfirmasi sewa</h1><p class="text-secondary mb-4">Satu durasi berlaku untuk seluruh paket dalam pesanan.</p>
<form action="<?= site_url('checkout') ?>" method="post" novalidate>
    <?= csrf_field() ?>
    <div class="row g-4">
        <div class="col-lg-7">
            <div class="surface p-4 mb-4">
                <h2 class="h5 fw-bold mb-3"><i class="bi bi-geo-alt text-toko"></i> Data pelanggan</h2>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="nama_penerima" class="form-label fw-bold">Nama pelanggan</label>
                        <input type="text" id="nama_penerima" name="nama_penerima" value="<?= esc(old('nama_penerima', $user['nama'])) ?>" class="form-control <?= $cls('nama_penerima') ?>">
                        <div class="invalid-feedback"><?= esc($errors['nama_penerima'] ?? '') ?></div>
                    </div>
                    <div class="col-md-6">
                        <label for="telepon" class="form-label fw-bold">No. HP</label>
                        <input type="tel" id="telepon" name="telepon" value="<?= esc(old('telepon', $user['telepon'] ?? '')) ?>" class="form-control <?= $cls('telepon') ?>">
                        <div class="invalid-feedback"><?= esc($errors['telepon'] ?? '') ?></div>
                    </div>
                    <div class="col-12">
                        <label for="catatan" class="form-label fw-bold">Catatan <span class="fw-normal text-secondary">(opsional)</span></label>
                        <input type="text" id="catatan" name="catatan" value="<?= esc(old('catatan')) ?>" maxlength="255" placeholder="Mis. kebutuhan sistem operasi atau proyek" class="form-control <?= $cls('catatan') ?>">
                        <div class="invalid-feedback"><?= esc($errors['catatan'] ?? '') ?></div>
                    </div>
                </div>
            </div>
            <div class="surface p-4 mb-4">
                <h2 class="h5 fw-bold">Durasi sewa</h2>
                <label for="durasi_bulan" class="form-label">Durasi (1 bulan = 30 hari)</label>
                <select id="durasi_bulan" name="durasi_bulan" class="form-select <?= $cls('durasi_bulan') ?>">
                    <?php for ($bulan = 1; $bulan <= 12; $bulan++) : ?>
                        <option value="<?= $bulan ?>" <?= (int) old('durasi_bulan', 1) === $bulan ? 'selected' : '' ?>><?= $bulan ?> bulan</option>
                    <?php endfor ?>
                </select>
                <div class="invalid-feedback"><?= esc($errors['durasi_bulan'] ?? '') ?></div>
                <small class="text-secondary">Periode dimulai ketika admin mengaktifkan layanan.</small>
            </div>
            <div class="surface p-4">
                <h2 class="h5 fw-bold mb-3"><i class="bi bi-bank text-toko"></i> Pembayaran</h2>
                <p class="mb-2">Pembayaran manual simulasi. Setelah pesanan dibuat, unggah bukti transfer untuk diverifikasi admin.</p>
                <ul class="list-unstyled mb-0 small">
                    <?php foreach (config('Toko')->rekening as $r) : ?>
                        <li><strong><?= esc($r['bank']) ?></strong> <?= esc($r['nomor']) ?> a.n. <?= esc($r['atas_nama']) ?></li>
                    <?php endforeach ?>
                </ul>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="surface p-4 mb-3">
                <h2 class="h5 fw-bold mb-3"><?= count($isi) ?> paket</h2>
                <?php foreach ($isi as $b) : ?>
                    <div class="d-flex gap-3 align-items-center mb-3">
                        <div class="cart-thumb sm"><?= foto_produk($b) ?></div>
                        <div class="flex-grow-1 small"><div class="fw-bold"><?= esc($b['nama']) ?></div><span class="text-secondary"><?= $b['qty'] ?> × <?= rupiah($b['harga']) ?> / bulan</span></div>
                        <div class="fw-bold small"><?= rupiah($b['subtotal']) ?></div>
                    </div>
                <?php endforeach ?>
            </div>
            <div class="surface p-4">
                <h2 class="h5 fw-bold">Ringkasan sewa</h2>
                <div class="d-flex justify-content-between"><span>Biaya per bulan</span><strong><?= rupiah($ringkasan['subtotal']) ?></strong></div>
                <div class="d-flex justify-content-between border-top pt-3 mt-3"><span>Total sewa</span><strong class="h5 text-toko" id="total-sewa" data-monthly="<?= (int) $ringkasan['subtotal'] ?>"><?= rupiah($ringkasan['subtotal']) ?></strong></div>
            </div>
            <button type="submit" class="btn btn-cta btn-lg w-100 mt-3"><i class="bi bi-lock"></i> Buat pesanan sewa</button>
        </div>
    </div>
</form>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
const durasi = document.getElementById('durasi_bulan');
const total = document.getElementById('total-sewa');
function updateTotal() { total.textContent = 'Rp' + (Number(total.dataset.monthly) * Number(durasi.value)).toLocaleString('id-ID'); }
durasi.addEventListener('change', updateTotal);
updateTotal();
</script>
<?= $this->endSection() ?>
