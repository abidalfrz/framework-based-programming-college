<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\DosenModel;
use App\Models\KrsModel;
use App\Models\MahasiswaModel;
use App\Models\UserModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\RedirectResponse;
use Config\Database;

/**
 * CRUD mahasiswa. Setiap mahasiswa sekaligus dibuatkan akun login
 * (username = NIM, password awal = NIM) dalam satu transaksi.
 */
class Mahasiswa extends BaseController
{
    private MahasiswaModel $mahasiswa;

    public function __construct()
    {
        $this->mahasiswa = new MahasiswaModel();
    }

    public function index(): string
    {
        $keyword = trim((string) $this->request->getGet('q'));

        return view('admin/mahasiswa/index', [
            'title'     => 'Data Mahasiswa',
            'mahasiswa' => $this->mahasiswa->cari($keyword, 10),
            'pager'     => $this->mahasiswa->pager,
            'keyword'   => $keyword,
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
        $data = $this->input();
        if (! $this->validateData($data, $this->rules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $db = Database::connect();
        $db->transException(true)->transStart();
        $userId = (new UserModel())->insert([
            'username'      => $data['nim'],
            'password_hash' => password_hash($data['nim'], PASSWORD_DEFAULT),
            'nama'          => $data['nama'],
            'role'          => 'mahasiswa',
        ]);
        $this->mahasiswa->insert([
            'user_id'       => $userId,
            'nim'           => $data['nim'],
            'email'         => $data['email'],
            'angkatan'      => $data['angkatan'],
            'dosen_wali_id' => $data['dosen_wali_id'],
        ]);
        $db->transComplete();

        return redirect()->to('/admin/mahasiswa')
            ->with('success', "Mahasiswa {$data['nama']} ditambahkan. Login: {$data['nim']} / password awal {$data['nim']}.");
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
        (new UserModel())->update($lama['user_id'], ['username' => $data['nim'], 'nama' => $data['nama']]);
        $this->mahasiswa->update($id, [
            'nim'           => $data['nim'],
            'email'         => $data['email'],
            'angkatan'      => $data['angkatan'],
            'dosen_wali_id' => $data['dosen_wali_id'],
        ]);
        $db->transComplete();

        return redirect()->to('/admin/mahasiswa')->with('success', "Data {$data['nama']} diperbarui.");
    }

    public function delete(int $id): RedirectResponse
    {
        $mhs = $this->temukan($id);

        $punyaNilai = (new KrsModel())->where('mahasiswa_id', $id)->where('nilai_huruf IS NOT NULL')->countAllResults() > 0;
        if ($punyaNilai) {
            return redirect()->back()->with('error', "{$mhs['nama']} sudah memiliki riwayat nilai sehingga tidak dapat dihapus.");
        }

        // Menghapus user ikut menghapus mahasiswa & KRS-nya (ON DELETE CASCADE).
        (new UserModel())->delete($mhs['user_id']);

        return redirect()->to('/admin/mahasiswa')->with('success', "Mahasiswa {$mhs['nama']} dihapus.");
    }

    public function resetPassword(int $id): RedirectResponse
    {
        $mhs = $this->temukan($id);
        (new UserModel())->update($mhs['user_id'], ['password_hash' => password_hash($mhs['nim'], PASSWORD_DEFAULT)]);

        return redirect()->back()->with('success', "Password {$mhs['nama']} direset menjadi NIM ({$mhs['nim']}).");
    }

    private function form(?array $mahasiswa): string
    {
        return view('admin/mahasiswa/form', [
            'title'     => $mahasiswa === null ? 'Tambah Mahasiswa' : 'Edit Mahasiswa',
            'mahasiswa' => $mahasiswa,
            'dosen'     => (new DosenModel())->pilihan(),
        ]);
    }

    private function temukan(int $id): array
    {
        return $this->mahasiswa->findLengkap($id)
            ?? throw PageNotFoundException::forPageNotFound('Mahasiswa tidak ditemukan.');
    }

    /** @return array<string, string> */
    private function input(): array
    {
        $post = $this->request->getPost(['nim', 'nama', 'email', 'angkatan', 'dosen_wali_id']);

        return array_map(static fn ($v): string => trim((string) $v), $post);
    }

    private function rules(): array
    {
        return [
            // id & user_id hanya ada saat update; dipakai placeholder is_unique agar data sendiri tidak dianggap duplikat.
            'id'            => 'permit_empty|is_natural_no_zero',
            'user_id'       => 'permit_empty|is_natural_no_zero',
            'nim'           => ['label' => 'NIM', 'rules' => 'required|numeric|exact_length[10]|is_unique[mahasiswa.nim,id,{id}]|is_unique[users.username,id,{user_id}]'],
            'nama'          => ['label' => 'Nama', 'rules' => 'required|min_length[3]|max_length[100]'],
            'email'         => ['label' => 'Email', 'rules' => 'required|valid_email|max_length[100]'],
            'angkatan'      => ['label' => 'Angkatan', 'rules' => 'required|integer|greater_than_equal_to[2000]|less_than_equal_to[2100]'],
            'dosen_wali_id' => ['label' => 'Dosen wali', 'rules' => 'required|is_not_unique[dosen.id]'],
        ];
    }
}
