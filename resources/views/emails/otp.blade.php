<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Verify Your OTP - LeadGenerator') }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
            color: #333333;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 6px;
            overflow: hidden;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
        }
        .header {
            background-color: #dc2626;
            padding: 30px 20px;
            text-align: center;
            color: #ffffff;
        }
        .logo {
            font-size: 28px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        .content {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 16px;
            color: #111111;
        }
        .message {
            font-size: 15px;
            line-height: 1.7;
            color: #555555;
            margin-bottom: 24px;
        }
        .otp-container {
            background-color: #fef2f2;
            border: 2px solid #dc2626;
            border-radius: 6px;
            padding: 30px 20px;
            text-align: center;
            margin: 30px 0;
        }
        .otp-label {
            font-size: 14px;
            color: #777777;
            margin-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .otp-code {
            font-size: 36px;
            font-weight: bold;
            color: #dc2626;
            letter-spacing: 8px;
            font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace;
        }
        .otp-validity {
            font-size: 13px;
            color: #666666;
            margin-top: 15px;
        }
        .security-notice {
            background-color: #f9fafb;
            border-left: 4px solid #dc2626;
            padding: 18px 20px;
            margin: 25px 0;
            border-radius: 4px;
            font-size: 14px;
            color: #555555;
            line-height: 1.6;
        }
        .footer {
            background-color: #111111;
            padding: 25px 20px;
            text-align: center;
            color: #ffffff;
        }
        .footer-text {
            font-size: 13px;
            line-height: 1.6;
            margin-bottom: 10px;
        }
        .copyright {
            font-size: 12px;
            color: #bbbbbb;
            margin-top: 8px;
        }
        @media only screen and (max-width: 600px) {
            .content {
                padding: 30px 20px;
            }
            .otp-code {
                font-size: 32px;
                letter-spacing: 6px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="logo">LeadGenerator</div>
        </div>
        <div class="content">
            <div class="greeting">{{ __('Verify Your Identity') }}</div>
            <div class="message">
                {{ __('Hi') }} <strong>{{ $user->name ?? __('there') }}</strong>,<br>
                {{ __("Please use the One-Time Password below to continue with your loan application process.") }}
            </div>
            <div class="otp-container">
                <div class="otp-label">{{ __('Your Verification Code') }}</div>
                <div class="otp-code">{{ $otp }}</div>
                <div class="otp-validity">{{ __('Valid for 10 minutes') }}</div>
            </div>
            <div class="security-notice">
                {{ __('For your security, never share this code with anyone. LeadGenerator support will never ask for your OTP via phone or email.') }}
            </div>
            <div class="message">
                {{ __('If you did not request this code, please contact our support team immediately.') }}
            </div>
        </div>
        <div class="footer">
            <div class="footer-text">
                {{ __('LeadGenerator · Loan Processing & Credit Score Solutions') }}
            </div>
            <div class="footer-text">
                {{ __('Need help? Reach us at support@leadgenerator.com') }}
            </div>
            <div class="copyright">
                &copy; {{ date('Y') }} LeadGenerator. {{ __('All rights reserved.') }}
            </div>
        </div>
    </div>
</body>
</html>