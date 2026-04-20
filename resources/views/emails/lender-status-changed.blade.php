<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Application status update - Fanikisha Marketplace</title>
</head>
<body style="margin:0;padding:20px;background:#f5f5f5;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;color:#374151;">
    <table width="100%" cellpadding="0" cellspacing="0" style="padding:20px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="max-width:600px;background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,0.08);">
                    <tr>
                        <td align="center" style="background:#ffffff;border-bottom:1px solid #E5E7EB;padding:28px 24px;">
                            <img src="{{ rtrim(config('app.url'), '/') }}/landing/redlogo.png" alt="Fanikisha Marketplace" style="max-width:180px;height:auto;display:block;background:#ffffff;padding:10px 16px;border-radius:8px;">
                            <p style="font-size:14px;color:#C40F11;margin:14px 0 0;">Application status update</p>
                            <p style="font-size:13px;color:#B91C1C;margin:8px 0 0;">{{ $lender->company_name }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:30px 28px;">
                            <h2 style="font-size:22px;color:#111827;margin:0 0 12px;">Hello {{ $lender->contact_person }},</h2>
                            @if($status === 'approved')
                                <div style="background:#F0FDF4;border:1px solid #BBF7D0;border-radius:10px;padding:20px;margin:0 0 16px;">
                                    <h3 style="margin:0 0 10px;font-size:16px;color:#166534;">Application approved</h3>
                                    <p style="font-size:14px;color:#4B5563;line-height:1.7;margin:0;">Your lender application has been approved. You will receive a separate email shortly with your login credentials. You can then sign in to Fanikisha Marketplace and start managing your loan products and leads.</p>
                                </div>
                                <p style="text-align:center;margin:24px 0;">
                                    <a href="{{ url('/login') }}" style="display:inline-block;background:#C40F11;color:#ffffff !important;text-decoration:none;padding:14px 28px;border-radius:8px;font-weight:600;font-size:16px;">Sign in to your account</a>
                                </p>
                            @elseif($status === 'rejected')
                                <div style="background:#FEF2F2;border:1px solid #FECACA;border-radius:10px;padding:20px;margin:0 0 16px;">
                                    <h3 style="margin:0 0 10px;font-size:16px;color:#991B1B;">Application not approved</h3>
                                    <p style="font-size:14px;color:#4B5563;line-height:1.7;margin:0 0 8px;">Your lender application has not been approved at this time.</p>
                                    @if($lender->rejection_reason)
                                        <p style="font-size:14px;color:#4B5563;line-height:1.7;margin:0 0 8px;"><strong>Reason:</strong> {{ $lender->rejection_reason }}</p>
                                    @endif
                                    <p style="font-size:14px;color:#4B5563;line-height:1.7;margin:0;">You may submit a new application in the future after addressing the points above. If you have questions, contact us at <a href="mailto:info@fanikisha.com" style="color:#C40F11;text-decoration:none;">info@fanikisha.com</a>.</p>
                                </div>
                            @elseif($status === 'suspended')
                                <div style="background:#FFFBEB;border:1px solid #FDE68A;border-radius:10px;padding:20px;margin:0 0 16px;">
                                    <h3 style="margin:0 0 10px;font-size:16px;color:#92400E;">Account suspended</h3>
                                    <p style="font-size:14px;color:#4B5563;line-height:1.7;margin:0;">Your lender account has been temporarily suspended. For more information and next steps, please contact our support team at <a href="mailto:info@fanikisha.com" style="color:#C40F11;text-decoration:none;">info@fanikisha.com</a>.</p>
                                </div>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="padding:22px 20px;background:#F9FAFB;border-top:1px solid #E5E7EB;">
                            <p style="font-size:12px;color:#6B7280;margin:0 0 6px;">Questions? Contact us at <a href="mailto:info@fanikisha.com" style="color:#C40F11;text-decoration:none;">info@fanikisha.com</a></p>
                            <p style="font-size:12px;color:#6B7280;margin:0 0 6px;">&copy; {{ date('Y') }} Fanikisha Marketplace. All rights reserved. Powered by CreditInfo.</p>
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
