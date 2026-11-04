<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title ?? 'Cetak') ?> · SIAKAD</title>
    <link href="https://fonts.googleapis.com/css2?family=Atkinson+Hyperlegible:wght@400;700&display=swap" rel="stylesheet">
    <link href="<?= base_url('assets/css/cetak.css') ?>" rel="stylesheet">
</head>
<body>
<div class="aksi no-print">
    <button type="button" onclick="window.print()">Cetak / Simpan PDF</button>
    <a href="javascript:history.back()">Kembali</a>
</div>
<main class="kertas">
    <header class="kop">
        <div class="logo" aria-hidden="true">S</div>
        <div>
            <div class="kop-judul">UNIVERSITAS KAMPUS DEMO</div>
            <div class="kop-sub">Fakultas Teknologi Informasi &middot; Program Studi Teknik Informatika</div>
            <div class="kop-sub">Jl. Kampus Raya No. 1, Surabaya</div>
        </div>
    </header>
    <?= $this->renderSection('content') ?>
</main>
</body>
</html>
