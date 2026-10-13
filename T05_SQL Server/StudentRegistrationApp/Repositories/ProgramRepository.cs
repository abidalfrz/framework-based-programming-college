using Microsoft.Data.SqlClient;

using StudentRegistrationApp.Data;
using StudentRegistrationApp.Models;

using System.Collections.Generic;

namespace StudentRegistrationApp.Repositories
{
    public class ProgramRepository : IProgramRepository
    {
        public List<ProgramStudi> GetAllActive(){
            List<ProgramStudi> programs = new();

            const string sql = """
                SELECT
                    ProgramId,
                    ProgramCode,
                    ProgramName,
                    IsActive
                FROM Programs
                WHERE IsActive = 1
                ORDER BY ProgramName;
            """;

            using SqlConnection connection =
                new SqlConnection(DatabaseConfig.ConnectionString);

            using SqlCommand command =
                new SqlCommand(sql, connection);

            connection.Open();

            using SqlDataReader reader =
                command.ExecuteReader();

            while (reader.Read()){
                programs.Add(new ProgramStudi{
                    ProgramId = reader.GetInt32(reader.GetOrdinal("ProgramId")),
                    ProgramCode = reader.GetString(reader.GetOrdinal("ProgramCode")),
                    ProgramName = reader.GetString(reader.GetOrdinal("ProgramName")),
                    IsActive = reader.GetBoolean(reader.GetOrdinal("IsActive"))
                });
            }

            return programs;
        }
    }
}