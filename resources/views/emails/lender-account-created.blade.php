<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Created - Lead Generator</title>
    <style>
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
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }
        .content {
            padding: 40px 30px;
        }
        .welcome-box {
            background: #F0FDF4;
            border: 2px solid #BBF7D0;
            border-radius: 16px;
            padding: 20px;
            margin: 20px 0;
        }
        .credentials-box {
            background: #FEF2F2;
            border: 2px solid #FECACA;
            border-radius: 16px;
            padding: 20px;
            margin: 20px 0;
        }
        .btn {
            display: inline-block;
            background: #C40F12;
            color: white;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 600;
            margin: 20px 0;
        }
        .footer {
            background: #F9FAFB;
            padding: 30px;
            text-align: center;
            color: #6B7280;
            font-size: 14px;
        }
        .credentials {
            font-family: 'Monaco', 'Consolas', monospace;
            background: #1F2937;
            color: #F9FAFB;
            padding: 15px;
            border-radius: 8px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎉 Welcome to Lead Generator!</h1>
            <p>Your lender account has been approved and created</p>
        </div>
        
        <div class="content">
            <h2>Hello {{ $user->name }}!</h2>
            
            <div class="welcome-box">
                <h3>🎊 Congratulations!</h3>
                <p>Your lender application has been <strong>approved</strong> and your account is now ready to use. You can now access our platform and start managing your lending operations.</p>
            </div>

            <h3>Your Login Credentials:</h3>
            <div class="credentials-box">
                <p><strong>⚠️ Important:</strong> Please save these credentials securely and change your password after your first login.</p>
                
                <div class="credentials">
                    <strong>Email:</strong> {{ $user->email }}<br>
                    <strong>Password:</strong> {{ $password }}
                </div>
            </div>

            <div style="text-align: center;">
                <a href="{{ url('/login') }}" class="btn">Login to Your Account</a>
            </div>

            <h3>What's Next?</h3>
            <ul>
                <li>Login to your account using the credentials above</li>
                <li>Complete your profile setup</li>
                <li>Configure your lending preferences</li>
                <li>Start receiving qualified leads</li>
                <li>Change your password for security</li>
            </ul>

            <div style="background: #EFF6FF; border: 2px solid #DBEAFE; border-radius: 16px; padding: 20px; margin: 20px 0;">
                <h4>🔐 Security Tips:</h4>
                <ul>
                    <li>Change your password immediately after logging in</li>
                    <li>Use a strong, unique password</li>
                    <li>Enable two-factor authentication if available</li>
                    <li>Never share your login credentials</li>
                </ul>
            </div>
        </div>

        <div class="footer">
            <p>Need help? Contact our support team at <a href="mailto:support@leadgenerator.com">support@leadgenerator.com</a></p>
            <p>&copy; {{ date('Y') }} Lead Generator. All rights reserved.</p>
        </div>
    </div>
</body>
</html>