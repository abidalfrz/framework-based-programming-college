using StudentRegistrationApp.Models;

using System.Collections.Generic;

namespace StudentRegistrationApp.Repositories
{
    public interface IProgramRepository
    {
        List<ProgramStudi> GetAllActive();
    }
}