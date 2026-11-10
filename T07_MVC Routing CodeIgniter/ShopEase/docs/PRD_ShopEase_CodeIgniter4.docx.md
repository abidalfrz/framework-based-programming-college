

**PRODUCT REQUIREMENT DOCUMENT**  
**SHOPEASE**  
Aplikasi Toko Online Berbasis CodeIgniter 4

Dokumen Spesifikasi Kebutuhan Produk

**Mata Kuliah: Web Framework**

Versi 1.0 \| 4 November 2026

# **Kontrol Dokumen**

| Elemen | Keterangan |
| :---- | :---- |
| Nama Produk | ShopEase – Online Store |
| Jenis Dokumen | Product Requirement Document (PRD) |
| Versi | 1.0 |
| Status | Draft untuk kebutuhan pembelajaran |
| Pemilik Produk | Tim pengembang/mahasiswa |
| Pengguna Dokumen | Dosen, mahasiswa, penguji, dan tim pengembang |
| Platform | Web application |
| Teknologi Utama | CodeIgniter 4, PHP, MySQL, Bootstrap 5 |

# **1\. Ringkasan Produk**

ShopEase adalah aplikasi toko online berbasis web yang membantu toko mengelola katalog produk, persediaan, pelanggan, pesanan, pembayaran, dan pengiriman. Customer dapat menjelajahi produk, menambahkan barang ke keranjang, melakukan checkout, mengirim bukti pembayaran, serta memantau status pesanan. Administrator mengelola data dan memantau aktivitas penjualan, sedangkan operator menangani proses pemenuhan pesanan.

## **1.1 Latar Belakang**

Proses penjualan yang hanya mengandalkan pencatatan manual atau komunikasi melalui pesan dapat menyebabkan informasi produk dan stok tidak konsisten, kesulitan merekap pesanan, serta keterlambatan pembaruan status transaksi. ShopEase dikembangkan sebagai sarana digitalisasi proses penjualan dan sekaligus sebagai studi kasus pembelajaran pengembangan aplikasi web menggunakan framework MVC.

## **1.2 Permasalahan yang Ingin Diselesaikan**

* Informasi produk dan stok belum tersaji dalam satu katalog terpusat.  
* Customer membutuhkan cara yang mudah untuk mencari dan memilih produk.  
* Pengelolaan keranjang dan pesanan perlu dicatat secara terstruktur.  
* Admin membutuhkan proses verifikasi pembayaran dan pemantauan pengiriman.  
* Pemilik toko membutuhkan ringkasan penjualan dan status pesanan.

## **1.3 Tujuan Produk**

* Menyediakan katalog produk yang dapat diakses melalui browser.  
* Menyediakan alur belanja dari pemilihan produk hingga checkout.  
* Menyediakan pengelolaan pesanan, pembayaran, dan pengiriman.  
* Menyediakan dashboard administrasi dan laporan dasar.  
* Menjadi media praktik penerapan CodeIgniter 4, MVC, CRUD, relasi database, session, validasi, dan testing.

# **2\. Sasaran dan Indikator Keberhasilan**

| Sasaran | Indikator Keberhasilan |
| :---- | :---- |
| Customer dapat berbelanja | Customer dapat melihat produk, mengelola keranjang, dan membuat pesanan. |
| Admin dapat mengelola katalog | Admin dapat melakukan CRUD kategori dan produk serta mengelola stok. |
| Pesanan tercatat konsisten | Order dan order\_items tersimpan dengan total dan snapshot harga yang benar. |
| Pembayaran dapat diverifikasi | Admin dapat menerima atau menolak bukti pembayaran dengan status tercatat. |
| Pesanan dapat dilacak | Customer dapat melihat status pesanan dan informasi pengiriman. |
| Aplikasi layak diuji | Seluruh skenario pengujian kritis lulus dan tidak ada akses lintas-role tanpa izin. |

# **3\. Ruang Lingkup Produk**

## **3.1 In Scope (Termasuk)**

* Registrasi, login, logout, dan pengelolaan session.  
* Role customer, administrator, dan operator.  
* Manajemen kategori dan produk.  
* Upload gambar produk.  
* Katalog, pencarian, filter kategori, sorting, dan pagination.  
* Keranjang belanja dan checkout.  
* Manajemen pesanan dan detail pesanan.  
* Upload dan verifikasi bukti pembayaran manual.  
* Manajemen pengiriman dan nomor resi.  
* Dashboard dan laporan penjualan dasar.  
* Validasi input, proteksi akses, dan pengujian fungsional.

## **3.2 Out of Scope (Tidak Termasuk pada Versi 1\)**

* Integrasi pembayaran langsung dengan bank atau payment gateway produksi.  
* Integrasi otomatis dengan layanan ekspedisi dan pelacakan real-time.  
* Aplikasi native Android atau iOS.  
* Multi-vendor marketplace dan pengelolaan banyak toko.  
* Program loyalitas, poin, rekomendasi berbasis AI, dan personalisasi lanjutan.  
* Integrasi akuntansi atau ERP.

# **4\. Stakeholder dan User Persona**

| Stakeholder/Aktor | Kebutuhan dan Tanggung Jawab |
| :---- | :---- |
| Customer | Mencari produk, melihat detail, mengelola keranjang, checkout, membayar, dan memantau pesanan. |
| Administrator | Mengelola katalog, pengguna, stok, pesanan, pembayaran, pengiriman, dan laporan. |
| Operator Toko | Menyiapkan pesanan dan memperbarui status pemrosesan/pengiriman sesuai kewenangan. |
| Dosen | Menetapkan ruang lingkup, memantau proses, dan menilai hasil pembelajaran. |
| Mahasiswa/Developer | Menganalisis, merancang, membangun, menguji, dan mendokumentasikan aplikasi. |

# **5\. User Journey dan Alur Utama**

## **5.1 Alur Customer**

1. Customer membuka halaman homepage.  
2. Customer mencari produk atau memilih kategori.  
3. Customer membuka halaman detail produk dan memeriksa harga serta stok.  
4. Customer menambahkan produk ke keranjang.  
5. Customer login atau registrasi jika belum memiliki akun.  
6. Customer memeriksa keranjang, memilih alamat, dan melakukan checkout.  
7. Sistem memvalidasi stok dan menghitung ulang total di sisi server.  
8. Sistem membuat pesanan dan menampilkan instruksi pembayaran.  
9. Customer mengunggah bukti pembayaran.  
10. Customer memantau status verifikasi, pemrosesan, pengiriman, dan penyelesaian pesanan.

## **5.2 Alur Administrator/Operator**

11. Administrator login ke halaman admin.  
12. Administrator mengelola kategori, produk, harga, gambar, dan stok.  
13. Administrator melihat pesanan baru.  
14. Administrator memeriksa bukti pembayaran dan memperbarui status pembayaran.  
15. Operator menyiapkan barang dan memperbarui status pemrosesan.  
16. Operator/admin memasukkan kurir dan nomor resi.  
17. Customer menerima pembaruan status hingga pesanan selesai.

# **6\. Kebutuhan Fungsional**

| ID | Modul | Kebutuhan Fungsional | Prioritas |
| :---- | :---- | :---- | :---- |
| FR-001 | Registrasi | Sistem menyediakan formulir registrasi customer dengan nama, email, dan password. | Must |
| FR-002 | Login | Sistem memvalidasi kredensial dan membuat session pengguna. | Must |
| FR-003 | Logout | Pengguna dapat mengakhiri session dengan aman. | Must |
| FR-004 | Role & Access | Sistem membatasi halaman dan aksi berdasarkan role pengguna. | Must |
| FR-005 | Kategori | Admin dapat menambah, melihat, mengubah, dan menghapus kategori sesuai aturan relasi. | Must |
| FR-006 | Produk | Admin dapat melakukan CRUD produk dan mengaitkannya dengan kategori. | Must |
| FR-007 | Upload Gambar | Admin dapat mengunggah gambar produk dengan validasi file. | Must |
| FR-008 | Katalog | Customer dapat melihat daftar produk aktif. | Must |
| FR-009 | Pencarian/Filter | Customer dapat mencari nama produk dan memfilter kategori. | Should |
| FR-010 | Detail Produk | Customer dapat melihat deskripsi, harga, gambar, dan stok produk. | Must |
| FR-011 | Keranjang | Customer dapat menambah, mengubah kuantitas, dan menghapus item keranjang. | Must |
| FR-012 | Checkout | Customer dapat memilih alamat dan mengirim pesanan. | Must |
| FR-013 | Order | Sistem menyimpan header pesanan dan rincian item pesanan. | Must |
| FR-014 | Pembayaran | Customer dapat mengunggah bukti pembayaran manual. | Must |
| FR-015 | Verifikasi | Admin dapat menyetujui atau menolak bukti pembayaran. | Must |
| FR-016 | Pengiriman | Operator/admin dapat mengisi kurir, nomor resi, dan status pengiriman. | Should |
| FR-017 | Riwayat | Customer dapat melihat daftar dan detail pesanan miliknya. | Must |
| FR-018 | Dashboard | Admin dapat melihat ringkasan jumlah produk, pelanggan, pesanan, dan penjualan. | Should |
| FR-019 | Laporan | Admin dapat melihat rekap penjualan berdasarkan periode/status. | Could |
| FR-020 | Pagination | Daftar produk dan data admin mendukung pagination jika data banyak. | Should |

# **7\. Kebutuhan Non-Fungsional**

| ID | Kategori | Persyaratan |
| :---- | :---- | :---- |
| NFR-001 | Usability | Antarmuka mudah dipahami dan navigasi konsisten. |
| NFR-002 | Responsive | Halaman utama, katalog, keranjang, checkout, dan admin dapat digunakan pada desktop dan perangkat mobile. |
| NFR-003 | Security | Password disimpan menggunakan hashing; validasi dilakukan di server; halaman privat dilindungi filter/otorisasi. |
| NFR-004 | Data Integrity | Relasi foreign key dan transaksi database menjaga konsistensi order dan stok. |
| NFR-005 | Performance | Halaman umum dapat dimuat secara wajar pada lingkungan praktikum dan query daftar menggunakan pagination. |
| NFR-006 | Compatibility | Aplikasi diuji pada browser modern berbasis Chromium dan Firefox. |
| NFR-007 | Maintainability | Kode mengikuti pemisahan MVC, penamaan konsisten, dan struktur folder yang jelas. |
| NFR-008 | Availability | Aplikasi berjalan pada server pengembangan lokal dan dapat dipindahkan ke hosting yang mendukung PHP dan MySQL. |
| NFR-009 | Backup | Database dapat diekspor dan dipulihkan melalui mekanisme backup SQL. |
| NFR-010 | Accessibility | Form memiliki label, pesan validasi terbaca, dan kontras antarmuka memadai. |

# **8\. Business Rules**

| ID | Aturan Bisnis |
| :---- | :---- |
| BR-001 | Hanya customer yang telah login yang dapat melakukan checkout. |
| BR-002 | Produk berstatus nonaktif tidak ditampilkan pada katalog dan tidak dapat dibeli. |
| BR-003 | Kuantitas item minimal satu dan tidak boleh melebihi stok tersedia. |
| BR-004 | Harga dan nama produk saat transaksi disimpan sebagai snapshot pada order\_items. |
| BR-005 | Subtotal item \= harga snapshot × kuantitas; total pesanan dihitung ulang di backend. |
| BR-006 | Customer hanya dapat melihat pesanan yang dimilikinya. |
| BR-007 | Hanya admin atau role yang diberi kewenangan yang dapat memverifikasi pembayaran. |
| BR-008 | Status pembayaran dan status pesanan merupakan dua atribut berbeda. |
| BR-009 | Pengurangan stok dilakukan pada titik proses yang ditentukan sistem; untuk versi pembelajaran, disarankan saat pesanan dikonfirmasi setelah validasi stok. |
| BR-010 | Pembuatan order, order\_items, dan perubahan stok harus berada dalam database transaction. |
| BR-011 | Pesanan yang sudah dibayar tidak boleh dihapus secara fisik melalui antarmuka admin; gunakan status atau mekanisme pembatalan. |
| BR-012 | Setiap perubahan status penting mencatat waktu dan pengguna yang melakukan perubahan jika memungkinkan. |

# **9\. Status dan Alur Transaksi**

## **9.1 Status Pesanan yang Disarankan**

| Status | Makna |
| :---- | :---- |
| pending\_payment | Pesanan dibuat dan menunggu pembayaran. |
| waiting\_verification | Bukti pembayaran diunggah dan menunggu pemeriksaan. |
| processing | Pembayaran diterima dan pesanan sedang disiapkan. |
| shipped | Pesanan telah dikirim. |
| completed | Pesanan telah diterima/selesai. |
| cancelled | Pesanan dibatalkan sesuai kebijakan. |

## **9.2 Status Pembayaran**

| Status | Makna |
| :---- | :---- |
| unpaid | Belum ada pembayaran yang diterima. |
| submitted | Customer telah mengunggah bukti pembayaran. |
| verified | Pembayaran telah diverifikasi. |
| rejected | Bukti pembayaran ditolak dan perlu tindak lanjut. |

Catatan: status pembayaran tidak boleh dicampur dengan status pemenuhan pesanan. Transisi status perlu divalidasi oleh backend.

# **10\. Spesifikasi Halaman dan UI**

| Halaman | Komponen/Elemen | Akses |
| :---- | :---- | :---- |
| Homepage | Header, logo, navigasi, search, banner, kategori, produk unggulan, footer. | Publik |
| Katalog | Search, filter kategori, sort harga, kartu produk, pagination. | Publik |
| Detail Produk | Foto, nama, deskripsi, harga, stok, kuantitas, tombol tambah keranjang. | Publik; aksi cart memerlukan login |
| Login/Register | Form email, password, nama, validasi, pesan error. | Publik |
| Keranjang | Daftar item, kuantitas, harga, subtotal, hapus, total. | Customer |
| Checkout | Alamat, ringkasan produk, ongkir jika digunakan, total, konfirmasi. | Customer |
| Riwayat Pesanan | Nomor order, tanggal, total, status, tautan detail. | Customer |
| Detail Pesanan | Item, harga snapshot, total, status pembayaran, status pengiriman. | Pemilik pesanan/Admin |
| Admin Dashboard | Kartu statistik, ringkasan pesanan, grafik/rekap sederhana. | Admin |
| Admin Produk | Tabel produk, pencarian, filter, tambah, edit, hapus/nonaktifkan. | Admin |
| Admin Pesanan | Daftar order, detail, verifikasi pembayaran, pembaruan status. | Admin/Operator sesuai izin |

# **11\. Rancangan Database**

Database: db\_shopease. Rancangan tabel inti berikut menjadi dasar implementasi dan dapat disempurnakan melalui ERD.

| Tabel | Kolom Inti yang Disarankan | Relasi |
| :---- | :---- | :---- |
| users | id, name, email, password\_hash, role, phone, is\_active, created\_at, updated\_at | Satu user memiliki banyak orders, carts, addresses. |
| categories | id, name, slug, description, is\_active, created\_at | Satu kategori memiliki banyak products. |
| products | id, category\_id, name, slug, description, price, stock, image, status, created\_at, updated\_at | FK category\_id ke categories. |
| carts | id, user\_id, created\_at, updated\_at | FK user\_id ke users. |
| cart\_items | id, cart\_id, product\_id, quantity, created\_at, updated\_at | FK cart\_id dan product\_id. |
| addresses | id, user\_id, recipient\_name, phone, address, city, province, postal\_code, is\_default | FK user\_id ke users. |
| orders | id, user\_id, address\_id, order\_number, order\_date, subtotal, shipping\_cost, total, order\_status, notes | FK user\_id dan address\_id. |
| order\_items | id, order\_id, product\_id, product\_name, price, quantity, subtotal | FK order\_id; simpan snapshot nama dan harga. |
| payments | id, order\_id, method, amount, proof\_file, payment\_status, verified\_by, verified\_at | FK order\_id dan verified\_by. |
| shipments | id, order\_id, courier, tracking\_number, shipment\_status, shipped\_at, delivered\_at | FK order\_id. |

## **11.1 Ketentuan Database**

* Gunakan primary key pada setiap tabel dan foreign key untuk relasi.  
* Gunakan DECIMAL untuk nilai uang, bukan FLOAT.  
* Tambahkan index pada email, order\_number, slug, foreign key, dan kolom pencarian yang relevan.  
* Email dan nomor pesanan harus unik sesuai kebutuhan.  
* Gunakan created\_at dan updated\_at untuk audit dasar.  
* Jangan menyimpan password dalam bentuk teks biasa.  
* Pertimbangkan soft delete atau status aktif untuk data yang sudah direferensikan transaksi.

# **12\. Arsitektur dan Teknologi**

| Lapisan | Teknologi/Komponen | Tanggung Jawab |
| :---- | :---- | :---- |
| Presentation | HTML, CSS, Bootstrap 5, JavaScript | Tampilan customer dan admin. |
| Routing | CodeIgniter 4 Routes | Memetakan URL ke controller. |
| Controller | PHP Controller | Mengatur request, validasi, dan alur aplikasi. |
| Model | CodeIgniter Model/Query Builder | Akses dan manipulasi data. |
| View | PHP View Templates | Merender halaman dan komponen UI. |
| Security | CSRF, validation, filters, session, password hashing | Melindungi input, session, dan akses. |
| Database | MySQL | Penyimpanan data aplikasi. |

## **12.1 Struktur Folder Konseptual**

app/  
├── Config/Routes.php  
├── Controllers/  
│   ├── HomeController.php  
│   ├── AuthController.php  
│   ├── ProductController.php  
│   ├── CartController.php  
│   ├── CheckoutController.php  
│   ├── OrderController.php  
│   └── Admin/  
├── Models/  
│   ├── UserModel.php  
│   ├── ProductModel.php  
│   ├── CategoryModel.php  
│   ├── CartModel.php  
│   ├── OrderModel.php  
│   └── PaymentModel.php  
├── Views/  
│   ├── layouts/  
│   ├── frontend/  
│   ├── auth/  
│   └── admin/  
└── Filters/AuthFilter.php  
public/uploads/  
writable/  
.env

# **13\. Validasi dan Penanganan Error**

| Area | Validasi/Perilaku |
| :---- | :---- |
| Registrasi | Email wajib dan unik; password memenuhi kebijakan minimum; input wajib diisi. |
| Login | Pesan kesalahan tidak membocorkan apakah email terdaftar atau tidak. |
| Produk | Harga tidak negatif; stok bilangan bulat tidak negatif; kategori harus valid. |
| Upload | Batasi ukuran, ekstensi dan MIME type; buat nama file acak; simpan file di lokasi yang tidak mengeksekusi script. |
| Keranjang | Kuantitas integer positif; cek ulang status dan stok produk di server. |
| Checkout | Hitung ulang harga dan total; validasi alamat; cegah submit ganda. |
| Pembayaran | Hanya format file yang diizinkan; validasi akses dan status order. |
| Otorisasi | Tolak request jika role tidak berhak meskipun URL diakses langsung. |
| Database | Gunakan transaksi dan rollback saat proses order gagal. |

# **14\. Acceptance Criteria**

| ID | Kriteria Penerimaan |
| :---- | :---- |
| AC-01 | Customer dapat membuat akun dengan email yang belum digunakan. |
| AC-02 | Customer dapat login dan logout; password tidak tersimpan sebagai teks biasa. |
| AC-03 | Admin dapat membuat, melihat, mengubah, dan menghapus/nonaktifkan kategori serta produk. |
| AC-04 | Produk nonaktif tidak muncul pada katalog customer. |
| AC-05 | Customer dapat mencari produk dan membuka halaman detail. |
| AC-06 | Customer dapat menambah item ke keranjang dan mengubah kuantitas. |
| AC-07 | Sistem menolak checkout jika stok tidak mencukupi. |
| AC-08 | Sistem menyimpan order dan item dengan snapshot harga yang benar. |
| AC-09 | Total order dihitung di backend dan sesuai dengan rincian item. |
| AC-10 | Customer hanya dapat melihat pesanan miliknya. |
| AC-11 | Admin dapat memverifikasi atau menolak bukti pembayaran. |
| AC-12 | Operator/admin dapat memperbarui status pengiriman sesuai hak akses. |
| AC-13 | Aplikasi responsif dan seluruh halaman utama dapat digunakan pada desktop dan mobile. |
| AC-14 | Seluruh skenario uji kritis lulus dan akses tanpa otorisasi ditolak. |

# **15\. Rencana Pengujian**

| Jenis Pengujian | Fokus | Contoh |
| :---- | :---- | :---- |
| Unit/Component | Fungsi atau komponen individual | Validasi kuantitas, perhitungan subtotal. |
| Integration | Interaksi antar modul dan database | Checkout membuat order dan order\_items. |
| Functional | Kesesuaian fitur dengan requirement | CRUD produk, login, cart, pembayaran. |
| Security | Kontrol akses dan input | Akses admin oleh customer, upload file berbahaya, CSRF. |
| Usability | Kemudahan penggunaan | Navigasi, pesan validasi, alur checkout. |
| Responsive | Tampilan berbagai ukuran layar | Homepage, katalog, checkout, dashboard. |
| User Acceptance | Kesesuaian dengan kebutuhan pengguna | Simulasi transaksi dari awal sampai selesai. |

# **16\. Risiko dan Mitigasi**

| Risiko | Dampak | Mitigasi |
| :---- | :---- | :---- |
| Stok berubah saat checkout bersamaan | Overselling atau stok negatif. | Validasi ulang stok di server dan transaksi database; pertimbangkan locking. |
| Harga dimanipulasi dari browser | Nilai transaksi tidak valid. | Hitung ulang total menggunakan harga database di backend. |
| Akses role tidak dibatasi | Data admin/customer dapat diakses pihak lain. | Gunakan filters dan pemeriksaan otorisasi pada setiap aksi. |
| Upload file tidak aman | Risiko file berbahaya di server. | Validasi MIME/ekstensi/ukuran, nama acak, dan penyimpanan aman. |
| Perubahan status tidak konsisten | Pesanan sulit dilacak. | Tentukan state transition dan validasi di backend. |
| Scope terlalu luas | Project tidak selesai sesuai waktu. | Prioritaskan fitur Must dan pisahkan fitur lanjutan. |

# **17\. Prioritas Pengembangan dan Milestone**

| Milestone | Ruang Lingkup | Output |
| :---- | :---- | :---- |
| M1 – Fondasi | Analisis, setup CI4, database, routing, layout. | Project dasar dan database. |
| M2 – Katalog/Admin | CRUD kategori, CRUD produk, upload gambar, autentikasi. | Manajemen katalog. |
| M3 – Customer Store | Homepage, katalog, detail, pencarian, cart. | Fitur belanja. |
| M4 – Transaksi | Checkout, order, pembayaran manual, verifikasi. | Siklus transaksi. |
| M5 – Fulfillment | Status pesanan, pengiriman, dashboard dan laporan dasar. | Operasional toko. |
| M6 – Quality & Release | Testing, perbaikan, dokumentasi, deployment/demo. | Rilis pembelajaran. |

# **18\. Deliverables Proyek**

| No. | Deliverable | Format/Media |
| :---- | :---- | :---- |
| 1 | PRD final | DOCX/PDF |
| 2 | Use Case Diagram dan Activity Diagram | PNG/PDF |
| 3 | ERD dan skema database | PNG/PDF/SQL |
| 4 | Wireframe/UI design | Figma/PNG |
| 5 | Source code aplikasi | Repository Git/ZIP |
| 6 | Database dan seed data | SQL |
| 7 | Test case dan hasil pengujian | DOCX/PDF/Spreadsheet |
| 8 | Panduan instalasi dan penggunaan | DOCX/PDF/README |
| 9 | Video demo dan presentasi | MP4/Slide |

# **19\. Rubrik Penilaian Akademik**

| Aspek | Bobot |
| :---- | :---- |
| Analisis kebutuhan dan PRD | 10% |
| Use case, alur proses, dan desain database | 15% |
| Penerapan MVC dan routing CodeIgniter | 15% |
| CRUD dan manajemen katalog | 15% |
| Authentication dan authorization | 10% |
| Keranjang, checkout, dan transaksi | 20% |
| UI/UX dan responsivitas | 5% |
| Testing, dokumentasi, dan presentasi | 10% |
| TOTAL | 100% |

# **20\. Asumsi dan Keputusan yang Perlu Dikonfirmasi**

* Versi awal menggunakan satu toko dan satu mata uang, yaitu Rupiah (IDR).  
* Pembayaran pada versi pembelajaran dilakukan manual melalui upload bukti pembayaran.  
* Ongkos kirim dapat berupa nilai tetap atau diisi admin; integrasi API ekspedisi ditunda.  
* Data produk awal disediakan sebagai seed data untuk demonstrasi.  
* Pengurangan stok dilakukan pada saat pesanan dikonfirmasi sesuai kebijakan yang dipilih tim.  
* Nama, alamat, dan informasi kontak pelanggan merupakan data contoh saat pengujian; hindari menggunakan data pribadi nyata.

# **Lampiran A – Contoh Use Case Ringkas**

| Aktor | Use Case |
| :---- | :---- |
| Customer | Registrasi, login, melihat katalog, mencari produk, melihat detail, mengelola cart, checkout, upload bukti bayar, melihat riwayat. |
| Administrator | Login, mengelola kategori, mengelola produk, mengelola stok, melihat pelanggan, mengelola pesanan, verifikasi pembayaran, melihat laporan. |
| Operator | Login, melihat pesanan, memperbarui status pemrosesan, memasukkan data pengiriman. |

# **Lampiran B – Definisi Istilah**

| Istilah | Definisi |
| :---- | :---- |
| PRD | Dokumen yang mendeskripsikan tujuan, ruang lingkup, kebutuhan, aturan, dan kriteria penerimaan produk. |
| MVC | Pola arsitektur yang memisahkan Model, View, dan Controller. |
| CRUD | Create, Read, Update, Delete. |
| Checkout | Proses konfirmasi keranjang menjadi pesanan. |
| Snapshot harga | Salinan nama/harga produk pada saat transaksi agar histori tidak berubah. |
| Acceptance Criteria | Kondisi terukur yang harus dipenuhi agar fitur diterima. |
| RBAC | Role-Based Access Control, pembatasan akses berdasarkan peran. |

— Akhir Dokumen PRD —