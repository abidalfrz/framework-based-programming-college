<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<?php
$isEdit = ! empty($user);
$action = $isEdit ? site_url('admin/users/' . $user['id']) : site_url('admin/users');
?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800 font-weight-bold"><?= esc($title) ?></h1>
    <a href="<?= site_url('admin/users') ?>" class="btn btn-secondary btn-sm shadow-sm">
        <i class="fas fa-arrow-left fa-sm mr-1"></i> Kembali ke Daftar
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm mb-4">
            <div class="card-header py-3 bg-white">
                <h6 class="m-0 font-weight-bold text-primary">Formulir Akun Petugas</h6>
            </div>
            <div class="card-body">
                <form action="<?= $action ?>" method="post">
                    <?= csrf_field() ?>
                    <?php if ($isEdit) : ?>
                        <input type="hidden" name="_method" value="PUT">
                    <?php endif ?>

                    <div class="form-group">
                        <label class="font-weight-bold text-dark">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control" value="<?= old('nama', $user['nama'] ?? '') ?>" required placeholder="Contoh: Muhammad Abid">
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold text-dark">Username Login <span class="text-danger">*</span></label>
                        <input type="text" name="username" class="form-control" value="<?= old('username', $user['username'] ?? '') ?>" required placeholder="Contoh: abid_sarpras">
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold text-dark">
                            Password <?= $isEdit ? '<small class="text-muted font-weight-normal">(Kosongkan jika tidak ingin mengganti)</small>' : '<span class="text-danger">*</span>' ?>
                        </label>
                        <input type="password" name="password" class="form-control" <?= $isEdit ? '' : 'required' ?> minlength="6" placeholder="Minimal 6 karakter">
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold text-dark">Peran (Role) Akses <span class="text-danger">*</span></label>
                        <select name="role" class="form-control" required>
                            <option value="staff" <?= old('role', $user['role'] ?? 'staff') === 'staff' ? 'selected' : '' ?>>Staff Sarpras (Akses Fasilitas & Pesan)</option>
                            <option value="admin" <?= old('role', $user['role'] ?? '') === 'admin' ? 'selected' : '' ?>>Administrator (Akses Penuh Termasuk Hapus & Kelola User)</option>
                        </select>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-end">
                        <a href="<?= site_url('admin/users') ?>" class="btn btn-secondary mr-2">Batal</a>
                        <button type="submit" class="btn btn-primary font-weight-bold px-4">
                            <i class="fas fa-save mr-1"></i> Simpan Petugas
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

