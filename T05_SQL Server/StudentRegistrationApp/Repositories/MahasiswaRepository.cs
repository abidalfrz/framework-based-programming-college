using Microsoft.Data.SqlClient;

using StudentRegistrationApp.Data;
using StudentRegistrationApp.Models;

using System;
using System.Collections.Generic;

namespace StudentRegistrationApp.Repositories
{
    public class MahasiswaRepository : IMahasiswaRepository
    {
        public List<Mahasiswa> GetAll(){
            List<Mahasiswa> mahasiswaList = new();

            const string sql = """
                SELECT
                    s.StudentId,
                    s.NIM,
                    s.Name,
                    s.ProgramId,
                    p.ProgramName,
                    s.IPK,
                    s.CreatedAt,
                    s.UpdatedAt
                FROM Students s
                INNER JOIN Programs p
                    ON s.ProgramId = p.ProgramId
                ORDER BY s.StudentId;
            """;

            using SqlConnection connection =
                new SqlConnection(DatabaseConfig.ConnectionString);

            using SqlCommand command =
                new SqlCommand(sql, connection);

            connection.Open();

            using SqlDataReader reader =
                command.ExecuteReader();

            while (reader.Read()){
                mahasiswaList.Add(new Mahasiswa{
                    Id = reader.GetInt32(reader.GetOrdinal("StudentId")),
                    NIM = reader.GetString(reader.GetOrdinal("NIM")),
                    Nama = reader.GetString(reader.GetOrdinal("Name")),
                    ProgramId = reader.GetInt32(reader.GetOrdinal("ProgramId")),
                    Prodi = reader.GetString(reader.GetOrdinal("ProgramName")),
                    IPK = Convert.ToDouble(reader.GetDecimal(reader.GetOrdinal("IPK"))),
                    CreatedAt = reader.GetDateTime(reader.GetOrdinal("CreatedAt")),
                    UpdatedAt = reader.IsDBNull(reader.GetOrdinal("UpdatedAt"))
                        ? null
                        : reader.GetDateTime(reader.GetOrdinal("UpdatedAt"))
                });
            }

            return mahasiswaList;
        }

        public Mahasiswa? GetById(int id){
            const string sql = """
                SELECT
                    s.StudentId,
                    s.NIM,
                    s.Name,
                    s.ProgramId,
                    p.ProgramName,
                    s.IPK,
                    s.CreatedAt,
                    s.UpdatedAt
                FROM Students s
                INNER JOIN Programs p
                    ON s.ProgramId = p.ProgramId
                WHERE s.StudentId = @StudentId;
            """;

            using SqlConnection connection =
                new SqlConnection(DatabaseConfig.ConnectionString);

            using SqlCommand command =
                new SqlCommand(sql, connection);

            command.Parameters.AddWithValue("@StudentId", id);

            connection.Open();

            using SqlDataReader reader =
                command.ExecuteReader();

            if (!reader.Read()){
                return null;
            }

            return new Mahasiswa{
                Id = reader.GetInt32(reader.GetOrdinal("StudentId")),
                NIM = reader.GetString(reader.GetOrdinal("NIM")),
                Nama = reader.GetString(reader.GetOrdinal("Name")),
                ProgramId = reader.GetInt32(reader.GetOrdinal("ProgramId")),
                Prodi = reader.GetString(reader.GetOrdinal("ProgramName")),
                IPK = Convert.ToDouble(reader.GetDecimal(reader.GetOrdinal("IPK"))),
                CreatedAt = reader.GetDateTime(reader.GetOrdinal("CreatedAt")),
                UpdatedAt = reader.IsDBNull(reader.GetOrdinal("UpdatedAt"))
                    ? null
                    : reader.GetDateTime(reader.GetOrdinal("UpdatedAt"))
            };
        }

        public void Add(Mahasiswa mahasiswa){
            const string sql = """
                INSERT INTO Students (
                    NIM,
                    Name,
                    ProgramId,
                    IPK
                )
                VALUES (
                    @NIM,
                    @Name,
                    @ProgramId,
                    @IPK
                );
            """;

            using SqlConnection connection =
                new SqlConnection(DatabaseConfig.ConnectionString);

            using SqlCommand command =
                new SqlCommand(sql, connection);

            command.Parameters.AddWithValue("@NIM", mahasiswa.NIM);
            command.Parameters.AddWithValue("@Name", mahasiswa.Nama);
            command.Parameters.AddWithValue("@ProgramId", mahasiswa.ProgramId);
            command.Parameters.AddWithValue("@IPK", mahasiswa.IPK);

            connection.Open();

            command.ExecuteNonQuery();
        }

        public void Update(Mahasiswa mahasiswa){
            const string sql = """
                UPDATE Students
                SET
                    NIM = @NIM,
                    Name = @Name,
                    ProgramId = @ProgramId,
                    IPK = @IPK,
                    UpdatedAt = GETDATE()
                WHERE StudentId = @StudentId;
            """;

            using SqlConnection connection =
                new SqlConnection(DatabaseConfig.ConnectionString);

            using SqlCommand command =
                new SqlCommand(sql, connection);

            command.Parameters.AddWithValue("@StudentId", mahasiswa.Id);
            command.Parameters.AddWithValue("@NIM", mahasiswa.NIM);
            command.Parameters.AddWithValue("@Name", mahasiswa.Nama);
            command.Parameters.AddWithValue("@ProgramId", mahasiswa.ProgramId);
            command.Parameters.AddWithValue("@IPK", mahasiswa.IPK);

            connection.Open();

            command.ExecuteNonQuery();
        }

        public void Delete(Mahasiswa mahasiswa){
            const string sql = """
                DELETE FROM Students
                WHERE StudentId = @StudentId;
            """;

            using SqlConnection connection =
                new SqlConnection(DatabaseConfig.ConnectionString);

            using SqlCommand command =
                new SqlCommand(sql, connection);

            command.Parameters.AddWithValue("@StudentId", mahasiswa.Id);

            connection.Open();

            command.ExecuteNonQuery();
        }

        public bool NIMExists(string nim){
            const string sql = """
                SELECT COUNT(*)
                FROM Students
                WHERE NIM = @NIM;
            """;

            using SqlConnection connection =
                new SqlConnection(DatabaseConfig.ConnectionString);

            using SqlCommand command =
                new SqlCommand(sql, connection);

            command.Parameters.AddWithValue("@NIM", nim);

            connection.Open();

            int count = Convert.ToInt32(command.ExecuteScalar());

            return count > 0;
        }

        public bool NIMExists(string nim, int exceptId){
            const string sql = """
                SELECT COUNT(*)
                FROM Students
                WHERE
                    NIM = @NIM
                    AND StudentId != @StudentId;
            """;

            using SqlConnection connection =
                new SqlConnection(DatabaseConfig.ConnectionString);

            using SqlCommand command =
                new SqlCommand(sql, connection);

            command.Parameters.AddWithValue("@NIM", nim);
            command.Parameters.AddWithValue("@StudentId", exceptId);

            connection.Open();

            int count = Convert.ToInt32(command.ExecuteScalar());

            return count > 0;
        }
    }
}