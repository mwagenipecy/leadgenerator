using LeadNet.Contracts.Auth;

namespace LeadNet.Application.Auth;

public interface IAuthService
{
    Task<LoginResponse?> LoginAsync(LoginRequest request, CancellationToken cancellationToken = default);
    Task<LoginResponse?> VerifyOtpAsync(string otpSessionId, string code, CancellationToken cancellationToken = default);
}
