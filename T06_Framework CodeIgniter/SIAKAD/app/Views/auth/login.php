<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login · SIAKAD Terpadu</title>
    <link href="<?= base_url('assets/sb-admin-2/vendor/fontawesome-free/css/all.min.css') ?>" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="<?= base_url('assets/sb-admin-2/css/sb-admin-2.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/siakad.css') ?>" rel="stylesheet">
</head>
<body class="login-bg d-flex align-items-center py-5">
<?php $errors = session('errors') ?? []; ?>
<main class="container">
    <div class="row justify-content-center">
        <div class="col-xl-9 col-lg-10">
            <div class="card login-panel shadow-lg border-0">
                <div class="row no-gutters">
                    <div class="col-lg-5 login-side p-4 p-md-5 d-none d-lg-flex flex-column justify-content-between">
                        <div>
                            <span class="ikon mb-3 shadow-sm"><i class="fas fa-graduation-cap" aria-hidden="true"></i></span>
                            <h2 class="h4 font-weight-bold mb-2">Portal Akademik Mahasiswa &amp; Dosen</h2>
                            <p class="small text-light opacity-75 mb-0">Rencana Studi (KRS), Rekap Nilai, Hasil Studi (KHS), dan Transkrip Nilai Akademik Terpadu.</p>
                        </div>
                        <div class="p-3 rounded mt-4" style="background: rgba(255, 255, 255, 0.08); border: 1px solid rgba(255, 255, 255, 0.15);">
                            <div class="font-weight-bold small text-warning mb-2"><i class="fas fa-info-circle mr-1"></i> Kredensial Demo:</div>
                            <div class="small mb-1">Admin: <code>admin</code> / <code>admin123</code></div>
                            <div class="small mb-1">Dosen: <code>0011018501</code> (Password: <code>0011018501</code>)</div>
                            <div class="small">Mahasiswa: <code>5025251001</code> (Password: <code>5025251001</code>)</div>
                        </div>
                    </div>
                    <div class="col-lg-7 p-4 p-md-5 bg-white">
                        <div class="mb-4">
                            <h1 class="h4 text-gray-900 font-weight-bold mb-1">Masuk ke SIAKAD</h1>
                            <p class="text-muted small">Gunakan NIM Mahasiswa, NIDN Dosen, atau username Admin.</p>
                        </div>

                        <?= $this->include('partials/alerts') ?>

                        <form action="<?= site_url('login') ?>" method="post" novalidate>
                            <?= csrf_field() ?>
                            <div class="form-group mb-3">
                                <label for="username" class="font-weight-bold small text-dark">NIM / NIDN / Username</label>
                                <input type="text" id="username" name="username" value="<?= esc(old('username')) ?>" autocomplete="username"
                                       class="form-control form-control-lg <?= isset($errors['username']) ? 'is-invalid' : '' ?>" placeholder="Contoh: 5025251001" required autofocus>
                                <div class="invalid-feedback"><?= esc($errors['username'] ?? '') ?></div>
                            </div>
                            <div class="form-group mb-4">
                                <label for="password" class="font-weight-bold small text-dark">Password</label>
                                <input type="password" id="password" name="password" autocomplete="current-password"
                                       class="form-control form-control-lg <?= isset($errors['password']) ? 'is-invalid' : '' ?>" placeholder="Masukkan password" required>
                                <div class="invalid-feedback"><?= esc($errors['password'] ?? '') ?></div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg btn-block font-weight-bold py-2">
                                <i class="fas fa-sign-in-alt mr-1"></i> Masuk Sekarang
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
</body>
</html>
