namespace StudentRegistrationApp.Models
{
    public class Mahasiswa
    {
        public int Id { get; set; }

        public string NIM { get; set; } = "";

        public string Nama { get; set; } = "";

        public string Prodi { get; set; } = "";

        public double IPK { get; set; }
    }
}