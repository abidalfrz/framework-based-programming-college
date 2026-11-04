<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800 font-weight-bold">Dashboard Akademik</h1>
    <span class="text-muted small"><i class="far fa-clock mr-1"></i> Masa KRS: <strong><?= esc($akademik->krsMulai) ?></strong> s.d. <strong><?= esc($akademik->krsSelesai) ?></strong></span>
</div>

<div class="row">
    <?php foreach ([
        ['Total Mahasiswa', $jumlahMhs, 'fa-user-graduate', 'primary', 'admin/mahasiswa'],
        ['Dosen Pengajar', $jumlahDosen, 'fa-chalkboard-teacher', 'info', 'admin/dosen'],
        ['Mata Kuliah Aktif', $jumlahMk, 'fa-book-open', 'success', 'admin/matakuliah'],
        ['Isian KRS Semester Ini', $jumlahKrs, 'fa-clipboard-check', 'warning', null],
    ] as [$label, $nilai, $ikon, $warna, $url]) : ?>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-<?= $warna ?> shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-<?= $warna ?> text-uppercase mb-1"><?= $label ?></div>
                            <div class="h4 mb-0 font-weight-bold text-gray-800 tabular"><?= $nilai ?></div>
                        </div>
                        <div class="col-auto"><i class="fas <?= $ikon ?> fa-2x text-gray-300"></i></div>
                    </div>
                    <?php if ($url) : ?><a href="<?= site_url($url) ?>" class="stretched-link" aria-label="Kelola <?= $label ?>"></a><?php endif ?>
                </div>
            </div>
        </div>
    <?php endforeach ?>
</div>

<div class="row">
    <div class="col-lg-7 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header py-3 bg-white"><h2 class="h6 m-0 font-weight-bold text-primary"><i class="fas fa-tasks mr-1"></i> Keterisian Kelas — <?= esc(label_periode($akademik->tahunAjaran, $akademik->semester)) ?></h2></div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="thead-light"><tr><th class="pl-4">Mata Kuliah</th><th>Dosen</th><th style="width:30%">Peserta / Kuota</th></tr></thead>
                        <tbody>
                        <?php foreach ($ditawarkan as $mk) : ?>
                            <?php $persen = min(100, (int) round($mk['peserta'] / max(1, $mk['kuota']) * 100)); ?>
                            <tr>
                                <td class="pl-4">
                                    <div class="font-weight-bold text-dark"><span class="badge badge-light border mr-1"><?= esc($mk['kode']) ?></span> <?= esc($mk['nama']) ?></div>
                                    <div class="small text-muted">Semester <?= $mk['semester'] ?> &middot; <?= $mk['sks'] ?> SKS</div>
                                </td>
                                <td class="small text-muted font-weight-bold"><?= esc($mk['nama_dosen']) ?></td>
                                <td>
                                    <div class="d-flex justify-content-between small tabular mb-1 font-weight-bold">
                                        <span><?= $mk['peserta'] ?> / <?= $mk['kuota'] ?></span>
                                        <span class="<?= $persen >= 100 ? 'text-danger' : 'text-primary' ?>"><?= $persen ?>%</span>
                                    </div>
                                    <div class="meter <?= $persen >= 100 ? 'penuh' : ($persen >= 80 ? 'hampir' : '') ?>" role="img" aria-label="Terisi <?= $persen ?> persen"><span style="width:<?= $persen ?>%"></span></div>
                                </td>
                            </tr>
                        <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-5 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header py-3 bg-white"><h2 class="h6 m-0 font-weight-bold text-primary"><i class="fas fa-chart-pie mr-1"></i> Distribusi Nilai (Semua Periode)</h2></div>
            <div class="card-body">
                <div class="chart-pie pt-2 pb-2" style="height: 240px;">
                    <canvas id="pieNilai"></canvas>
                </div>
                <div class="mt-4 text-center small text-muted">
                    <span class="mr-2"><i class="fas fa-circle text-success"></i> Nilai A</span>
                    <span class="mr-2"><i class="fas fa-circle text-primary"></i> Nilai B</span>
                    <span class="mr-2"><i class="fas fa-circle text-warning"></i> Nilai C</span>
                    <span class="mr-2"><i class="fas fa-circle text-danger"></i> Nilai D/E</span>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('assets/sb-admin-2/vendor/chart.js/Chart.min.js') ?>"></script>
<script>
    var ctx = document.getElementById("pieNilai");
    var myPieChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: <?= json_encode(array_keys($distribusi ?? [])) ?>,
            datasets: [{
                data: <?= json_encode(array_values($distribusi ?? [])) ?>,
                backgroundColor: ['#10b981', '#4f46e5', '#f59e0b', '#ef4444', '#64748b'],
                hoverBorderColor: "rgba(234, 236, 244, 1)",
            }],
        },
        options: {
            maintainAspectRatio: false,
            tooltips: {
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                borderColor: '#dddfeb',
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                caretPadding: 10,
            },
            legend: { display: false },
            cutoutPercentage: 70,
        },
    });
</script>
<?= $this->endSection() ?>
