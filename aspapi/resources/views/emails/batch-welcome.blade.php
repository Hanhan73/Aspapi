<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <style>
        body { font-family: Arial, sans-serif; background: #F8FAFC; margin: 0; padding: 0; }
        .container { max-width: 560px; margin: 40px auto; background: #fff; border-radius: 8px; overflow: hidden; border: 1px solid #D6E8F7; }
        .header { background: linear-gradient(135deg, #1A5F9A, #2A7FC1); padding: 2rem; text-align: center; }
        .header img { height: 48px; }
        .header h1 { color: #fff; font-size: 1.25rem; margin-top: 1rem; }
        .body { padding: 2rem; }
        .body p { font-size: 0.9rem; color: #4A6580; line-height: 1.8; margin-bottom: 1rem; }
        .credential-box { background: #1A2A3A; border-radius: 6px; padding: 1.25rem; margin: 1rem 0; }
        .cred-label { font-size: 0.65rem; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: #B0CCDF; margin-bottom: 0.25rem; }
        .cred-value { font-size: 0.95rem; font-weight: 700; color: #fff; font-family: monospace; }
        .btn { display: inline-block; padding: 0.875rem 2rem; background: #2A7FC1; color: #fff; text-decoration: none; border-radius: 4px; font-weight: 700; font-size: 0.85rem; letter-spacing: 0.06em; text-transform: uppercase; }
        .footer { background: #F8FAFC; padding: 1.25rem 2rem; text-align: center; font-size: 0.75rem; color: #B0CCDF; border-top: 1px solid #EEF4FB; }
        .warning { background: #FEF8EC; border-left: 4px solid #E8B84B; padding: 0.875rem 1rem; border-radius: 0 4px 4px 0; font-size: 0.825rem; color: #8B6914; margin: 1rem 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('images/logo-aspapi.png') }}" alt="ASPAPI"/>
            <h1>Selamat Datang di ASPAPI!</h1>
        </div>
        <div class="body">
            <p>Halo, <strong>{{ $name }}</strong>!</p>
            <p>Anda telah didaftarkan sebagai anggota <strong>ASPAPI</strong> melalui <strong>{{ $region }}</strong>. Berikut adalah kredensial akun Anda:</p>

            <div class="credential-box">
                <div style="margin-bottom:0.875rem;">
                    <div class="cred-label">Email</div>
                    <div class="cred-value">{{ $email }}</div>
                </div>
                <div>
                    <div class="cred-label">Password</div>
                    <div class="cred-value">{{ $password }}</div>
                </div>
            </div>

            <div class="warning">
                Segera ganti password Anda setelah login pertama kali untuk keamanan akun.
            </div>

            <p>Setelah login, lengkapi biodata Anda dan tunggu verifikasi dari Admin ASPAPI.</p>

            <div style="text-align:center;margin-top:1.5rem;">
                <a href="{{ url('/login') }}" class="btn">Login Sekarang</a>
            </div>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} ASPAPI — Asosiasi Sarjana dan Praktisi Administrasi Perkantoran Indonesia
        </div>
    </div>
</body>
</html>