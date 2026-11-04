<?php

namespace Tests\Unit;

use App\Libraries\KrsService;
use App\Models\KrsModel;
use App\Models\MatakuliahModel;
use CodeIgniter\Config\Factories;
use CodeIgniter\I18n\Time;
use CodeIgniter\Test\CIUnitTestCase;
use Config\Akademik;
use DomainException;

final class KrsPeriodTest extends CIUnitTestCase
{
    private function serviceAt(string $date, ?Akademik $config = null): KrsService
    {
        Factories::injectMock('models', KrsModel::class, $this->createMock(KrsModel::class));
        Factories::injectMock('models', MatakuliahModel::class, $this->createMock(MatakuliahModel::class));
        return new KrsService($config ?? new Akademik(), Time::parse($date, 'Asia/Jakarta'));
    }

    public function testOpeningAndClosingDaysAreInclusive(): void
    {
        $this->assertTrue($this->serviceAt('2026-08-01 00:00:00')->periodeTerbuka());
        $this->assertTrue($this->serviceAt('2026-11-30 23:59:59')->periodeTerbuka());
        $this->assertFalse($this->serviceAt('2026-07-31 23:59:59')->periodeTerbuka());
        $this->assertFalse($this->serviceAt('2026-12-01 00:00:00')->periodeTerbuka());
    }

    public function testSemesterParity(): void
    {
        $service = $this->serviceAt('2026-11-03 12:00:00');
        $this->assertTrue($service->ditawarkan(['semester' => 1]));
        $this->assertTrue($service->ditawarkan(['semester' => 7]));
        $this->assertFalse($service->ditawarkan(['semester' => 2]));
        $config = new Akademik();
        $config->semester = 'genap';
        $service = $this->serviceAt('2026-11-03 12:00:00', $config);
        $this->assertTrue($service->ditawarkan(['semester' => 8]));
        $this->assertFalse($service->ditawarkan(['semester' => 3]));
    }

    public function testClosedPeriodRejectsRegistrationBeforeDatabaseAccess(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('sudah ditutup');
        $this->serviceAt('2026-12-01 00:00:00')->ambil(1, 1);
    }

    public function testClosedPeriodRejectsCancellationBeforeDatabaseAccess(): void
    {
        $this->expectException(DomainException::class);
        $this->serviceAt('2026-07-31 00:00:00')->batal(1, 1);
    }
}
