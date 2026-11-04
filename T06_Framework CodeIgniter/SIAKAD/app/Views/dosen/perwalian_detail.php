<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-transparent px-0 mb-2">
        <li class="breadcrumb-item"><a href="<?= site_url('dosen/perwalian') ?>">Perwalian</a></li>
        <li class="breadcrumb-item active" aria-current="page"><?= esc($mhs['nim']) ?></li>
    </ol>
</nav>
<h1 class="h3 mb-1 text-gray-800"><?= esc($mhs['nama']) ?></h1>
<p class="text-gray-600 mb-4"><?= esc($mhs['nim']) ?> &middot; Angkatan <?= esc($mhs['angkatan']) ?> &middot; <?= esc($mhs['email']) ?></p>

<div class="row">
    <div class="col-lg-5 mb-4">
        <div class="card shadow h-100">
            <div class="card-header py-3"><h2 class="h6 m-0 font-weight-bold text-primary">Riwayat IP per semester</h2></div>
            <table class="table mb-0">
                <thead><tr><th class="pl-4">Periode</th><th class="text-right">SKS</th><th class="text-right">IP</th><th class="text-right pr-4">IPK</th></tr></thead>
                <tbody>
                <?php foreach ($ringkasan as $p) : ?>
                    <tr>
                        <td class="pl-4"><?= esc($p['label']) ?></td>
                        <td class="text-right tabular"><?= $p['sks'] ?></td>
                        <?php if ($p['dinilai']) : ?>
                            <td class="text-right tabular"><?= format_ip($p['ip']) ?></td>
                            <td class="text-right pr-4 tabular font-weight-bold"><?= format_ip($p['ipk']) ?></td>
                        <?php else : ?>
                            <td colspan="2" class="text-right pr-4 small text-gray-600">berjalan</td>
                        <?php endif ?>
                    </tr>
                <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-lg-7 mb-4">
        <div class="card shadow h-100">
            <div class="card-header py-3 d-flex justify-content-between">
                <h2 class="h6 m-0 font-weight-bold text-primary">KRS <?= esc(label_periode($akademik->tahunAjaran, $akademik->semester)) ?></h2>
                <span class="small tabular"><?= array_sum(array_column($krsIni, 'sks')) ?> / <?= $batasSks ?> SKS</span>
            </div>
            <table class="table mb-0">
                <thead><tr><th class="pl-4">Kode</th><th>Mata kuliah</th><th class="text-center">SKS</th><th class="pr-4">Dosen</th></tr></thead>
                <tbody>
                <?php foreach ($krsIni as $k) : ?>
                    <tr><td class="pl-4 font-weight-bold"><?= esc($k['kode']) ?></td><td><?= esc($k['nama_mk']) ?></td><td class="text-center"><?= $k['sks'] ?></td><td class="pr-4 small"><?= esc($k['nama_dosen']) ?></td></tr>
                <?php endforeach ?>
                <?php if ($krsIni === []) : ?>
                    <tr><td colspan="4" class="text-center text-gray-600 py-4">Mahasiswa belum mengisi KRS.</td></tr>
                <?php endif ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
