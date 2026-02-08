<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Status Change</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #10b981;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background-color: #f9fafb;
            padding: 30px;
            border: 1px solid #e5e7eb;
        }
        .status-box {
            background-color: {{ $isDisabled ? '#fee2e2' : '#d1fae5' }};
            border-left: 4px solid {{ $isDisabled ? '#ef4444' : '#10b981' }};
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .info-box {
            background-color: white;
            padding: 15px;
            margin: 15px 0;
            border-radius: 4px;
            border: 1px solid #e5e7eb;
        }
        .footer {
            text-align: center;
            padding: 20px;
            color: #6b7280;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Account Status Change</h1>
    </div>

    <div class="content">
        <p>Dear {{ $user->name ?? $user->email }},</p>
        <p>This is to inform you that your account status has been updated.</p>

        <div class="status-box">
            <h3 style="margin-top: 0; color: {{ $isDisabled ? '#991b1b' : '#065f46' }};">
                Status: {{ $isDisabled ? 'DISABLED' : 'ENABLED' }}
            </h3>
            <p style="margin-bottom: 0;">
                @if($isDisabled)
                    Your account has been disabled. You will not be able to access the system until your account is re-enabled.
                @else
                    Your account has been enabled. You can now access the system normally.
                @endif
            </p>
        </div>

        <div class="info-box">
            <h3 style="margin-top: 0;">Account Information:</h3>
            <p><strong>Name:</strong> {{ $user->name ?? 'N/A' }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Role:</strong> {{ ucfirst($user->role ?? 'User') }}</p>
            <p><strong>Status Changed:</strong> {{ now()->format('F d, Y \a\t g:i A') }}</p>
        </div>

        @if($isDisabled)
            <div class="info-box" style="background-color: #fef3c7; border-left-color: #f59e0b;">
                <p><strong>Important Notice:</strong></p>
                <ul>
                    <li>You will not be able to log in to the system</li>
                    <li>All your active sessions have been terminated</li>
                    <li>Please contact the system administrator if you have any questions</li>
                </ul>
            </div>
        @else
            <div class="info-box" style="background-color: #d1fae5; border-left-color: #10b981;">
                <p><strong>Account Reactivated:</strong></p>
                <ul>
                    <li>You can now log in to the system</li>
                    <li>All your previous access permissions have been restored</li>
                </ul>
            </div>
        @endif

        <p>If you have any questions or concerns, please contact our support team.</p>
    </div>

    <div class="footer">
        <p>This is an automated notification from Lead Generator System.</p>
        <p>&copy; {{ date('Y') }} Lead Generator. All rights reserved.</p>
    </div>
</body>
</html>

