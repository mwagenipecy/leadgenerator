<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your admin account is ready - Fanikisha Marketplace</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
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
            padding: 32px 28px;
            text-align: center;
        }
        .content {
            padding: 30px 28px;
        }
        .credentials {
            background: #FFFBEB;
            border: 1px solid #FDE68A;
            border-radius: 10px;
            padding: 16px;
            margin: 16px 0;
        }
        .credentials pre {
            margin: 0;
            background: #1F2937;
            color: #F9FAFB;
            padding: 12px;
            border-radius: 8px;
            font-size: 14px;
            white-space: pre-wrap;
            word-break: break-word;
        }
        .btn {
            display: inline-block;
            background: #C40F11;
            color: #ffffff !important;
            text-decoration: none;
            padding: 12px 22px;
            border-radius: 8px;
            font-weight: 600;
            margin-top: 12px;
        }
        .footer {
            background: #f9fafb;
            padding: 20px 28px;
            text-align: center;
            color: #6B7280;
            font-size: 13px;
            border-top: 1px solid #e5e7eb;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Your admin account is ready</h1>
            <p>You can now sign in to Fanikisha Marketplace</p>
        </div>

        <div class="content">
            <p>Hello {{ $user->name }},</p>
            <p>Your account has been created with <strong>{{ ucfirst(str_replace('_', ' ', $user->role)) }}</strong> access.</p>

            <div class="credentials">
                <p><strong>Temporary login credentials</strong></p>
                <pre>Email: {{ $user->email }}
Password: {{ $password }}</pre>
            </div>

            <p>Please sign in and change your password immediately for security.</p>
            <p>
                <a href="{{ url('/login') }}" class="btn">Sign in</a>
            </p>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} Fanikisha Marketplace. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
