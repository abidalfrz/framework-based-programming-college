<?php

namespace App\Controllers\Mahasiswa;

/**
 * Kartu Hasil Studi per periode, dipilih lewat ?periode=2025/2026|genap.
 */
class Khs extends BaseMahasiswa
{
    public function index(): string
    {
        $perPeriode = $this->penilaian->perPeriode($this->krs->riwayat($this->mahasiswaId()));
        $kunci      = array_map(static fn (array $p): string => $p['tahun_ajaran'] . '|' . $p['semester'], $perPeriode);

        $dipilih = (string) $this->request->getGet('periode');
        $index   = array_search($dipilih, $kunci, true);
        if ($index === false) {
            $index = array_key_last($kunci); // default: periode terakhir
        }

        return view('mahasiswa/khs', [
            'title'      => 'Kartu Hasil Studi',
            'mhs'        => $this->profil(),
            'perPeriode' => $perPeriode,
            'kunci'      => $kunci,
            'aktif'      => $index === null ? null : $perPeriode[$index],
            'akademik'   => $this->akademik,
            'penilaian'  => $this->penilaian,
        ]);
    }
}
