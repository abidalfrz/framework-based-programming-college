<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card p-4 p-md-5 border-0 rounded-4 shadow-sm bg-white">
            <span class="badge bg-teal-subtle text-teal border px-3 py-2 rounded-pill mb-3 align-self-start">
                <i class="bi bi-info-circle me-1"></i> Informasi Platform
            </span>
            <h2 class="fw-extrabold text-dark mb-3">Tentang UniSpace</h2>
            <p class="lead text-secondary mb-4">
                UniSpace adalah platform direktori dan pengelolaan sarana prasarana kampus berbasis web modern yang dikembangkan untuk memfasilitasi transparansi ketersediaan ruang, laboratorium, dan inventaris kampus.
            </p>

            <div class="row g-4 my-2">
                <div class="col-md-6">
                    <div class="p-3 border rounded-3 bg-light h-100">
                        <div class="fs-3 text-teal mb-2"><i class="bi bi-search"></i></div>
                        <h5 class="fw-bold text-dark">Katalog Terpusat</h5>
                        <p class="small text-muted mb-0">Memberikan visibilitas langsung terhadap status laboratorium, auditorium, dan perangkat kelas secara terstruktur.</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 border rounded-3 bg-light h-100">
                        <div class="fs-3 text-teal mb-2"><i class="bi bi-shield-lock"></i></div>
                        <h5 class="fw-bold text-dark">Manajemen Berbasis Peran</h5>
                        <p class="small text-muted mb-0">Dilengkapi panel kendali untuk staff teknisi dan administrator sarana prasarana dengan proteksi sesi dan validasi ketat.</p>
                    </div>
                </div>
            </div>

            <hr class="my-4">

            <h5 class="fw-bold text-dark mb-3">Teknologi yang Digunakan</h5>
            <ul class="list-unstyled d-flex flex-wrap gap-2 mb-0">
                <li class="badge bg-dark-subtle text-dark p-2 px-3 fs-6"><i class="bi bi-code-slash me-1"></i> CodeIgniter 4.7</li>
                <li class="badge bg-primary-subtle text-primary p-2 px-3 fs-6"><i class="bi bi-bootstrap me-1"></i> Bootstrap 5.3</li>
                <li class="badge bg-info-subtle text-info p-2 px-3 fs-6"><i class="bi bi-database me-1"></i> MySQL Relasional</li>
                <li class="badge bg-secondary-subtle text-secondary p-2 px-3 fs-6"><i class="bi bi-layout-sidebar-inset me-1"></i> SB Admin 2</li>
            </ul>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

