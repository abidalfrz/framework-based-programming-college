<?php
$path  = trim(service('uri')->getPath(), '/');
$aktif = static fn (string $prefix, bool $persis = false): string
    => ($persis ? $path === $prefix : str_starts_with($path, $prefix)) ? 'active' : '';
$perluVerifikasi = (new \App\Models\OrderModel())->where('status', \App\Libraries\OrderStatus::MENUNGGU_VERIFIKASI)->countAllResults();
?>
<ul class="navbar-nav sidebar sidebar-dark sidebar-toko accordion" id="accordionSidebar">
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= site_url('admin') ?>">
        <div class="sidebar-brand-icon"><i class="fas fa-cloud"></i></div>
        <div class="sidebar-brand-text mx-3">AwanKarsa</div>
    </a>
    <hr class="sidebar-divider my-0">
    <li class="nav-item <?= $aktif('admin', true) ?>">
        <a class="nav-link" href="<?= site_url('admin') ?>"><i class="fas fa-fw fa-tachometer-alt"></i><span>Dashboard</span></a>
    </li>
    <hr class="sidebar-divider">
    <div class="sidebar-heading">Persewaan</div>
    <li class="nav-item <?= $aktif('admin/pesanan') ?>">
        <a class="nav-link d-flex align-items-center" href="<?= site_url('admin/pesanan') ?>">
            <i class="fas fa-fw fa-receipt"></i><span class="flex-grow-1">Pesanan</span>
            <?php if ($perluVerifikasi > 0) : ?><span class="badge badge-warning badge-counter-side" title="Menunggu verifikasi"><?= $perluVerifikasi ?></span><?php endif ?>
        </a>
    </li>
    <hr class="sidebar-divider">
    <div class="sidebar-heading">Katalog</div>
    <li class="nav-item <?= $aktif('admin/produk') ?>">
        <a class="nav-link" href="<?= site_url('admin/produk') ?>"><i class="fas fa-fw fa-server"></i><span>Paket layanan</span></a>
    </li>
    <li class="nav-item <?= $aktif('admin/kategori') ?>">
        <a class="nav-link" href="<?= site_url('admin/kategori') ?>"><i class="fas fa-fw fa-tags"></i><span>Kategori</span></a>
    </li>
    <hr class="sidebar-divider d-none d-md-block">
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle" aria-label="Ciutkan sidebar"></button>
    </div>
</ul>
