<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= site_url('/') ?>" class="text-decoration-none">Beranda</a></li>
        <li class="breadcrumb-item"><a href="<?= site_url('direktori') ?>" class="text-decoration-none">Direktori</a></li>
        <li class="breadcrumb-item active" aria-current="page"><?= esc($kategori['nama']) ?></li>
    </ol>
</nav>

<div class="card p-4 border-0 rounded-4 shadow-sm bg-white mb-4">
    <div class="d-flex align-items-center gap-3">
        <div class="rounded-3 p-3 bg-light text-teal fs-2">
            <i class="bi <?= esc($kategori['icon'] ?: 'bi-building') ?>"></i>
        </div>
        <div>
            <span class="badge bg-secondary-subtle text-secondary mb-1">Kode: <?= esc($kategori['kode']) ?></span>
            <h2 class="fw-bold text-dark mb-1"><?= esc($kategori['nama']) ?></h2>
            <p class="text-muted mb-0"><?= esc($kategori['deskripsi'] ?: 'Kategori fasilitas penunjang perkuliahan.') ?></p>
        </div>
    </div>
</div>

<h4 class="fw-bold text-dark mb-3">Daftar Fasilitas dalam Kategori Ini</h4>

<?php if (empty($fasilitas)) : ?>
    <div class="card p-5 text-center border-0 bg-white rounded-3 shadow-sm">
        <p class="text-muted mb-0">Belum ada fasilitas yang didaftarkan pada kategori ini.</p>
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
                            <i class="bi bi-building fs-1 text-muted opacity-50"></i>
                        <?php endif ?>
                        <span class="facility-badge-status badge-<?= esc($f['status']) ?>">
                            <?= esc(ucfirst($f['status'])) ?>
                        </span>
                    </div>
                    <div class="p-3 d-flex flex-column flex-grow-1">
                        <span class="small text-muted font-monospace mb-1"><?= esc($f['kode']) ?></span>
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
                            Lihat Detail <i class="bi bi-chevron-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach ?>
    </div>

    <div class="d-flex justify-content-center mt-4">
        <?= $pager->links('default', 'bootstrap_pagination') ?>
    </div>
<?php endif ?>
<?= $this->endSection() ?>

