

**STUDI KASUS PROJECT**  
**SHOPEASE – ONLINE STORE**

Pengembangan Aplikasi Toko Online  
Menggunakan CodeIgniter 4 dan MySQL

**Mata Kuliah: Web Framework**

Dokumen Studi Kasus dan Panduan Project-Based Learning

# **1\. Identitas Studi Kasus**

## **ShopEase – Online Store**

ShopEase merupakan aplikasi toko online untuk menjual berbagai produk secara digital. Pelanggan dapat melihat katalog, mencari produk, memasukkan barang ke keranjang, melakukan pemesanan, mengunggah bukti pembayaran, dan memantau status transaksi. Administrator mengelola produk, kategori, pelanggan, pesanan, pembayaran, pengiriman, dan laporan penjualan.

| Komponen | Teknologi |
| :---- | :---- |
| Framework | CodeIgniter 4 |
| Database | MySQL |
| Frontend | HTML, CSS, Bootstrap 5, JavaScript |
| Bahasa pemrograman | PHP 8 atau versi yang kompatibel dengan CI4 |
| Akses database | CodeIgniter Model dan Query Builder |
| Arsitektur | Model–View–Controller (MVC) |
| Tools | Visual Studio Code, Composer, XAMPP/Laragon, browser |

# **2\. Tujuan Pembelajaran**

* Memahami konsep MVC pada CodeIgniter 4\.  
* Mengonfigurasi koneksi aplikasi dengan database MySQL.  
* Mengimplementasikan routing, controller, model, dan view.  
* Membangun fitur Create, Read, Update, dan Delete (CRUD).  
* Mengimplementasikan autentikasi, session, dan otorisasi berdasarkan role.  
* Mengelola relasi antar tabel dan transaksi database.  
* Mengembangkan fitur katalog, keranjang, checkout, dan manajemen pesanan.  
* Menerapkan validasi input dan keamanan dasar aplikasi web.  
* Melakukan pengujian fungsional dan menyiapkan deployment aplikasi.

# **3\. Aktor dan Hak Akses**

| Aktor | Hak Akses dan Aktivitas |
| :---- | :---- |
| Administrator | Login ke dashboard; mengelola kategori dan produk; mengelola stok; melihat pelanggan; memproses pesanan; memverifikasi pembayaran; mengelola pengiriman; melihat laporan. |
| Customer | Registrasi dan login; melihat katalog dan detail produk; mencari dan memfilter produk; mengelola keranjang; checkout; mengunggah bukti pembayaran; melihat riwayat dan status pesanan. |
| Operator Toko | Melihat pesanan masuk; memproses pesanan; memperbarui status pemrosesan dan pengiriman; memperbarui stok sesuai kewenangan. |

# **4\. Spesifikasi Fitur Aplikasi**

## **4.1 Frontend – Customer**

| Modul | Fitur Utama |
| :---- | :---- |
| Homepage | Banner promosi, kategori produk, produk terbaru, produk terlaris, navigasi, dan tautan ke halaman katalog. |
| Product Catalog | Daftar produk, filter kategori, pencarian, pengurutan harga, dan pagination. |
| Product Detail | Foto, nama, deskripsi, harga, informasi stok, dan tombol tambah ke keranjang. |
| Shopping Cart | Menambah item, mengubah kuantitas, menghapus item, melihat harga satuan, dan menghitung subtotal. |
| Checkout | Memilih alamat, memeriksa ringkasan pesanan, menghitung total, dan membuat pesanan. |
| Order Management | Melihat riwayat pesanan, detail transaksi, mengunggah bukti pembayaran, dan memantau status. |

## **4.2 Backend – Administrator**

| Modul | Fitur |
| :---- | :---- |
| Dashboard | Ringkasan jumlah produk, pelanggan, pesanan, transaksi, dan penjualan. |
| Kategori | Tambah, tampil, ubah, dan hapus kategori. |
| Produk | CRUD produk, upload foto, pengaturan harga, status, dan stok. |
| Pelanggan | Melihat data pelanggan dan informasi akun. |
| Pesanan | Melihat detail pesanan dan memperbarui status pemrosesan. |
| Pembayaran | Melihat bukti pembayaran dan menerima atau menolak verifikasi. |
| Pengiriman | Mengelola kurir, nomor resi, dan status pengiriman. |
| Laporan | Rekap penjualan berdasarkan periode dan status transaksi. |

# **5\. Desain Database**

Nama database yang digunakan adalah db\_shopease. Tabel inti yang disarankan:

| No. | Tabel | Fungsi |
| :---- | :---- | :---- |
| 1 | users | Data akun administrator, operator, dan customer. |
| 2 | categories | Data kategori produk. |
| 3 | products | Informasi produk, harga, stok, dan gambar. |
| 4 | carts | Keranjang aktif milik customer. |
| 5 | cart\_items | Daftar produk dan kuantitas di keranjang. |
| 6 | addresses | Alamat pengiriman customer. |
| 7 | orders | Header transaksi/pesanan. |
| 8 | order\_items | Rincian produk dalam pesanan beserta harga saat transaksi. |
| 9 | payments | Informasi pembayaran dan bukti transfer. |
| 10 | shipments | Informasi pengiriman, kurir, resi, dan status. |

## **5.1 Relasi Antar Tabel**

* Satu users dapat memiliki banyak orders.  
* Satu categories dapat memiliki banyak products.  
* Satu users dapat memiliki satu keranjang aktif; carts memiliki banyak cart\_items.  
* Satu products dapat muncul pada banyak cart\_items dan order\_items.  
* Satu orders memiliki banyak order\_items.  
* Satu orders memiliki data pembayaran dan data pengiriman sesuai desain sistem.  
* Satu users dapat menyimpan beberapa addresses.

## **5.2 Contoh Struktur Tabel products**

| Field | Tipe Data | Keterangan |
| :---- | :---- | :---- |
| id | INT | Primary key, auto increment. |
| category\_id | INT | Foreign key ke categories.id. |
| name | VARCHAR(150) | Nama produk. |
| slug | VARCHAR(180) | Identitas URL produk. |
| description | TEXT | Deskripsi produk. |
| price | DECIMAL(12,2) | Harga jual. |
| stock | INT | Jumlah stok tersedia. |
| image | VARCHAR(255) | Nama/path gambar produk. |
| status | VARCHAR/ENUM | Status aktif atau nonaktif. |
| created\_at | DATETIME | Waktu data dibuat. |

# **6\. Arsitektur Aplikasi CodeIgniter 4**

Aplikasi menerapkan pola Model–View–Controller. View bertanggung jawab menampilkan antarmuka; Controller menerima request, menjalankan validasi dan logika alur; Model mengelola akses data ke database. Controller tidak sebaiknya memuat seluruh query dan tampilan sekaligus.

## **6.1 Contoh Struktur Folder**

shopease/  
├── app/  
│   ├── Config/  
│   │   └── Routes.php  
│   ├── Controllers/  
│   │   ├── HomeController.php  
│   │   ├── AuthController.php  
│   │   ├── ProductController.php  
│   │   ├── CartController.php  
│   │   ├── CheckoutController.php  
│   │   ├── OrderController.php  
│   │   └── Admin/  
│   │       ├── DashboardController.php  
│   │       ├── ProductController.php  
│   │       └── OrderController.php  
│   ├── Models/  
│   │   ├── UserModel.php  
│   │   ├── ProductModel.php  
│   │   ├── CategoryModel.php  
│   │   ├── CartModel.php  
│   │   ├── OrderModel.php  
│   │   └── PaymentModel.php  
│   ├── Views/  
│   │   ├── layouts/  
│   │   ├── frontend/  
│   │   ├── auth/  
│   │   └── admin/  
│   └── Filters/  
│       └── AuthFilter.php  
├── public/  
│   └── uploads/products/  
├── writable/  
└── .env

# **7\. Tahapan Pengerjaan Project (12 Pertemuan)**

| Pertemuan | Aktivitas Praktikum | Output |
| :---- | :---- | :---- |
| Pertemuan 1 – Analisis Sistem | Identifikasi masalah, kebutuhan pengguna, aktor, fitur, dan use case. | Dokumen kebutuhan sistem dan use case. |
| Pertemuan 2 – Setup CodeIgniter 4 | Instalasi Composer dan CI4; konfigurasi .env; uji koneksi MySQL; menjalankan server lokal. | Project CI4 berjalan dan terkoneksi ke database. |
| Pertemuan 3 – Database Design | Menyusun ERD, membuat database dan tabel, menentukan primary key, foreign key, serta relasi. | Database db\_shopease. |
| Pertemuan 4 – Routing dan Controller | Membuat route, controller, layout, view, dan navigasi halaman. | Kerangka navigasi aplikasi. |
| Pertemuan 5 – CRUD Kategori | Membangun fungsi tambah, tampil, ubah, hapus, validasi, dan pesan status. | Modul kategori berfungsi. |
| Pertemuan 6 – CRUD Produk | Mengelola produk, upload gambar, validasi, harga, status, dan stok. | Modul produk berfungsi. |
| Pertemuan 7 – Authentication | Registrasi, login, logout, session, hashing password, filter autentikasi, dan role. | Sistem autentikasi dan pembatasan akses. |
| Pertemuan 8 – Frontend E-Commerce | Membangun homepage, katalog, detail produk, pencarian, filter, dan pagination. | Antarmuka customer. |
| Pertemuan 9 – Shopping Cart | Menambah item, mengubah kuantitas, menghapus item, dan menghitung subtotal. | Keranjang belanja. |
| Pertemuan 10 – Checkout dan Order | Memilih alamat, validasi stok, membuat order dan order\_items, serta menyimpan snapshot harga. | Proses transaksi/pesanan. |
| Pertemuan 11 – Payment dan Shipment | Upload bukti pembayaran, verifikasi admin, status pesanan, kurir, dan nomor resi. | Modul pembayaran dan pengiriman. |
| Pertemuan 12 – Testing dan Deployment | Pengujian fungsional, perbaikan bug, konfigurasi server, dan demonstrasi aplikasi. | Aplikasi teruji dan siap dipresentasikan. |

# **8\. Alur Proses Bisnis Transaksi**

1. Customer membuka website dan melihat katalog produk.  
2. Customer memilih produk dan memasukkannya ke keranjang.  
3. Customer memeriksa keranjang dan melanjutkan checkout.  
4. Customer memilih alamat pengiriman dan mengonfirmasi pesanan.  
5. Backend memvalidasi harga, kuantitas, dan ketersediaan stok.  
6. Sistem menyimpan order dan detail item secara konsisten.  
7. Customer melakukan pembayaran dan mengunggah bukti pembayaran.  
8. Administrator memeriksa dan memverifikasi pembayaran.  
9. Operator menyiapkan pesanan dan memperbarui informasi pengiriman.  
10. Customer memantau status hingga pesanan selesai.

## **8.1 Aturan Bisnis dan Validasi**

* Customer harus login sebelum melakukan checkout.  
* Stok produk tidak boleh bernilai negatif.  
* Kuantitas pembelian harus lebih besar dari nol dan tidak boleh melebihi stok tersedia.  
* Harga produk saat transaksi harus disimpan di order\_items agar riwayat transaksi tidak berubah ketika harga katalog diperbarui.  
* Total transaksi dihitung ulang di backend; jangan mempercayai total yang dikirim dari browser.  
* Proses pembuatan pesanan dan perubahan stok perlu menggunakan database transaction agar data konsisten.  
* Tentukan dengan jelas kapan stok dikurangi, misalnya saat pesanan dikonfirmasi atau pembayaran diverifikasi, dan terapkan kebijakan tersebut secara konsisten.  
* Bukti pembayaran hanya dapat diverifikasi oleh role yang berwenang.  
* File upload harus divalidasi berdasarkan ukuran, ekstensi, dan MIME type; nama file sebaiknya dibuat oleh sistem.  
* Akses halaman admin dan operator harus dilindungi filter/otorisasi di sisi server.

# **9\. Skenario Pengujian**

| No. | Skenario | Hasil yang Diharapkan |
| :---- | :---- | :---- |
| 1 | Registrasi customer dengan data valid. | Akun berhasil dibuat dan password disimpan dalam bentuk hash. |
| 2 | Login dengan kredensial valid. | Pengguna masuk ke halaman sesuai role. |
| 3 | Login dengan password salah. | Akses ditolak dan pesan kesalahan ditampilkan. |
| 4 | Menambah produk melalui admin. | Produk tersimpan dan tampil pada daftar. |
| 5 | Mengubah data produk. | Perubahan tersimpan dengan benar. |
| 6 | Menghapus produk yang tidak digunakan. | Produk dihapus atau dinonaktifkan sesuai aturan sistem. |
| 7 | Upload gambar dengan format tidak diizinkan. | Upload ditolak dan pesan validasi muncul. |
| 8 | Menambahkan produk ke keranjang. | Item dan kuantitas tersimpan pada keranjang. |
| 9 | Checkout dengan stok mencukupi. | Order dan detail order berhasil dibuat. |
| 10 | Checkout melebihi stok. | Transaksi ditolak dan stok tidak berubah. |
| 11 | Mengunggah bukti pembayaran. | File tersimpan dan status menunggu verifikasi. |
| 12 | Admin memverifikasi pembayaran. | Status pembayaran dan pesanan berubah sesuai aturan. |


# **10\. Pengembangan Lanjutan (Advanced Challenge)**

* Integrasi payment gateway menggunakan mode sandbox.  
* Integrasi API ongkos kirim.  
* Dashboard grafik penjualan menggunakan Chart.js.  
* Fitur kupon dan diskon.  
* Fitur wishlist.  
* Review dan rating produk.  
* REST API menggunakan fitur RESTful CodeIgniter 4\.  
* Notifikasi email untuk perubahan status pesanan.  
* Role-Based Access Control (RBAC) yang lebih terperinci.