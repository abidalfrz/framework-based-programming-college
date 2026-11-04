<?php

namespace App\Libraries;

use Config\Akademik;
use InvalidArgumentException;

/**
 * Perhitungan nilai: konversi angka -> huruf, bobot, IP, IPK, dan batas SKS.
 * Tidak menyentuh database sehingga mudah diuji.
 */
class Penilaian
{
    private Akademik $akademik;

    public function __construct(?Akademik $akademik = null)
    {
        $this->akademik = $akademik ?? config(Akademik::class);
    }

    public function huruf(float $nilai): string
    {
        if ($nilai < 0 || $nilai > 100) {
            throw new InvalidArgumentException('Nilai harus di antara 0 dan 100.');
        }

        foreach ($this->akademik->skala as $skala) {
            if ($nilai >= $skala['min']) {
                return $skala['huruf'];
            }
        }

        return 'E';
    }

    public function bobot(string $huruf): float
    {
        foreach ($this->akademik->skala as $skala) {
            if ($skala['huruf'] === $huruf) {
                return (float) $skala['bobot'];
            }
        }

        throw new InvalidArgumentException("Huruf mutu {$huruf} tidak dikenal.");
    }

    public function lulus(?string $huruf): bool
    {
        return $huruf !== null && $this->bobot($huruf) >= $this->akademik->bobotLulus;
    }

    /**
     * IP = jumlah (SKS x bobot) / jumlah SKS. Baris yang belum dinilai diabaikan.
     *
     * @param list<array{sks: int|string, nilai_huruf: string|null}> $baris
     */
    public function ip(array $baris): float
    {
        $sks  = 0;
        $mutu = 0.0;

        foreach ($baris as $b) {
            if ($b['nilai_huruf'] === null) {
                continue;
            }
            $sks  += (int) $b['sks'];
            $mutu += (int) $b['sks'] * $this->bobot($b['nilai_huruf']);
        }

        return $sks === 0 ? 0.0 : round($mutu / $sks, 2);
    }

    /** @param list<array{sks: int|string, nilai_huruf: string|null}> $baris */
    public function totalSks(array $baris): int
    {
        $total = 0;
        foreach ($baris as $b) {
            if ($b['nilai_huruf'] !== null) {
                $total += (int) $b['sks'];
            }
        }

        return $total;
    }

    /**
     * Untuk transkrip/IPK: mata kuliah yang diulang hanya dihitung nilai terbaiknya.
     *
     * @param list<array{matakuliah_id: int|string, sks: int|string, nilai_huruf: string|null}> $baris
     *
     * @return list<array<string, mixed>>
     */
    public function terbaikPerMatakuliah(array $baris): array
    {
        $terbaik = [];

        foreach ($baris as $b) {
            if ($b['nilai_huruf'] === null) {
                continue;
            }
            $id = (int) $b['matakuliah_id'];
            if (! isset($terbaik[$id]) || $this->bobot($b['nilai_huruf']) > $this->bobot($terbaik[$id]['nilai_huruf'])) {
                $terbaik[$id] = $b;
            }
        }

        return array_values($terbaik);
    }

    /** @param list<array{matakuliah_id: int|string, sks: int|string, nilai_huruf: string|null}> $baris */
    public function ipk(array $baris): float
    {
        return $this->ip($this->terbaikPerMatakuliah($baris));
    }

    /**
     * Ringkasan per periode untuk KHS/transkrip: IP semester dan IPK kumulatif sampai periode itu.
     *
     * @param list<array<string, mixed>> $riwayat baris KRS (urut periode) dengan sks & nilai_huruf
     *
     * @return list<array{tahun_ajaran: string, semester: string, label: string, baris: list<array<string, mixed>>, sks: int, ip: float, ipk: float, dinilai: bool}>
     */
    public function perPeriode(array $riwayat): array
    {
        $kelompok = [];
        foreach ($riwayat as $b) {
            $kelompok[$b['tahun_ajaran'] . '|' . $b['semester']][] = $b;
        }
        ksort($kelompok); // "2025/2026|ganjil" < "2025/2026|genap" < "2026/2027|ganjil"

        $hasil     = [];
        $kumulatif = [];
        foreach ($kelompok as $kunci => $baris) {
            [$ta, $sem] = explode('|', $kunci);
            $kumulatif  = [...$kumulatif, ...$baris];
            $hasil[]    = [
                'tahun_ajaran' => $ta,
                'semester'     => $sem,
                'label'        => ucfirst($sem) . ' ' . $ta,
                'baris'        => $baris,
                'sks'          => array_sum(array_map(static fn (array $b): int => (int) $b['sks'], $baris)),
                'ip'           => $this->ip($baris),
                'ipk'          => $this->ipk($kumulatif),
                'dinilai'      => array_filter($baris, static fn (array $b): bool => $b['nilai_huruf'] !== null) !== [],
            ];
        }

        return $hasil;
    }

    /** Batas SKS semester ini berdasarkan IP semester lalu (null = mahasiswa baru). */
    public function maxSks(?float $ipLalu): int
    {
        if ($ipLalu === null) {
            return $this->akademik->sksMahasiswaBaru;
        }

        foreach ($this->akademik->batasSks as [$ipMinimal, $sks]) {
            if ($ipLalu >= $ipMinimal) {
                return $sks;
            }
        }

        return end($this->akademik->batasSks)[1];
    }
}
