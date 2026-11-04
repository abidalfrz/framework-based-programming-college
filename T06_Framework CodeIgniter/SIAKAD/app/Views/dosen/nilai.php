<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<?php
$errors  = session('errors') ?? [];
$skala   = $akademik->skala;
$dinilai = count(array_filter($peserta, static fn (array $p): bool => $p['nilai_huruf'] !== null));
?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-transparent px-0 mb-2">
        <li class="breadcrumb-item"><a href="<?= site_url('dosen') ?>">Kelas Saya</a></li>
        <li class="breadcrumb-item active" aria-current="page"><?= esc($mk['kode']) ?></li>
    </ol>
</nav>
<div class="d-sm-flex align-items-end justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-1 text-gray-800"><?= esc($mk['kode'] . ' ' . $mk['nama']) ?></h1>
        <span class="text-gray-600"><?= $mk['sks'] ?> SKS &middot; <?= esc(label_periode($akademik->tahunAjaran, $akademik->semester)) ?></span>
    </div>
    <span class="h5 mb-0 tabular"><?= $dinilai ?>/<?= count($peserta) ?> sudah dinilai</span>
</div>

<div class="row">
    <div class="col-xl-8">
        <form action="<?= site_url('dosen/kelas/' . $mk['id']) ?>" method="post" class="card shadow mb-4" novalidate>
            <?= csrf_field() ?>
            <input type="hidden" name="_method" value="PUT">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead><tr><th class="pl-4">NIM</th><th>Nama</th><th class="text-right">Nilai angka</th><th class="text-center pr-4">Huruf</th></tr></thead>
                    <tbody>
                    <?php foreach ($peserta as $p) : ?>
                        <?php $old = old('nilai.' . $p['id'], $p['nilai_angka'] === null ? '' : rtrim(rtrim($p['nilai_angka'], '0'), '.')); ?>
                        <tr>
                            <td class="pl-4 tabular"><?= esc($p['nim']) ?></td>
                            <td><label for="nilai-<?= $p['id'] ?>" class="mb-0"><?= esc($p['nama']) ?></label></td>
                            <td class="text-right">
                                <input type="text" inputmode="decimal" id="nilai-<?= $p['id'] ?>" name="nilai[<?= $p['id'] ?>]" value="<?= esc($old) ?>"
                                       class="form-control form-control-sm input-nilai d-inline-block <?= isset($errors[$p['id']]) ? 'is-invalid' : '' ?>" data-huruf="huruf-<?= $p['id'] ?>">
                                <div class="invalid-feedback"><?= esc($errors[$p['id']] ?? '') ?></div>
                            </td>
                            <td class="text-center pr-4" id="huruf-<?= $p['id'] ?>"><?= badge_nilai($p['nilai_huruf']) ?></td>
                        </tr>
                    <?php endforeach ?>
                    <?php if ($peserta === []) : ?>
                        <tr><td colspan="4" class="text-center text-gray-600 py-4">Belum ada mahasiswa yang mengambil kelas ini.</td></tr>
                    <?php endif ?>
                    </tbody>
                </table>
            </div>
            <?php if ($peserta !== []) : ?>
                <div class="card-footer bg-white d-flex justify-content-between align-items-center">
                    <small class="text-gray-600">Kosongkan kolom untuk menghapus nilai.</small>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan Nilai</button>
                </div>
            <?php endif ?>
        </form>
    </div>
    <div class="col-xl-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3"><h2 class="h6 m-0 font-weight-bold text-primary">Skala konversi</h2></div>
            <table class="table table-sm mb-0">
                <thead><tr><th class="pl-3">Huruf</th><th>Nilai angka</th><th class="text-right pr-3">Bobot</th></tr></thead>
                <tbody>
                <?php foreach ($skala as $i => $s) : ?>
                    <tr>
                        <td class="pl-3"><?= badge_nilai($s['huruf']) ?></td>
                        <td class="tabular"><?= $s['min'] ?> – <?= $i === 0 ? 100 : '< ' . $skala[$i - 1]['min'] ?></td>
                        <td class="text-right pr-3 tabular"><?= number_format($s['bobot'], 1) ?></td>
                    </tr>
                <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Pratinjau huruf mutu saat dosen mengetik (konversi final tetap dihitung di server).
    var skala = <?= json_encode(array_map(static fn (array $s): array => [$s['huruf'], $s['min']], $skala)) ?>;
    function kelas(h) { return ({A: 'a', AB: 'a', B: 'b', BC: 'b', C: 'c'})[h] || 'd'; }
    document.querySelectorAll('.input-nilai').forEach(function (input) {
        input.addEventListener('input', function () {
            var sel = document.getElementById(input.dataset.huruf);
            var v = input.value.trim().replace(',', '.');
            if (v === '') { sel.innerHTML = '<span class="badge badge-light text-gray-600">Belum dinilai</span>'; return; }
            var n = Number(v);
            if (isNaN(n) || n < 0 || n > 100) { sel.innerHTML = '<span class="badge badge-nilai nilai-d">?</span>'; return; }
            var h = skala.find(function (s) { return n >= s[1]; })[0];
            sel.innerHTML = '<span class="badge badge-nilai nilai-' + kelas(h) + '">' + h + '</span>';
        });
    });
</script>
<?= $this->endSection() ?>
