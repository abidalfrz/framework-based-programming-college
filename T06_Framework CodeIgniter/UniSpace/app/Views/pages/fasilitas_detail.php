<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= site_url('/') ?>" class="text-decoration-none">Beranda</a></li>
        <li class="breadcrumb-item"><a href="<?= site_url('direktori') ?>" class="text-decoration-none">Direktori</a></li>
        <li class="breadcrumb-item"><a href="<?= site_url('kategori/' . esc($fasilitas['slug_kategori'])) ?>" class="text-decoration-none"><?= esc($fasilitas['nama_kategori']) ?></a></li>
        <li class="breadcrumb-item active" aria-current="page"><?= esc($fasilitas['nama']) ?></li>
    </ol>
</nav>

<div class="row g-4 mb-5">
    <!-- Gambar & Status Utama -->
    <div class="col-lg-6">
        <div class="card border rounded-4 overflow-hidden shadow-sm bg-white">
            <div class="position-relative bg-light text-center" style="min-height: 320px; display: flex; align-items: center; justify-content: center;">
                <?php if (! empty($fasilitas['foto'])) : ?>
                    <img src="<?= base_url('uploads/fasilitas/' . esc($fasilitas['foto'])) ?>" alt="<?= esc($fasilitas['nama']) ?>" class="img-fluid w-100" style="max-height: 400px; object-fit: cover;">
                <?php else : ?>
                    <div class="p-5 text-muted">
                        <i class="bi bi-building display-1 opacity-25"></i>
                        <div class="mt-2 fw-semibold">Tidak ada foto fasilitas yang diunggah</div>
                    </div>
                <?php endif ?>
                <span class="facility-badge-status badge-<?= esc($fasilitas['status']) ?> fs-6 px-3 py-2">
                    Status: <?= esc(ucfirst($fasilitas['status'])) ?>
                </span>
            </div>
        </div>
    </div>

    <!-- Informasi Detail & Spesifikasi -->
    <div class="col-lg-6">
        <div class="card border-0 p-4 rounded-4 shadow-sm bg-white h-100">
            <div class="d-flex align-items-center gap-2 mb-2">
                <span class="badge bg-teal-subtle text-teal border px-3 py-1 fs-6">
                    <i class="bi <?= esc($fasilitas['icon_kategori'] ?: 'bi-tags') ?> me-1"></i>
                    <?= esc($fasilitas['nama_kategori']) ?>
                </span>
                <span class="badge bg-secondary-subtle text-secondary px-3 py-1 font-monospace fs-6">
                    Kode: <?= esc($fasilitas['kode']) ?>
                </span>
            </div>

            <h2 class="fw-bold text-dark mb-3"><?= esc($fasilitas['nama']) ?></h2>

            <div class="row g-3 py-3 border-top border-bottom my-2">
                <div class="col-sm-6">
                    <div class="text-muted small">Lokasi Gedung / Ruang</div>
                    <div class="fw-bold text-dark fs-6"><i class="bi bi-geo-alt-fill text-danger me-1"></i><?= esc($fasilitas['lokasi']) ?></div>
                </div>
                <div class="col-sm-6">
                    <div class="text-muted small">Daya Tampung / Kapasitas</div>
                    <div class="fw-bold text-dark fs-6"><i class="bi bi-people-fill text-primary me-1"></i><?= esc($fasilitas['kapasitas']) ?> Orang / Satuan</div>
                </div>
                <div class="col-sm-12">
                    <div class="text-muted small">Penanggung Jawab (PIC / Teknisi)</div>
                    <div class="fw-bold text-dark fs-6"><i class="bi bi-person-badge-fill text-teal me-1"></i><?= esc($fasilitas['penanggung_jawab']) ?></div>
                </div>
            </div>

            <div class="my-3">
                <h5 class="fw-bold text-dark mb-2">Spesifikasi &amp; Perlengkapan:</h5>
                <div class="p-3 bg-light rounded-3 text-secondary lh-lg">
                    <?= nl2br(esc($fasilitas['spesifikasi'] ?: 'Tidak ada rincian spesifikasi tambahan.')) ?>
                </div>
            </div>

            <div class="mt-auto pt-3">
                <a href="<?= site_url('contact?fasilitas_id=' . $fasilitas['id']) ?>" class="btn btn-portal btn-lg w-100">
                    <i class="bi bi-question-circle me-1"></i> Ajukan Pertanyaan / Lapor Kendala Fasilitas
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Fasilitas Terkait -->
<?php if (! empty($terkait)) : ?>
    <div class="border-top pt-5">
        <h4 class="fw-bold text-dark mb-4">Fasilitas Lain di Kategori <?= esc($fasilitas['nama_kategori']) ?></h4>
        <div class="row g-4">
            <?php foreach ($terkait as $t) : ?>
                <div class="col-md-4">
                    <div class="card-facility">
                        <div class="facility-thumb" style="height: 150px;">
                            <?php if (! empty($t['foto'])) : ?>
                                <img src="<?= base_url('uploads/fasilitas/' . esc($t['foto'])) ?>" alt="<?= esc($t['nama']) ?>">
                            <?php else : ?>
                                <i class="bi bi-building fs-2 text-muted opacity-50"></i>
                            <?php endif ?>
                            <span class="facility-badge-status badge-<?= esc($t['status']) ?> small">
                                <?= esc(ucfirst($t['status'])) ?>
                            </span>
                        </div>
                        <div class="p-3">
                            <h6 class="fw-bold text-dark mb-1">
                                <a href="<?= site_url('fasilitas/' . esc($t['slug'])) ?>" class="text-dark text-decoration-none">
                                    <?= esc($t['nama']) ?>
                                </a>
                            </h6>
                            <p class="small text-muted mb-2"><i class="bi bi-geo-alt me-1"></i><?= esc($t['lokasi']) ?></p>
                            <a href="<?= site_url('fasilitas/' . esc($t['slug'])) ?>" class="btn btn-outline-portal btn-sm w-100">Lihat Detail</a>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
    </div>
<?php endif ?>
<?= $this->endSection() ?>

