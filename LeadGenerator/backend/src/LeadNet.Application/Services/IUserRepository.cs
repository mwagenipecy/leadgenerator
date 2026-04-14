using LeadNet.Domain.Entities;

namespace LeadNet.Application.Services;

public interface IUserRepository
{
    Task<User?> FindByLoginAsync(string login, CancellationToken cancellationToken = default);
}
