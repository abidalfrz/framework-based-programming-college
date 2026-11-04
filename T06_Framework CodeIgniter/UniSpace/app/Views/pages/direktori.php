<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>
<div class="mb-4">
    <h2 class="fw-bold text-dark mb-1">Direktori Sarana &amp; Fasilitas Kampus</h2>
    <p class="text-muted">Cari ruang kelas, laboratorium, auditorium, atau peralatan inventaris kampus</p>
</div>

<!-- Form Pencarian & Filter Multifaktor -->
<div class="card p-3 p-md-4 border rounded-3 shadow-sm mb-4 bg-white">
    <form action="<?= site_url('direktori') ?>" method="get" class="row g-2 align-items-center">
        <div class="col-md-5">
            <div class="input-group">
                <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                <input type="text" name="q" class="form-control" placeholder="Cari nama, kode, lokasi, atau spesifikasi..." value="<?= esc($keyword) ?>">
            </div>
        </div>
        <div class="col-md-3">
            <select name="kategori" class="form-select">
                <option value="">-- Semua Kategori --</option>
                <?php foreach ($kategoriList as $k) : ?>
                    <option value="<?= $k['id'] ?>" <?= $kategoriId === (int) $k['id'] ? 'selected' : '' ?>>
                        <?= esc($k['nama']) ?>
                    </option>
                <?php endforeach ?>
            </select>
        </div>
        <div class="col-md-2">
            <select name="status" class="form-select">
                <option value="">-- Semua Status --</option>
                <option value="tersedia" <?= $status === 'tersedia' ? 'selected' : '' ?>>Tersedia</option>
                <option value="reservasi" <?= $status === 'reservasi' ? 'selected' : '' ?>>Reservasi</option>
                <option value="pemeliharaan" <?= $status === 'pemeliharaan' ? 'selected' : '' ?>>Pemeliharaan</option>
            </select>
        </div>
        <div class="col-md-2 d-flex gap-1">
            <button type="submit" class="btn btn-portal w-100">
                <i class="bi bi-funnel me-1"></i> Filter
            </button>
            <?php if ($keyword !== '' || $kategoriId !== null || $status !== '') : ?>
                <a href="<?= site_url('direktori') ?>" class="btn btn-outline-secondary" title="Reset filter">
                    <i class="bi bi-x-circle"></i>
                </a>
            <?php endif ?>
        </div>
    </form>
</div>

<!-- Hasil Daftar Fasilitas -->
<?php if (empty($fasilitas)) : ?>
    <div class="card p-5 text-center border-0 shadow-sm bg-white rounded-4">
        <div class="text-muted mb-3">
            <i class="bi bi-search display-3 opacity-25"></i>
        </div>
        <h4 class="fw-bold text-dark">Fasilitas Tidak Ditemukan</h4>
        <p class="text-muted">Tidak ada fasilitas atau sarana yang sesuai dengan kriteria pencarian Anda.</p>
        <div>
            <a href="<?= site_url('direktori') ?>" class="btn btn-outline-portal btn-sm">Reset Pencarian</a>
        </div>
    </div>
<?php else : ?>
    <div class="row g-4 mb-4">
        <?php foreach ($fasilitas as $f) : ?>
            <div class="col-lg-4 col-md-6">
                <div class="card-facility">
                    <div class="facility-thumb">
                        <?php if (! empty($f['foto'])) : ?>
                            <img src="<?= base_url('uploads/fasilitas/' . esc($f['foto'])) ?>" alt="<?= esc($f['nama']) ?>">
                        <?php else : ?>
                            <div class="text-center text-muted">
                                <i class="bi bi-building fs-1 opacity-50"></i>
                                <div class="small fw-semibold mt-1">Foto Fasilitas</div>
                            </div>
                        <?php endif ?>
                        <span class="facility-badge-status badge-<?= esc($f['status']) ?>">
                            <?= esc(ucfirst($f['status'])) ?>
                        </span>
                    </div>
                    <div class="p-3 d-flex flex-column flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="badge bg-light text-teal border small fw-bold"><?= esc($f['nama_kategori']) ?></span>
                            <span class="small text-muted font-monospace"><i class="bi bi-qr-code me-1"></i><?= esc($f['kode']) ?></span>
                        </div>
                        <h5 class="fw-bold text-dark mb-2">
                            <a href="<?= site_url('fasilitas/' . esc($f['slug'])) ?>" class="text-dark text-decoration-none">
                                <?= esc($f['nama']) ?>
                            </a>
                        </h5>
                        <p class="small text-muted mb-3 flex-grow-1">
                            <i class="bi bi-geo-alt-fill text-danger me-1"></i>Lokasi: <?= esc($f['lokasi']) ?><br>
                            <i class="bi bi-people-fill text-primary me-1"></i>Kapasitas: <strong><?= esc($f['kapasitas']) ?> Orang / Unit</strong>
                        </p>
                        <a href="<?= site_url('fasilitas/' . esc($f['slug'])) ?>" class="btn btn-portal btn-sm w-100">
                            Lihat Detail Spesifikasi <i class="bi bi-chevron-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach ?>
    </div>

    <!-- Paginasi -->
    <div class="d-flex justify-content-center mt-4">
        <?= $pager->links('default', 'bootstrap_pagination') ?>
    </div>
<?php endif ?>
<?= $this->endSection() ?>

