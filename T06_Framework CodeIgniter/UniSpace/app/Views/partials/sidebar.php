<?php
$segment = service('uri')->getSegment(2);
$role    = session('role');
?>
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= site_url('admin') ?>">
        <div class="sidebar-brand-icon">
            <i class="fas fa-cubes"></i>
        </div>
        <div class="sidebar-brand-text mx-2">Uni<strong>Space</strong></div>
    </a>

    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item <?= $segment === '' ? 'active' : '' ?>">
        <a class="nav-link" href="<?= site_url('admin') ?>">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <hr class="sidebar-divider">

    <div class="sidebar-heading">
        Pengelolaan Sarpras
    </div>

    <!-- Nav Item - Fasilitas -->
    <li class="nav-item <?= $segment === 'fasilitas' ? 'active' : '' ?>">
        <a class="nav-link" href="<?= site_url('admin/fasilitas') ?>">
            <i class="fas fa-fw fa-door-open"></i>
            <span>Data Fasilitas</span>
        </a>
    </li>

    <!-- Nav Item - Pesan Masuk -->
    <li class="nav-item <?= $segment === 'pesan' ? 'active' : '' ?>">
        <a class="nav-link" href="<?= site_url('admin/pesan') ?>">
            <i class="fas fa-fw fa-envelope"></i>
            <span>Pesan & Aduan</span>
        </a>
    </li>

    <?php if ($role === 'admin') : ?>
        <hr class="sidebar-divider">

        <div class="sidebar-heading">
            Administrasi Sistem
        </div>

        <!-- Nav Item - Kategori -->
        <li class="nav-item <?= $segment === 'kategori' ? 'active' : '' ?>">
            <a class="nav-link" href="<?= site_url('admin/kategori') ?>">
                <i class="fas fa-fw fa-tags"></i>
                <span>Kategori Fasilitas</span>
            </a>
        </li>

        <!-- Nav Item - Pengguna -->
        <li class="nav-item <?= $segment === 'users' ? 'active' : '' ?>">
            <a class="nav-link" href="<?= site_url('admin/users') ?>">
                <i class="fas fa-fw fa-user-shield"></i>
                <span>Kelola Petugas</span>
            </a>
        </li>
    <?php endif ?>

    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>

