<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Application status update – Fanikisha Marketplace</title>
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
        .header h1 { margin: 0 0 6px 0; font-size: 20px; font-weight: 700; }
        .header .company { margin: 0; font-size: 14px; opacity: 0.95; }
        .content { padding: 32px 30px; }
        .content h2 { font-size: 18px; color: #1a1a1a; margin-bottom: 16px; }
        .content p { margin-bottom: 12px; color: #4B5563; }
        .status-approved {
            background: #F0FDF4;
            border: 1px solid #BBF7D0;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
        }
        .status-rejected {
            background: #FEF2F2;
            border: 1px solid #FECACA;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
        }
        .status-suspended {
            background: #FFFBEB;
            border: 1px solid #FDE68A;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
        }
        .status-approved h3, .status-rejected h3, .status-suspended h3 { margin-top: 0; margin-bottom: 10px; font-size: 16px; }
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
        .footer {
            background: #F9FAFB;
            padding: 24px 30px;
            text-align: center;
            color: #6B7280;
            font-size: 13px;
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
            <h1>Application status update</h1>
            <p class="company">{{ $lender->company_name }}</p>
        </div>

        <div class="content">
            <h2>Hello {{ $lender->contact_person }},</h2>

            @if($status === 'approved')
                <div class="status-approved">
                    <h3>Application approved</h3>
                    <p>Your lender application has been approved. You will receive a separate email shortly with your login credentials. You can then sign in to Fanikisha Marketplace and start managing your loan products and leads.</p>
                </div>
                <div style="text-align: center;">
                    <a href="{{ url('/login') }}" class="btn">Sign in to your account</a>
                </div>
            @elseif($status === 'rejected')
                <div class="status-rejected">
                    <h3>Application not approved</h3>
                    <p>Your lender application has not been approved at this time.</p>
                    @if($lender->rejection_reason)
                        <p><strong>Reason:</strong> {{ $lender->rejection_reason }}</p>
                    @endif
                    <p>You may submit a new application in the future after addressing the points above. If you have questions, contact us at <a href="mailto:info@fanikisha.com">info@fanikisha.com</a>.</p>
                </div>
            @elseif($status === 'suspended')
                <div class="status-suspended">
                    <h3>Account suspended</h3>
                    <p>Your lender account has been temporarily suspended. For more information and next steps, please contact our support team at <a href="mailto:info@fanikisha.com">info@fanikisha.com</a>.</p>
                </div>
            @endif
        </div>

        <div class="footer">
            <p>Questions? Contact us at <a href="mailto:info@fanikisha.com">info@fanikisha.com</a></p>
            <p>&copy; {{ date('Y') }} Fanikisha Marketplace. All rights reserved. Powered by CreditInfo Tanzania.</p>
        </div>
    </div>
</body>
</html>
