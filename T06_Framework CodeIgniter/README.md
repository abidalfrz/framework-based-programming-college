# Implementasi Framework Code Igniter

Nama: Muhammad Abid Baihaqi Al Faridzi

NRP: 5025241133

Prodi: Teknik Informatika

Mata Kuliah: Pemrograman Berbasis Kerangka Kerja

Kelas: D

---

# Overview 

Tugas ini mengimplementasikan konsep framework web PHP menggunakan **CodeIgniter 4** yang mengacu pada silabus **Latihan 1 dan Latihan 2**:

| Bagian | Nama Aplikasi | Deskripsi & Ruang Lingkup | Teknologi Utama | 
|---|---|---|:---:|
| **Latihan 1** | [**UniSpace**](UniSpace/) | **Smart Campus Facility & Asset Directory**:<br>Katalog fasilitas, ruang kuliah, lab riset & alat multimedia kampus. Memuat fitur pencarian, filter kategori & status ketersediaan, upload foto, validasi input, kontak/aduan sarpras, serta panel admin petugas (SB Admin 2) dengan kontrol hak akses (RBAC). | CodeIgniter 4, Bootstrap 5, SB Admin 2, MySQL | 
| **Latihan 2a** | [**SIAKAD**](SIAKAD/) | **Sistem Informasi Akademik Terpadu**:<br>Pengelolaan perkuliahan terpadu multi-role (Admin, Dosen, Mahasiswa). Fitur: Rencana Studi (KRS), validasi kuota & batas SKS dinamis, input nilai dosen dengan konversi huruf/bobot otomatis, KHS, grafik perkembangan IPK, transkrip nilai, dan cetak dokumen resmi. | CodeIgniter 4, SB Admin 2 Modernized, MySQL |
| **Latihan 2b** | [**AwanKarsa**](AwanKarsa/) | **Academic Cloud & VPS Hub**:<br>Marketplace penyewaan VPS, server riset AI/GPU, dan cloud hosting kampus dengan alur checkout keranjang dan verifikasi pembayaran manual. | CodeIgniter 4, MySQL, Bootstrap | 

---

## How to Run Each Application

> Buat database kosong tiap project, lalu atur kredensial pada `.env` sebelum menjalankan:

### 1. UniSpace (Port 8080)
```bash

docker exec -i mysql-pbkk mysql -u root -ppassword -e "CREATE DATABASE IF NOT EXISTS unispace;"

cd UniSpace
cp .env.example .env

php spark migrate
php spark db:seed DatabaseSeeder
php spark serve --host 127.0.0.1 --port 8080
```
* **URL**: `http://localhost:8080`
* **Demo Admin**: `admin` / `admin123`
* **Demo Staff**: `staff` / `staff123`

### 2. SIAKAD (Port 8081)
```bash

docker exec -i mysql-pbkk mysql -u root -ppassword -e "CREATE DATABASE IF NOT EXISTS siakad;"

cd SIAKAD
cp .env.example .env
php spark migrate
php spark db:seed DatabaseSeeder
php spark serve --host 127.0.0.1 --port 8081
```
* **URL**: `http://localhost:8081`
* **Demo Admin**: `admin` / `admin123`
* **Demo Dosen**: `0011018501` / `0011018501` (NIDN)
* **Demo Mahasiswa**: `5025251001` / `5025251001` (NIM)

### 3. AwanKarsa (Port 8082)

```bash

docker exec -i mysql-pbkk mysql -u root -ppassword -e "CREATE DATABASE IF NOT EXISTS awankarsa;"

cd AwanKarsa
cp .env.example .env
composer install
php spark project:setup --seed
php spark serve --host 127.0.0.1 --port 8082
```

* **URL**: `http://localhost:8082`
* **Demo Admin**: `admin@awankarsa.test` / `admin123`
* **Demo Pembeli**: `budi@mail.test` / `pembeli123`
