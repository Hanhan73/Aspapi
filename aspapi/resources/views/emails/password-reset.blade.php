<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>Password Anda Telah Direset</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f7fb; margin: 0; padding: 0; }
        .wrap { max-width: 560px; margin: 40px auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,0.08); }
        .header { background: #1A3A5C; padding: 28px 32px; }
        .header h1 { color: #fff; font-size: 20px; margin: 0; letter-spacing: 0.04em; }
        .header p  { color: rgba(255,255,255,0.6); font-size: 12px; margin: 4px 0 0; }
        .body { padding: 28px 32px; }
        .body p { color: #4A6580; font-size: 14px; line-height: 1.7; margin: 0 0 14px; }
        .password-box {
            background: #EEF4FB;
            border: 1.5px dashed #2A7FC1;
            border-radius: 6px;
            padding: 16px 20px;
            margin: 20px 0;
            text-align: center;
        }
        .password-box p { margin: 0 0 6px; font-size: 12px; color: #4A6580; }
        .password-box .pw {
            font-family: 'Courier New', monospace;
            font-size: 22px;
            font-weight: 700;
            color: #1A3A5C;
            letter-spacing: 0.15em;
        }
        .note { background: #FEF8EC; border-left: 4px solid #E8B84B; padding: 12px 16px; border-radius: 0 4px 4px 0; margin: 20px 0; }
        .note p { color: #8B6914; font-size: 13px; margin: 0; }
        .footer { background: #F4F7FB; padding: 16px 32px; text-align: center; }
        .footer p { color: #B0CCDF; font-size: 12px; margin: 0; }
    </style>
</head>
<body>
<div class="wrap">
    <div class="header">
        <h1>ASPAPI</h1>
        <p>Asosiasi Sarjana dan Praktisi Administrasi Perkantoran Indonesia</p>
    </div>
    <div class="body">
        <p>Yth. <strong>{{ $member->full_name }}</strong>,</p>
        <p>
            Password akun Anda di sistem ASPAPI telah <strong>direset oleh administrator</strong>.
            Berikut adalah password baru Anda:
        </p>

        <div class="password-box">
            <p>Password Baru</p>
            <div class="pw">{{ $newPassword }}</div>
        </div>

        <div class="note">
            <p>⚠ Segera login dan ganti password Anda melalui halaman <strong>Profil → Ubah Password</strong> setelah menerima email ini.</p>
        </div>

        <p>
            Login ke akun Anda di:
            <a href="{{ url('/login') }}" style="color:#2A7FC1;font-weight:700;">{{ url('/login') }}</a>
        </p>

        <p>Jika Anda merasa tidak meminta reset password ini, silakan hubungi administrator ASPAPI segera.</p>

        <p>Salam hormat,<br/><strong>Tim ASPAPI</strong></p>
    </div>
    <div class="footer">
        <p>© {{ date('Y') }} ASPAPI — Email ini dikirim otomatis, harap tidak membalas.</p>
    </div>
</div>
</body>
</html>