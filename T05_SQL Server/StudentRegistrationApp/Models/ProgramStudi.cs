namespace StudentRegistrationApp.Models
{
    public class ProgramStudi
    {
        public int ProgramId { get; set; }
        public string ProgramCode { get; set; } = "";
        public string ProgramName { get; set; } = "";
        public bool IsActive { get; set; }

        public override string ToString(){
            return ProgramName;
        }
    }
}