<?php

namespace App\Libraries;

use App\Models\KrsModel;
use App\Models\MatakuliahModel;
use CodeIgniter\I18n\Time;
use Config\Akademik;
use Config\Database;
use DomainException;
use Throwable;

/**
 * Aturan bisnis KRS. Pelanggaran aturan dilempar sebagai DomainException
 * dengan pesan yang siap ditampilkan ke mahasiswa.
 */
class KrsService
{
    private Akademik $akademik;
    private KrsModel $krs;
    private MatakuliahModel $matakuliah;
    private Penilaian $penilaian;
    private Time $sekarang;

    public function __construct(?Akademik $akademik = null, ?Time $sekarang = null)
    {
        $this->akademik   = $akademik ?? config(Akademik::class);
        $this->krs        = model(KrsModel::class);
        $this->matakuliah = model(MatakuliahModel::class);
        $this->penilaian  = new Penilaian($this->akademik);
        $this->sekarang   = $sekarang ?? Time::now();
    }

    /** @return array{tahun_ajaran: string, semester: string} */
    public function periode(): array
    {
        return ['tahun_ajaran' => $this->akademik->tahunAjaran, 'semester' => $this->akademik->semester];
    }

    public function periodeTerbuka(): bool
    {
        $mulai   = Time::parse($this->akademik->krsMulai . ' 00:00:00');
        $selesai = Time::parse($this->akademik->krsSelesai . ' 23:59:59');

        return ! $this->sekarang->isBefore($mulai) && ! $this->sekarang->isAfter($selesai);
    }

    /** Semester ganjil membuka mata kuliah semester 1,3,5,7; genap membuka 2,4,6,8. */
    public function ditawarkan(array $mk): bool
    {
        $ganjil = ((int) $mk['semester']) % 2 === 1;

        return $ganjil === ($this->akademik->semester === 'ganjil');
    }

    /** IP periode terakhir yang sudah dinilai, null jika belum pernah ada nilai. */
    public function ipSemesterLalu(int $mahasiswaId): ?float
    {
        $perPeriode = [];

        foreach ($this->krs->riwayat($mahasiswaId) as $b) {
            if ($b['nilai_huruf'] === null || $this->periodeBerjalan($b)) {
                continue;
            }
            $kunci                = $b['tahun_ajaran'] . '-' . ($b['semester'] === 'ganjil' ? '1' : '2');
            $perPeriode[$kunci][] = $b;
        }

        if ($perPeriode === []) {
            return null;
        }
        ksort($perPeriode);

        return $this->penilaian->ip(end($perPeriode));
    }

    public function batasSks(int $mahasiswaId): int
    {
        return $this->penilaian->maxSks($this->ipSemesterLalu($mahasiswaId));
    }

    /** @return array<string, mixed> mata kuliah yang berhasil diambil */
    public function ambil(int $mahasiswaId, int $matakuliahId): array
    {
        $this->pastikanPeriodeTerbuka();

        $db = Database::connect();
        $db->transException(true)->transBegin();

        try {
            // Serialize requests for one student before checking their total SKS and history.
            $student = $db->query('SELECT id FROM mahasiswa WHERE id = ? FOR UPDATE', [$mahasiswaId])->getRowArray();
            if ($student === null) {
                throw new DomainException('Mahasiswa tidak ditemukan.');
            }
            // A locking read sees the latest quota even with MySQL REPEATABLE READ.
            $mk = $db->query('SELECT * FROM matakuliah WHERE id = ? FOR UPDATE', [$matakuliahId])->getRowArray();
            if ($mk === null || ! $this->ditawarkan($mk)) {
                throw new DomainException('Mata kuliah tidak ditawarkan pada semester ini.');
            }

            ['tahun_ajaran' => $ta, 'semester' => $sem] = $this->periode();
            foreach ($this->krs->riwayatMatakuliah($mahasiswaId, $matakuliahId) as $b) {
                if ($b['tahun_ajaran'] === $ta && $b['semester'] === $sem) {
                    throw new DomainException("{$mk['nama']} sudah ada di KRS Anda.");
                }
                if ($this->penilaian->lulus($b['nilai_huruf'])) {
                    throw new DomainException("{$mk['nama']} sudah lulus dengan nilai {$b['nilai_huruf']}, tidak perlu diulang.");
                }
            }

            $batas = $this->batasSks($mahasiswaId);
            $diambil = $this->krs->sksDiambil($mahasiswaId, $ta, $sem);
            if ($diambil + (int) $mk['sks'] > $batas) {
                throw new DomainException("Melebihi batas SKS: {$diambil} + {$mk['sks']} SKS > batas {$batas} SKS.");
            }
            // Locking reads are current reads; an ordinary COUNT could use an older snapshot.
            $peserta = $db->query(
                'SELECT id FROM krs WHERE matakuliah_id = ? AND tahun_ajaran = ? AND semester = ? FOR UPDATE',
                [$matakuliahId, $ta, $sem],
            )->getResultArray();
            if (count($peserta) >= (int) $mk['kuota']) {
                throw new DomainException("Kuota kelas {$mk['nama']} sudah penuh ({$mk['kuota']} mahasiswa).");
            }
            $id = $this->krs->insert([
                'mahasiswa_id' => $mahasiswaId,
                'matakuliah_id' => $matakuliahId,
                'tahun_ajaran' => $ta,
                'semester' => $sem,
            ]);
            if ($id === false || ! $db->transStatus() || ! $db->transCommit()) {
                throw new \RuntimeException('KRS gagal disimpan.');
            }
        } catch (Throwable $e) {
            $db->transRollback();
            throw $e;
        }

        return $mk;
    }

    /** @return array<string, mixed> baris KRS yang dibatalkan */
    public function batal(int $mahasiswaId, int $krsId): array
    {
        $this->pastikanPeriodeTerbuka();

        $baris = $this->krs->riwayatById($mahasiswaId, $krsId);
        if ($baris === null) {
            throw new DomainException('Data KRS tidak ditemukan.');
        }
        if (! $this->periodeBerjalan($baris)) {
            throw new DomainException('Hanya KRS semester berjalan yang dapat dibatalkan.');
        }
        if ($baris['nilai_huruf'] !== null) {
            throw new DomainException("{$baris['nama_mk']} sudah dinilai dosen, tidak dapat dibatalkan.");
        }

        $this->krs->delete($krsId);

        return $baris;
    }

    private function pastikanPeriodeTerbuka(): void
    {
        if (! $this->periodeTerbuka()) {
            throw new DomainException(sprintf(
                'Masa pengisian KRS %s s.d. %s sudah ditutup.',
                $this->akademik->krsMulai,
                $this->akademik->krsSelesai,
            ));
        }
    }

    private function periodeBerjalan(array $baris): bool
    {
        return $baris['tahun_ajaran'] === $this->akademik->tahunAjaran
            && $baris['semester'] === $this->akademik->semester;
    }
}
