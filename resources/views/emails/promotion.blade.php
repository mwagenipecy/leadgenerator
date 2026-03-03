<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $promotion->title }}</title>
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
        
        /* Header with red background */
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
        
        .greeting {
            font-size: 18px;
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 20px;
            text-align: left;
        }
        
        .title {
            font-size: 24px;
            font-weight: 700;
            color: #C40F11;
            margin-bottom: 20px;
            text-align: left;
        }
        
        .message {
            font-size: 15px;
            color: #555555;
            margin-bottom: 30px;
            line-height: 1.8;
            text-align: left;
        }
        
        /* Button */
        .button-container {
            text-align: center;
            margin: 30px 0;
        }
        
        .button {
            display: inline-block;
            padding: 14px 32px;
            background-color: #C40F11;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }
        
        .button:hover {
            background-color: #A00E11;
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
            color: #C40F11;
        }
        
        .footer-powered {
            font-size: 11px;
            color: #999999;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #e0e0e0;
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
            <p class="greeting">Hello {{ $user->first_name ?? $user->name ?? 'there' }}!</p>
            
            <h1 class="title">{{ $promotion->title }}</h1>
            
            <div class="message">
                {!! nl2br(e($promotion->message)) !!}
            </div>
            
            <div class="button-container">
                <a href="{{ $actionUrl }}" class="button">View Details</a>
            </div>
            
            <p class="message" style="margin-top: 30px; font-size: 14px; color: #888888;">
                Thank you for being part of Fanikisha Marketplace. We are here to help you connect, grow, and succeed.
            </p>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <p class="footer-company">Fanikisha Marketplace</p>
            <p>This message was sent by Fanikisha Marketplace, the loan marketplace connecting borrowers and lenders in Tanzania. &copy; {{ date('Y') }} Fanikisha Marketplace. All rights reserved.</p>
            <p class="footer-powered">Powered by CreditInfo Tanzania</p>
        </div>
    </div>
</body>
</html>

