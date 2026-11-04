<?php

namespace App\Controllers\Mahasiswa;

use App\Libraries\KrsService;
use App\Models\MatakuliahModel;
use CodeIgniter\HTTP\RedirectResponse;
use DomainException;

/**
 * Pengisian KRS: semua aturan (periode, kuota, batas SKS, sudah lulus, duplikat)
 * dijalankan oleh KrsService; controller hanya meneruskan hasilnya.
 */
class Krs extends BaseMahasiswa
{
    public function index(): string
    {
        $id      = $this->mahasiswaId();
        $service = new KrsService($this->akademik);
        $ta      = $this->akademik->tahunAjaran;
        $sem     = $this->akademik->semester;

        // Status setiap mata kuliah yang ditawarkan, dipakai untuk menampilkan tombol yang sesuai.
        $diambil = array_column($this->krs->periode($id, $ta, $sem), 'matakuliah_id');
        $lulus   = [];
        foreach ($this->krs->riwayat($id) as $b) {
            if ($this->penilaian->lulus($b['nilai_huruf'])) {
                $lulus[(int) $b['matakuliah_id']] = $b['nilai_huruf'];
            }
        }

        return view('mahasiswa/krs', [
            'title'      => 'Kartu Rencana Studi',
            'akademik'   => $this->akademik,
            'terbuka'    => $service->periodeTerbuka(),
            'krs'        => $this->krs->periode($id, $ta, $sem),
            'ditawarkan' => (new MatakuliahModel())->ditawarkan($ta, $sem),
            'diambil'    => array_map('intval', $diambil),
            'lulus'      => $lulus,
            'sksIni'     => $this->krs->sksDiambil($id, $ta, $sem),
            'batasSks'   => $service->batasSks($id),
            'ipLalu'     => $service->ipSemesterLalu($id),
        ]);
    }

    public function ambil(): RedirectResponse
    {
        $mkId = (int) $this->request->getPost('matakuliah_id');

        try {
            $mk = (new KrsService($this->akademik))->ambil($this->mahasiswaId(), $mkId);
        } catch (DomainException $e) {
            return redirect()->to('/mahasiswa/krs')->with('error', $e->getMessage());
        }

        return redirect()->to('/mahasiswa/krs')->with('success', "{$mk['kode']} {$mk['nama']} ({$mk['sks']} SKS) ditambahkan ke KRS.");
    }

    public function batal(int $krsId): RedirectResponse
    {
        try {
            $baris = (new KrsService($this->akademik))->batal($this->mahasiswaId(), $krsId);
        } catch (DomainException $e) {
            return redirect()->to('/mahasiswa/krs')->with('error', $e->getMessage());
        }

        return redirect()->to('/mahasiswa/krs')->with('success', "{$baris['kode']} {$baris['nama_mk']} dibatalkan dari KRS.");
    }

    public function cetak(): string
    {
        $id = $this->mahasiswaId();

        return view('mahasiswa/krs_cetak', [
            'title'    => 'Cetak KRS',
            'mhs'      => $this->profil(),
            'akademik' => $this->akademik,
            'krs'      => $this->krs->periode($id, $this->akademik->tahunAjaran, $this->akademik->semester),
        ]);
    }
}
