namespace LeadNet.Domain.Entities;

public sealed class ActivityLog
{
    public Guid Id { get; set; } = Guid.NewGuid();
    public Guid? UserId { get; set; }
    public string EventType { get; set; } = string.Empty;
    public string? EmailOrPhone { get; set; }
    public string? Role { get; set; }
    public string? Realm { get; set; }
    public string? IpAddress { get; set; }
    public string? UserAgent { get; set; }
    public string? Metadata { get; set; }
    public DateTime OccurredAtUtc { get; set; } = DateTime.UtcNow;
}
