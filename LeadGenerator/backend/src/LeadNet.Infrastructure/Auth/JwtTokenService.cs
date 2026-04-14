using System.IdentityModel.Tokens.Jwt;
using System.Security.Claims;
using System.Text;
using LeadNet.Application.Services;
using LeadNet.Domain.Entities;
using Microsoft.Extensions.Configuration;
using Microsoft.IdentityModel.Tokens;

namespace LeadNet.Infrastructure.Auth;

public sealed class JwtTokenService(IConfiguration configuration) : IJwtTokenService
{
    public string Generate(User user)
    {
        var key = configuration["Jwt:Key"] ?? throw new InvalidOperationException("Missing Jwt:Key");
        var issuer = configuration["Jwt:Issuer"] ?? "lead-net";
        var audience = configuration["Jwt:Audience"] ?? "lead-net-frontend";

        var claims = new[]
        {
            new Claim(ClaimTypes.NameIdentifier, user.Id.ToString()),
            new Claim(ClaimTypes.Name, user.Name),
            new Claim(ClaimTypes.Role, user.Role),
            new Claim("realm", string.Empty)
        };

        var signingKey = new SymmetricSecurityKey(Encoding.UTF8.GetBytes(key));
        var credentials = new SigningCredentials(signingKey, SecurityAlgorithms.HmacSha256);
        var token = new JwtSecurityToken(
            issuer,
            audience,
            claims,
            expires: DateTime.UtcNow.AddHours(12),
            signingCredentials: credentials);

        return new JwtSecurityTokenHandler().WriteToken(token);
    }
}
