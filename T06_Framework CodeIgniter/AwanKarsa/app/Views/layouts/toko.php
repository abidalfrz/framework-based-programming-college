<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title ?? 'AwanKarsa') ?> · AwanKarsa</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="<?= base_url('assets/css/toko.css') ?>" rel="stylesheet">
</head>
<body>
<a class="visually-hidden-focusable skip-link" href="#konten">Lewati ke konten</a>
<?php
$jumlahKeranjang = (new \App\Libraries\Keranjang())->jumlahItem();
$seg             = service('uri')->getSegment(1);
?>
<nav class="navbar navbar-expand-lg toko-nav sticky-top" aria-label="Navigasi utama">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="<?= site_url('/') ?>">
            <span class="brand-mark" aria-hidden="true"><i class="bi bi-cloud-fill"></i></span>
            <span>Awan<strong>Karsa</strong></span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navToko" aria-controls="navToko" aria-expanded="false" aria-label="Buka menu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navToko">
            <form action="<?= site_url('katalog') ?>" method="get" class="toko-search mx-lg-4 my-3 my-lg-0 flex-grow-1" role="search">
                <label for="cari-global" class="visually-hidden">Cari paket</label>
                <i class="bi bi-search" aria-hidden="true"></i>
                <input type="search" id="cari-global" name="q" value="<?= esc((string) service('request')->getGet('q')) ?>" class="form-control" placeholder="Cari VPS, GPU, hosting…">
            </form>
            <ul class="navbar-nav align-items-lg-center gap-lg-1">
                <li class="nav-item"><a class="nav-link <?= $seg === 'katalog' ? 'active' : '' ?>" href="<?= site_url('katalog') ?>">Katalog</a></li>
                <li class="nav-item">
                    <a class="nav-link position-relative <?= $seg === 'keranjang' ? 'active' : '' ?>" href="<?= site_url('keranjang') ?>" aria-label="Keranjang, <?= $jumlahKeranjang ?> unit">
                        <i class="bi bi-cart3 fs-5" aria-hidden="true"></i><span class="d-lg-none ms-2">Keranjang</span>
                        <?php if ($jumlahKeranjang > 0) : ?><span class="cart-count"><?= $jumlahKeranjang ?></span><?php endif ?>
                    </a>
                </li>
                <?php if (session('isLoggedIn')) : ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle" aria-hidden="true"></i> <?= esc(strtok((string) session('nama'), ' ')) ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                            <?php if (session('role') === 'admin') : ?>
                                <li><a class="dropdown-item" href="<?= site_url('admin') ?>"><i class="bi bi-speedometer2 me-2"></i>Panel admin</a></li>
                            <?php else : ?>
                                <li><a class="dropdown-item" href="<?= site_url('pesanan') ?>"><i class="bi bi-receipt me-2"></i>Pesanan saya</a></li>
                            <?php endif ?>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="<?= site_url('logout') ?>" method="post" class="m-0">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="dropdown-item"><i class="bi bi-box-arrow-right me-2"></i>Keluar</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                <?php else : ?>
                    <li class="nav-item ms-lg-2"><a class="btn btn-outline-toko btn-sm" href="<?= site_url('login') ?>">Masuk</a></li>
                    <li class="nav-item ms-lg-1 mt-2 mt-lg-0"><a class="btn btn-toko btn-sm" href="<?= site_url('daftar') ?>">Daftar</a></li>
                <?php endif ?>
            </ul>
        </div>
    </div>
</nav>

<main id="konten">
    <?= $this->renderSection('hero') ?>
    <div class="container py-4 py-lg-5">
        <?= $this->include('partials/alerts') ?>
        <?= $this->renderSection('content') ?>
    </div>
</main>

<footer class="toko-footer">
    <div class="container d-flex flex-column flex-md-row justify-content-between gap-2 py-4">
        <span>&copy; <?= date('Y') ?> AwanKarsa &middot; Academic Cloud &amp; VPS Hub</span>
        <span>CodeIgniter <?= \CodeIgniter\CodeIgniter::CI_VERSION ?></span>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?= $this->renderSection('scripts') ?>
</body>
</html>
