<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= esc($title ?? 'SIAKAD') ?> · SIAKAD Terpadu</title>
    <link href="<?= base_url('assets/sb-admin-2/vendor/fontawesome-free/css/all.min.css') ?>" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="<?= base_url('assets/sb-admin-2/css/sb-admin-2.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/siakad.css') ?>" rel="stylesheet">
</head>
<body id="page-top">
<div id="wrapper">

    <?= $this->include('partials/sidebar') ?>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow-sm">
                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3" aria-label="Tampilkan menu">
                    <i class="fa fa-bars"></i>
                </button>
                <span class="periode-chip d-none d-sm-inline-flex">
                    <i class="far fa-calendar-alt mr-2" aria-hidden="true"></i>
                    <?= esc(label_periode(config('Akademik')->tahunAjaran, config('Akademik')->semester)) ?>
                </span>

                <ul class="navbar-nav ml-auto">
                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="mr-2 d-none d-lg-inline text-gray-700 font-weight-bold"><?= esc(session('nama')) ?></span>
                            <span class="badge badge-role role-<?= esc(session('role'), 'attr') ?>"><?= esc(strtoupper((string) session('role'))) ?></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                            <span class="dropdown-item-text small text-gray-600">Login: <strong><?= esc(session('username')) ?></strong></span>
                            <a class="dropdown-item" href="<?= site_url('password') ?>"><i class="fas fa-key fa-sm fa-fw mr-2 text-gray-500"></i> Ganti password</a>
                            <div class="dropdown-divider"></div>
                            <form action="<?= site_url('logout') ?>" method="post" class="m-0">
                                <?= csrf_field() ?>
                                <button type="submit" class="dropdown-item text-danger"><i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-danger"></i> Logout</button>
                            </form>
                        </div>
                    </li>
                </ul>
            </nav>

            <div class="container-fluid" id="konten">
                <?= $this->include('partials/alerts') ?>
                <?= $this->renderSection('content') ?>
            </div>
        </div>

        <footer class="sticky-footer bg-white shadow-sm mt-auto">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span>SIAKAD &middot; Sistem Informasi Akademik Terpadu &middot; CodeIgniter <?= \CodeIgniter\CodeIgniter::CI_VERSION ?></span>
                </div>
            </div>
        </footer>
    </div>
</div>

<!-- Modal konfirmasi (hapus / batal) dipakai semua tombol [data-target="#modalHapus"] -->
<div class="modal fade" id="modalHapus" tabindex="-1" role="dialog" aria-labelledby="modalHapusLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form class="modal-content" method="post" id="formHapus">
            <?= csrf_field() ?>
            <input type="hidden" name="_method" value="DELETE">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold" id="modalHapusLabel">Konfirmasi Tindakan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Tutup"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body" id="pesanHapus"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-danger font-weight-bold" id="tombolHapus">Ya, lanjutkan</button>
            </div>
        </form>
    </div>
</div>

<script src="<?= base_url('assets/sb-admin-2/vendor/jquery/jquery.min.js') ?>"></script>
<script src="<?= base_url('assets/sb-admin-2/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('assets/sb-admin-2/vendor/jquery-easing/jquery.easing.min.js') ?>"></script>
<script src="<?= base_url('assets/sb-admin-2/js/sb-admin-2.min.js') ?>"></script>
<script>
    $('#modalHapus').on('show.bs.modal', function (e) {
        var btn = $(e.relatedTarget);
        $('#formHapus').attr('action', btn.data('action'));
        $('#pesanHapus').text(btn.data('pesan'));
        $('#tombolHapus').text(btn.data('tombol') || 'Ya, hapus');
    });
</script>
<?= $this->renderSection('scripts') ?>
</body>
</html>
