<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\KategoriModel;
use App\Models\OrderItemModel;
use App\Models\ProdukModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\RedirectResponse;

class Produk extends BaseController
{
    private const FOLDER = FCPATH . 'uploads/produk/';

    private const FOTO_RULES = ['foto' => [
        'label' => 'Foto produk',
        'rules' => 'is_image[foto]|mime_in[foto,image/jpeg,image/png,image/webp]|ext_in[foto,jpg,jpeg,png,webp]|max_size[foto,2048]',
    ]];

    private ProdukModel $produk;

    public function __construct()
    {
        $this->produk = new ProdukModel();
    }

    public function index(): string
    {
        $q = trim((string) $this->request->getGet('q'));
        $this->produk->withKategori();
        if ($q !== '') {
            $this->produk->like('produk.nama', $q);
        }

        return view('admin/produk/index', [
            'title'  => 'Produk',
            'produk' => $this->produk->orderBy('produk.nama')->paginate(10),
            'pager'  => $this->produk->pager,
            'q'      => $q,
        ]);
    }

    public function new(): string
    {
        return $this->form(null);
    }

    public function edit(int $id): string
    {
        return $this->form($this->temukan($id));
    }

    public function create(): RedirectResponse
    {
        if (($error = $this->validasiFoto()) !== null) {
            return $error;
        }

        $data = $this->input();
        if (! $this->produk->validate($data)) {
            return redirect()->back()->withInput()->with('errors', $this->produk->errors());
        }

        $data['foto'] = $this->simpanFoto();
        $this->produk->insert($data, false);

        return redirect()->to('/admin/produk')->with('success', "Produk {$data['nama']} ditambahkan.");
    }

    public function update(int $id): RedirectResponse
    {
        $lama = $this->temukan($id);
        if (($error = $this->validasiFoto()) !== null) {
            return $error;
        }

        $data = ['id' => $id] + $this->input();
        if (! $this->produk->validate($data)) {
            return redirect()->back()->withInput()->with('errors', $this->produk->errors());
        }

        $fotoBaru = $this->simpanFoto();
        if ($fotoBaru !== null || $this->request->getPost('hapus_foto')) {
            $this->hapusFoto($lama['foto']);
            $data['foto'] = $fotoBaru;
        }
        $this->produk->update($id, $data);

        return redirect()->to('/admin/produk')->with('success', "Produk {$data['nama']} diperbarui.");
    }

    public function delete(int $id): RedirectResponse
    {
        $produk = $this->temukan($id);

        // Produk yang sudah pernah dipesan hanya boleh dinonaktifkan agar riwayat pesanan tetap utuh.
        if ((new OrderItemModel())->where('produk_id', $id)->countAllResults() > 0) {
            $this->produk->update($id, ['aktif' => 0]);

            return redirect()->to('/admin/produk')->with('success', "{$produk['nama']} sudah pernah dipesan, jadi dinonaktifkan (tidak tampil di toko).");
        }

        $this->produk->delete($id);
        $this->hapusFoto($produk['foto']);

        return redirect()->to('/admin/produk')->with('success', "Produk {$produk['nama']} dihapus.");
    }

    private function form(?array $produk): string
    {
        return view('admin/produk/form', [
            'title'    => $produk === null ? 'Tambah Produk' : 'Edit Produk',
            'produk'   => $produk,
            'kategori' => (new KategoriModel())->orderBy('nama')->findAll(),
        ]);
    }

    private function temukan(int $id): array
    {
        return $this->produk->find($id) ?? throw PageNotFoundException::forPageNotFound('Produk tidak ditemukan.');
    }

    private function input(): array
    {
        $post = $this->request->getPost(['kategori_id', 'nama', 'deskripsi', 'harga', 'stok']);
        $data = array_map(static fn ($v): string => trim((string) $v), $post);
        // Harga boleh diketik "125.000"
        $data['harga'] = preg_replace('/\D/', '', $data['harga']);
        $data['aktif'] = $this->request->getPost('aktif') ? '1' : '0';

        return $data;
    }

    /** Foto opsional; jika ada, divalidasi sebelum data lain disimpan. */
    private function validasiFoto(): ?RedirectResponse
    {
        $file = $this->request->getFile('foto');
        if ($file === null || $file->getError() === UPLOAD_ERR_NO_FILE) {
            return null;
        }
        if (! $this->validate(self::FOTO_RULES)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        return null;
    }

    private function simpanFoto(): ?string
    {
        $file = $this->request->getFile('foto');
        if ($file === null || ! $file->isValid() || $file->hasMoved()) {
            return null;
        }
        $nama = $file->getRandomName();
        $file->move(self::FOLDER, $nama);

        return $nama;
    }

    private function hapusFoto(?string $foto): void
    {
        if ($foto !== null && $foto !== '' && is_file(self::FOLDER . basename($foto))) {
            unlink(self::FOLDER . basename($foto));
        }
    }
}
