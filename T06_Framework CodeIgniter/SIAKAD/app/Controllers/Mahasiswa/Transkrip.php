<?php

namespace App\Controllers\Mahasiswa;

/**
 * Transkrip: semua mata kuliah yang sudah dinilai, mata kuliah yang diulang
 * hanya ditampilkan nilai terbaiknya.
 */
class Transkrip extends BaseMahasiswa
{
    public function index(): string
    {
        return view('mahasiswa/transkrip', $this->data('Transkrip Akademik'));
    }

    public function cetak(): string
    {
        return view('mahasiswa/transkrip_cetak', $this->data('Cetak Transkrip'));
    }

    private function data(string $title): array
    {
        $terbaik = $this->penilaian->terbaikPerMatakuliah($this->krs->riwayat($this->mahasiswaId()));
        usort($terbaik, static fn (array $a, array $b): int => [$a['semester_mk'], $a['kode']] <=> [$b['semester_mk'], $b['kode']]);

        $perSemester = [];
        foreach ($terbaik as $b) {
            $perSemester[(int) $b['semester_mk']][] = $b;
        }

        return [
            'title'       => $title,
            'mhs'         => $this->profil(),
            'perSemester' => $perSemester,
            'totalSks'    => $this->penilaian->totalSks($terbaik),
            'ipk'         => $this->penilaian->ip($terbaik),
            'penilaian'   => $this->penilaian,
            'akademik'    => $this->akademik,
        ];
    }
}
