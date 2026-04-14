using System.Security.Claims;
using LeadNet.Application.Auth;
using LeadNet.Application.Services;
using LeadNet.Contracts.Auth;
using LeadNet.Domain.Entities;
using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;

namespace LeadNet.Api.Controllers;

[ApiController]
[Route("api/auth")]
public sealed class AuthController(
    IAuthService authService,
    IActivityLogService activityLogService) : ControllerBase
{
    [HttpPost("login")]
    [AllowAnonymous]
    public async Task<IActionResult> Login([FromBody] LoginRequest request, CancellationToken cancellationToken)
    {
        var result = await authService.LoginAsync(request, cancellationToken);
        var log = new ActivityLog
        {
            EventType = result is null ? "LOGIN_FAILED" : "LOGIN_OTP_SENT",
            UserId = null,
            EmailOrPhone = request.Login,
            Role = null,
            Realm = null,
            IpAddress = HttpContext.Connection.RemoteIpAddress?.ToString(),
            UserAgent = HttpContext.Request.Headers.UserAgent.ToString(),
            Metadata = result is null ? "Invalid credentials" : "OTP sent for verification"
        };
        await activityLogService.LogAsync(log, cancellationToken);

        return result is null ? Unauthorized(new { message = "Invalid credentials." }) : Ok(result);
    }

    [HttpPost("otp/verify")]
    [AllowAnonymous]
    public async Task<IActionResult> VerifyOtp([FromBody] OtpVerifyRequest request, CancellationToken cancellationToken)
    {
        var result = await authService.VerifyOtpAsync(request.OtpSessionId, request.Code, cancellationToken);
        var log = new ActivityLog
        {
            EventType = result is null ? "OTP_VERIFY_FAILED" : "LOGIN_SUCCESS",
            UserId = result?.User is null ? null : Guid.Parse(result.User.Id),
            EmailOrPhone = null,
            Role = result?.User?.Role,
            Realm = result?.User?.Realm,
            IpAddress = HttpContext.Connection.RemoteIpAddress?.ToString(),
            UserAgent = HttpContext.Request.Headers.UserAgent.ToString(),
            Metadata = result is null ? "Invalid OTP code" : "OTP verification successful"
        };
        await activityLogService.LogAsync(log, cancellationToken);

        return result is null ? Unauthorized(new { message = "Invalid OTP code." }) : Ok(result);
    }

    [HttpGet("me")]
    [Authorize]
    public IActionResult Me()
    {
        var dto = new UserDto(
            User.FindFirstValue(ClaimTypes.NameIdentifier) ?? string.Empty,
            User.FindFirstValue(ClaimTypes.Name) ?? string.Empty,
            User.FindFirstValue(ClaimTypes.Role) ?? string.Empty,
            User.FindFirstValue("realm") ?? string.Empty);

        return Ok(dto);
    }

    [HttpPost("logout")]
    [Authorize]
    public async Task<IActionResult> Logout(CancellationToken cancellationToken)
    {
        var userIdRaw = User.FindFirstValue(ClaimTypes.NameIdentifier);
        var parsedUserId = Guid.TryParse(userIdRaw, out var userId) ? userId : (Guid?)null;

        var log = new ActivityLog
        {
            EventType = "LOGOUT",
            UserId = parsedUserId,
            Role = User.FindFirstValue(ClaimTypes.Role),
            Realm = User.FindFirstValue("realm"),
            IpAddress = HttpContext.Connection.RemoteIpAddress?.ToString(),
            UserAgent = HttpContext.Request.Headers.UserAgent.ToString(),
            Metadata = "User logout"
        };
        await activityLogService.LogAsync(log, cancellationToken);

        return Ok(new { message = "Logged out." });
    }
}
