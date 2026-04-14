using LeadNet.Application.Services;
using LeadNet.Domain.Entities;
using Microsoft.EntityFrameworkCore;

namespace LeadNet.Infrastructure.Data;

public sealed class UserRepository(AppDbContext dbContext) : IUserRepository
{
    public Task<User?> FindByLoginAsync(string login, CancellationToken cancellationToken = default)
    {
        return dbContext.Users
            .AsNoTracking()
            .Where(x => x.Email == login || x.Phone == login)
            .FirstOrDefaultAsync(cancellationToken);
    }
}
