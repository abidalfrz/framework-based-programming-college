namespace StudentRegistrationApp.Data
{
    public static class DatabaseConfig
    {
        public const string ConnectionString =
            "Server=localhost,1433;" +
            "Database=StudentRegistrationDB;" +
            "User Id=sa;" +
            "Password=StrongPass12345678;" +
            "TrustServerCertificate=True;";
    }
}