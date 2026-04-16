<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Team invitation - Fanikisha Marketplace</title>
</head>
<body style="margin:0;padding:20px;background:#f5f5f5;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;color:#374151;">
    <table width="100%" cellpadding="0" cellspacing="0" style="padding:20px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="max-width:600px;background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,0.08);">
                    <tr>
                        <td align="center" style="background:#ffffff;border-bottom:1px solid #E5E7EB;padding:28px 24px;">
                            <img src="{{ rtrim(config('app.url'), '/') }}/landing/redlogo.png" alt="Fanikisha Marketplace" style="max-width:180px;height:auto;display:block;background:#ffffff;padding:10px 16px;border-radius:8px;">
                            <p style="font-size:14px;color:#C40F11;margin:14px 0 0;">Team invitation</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:30px 28px;">
                            <h2 style="font-size:22px;color:#111827;margin:0 0 12px;">Fanikisha Marketplace - Team invitation</h2>
                            <p style="font-size:14px;color:#4B5563;line-height:1.7;margin:0 0 16px;">
                                {{ __('You have been invited to join the :team team!', ['team' => $invitation->team->name]) }}
                            </p>

                            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::registration()))
                                <p style="font-size:14px;color:#4B5563;line-height:1.7;margin:0 0 14px;">
                                    {{ __('If you do not have an account, you may create one by clicking the button below. After creating an account, you may click the invitation acceptance button in this email to accept the team invitation:') }}
                                </p>
                                <p style="margin:0 0 16px;">
                                    <a href="{{ route('register') }}" style="display:inline-block;background:#C40F11;color:#ffffff !important;text-decoration:none;padding:14px 28px;border-radius:8px;font-weight:600;font-size:16px;">{{ __('Create Account') }}</a>
                                </p>
                                <p style="font-size:14px;color:#4B5563;line-height:1.7;margin:0 0 16px;">
                                    {{ __('If you already have an account, you may accept this invitation by clicking the button below:') }}
                                </p>
                            @else
                                <p style="font-size:14px;color:#4B5563;line-height:1.7;margin:0 0 16px;">
                                    {{ __('You may accept this invitation by clicking the button below:') }}
                                </p>
                            @endif

                            <p style="margin:0 0 16px;">
                                <a href="{{ $acceptUrl }}" style="display:inline-block;background:#C40F11;color:#ffffff !important;text-decoration:none;padding:14px 28px;border-radius:8px;font-weight:600;font-size:16px;">{{ __('Accept Invitation') }}</a>
                            </p>

                            <div style="background:#FEF2F2;padding:14px 16px;border-radius:10px;border:1px solid #FECACA;">
                                <p style="font-size:12.5px;color:#374151;line-height:1.6;margin:0;">
                                    {{ __('If you did not expect to receive an invitation to this team, you may discard this email.') }}
                                </p>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="padding:22px 20px;background:#F9FAFB;border-top:1px solid #E5E7EB;">
                            <p style="font-size:12px;color:#6B7280;margin:0 0 6px;">&copy; {{ date('Y') }} Fanikisha Marketplace. All rights reserved.</p>
                            <p style="margin:0;">
                                <a href="{{ url('/terms') }}" style="font-size:11.5px;color:#C40F11;text-decoration:none;margin:0 9px;">Terms of Service</a>
                                <a href="{{ url('/customer-help') }}" style="font-size:11.5px;color:#C40F11;text-decoration:none;margin:0 9px;">Help Center</a>
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
