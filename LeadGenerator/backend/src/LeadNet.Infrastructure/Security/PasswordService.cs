using LeadNet.Application.Services;

namespace LeadNet.Infrastructure.Security;

public sealed class PasswordService : IPasswordService
{
    public bool Verify(string plainTextPassword, string passwordHash)
        => BCrypt.Net.BCrypt.Verify(plainTextPassword, passwordHash);
}
