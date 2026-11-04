<?php

namespace Tests\Unit;

use App\Libraries\DemoSeedGuard;
use CodeIgniter\Test\CIUnitTestCase;
use RuntimeException;

/** Rejection tests do not open a database connection. */
final class SetupSafetyTest extends CIUnitTestCase
{
    public function testUnrelatedDatabasesAreRejectedBeforeConnecting(): void
    {
        $config = config('Database');
        $original = $config->default;
        try {
            foreach (['lms', 'phpmyadmin', 'mysql', 'sys', 'unrelated', ''] as $database) {
                $config->default['database'] = $database;
                try {
                    DemoSeedGuard::connection();
                    $this->fail('An unrelated database was accepted.');
                } catch (RuntimeException $error) {
                    $this->assertStringContainsString('Setup ditolak', $error->getMessage());
                }
            }
        } finally {
            $config->default = $original;
        }
    }

    public function testRemoteDatabaseIsRejectedBeforeConnecting(): void
    {
        $config = config('Database');
        $original = $config->default;
        try {
            $config->default['database'] = DemoSeedGuard::DATABASE;
            $config->default['hostname'] = 'remote.invalid';
            $this->expectException(RuntimeException::class);
            $this->expectExceptionMessage('Setup ditolak');
            DemoSeedGuard::connection();
        } finally {
            $config->default = $original;
        }
    }
}
