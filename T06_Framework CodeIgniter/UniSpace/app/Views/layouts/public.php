<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title ?? 'UniSpace') ?> · UniSpace Smart Campus</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="<?= base_url('assets/css/portal.css') ?>" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', system-ui, sans-serif; }
    </style>
</head>
<body>
<a class="visually-hidden-focusable skip-link" href="#konten">Lewati ke konten</a>

<?php $uri = service('uri')->getSegment(1); ?>
<nav class="navbar navbar-expand-lg portal-nav sticky-top" aria-label="Navigasi utama">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="<?= site_url('/') ?>">
            <span class="brand-mark" aria-hidden="true"><i class="bi bi-buildings"></i></span>
            <span class="fs-5">Uni<strong>Space</strong></span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navUtama"
                aria-controls="navUtama" aria-expanded="false" aria-label="Buka menu navigasi">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navUtama">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
                <li class="nav-item">
                    <a class="nav-link <?= $uri === '' ? 'active' : '' ?>" href="<?= site_url('/') ?>">
                        <i class="bi bi-house me-1"></i> Beranda
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= in_array($uri, ['direktori', 'fasilitas'], true) ? 'active' : '' ?>" href="<?= site_url('direktori') ?>">
                        <i class="bi bi-grid-3x3-gap me-1"></i> Direktori Fasilitas
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $uri === 'about' ? 'active' : '' ?>" href="<?= site_url('about') ?>">
                        <i class="bi bi-info-circle me-1"></i> Tentang
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $uri === 'contact' ? 'active' : '' ?>" href="<?= site_url('contact') ?>">
                        <i class="bi bi-envelope me-1"></i> Kontak & Bantuan
                    </a>
                </li>
                <li class="nav-item ms-lg-2">
                    <?php if (session('isLoggedIn')) : ?>
                        <a class="btn btn-portal btn-sm" href="<?= site_url('admin') ?>">
                            <i class="bi bi-speedometer2 me-1"></i> Panel Admin (<?= esc(session('nama')) ?>)
                        </a>
                    <?php else : ?>
                        <a class="btn btn-outline-portal btn-sm" href="<?= site_url('login') ?>">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Login Petugas
                        </a>
                    <?php endif ?>
                </li>
            </ul>
        </div>
    </div>
</nav>

<main id="konten">
    <?= $this->renderSection('hero') ?>
    <div class="container py-4 py-md-5">
        <?= $this->include('partials/alerts') ?>
        <?= $this->renderSection('content') ?>
    </div>
</main>

<footer class="portal-footer mt-auto">
    <div class="container py-4">
        <div class="row align-items-center g-3">
            <div class="col-md-6 text-center text-md-start">
                <div class="fw-bold text-dark fs-6">Uni<strong>Space</strong> &middot; Smart Campus Facility Directory</div>
                <div class="small text-muted">Sistem Pengelolaan &amp; Informasi Sarana Prasarana Kampus Terpadu</div>
            </div>
            <div class="col-md-6 text-center text-md-end small text-muted">
                &copy; <?= date('Y') ?> PBKK Kelompok &amp; Tugas Pemrograman Berbasis Kerangka Kerja<br>
                Dibangun dengan <strong>CodeIgniter <?= \CodeIgniter\CodeIgniter::CI_VERSION ?></strong> &amp; Bootstrap 5
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

