<?php

use App\Libraries\OrderStatus;

/**
 * Helper tampilan AwanKarsa.
 */

if (! function_exists('rupiah')) {
    function rupiah(int|string|float $nilai): string
    {
        return 'Rp' . number_format((float) $nilai, 0, ',', '.');
    }
}

if (! function_exists('badge_status')) {
    function badge_status(string $status): string
    {
        return sprintf(
            '<span class="badge status-badge status-%s"><i class="bi bi-%s" aria-hidden="true"></i> %s</span>',
            esc(OrderStatus::warna($status), 'attr'),
            esc(OrderStatus::ikon($status), 'attr'),
            esc(OrderStatus::label($status)),
        );
    }
}

if (! function_exists('foto_produk')) {
    /** Foto produk atau ubin ikon kategori bila belum ada foto. */
    function foto_produk(array $produk, string $kelas = ''): string
    {
        if (! empty($produk['foto'])) {
            return sprintf(
                '<img src="%s" alt="%s" class="produk-foto %s" loading="lazy" width="400" height="400">',
                esc(base_url('uploads/produk/' . $produk['foto']), 'attr'),
                esc($produk['nama'] ?? $produk['nama_produk'] ?? 'Produk', 'attr'),
                esc($kelas, 'attr'),
            );
        }

        $ikon = $produk['ikon'] ?? 'server';
        $hue  = crc32((string) ($produk['nama'] ?? $produk['nama_produk'] ?? '')) % 360;

        return sprintf(
            '<div class="produk-foto produk-placeholder %s" style="--hue:%d" role="img" aria-label="%s"><i class="bi bi-%s" aria-hidden="true"></i></div>',
            esc($kelas, 'attr'),
            $hue,
            esc($produk['nama'] ?? $produk['nama_produk'] ?? 'Produk', 'attr'),
            esc($ikon, 'attr'),
        );
    }
}
