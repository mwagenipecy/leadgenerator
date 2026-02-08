<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lender Account Status Change</title>
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
        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #10b981;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Lender Account Status Change</h1>
    </div>

    <div class="content">
        @if($isAdminNotification)
            <h2>System Administrator Notification</h2>
            <p>The lender account status has been changed in the system.</p>
        @else
            <p>Dear {{ $user->name ?? 'Valued Lender' }},</p>
            <p>This is to inform you that your lender account status has been updated.</p>
        @endif

        <div class="status-box">
            <h3 style="margin-top: 0; color: {{ $isDisabled ? '#991b1b' : '#065f46' }};">
                Status: {{ $isDisabled ? 'DISABLED' : 'ENABLED' }}
            </h3>
            <p style="margin-bottom: 0;">
                @if($isDisabled)
                    Your lender account has been disabled. All associated loan products and user accounts have also been disabled.
                @else
                    Your lender account has been enabled. All associated loan products and user accounts have been reactivated.
                @endif
            </p>
        </div>

        <div class="info-box">
            <h3 style="margin-top: 0;">Lender Information:</h3>
            <p><strong>Company Name:</strong> {{ $lender->company_name }}</p>
            <p><strong>Contact Person:</strong> {{ $lender->contact_person }}</p>
            <p><strong>Email:</strong> {{ $lender->email }}</p>
            <p><strong>Phone:</strong> {{ $lender->phone }}</p>
            <p><strong>Status Changed:</strong> {{ now()->format('F d, Y \a\t g:i A') }}</p>
        </div>

        @if($isDisabled)
            <div class="info-box" style="background-color: #fef3c7; border-left-color: #f59e0b;">
                <p><strong>Important Notice:</strong></p>
                <ul>
                    <li>All loan products associated with this lender have been disabled</li>
                    <li>All user accounts associated with this lender have been deactivated</li>
                    <li>Users will not be able to access the system until the account is re-enabled</li>
                </ul>
            </div>
        @else
            <div class="info-box" style="background-color: #d1fae5; border-left-color: #10b981;">
                <p><strong>Account Reactivated:</strong></p>
                <ul>
                    <li>All loan products associated with this lender have been enabled</li>
                    <li>All user accounts associated with this lender have been reactivated</li>
                    <li>Users can now access the system normally</li>
                </ul>
            </div>
        @endif

        @if(!$isAdminNotification)
            <p>If you have any questions or concerns, please contact our support team.</p>
        @endif
    </div>

    <div class="footer">
        <p>This is an automated notification from Lead Generator System.</p>
        <p>&copy; {{ date('Y') }} Lead Generator. All rights reserved.</p>
    </div>
</body>
</html>

