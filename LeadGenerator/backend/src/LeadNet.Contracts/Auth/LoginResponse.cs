namespace LeadNet.Contracts.Auth;

public sealed record LoginResponse(string? AccessToken, UserDto? User, bool OtpRequired, string? OtpSessionId, string? DevOtpCode);
