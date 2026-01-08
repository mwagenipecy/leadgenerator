<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Lead Generator</title>
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
        
        /* Header with dark background */
        .header {
            background: linear-gradient(135deg, #2C3E50 0%, #34495E 100%);
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
        
        /* Button */
        .button-container {
            text-align: center;
            margin: 40px 0;
        }
        
        .button {
            display: inline-block;
            background-color: #10b981;
            color: #ffffff;
            text-decoration: none;
            padding: 14px 32px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        
        .button:hover {
            background-color: #059669;
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
        
        .url-fallback {
            background-color: #f9f9f9;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
            word-break: break-all;
            font-size: 13px;
            color: #666666;
            font-family: 'Courier New', monospace;
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
                <img src="{{ asset('logo/logoOnWhitebg.png') }}" alt="Lead Generator" class="logo">
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="content">
            <h1 class="title">Reset Your Password</h1>
            
            <p class="message">
                You are receiving this email because we received a password reset request for your account.
            </p>
            
            <div class="button-container">
                <a href="{{ $url }}" class="button">Reset Password</a>
            </div>
            
            <p class="message">
                This password reset link will expire in {{ $expiration }} minutes.
            </p>
            
            <p class="message">
                If you did not request a password reset, no further action is required.
            </p>
            
            <!-- URL Fallback -->
            <div class="url-fallback">
                <strong>If the button doesn't work, copy and paste this URL into your browser:</strong><br>
                {{ $url }}
            </div>
            
            <!-- Security Notice -->
            <div class="security-notice">
                <p>
                    <strong>Security Notice:</strong> If you did not request this password reset, please ignore this email. Your password will remain unchanged.
                </p>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <p class="footer-company">Lead Generator Team</p>
            <p>This message was produced and distributed by Lead Generator. &copy; {{ date('Y') }} Lead Generator. All rights reserved.</p>
        </div>
    </div>
</body>
</html>

