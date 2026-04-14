namespace LeadNet.Contracts.Auth;

public sealed record OtpVerifyRequest(string OtpSessionId, string Code);
