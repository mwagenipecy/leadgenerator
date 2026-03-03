<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verification Code – Fanikisha Marketplace</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Helvetica', 'Arial', sans-serif;
            line-height: 1.6;
            color: #333333;
            background-color: #f5f5f5;
            padding: 0;
            margin: 0;
        }
        
        .email-wrapper {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
        }
        
        /* Header – Fanikisha brand */
        .header {
            background: linear-gradient(135deg, #C40F11 0%, #A00E11 100%);
            padding: 40px 20px;
            text-align: center;
        }
        
        .logo-container {
            display: inline-block;
            background-color: #ffffff;
            padding: 20px 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .logo {
            max-width: 200px;
            height: auto;
            display: block;
        }
        
        /* Main content */
        .content {
            padding: 50px 40px;
            background-color: #ffffff;
        }
        
        .title {
            font-size: 24px;
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 30px;
            text-align: left;
        }
        
        .message {
            font-size: 15px;
            color: #555555;
            margin-bottom: 20px;
            line-height: 1.8;
            text-align: left;
        }
        
        /* Verification code section */
        .code-section {
            text-align: center;
            margin: 40px 0;
            padding: 30px 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            border: 1px solid #e0e0e0;
        }
        
        .code-label {
            font-size: 14px;
            font-weight: 600;
            color: #666666;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .otp-code {
            font-size: 48px;
            font-weight: 700;
            color: #1a1a1a;
            letter-spacing: 8px;
            font-family: 'Courier New', monospace;
            margin: 15px 0;
            user-select: all;
        }
        
        .code-validity {
            font-size: 13px;
            color: #888888;
            margin-top: 15px;
            font-style: italic;
        }
        
        /* Security notice */
        .security-notice {
            background-color: #fff9e6;
            border-left: 4px solid #ffc107;
            padding: 20px;
            margin: 30px 0;
            border-radius: 4px;
        }
        
        .security-notice p {
            font-size: 14px;
            color: #555555;
            margin: 0;
            line-height: 1.6;
        }
        
        /* Footer */
        .footer {
            background-color: #f9f9f9;
            padding: 30px 40px;
            border-top: 1px solid #e0e0e0;
            text-align: left;
        }
        
        .footer p {
            font-size: 12px;
            color: #888888;
            margin: 8px 0;
            line-height: 1.6;
        }
        
        .footer-company {
            font-weight: 600;
            color: #666666;
        }
        
        /* Responsive */
        @media only screen and (max-width: 600px) {
            .email-wrapper {
                width: 100% !important;
            }
            
            .header {
                padding: 30px 20px;
            }
            
            .logo-container {
                padding: 15px 20px;
            }
            
            .logo {
                max-width: 150px;
            }
            
            .content {
                padding: 30px 20px;
            }
            
            .title {
                font-size: 20px;
            }
            
            .otp-code {
                font-size: 36px;
                letter-spacing: 4px;
            }
            
            .footer {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <!-- Header with Logo -->
        <div class="header">
            <div class="logo-container">
                <img src="{{ asset('landing/redlogo.png') }}" alt="Fanikisha Marketplace" class="logo">
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="content">
            <h1 class="title">Your verification code</h1>
            
            <p class="message">
                You requested a verification code to sign in or complete registration on Fanikisha Marketplace. Enter the code below when prompted on our platform.
            </p>
            
            <!-- Verification Code -->
            <div class="code-section">
                <div class="code-label">Verification code</div>
                <div class="otp-code">{{ $otp }}</div>
                <div class="code-validity">This code expires in 10 minutes. Do not share it with anyone.</div>
            </div>
            
            <!-- Security Notice -->
            <div class="security-notice">
                <p>
                    <strong>Security:</strong> Fanikisha Marketplace will never ask you for your password, bank details, or card number by email. If you did not request this code, ignore this email and ensure your password is secure.
                </p>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <p class="footer-company">Fanikisha Marketplace</p>
            <p>This is an automated message from Fanikisha Marketplace, the loan marketplace connecting borrowers and lenders in Tanzania. &copy; {{ date('Y') }} Fanikisha Marketplace. All rights reserved. Powered by CreditInfo Tanzania.</p>
            <p style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #e0e0e0;">
                If you did not request this code, please disregard this email.
            </p>
        </div>
    </div>
</body>
</html>
