<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\DosenModel;
use App\Models\KrsModel;
use App\Models\MatakuliahModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\RedirectResponse;

class Matakuliah extends BaseController
{
    private const FIELDS = ['kode', 'nama', 'sks', 'semester', 'dosen_id', 'kuota'];

    private MatakuliahModel $matakuliah;

    public function __construct()
    {
        $this->matakuliah = new MatakuliahModel();
    }

    public function index(): string
    {
        return view('admin/matakuliah/index', ['title' => 'Mata Kuliah', 'matakuliah' => $this->matakuliah->semua()]);
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
        $data         = $this->input();
        $data['kode'] = strtoupper($data['kode']);

        if ($this->matakuliah->insert($data) === false) {
            return redirect()->back()->withInput()->with('errors', $this->matakuliah->errors());
        }

        return redirect()->to('/admin/matakuliah')->with('success', "Mata kuliah {$data['kode']} {$data['nama']} ditambahkan.");
    }

    public function update(int $id): RedirectResponse
    {
        $this->temukan($id);
        $data         = ['id' => $id] + $this->input();
        $data['kode'] = strtoupper($data['kode']);

        if ($this->matakuliah->update($id, $data) === false) {
            return redirect()->back()->withInput()->with('errors', $this->matakuliah->errors());
        }

        return redirect()->to('/admin/matakuliah')->with('success', "Mata kuliah {$data['kode']} diperbarui.");
    }

    public function delete(int $id): RedirectResponse
    {
        $mk = $this->temukan($id);

        if ((new KrsModel())->where('matakuliah_id', $id)->countAllResults() > 0) {
            return redirect()->back()->with('error', "{$mk['kode']} {$mk['nama']} sudah pernah diambil mahasiswa sehingga tidak dapat dihapus.");
        }

        $this->matakuliah->delete($id);

        return redirect()->to('/admin/matakuliah')->with('success', "Mata kuliah {$mk['kode']} dihapus.");
    }

    private function form(?array $mk): string
    {
        return view('admin/matakuliah/form', [
            'title' => $mk === null ? 'Tambah Mata Kuliah' : 'Edit Mata Kuliah',
            'mk'    => $mk,
            'dosen' => (new DosenModel())->pilihan(),
        ]);
    }

    private function temukan(int $id): array
    {
        return $this->matakuliah->find($id)
            ?? throw PageNotFoundException::forPageNotFound('Mata kuliah tidak ditemukan.');
    }

    /** @return array<string, string> */
    private function input(): array
    {
        return array_map(static fn ($v): string => trim((string) $v), $this->request->getPost(self::FIELDS));
    }
}
