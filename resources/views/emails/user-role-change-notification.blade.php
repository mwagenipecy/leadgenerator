<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Role update - Fanikisha Marketplace</title>
</head>
<body style="margin:0;padding:20px;background:#f5f5f5;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;color:#374151;">
    <table width="100%" cellpadding="0" cellspacing="0" style="padding:20px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="max-width:600px;background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,0.08);">
                    <tr>
                        <td align="center" style="background:#ffffff;border-bottom:1px solid #E5E7EB;padding:28px 24px;">
                            <img src="{{ rtrim(config('app.url'), '/') }}/landing/redlogo.png" alt="Fanikisha Marketplace" style="max-width:180px;height:auto;display:block;background:#ffffff;padding:10px 16px;border-radius:8px;">
                            <p style="font-size:14px;color:#C40F11;margin:14px 0 0;">Role update</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:30px 28px;">
                            <h2 style="font-size:22px;color:#111827;margin:0 0 12px;">
                                Dear {{ $user->name ?? $user->email }},
                            </h2>

                            <p style="font-size:14px;color:#4B5563;line-height:1.7;margin:0 0 16px;">
                                This email confirms that your role on Fanikisha Marketplace has been updated.
                            </p>

                            <div style="background:#F9FAFB;border:1px solid #E5E7EB;border-radius:10px;padding:20px;margin:0 0 16px;">
                                <p style="margin:0 0 10px;color:#374151;"><strong>Role details</strong></p>
                                <p style="margin:6px 0;color:#4B5563;"><strong>Previous role:</strong> {{ ucfirst(str_replace('_', ' ', $oldRole)) }}</p>
                                <p style="margin:6px 0;color:#4B5563;"><strong>New role:</strong> {{ ucfirst(str_replace('_', ' ', $newRole)) }}</p>
                                <p style="margin:6px 0;color:#4B5563;"><strong>Updated at:</strong> {{ now()->format('F j, Y \a\t g:i A') }}</p>
                            </div>

                            <div style="padding:16px 20px;border-radius:10px;border-left:4px solid #C40F11;background:#FEF2F2;margin:0 0 16px;">
                                <p style="margin:0;font-size:14px;color:#4B5563;line-height:1.7;">
                                    If you did not expect this change, please contact
                                    <a href="mailto:info@fanikisha.com" style="color:#C40F11;text-decoration:none;">info@fanikisha.com</a>.
                                </p>
                            </div>

                            <p style="font-size:14px;color:#4B5563;line-height:1.7;margin:0;">
                                If you have any questions, please contact us at
                                <a href="mailto:info@fanikisha.com" style="color:#C40F11;text-decoration:none;">info@fanikisha.com</a>.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="padding:22px 20px;background:#F9FAFB;border-top:1px solid #E5E7EB;">
                            <p style="font-size:12px;color:#6B7280;margin:0 0 6px;">This is an automated notification from Fanikisha Marketplace.</p>
                            <p style="font-size:12px;color:#6B7280;margin:0 0 6px;">&copy; {{ date('Y') }} Fanikisha Marketplace. All rights reserved. Powered by CreditInfo Tanzania.</p>
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

