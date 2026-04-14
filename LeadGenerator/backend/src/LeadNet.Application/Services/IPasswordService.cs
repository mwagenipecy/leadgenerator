namespace LeadNet.Application.Services;

public interface IPasswordService
{
    bool Verify(string plainTextPassword, string passwordHash);
}
