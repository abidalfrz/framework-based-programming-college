<?php

namespace App\Controllers\Dosen;

use App\Controllers\BaseController;
use App\Libraries\Penilaian;
use App\Models\KrsModel;
use App\Models\MatakuliahModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\RedirectResponse;
use Config\Akademik;
use Config\Database;

/**
 * Kelas yang diampu dosen dan input nilai. Dosen hanya bisa membuka kelasnya sendiri.
 */
class Kelas extends BaseController
{
    private Akademik $akademik;

    public function __construct()
    {
        $this->akademik = config(Akademik::class);
    }

    public function index(): string
    {
        return view('dosen/kelas', [
            'title'    => 'Kelas Saya',
            'akademik' => $this->akademik,
            'kelas'    => (new MatakuliahModel())->kelasDosen(
                (int) session('profil_id'),
                $this->akademik->tahunAjaran,
                $this->akademik->semester,
            ),
        ]);
    }

    public function nilai(int $matakuliahId): string
    {
        $mk = $this->kelasMilikSaya($matakuliahId);

        return view('dosen/nilai', [
            'title'    => 'Input Nilai ' . $mk['kode'],
            'mk'       => $mk,
            'akademik' => $this->akademik,
            'peserta'  => $this->peserta($matakuliahId),
        ]);
    }

    public function simpanNilai(int $matakuliahId): RedirectResponse
    {
        $mk      = $this->kelasMilikSaya($matakuliahId);
        $input   = (array) $this->request->getPost('nilai');
        $peserta = array_column($this->peserta($matakuliahId), null, 'id');

        $errors = [];
        $ubah   = [];
        $penilaian = new Penilaian($this->akademik);

        foreach ($input as $krsId => $nilai) {
            $krsId = (int) $krsId;
            $nilai = trim((string) $nilai);

            if (! isset($peserta[$krsId])) {
                continue; // abaikan id yang bukan peserta kelas ini
            }
            if ($nilai === '') {
                $ubah[$krsId] = ['nilai_angka' => null, 'nilai_huruf' => null];

                continue;
            }
            if (! is_numeric($nilai) || (float) $nilai < 0 || (float) $nilai > 100) {
                $errors[$krsId] = 'Nilai harus angka 0–100.';

                continue;
            }
            $angka        = round((float) $nilai, 2);
            $ubah[$krsId] = ['nilai_angka' => $angka, 'nilai_huruf' => $penilaian->huruf($angka)];
        }

        if ($errors !== []) {
            return redirect()->back()->withInput()->with('errors', $errors)
                ->with('error', 'Sebagian nilai tidak valid, tidak ada nilai yang disimpan.');
        }

        $krs = new KrsModel();
        $db  = Database::connect();
        $db->transException(true)->transStart();
        foreach ($ubah as $krsId => $data) {
            $krs->update($krsId, $data);
        }
        $db->transComplete();

        $terisi = count(array_filter($ubah, static fn (array $d): bool => $d['nilai_huruf'] !== null));

        return redirect()->to("/dosen/kelas/{$matakuliahId}")
            ->with('success', "Nilai {$mk['kode']} disimpan: {$terisi} dari " . count($peserta) . ' mahasiswa sudah dinilai.');
    }

    private function kelasMilikSaya(int $matakuliahId): array
    {
        $mk = (new MatakuliahModel())->withDosen()->where('matakuliah.id', $matakuliahId)->first();

        if ($mk === null || (int) $mk['dosen_id'] !== (int) session('profil_id')) {
            throw PageNotFoundException::forPageNotFound('Kelas tidak ditemukan.');
        }

        return $mk;
    }

    private function peserta(int $matakuliahId): array
    {
        return (new KrsModel())->peserta($matakuliahId, $this->akademik->tahunAjaran, $this->akademik->semester);
    }
}
