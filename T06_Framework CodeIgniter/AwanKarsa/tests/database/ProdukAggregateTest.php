<?php

namespace Tests\Database;

use App\Libraries\DemoSeedGuard;
use App\Models\ProdukModel;
use CodeIgniter\Test\CIUnitTestCase;

/** Opt-in, read-only verification against the actual local MySQL project DB. */
final class ProdukAggregateTest extends CIUnitTestCase
{
    public function testCancelledOrdersDoNotCountAsSales(): void
    {
        if (getenv('PBKK_MYSQL_ACCEPTANCE') !== '1') {
            $this->markTestSkipped('Run PBKK_MYSQL_ACCEPTANCE=1 vendor/bin/phpunit tests/database/ProdukAggregateTest.php for read-only MySQL verification.');
        }
        $db = DemoSeedGuard::connection();
        $actual = (new ProdukModel($db))->terlaris(1000);
        $expected = $db->query("SELECT p.id, COALESCE(SUM(i.qty), 0) AS terjual
            FROM produk p LEFT JOIN
                (SELECT oi.produk_id, oi.qty FROM order_items oi
                 JOIN orders o ON o.id=oi.order_id WHERE o.status <> 'dibatalkan') i
            ON i.produk_id=p.id WHERE p.aktif=1
            GROUP BY p.id ORDER BY terjual DESC, p.id ASC")->getResultArray();
        $pairs = static fn (array $rows): array => array_map(
            static fn (array $row): array => [(int) $row['id'], (int) $row['terjual']], $rows,
        );
        $this->assertSame($pairs($expected), $pairs($actual));
        $this->assertNotEmpty($actual);
    }
}
