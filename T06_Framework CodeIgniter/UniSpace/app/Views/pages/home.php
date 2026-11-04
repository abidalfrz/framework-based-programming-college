<?= $this->extend('layouts/public') ?>

<?= $this->section('hero') ?>
<section class="hero-section text-center text-md-start">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-lg-7">
                <span class="badge bg-teal-subtle text-light border border-light-subtle px-3 py-2 rounded-pill mb-3">
                    <i class="bi bi-shield-check me-1"></i> Sistem Informasi Sarana Prasarana Kampus
                </span>
                <h1 class="display-4 fw-extrabold mb-3">
                    Temukan &amp; Akses Fasilitas Kampus dengan Mudah
                </h1>
                <p class="lead mb-4">
                    UniSpace menyediakan katalog lengkap laboratorium komputer, auditorium, ruang seminar, dan sarana teknologi penunjang perkuliahan untuk seluruh civitas akademika.
                </p>
                <div class="d-flex flex-wrap gap-2 justify-content-center justify-content-md-start">
                    <a href="<?= site_url('direktori') ?>" class="btn btn-portal btn-lg px-4 shadow-sm">
                        <i class="bi bi-search me-1"></i> Jelajahi Direktori
                    </a>
                    <a href="<?= site_url('contact') ?>" class="btn btn-outline-light btn-lg px-4">
                        <i class="bi bi-chat-dots me-1"></i> Hubungi Sarpras
                    </a>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="bg-white p-4 rounded-4 shadow-lg text-dark">
                    <h5 class="fw-bold mb-3 text-dark">
                        <i class="bi bi-search-heart text-teal me-2"></i>Pencarian Cepat
                    </h5>
                    <form action="<?= site_url('direktori') ?>" method="get">
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Kata Kunci / Nama Fasilitas</label>
                            <input type="text" name="q" class="form-control" placeholder="Misal: Lab AI, Auditorium, Laser Projector...">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Kategori</label>
                            <select name="kategori" class="form-select">
                                <option value="">Semua Kategori</option>
                                <?php foreach ($kategoriList as $k) : ?>
                                    <option value="<?= $k['id'] ?>"><?= esc($k['nama']) ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-portal w-100 py-2">
                            <i class="bi bi-filter me-1"></i> Filter Fasilitas
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Baris Ringkasan Statistik -->
<div class="row g-3 mb-5">
    <div class="col-6 col-md-3">
        <div class="stat-box d-flex align-items-center gap-3">
            <div class="stat-icon"><i class="bi bi-door-open"></i></div>
            <div>
                <div class="h3 fw-bold mb-0 text-dark"><?= esc($statistik['total_fasilitas']) ?></div>
                <div class="small text-muted">Total Fasilitas</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-box d-flex align-items-center gap-3">
            <div class="stat-icon text-success" style="background:#ecfdf5;"><i class="bi bi-check-circle"></i></div>
            <div>
                <div class="h3 fw-bold mb-0 text-success"><?= esc($statistik['tersedia']) ?></div>
                <div class="small text-muted">Status Tersedia</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-box d-flex align-items-center gap-3">
            <div class="stat-icon text-primary" style="background:#eff6ff;"><i class="bi bi-people"></i></div>
            <div>
                <div class="h3 fw-bold mb-0 text-primary"><?= number_format($statistik['total_kapasitas'], 0, ',', '.') ?></div>
                <div class="small text-muted">Total Daya Tampung</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-box d-flex align-items-center gap-3">
            <div class="stat-icon text-warning" style="background:#fffbeb;"><i class="bi bi-tags"></i></div>
            <div>
                <div class="h3 fw-bold mb-0 text-warning"><?= esc($statistik['total_kategori']) ?></div>
                <div class="small text-muted">Kategori Fasilitas</div>
            </div>
        </div>
    </div>
</div>

<!-- Kategori Fasilitas -->
<div class="mb-5">
    <div class="d-flex justify-content-between align-items-end mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Kategori Sarana &amp; Fasilitas</h3>
            <p class="text-muted mb-0">Pilih rumpun fasilitas untuk melihat daftar sarana terkait</p>
        </div>
    </div>
    <div class="row g-3">
        <?php foreach ($kategoriList as $k) : ?>
            <div class="col-md-4 col-sm-6">
                <a href="<?= site_url('kategori/' . esc($k['slug'])) ?>" class="text-decoration-none">
                    <div class="card h-100 p-3 border rounded-3 shadow-sm hover-shadow bg-white transition">
                        <div class="d-flex align-items-center gap-3 mb-2">
                            <div class="rounded-3 p-2 bg-light text-teal fs-4">
                                <i class="bi <?= esc($k['icon'] ?: 'bi-building') ?>"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0 text-dark"><?= esc($k['nama']) ?></h6>
                                <span class="badge bg-secondary-subtle text-secondary small"><?= esc($k['kode']) ?> &middot; <?= (int) $k['jumlah_fasilitas'] ?> Fasilitas</span>
                            </div>
                        </div>
                        <p class="small text-muted mb-0 text-truncate-2"><?= esc($k['deskripsi'] ?: 'Kumpulan fasilitas sarana pendukung perkuliahan dan aktivitas kampus.') ?></p>
                    </div>
                </a>
            </div>
        <?php endforeach ?>
    </div>
</div>

<!-- Fasilitas Terbaru & Unggulan -->
<div>
    <div class="d-flex justify-content-between align-items-end mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Fasilitas Kampus Unggulan</h3>
            <p class="text-muted mb-0">Sarana prasarana yang siap mendukung kegiatan akademik &amp; riset</p>
        </div>
        <a href="<?= site_url('direktori') ?>" class="btn btn-outline-portal btn-sm">
            Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
        </a>
    </div>

    <div class="row g-4">
        <?php foreach ($fasilitasUnggulan as $f) : ?>
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
                            <i class="bi bi-geo-alt-fill text-danger me-1"></i><?= esc($f['lokasi']) ?><br>
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
</div>
<?= $this->endSection() ?>

