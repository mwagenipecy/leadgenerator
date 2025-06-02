<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Status Update - Lead Generator</title>
    <style>
        /* Same styles as above */
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            line-height: 1.6;
            color: #374151;
            background-color: #F8F9FA;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #C40F12, #A00E11);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }
        .content { padding: 40px 30px; }
        .status-approved { background: #F0FDF4; border: 2px solid #BBF7D0; border-radius: 16px; padding: 20px; margin: 20px 0; }
        .status-rejected { background: #FEF2F2; border: 2px solid #FECACA; border-radius: 16px; padding: 20px; margin: 20px 0; }
        .status-suspended { background: #FFFBEB; border: 2px solid #FDE68A; border-radius: 16px; padding: 20px; margin: 20px 0; }
        .btn { display: inline-block; background: #C40F12; color: white; text-decoration: none; padding: 12px 24px; border-radius: 12px; font-weight: 600; margin: 20px 0; }
        .footer { background: #F9FAFB; padding: 30px; text-align: center; color: #6B7280; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Application Status Update</h1>
            <p>{{ $lender->company_name }}</p>
        </div>
        
        <div class="content">
            <h2>Hello {{ $lender->contact_person }}!</h2>
            
            @if($status === 'approved')
                <div class="status-approved">
                    <h3>🎉 Application Approved!</h3>
                    <p>Congratulations! Your lender application has been <strong>approved</strong>. You should receive another email shortly with your login credentials.</p>
                </div>
            @elseif($status === 'rejected')
                <div class="status-rejected">
                    <h3>❌ Application Not Approved</h3>
                    <p>Unfortunately, your lender application has not been approved at this time.</p>
                    @if($lender->rejection_reason)
                        <p><strong>Reason:</strong> {{ $lender->rejection_reason }}</p>
                    @endif
                    <p>You may reapply in the future after addressing the concerns mentioned above.</p>
                </div>
            @elseif($status === 'suspended')
                <div class="status-suspended">
                    <h3>⚠️ Account Suspended</h3>
                    <p>Your lender account has been temporarily suspended. Please contact our support team for more information.</p>
                </div>
            @endif

            @if($status === 'approved')
                <div style="text-align: center;">
                    <a href="{{ url('/login') }}" class="btn">Access Your Account</a>
                </div>
            @endif
        </div>

        <div class="footer">
            <p>Questions? Contact us at <a href="mailto:support@leadgenerator.com">support@leadgenerator.com</a></p>
            <p>&copy; {{ date('Y') }} Lead Generator. All rights reserved.</p>
        </div>
    </div>
</body>
</html>