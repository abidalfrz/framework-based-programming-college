<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\DosenModel;
use App\Models\MahasiswaModel;
use App\Models\MatakuliahModel;
use App\Models\UserModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\RedirectResponse;
use Config\Database;

/**
 * CRUD dosen + akun login (username = NIDN, password awal = NIDN).
 */
class Dosen extends BaseController
{
    private DosenModel $dosen;

    public function __construct()
    {
        $this->dosen = new DosenModel();
    }

    public function index(): string
    {
        return view('admin/dosen/index', ['title' => 'Data Dosen', 'dosen' => $this->dosen->daftar()]);
    }

    public function new(): string
    {
        return view('admin/dosen/form', ['title' => 'Tambah Dosen', 'dosen' => null]);
    }

    public function edit(int $id): string
    {
        return view('admin/dosen/form', ['title' => 'Edit Dosen', 'dosen' => $this->temukan($id)]);
    }

    public function create(): RedirectResponse
    {
        $data = $this->input();
        if (! $this->validateData($data, $this->rules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $db = Database::connect();
        $db->transException(true)->transStart();
        $userId = (new UserModel())->insert([
            'username'      => $data['nidn'],
            'password_hash' => password_hash($data['nidn'], PASSWORD_DEFAULT),
            'nama'          => $data['nama'],
            'role'          => 'dosen',
        ]);
        $this->dosen->insert(['user_id' => $userId, 'nidn' => $data['nidn'], 'email' => $data['email']]);
        $db->transComplete();

        return redirect()->to('/admin/dosen')
            ->with('success', "Dosen {$data['nama']} ditambahkan. Login: {$data['nidn']} / password awal {$data['nidn']}.");
    }

    public function update(int $id): RedirectResponse
    {
        $lama = $this->temukan($id);
        $data = ['id' => $id, 'user_id' => $lama['user_id']] + $this->input();

        if (! $this->validateData($data, $this->rules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $db = Database::connect();
        $db->transException(true)->transStart();
        (new UserModel())->update($lama['user_id'], ['username' => $data['nidn'], 'nama' => $data['nama']]);
        $this->dosen->update($id, ['nidn' => $data['nidn'], 'email' => $data['email']]);
        $db->transComplete();

        return redirect()->to('/admin/dosen')->with('success', "Data {$data['nama']} diperbarui.");
    }

    public function delete(int $id): RedirectResponse
    {
        $dosen = $this->temukan($id);

        $jumlahMk   = (new MatakuliahModel())->where('dosen_id', $id)->countAllResults();
        $jumlahWali = (new MahasiswaModel())->where('dosen_wali_id', $id)->countAllResults();
        if ($jumlahMk > 0 || $jumlahWali > 0) {
            return redirect()->back()->with(
                'error',
                "{$dosen['nama']} masih mengampu {$jumlahMk} mata kuliah dan menjadi wali {$jumlahWali} mahasiswa. Pindahkan dulu sebelum menghapus.",
            );
        }

        (new UserModel())->delete($dosen['user_id']);

        return redirect()->to('/admin/dosen')->with('success', "Dosen {$dosen['nama']} dihapus.");
    }

    public function resetPassword(int $id): RedirectResponse
    {
        $dosen = $this->temukan($id);
        (new UserModel())->update($dosen['user_id'], ['password_hash' => password_hash($dosen['nidn'], PASSWORD_DEFAULT)]);

        return redirect()->back()->with('success', "Password {$dosen['nama']} direset menjadi NIDN ({$dosen['nidn']}).");
    }

    private function temukan(int $id): array
    {
        return $this->dosen->findLengkap($id)
            ?? throw PageNotFoundException::forPageNotFound('Dosen tidak ditemukan.');
    }

    /** @return array<string, string> */
    private function input(): array
    {
        $post = $this->request->getPost(['nidn', 'nama', 'email']);

        return array_map(static fn ($v): string => trim((string) $v), $post);
    }

    private function rules(): array
    {
        return [
            // id & user_id hanya ada saat update; dipakai placeholder is_unique agar data sendiri tidak dianggap duplikat.
            'id'      => 'permit_empty|is_natural_no_zero',
            'user_id' => 'permit_empty|is_natural_no_zero',
            'nidn'    => ['label' => 'NIDN', 'rules' => 'required|numeric|exact_length[10]|is_unique[dosen.nidn,id,{id}]|is_unique[users.username,id,{user_id}]'],
            'nama'    => ['label' => 'Nama', 'rules' => 'required|min_length[3]|max_length[100]'],
            'email'   => ['label' => 'Email', 'rules' => 'required|valid_email|max_length[100]'],
        ];
    }
}
