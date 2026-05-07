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
        .btn { display: inline-block; padding: 0.875rem 2rem; background: #2A7FC1; color: #fff; text-decoration: none; border-radius: 4px; font-weight: 700; font-size: 0.85rem; letter-spacing: 0.06em; text-transform: uppercase; margin: 1rem 0; }
        .footer { background: #F8FAFC; padding: 1.25rem 2rem; text-align: center; font-size: 0.75rem; color: #B0CCDF; border-top: 1px solid #EEF4FB; }
        .accent { color: #2A7FC1; font-weight: 700; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('images/logo-aspapi.png') }}" alt="ASPAPI"/>
            <h1>Verifikasi Email Anda</h1>
        </div>
        <div class="body">
            <p>Halo, <strong>{{ $name }}</strong>!</p>
            <p>Terima kasih telah mendaftar sebagai anggota <span class="accent">ASPAPI</span>. Untuk melanjutkan proses pendaftaran, silakan verifikasi alamat email Anda dengan mengklik tombol di bawah ini:</p>
            <div style="text-align:center;">
                <a href="{{ url('/verifikasi-email/' . $token) }}" class="btn">Verifikasi Email Saya</a>
            </div>
            <p>Atau salin link berikut ke browser Anda:</p>
            <p style="word-break:break-all;font-size:0.8rem;color:#B0CCDF;">{{ url('/verifikasi-email/' . $token) }}</p>
            <p>Link ini akan kadaluarsa dalam 24 jam. Jika Anda tidak mendaftar di ASPAPI, abaikan email ini.</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} ASPAPI — Asosiasi Sarjana dan Praktisi Administrasi Perkantoran Indonesia
        </div>
    </div>
</body>
</html>