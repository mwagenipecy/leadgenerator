using LeadNet.Domain.Entities;

namespace LeadNet.Application.Services;

public interface IActivityLogService
{
    Task LogAsync(ActivityLog log, CancellationToken cancellationToken = default);
}
