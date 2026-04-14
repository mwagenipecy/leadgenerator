using LeadNet.Domain.Entities;
using Microsoft.EntityFrameworkCore;

namespace LeadNet.Infrastructure.Data;

public sealed class AppDbContext(DbContextOptions<AppDbContext> options) : DbContext(options)
{
    public DbSet<User> Users => Set<User>();
    public DbSet<ActivityLog> ActivityLogs => Set<ActivityLog>();

    protected override void OnModelCreating(ModelBuilder modelBuilder)
    {
        modelBuilder.Entity<User>(entity =>
        {
            entity.ToTable("users");
            entity.HasKey(x => x.Id);
            entity.Property(x => x.Name).HasColumnName("name").IsRequired();
            entity.Property(x => x.Email).HasColumnName("email");
            entity.Property(x => x.Phone).HasColumnName("phone");
            entity.Property(x => x.PasswordHash).HasColumnName("password").IsRequired();
            entity.Property(x => x.Role).HasColumnName("role").IsRequired();
            entity.Property(x => x.IsActive).HasColumnName("is_active").IsRequired();
        });

        modelBuilder.Entity<ActivityLog>(entity =>
        {
            entity.ToTable("activity_logs");
            entity.HasKey(x => x.Id);
            entity.Property(x => x.EventType).HasColumnName("event_type").IsRequired();
            entity.Property(x => x.EmailOrPhone).HasColumnName("email_or_phone");
            entity.Property(x => x.Role).HasColumnName("role");
            entity.Property(x => x.Realm).HasColumnName("realm");
            entity.Property(x => x.IpAddress).HasColumnName("ip_address");
            entity.Property(x => x.UserAgent).HasColumnName("user_agent");
            entity.Property(x => x.Metadata).HasColumnName("metadata");
            entity.Property(x => x.OccurredAtUtc).HasColumnName("occurred_at_utc").IsRequired();
        });
    }
}
