using System.Collections.Concurrent;
using LeadNet.Application.Services;
using LeadNet.Domain.Entities;

namespace LeadNet.Infrastructure.Auth;

public sealed class OtpService : IOtpService
{
    private readonly ConcurrentDictionary<string, (User User, string Code, DateTime ExpiresAtUtc)> _sessions = new();

    public (string SessionId, string Code) CreateSession(User user)
    {
        var sessionId = Guid.NewGuid().ToString("N");
        var code = Random.Shared.Next(100000, 999999).ToString();
        _sessions[sessionId] = (user, code, DateTime.UtcNow.AddMinutes(5));
        return (sessionId, code);
    }

    public User? Verify(string sessionId, string code)
    {
        if (!_sessions.TryGetValue(sessionId, out var session))
        {
            return null;
        }

        if (session.ExpiresAtUtc < DateTime.UtcNow || session.Code != code)
        {
            return null;
        }

        _sessions.TryRemove(sessionId, out _);
        return session.User;
    }
}
