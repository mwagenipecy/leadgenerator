<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lender account status update – Fanikisha Marketplace</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #374151;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }
        .header {
            background: linear-gradient(135deg, #C40F11 0%, #A00E11 100%);
            color: #ffffff;
            padding: 28px 30px;
            text-align: center;
        }
        .header .logo-wrap {
            display: inline-block;
            background: #ffffff;
            padding: 12px 20px;
            border-radius: 8px;
            margin-bottom: 16px;
        }
        .header h1 { margin: 0; font-size: 20px; font-weight: 700; }
        .content { padding: 32px 30px; }
        .content h2 { font-size: 18px; color: #1a1a1a; margin-bottom: 16px; }
        .content p { margin-bottom: 12px; color: #4B5563; }
        .status-box {
            padding: 20px;
            margin: 20px 0;
            border-radius: 10px;
            border-left: 4px solid;
        }
        .status-disabled {
            background: #FEF2F2;
            border-left-color: #DC2626;
        }
        .status-enabled {
            background: #F0FDF4;
            border-left-color: #16A34A;
        }
        .status-box h3 { margin-top: 0; margin-bottom: 8px; font-size: 16px; }
        .status-disabled h3 { color: #991B1B; }
        .status-enabled h3 { color: #166534; }
        .info-box {
            background: #F9FAFB;
            border: 1px solid #E5E7EB;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
        }
        .info-box h3 { margin-top: 0; margin-bottom: 12px; font-size: 15px; color: #374151; }
        .info-box p { margin: 6px 0; }
        .notice-box {
            padding: 16px 20px;
            margin: 20px 0;
            border-radius: 10px;
            border-left: 4px solid;
        }
        .notice-box ul { margin: 8px 0 0 0; padding-left: 20px; }
        .footer {
            background: #F9FAFB;
            padding: 24px 30px;
            text-align: center;
            color: #6B7280;
            font-size: 12px;
            border-top: 1px solid #E5E7EB;
        }
        .footer a { color: #C40F11; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo-wrap">
                <img src="{{ asset('landing/redlogo.png') }}" alt="Fanikisha Marketplace" style="max-width: 160px; height: auto; display: block;">
            </div>
            <h1>Lender account status update</h1>
        </div>

        <div class="content">
            @if($isAdminNotification)
                <h2>Administrator notification</h2>
                <p>The status of a lender account on Fanikisha Marketplace has been changed.</p>
            @else
                <h2>Dear {{ $user->name ?? 'Valued Lender' }},</h2>
                <p>This email confirms that your lender account status on Fanikisha Marketplace has been updated.</p>
            @endif

            <div class="status-box {{ $isDisabled ? 'status-disabled' : 'status-enabled' }}">
                <h3>Current status: {{ $isDisabled ? 'Disabled' : 'Enabled' }}</h3>
                <p style="margin-bottom: 0;">
                    @if($isDisabled)
                        Your lender account has been disabled. All associated loan products and user accounts have been deactivated. You will not be able to access the platform until the account is re-enabled.
                    @else
                        Your lender account has been enabled. All associated loan products and user accounts have been reactivated. You can access the platform as usual.
                    @endif
                </p>
            </div>

            <div class="info-box">
                <h3>Lender details</h3>
                <p><strong>Company:</strong> {{ $lender->company_name }}</p>
                <p><strong>Contact:</strong> {{ $lender->contact_person }}</p>
                <p><strong>Email:</strong> {{ $lender->email }}</p>
                <p><strong>Phone:</strong> {{ $lender->phone }}</p>
                <p><strong>Status updated:</strong> {{ now()->format('F j, Y \a\t g:i A') }}</p>
            </div>

            @if($isDisabled)
                <div class="notice-box" style="background: #FFFBEB; border-left-color: #F59E0B;">
                    <p style="margin: 0;"><strong>Please note:</strong></p>
                    <ul>
                        <li>All loan products linked to this lender are disabled</li>
                        <li>All user accounts linked to this lender are deactivated</li>
                        <li>Access will be restored when the account is re-enabled by the administrator</li>
                    </ul>
                </div>
            @else
                <div class="notice-box" style="background: #F0FDF4; border-left-color: #16A34A;">
                    <p style="margin: 0;"><strong>Account reactivated</strong></p>
                    <ul>
                        <li>Loan products linked to this lender are enabled</li>
                        <li>User accounts linked to this lender can sign in again</li>
                    </ul>
                </div>
            @endif

            @if(!$isAdminNotification)
                <p>If you have any questions, please contact us at <a href="mailto:info@fanikisha.com">info@fanikisha.com</a>.</p>
            @endif
        </div>

        <div class="footer">
            <p>This is an automated notification from Fanikisha Marketplace.</p>
            <p>&copy; {{ date('Y') }} Fanikisha Marketplace. All rights reserved. Powered by CreditInfo Tanzania.</p>
        </div>
    </div>
</body>
</html>
