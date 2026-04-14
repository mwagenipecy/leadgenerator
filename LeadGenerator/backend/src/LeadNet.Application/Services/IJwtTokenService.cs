using LeadNet.Domain.Entities;

namespace LeadNet.Application.Services;

public interface IJwtTokenService
{
    string Generate(User user);
}
