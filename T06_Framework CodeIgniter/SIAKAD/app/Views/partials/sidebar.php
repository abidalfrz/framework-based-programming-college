<?php
$role  = session('role');
$path  = trim(service('uri')->getPath(), '/');
$aktif = static fn (string $prefix, bool $persis = false): string
    => ($persis ? $path === $prefix : str_starts_with($path, $prefix)) ? 'active' : '';

$menu = match ($role) {
    'admin' => [
        'Utama'        => [['admin', 'fa-tachometer-alt', 'Dashboard', true]],
        'Data Master'  => [
            ['admin/mahasiswa', 'fa-user-graduate', 'Data Mahasiswa', false],
            ['admin/dosen', 'fa-chalkboard-teacher', 'Data Dosen', false],
            ['admin/matakuliah', 'fa-book-open', 'Mata Kuliah', false],
        ],
    ],
    'dosen' => [
        'Pengajaran' => [['dosen', 'fa-chalkboard', 'Kelas & Input Nilai', true]],
        'Akademik'   => [['dosen/perwalian', 'fa-user-friends', 'Perwalian Mahasiswa', false]],
    ],
    default => [
        'Utama'    => [['mahasiswa', 'fa-home', 'Dashboard Mahasiswa', true]],
        'Akademik' => [
            ['mahasiswa/krs', 'fa-clipboard-list', 'Rencana Studi (KRS)', false],
            ['mahasiswa/khs', 'fa-file-invoice', 'Hasil Studi (KHS)', false],
            ['mahasiswa/transkrip', 'fa-award', 'Transkrip Akademik', false],
        ],
    ],
};
?>
<ul class="navbar-nav sidebar sidebar-dark sidebar-siakad accordion" id="accordionSidebar">
    <a class="sidebar-brand d-flex align-items-center justify-content-center py-4" href="<?= site_url('/') ?>">
        <div class="sidebar-brand-icon">
            <i class="fas fa-graduation-cap fa-lg text-primary"></i>
        </div>
        <div class="sidebar-brand-text mx-2 font-weight-bold">SIA<strong>KAD</strong></div>
    </a>

    <?php foreach ($menu as $judul => $items) : ?>
        <hr class="sidebar-divider<?= $judul === array_key_first($menu) ? ' my-0' : '' ?>">
        <div class="sidebar-heading text-uppercase font-weight-bold" style="font-size: 0.68rem; letter-spacing: 0.08em; opacity: 0.7;"><?= esc($judul) ?></div>
        <?php foreach ($items as [$url, $ikon, $label, $persis]) : ?>
            <?php $kelas = $aktif($url, $persis) ?: ($url === 'dosen' && str_starts_with($path, 'dosen/kelas') ? 'active' : ''); ?>
            <li class="nav-item <?= $kelas ?>">
                <a class="nav-link" href="<?= site_url($url) ?>"<?= $kelas ? ' aria-current="page"' : '' ?>>
                    <i class="fas fa-fw <?= $ikon ?>"></i><span><?= esc($label) ?></span>
                </a>
            </li>
        <?php endforeach ?>
    <?php endforeach ?>

    <hr class="sidebar-divider">
    <div class="sidebar-heading text-uppercase font-weight-bold" style="font-size: 0.68rem; letter-spacing: 0.08em; opacity: 0.7;">Akun</div>
    <li class="nav-item <?= $aktif('password', true) ?>">
        <a class="nav-link" href="<?= site_url('password') ?>"><i class="fas fa-fw fa-key"></i><span>Ganti Password</span></a>
    </li>

    <hr class="sidebar-divider d-none d-md-block">
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle" aria-label="Ciutkan sidebar"></button>
    </div>
</ul>
