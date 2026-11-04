<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<?php
$isEdit = ! empty($fasilitas);
$action = $isEdit ? site_url('admin/fasilitas/' . $fasilitas['id']) : site_url('admin/fasilitas');
?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800 font-weight-bold"><?= esc($title) ?></h1>
    <a href="<?= site_url('admin/fasilitas') ?>" class="btn btn-secondary btn-sm shadow-sm">
        <i class="fas fa-arrow-left fa-sm mr-1"></i> Kembali ke Daftar
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card shadow-sm mb-4">
            <div class="card-header py-3 bg-white">
                <h6 class="m-0 font-weight-bold text-primary">Formulir Data Fasilitas Kampus</h6>
            </div>
            <div class="card-body">
                <form action="<?= $action ?>" method="post" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <?php if ($isEdit) : ?>
                        <input type="hidden" name="_method" value="PUT">
                    <?php endif ?>

                    <div class="row">
                        <!-- Kode Fasilitas -->
                        <div class="col-md-4 form-group">
                            <label class="font-weight-bold text-dark">Kode Fasilitas <span class="text-danger">*</span></label>
                            <input type="text" name="kode" class="form-control" value="<?= old('kode', $fasilitas['kode'] ?? '') ?>" required placeholder="Misal: LAB-AI-01">
                            <small class="form-text text-muted">Kode unik identifikasi sarana / ruangan.</small>
                        </div>

                        <!-- Nama Fasilitas -->
                        <div class="col-md-8 form-group">
                            <label class="font-weight-bold text-dark">Nama Fasilitas / Ruang <span class="text-danger">*</span></label>
                            <input type="text" name="nama" class="form-control" value="<?= old('nama', $fasilitas['nama'] ?? '') ?>" required placeholder="Contoh: Lab AI & High Performance Computing">
                        </div>

                        <!-- Kategori -->
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold text-dark">Kategori Sarpras <span class="text-danger">*</span></label>
                            <select name="kategori_id" class="form-control" required>
                                <option value="">-- Pilih Kategori --</option>
                                <?php foreach ($kategoriList as $k) : ?>
                                    <option value="<?= $k['id'] ?>" <?= (string) old('kategori_id', $fasilitas['kategori_id'] ?? '') === (string) $k['id'] ? 'selected' : '' ?>>
                                        [<?= esc($k['kode']) ?>] <?= esc($k['nama']) ?>
                                    </option>
                                <?php endforeach ?>
                            </select>
                        </div>

                        <!-- Lokasi / Gedung -->
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold text-dark">Lokasi Gedung &amp; Lantai <span class="text-danger">*</span></label>
                            <input type="text" name="lokasi" class="form-control" value="<?= old('lokasi', $fasilitas['lokasi'] ?? '') ?>" required placeholder="Contoh: Gedung Riset Informatika Lt. 3">
                        </div>

                        <!-- Kapasitas -->
                        <div class="col-md-4 form-group">
                            <label class="font-weight-bold text-dark">Daya Tampung / Kapasitas <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" name="kapasitas" class="form-control" value="<?= old('kapasitas', $fasilitas['kapasitas'] ?? '0') ?>" min="0" required>
                                <div class="input-group-append">
                                    <span class="input-group-text small">Orang/Unit</span>
                                </div>
                            </div>
                        </div>

                        <!-- Status Ketersediaan -->
                        <div class="col-md-4 form-group">
                            <label class="font-weight-bold text-dark">Status Ketersediaan <span class="text-danger">*</span></label>
                            <select name="status" class="form-control" required>
                                <option value="tersedia" <?= old('status', $fasilitas['status'] ?? 'tersedia') === 'tersedia' ? 'selected' : '' ?>>Tersedia (Siap Digunakan)</option>
                                <option value="reservasi" <?= old('status', $fasilitas['status'] ?? '') === 'reservasi' ? 'selected' : '' ?>>Reservasi (Terjadwal)</option>
                                <option value="pemeliharaan" <?= old('status', $fasilitas['status'] ?? '') === 'pemeliharaan' ? 'selected' : '' ?>>Pemeliharaan (Maintenance)</option>
                            </select>
                        </div>

                        <!-- Penanggung Jawab (PIC) -->
                        <div class="col-md-4 form-group">
                            <label class="font-weight-bold text-dark">Penanggung Jawab (PIC) <span class="text-danger">*</span></label>
                            <input type="text" name="penanggung_jawab" class="form-control" value="<?= old('penanggung_jawab', $fasilitas['penanggung_jawab'] ?? '') ?>" required placeholder="Nama Teknisi / Dosen PIC">
                        </div>

                        <!-- Spesifikasi & Perlengkapan -->
                        <div class="col-12 form-group">
                            <label class="font-weight-bold text-dark">Rincian Spesifikasi &amp; Fasilitas Penunjang</label>
                            <textarea name="spesifikasi" rows="4" class="form-control" placeholder="Contoh: 35 Unit PC Core i9, Smart Interactive Display 85', Dual AC 2PK, Gigabit Network Switch..."><?= old('spesifikasi', $fasilitas['spesifikasi'] ?? '') ?></textarea>
                            <small class="form-text text-muted">Tuliskan perangkat keras, software pendukung, atau fasilitas yang tersedia.</small>
                        </div>

                        <!-- Upload Foto -->
                        <div class="col-12 form-group">
                            <label class="font-weight-bold text-dark">Unggah Foto Fasilitas (JPEG, PNG, WebP &middot; Maks. 2MB)</label>
                            <div class="custom-file mb-2">
                                <input type="file" name="foto" class="custom-file-input" id="inputFoto" accept="image/jpeg,image/png,image/webp">
                                <label class="custom-file-label" for="inputFoto" id="labelFoto">Pilih file foto...</label>
                            </div>

                            <div class="d-flex align-items-center gap-3 mt-2">
                                <div id="previewContainer" class="img-preview-box <?= ! empty($fasilitas['foto']) ? '' : 'd-none' ?>">
                                    <img id="imgPreview" src="<?= ! empty($fasilitas['foto']) ? base_url('uploads/fasilitas/' . esc($fasilitas['foto'])) : '#' ?>" alt="Preview">
                                </div>
                                <?php if ($isEdit && ! empty($fasilitas['foto'])) : ?>
                                    <div class="form-check ml-3">
                                        <input type="checkbox" name="hapus_foto" value="1" class="form-check-input" id="checkHapusFoto">
                                        <label class="form-check-label text-danger small font-weight-bold" for="checkHapusFoto">
                                            Hapus foto saat ini
                                        </label>
                                    </div>
                                <?php endif ?>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-end">
                        <a href="<?= site_url('admin/fasilitas') ?>" class="btn btn-secondary mr-2">Batal</a>
                        <button type="submit" class="btn btn-primary font-weight-bold px-4">
                            <i class="fas fa-save mr-1"></i> Simpan Data Fasilitas
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $('#inputFoto').on('change', function () {
        var file = this.files[0];
        if (file) {
            $('#labelFoto').text(file.name);
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#imgPreview').attr('src', e.target.result);
                $('#previewContainer').removeClass('d-none');
            };
            reader.readAsDataURL(file);
        }
    });
</script>
<?= $this->endSection() ?>

