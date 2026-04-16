<!DOCTYPE html>
<html lang="{{ $locale === 'sw' ? 'sw' : 'en' }}">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>{{ $locale === 'sw' ? 'Nambari Yako ya Uthibitishaji' : 'Your Verification Code' }}</title>
</head>
<body style="margin:0;padding:20px;background:#f5f5f5;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;color:#374151;">
  @php
      $otpDigits = str_split(str_pad((string) $otp, 6, '0', STR_PAD_LEFT));
  @endphp
  <table width="100%" cellpadding="0" cellspacing="0" style="padding:20px 0;">
    <tr>
      <td align="center">
        <table width="600" cellpadding="0" cellspacing="0" style="max-width:600px;background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,0.08);">
          <tr>
            <td align="center" style="background:#ffffff;border-bottom:1px solid #E5E7EB;padding:28px 24px;">
              <img src="{{ rtrim(config('app.url'), '/') }}/landing/redlogo.png" alt="Fanikisha Marketplace" style="max-width:180px;height:auto;display:block;background:#ffffff;padding:10px 16px;border-radius:8px;">
              <p style="font-size:14px;color:#C40F11;margin:14px 0 0;">
                {{ $locale === 'sw' ? 'Nambari ya uthibitishaji' : 'Verification code' }}
              </p>
            </td>
          </tr>
          <tr>
            <td style="padding:30px 28px;">
              <div style="background:#ffffff;border-radius:10px;">
                <p style="font-size:15px;color:#374151;margin:0 0 4px;">
                  {{ $locale === 'sw' ? 'Habari,' : 'Hello,' }}
                </p>
                <h2 style="font-size:22px;color:#111827;margin:0 0 8px;">
                  {{ $locale === 'sw' ? 'Nambari yako ya uthibitishaji' : 'Your verification code' }}
                </h2>
                <p style="font-size:14px;color:#4B5563;line-height:1.6;margin:0 0 28px;">
                  @if($locale === 'sw')
                    Tumia nambari hii ya muda mmoja kukamilisha kuingia. Nambari hii itaisha ndani ya
                    <strong style="color:#C40F11;">dakika {{ $expiryMinutes }}</strong>.
                  @else
                    Use this one-time password to complete your sign-in. This code expires in
                    <strong style="color:#C40F11;">{{ $expiryMinutes }} minutes</strong>.
                  @endif
                </p>
                <div style="text-align:center;margin-bottom:28px;">
                  @foreach($otpDigits as $digit)
                    <span style="display:inline-block;width:52px;height:64px;background:#ffffff;border-radius:12px;border:1.5px solid #E5E7EB;font-size:28px;font-weight:700;color:#C40F11;line-height:64px;text-align:center;margin:0 4px;">{{ $digit }}</span>
                  @endforeach
                </div>
                <div style="height:1px;background:#E5E7EB;margin:0 0 20px;"></div>
                <div style="background:#FEF2F2;padding:14px 16px;border-radius:10px;border:1px solid #FECACA;">
                  <p style="font-size:12.5px;color:#374151;line-height:1.6;margin:0;">
                    @if($locale === 'sw')
                      ⚠ Kama hukuomba nambari hii, tafadhali puuza barua pepe hii au wasiliana na msaada. Usimpe mtu yeyote nambari hii.
                    @else
                      ⚠ If you did not request this code, please ignore this email or contact support. Never share this code.
                    @endif
                  </p>
                </div>
              </div>
            </td>
          </tr>
          <tr>
            <td align="center" style="padding:22px 20px;background:#F9FAFB;border-top:1px solid #E5E7EB;">
              <p style="font-size:12px;color:#6B7280;margin:0 0 6px;">
                © {{ date('Y') }} Fanikisha Marketplace · {{ $locale === 'sw' ? 'Haki zote zimehifadhiwa' : 'All rights reserved' }}
              </p>
              <p style="margin:0;">
                <a href="{{ url('/terms') }}" style="font-size:11.5px;color:#C40F11;text-decoration:none;margin:0 9px;">
                  {{ $locale === 'sw' ? 'Masharti ya Matumizi' : 'Terms of Service' }}
                </a>
                <a href="{{ url('/customer-help') }}" style="font-size:11.5px;color:#C40F11;text-decoration:none;margin:0 9px;">
                  {{ $locale === 'sw' ? 'Kituo cha Msaada' : 'Help Center' }}
                </a>
              </p>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>
