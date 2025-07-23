<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Login Code</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.5;
            color: #333;
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background-color: white;
            border-radius: 10px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin-bottom: 20px;
        }
        .logo .red {
            color: #dc2626;
        }
        h1 {
            color: #333;
            font-size: 20px;
            margin: 0 0 20px 0;
        }
        .otp-box {
            background-color: #dc2626;
            color: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .otp-code {
            font-size: 32px;
            font-weight: bold;
            letter-spacing: 4px;
            margin: 10px 0;
            font-family: monospace;
        }
        .message {
            font-size: 16px;
            margin: 20px 0;
            color: #555;
        }
        .warning {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            color: #856404;
        }
        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #888;
        }
        @media only screen and (max-width: 600px) {
            .container {
                padding: 20px;
            }
            .otp-code {
                font-size: 24px;
                letter-spacing: 2px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            Lead<span class="red">Generator</span>
        </div>
        
        <h1>Your Login Code</h1>
        
        <p class="message">
            Hi <strong>{{ $user->name ?? 'User' }}</strong>,<br>
            Use this code to login:
        </p>
        
        <div class="otp-box">
            <div style="font-size: 14px; margin-bottom: 10px;">Your Code</div>
            <div class="otp-code">{{ $otp }}</div>
        </div>
        
        <div class="warning">
            <strong>⚠️ Important:</strong><br>
            • This code expires in 10 minutes<br>
            • Don't share this code with anyone<br>
            • Only use if you just tried to login
        </div>
        
        <p class="message">
            If you didn't try to login, ignore this email.
        </p>
        
        <div class="footer">
            <p>LeadGenerator Team</p>
            <p>&copy; {{ date('Y') }} LeadGenerator</p>
        </div>
    </div>
</body>
</html>