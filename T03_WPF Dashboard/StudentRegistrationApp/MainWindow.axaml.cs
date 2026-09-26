using Avalonia;
using Avalonia.Controls;
using Avalonia.Interactivity;

using System;
using System.Collections.Generic;
using System.Globalization;
using System.Linq;
using System.Threading.Tasks;

namespace StudentRegistrationApp
{
    public partial class MainWindow : Window
    {
        private readonly List<Mahasiswa> daftarMahasiswa = new();
        private Mahasiswa? mahasiswaSedangDiedit = null;

        public MainWindow(){
            InitializeComponent();
            RefreshData();
            UpdateDashboard();
        }

        public class Mahasiswa{
            public string NIM{
                get;
                set;
            }

            public string Nama{
                get;
                set;
            }

            public string Prodi{
                get;
                set;
            }

            public double IPK{
                get;
                set;
            }

            public Mahasiswa(
                string nim,
                string nama,
                string prodi,
                double ipk
            ){
                NIM = nim;
                Nama = nama;
                Prodi = prodi;
                IPK = ipk;
            }
        }

        private async void BtnSimpan_Click(object? sender, RoutedEventArgs e){
            string nim = txtNim.Text?.Trim() ?? "";
            string nama = txtNama.Text?.Trim() ?? "";

            if (string.IsNullOrWhiteSpace(nim)){
                await TampilkanPesan("Validasi", "NIM harus diisi.");
                txtNim.Focus();
                return;
            }

            if (string.IsNullOrWhiteSpace(nama)){
                await TampilkanPesan("Validasi", "Nama mahasiswa harus diisi.");
                txtNama.Focus();
                return;
            }

            if (cmbProdi.SelectedItem is not ComboBoxItem selectedProdi){
                await TampilkanPesan("Validasi", "Program studi harus dipilih.");
                return;
            }

            string prodi = selectedProdi.Content?.ToString() ?? "";

            if (!CobaParseIPK(txtIPK.Text, out double ipk)){
                await TampilkanPesan("Validasi", "IPK harus berupa angka.");
                txtIPK.Focus();
                return;
            }

            if (ipk < 0 || ipk > 4){
                await TampilkanPesan("Validasi", "IPK harus berada antara 0 sampai 4.");
                txtIPK.Focus();
                return;
            }

            if (mahasiswaSedangDiedit == null){
                bool nimSudahAda = daftarMahasiswa.Any(m => m.NIM.Equals(nim, StringComparison.OrdinalIgnoreCase));

                if (nimSudahAda){
                    await TampilkanPesan("Validasi", "NIM tersebut sudah terdaftar.");
                    txtNim.Focus();
                    return;
                }

                Mahasiswa mahasiswaBaru = new Mahasiswa(nim, nama, prodi, ipk);

                daftarMahasiswa.Add(mahasiswaBaru);

                await TampilkanPesan("Berhasil","Data mahasiswa berhasil ditambahkan.");
            }
            else{
                bool nimDipakaiMahasiswaLain = daftarMahasiswa.Any(
                    m =>
                        m != mahasiswaSedangDiedit
                        &&
                        m.NIM.Equals(
                            nim,
                            StringComparison.OrdinalIgnoreCase
                        )
                );

                if (nimDipakaiMahasiswaLain){
                    await TampilkanPesan("Validasi","NIM sudah digunakan mahasiswa lain.");
                    txtNim.Focus();
                    return;

                }

                mahasiswaSedangDiedit.NIM = nim;
                mahasiswaSedangDiedit.Nama = nama;
                mahasiswaSedangDiedit.Prodi = prodi;
                mahasiswaSedangDiedit.IPK = ipk;

                await TampilkanPesan("Berhasil", "Data mahasiswa berhasil diperbarui.");
            }

            RefreshData();
            UpdateDashboard();
            ResetForm();
        }

        private async void BtnEdit_Click(object? sender, RoutedEventArgs e){
            if (dgMahasiswa.SelectedItem is not Mahasiswa mahasiswa){
                await TampilkanPesan("Informasi","Pilih mahasiswa yang ingin diedit.");
                return;
            }

            mahasiswaSedangDiedit = mahasiswa;

            txtNim.Text = mahasiswa.NIM;
            txtNama.Text = mahasiswa.Nama;
            txtIPK.Text = mahasiswa.IPK.ToString("0.00", CultureInfo.InvariantCulture);

            foreach (object? item in cmbProdi.Items){
                if (item is ComboBoxItem comboItem && comboItem.Content?.ToString() == mahasiswa.Prodi
                ){
                    cmbProdi.SelectedItem = comboItem;
                    break;
                }
            }

            txtFormTitle.Text = "Edit Mahasiswa";
            btnSimpan.Content = "Simpan Perubahan";

            txtNim.Focus();
        }

        private async void BtnHapus_Click(object? sender, RoutedEventArgs e){
            if (dgMahasiswa.SelectedItem is not Mahasiswa mahasiswa){
                await TampilkanPesan("Informasi", "Pilih mahasiswa yang ingin dihapus.");
                return;

            }

            bool konfirmasi = await TampilkanKonfirmasi("Konfirmasi Hapus", $"Apakah yakin ingin menghapus data {mahasiswa.Nama}?");

            if (!konfirmasi){
                return;
            }

            daftarMahasiswa.Remove(mahasiswa);

            if (mahasiswaSedangDiedit == mahasiswa){
                ResetForm();
            }

            RefreshData();
            UpdateDashboard();

            await TampilkanPesan("Berhasil", "Data mahasiswa berhasil dihapus.");
        }

        private void BtnReset_Click(object? sender, RoutedEventArgs e){
            ResetForm();
        }

        private void ResetForm(){
            txtNim.Clear();
            txtNama.Clear();
            txtIPK.Clear();

            cmbProdi.SelectedIndex = -1;

            mahasiswaSedangDiedit = null;

            txtFormTitle.Text = "Tambah Mahasiswa";
            btnSimpan.Content = "Simpan";

            txtNim.Focus();
        }

        private void TxtSearch_TextChanged(object? sender, TextChangedEventArgs e){
            RefreshData();
        }

        private void RefreshData(){
            string keyword = txtSearch.Text?.Trim().ToLowerInvariant()?? "";

            List<Mahasiswa> hasil;

            if (string.IsNullOrWhiteSpace(keyword)){
                hasil = daftarMahasiswa.ToList();
            }
            else{
                hasil = daftarMahasiswa
                    .Where(
                        m =>
                            m.NIM
                                .ToLowerInvariant()
                                .Contains(keyword)
                            ||
                            m.Nama
                                .ToLowerInvariant()
                                .Contains(keyword)
                            ||
                            m.Prodi
                                .ToLowerInvariant()
                                .Contains(keyword)
                    ).ToList();
            }

            dgMahasiswa.ItemsSource = hasil;
            txtJumlahData.Text = $"{hasil.Count} data mahasiswa";
        }

        private void UpdateDashboard(){
            int total = daftarMahasiswa.Count;

            txtTotal.Text = total.ToString();

            if (total == 0){
                txtRataRata.Text = "-";
                txtIPKTertinggi.Text = "-";
                return;
            }

            double rataRata = daftarMahasiswa.Average(m => m.IPK);
            double tertinggi = daftarMahasiswa.Max(m => m.IPK);

            txtRataRata.Text = rataRata.ToString("0.00");
            txtIPKTertinggi.Text = tertinggi.ToString("0.00");
        }

        private bool CobaParseIPK(string? input, out double ipk){
            ipk = 0;

            if (string.IsNullOrWhiteSpace(input)){
                return false;
            }

            if (double.TryParse(input, NumberStyles.Float, CultureInfo.CurrentCulture, out ipk)){
                return true;
            }

            if (double.TryParse(input, NumberStyles.Float, CultureInfo.InvariantCulture, out ipk)){
                return true;
            }

            string alternatif = input.Replace(',', '.');

            return double.TryParse(alternatif, NumberStyles.Float, CultureInfo.InvariantCulture, out ipk);
        }

        private async Task TampilkanPesan(string judul, string pesan){
            Window dialog = new Window{
                Title = judul,
                Width = 380,
                Height = 190,
                CanResize = false,
                WindowStartupLocation = WindowStartupLocation.CenterOwner
            };

            TextBlock isiPesan = new TextBlock{
                Text = pesan,
                TextWrapping = Avalonia.Media.TextWrapping.Wrap,
                HorizontalAlignment = Avalonia.Layout.HorizontalAlignment.Center,
                TextAlignment = Avalonia.Media.TextAlignment.Center
            };

            Button btnOK = new Button{
                Content = "OK",
                Width = 90,
                HorizontalAlignment = Avalonia.Layout.HorizontalAlignment.Center,
                HorizontalContentAlignment = Avalonia.Layout.HorizontalAlignment.Center
            };

            btnOK.Click += (_, _) =>{
                dialog.Close();
            };

            StackPanel panel = new StackPanel{
                Margin = new Thickness(25),
                Spacing = 25
            };

            panel.Children.Add(isiPesan);
            panel.Children.Add(btnOK);

            dialog.Content = panel;

            await dialog.ShowDialog(this);
        }

        private async Task<bool> TampilkanKonfirmasi(string judul,string pesan){
            bool hasil = false;

            Window dialog = new Window{
                Title = judul,
                Width = 400,
                Height = 210,
                CanResize = false,
                WindowStartupLocation = WindowStartupLocation.CenterOwner
            };

            TextBlock isiPesan = new TextBlock{
                Text = pesan,
                TextWrapping = Avalonia.Media.TextWrapping.Wrap,
                TextAlignment = Avalonia.Media.TextAlignment.Center,
                HorizontalAlignment = Avalonia.Layout.HorizontalAlignment.Center
            };

            Button btnBatal = new Button{
                Content = "Batal",
                Width = 100
            };

            Button btnHapus = new Button{
                Content = "Hapus",
                Width = 100,
                Background = Avalonia.Media.Brushes.Crimson,
                Foreground = Avalonia.Media.Brushes.White
            };

            btnBatal.Click += (_, _) =>{
                hasil = false;
                dialog.Close();
            };

            btnHapus.Click += (_, _) =>{
                hasil = true;
                dialog.Close();
            };

            StackPanel buttons = new StackPanel{
                Orientation = Avalonia.Layout.Orientation.Horizontal,
                HorizontalAlignment = Avalonia.Layout.HorizontalAlignment.Center,
                Spacing = 12
            };

            buttons.Children.Add(btnBatal);
            buttons.Children.Add(btnHapus);

            StackPanel panel = new StackPanel{
                Margin = new Thickness(25),
                Spacing = 30
            };

            panel.Children.Add(isiPesan);
            panel.Children.Add(buttons);

            dialog.Content = panel;

            await dialog.ShowDialog(this);

            return hasil;
        }
    }
}