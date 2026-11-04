<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= esc($title ?? 'Admin') ?> · UniSpace Sarpras</title>
    <link href="<?= base_url('assets/sb-admin-2/vendor/fontawesome-free/css/all.min.css') ?>" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="<?= base_url('assets/sb-admin-2/css/sb-admin-2.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/admin.css') ?>" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', system-ui, sans-serif; }
    </style>
</head>
<body id="page-top">
<div id="wrapper">

    <?= $this->include('partials/sidebar') ?>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <!-- Topbar -->
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow-sm">
                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3" aria-label="Tampilkan menu">
                    <i class="fa fa-bars"></i>
                </button>
                <span class="h5 mb-0 text-gray-800 font-weight-bold d-none d-sm-inline"><?= esc($title ?? '') ?></span>

                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('/') ?>" target="_blank" rel="noopener" title="Buka katalog publik">
                            <i class="fas fa-external-link-alt fa-fw"></i><span class="d-none d-lg-inline ml-1 small font-weight-bold">Lihat Situs Publik</span>
                        </a>
                    </li>
                    <div class="topbar-divider d-none d-sm-block"></div>
                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="mr-2 d-none d-lg-inline text-gray-700 font-weight-bold"><?= esc(session('nama')) ?></span>
                            <span class="badge badge-role badge-<?= session('role') === 'admin' ? 'primary' : 'success' ?>"><?= esc(strtoupper((string) session('role'))) ?></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                            <span class="dropdown-item-text small text-gray-600">Login: <strong><?= esc(session('username')) ?></strong></span>
                            <div class="dropdown-divider"></div>
                            <form action="<?= site_url('logout') ?>" method="post" class="m-0">
                                <?= csrf_field() ?>
                                <button type="submit" class="dropdown-item text-danger"><i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-danger"></i> Logout</button>
                            </form>
                        </div>
                    </li>
                </ul>
            </nav>

            <div class="container-fluid mb-5">
                <!-- Alert flash message (Bootstrap 4 format for SB Admin 2) -->
                <?php if (session('success')) : ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle mr-2"></i> <?= esc(session('success')) ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif ?>

                <?php if (session('error')) : ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle mr-2"></i> <?= esc(session('error')) ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif ?>

                <?php if (session('errors')) : ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <div class="font-weight-bold mb-1"><i class="fas fa-exclamation-circle mr-2"></i>Terdapat kesalahan input:</div>
                        <ul class="mb-0 pl-3">
                            <?php foreach ((array) session('errors') as $err) : ?>
                                <li><?= esc($err) ?></li>
                            <?php endforeach ?>
                        </ul>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif ?>

                <?= $this->renderSection('content') ?>
            </div>
        </div>

        <footer class="sticky-footer bg-white shadow-sm mt-auto">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span>UniSpace &middot; Sarpras Panel &middot; CodeIgniter <?= \CodeIgniter\CodeIgniter::CI_VERSION ?> &middot; SB Admin 2</span>
                </div>
            </div>
        </footer>
    </div>
</div>

<!-- Modal Konfirmasi Hapus Data -->
<div class="modal fade" id="modalHapus" tabindex="-1" role="dialog" aria-labelledby="modalHapusLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form class="modal-content" method="post" id="formHapus">
            <?= csrf_field() ?>
            <input type="hidden" name="_method" value="DELETE">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold" id="modalHapusLabel">Konfirmasi Penghapusan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Tutup"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus permanen data <strong id="namaHapus" class="text-danger"></strong>? Tindakan ini tidak dapat dibatalkan.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-danger font-weight-bold"><i class="fas fa-trash-alt mr-1"></i> Ya, Hapus</button>
            </div>
        </form>
    </div>
</div>

<script src="<?= base_url('assets/sb-admin-2/vendor/jquery/jquery.min.js') ?>"></script>
<script src="<?= base_url('assets/sb-admin-2/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('assets/sb-admin-2/vendor/jquery-easing/jquery.easing.min.js') ?>"></script>
<script src="<?= base_url('assets/sb-admin-2/js/sb-admin-2.min.js') ?>"></script>
<script>
    $('#modalHapus').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var url = button.data('url');
        var nama = button.data('nama');
        var modal = $(this);
        modal.find('#formHapus').attr('action', url);
        modal.find('#namaHapus').text(nama);
    });
</script>
<?= $this->renderSection('scripts') ?>
</body>
</html>

