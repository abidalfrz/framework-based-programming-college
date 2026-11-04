<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

/**
 * Pengaturan akademik: periode berjalan, masa KRS, skala nilai, dan batas SKS.
 */
class Akademik extends BaseConfig
{
    /** Periode akademik yang sedang berjalan. */
    public string $tahunAjaran = '2026/2027';

    /** 'ganjil' atau 'genap'. */
    public string $semester = 'ganjil';

    /** Masa pengisian KRS (format Y-m-d, inklusif). */
    public string $krsMulai   = '2026-08-01';
    public string $krsSelesai = '2026-11-30';

    /**
     * Konversi nilai angka ke huruf mutu, urut dari batas tertinggi.
     *
     * @var list<array{huruf: string, min: float, bobot: float}>
     */
    public array $skala = [
        ['huruf' => 'A',  'min' => 85, 'bobot' => 4.0],
        ['huruf' => 'AB', 'min' => 80, 'bobot' => 3.5],
        ['huruf' => 'B',  'min' => 70, 'bobot' => 3.0],
        ['huruf' => 'BC', 'min' => 65, 'bobot' => 2.5],
        ['huruf' => 'C',  'min' => 55, 'bobot' => 2.0],
        ['huruf' => 'D',  'min' => 40, 'bobot' => 1.0],
        ['huruf' => 'E',  'min' => 0,  'bobot' => 0.0],
    ];

    /** Bobot minimal agar mata kuliah dianggap lulus (C). */
    public float $bobotLulus = 2.0;

    /**
     * Batas SKS berdasarkan IP semester sebelumnya: [IP minimal, SKS maksimal].
     *
     * @var list<array{0: float, 1: int}>
     */
    public array $batasSks = [
        [3.00, 24],
        [2.50, 21],
        [2.00, 18],
        [0.00, 15],
    ];

    /** Batas SKS mahasiswa baru (belum punya IP). */
    public int $sksMahasiswaBaru = 20;
}
