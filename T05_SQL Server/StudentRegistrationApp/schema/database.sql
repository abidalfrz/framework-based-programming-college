IF DB_ID('StudentRegistrationDB') IS NULL
BEGIN
    CREATE DATABASE StudentRegistrationDB;
END;
GO

USE StudentRegistrationDB;
GO

IF OBJECT_ID('Programs', 'U') IS NULL
BEGIN
    CREATE TABLE Programs (
        ProgramId INT IDENTITY(1,1) PRIMARY KEY,
        ProgramCode VARCHAR(20) NOT NULL UNIQUE,
        ProgramName VARCHAR(100) NOT NULL,
        IsActive BIT NOT NULL DEFAULT 1
    );
END;
GO

IF OBJECT_ID('Students', 'U') IS NULL
BEGIN
    CREATE TABLE Students (
        StudentId INT IDENTITY(1,1) PRIMARY KEY,
        NIM VARCHAR(20) NOT NULL UNIQUE,
        Name VARCHAR(100) NOT NULL,
        ProgramId INT NOT NULL,
        IPK DECIMAL(3,2) NOT NULL,
        CreatedAt DATETIME2 NOT NULL DEFAULT GETDATE(),
        UpdatedAt DATETIME2 NULL,

        CONSTRAINT FK_Students_Programs
            FOREIGN KEY (ProgramId)
            REFERENCES Programs(ProgramId),

        CONSTRAINT CK_Students_IPK
            CHECK (IPK >= 0 AND IPK <= 4)
    );
END;
GO

IF NOT EXISTS (
    SELECT 1
    FROM Programs
)
BEGIN
    INSERT INTO Programs (
        ProgramCode,
        ProgramName
    )
    VALUES
    ('IF', 'Teknik Informatika'),
    ('SI', 'Sistem Informasi'),
    ('TK', 'Teknik Komputer'),
    ('TI', 'Teknologi Informasi'),
    ('SD', 'Sains Data');
END;
GO