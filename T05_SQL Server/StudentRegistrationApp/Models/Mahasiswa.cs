using System;

namespace StudentRegistrationApp.Models
{
    public class Mahasiswa
    {
        public int Id { get; set; }
        public string NIM { get; set; } = "";
        public string Nama { get; set; } = "";
        public int ProgramId { get; set; }
        public string Prodi { get; set; } = "";
        public double IPK { get; set; }
        public DateTime CreatedAt { get; set; }
        public DateTime? UpdatedAt { get; set; }
    }
}