using Microsoft.EntityFrameworkCore;

using StudentRegistrationApp.Data;
using StudentRegistrationApp.Models;

using System.Collections.Generic;
using System.Linq;

namespace StudentRegistrationApp.Repositories
{
    public class MahasiswaRepository : IMahasiswaRepository
    {
        public List<Mahasiswa> GetAll(){
            using AppDbContext context = new AppDbContext();

            return context.Mahasiswa
                .AsNoTracking()
                .OrderBy(m => m.NIM)
                .ToList();
        }

        public Mahasiswa? GetById(int id){
            using AppDbContext context = new AppDbContext();

            return context.Mahasiswa
                .AsNoTracking()
                .FirstOrDefault(m => m.Id == id);
        }

        public void Add(Mahasiswa mahasiswa){
            using AppDbContext context = new AppDbContext();

            context.Mahasiswa.Add(mahasiswa);

            context.SaveChanges();
        }

        public void Update(Mahasiswa mahasiswa){
            using AppDbContext context = new AppDbContext();

            context.Mahasiswa.Update(mahasiswa);

            context.SaveChanges();
        }

        public void Delete(Mahasiswa mahasiswa){
            using AppDbContext context = new AppDbContext();

            context.Mahasiswa.Remove(mahasiswa);

            context.SaveChanges();
        }

        public bool NIMExists(string nim){
            using AppDbContext context = new AppDbContext();

            return context.Mahasiswa.Any(
                m => m.NIM == nim
            );
        }

        public bool NIMExists(string nim, int exceptId){
            using AppDbContext context = new AppDbContext();

            return context.Mahasiswa.Any(
                m =>
                    m.NIM == nim
                    &&
                    m.Id != exceptId
            );
        }
    }
}