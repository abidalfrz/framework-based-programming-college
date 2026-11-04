<?php

namespace App\Controllers;

use App\Models\OrderModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Menampilkan gambar bukti bayar dari writable/ hanya untuk pemilik pesanan atau admin.
 */
class Bukti extends BaseController
{
    public function show(string $nama): ResponseInterface
    {
        $nama  = basename($nama);
        $order = (new OrderModel())->where('bukti_bayar', $nama)->first();
        $boleh = $order !== null
            && (session('role') === 'admin' || (int) $order['user_id'] === (int) session('user_id'));
        $path  = Pesanan::FOLDER_BUKTI . $nama;

        if (! $boleh || ! is_file($path)) {
            throw PageNotFoundException::forPageNotFound();
        }

        return $this->response
            ->setHeader('Content-Type', mime_content_type($path) ?: 'application/octet-stream')
            ->setHeader('Cache-Control', 'private, max-age=600')
            ->setHeader('X-Content-Type-Options', 'nosniff')
            ->setBody((string) file_get_contents($path));
    }
}
