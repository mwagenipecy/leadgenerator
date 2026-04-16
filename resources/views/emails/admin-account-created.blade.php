<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your admin account is ready - Fanikisha Marketplace</title>
</head>
<body style="margin:0;padding:20px;background:#f5f5f5;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;color:#374151;">
    <table width="100%" cellpadding="0" cellspacing="0" style="padding:20px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="max-width:600px;background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,0.08);">
                    <tr>
                        <td align="center" style="background:#ffffff;border-bottom:1px solid #E5E7EB;padding:28px 24px;">
                            <img src="{{ rtrim(config('app.url'), '/') }}/landing/redlogo.png" alt="Fanikisha Marketplace" style="max-width:180px;height:auto;display:block;background:#ffffff;padding:10px 16px;border-radius:8px;">
                            <p style="font-size:14px;color:#C40F11;margin:14px 0 0;">Admin account ready</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:30px 28px;">
                            <p style="font-size:15px;color:#374151;margin:0 0 4px;">Hello {{ $user->name }},</p>
                            <h2 style="font-size:22px;color:#111827;margin:0 0 12px;">Your admin account is ready</h2>
                            <p style="font-size:14px;color:#4B5563;line-height:1.6;margin:0 0 16px;">
                                Your account has been created with <strong>{{ ucfirst(str_replace('_', ' ', $user->role)) }}</strong> access.
                            </p>

                            <div style="background:#FFFBEB;border:1px solid #FDE68A;border-radius:10px;padding:16px;margin:0 0 18px;">
                                <p style="margin:0 0 10px;color:#374151;"><strong>Temporary login credentials</strong></p>
                                <pre style="margin:0;background:#1F2937;color:#F9FAFB;padding:12px;border-radius:8px;font-size:14px;white-space:pre-wrap;word-break:break-word;">Email: {{ $user->email }}
Password: {{ $password }}</pre>
                            </div>

                            <p style="font-size:14px;color:#4B5563;line-height:1.6;margin:0 0 18px;">Please sign in and change your password immediately for security.</p>
                            <p style="margin:0;">
                                <a href="{{ url('/login') }}" style="display:inline-block;background:#C40F11;color:#ffffff !important;text-decoration:none;padding:14px 28px;border-radius:8px;font-weight:600;font-size:16px;">Sign in</a>
                            </p>
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
