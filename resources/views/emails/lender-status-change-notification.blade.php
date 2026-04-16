<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lender account status update - Fanikisha Marketplace</title>
</head>
<body style="margin:0;padding:20px;background:#f5f5f5;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;color:#374151;">
    <table width="100%" cellpadding="0" cellspacing="0" style="padding:20px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="max-width:600px;background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,0.08);">
                    <tr>
                        <td align="center" style="background:#ffffff;border-bottom:1px solid #E5E7EB;padding:28px 24px;">
                            <img src="{{ rtrim(config('app.url'), '/') }}/landing/redlogo.png" alt="Fanikisha Marketplace" style="max-width:180px;height:auto;display:block;background:#ffffff;padding:10px 16px;border-radius:8px;">
                            <p style="font-size:14px;color:#C40F11;margin:14px 0 0;">Lender status notification</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:30px 28px;">
                            @if($isAdminNotification)
                                <h2 style="font-size:22px;color:#111827;margin:0 0 12px;">Administrator notification</h2>
                                <p style="font-size:14px;color:#4B5563;line-height:1.7;margin:0 0 16px;">The status of a lender account on Fanikisha Marketplace has been changed.</p>
                            @else
                                <h2 style="font-size:22px;color:#111827;margin:0 0 12px;">Dear {{ $user->name ?? 'Valued Lender' }},</h2>
                                <p style="font-size:14px;color:#4B5563;line-height:1.7;margin:0 0 16px;">This email confirms that your lender account status on Fanikisha Marketplace has been updated.</p>
                            @endif

                            <div style="padding:20px;margin:0 0 16px;border-radius:10px;border-left:4px solid {{ $isDisabled ? '#DC2626' : '#16A34A' }};background:{{ $isDisabled ? '#FEF2F2' : '#F0FDF4' }};">
                                <h3 style="margin:0 0 8px;font-size:16px;color:{{ $isDisabled ? '#991B1B' : '#166534' }};">Current status: {{ $isDisabled ? 'Disabled' : 'Enabled' }}</h3>
                                <p style="margin:0;font-size:14px;color:#4B5563;line-height:1.7;">
                                    @if($isDisabled)
                                        Your lender account has been disabled. All associated loan products and user accounts have been deactivated. You will not be able to access the platform until the account is re-enabled.
                                    @else
                                        Your lender account has been enabled. All associated loan products and user accounts have been reactivated. You can access the platform as usual.
                                    @endif
                                </p>
                            </div>

                            <div style="background:#F9FAFB;border:1px solid #E5E7EB;border-radius:10px;padding:20px;margin:0 0 16px;">
                                <p style="margin:0 0 10px;color:#374151;"><strong>Lender details</strong></p>
                                <p style="margin:6px 0;color:#4B5563;"><strong>Company:</strong> {{ $lender->company_name }}</p>
                                <p style="margin:6px 0;color:#4B5563;"><strong>Contact:</strong> {{ $lender->contact_person }}</p>
                                <p style="margin:6px 0;color:#4B5563;"><strong>Email:</strong> {{ $lender->email }}</p>
                                <p style="margin:6px 0;color:#4B5563;"><strong>Phone:</strong> {{ $lender->phone }}</p>
                                <p style="margin:6px 0;color:#4B5563;"><strong>Status updated:</strong> {{ now()->format('F j, Y \a\t g:i A') }}</p>
                            </div>

                            <div style="padding:16px 20px;margin:0 0 16px;border-radius:10px;border-left:4px solid {{ $isDisabled ? '#F59E0B' : '#16A34A' }};background:{{ $isDisabled ? '#FFFBEB' : '#F0FDF4' }};">
                                @if($isDisabled)
                                    <p style="margin:0;font-size:14px;color:#4B5563;line-height:1.7;"><strong>Please note:</strong> All loan products linked to this lender are disabled, linked user accounts are deactivated, and access will be restored after re-enable.</p>
                                @else
                                    <p style="margin:0;font-size:14px;color:#4B5563;line-height:1.7;"><strong>Account reactivated:</strong> Loan products are enabled and linked user accounts can sign in again.</p>
                                @endif
                            </div>

                            @if(!$isAdminNotification)
                                <p style="font-size:14px;color:#4B5563;line-height:1.7;margin:0;">If you have any questions, please contact us at <a href="mailto:info@fanikisha.com" style="color:#C40F11;text-decoration:none;">info@fanikisha.com</a>.</p>
                            @endif
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
