<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('creditinfo_alert.email_unsubscribe_subject', ['name' => $serviceName]) }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; line-height: 1.6; color: #374151; background: #f5f5f5; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
        .header { background: linear-gradient(135deg, #C40F11 0%, #A00E11 100%); color: #fff; padding: 24px 30px; text-align: center; }
        .header h1 { font-size: 18px; font-weight: 700; }
        .content { padding: 28px 30px; }
        .content h2 { font-size: 16px; color: #1a1a1a; margin-bottom: 12px; }
        .content p { margin-bottom: 12px; color: #4B5563; }
        .info-box { background: #F9FAFB; border: 1px solid #E5E7EB; border-radius: 10px; padding: 16px; margin: 16px 0; }
        .info-box p { margin: 4px 0; }
        .footer { background: #F9FAFB; padding: 20px 30px; text-align: center; color: #6B7280; font-size: 12px; border-top: 1px solid #E5E7EB; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ __('creditinfo_alert.email_unsubscribe_heading') }}</h1>
        </div>
        <div class="content">
            <h2>{{ __('creditinfo_alert.email_unsubscribe_greeting', ['name' => $user->name ?? $user->email]) }},</h2>
            <p>{{ __('creditinfo_alert.email_unsubscribe_body', ['name' => $serviceName]) }}</p>
            <div class="info-box">
                <p><strong>{{ __('creditinfo_alert.service') }}:</strong> {{ $serviceName }}</p>
                <p><strong>{{ __('creditinfo_alert.email_unsubscribe_time') }}:</strong> {{ $unsubscribedAt }}</p>
            </div>
            <p>{{ __('creditinfo_alert.email_unsubscribe_footer') }}</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Fanikisha Marketplace. {{ __('creditinfo_alert.email_unsubscribe_powered') }}</p>
        </div>
    </div>
</body>
</html>
