<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\FasilitasModel;
use App\Models\KategoriModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\Files\UploadedFile;
use CodeIgniter\HTTP\RedirectResponse;

class Fasilitas extends BaseController
{
    private const FOTO_DIR = FCPATH . 'uploads/fasilitas/';

    private const FOTO_RULES = [
        'label' => 'Foto Fasilitas',
        'rules' => 'uploaded[foto]'
            . '|is_image[foto]'
            . '|mime_in[foto,image/jpg,image/jpeg,image/png,image/webp]'
            . '|ext_in[foto,jpg,jpeg,png,webp]'
            . '|max_size[foto,2048]',
    ];

    private FasilitasModel $model;

    public function __construct()
    {
        $this->model = new FasilitasModel();
    }

    public function index(): string
    {
        $keyword    = trim((string) $this->request->getGet('q'));
        $kategori   = $this->request->getGet('kategori');
        $kategoriId = ctype_digit((string) $kategori) ? (int) $kategori : null;
        $status     = trim((string) $this->request->getGet('status'));

        return view('admin/fasilitas/index', [
            'title'        => 'Kelola Data Fasilitas',
            'fasilitas'    => $this->model->cari($keyword, $kategoriId, $status ?: null, 10),
            'pager'        => $this->model->pager,
            'keyword'      => $keyword,
            'kategoriId'   => $kategoriId,
            'status'       => $status,
            'kategoriList' => $this->daftarKategori(),
        ]);
    }

    public function new(): string
    {
        return view('admin/fasilitas/form', [
            'title'        => 'Tambah Fasilitas Baru',
            'fasilitas'    => null,
            'kategoriList' => $this->daftarKategori(),
        ]);
    }

    public function create(): RedirectResponse
    {
        $data = $this->ambilInput();

        if (! $this->model->validate($data)) {
            return $this->gagal($this->model->errors());
        }

        $foto = $this->request->getFile('foto');
        if ($this->adaFile($foto)) {
            if (! $this->validate(['foto' => self::FOTO_RULES])) {
                return $this->gagal($this->validator->getErrors());
            }
            $data['foto'] = $this->simpanFoto($foto);
        }

        $this->model->insert($data, false);

        return redirect()->to('/admin/fasilitas')->with('success', "Fasilitas '{$data['nama']}' berhasil ditambahkan.");
    }

    public function edit(int $id): string
    {
        return view('admin/fasilitas/form', [
            'title'        => 'Edit Data Fasilitas',
            'fasilitas'    => $this->cariAtau404($id),
            'kategoriList' => $this->daftarKategori(),
        ]);
    }

    public function update(int $id): RedirectResponse
    {
        $lama = $this->cariAtau404($id);
        $data = ['id' => $id] + $this->ambilInput();

        if (! $this->model->validate($data)) {
            return $this->gagal($this->model->errors());
        }

        $foto = $this->request->getFile('foto');
        if ($this->adaFile($foto)) {
            if (! $this->validate(['foto' => self::FOTO_RULES])) {
                return $this->gagal($this->validator->getErrors());
            }
            $data['foto'] = $this->simpanFoto($foto);
            $this->hapusFoto($lama['foto']);
        } elseif ($this->request->getPost('hapus_foto') === '1') {
            $data['foto'] = null;
            $this->hapusFoto($lama['foto']);
        }

        unset($data['id']);
        $this->model->skipValidation(true)->update($id, $data);

        return redirect()->to('/admin/fasilitas')->with('success', "Data fasilitas '{$data['nama']}' berhasil diperbarui.");
    }

    public function delete(int $id): RedirectResponse
    {
        $fasilitas = $this->cariAtau404($id);

        $this->model->delete($id);
        $this->hapusFoto($fasilitas['foto']);

        return redirect()->to('/admin/fasilitas')->with('success', "Fasilitas '{$fasilitas['nama']}' berhasil dihapus permanen.");
    }

    // ------------------------------------------------------------------

    private function ambilInput(): array
    {
        $fields = ['kode', 'nama', 'kategori_id', 'lokasi', 'kapasitas', 'spesifikasi', 'status', 'penanggung_jawab'];
        $input  = $this->request->getPost($fields);

        return array_map(static fn ($v) => is_string($v) ? trim($v) : $v, $input);
    }

    private function gagal(array $errors): RedirectResponse
    {
        return redirect()->back()->withInput()->with('errors', $errors);
    }

    private function cariAtau404(int $id): array
    {
        $item = $this->model->find($id);
        if ($item === null) {
            throw PageNotFoundException::forPageNotFound('Data fasilitas tidak ditemukan.');
        }

        return $item;
    }

    private function daftarKategori(): array
    {
        return (new KategoriModel())->orderBy('nama')->findAll();
    }

    private function adaFile(?UploadedFile $file): bool
    {
        return $file !== null && $file->getError() !== UPLOAD_ERR_NO_FILE;
    }

    private function simpanFoto(UploadedFile $file): string
    {
        if (! is_dir(self::FOTO_DIR)) {
            mkdir(self::FOTO_DIR, 0777, true);
        }

        $nama = $file->getRandomName();
        $file->move(self::FOTO_DIR, $nama);

        return $nama;
    }

    private function hapusFoto(?string $nama): void
    {
        if ($nama === null || $nama === '') {
            return;
        }

        $path = self::FOTO_DIR . basename($nama);
        if (is_file($path)) {
            unlink($path);
        }
    }
}

