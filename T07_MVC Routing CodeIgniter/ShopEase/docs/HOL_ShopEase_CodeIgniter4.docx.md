

**HANDS-ON LAB**  
**SHOPEASE – ONLINE STORE**  
Pengembangan Aplikasi Toko Online  
Menggunakan CodeIgniter 4

**Panduan Praktikum Mata Kuliah Web Framework**

Versi 1.0 \| November 2026

# **Informasi Dokumen**

| Komponen | Keterangan |
| :---- | :---- |
| Nama Project | ShopEase – Online Store |
| Jenis Dokumen | Hands-On Lab (HOL) |
| Mata Kuliah | Web Framework |
| Framework | CodeIgniter 4 |
| Bahasa Pemrograman | PHP 8+ (sesuaikan dengan persyaratan versi CI4 yang digunakan) |
| Database | MySQL/MariaDB |
| Frontend | HTML, CSS, Bootstrap 5, JavaScript |
| Tools | Visual Studio Code, Composer, XAMPP/Laragon, browser |
| Arsitektur | Model–View–Controller (MVC) |
| Durasi | 12 pertemuan praktikum |

# **1\. Deskripsi Praktikum**

Mahasiswa akan membangun aplikasi toko online ShopEase menggunakan CodeIgniter 4\. Aplikasi terdiri dari frontend untuk customer dan backend untuk administrator/operator. Customer dapat melihat katalog, mencari produk, mengelola keranjang, checkout, mengunggah bukti pembayaran, serta memantau pesanan. Admin/operator mengelola kategori, produk, stok, pesanan, pembayaran, pengiriman, dan laporan.

## **1.1 Tujuan Pembelajaran**

* Menginstal dan mengonfigurasi CodeIgniter 4\.  
* Menerapkan konsep MVC, routing, controller, model, dan view.  
* Membangun CRUD dan relasi database MySQL.  
* Membuat autentikasi, session, dan otorisasi berbasis role.  
* Membangun katalog, keranjang, checkout, dan manajemen pesanan.  
* Menerapkan validasi, transaksi database, dan keamanan dasar.  
* Melakukan pengujian dan menyiapkan deployment.

# **2\. Persiapan Software dan Lingkungan Pengembangan**

| Software | Fungsi | Sumber |
| :---- | :---- | :---- |
| Visual Studio Code | Editor kode | https\://code.visualstudio.com/ |
| XAMPP atau Laragon | Web server lokal dan MySQL/MariaDB | https\://www\.apachefriends.org/ |
| PHP | Runtime backend | https\://www\.php.net/downloads |
| Composer | Dependency manager PHP | https\://getcomposer.org/ |
| CodeIgniter 4 | Framework aplikasi | https\://codeigniter.com/ |
| Browser | Pengujian aplikasi | Chrome, Firefox, atau browser modern lainnya |

## **2.1 Pemeriksaan Instalasi**

Buka terminal dan jalankan perintah berikut:

php \-v  
composer \-V

Pastikan kedua perintah dikenali. Pada XAMPP, aktifkan Apache dan MySQL melalui XAMPP Control Panel.

# **3\. Roadmap Praktikum**

| No. | Praktikum | Output |
| :---- | :---- | :---- |
| 1 | Setup project CodeIgniter 4 | Project berjalan dan terkoneksi ke database. |
| 2 | Membuat database dan tabel | Database db\_shopease dan tabel inti. |
| 3 | Routing, Controller, dan View | Homepage dan navigasi. |
| 4 | CRUD kategori produk | Modul kategori. |
| 5 | CRUD produk dan upload gambar | Modul produk. |
| 6 | Authentication dan Authorization | Login, session, dan role. |
| 7 | Frontend katalog produk | Katalog, pencarian, detail. |
| 8 | Shopping Cart | Keranjang belanja. |
| 9 | Checkout dan Order Management | Transaksi dan riwayat pesanan. |
| 10 | Payment dan Shipment | Pembayaran dan pengiriman. |
| 11 | Dashboard dan laporan | Dashboard administrator. |
| 12 | Testing dan deployment | Aplikasi teruji dan siap didemonstrasikan. |

# **4\. Praktikum 1 – Setup Project CodeIgniter 4**

Tujuan: membuat project CodeIgniter 4 dan menjalankannya pada komputer lokal.

## **Langkah 1\. Membuat project**

composer create-project codeigniter4/appstarter shopease  
cd shopease

## **Langkah 2\. Menjalankan aplikasi**

php spark serve

Buka browser pada alamat http\://localhost:8080.

## **Langkah 3\. Konfigurasi environment**

Salin atau ubah nama file env menjadi .env, kemudian atur konfigurasi berikut. Sesuaikan nama driver dengan instalasi MySQL/MariaDB yang digunakan.

CI\_ENVIRONMENT \= development

database.default.hostname \= localhost  
database.default.database \= db\_shopease  
database.default.username \= root  
database.default.password \=  
database.default.DBDriver \= MySQLi  
database.default.port \= 3306

## **Langkah 4\. Membuat database**

CREATE DATABASE db\_shopease;

Jalankan query melalui phpMyAdmin atau client database.

## **Tugas Praktikum 1**

* Membuat project CodeIgniter 4\.  
* Menjalankan server lokal.  
* Membuat database db\_shopease.  
* Mengonfigurasi koneksi database.  
* Memastikan halaman CodeIgniter dapat dibuka.

Output: project ShopEase berjalan di localhost dan terhubung ke database.

# **5\. Praktikum 2 – Membuat Database dan Tabel**

Tujuan: memahami relasi database dan membuat struktur tabel e-commerce.

## **Langkah 1\. Membuat tabel users**

CREATE TABLE users (  
    id INT AUTO\_INCREMENT PRIMARY KEY,  
    name VARCHAR(100) NOT NULL,  
    email VARCHAR(100) NOT NULL UNIQUE,  
    password VARCHAR(255) NOT NULL,  
    role ENUM('admin','customer','operator') DEFAULT 'customer',  
    created\_at DATETIME DEFAULT CURRENT\_TIMESTAMP  
);

## **Langkah 2\. Membuat tabel categories**

CREATE TABLE categories (  
    id INT AUTO\_INCREMENT PRIMARY KEY,  
    name VARCHAR(100) NOT NULL,  
    slug VARCHAR(120) UNIQUE,  
    description TEXT,  
    created\_at DATETIME DEFAULT CURRENT\_TIMESTAMP  
);

## **Langkah 3\. Membuat tabel products**

CREATE TABLE products (  
    id INT AUTO\_INCREMENT PRIMARY KEY,  
    category\_id INT NOT NULL,  
    name VARCHAR(150) NOT NULL,  
    description TEXT,  
    price DECIMAL(12,2) NOT NULL,  
    stock INT NOT NULL DEFAULT 0,  
    image VARCHAR(255),  
    status ENUM('active','inactive') DEFAULT 'active',  
    created\_at DATETIME DEFAULT CURRENT\_TIMESTAMP,  
    FOREIGN KEY (category\_id) REFERENCES categories(id)  
);

## **Langkah 4\. Membuat tabel transaksi**

CREATE TABLE orders (  
    id INT AUTO\_INCREMENT PRIMARY KEY,  
    user\_id INT NOT NULL,  
    order\_number VARCHAR(50) UNIQUE NOT NULL,  
    subtotal DECIMAL(12,2) NOT NULL,  
    total DECIMAL(12,2) NOT NULL,  
    order\_status VARCHAR(30) DEFAULT 'pending\_payment',  
    created\_at DATETIME DEFAULT CURRENT\_TIMESTAMP,  
    FOREIGN KEY (user\_id) REFERENCES users(id)  
);

CREATE TABLE order\_items (  
    id INT AUTO\_INCREMENT PRIMARY KEY,  
    order\_id INT NOT NULL,  
    product\_id INT NOT NULL,  
    product\_name VARCHAR(150) NOT NULL,  
    price DECIMAL(12,2) NOT NULL,  
    quantity INT NOT NULL,  
    subtotal DECIMAL(12,2) NOT NULL,  
    FOREIGN KEY (order\_id) REFERENCES orders(id),  
    FOREIGN KEY (product\_id) REFERENCES products(id)  
);

Untuk implementasi lengkap, buat juga tabel carts, cart\_items, addresses, payments, dan shipments sesuai PRD. order\_items menyimpan snapshot nama dan harga produk pada saat transaksi.

## **Langkah 5\. Mengisi data awal**

INSERT INTO categories (name, slug) VALUES  
('Elektronik', 'elektronik'),  
('Fashion', 'fashion'),  
('Aksesoris', 'aksesoris');

INSERT INTO products  
(category\_id, name, description, price, stock)  
VALUES  
(1, 'Wireless Headphone', 'Headphone Bluetooth', 299000, 20),  
(2, 'Sneakers Casual', 'Sepatu casual pria', 350000, 15),  
(3, 'Smart Watch', 'Jam tangan digital', 450000, 10);

## **Tugas Praktikum 2**

* Buat seluruh tabel berdasarkan ERD PRD.  
* Tentukan primary key dan foreign key.  
* Masukkan minimal 10 data produk.  
* Periksa relasi dan data melalui phpMyAdmin.

Output: database db\_shopease lengkap dengan tabel dan data awal.

# **6\. Praktikum 3 – Routing, Controller, dan View**

Tujuan: memahami mekanisme request dan response pada CodeIgniter 4\.

## **Langkah 1\. Membuat controller**

\<?php  
namespace App\\Controllers;

class HomeController extends BaseController  
{  
    public function index()  
    {  
        \$data \= \['title' \=\> 'ShopEase Online Store'\];  
        return view('frontend/home', \$data);  
    }  
}

## **Langkah 2\. Membuat view**

Buat file app/Views/frontend/home.php:

\<\!DOCTYPE html\>  
\<html lang="id"\>  
\<head\>  
    \<meta charset="UTF-8"\>  
    \<title\>\<?= esc(\$title) ?\>\</title\>  
\</head\>  
\<body\>  
    \<h1\>Selamat Datang di ShopEase\</h1\>  
    \<p\>Belanja online mudah dan aman.\</p\>  
\</body\>  
\</html\>

## **Langkah 3\. Membuat route**

\$routes-\>get('/', 'HomeController::index');

Uji melalui http\://localhost:8080/. Tambahkan halaman About dan Contact sebagai latihan routing.

## **Tugas Praktikum 3**

* Membuat HomeController.  
* Membuat halaman homepage.  
* Membuat route halaman utama.  
* Membuat halaman About dan Contact.

Output: homepage ShopEase berhasil ditampilkan.

# **7\. Praktikum 4 – CRUD Kategori Produk**

Tujuan: mengimplementasikan Create, Read, Update, Delete menggunakan Model CodeIgniter.

## **Langkah 1\. Membuat CategoryModel**

\<?php  
namespace App\\Models;  
use CodeIgniter\\Model;

class CategoryModel extends Model  
{  
    protected \$table \= 'categories';  
    protected \$primaryKey \= 'id';  
    protected \$allowedFields \= \['name', 'slug', 'description'\];  
}

## **Langkah 2\. Membuat CategoryController**

\<?php  
namespace App\\Controllers;  
use App\\Models\\CategoryModel;

class CategoryController extends BaseController  
{  
    public function index()  
    {  
        \$model \= new CategoryModel();  
        \$data\['categories'\] \= \$model-\>findAll();  
        return view('admin/categories/index', \$data);  
    }

    public function create()  
    {  
        return view('admin/categories/create');  
    }

    public function store()  
    {  
        \$model \= new CategoryModel();  
        \$rules \= \['name' \=\> 'required\|min\_length\[3\]'\];

        if (\!\$this-\>validate(\$rules)) {  
            return redirect()-\>back()  
                \-\>withInput()  
                \-\>with('errors', \$this-\>validator-\>getErrors());  
        }

        \$name \= \$this-\>request-\>getPost('name');  
        \$model-\>insert(\[  
            'name' \=\> \$name,  
            'slug' \=\> url\_title(\$name, '-', true),  
            'description' \=\> \$this-\>request-\>getPost('description')  
        \]);

        return redirect()-\>to('/admin/categories');  
    }  
}

## **Langkah 3\. Membuat routes**

\$routes-\>group('admin', function(\$routes) {  
    \$routes-\>get('categories', 'CategoryController::index');  
    \$routes-\>get('categories/create', 'CategoryController::create');  
    \$routes-\>post('categories/store', 'CategoryController::store');  
});

## **Langkah 4\. Membuat view daftar kategori**

\<h2\>Manajemen Kategori\</h2\>  
\<a href="/admin/categories/create"\>Tambah Kategori\</a\>

\<table\>  
    \<tr\>\<th\>No\</th\>\<th\>Nama\</th\>\<th\>Slug\</th\>\</tr\>  
    \<?php foreach (\$categories as \$i \=\> \$item): ?\>  
    \<tr\>  
        \<td\>\<?= \$i \+ 1 ?\>\</td\>  
        \<td\>\<?= esc(\$item\['name'\]) ?\>\</td\>  
        \<td\>\<?= esc(\$item\['slug'\]) ?\>\</td\>  
    \</tr\>  
    \<?php endforeach; ?\>  
\</table\>

Lengkapi form create, edit, dan delete. Gunakan CSRF field pada form dan validasi server-side.

## **Tugas Praktikum 4**

* Membuat kategori baru.  
* Menampilkan daftar kategori.  
* Mengubah kategori.  
* Menghapus kategori dengan mempertimbangkan relasi produk.  
* Melakukan validasi form.

Output: modul CRUD kategori berfungsi.

# **8\. Praktikum 5 – CRUD Produk dan Upload Gambar**

Tujuan: membuat manajemen produk yang berelasi dengan kategori.

## **Langkah 1\. Membuat ProductModel**

\<?php  
namespace App\\Models;  
use CodeIgniter\\Model;

class ProductModel extends Model  
{  
    protected \$table \= 'products';  
    protected \$primaryKey \= 'id';  
    protected \$allowedFields \= \[  
        'category\_id', 'name', 'description',  
        'price', 'stock', 'image', 'status'  
    \];  
}

## **Langkah 2\. Membuat controller daftar produk**

public function index()  
{  
    \$product \= new ProductModel();  
    \$data\['products'\] \= \$product  
        \-\>select('products.\*, categories.name AS category\_name')  
        \-\>join('categories', 'categories.id \= products.category\_id')  
        \-\>findAll();

    return view('admin/products/index', \$data);  
}

public function create()  
{  
    \$category \= new CategoryModel();  
    \$data\['categories'\] \= \$category-\>findAll();  
    return view('admin/products/create', \$data);  
}

## **Langkah 3\. Membuat form produk**

\<form method="post" action="/admin/products/store"  
      enctype="multipart/form-data"\>  
    \<?= csrf\_field() ?\>  
    \<label\>Nama Produk\</label\>  
    \<input type="text" name="name" required\>

    \<label\>Kategori\</label\>  
    \<select name="category\_id" required\>  
        \<?php foreach (\$categories as \$c): ?\>  
        \<option value="\<?= \$c\['id'\] ?\>"\>  
            \<?= esc(\$c\['name'\]) ?\>  
        \</option\>  
        \<?php endforeach; ?\>  
    \</select\>

    \<label\>Harga\</label\>  
    \<input type="number" name="price" min="0" required\>  
    \<label\>Stok\</label\>  
    \<input type="number" name="stock" min="0" required\>  
    \<label\>Foto Produk\</label\>  
    \<input type="file" name="image"  
           accept="image/jpeg,image/png,image/webp"\>  
    \<button type="submit"\>Simpan\</button\>  
\</form\>

## **Langkah 4\. Contoh logika upload**

\$image \= \$this-\>request-\>getFile('image');  
\$imageName \= null;

if (\$image && \$image-\>isValid() && \!\$image-\>hasMoved()) {  
    \$allowed \= \['image/jpeg', 'image/png', 'image/webp'\];

    if (\!in\_array(\$image-\>getMimeType(), \$allowed)) {  
        return redirect()-\>back()  
            \-\>with('error', 'Format gambar tidak valid');  
    }

    \$imageName \= \$image-\>getRandomName();  
    \$image-\>move(FCPATH . 'uploads/products', \$imageName);  
}

Tambahkan validasi ukuran file, MIME type, penanganan kegagalan upload, serta penyimpanan nama file ke database. Buat folder public/uploads/products.

## **Tugas Praktikum 5**

* Membuat tambah, edit, dan hapus/nonaktifkan produk.  
* Membuat upload gambar.  
* Menampilkan gambar pada daftar produk.  
* Membuat relasi produk dengan kategori.

Output: modul manajemen produk lengkap.

# **9\. Praktikum 6 – Authentication dan Authorization**

Tujuan: membuat login dan membatasi hak akses berdasarkan role.

## **Langkah 1\. Registrasi dan password hashing**

\$passwordHash \= password\_hash(  
    \$this-\>request-\>getPost('password'),  
    PASSWORD\_DEFAULT  
);  
// Simpan \$passwordHash ke kolom users.password

## **Langkah 2\. Proses login**

\$user \= \$userModel-\>where('email', \$email)-\>first();

if (\$user && password\_verify(\$password, \$user\['password'\])) {  
    session()-\>regenerate();  
    session()-\>set(\[  
        'user\_id' \=\> \$user\['id'\],  
        'name' \=\> \$user\['name'\],  
        'role' \=\> \$user\['role'\],  
        'logged\_in' \=\> true  
    \]);  
    return redirect()-\>to('/');  
}

## **Langkah 3\. Membuat AuthFilter**

\<?php  
namespace App\\Filters;  
use CodeIgniter\\HTTP\\RequestInterface;  
use CodeIgniter\\HTTP\\ResponseInterface;  
use CodeIgniter\\Filters\\FilterInterface;

class AuthFilter implements FilterInterface  
{  
    public function before(RequestInterface \$request, \$arguments \= null)  
    {  
        if (\!session()-\>get('logged\_in')) {  
            return redirect()-\>to('/login');  
        }

        if (\$arguments &&  
            \!in\_array(session()-\>get('role'), \$arguments)) {  
            return redirect()-\>to('/');  
        }  
    }

    public function after(  
        RequestInterface \$request,  
        ResponseInterface \$response,  
        \$arguments \= null  
    ) {}  
}

Daftarkan alias filter pada app/Config/Filters.php sesuai versi CodeIgniter yang digunakan. Terapkan filter pada route admin dan operator.

\$routes-\>group('admin', \['filter' \=\> 'auth:admin'\], function(\$routes) {  
    \$routes-\>get('dashboard', 'Admin\\\\DashboardController::index');  
});

## **Tugas Praktikum 6**

* Registrasi customer.  
* Login dan logout.  
* Menerapkan password hashing.  
* Mengelola session pengguna.  
* Membatasi halaman admin.  
* Menguji akses berdasarkan role.

Output: sistem autentikasi dan otorisasi pengguna.

# **10\. Praktikum 7 – Frontend Katalog Produk**

Tujuan: menampilkan produk dari database pada halaman customer.

## **Langkah 1\. Membuat method katalog**

public function catalog()  
{  
    \$productModel \= new ProductModel();  
    \$data\['products'\] \= \$productModel  
        \-\>where('status', 'active')  
        \-\>paginate(8);  
    \$data\['pager'\] \= \$productModel-\>pager;

    return view('frontend/catalog', \$data);  
}

## **Langkah 2\. Membuat view katalog**

\<h2\>Katalog Produk ShopEase\</h2\>  
\<div class="container"\>  
  \<div class="row"\>  
    \<?php foreach (\$products as \$p): ?\>  
    \<div class="col-md-3 mb-4"\>  
      \<div class="card"\>  
        \<img src="\<?= base\_url('uploads/products/' . esc(\$p\['image'\])) ?\>"  
             class="card-img-top" alt="\<?= esc(\$p\['name'\]) ?\>"\>  
        \<div class="card-body"\>  
          \<h5\>\<?= esc(\$p\['name'\]) ?\>\</h5\>  
          \<p\>Rp \<?= number\_format(\$p\['price'\], 0, ',', '.') ?\>\</p\>  
          \<a href="/product/\<?= \$p\['id'\] ?\>" class="btn btn-primary"\>  
            Detail Produk  
          \</a\>  
        \</div\>  
      \</div\>  
    \</div\>  
    \<?php endforeach; ?\>  
  \</div\>  
  \<?= \$pager-\>links() ?\>  
\</div\>

## **Langkah 3\. Membuat route**

\$routes-\>get('catalog', 'ProductController::catalog');  
\$routes-\>get('product/(:num)', 'ProductController::detail/\$1');

## **Tugas Praktikum 7**

* Menampilkan katalog produk.  
* Menampilkan detail produk.  
* Membuat pencarian produk.  
* Membuat filter kategori.  
* Membuat pagination.

Output: halaman katalog produk ShopEase.

# **11\. Praktikum 8 – Shopping Cart**

Tujuan: mengembangkan fitur keranjang belanja yang terhubung dengan akun customer.

## **Langkah 1\. Membuat tabel cart**

CREATE TABLE carts (  
    id INT AUTO\_INCREMENT PRIMARY KEY,  
    user\_id INT NOT NULL,  
    created\_at DATETIME DEFAULT CURRENT\_TIMESTAMP,  
    FOREIGN KEY (user\_id) REFERENCES users(id)  
);

CREATE TABLE cart\_items (  
    id INT AUTO\_INCREMENT PRIMARY KEY,  
    cart\_id INT NOT NULL,  
    product\_id INT NOT NULL,  
    quantity INT NOT NULL DEFAULT 1,  
    FOREIGN KEY (cart\_id) REFERENCES carts(id),  
    FOREIGN KEY (product\_id) REFERENCES products(id),  
    UNIQUE KEY unique\_cart\_product (cart\_id, product\_id)  
);

## **Langkah 2\. Membuat CartModel**

\<?php  
namespace App\\Models;  
use CodeIgniter\\Model;

class CartModel extends Model  
{  
    protected \$table \= 'carts';  
    protected \$primaryKey \= 'id';  
    protected \$allowedFields \= \['user\_id'\];  
}

Buat CartItemModel untuk tabel cart\_items.

## **Langkah 3\. Contoh validasi tambah cart**

public function add()  
{  
    \$productId \= (int) \$this-\>request-\>getPost('product\_id');  
    \$quantity \= (int) \$this-\>request-\>getPost('quantity');

    if (\$quantity \< 1\) {  
        return redirect()-\>back()-\>with('error', 'Jumlah tidak valid');  
    }

    \$productModel \= new ProductModel();  
    \$product \= \$productModel  
        \-\>where('status', 'active')  
        \-\>find(\$productId);

    if (\!\$product \|\| \$product\['stock'\] \< \$quantity) {  
        return redirect()-\>back()  
            \-\>with('error', 'Stok tidak mencukupi');  
    }

    // Cari/buat cart milik user yang login.  
    // Tambahkan item atau perbarui quantity.  
    return redirect()-\>to('/cart');  
}

Kerangka tersebut harus dilengkapi dengan operasi database untuk cart dan cart\_items. Validasi ulang stok saat checkout karena stok dapat berubah.

## **Langkah 4\. Membuat tampilan cart**

| Produk | Harga | Qty | Subtotal |
| :---- | :---- | :---- | :---- |
| Headphone | Rp299.000 | 1 | Rp299.000 |
| Sneakers | Rp350.000 | 2 | Rp700.000 |
| TOTAL |  |  | Rp999.000 |

Rumus: Subtotal \= Harga × Quantity. Total \= jumlah seluruh subtotal item.

## **Tugas Praktikum 8**

* Menambah produk ke cart.  
* Mengubah jumlah produk.  
* Menghapus item.  
* Menghitung subtotal dan total.  
* Membatasi akses cart berdasarkan customer yang login.

Output: shopping cart berfungsi.

# **12\. Praktikum 9 – Checkout dan Order Management**

Tujuan: membuat proses transaksi dari keranjang menjadi pesanan.

## **Langkah 1\. Membuat form checkout**

* Nama penerima dan nomor telepon.  
* Alamat pengiriman.  
* Daftar produk dan jumlah.  
* Subtotal, ongkos kirim jika digunakan, dan total pembayaran.

## **Langkah 2\. Membuat OrderModel**

\<?php  
namespace App\\Models;  
use CodeIgniter\\Model;

class OrderModel extends Model  
{  
    protected \$table \= 'orders';  
    protected \$primaryKey \= 'id';  
    protected \$allowedFields \= \[  
        'user\_id', 'order\_number', 'subtotal',  
        'total', 'order\_status'  
    \];  
}

## **Langkah 3\. Kerangka proses checkout**

\$db \= \\\\Config\\\\Database::connect();  
\$db-\>transBegin();

try {  
    // 1\. Ambil cart milik customer dari database.  
    // 2\. Ambil ulang data produk dari database.  
    // 3\. Validasi status produk dan ketersediaan stok.  
    // 4\. Hitung ulang harga dan total di backend.  
    // 5\. Buat record orders.  
    // 6\. Buat record order\_items dengan snapshot harga/nama.  
    // 7\. Perbarui stok sesuai kebijakan transaksi.  
    // 8\. Kosongkan cart.

    if (\$db-\>transStatus() \=== false) {  
        throw new \\\\RuntimeException('Transaksi gagal');  
    }  
    \$db-\>transCommit();  
} catch (\\\\Throwable \$e) {  
    \$db-\>transRollback();  
    throw \$e;  
}

Kerangka perlu dilengkapi dengan query insert dan validasi. Jangan menerima harga atau total dari browser sebagai nilai final. Proses pembuatan order, detail order, dan pengurangan stok harus konsisten dalam transaksi database.

## **Langkah 4\. Riwayat pesanan**

* Nomor pesanan dan tanggal.  
* Total transaksi.  
* Status pembayaran dan status pesanan.  
* Detail item pesanan.

## **Tugas Praktikum 9**

* Membuat checkout.  
* Menyimpan pesanan dan detail pesanan.  
* Membuat nomor order unik.  
* Menghitung total transaksi di backend.  
* Memvalidasi stok.  
* Membuat riwayat transaksi.

Output: sistem checkout dan order management.

# **13\. Praktikum 10 – Payment dan Shipment**

Tujuan: membuat fitur pembayaran manual dan pengiriman.

## **Langkah 1\. Membuat tabel payments**

CREATE TABLE payments (  
    id INT AUTO\_INCREMENT PRIMARY KEY,  
    order\_id INT NOT NULL,  
    method VARCHAR(50),  
    amount DECIMAL(12,2) NOT NULL,  
    proof\_file VARCHAR(255),  
    payment\_status ENUM(  
        'unpaid','submitted','verified','rejected'  
    ) DEFAULT 'unpaid',  
    verified\_by INT NULL,  
    verified\_at DATETIME NULL,  
    FOREIGN KEY (order\_id) REFERENCES orders(id),  
    FOREIGN KEY (verified\_by) REFERENCES users(id)  
);

## **Langkah 2\. Form upload bukti pembayaran**

\<form method="post" action="/payment/upload"  
      enctype="multipart/form-data"\>  
    \<?= csrf\_field() ?\>  
    \<label\>Bukti Pembayaran\</label\>  
    \<input type="file" name="proof"  
           accept="image/jpeg,image/png,image/webp" required\>  
    \<button type="submit"\>Upload Bukti\</button\>  
\</form\>

Backend harus memvalidasi kepemilikan pesanan, status pembayaran, MIME type, ukuran file, dan nama file yang aman.

## **Langkah 3\. Verifikasi pembayaran**

* Approve: pembayaran diterima dan status diperbarui.  
* Reject: bukti ditolak dan customer diminta melakukan tindak lanjut.

## **Langkah 4\. Membuat tabel shipments**

CREATE TABLE shipments (  
    id INT AUTO\_INCREMENT PRIMARY KEY,  
    order\_id INT NOT NULL,  
    courier VARCHAR(100),  
    tracking\_number VARCHAR(100),  
    shipment\_status VARCHAR(30),  
    shipped\_at DATETIME NULL,  
    delivered\_at DATETIME NULL,  
    FOREIGN KEY (order\_id) REFERENCES orders(id)  
);

## **Tugas Praktikum 10**

* Membuat upload bukti pembayaran.  
* Membuat dashboard verifikasi admin.  
* Membuat approve/reject.  
* Membuat pengelolaan pengiriman.  
* Membuat halaman tracking pesanan.

Output: modul pembayaran dan pengiriman.

# **14\. Praktikum 11 – Dashboard Administrator dan Laporan**

Tujuan: menyediakan informasi operasional toko dalam satu halaman dashboard.

| Statistik | Sumber Data |
| :---- | :---- |
| Total Produk | Tabel products |
| Total Customer | Tabel users dengan role customer |
| Total Pesanan | Tabel orders |
| Total Penjualan | Tabel orders dengan status yang memenuhi kriteria penjualan |
| Pesanan Pending | Tabel orders |
| Pembayaran Menunggu Verifikasi | Tabel payments |

## **Langkah 1\. Contoh query statistik**

SELECT COUNT(\*) AS total\_produk FROM products;

SELECT COUNT(\*) AS total\_customer  
FROM users WHERE role \= 'customer';

SELECT COUNT(\*) AS total\_pesanan FROM orders;

## **Langkah 2\. Contoh laporan penjualan harian**

SELECT DATE(created\_at) AS tanggal,  
       SUM(total) AS penjualan  
FROM orders  
WHERE order\_status IN ('processing','shipped','completed')  
GROUP BY DATE(created\_at)  
ORDER BY tanggal;

## **Langkah 3\. Visualisasi**

* Grafik penjualan harian.  
* Grafik jumlah transaksi.  
* Daftar produk terlaris.  
* Distribusi status pesanan.

Gunakan Chart.js atau pustaka visualisasi lain untuk menampilkan grafik.

## **Tugas Praktikum 11**

* Membuat dashboard administrator.  
* Menampilkan statistik.  
* Membuat grafik penjualan.  
* Membuat laporan berdasarkan periode.  
* Membuat tabel transaksi terbaru.

Output: dashboard administrator dan laporan penjualan.

# **15\. Praktikum 12 – Testing dan Deployment**

Tujuan: menguji fungsi aplikasi dan mempersiapkan deployment.

## **Langkah 1\. Pengujian fungsional**

| No. | Pengujian | Hasil yang Diharapkan |
| :---- | :---- | :---- |
| 1 | Registrasi | Akun berhasil dibuat. |
| 2 | Login | Pengguna masuk sesuai role. |
| 3 | CRUD kategori | Data berhasil dikelola. |
| 4 | CRUD produk | Produk berhasil dikelola. |
| 5 | Upload gambar | Gambar tervalidasi dan tersimpan. |
| 6 | Katalog | Produk aktif tampil. |
| 7 | Shopping Cart | Produk masuk keranjang. |
| 8 | Checkout | Order terbentuk. |
| 9 | Pembayaran | Bukti berhasil diunggah. |
| 10 | Verifikasi | Status pembayaran berubah. |
| 11 | Pengiriman | Nomor resi tersimpan. |
| 12 | Laporan | Data penjualan tampil. |

## **Langkah 2\. Pengujian keamanan**

* Akses halaman admin tanpa login.  
* Customer mencoba membuka halaman admin.  
* Upload file bukan gambar.  
* Checkout dengan stok tidak mencukupi.  
* Manipulasi harga melalui browser.  
* Akses pesanan milik customer lain.  
* Memastikan CSRF protection aktif.

## **Langkah 3\. Deployment**

1. Menyiapkan hosting yang mendukung PHP dan MySQL.  
2. Mengunggah source code dan mengimpor database SQL.  
3. Mengatur konfigurasi .env untuk production.  
4. Memastikan document root mengarah ke folder public.  
5. Mengatur permission folder writable.  
6. Mengaktifkan HTTPS.  
7. Melakukan pengujian ulang setelah deployment.

Output: aplikasi ShopEase siap dipresentasikan dan diuji.

# **16\. Tugas Akhir Project (Final Project)**

Mahasiswa mengembangkan aplikasi ShopEase secara individu atau berkelompok sesuai arahan dosen.

| Komponen | Ketentuan Minimum |
| :---- | :---- |
| Nama aplikasi | ShopEase |
| Framework | CodeIgniter 4 |
| Database | MySQL/MariaDB |
| Kategori | Minimal 3 kategori |
| Produk | Minimal 15 produk |
| Akun | Minimal 1 admin dan 2 customer |
| Fitur wajib | CRUD, login, katalog, cart, checkout |
| Fitur transaksi | Order dan pembayaran |
| Dokumentasi | PRD, ERD, UI/UX, dan testing |

## **16.1 Output yang Dikumpulkan**

* Source code aplikasi.  
* Database SQL.  
* Dokumen PRD.  
* ERD database.  
* Desain UI/UX.  
* Dokumentasi pengujian.  
* Video demonstrasi aplikasi.  
* Laporan hasil praktikum.

# **17\. Rubrik Penilaian Praktikum**

| Aspek | Bobot |
| :---- | :---- |
| Setup dan konfigurasi CI4 | 5% |
| Database dan relasi | 10% |
| Implementasi MVC | 15% |
| CRUD kategori dan produk | 15% |
| Authentication dan Authorization | 10% |
| Katalog dan Shopping Cart | 15% |
| Checkout dan transaksi | 15% |
| UI/UX | 5% |
| Testing dan dokumentasi | 5% |
| Presentasi | 5% |
| TOTAL | 100% |

# **18\. Checklist Monitoring Dosen**

☐ Setup CodeIgniter 4 dan database

☐ Membuat ERD dan tabel MySQL

☐ Membuat routing dan controller

☐ Implementasi CRUD kategori

☐ Implementasi CRUD produk

☐ Upload gambar produk

☐ Registrasi dan login

☐ Implementasi role pengguna

☐ Membuat katalog produk

☐ Membuat shopping cart

☐ Membuat checkout

☐ Membuat order management

☐ Implementasi pembayaran

☐ Implementasi pengiriman

☐ Dashboard dan laporan

☐ Testing dan deployment

# **19\. Catatan Teknis dan Keamanan**

* Contoh kode dalam dokumen merupakan kerangka pembelajaran dan perlu dilengkapi sesuai struktur project serta versi CodeIgniter yang digunakan.  
* Gunakan validasi server-side dan escaping output menggunakan esc() pada view.  
* Jangan menyimpan password dalam bentuk teks biasa; gunakan password\_hash() dan password\_verify().  
* Gunakan CSRF protection pada form yang mengubah data.  
* Batasi akses melalui filter dan pemeriksaan otorisasi di server.  
* Validasi upload berdasarkan ukuran, ekstensi, dan MIME type; gunakan nama file acak.  
* Hitung ulang harga dan total transaksi dari database di backend.  
* Gunakan database transaction untuk menjaga konsistensi pesanan dan stok.  
* Jangan menghapus secara fisik data transaksi yang sudah memiliki riwayat; gunakan status atau mekanisme pembatalan.  
* Jangan menggunakan data pribadi nyata dalam dataset demonstrasi.

— Akhir Dokumen Hands-On Lab ShopEase —