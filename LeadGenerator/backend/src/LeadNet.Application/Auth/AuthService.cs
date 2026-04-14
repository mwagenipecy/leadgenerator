using LeadNet.Application.Services;
using LeadNet.Contracts.Auth;

namespace LeadNet.Application.Auth;

public sealed class AuthService(
    IUserRepository userRepository,
    IPasswordService passwordService,
    IJwtTokenService jwtTokenService,
    IOtpService otpService) : IAuthService
{
    public async Task<LoginResponse?> LoginAsync(LoginRequest request, CancellationToken cancellationToken = default)
    {
        var user = await userRepository.FindByLoginAsync(request.Login, cancellationToken);
        if (user is null || !user.IsActive)
        {
            return null;
        }

        var isValidPassword = passwordService.Verify(request.Password, user.PasswordHash);
        if (!isValidPassword)
        {
            return null;
        }

        var otp = otpService.CreateSession(user);
        return new LoginResponse(null, null, true, otp.SessionId, otp.Code);
    }

    public Task<LoginResponse?> VerifyOtpAsync(string otpSessionId, string code, CancellationToken cancellationToken = default)
    {
        var user = otpService.Verify(otpSessionId, code);
        if (user is null)
        {
            return Task.FromResult<LoginResponse?>(null);
        }

        var token = jwtTokenService.Generate(user);
        var userDto = new UserDto(user.Id.ToString(), user.Name, user.Role, string.Empty);
        return Task.FromResult<LoginResponse?>(new LoginResponse(token, userDto, false, null, null));
    }
}
