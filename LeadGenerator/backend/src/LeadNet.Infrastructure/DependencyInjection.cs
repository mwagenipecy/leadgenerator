using LeadNet.Application.Auth;
using LeadNet.Application.Services;
using LeadNet.Infrastructure.Auth;
using LeadNet.Infrastructure.Data;
using LeadNet.Infrastructure.Logging;
using LeadNet.Infrastructure.Security;
using Microsoft.EntityFrameworkCore;
using Microsoft.Extensions.Configuration;
using Microsoft.Extensions.DependencyInjection;

namespace LeadNet.Infrastructure;

public static class DependencyInjection
{
    public static IServiceCollection AddLeadNetInfrastructure(this IServiceCollection services, IConfiguration configuration)
    {
        var connectionString = configuration.GetConnectionString("DefaultConnection")
            ?? "Host=localhost;Port=5432;Database=lead_net;Username=mac;Password=password";

        services.AddDbContext<AppDbContext>(options => options.UseNpgsql(connectionString));

        services.AddScoped<IAuthService, AuthService>();
        services.AddScoped<IUserRepository, UserRepository>();
        services.AddScoped<IPasswordService, PasswordService>();
        services.AddScoped<IJwtTokenService, JwtTokenService>();
        services.AddScoped<IActivityLogService, ActivityLogService>();
        services.AddSingleton<IOtpService, OtpService>();
        return services;
    }
}
