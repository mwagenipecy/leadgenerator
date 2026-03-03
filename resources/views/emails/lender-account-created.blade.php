<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your lender account is ready – Fanikisha Marketplace</title>
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
            padding: 36px 30px;
            text-align: center;
        }
        .header .logo-wrap {
            display: inline-block;
            background: #ffffff;
            padding: 16px 24px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0 0 8px 0;
            font-size: 24px;
            font-weight: 700;
        }
        .header .subtitle {
            margin: 0;
            font-size: 15px;
            opacity: 0.95;
        }
        .content { padding: 36px 30px; }
        .content h2 {
            font-size: 18px;
            color: #1a1a1a;
            margin-bottom: 16px;
        }
        .content h3 {
            font-size: 16px;
            color: #374151;
            margin: 24px 0 12px 0;
        }
        .welcome-box {
            background: #F0FDF4;
            border: 1px solid #BBF7D0;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
        }
        .welcome-box h3 { margin-top: 0; }
        .credentials-box {
            background: #FFFBEB;
            border: 1px solid #FDE68A;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
        }
        .credentials {
            font-family: 'Consolas', 'Monaco', monospace;
            background: #1F2937;
            color: #F9FAFB;
            padding: 16px;
            border-radius: 8px;
            margin: 12px 0;
            font-size: 14px;
        }
        .btn {
            display: inline-block;
            background: #C40F11;
            color: #ffffff !important;
            text-decoration: none;
            padding: 14px 28px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            margin: 20px 0;
        }
        .btn:hover { background: #A00E11; }
        .security-tips {
            background: #EFF6FF;
            border: 1px solid #DBEAFE;
            border-radius: 10px;
            padding: 20px;
            margin: 24px 0;
        }
        .security-tips h4 { margin-top: 0; margin-bottom: 12px; font-size: 14px; }
        .security-tips ul { margin: 0; padding-left: 20px; }
        .footer {
            background: #f9fafb;
            padding: 24px 30px;
            text-align: center;
            color: #6B7280;
            font-size: 13px;
            border-top: 1px solid #e5e7eb;
        }
        .footer a { color: #C40F11; text-decoration: none; }
        ul { margin: 12px 0; padding-left: 22px; line-height: 1.7; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo-wrap">
                <img src="{{ asset('landing/redlogo.png') }}" alt="Fanikisha Marketplace" style="max-width: 180px; height: auto; display: block;">
            </div>
            <h1>Your lender account is ready</h1>
            <p class="subtitle">Welcome to Fanikisha Marketplace</p>
        </div>

        <div class="content">
            <h2>Hello {{ $user->name }},</h2>

            <div class="welcome-box">
                <h3>Application approved</h3>
                <p>Your lender application has been approved. Your account is now active and you can sign in to manage your loan products and receive qualified leads from borrowers on the platform.</p>
            </div>

            <h3>Your login credentials</h3>
            <div class="credentials-box">
                <p><strong>Important:</strong> Store these details securely and change your password after your first login.</p>
                <div class="credentials">
                    <strong>Email:</strong> {{ $user->email }}<br>
                    <strong>Password:</strong> {{ $password }}
                </div>
            </div>

            <div style="text-align: center;">
                <a href="{{ url('/login') }}" class="btn">Sign in to your account</a>
            </div>

            <h3>Next steps</h3>
            <ul>
                <li>Sign in using the credentials above</li>
                <li>Complete your company and profile information</li>
                <li>Configure your loan products and lending criteria</li>
                <li>Start receiving and reviewing loan applications from borrowers</li>
                <li>Change your password for security</li>
            </ul>

            <div class="security-tips">
                <h4>Security</h4>
                <ul>
                    <li>Change your password immediately after first login</li>
                    <li>Use a strong, unique password</li>
                    <li>Do not share your login details with anyone</li>
                </ul>
            </div>
        </div>

        <div class="footer">
            <p>Need help? Contact us at <a href="mailto:info@fanikisha.com">info@fanikisha.com</a></p>
            <p>&copy; {{ date('Y') }} Fanikisha Marketplace. All rights reserved. Powered by CreditInfo Tanzania.</p>
        </div>
    </div>
</body>
</html>
