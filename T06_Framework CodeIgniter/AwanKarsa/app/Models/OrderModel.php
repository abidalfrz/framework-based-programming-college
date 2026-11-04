<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderModel extends Model
{
    protected $table         = 'orders';
    protected $returnType    = 'array';
    protected $allowedFields = [
        'kode', 'user_id', 'status', 'subtotal', 'ongkir', 'total', 'nama_penerima', 'telepon',
        'alamat', 'catatan', 'bukti_bayar', 'resi', 'catatan_admin', 'durasi_bulan', 'mulai_sewa', 'akhir_sewa',
    ];
    protected $useTimestamps = true;

    /** Kode pesanan unik, contoh KM261102-7F3A. */
    public function kodeBaru(): string
    {
        do {
            $kode = 'AK' . date('ymd') . '-' . strtoupper(bin2hex(random_bytes(2)));
        } while ($this->where('kode', $kode)->countAllResults() > 0);

        return $kode;
    }

    public function withPembeli(): static
    {
        return $this->select('orders.*, u.nama AS nama_pembeli, u.email')
            ->join('users u', 'u.id = orders.user_id');
    }

    public function milik(int $userId): array
    {
        return $this->where('user_id', $userId)->orderBy('created_at', 'DESC')->orderBy('id', 'DESC')->findAll();
    }

    public function byKodeMilik(string $kode, int $userId): ?array
    {
        return $this->where(['kode' => $kode, 'user_id' => $userId])->first();
    }

    public function daftarAdmin(?string $status, int $perPage = 10): array
    {
        $this->withPembeli();
        if ($status !== null && $status !== '') {
            $this->where('orders.status', $status);
        }

        return $this->orderBy('orders.created_at', 'DESC')->orderBy('orders.id', 'DESC')->paginate($perPage);
    }

    /** @return array<string, int> status => jumlah */
    public function jumlahPerStatus(): array
    {
        $hasil = [];
        foreach ($this->select('status, COUNT(*) AS jumlah')->groupBy('status')->findAll() as $r) {
            $hasil[$r['status']] = (int) $r['jumlah'];
        }

        return $hasil;
    }

    /** Omzet harian 7 hari terakhir dari pesanan yang sudah dibayar. */
    public function omzetHarian(int $hari = 7): array
    {
        $mulai = date('Y-m-d', strtotime('-' . ($hari - 1) . ' days'));
        $rows  = $this->select('DATE(created_at) AS tanggal, SUM(total) AS omzet')
            ->whereIn('status', ['diproses', 'dikirim', 'selesai'])
            ->where('created_at >=', $mulai . ' 00:00:00')
            ->groupBy('DATE(created_at)')
            ->findAll();
        $peta = array_column($rows, 'omzet', 'tanggal');

        $hasil = [];
        for ($i = 0; $i < $hari; $i++) {
            $tgl         = date('Y-m-d', strtotime("{$mulai} +{$i} days"));
            $hasil[$tgl] = (int) ($peta[$tgl] ?? 0);
        }

        return $hasil;
    }
}
