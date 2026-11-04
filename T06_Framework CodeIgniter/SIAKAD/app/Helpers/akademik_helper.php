<?php

/**
 * Helper tampilan untuk SIAKAD.
 */

if (! function_exists('badge_nilai')) {
    /** Badge berwarna untuk huruf mutu: A/AB hijau, B/BC biru, C kuning, D/E merah. */
    function badge_nilai(?string $huruf): string
    {
        if ($huruf === null) {
            return '<span class="badge badge-light text-gray-600">Belum dinilai</span>';
        }

        $kelas = match ($huruf) {
            'A', 'AB' => 'nilai-a',
            'B', 'BC' => 'nilai-b',
            'C'       => 'nilai-c',
            default   => 'nilai-d',
        };

        return '<span class="badge badge-nilai ' . $kelas . '">' . esc($huruf) . '</span>';
    }
}

if (! function_exists('label_periode')) {
    function label_periode(string $tahunAjaran, string $semester): string
    {
        return 'Semester ' . ucfirst($semester) . ' ' . $tahunAjaran;
    }
}

if (! function_exists('format_ip')) {
    function format_ip(float $ip): string
    {
        return number_format($ip, 2, ',', '.');
    }
}
