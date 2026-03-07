<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Account status update – Fanikisha Marketplace</title>
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
                <img src="{{ rtrim(config('app.url'), '/') }}/landing/redlogo.png" alt="Fanikisha Marketplace" style="max-width: 160px; height: auto; display: block;">
            </div>
            <h1>Account status update</h1>
        </div>

        <div class="content">
            <h2>Dear {{ $user->name ?? $user->email }},</h2>
            <p>This email confirms that your account status on Fanikisha Marketplace has been updated.</p>

            <div class="status-box {{ $isDisabled ? 'status-disabled' : 'status-enabled' }}">
                <h3>Current status: {{ $isDisabled ? 'Disabled' : 'Enabled' }}</h3>
                <p style="margin-bottom: 0;">
                    @if($isDisabled)
                        Your account has been disabled. You will not be able to sign in to the platform until your account is re-enabled by an administrator.
                    @else
                        Your account has been enabled. You can sign in to the platform and use your previous access permissions as before.
                    @endif
                </p>
            </div>

            <div class="info-box">
                <h3>Account details</h3>
                <p><strong>Name:</strong> {{ $user->name ?? 'N/A' }}</p>
                <p><strong>Email:</strong> {{ $user->email }}</p>
                <p><strong>Role:</strong> {{ ucfirst($user->role ?? 'User') }}</p>
                <p><strong>Status updated:</strong> {{ now()->format('F j, Y \a\t g:i A') }}</p>
            </div>

            @if($isDisabled)
                <div class="notice-box" style="background: #FFFBEB; border-left-color: #F59E0B;">
                    <p style="margin: 0;"><strong>Please note:</strong></p>
                    <ul>
                        <li>You cannot sign in to the system until the account is re-enabled</li>
                        <li>Any active sessions have been ended</li>
                        <li>Contact your administrator or <a href="mailto:info@fanikisha.com">info@fanikisha.com</a> if you have questions</li>
                    </ul>
                </div>
            @else
                <div class="notice-box" style="background: #F0FDF4; border-left-color: #16A34A;">
                    <p style="margin: 0;"><strong>Account reactivated</strong></p>
                    <ul>
                        <li>You can sign in to the system again</li>
                        <li>Your previous access permissions have been restored</li>
                    </ul>
                </div>
            @endif

            <p>If you have any questions, please contact us at <a href="mailto:info@fanikisha.com">info@fanikisha.com</a>.</p>
        </div>

        <div class="footer">
            <p>This is an automated notification from Fanikisha Marketplace.</p>
            <p>&copy; {{ date('Y') }} Fanikisha Marketplace. All rights reserved. Powered by CreditInfo Tanzania.</p>
        </div>
    </div>
</body>
</html>
