using LeadNet.Application.Services;
using LeadNet.Domain.Entities;
using LeadNet.Infrastructure.Data;
using Microsoft.Extensions.Logging;

namespace LeadNet.Infrastructure.Logging;

public sealed class ActivityLogService(AppDbContext dbContext, ILogger<ActivityLogService> logger) : IActivityLogService
{
    public async Task LogAsync(ActivityLog log, CancellationToken cancellationToken = default)
    {
        dbContext.ActivityLogs.Add(log);
        await dbContext.SaveChangesAsync(cancellationToken);

        logger.LogInformation(
            "UserActivity {EventType} UserId={UserId} Role={Role} Realm={Realm} Login={Login}",
            log.EventType,
            log.UserId,
            log.Role,
            log.Realm,
            log.EmailOrPhone);
    }
}
