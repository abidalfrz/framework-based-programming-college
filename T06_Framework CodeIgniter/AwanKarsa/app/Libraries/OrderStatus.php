<?php

namespace App\Libraries;

/**
 * Alur status pesanan. Semua perubahan status wajib lewat izinkan() agar
 * tidak ada lompatan status yang tidak sah (mis. dibatalkan -> dikirim).
 */
final class OrderStatus
{
    public const MENUNGGU_PEMBAYARAN = 'menunggu_pembayaran';
    public const MENUNGGU_VERIFIKASI = 'menunggu_verifikasi';
    public const DIPROSES            = 'diproses';
    public const DIKIRIM             = 'dikirim';
    public const SELESAI             = 'selesai';
    public const DIBATALKAN          = 'dibatalkan';

    /** @var array<string, array{label: string, warna: string, ikon: string}> */
    private const INFO = [
        self::MENUNGGU_PEMBAYARAN => ['label' => 'Menunggu Pembayaran', 'warna' => 'warning', 'ikon' => 'wallet2'],
        self::MENUNGGU_VERIFIKASI => ['label' => 'Menunggu Verifikasi', 'warna' => 'info', 'ikon' => 'hourglass-split'],
        self::DIPROSES            => ['label' => 'Diproses', 'warna' => 'primary', 'ikon' => 'box-seam'],
        self::DIKIRIM             => ['label' => 'Sewa aktif', 'warna' => 'indigo', 'ikon' => 'server'],
        self::SELESAI             => ['label' => 'Sewa selesai', 'warna' => 'success', 'ikon' => 'check-circle'],
        self::DIBATALKAN          => ['label' => 'Dibatalkan', 'warna' => 'secondary', 'ikon' => 'x-circle'],
    ];

    /**
     * Transisi yang sah: status asal => [status tujuan => siapa yang boleh].
     *
     * @var array<string, array<string, list<string>>>
     */
    private const TRANSISI = [
        self::MENUNGGU_PEMBAYARAN => [
            self::MENUNGGU_VERIFIKASI => ['pembeli'],          // upload bukti bayar
            self::DIBATALKAN          => ['pembeli', 'admin'],
        ],
        self::MENUNGGU_VERIFIKASI => [
            self::DIPROSES            => ['admin'],            // pembayaran diterima
            self::MENUNGGU_PEMBAYARAN => ['admin'],            // bukti ditolak, upload ulang
            self::DIBATALKAN          => ['admin'],
        ],
        self::DIPROSES => [
            self::DIKIRIM    => ['admin'],                     // wajib isi resi
            self::DIBATALKAN => ['admin'],
        ],
        self::DIKIRIM => [
            self::SELESAI => ['admin'],             // pesanan diterima
        ],
        self::SELESAI    => [],
        self::DIBATALKAN => [],
    ];

    /** @return list<string> */
    public static function semua(): array
    {
        return array_keys(self::INFO);
    }

    public static function label(string $status): string
    {
        return self::INFO[$status]['label'] ?? $status;
    }

    public static function warna(string $status): string
    {
        return self::INFO[$status]['warna'] ?? 'secondary';
    }

    public static function ikon(string $status): string
    {
        return self::INFO[$status]['ikon'] ?? 'circle';
    }

    public static function izinkan(string $dari, string $ke, string $role): bool
    {
        return in_array($role, self::TRANSISI[$dari][$ke] ?? [], true);
    }

    /** @return list<string> status tujuan yang boleh dipilih role ini */
    public static function tujuan(string $dari, string $role): array
    {
        $hasil = [];
        foreach (self::TRANSISI[$dari] ?? [] as $ke => $roles) {
            if (in_array($role, $roles, true)) {
                $hasil[] = $ke;
            }
        }

        return $hasil;
    }

    /** Stok dikembalikan bila pesanan dibatalkan (stok dipotong saat checkout). */
    public static function kembalikanStok(string $ke): bool
    {
        return in_array($ke, [self::DIBATALKAN, self::SELESAI], true);
    }
}
