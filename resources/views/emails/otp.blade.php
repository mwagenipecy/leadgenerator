<!DOCTYPE html>
<html lang="{{ $locale === 'sw' ? 'sw' : 'en' }}">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>{{ $locale === 'sw' ? 'Nambari Yako ya Uthibitishaji' : 'Your Verification Code' }}</title>
</head>
<body style="margin:0;padding:0;background:#0f0d08;font-family:Georgia,'Times New Roman',serif;color:#ffffff;">
  @php
      $otpDigits = str_split(str_pad((string) $otp, 6, '0', STR_PAD_LEFT));
  @endphp
  <table width="100%" cellpadding="0" cellspacing="0" style="padding:40px 0;">
    <tr>
      <td align="center">
        <table width="520" cellpadding="0" cellspacing="0">
          <tr>
            <td align="center" style="padding-bottom:32px;">
              <img src="{{ rtrim(config('app.url'), '/') }}/landing/redlogo.png" alt="Fanikisha Marketplace" style="max-width:180px;height:auto;display:block;">
            </td>
          </tr>
          <tr>
            <td style="padding:0 20px;">
              <div style="background:#1a1610;border-radius:16px;padding:40px 32px;border:1px solid #2a261e;">
                <p style="font-size:15px;color:#ffffff;margin:0 0 4px;">
                  {{ $locale === 'sw' ? 'Habari,' : 'Hello,' }}
                </p>
                <h2 style="font-family:Georgia,serif;font-size:22px;color:#ffffff;margin:0 0 8px;">
                  {{ $locale === 'sw' ? 'Nambari yako ya uthibitishaji' : 'Your verification code' }}
                </h2>
                <p style="font-size:14px;color:#ffffff;line-height:1.6;margin:0 0 28px;">
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
                    <span style="display:inline-block;width:52px;height:64px;background:#0f0d08;border-radius:12px;border:1.5px solid #2a261e;font-size:28px;font-weight:700;color:#C40F11;line-height:64px;text-align:center;margin:0 4px;">{{ $digit }}</span>
                  @endforeach
                </div>
                <div style="height:1px;background:#2a261e;margin:0 0 20px;"></div>
                <div style="background:rgba(196,15,17,0.10);padding:14px 16px;border-radius:10px;border:1px solid rgba(196,15,17,0.20);">
                  <p style="font-size:12.5px;color:#ffffff;line-height:1.6;margin:0;">
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
            <td align="center" style="padding:28px 20px 0;">
              <p style="font-size:12px;color:#ffffff;margin:0 0 6px;">
                © {{ date('Y') }} Fanikisha Marketplace · {{ $locale === 'sw' ? 'Haki zote zimehifadhiwa' : 'All rights reserved' }}
              </p>
              <p style="margin:0;">
                <a href="{{ url('/terms') }}" style="font-size:11.5px;color:#ffffff;text-decoration:none;margin:0 9px;">
                  {{ $locale === 'sw' ? 'Masharti ya Matumizi' : 'Terms of Service' }}
                </a>
                <a href="{{ url('/customer-help') }}" style="font-size:11.5px;color:#ffffff;text-decoration:none;margin:0 9px;">
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
