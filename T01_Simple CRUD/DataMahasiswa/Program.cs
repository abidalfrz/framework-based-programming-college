using System;
using System.Collections.Generic;

namespace DataMahasiswa
{
    class Mahasiswa
    {
        public string NIM { get; set; }
        public string Nama { get; set; }
        public string Prodi { get; set; }
        public double IPK { get; set; }

        public Mahasiswa(string nim, string nama, string prodi, double ipk)
        {
            NIM = nim;
            Nama = nama;
            Prodi = prodi;
            IPK = ipk;
        }
    }

    class Program
    {
        static List<Mahasiswa> daftarMahasiswa = new List<Mahasiswa>();

        static void Main(string[] args)
        {
            int pilihan;

            do
            {
                TampilkanMenu();

                Console.ForegroundColor = ConsoleColor.White;
                Console.Write("Masukkan pilihan → ");
                Console.ResetColor();

                string input = Console.ReadLine();

                if (!int.TryParse(input, out pilihan))
                {
                    pilihan = 0;
                }

                switch (pilihan)
                {
                    case 1:
                        TambahMahasiswa();
                        break;

                    case 2:
                        TampilkanMahasiswa();
                        break;

                    case 3:
                        CariMahasiswa();
                        break;

                    case 4:
                        HapusMahasiswa();
                        break;

                    case 5:
                        TampilkanPesan("Terima kasih telah menggunakan program.", ConsoleColor.Green);
                        break;

                    default:
                        TampilkanPesan("Pilihan tidak tersedia!", ConsoleColor.Red);
                        break;
                }

                if (pilihan != 5)
                {
                    Pause();
                }

            } while (pilihan != 5);
        }

        static void TampilkanMenu()
        {
            Console.Clear();

            Console.ForegroundColor = ConsoleColor.Cyan;
            Console.WriteLine("╔════════════════════════════════════════════╗");
            Console.WriteLine("║         SISTEM DATA MAHASISWA              ║");
            Console.WriteLine("╚════════════════════════════════════════════╝");
            Console.ResetColor();

            Console.WriteLine();

            TampilkanDashboard();

            Console.WriteLine();

            Console.ForegroundColor = ConsoleColor.Green;
            Console.WriteLine("[1] ➕ Tambah Mahasiswa");

            Console.ForegroundColor = ConsoleColor.Yellow;
            Console.WriteLine("[2] 📋 Tampilkan Mahasiswa");

            Console.ForegroundColor = ConsoleColor.Blue;
            Console.WriteLine("[3] 🔍 Cari Mahasiswa");

            Console.ForegroundColor = ConsoleColor.Red;
            Console.WriteLine("[4] 🗑️  Hapus Mahasiswa");

            Console.ForegroundColor = ConsoleColor.DarkGray;
            Console.WriteLine("[5] 🚪 Keluar");

            Console.ResetColor();

            Console.WriteLine();
            Console.WriteLine("──────────────────────────────────────────────");
        }

        static void TampilkanDashboard()
        {
            Console.ForegroundColor = ConsoleColor.White;

            Console.WriteLine("┌────────────────────────────────────────────┐");
            Console.WriteLine("│                  DASHBOARD                 │");
            Console.WriteLine("├────────────────────────────────────────────┤");

            Console.WriteLine(
                $"│ Total Mahasiswa : {daftarMahasiswa.Count,-25}│"
            );

            if (daftarMahasiswa.Count > 0)
            {
                double totalIPK = 0;
                double ipkTertinggi = daftarMahasiswa[0].IPK;

                foreach (Mahasiswa m in daftarMahasiswa)
                {
                    totalIPK += m.IPK;

                    if (m.IPK > ipkTertinggi)
                    {
                        ipkTertinggi = m.IPK;
                    }
                }

                double rataRata = totalIPK / daftarMahasiswa.Count;

                Console.WriteLine(
                    $"│ Rata-rata IPK   : {rataRata,-25:F2}│"
                );

                Console.WriteLine(
                    $"│ IPK Tertinggi   : {ipkTertinggi,-25:F2}│"
                );
            }
            else
            {
                Console.WriteLine(
                    $"│ Rata-rata IPK   : {"-",-25}│"
                );

                Console.WriteLine(
                    $"│ IPK Tertinggi   : {"-",-25}│"
                );
            }

            Console.WriteLine("└────────────────────────────────────────────┘");

            Console.ResetColor();
        }

        static void TambahMahasiswa()
        {
            Console.Clear();

            Header("TAMBAH MAHASISWA", ConsoleColor.Green);

            Console.Write("NIM            : ");
            string nim = Console.ReadLine();

            if (string.IsNullOrWhiteSpace(nim))
            {
                TampilkanPesan("NIM tidak boleh kosong.", ConsoleColor.Red);

                return;
            }

            foreach (Mahasiswa m in daftarMahasiswa)
            {
                if (m.NIM.Equals(
                    nim,
                    StringComparison.OrdinalIgnoreCase
                ))
                {
                    TampilkanPesan("NIM sudah terdaftar.", ConsoleColor.Red);

                    return;
                }
            }

            Console.Write("Nama           : ");
            string nama = Console.ReadLine();

            if (string.IsNullOrWhiteSpace(nama))
            {
                TampilkanPesan("Nama tidak boleh kosong.", ConsoleColor.Red);

                return;
            }

            Console.Write("Program Studi  : ");
            string prodi = Console.ReadLine();

            if (string.IsNullOrWhiteSpace(prodi))
            {
                TampilkanPesan("Program Studi tidak boleh kosong.", ConsoleColor.Red);

                return;
            }

            double ipk;

            while (true)
            {
                Console.Write("IPK (0 - 4)    : ");

                string inputIPK = Console.ReadLine();

                if (
                    double.TryParse(inputIPK, out ipk)
                    && ipk >= 0
                    && ipk <= 4
                )
                {
                    break;
                }

                TampilkanPesan("IPK harus berupa angka antara 0 sampai 4.", ConsoleColor.Red);
            }

            Mahasiswa mahasiswa = new Mahasiswa(
                nim,
                nama,
                prodi,
                ipk
            );

            daftarMahasiswa.Add(mahasiswa);

            Console.WriteLine();

            TampilkanPesan("✓ Data mahasiswa berhasil ditambahkan.", ConsoleColor.Green);
        }

        static void TampilkanMahasiswa()
        {
            Console.Clear();

            Header("DAFTAR MAHASISWA", ConsoleColor.Yellow);

            if (daftarMahasiswa.Count == 0)
            {
                TampilkanPesan("Belum ada data mahasiswa.", ConsoleColor.DarkYellow);

                return;
            }

            Console.WriteLine(
                "{0,-15} {1,-25} {2,-25} {3,8}",
                "NIM",
                "Nama",
                "Program Studi",
                "IPK"
            );

            Console.WriteLine(
                new string('─', 78)
            );

            foreach (Mahasiswa m in daftarMahasiswa)
            {
                Console.WriteLine(
                    "{0,-15} {1,-25} {2,-25} {3,8:F2}",
                    m.NIM,
                    m.Nama,
                    m.Prodi,
                    m.IPK
                );
            }

            Console.WriteLine(
                new string('─', 78)
            );

            Console.WriteLine(
                $"Total mahasiswa: {daftarMahasiswa.Count}"
            );
        }

        static void CariMahasiswa()
        {
            Console.Clear();

            Header("CARI MAHASISWA", ConsoleColor.Blue);

            if (daftarMahasiswa.Count == 0)
            {
                TampilkanPesan("Belum ada data mahasiswa.", ConsoleColor.DarkYellow);

                return;
            }

            Console.WriteLine("Cari berdasarkan NIM");

            CariBerdasarkanNIM();

        }

        static void CariBerdasarkanNIM()
        {
            Console.WriteLine();

            Console.Write("Masukkan NIM: ");
            string nimCari = Console.ReadLine();

            Mahasiswa mahasiswaDitemukan = null;

            foreach (Mahasiswa m in daftarMahasiswa)
            {
                if (m.NIM.Equals(
                    nimCari,
                    StringComparison.OrdinalIgnoreCase
                ))
                {
                    mahasiswaDitemukan = m;
                    break;
                }
            }

            Console.WriteLine();

            if (mahasiswaDitemukan != null)
            {
                TampilkanDetail(mahasiswaDitemukan);
            }
            else
            {
                TampilkanPesan("Mahasiswa tidak ditemukan.", ConsoleColor.Red);
            }
        }

        static void HapusMahasiswa()
        {
            Console.Clear();

            Header("HAPUS MAHASISWA", ConsoleColor.Red);

            if (daftarMahasiswa.Count == 0)
            {
                TampilkanPesan("Belum ada data mahasiswa.", ConsoleColor.DarkYellow);

                return;
            }

            Console.Write("Masukkan NIM: ");
            string nimHapus = Console.ReadLine();

            Mahasiswa mahasiswaDitemukan = null;

            foreach (Mahasiswa m in daftarMahasiswa)
            {
                if (
                    m.NIM.Equals(
                        nimHapus,
                        StringComparison.OrdinalIgnoreCase
                    )
                )
                {
                    mahasiswaDitemukan = m;
                    break;
                }
            }

            Console.WriteLine();

            if (mahasiswaDitemukan == null)
            {
                TampilkanPesan("Mahasiswa tidak ditemukan.", ConsoleColor.Red);

                return;
            }

            TampilkanDetail(mahasiswaDitemukan);

            Console.WriteLine();

            Console.ForegroundColor = ConsoleColor.Yellow;
            Console.Write(
                "Apakah yakin ingin menghapus data ini? (y/n): "
            );
            Console.ResetColor();

            string konfirmasi = Console.ReadLine();

            if (
                konfirmasi != null
                && konfirmasi.Equals(
                    "y",
                    StringComparison.OrdinalIgnoreCase
                )
            )
            {
                daftarMahasiswa.Remove(mahasiswaDitemukan);

                Console.WriteLine();

                TampilkanPesan("✓ Data mahasiswa berhasil dihapus.", ConsoleColor.Green);
            }
            else
            {
                Console.WriteLine();

                TampilkanPesan("Penghapusan dibatalkan.", ConsoleColor.Yellow);
            }
        }

        static void TampilkanDetail(Mahasiswa mahasiswa)
        {
            Console.ForegroundColor = ConsoleColor.Cyan;

            Console.WriteLine(
                "╔════════════════════════════════════════╗"
            );

            Console.WriteLine(
                "║          DETAIL MAHASISWA              ║"
            );

            Console.WriteLine(
                "╠════════════════════════════════════════╣"
            );

            Console.ResetColor();

            Console.WriteLine(
                $"  NIM   : {mahasiswa.NIM}"
            );

            Console.WriteLine(
                $"  Nama  : {mahasiswa.Nama}"
            );

            Console.WriteLine(
                $"  Prodi : {mahasiswa.Prodi}"
            );

            Console.WriteLine(
                $"  IPK   : {mahasiswa.IPK:F2}"
            );

            Console.ForegroundColor = ConsoleColor.Cyan;

            Console.WriteLine(
                "╚════════════════════════════════════════╝"
            );

            Console.ResetColor();
        }

        static void Header(
            string judul,
            ConsoleColor warna
        )
        {
            Console.ForegroundColor = warna;

            Console.WriteLine(
                "══════════════════════════════════════════════"
            );

            Console.WriteLine(
                " " + judul
            );

            Console.WriteLine(
                "══════════════════════════════════════════════"
            );

            Console.ResetColor();

            Console.WriteLine();
        }

        static void TampilkanPesan(
            string pesan,
            ConsoleColor warna
        )
        {
            Console.ForegroundColor = warna;
            Console.WriteLine(pesan);
            Console.ResetColor();
        }

        static void Pause()
        {
            Console.WriteLine();

            Console.ForegroundColor = ConsoleColor.DarkGray;

            Console.Write(
                "Tekan ENTER untuk kembali ke menu utama..."
            );

            Console.ResetColor();

            Console.ReadLine();
        }
    }
}

