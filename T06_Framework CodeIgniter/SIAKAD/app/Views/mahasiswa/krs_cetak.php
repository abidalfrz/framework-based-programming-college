<?= $this->extend('layouts/cetak') ?>

<?= $this->section('content') ?>
<h1>KARTU RENCANA STUDI</h1>
<table class="identitas">
    <tr><td>Nama</td><td>: <strong><?= esc($mhs['nama']) ?></strong></td></tr>
    <tr><td>NIM</td><td>: <?= esc($mhs['nim']) ?></td></tr>
    <tr><td>Angkatan</td><td>: <?= esc($mhs['angkatan']) ?></td></tr>
    <tr><td>Periode</td><td>: <?= esc(label_periode($akademik->tahunAjaran, $akademik->semester)) ?></td></tr>
    <tr><td>Dosen wali</td><td>: <?= esc($mhs['nama_wali']) ?></td></tr>
</table>

<table class="data">
    <thead><tr><th class="c">No</th><th>Kode</th><th>Mata kuliah</th><th class="c">SKS</th><th>Dosen pengampu</th></tr></thead>
    <tbody>
    <?php foreach ($krs as $i => $k) : ?>
        <tr><td class="c"><?= $i + 1 ?></td><td><?= esc($k['kode']) ?></td><td><?= esc($k['nama_mk']) ?></td><td class="c"><?= $k['sks'] ?></td><td><?= esc($k['nama_dosen']) ?></td></tr>
    <?php endforeach ?>
    </tbody>
    <tfoot><tr><td colspan="3">Total SKS</td><td class="c"><?= array_sum(array_column($krs, 'sks')) ?></td><td></td></tr></tfoot>
</table>

<div class="ttd">
    <div>Menyetujui,<br>Dosen Wali<div class="garis"><?= esc($mhs['nama_wali']) ?></div></div>
    <div>Surabaya, <?= date('d-m-Y') ?><br>Mahasiswa<div class="garis"><?= esc($mhs['nama']) ?></div></div>
</div>
<?= $this->endSection() ?>
