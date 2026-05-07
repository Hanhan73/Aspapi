<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <style>
        body { margin:0; padding:0; background:#F0F4F8; font-family:'DM Sans',Arial,sans-serif; }
        .wrapper { max-width:560px; margin:40px auto; background:#fff; border-radius:8px; overflow:hidden; border:1px solid #D6E8F7; }
        .header { background:linear-gradient(135deg,#1A5F9A,#2A7FC1); padding:2rem; text-align:center; }
        .header img { height:48px; }
        .header h1 { color:#fff; font-size:1.1rem; margin:0.75rem 0 0; font-weight:700; letter-spacing:0.04em; }
        .body { padding:2rem; color:#1A2A3A; font-size:0.9rem; line-height:1.7; }
        .body p { margin:0 0 1rem; }
        .accent { color:#2A7FC1; font-weight:700; }
        .btn {
            display:inline-block; padding:0.75rem 2rem;
            background:#2A7FC1; color:#fff !important; text-decoration:none;
            border-radius:4px; font-size:0.8rem; font-weight:700;
            letter-spacing:0.08em; text-transform:uppercase; margin:1rem 0;
        }
        .url-box { background:#F0F4F8; border-radius:4px; padding:0.75rem 1rem; font-size:0.75rem; color:#5C6B78; word-break:break-all; margin:0.5rem 0 1rem; }
        .footer { background:#F8FAFC; padding:1.25rem 2rem; text-align:center; font-size:0.72rem; color:#8A97A4; border-top:1px solid #EEF4FB; }
    </style>
</head>
<body>
    <div class="wrapper">

        <div class="header">
            <img src="{{ asset('images/logo-aspapi.png') }}" alt="ASPAPI" onerror="this.style.display='none'"/>
            <h1>Verifikasi Alamat Email</h1>
        </div>

        <div class="body">
            <p>Halo, <strong>{{ $name }}</strong>!</p>
            <p>Terima kasih telah mendaftar sebagai anggota <span class="accent">ASPAPI</span>. Klik tombol di bawah untuk memverifikasi alamat email Anda:</p>

            <div style="text-align:center;">
                <a href="{{ $verifyUrl }}" class="btn">Verifikasi Email Saya</a>
            </div>

            <p>Atau salin link berikut ke browser Anda:</p>
            <div class="url-box">{{ $verifyUrl }}</div>

            <p style="font-size:0.8rem;color:#8A97A4;">
                Link ini berlaku selama <strong>24 jam</strong>. Jika Anda tidak mendaftar di ASPAPI, abaikan email ini.
            </p>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} ASPAPI — Asosiasi Sarjana dan Praktisi Administrasi Perkantoran Indonesia
        </div>

    </div>
</body>
</html>