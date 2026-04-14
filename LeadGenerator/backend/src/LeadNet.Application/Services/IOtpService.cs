using LeadNet.Domain.Entities;

namespace LeadNet.Application.Services;

public interface IOtpService
{
    (string SessionId, string Code) CreateSession(User user);
    User? Verify(string sessionId, string code);
}
