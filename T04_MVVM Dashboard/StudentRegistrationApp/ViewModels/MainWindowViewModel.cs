using CommunityToolkit.Mvvm.ComponentModel;
using CommunityToolkit.Mvvm.Input;

using StudentRegistrationApp.Models;
using StudentRegistrationApp.Repositories;

using System;
using System.Collections.ObjectModel;
using System.Globalization;
using System.Linq;

namespace StudentRegistrationApp.ViewModels
{
    public partial class MainWindowViewModel : ViewModelBase
    {
        private readonly IMahasiswaRepository _repository;

        private Mahasiswa? mahasiswaSedangDiedit;

        public ObservableCollection<Mahasiswa> DaftarMahasiswa{
            get;
        } = new();

        public ObservableCollection<Mahasiswa> MahasiswaTampil{
            get;
        } = new();

        public ObservableCollection<string> DaftarProdi{
            get;
        } = new()
        {
            "Teknik Informatika",
            "Sistem Informasi",
            "Teknik Komputer",
            "Teknologi Informasi",
            "Sains Data"
        };

        [ObservableProperty]
        private string nim = "";

        [ObservableProperty]
        private string nama = "";

        [ObservableProperty]
        private string prodi = "";

        [ObservableProperty]
        private string ipk = "";

        [ObservableProperty]
        private string keywordSearch = "";

        [ObservableProperty]
        private Mahasiswa? mahasiswaTerpilih;

        [ObservableProperty]
        private string formTitle = "Tambah Mahasiswa";

        [ObservableProperty]
        private string simpanButtonText = "Simpan";

        [ObservableProperty]
        private string pesan = "";

        public int TotalMahasiswa =>
            DaftarMahasiswa.Count;

        public double RataRataIPK =>
            DaftarMahasiswa.Count == 0
                ? 0
                : DaftarMahasiswa.Average(m => m.IPK);

        public double IPKTertinggi =>
            DaftarMahasiswa.Count == 0
                ? 0
                : DaftarMahasiswa.Max(m => m.IPK);

        public string JumlahData =>
            $"{MahasiswaTampil.Count} data mahasiswa";

        public MainWindowViewModel(IMahasiswaRepository repository){
            _repository = repository;

            LoadData();
        }

        partial void OnKeywordSearchChanged(string value){
            RefreshData();
        }

        [RelayCommand]
        private void Simpan(){
            string nimInput = Nim.Trim();
            string namaInput = Nama.Trim();
            string prodiInput = Prodi.Trim();

            if (string.IsNullOrWhiteSpace(nimInput)){
                Pesan = "NIM harus diisi.";
                return;
            }

            if (string.IsNullOrWhiteSpace(namaInput)){
                Pesan = "Nama mahasiswa harus diisi.";
                return;
            }

            if (string.IsNullOrWhiteSpace(prodiInput)){
                Pesan = "Program studi harus dipilih.";
                return;
            }

            if (!CobaParseIPK(Ipk, out double nilaiIpk)){
                Pesan = "IPK harus berupa angka.";
                return;
            }

            if (nilaiIpk < 0 || nilaiIpk > 4){
                Pesan = "IPK harus berada antara 0 sampai 4.";
                return;
            }

            if (mahasiswaSedangDiedit == null){
                if (_repository.NIMExists(nimInput)){
                    Pesan = "NIM tersebut sudah terdaftar.";
                    return;
                }

                Mahasiswa mahasiswaBaru = new Mahasiswa{
                    NIM = nimInput,
                    Nama = namaInput,
                    Prodi = prodiInput,
                    IPK = nilaiIpk
                };

                _repository.Add(mahasiswaBaru);

                Pesan = "Data mahasiswa berhasil ditambahkan.";
            }
            else{
                if (
                    _repository.NIMExists(
                        nimInput,
                        mahasiswaSedangDiedit.Id
                    )
                ){
                    Pesan = "NIM sudah digunakan mahasiswa lain.";
                    return;
                }

                mahasiswaSedangDiedit.NIM = nimInput;
                mahasiswaSedangDiedit.Nama = namaInput;
                mahasiswaSedangDiedit.Prodi = prodiInput;
                mahasiswaSedangDiedit.IPK = nilaiIpk;

                _repository.Update(
                    mahasiswaSedangDiedit
                );

                Pesan = "Data mahasiswa berhasil diperbarui.";
            }

            LoadData();
            ResetForm();
        }

        [RelayCommand]
        private void Edit(){
            if (MahasiswaTerpilih == null){
                Pesan = "Pilih mahasiswa yang ingin diedit.";
                return;
            }

            mahasiswaSedangDiedit =
                MahasiswaTerpilih;

            Nim =
                mahasiswaSedangDiedit.NIM;

            Nama =
                mahasiswaSedangDiedit.Nama;

            Prodi =
                mahasiswaSedangDiedit.Prodi;

            Ipk =
                mahasiswaSedangDiedit.IPK.ToString(
                    "0.00",
                    CultureInfo.InvariantCulture
                );

            FormTitle =
                "Edit Mahasiswa";

            SimpanButtonText =
                "Simpan Perubahan";

            Pesan = "";
        }

        [RelayCommand]
        private void Hapus(){
            if (MahasiswaTerpilih == null){
                Pesan = "Pilih mahasiswa yang ingin dihapus.";
                return;
            }

            _repository.Delete(
                MahasiswaTerpilih
            );

            MahasiswaTerpilih = null;

            LoadData();

            Pesan =
                "Data mahasiswa berhasil dihapus.";
        }

        [RelayCommand]
        private void Reset(){
            ResetForm();

            Pesan = "";
        }

        private void LoadData(){
            DaftarMahasiswa.Clear();

            foreach (
                Mahasiswa mahasiswa
                in _repository.GetAll()
            ){
                DaftarMahasiswa.Add(
                    mahasiswa
                );
            }

            RefreshData();
            RefreshStatistics();
        }

        private void ResetForm(){
            Nim = "";
            Nama = "";
            Prodi = "";
            Ipk = "";

            mahasiswaSedangDiedit =
                null;

            FormTitle =
                "Tambah Mahasiswa";

            SimpanButtonText =
                "Simpan";
        }

        private void RefreshData(){
            MahasiswaTampil.Clear();

            string keyword =
                KeywordSearch
                    .Trim()
                    .ToLowerInvariant();

            var hasil =
                DaftarMahasiswa.AsEnumerable();

            if (!string.IsNullOrWhiteSpace(keyword)){
                hasil =
                    hasil.Where(
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
                    );
            }

            foreach (
                Mahasiswa mahasiswa
                in hasil
            ){
                MahasiswaTampil.Add(
                    mahasiswa
                );
            }

            OnPropertyChanged(
                nameof(JumlahData)
            );
        }

        private void RefreshStatistics(){
            OnPropertyChanged(
                nameof(TotalMahasiswa)
            );

            OnPropertyChanged(
                nameof(RataRataIPK)
            );

            OnPropertyChanged(
                nameof(IPKTertinggi)
            );

            OnPropertyChanged(
                nameof(JumlahData)
            );
        }

        private bool CobaParseIPK(
            string? input,
            out double ipk
        ){
            ipk = 0;

            if (string.IsNullOrWhiteSpace(input)){
                return false;
            }

            if (
                double.TryParse(
                    input,
                    NumberStyles.Float,
                    CultureInfo.CurrentCulture,
                    out ipk
                )
            ){
                return true;
            }

            if (
                double.TryParse(
                    input,
                    NumberStyles.Float,
                    CultureInfo.InvariantCulture,
                    out ipk
                )
            ){
                return true;
            }

            string alternatif =
                input.Replace(',', '.');

            return double.TryParse(
                alternatif,
                NumberStyles.Float,
                CultureInfo.InvariantCulture,
                out ipk
            );
        }
    }
}