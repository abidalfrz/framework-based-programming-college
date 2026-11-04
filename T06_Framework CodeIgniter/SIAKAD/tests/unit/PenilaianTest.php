<?php

namespace Tests\Unit;

use App\Libraries\Penilaian;
use CodeIgniter\Test\CIUnitTestCase;
use Config\Akademik;
use InvalidArgumentException;

/**
 * @internal
 */
final class PenilaianTest extends CIUnitTestCase
{
    private Penilaian $penilaian;

    protected function setUp(): void
    {
        parent::setUp();
        $this->penilaian = new Penilaian(new Akademik());
    }

    public function testKonversiHurufPadaBatasSkala(): void
    {
        $this->assertSame('A', $this->penilaian->huruf(85));
        $this->assertSame('AB', $this->penilaian->huruf(84.99));
        $this->assertSame('B', $this->penilaian->huruf(70));
        $this->assertSame('BC', $this->penilaian->huruf(65));
        $this->assertSame('C', $this->penilaian->huruf(55));
        $this->assertSame('D', $this->penilaian->huruf(40));
        $this->assertSame('E', $this->penilaian->huruf(0));
    }

    public function testNilaiDiLuarRentangDitolak(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->penilaian->huruf(100.5);
    }

    public function testIpMengabaikanMataKuliahBelumDinilai(): void
    {
        $baris = [
            ['sks' => 3, 'nilai_huruf' => 'A'],  // 12
            ['sks' => 4, 'nilai_huruf' => 'B'],  // 12
            ['sks' => 2, 'nilai_huruf' => null], // diabaikan
        ];

        $this->assertSame(3.43, $this->penilaian->ip($baris)); // 24 / 7
        $this->assertSame(7, $this->penilaian->totalSks($baris));
    }

    public function testIpkMemakaiNilaiTerbaikMataKuliahYangDiulang(): void
    {
        $baris = [
            ['matakuliah_id' => 1, 'sks' => 3, 'nilai_huruf' => 'D'],
            ['matakuliah_id' => 2, 'sks' => 3, 'nilai_huruf' => 'B'],
            ['matakuliah_id' => 1, 'sks' => 3, 'nilai_huruf' => 'A'], // mengulang
        ];

        $this->assertSame(3.5, $this->penilaian->ipk($baris)); // (12 + 9) / 6
    }

    public function testLulusMinimalC(): void
    {
        $this->assertTrue($this->penilaian->lulus('C'));
        $this->assertFalse($this->penilaian->lulus('D'));
        $this->assertFalse($this->penilaian->lulus(null));
    }

    public function testBatasSksBerdasarkanIpSemesterLalu(): void
    {
        $this->assertSame(20, $this->penilaian->maxSks(null));
        $this->assertSame(24, $this->penilaian->maxSks(3.00));
        $this->assertSame(21, $this->penilaian->maxSks(2.99));
        $this->assertSame(18, $this->penilaian->maxSks(2.00));
        $this->assertSame(15, $this->penilaian->maxSks(1.87));
    }

    public function testRingkasanPerPeriodeMenghitungIpkKumulatif(): void
    {
        $riwayat = [
            ['tahun_ajaran' => '2025/2026', 'semester' => 'ganjil', 'matakuliah_id' => 1, 'sks' => 4, 'nilai_huruf' => 'A'],
            ['tahun_ajaran' => '2025/2026', 'semester' => 'genap', 'matakuliah_id' => 2, 'sks' => 4, 'nilai_huruf' => 'C'],
            ['tahun_ajaran' => '2026/2027', 'semester' => 'ganjil', 'matakuliah_id' => 3, 'sks' => 3, 'nilai_huruf' => null],
        ];

        $hasil = $this->penilaian->perPeriode($riwayat);

        $this->assertCount(3, $hasil);
        $this->assertSame('Genap 2025/2026', $hasil[1]['label']);
        $this->assertSame(2.0, $hasil[1]['ip']);
        $this->assertSame(3.0, $hasil[1]['ipk']);
        $this->assertFalse($hasil[2]['dinilai']);
    }
}
