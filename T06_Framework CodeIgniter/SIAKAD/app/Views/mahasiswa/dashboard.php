<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<?php $persenSks = min(100, (int) round($sksIni / max(1, $batasSks) * 100)); ?>
<div class="mb-4">
    <h1 class="h3 mb-1 text-gray-800 font-weight-bold">Halo, <?= esc(strtok($mhs['nama'], ' ')) ?>! 👋</h1>
    <p class="text-gray-600 mb-0"><?= esc($mhs['nim']) ?> &middot; Angkatan <?= esc($mhs['angkatan']) ?> &middot; Dosen Wali: <strong><?= esc($mhs['nama_wali']) ?></strong></p>
</div>

<div class="row">
    <!-- IPK Hero Banner -->
    <div class="col-lg-4 mb-4">
        <div class="ipk-hero shadow-sm p-4 h-100 d-flex flex-column justify-content-between">
            <div>
                <div class="label"><i class="fas fa-chart-line mr-1"></i> Indeks Prestasi Kumulatif</div>
                <div class="angka my-2"><?= $sksLulus > 0 ? format_ip($ipk) : '–' ?></div>
                <div class="small opacity-75">dari total <strong><?= $sksLulus ?> SKS</strong> yang telah dinilai</div>
            </div>
            <a href="<?= site_url('mahasiswa/transkrip') ?>" class="btn btn-light btn-sm mt-4 font-weight-bold align-self-start shadow-sm">
                Lihat Transkrip Lengkap <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
    </div>

    <!-- Status KRS & Progres SKS -->
    <div class="col-lg-8 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">KRS <?= esc(label_periode($akademik->tahunAjaran, $akademik->semester)) ?></div>
                        <div class="h4 font-weight-bold text-gray-800 mb-0 tabular"><?= $sksIni ?> <span class="h6 text-gray-500 font-weight-normal">/ <?= $batasSks ?> SKS</span></div>
                    </div>
                    <span class="badge <?= $krsTerbuka ? 'badge-success' : 'badge-secondary' ?> p-2 px-3 font-weight-bold">
                        <i class="fas <?= $krsTerbuka ? 'fa-door-open' : 'fa-lock' ?> mr-1"></i>
                        <?= $krsTerbuka ? 'Masa KRS Dibuka' : 'Masa KRS Ditutup' ?>
                    </span>
                </div>
                <div class="meter mb-3" role="img" aria-label="<?= $sksIni ?> dari <?= $batasSks ?> SKS"><span style="width:<?= $persenSks ?>%"></span></div>
                <p class="small text-gray-600 mb-3">Periode pengisian: <strong><?= esc($akademik->krsMulai) ?></strong> s.d. <strong><?= esc($akademik->krsSelesai) ?></strong>. Beban maksimal SKS dihitung otomatis dari IP semester sebelumnya.</p>
                <a href="<?= site_url('mahasiswa/krs') ?>" class="btn btn-primary btn-sm font-weight-bold px-3">
                    <i class="fas fa-clipboard-list mr-1"></i> <?= $sksIni === 0 ? 'Isi KRS Sekarang' : 'Kelola Rencana Studi (KRS)' ?>
                </a>
                <hr class="my-4">
                <h2 class="h6 font-weight-bold text-gray-800 mb-3"><i class="fas fa-chart-area text-primary mr-1"></i> Grafik Perkembangan IP &amp; IPK</h2>
                <div style="height: 140px;">
                    <canvas id="grafikIp" role="img" aria-label="Grafik IP dan IPK per semester"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<?php $dinilai = array_values(array_filter($perPeriode, static fn (array $p): bool => $p['dinilai'])); ?>
<script src="<?= base_url('assets/sb-admin-2/vendor/chart.js/Chart.min.js') ?>"></script>
<script>
    new Chart(document.getElementById('grafikIp'), {
        type: 'line',
        data: {
            labels: <?= json_encode(array_column($dinilai, 'label')) ?>,
            datasets: [
                {
                    label: 'IP Semester',
                    data: <?= json_encode(array_column($dinilai, 'ip')) ?>,
                    borderColor: '#4f46e5',
                    backgroundColor: 'rgba(79, 70, 229, 0.1)',
                    pointBackgroundColor: '#4f46e5',
                    borderWidth: 2,
                    lineTension: 0.2
                },
                {
                    label: 'IPK Kumulatif',
                    data: <?= json_encode(array_column($dinilai, 'ipk')) ?>,
                    borderColor: '#10b981',
                    pointBackgroundColor: '#10b981',
                    fill: false,
                    borderDash: [5, 5],
                    borderWidth: 2,
                    lineTension: 0.2
                }
            ]
        },
        options: {
            maintainAspectRatio: false,
            animation: { duration: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 0 : 600 },
            scales: {
                yAxes: [{ ticks: { min: 0, max: 4, stepSize: 1 } }]
            }
        }
    });
</script>
<?= $this->endSection() ?>
