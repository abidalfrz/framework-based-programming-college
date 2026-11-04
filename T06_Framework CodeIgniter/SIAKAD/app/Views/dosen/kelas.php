<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<h1 class="h3 mb-1 text-gray-800">Kelas Saya</h1>
<p class="text-gray-600 mb-4"><?= esc(label_periode($akademik->tahunAjaran, $akademik->semester)) ?> &middot; pilih kelas untuk mengisi nilai.</p>

<div class="row">
    <?php foreach ($kelas as $k) : ?>
        <?php $ditawarkan = ((int) $k['semester'] % 2 === 1) === ($akademik->semester === 'ganjil'); ?>
        <div class="col-md-6 col-xl-4 mb-4">
            <div class="card shadow h-100 border-left-primary">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1"><?= esc($k['kode']) ?> &middot; <?= $k['sks'] ?> SKS &middot; Smt <?= $k['semester'] ?></div>
                    <h2 class="h5 font-weight-bold text-gray-800"><?= esc($k['nama']) ?></h2>
                    <?php if ($ditawarkan) : ?>
                        <p class="mb-3 tabular"><i class="fas fa-users text-gray-400 mr-1"></i> <?= $k['peserta'] ?> / <?= $k['kuota'] ?> mahasiswa</p>
                        <a href="<?= site_url('dosen/kelas/' . $k['id']) ?>" class="btn btn-primary btn-sm stretched-link"><i class="fas fa-pen mr-1"></i> Input nilai</a>
                    <?php else : ?>
                        <p class="mb-0 text-gray-600 small">Tidak dibuka pada semester <?= esc($akademik->semester) ?>.</p>
                    <?php endif ?>
                </div>
            </div>
        </div>
    <?php endforeach ?>
    <?php if ($kelas === []) : ?>
        <div class="col"><div class="alert alert-info">Anda belum mengampu mata kuliah.</div></div>
    <?php endif ?>
</div>
<?= $this->endSection() ?>
