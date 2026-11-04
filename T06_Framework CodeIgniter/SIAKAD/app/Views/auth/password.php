<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<?php $errors = session('errors') ?? []; ?>
<h1 class="h3 mb-4 text-gray-800"><?= esc($title) ?></h1>
<div class="row">
    <div class="col-lg-6">
        <div class="card shadow mb-4">
            <div class="card-body">
                <form action="<?= site_url('password') ?>" method="post" novalidate>
                    <?= csrf_field() ?>
                    <input type="hidden" name="_method" value="PUT">
                    <?php foreach (['password_lama' => ['Password lama', 'current-password'], 'password_baru' => ['Password baru', 'new-password'], 'konfirmasi' => ['Ulangi password baru', 'new-password']] as $field => [$label, $auto]) : ?>
                        <div class="form-group">
                            <label for="<?= $field ?>" class="font-weight-bold"><?= $label ?></label>
                            <input type="password" id="<?= $field ?>" name="<?= $field ?>" autocomplete="<?= $auto ?>"
                                   class="form-control <?= isset($errors[$field]) ? 'is-invalid' : '' ?>">
                            <?php if ($field === 'password_baru') : ?><small class="form-text text-muted">Minimal 8 karakter.</small><?php endif ?>
                            <div class="invalid-feedback"><?= esc($errors[$field] ?? '') ?></div>
                        </div>
                    <?php endforeach ?>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
