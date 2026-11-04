<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title ?? 'Login Petugas') ?> · UniSpace</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="<?= base_url('assets/css/portal.css') ?>" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #115e59 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        .login-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            max-width: 440px;
            width: 100%;
            padding: 2.5rem;
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="text-center mb-4">
        <div class="brand-mark mx-auto mb-3" style="width: 52px; height: 52px; font-size: 1.5rem;">
            <i class="bi bi-buildings"></i>
        </div>
        <h3 class="fw-bold text-dark mb-1">Login Petugas</h3>
        <p class="text-muted small">Panel Administrasi Sarana Prasarana Kampus</p>
    </div>

    <?= $this->include('partials/alerts') ?>

    <form action="<?= site_url('login') ?>" method="post">
        <?= csrf_field() ?>

        <div class="mb-3">
            <label class="form-label fw-semibold text-secondary small">Username</label>
            <div class="input-group">
                <span class="input-group-text bg-light"><i class="bi bi-person"></i></span>
                <input type="text" name="username" class="form-control" value="<?= old('username') ?>" required autofocus placeholder="Masukkan username">
            </div>
        </div>

        <div class="mb-4">
            <label class="form-label fw-semibold text-secondary small">Password</label>
            <div class="input-group">
                <span class="input-group-text bg-light"><i class="bi bi-key"></i></span>
                <input type="password" name="password" class="form-control" required placeholder="Masukkan password">
            </div>
        </div>

        <button type="submit" class="btn btn-portal w-100 py-2 fw-bold mb-3">
            <i class="bi bi-box-arrow-in-right me-1"></i> Masuk ke Panel
        </button>

        <div class="text-center">
            <a href="<?= site_url('/') ?>" class="text-muted small text-decoration-none">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Beranda Publik
            </a>
        </div>
    </form>

    <div class="alert alert-light border small text-muted mt-4 mb-0 py-2">
        <strong>Demo Credentials:</strong><br>
        Admin: <code>admin</code> / <code>admin123</code><br>
        Staff: <code>staff</code> / <code>staff123</code>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

