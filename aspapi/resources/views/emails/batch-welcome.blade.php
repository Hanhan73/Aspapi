<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>Selamat Datang di ASPAPI</title>
    <style>
        body       { font-family: Arial, sans-serif; background:#f4f7fb; margin:0; padding:0; }
        .wrap      { max-width:560px; margin:40px auto; background:#fff; border-radius:8px; overflow:hidden; box-shadow:0 2px 12px rgba(0,0,0,0.08); }
        .header    { background:#1A3A5C; padding:28px 32px; }
        .header h1 { color:#fff; font-size:20px; margin:0; letter-spacing:0.04em; }
        .header p  { color:rgba(255,255,255,0.6); font-size:12px; margin:4px 0 0; }
        .body      { padding:28px 32px; }
        .body p    { color:#4A6580; font-size:14px; line-height:1.7; margin:0 0 14px; }
        .cred-box  { background:#EEF4FB; border-radius:6px; padding:16px 20px; margin:20px 0; }
        .cred-box .row { display:flex; justify-content:space-between; align-items:center; padding:6px 0; border-bottom:1px solid #D6E8F7; }
        .cred-box .row:last-child { border-bottom:none; }
        .cred-box .lbl { font-size:11px; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; color:#8A97A4; }
        .cred-box .val { font-size:14px; font-weight:700; color:#1A2A3A; font-family:monospace; }
        .cta       { display:block; text-align:center; padding:14px 24px; border-radius:6px; font-size:14px; font-weight:700; text-decoration:none; background:#2A7FC1; color:#fff; margin:20px 0; }
        .note      { background:#FEF8EC; border-left:4px solid #E8B84B; padding:12px 16px; border-radius:0 4px 4px 0; margin:20px 0; }
        .note p    { color:#8B6914; font-size:13px; margin:0; }
        .footer    { background:#F4F7FB; padding:16px 32px; text-align:center; }
        .footer p  { color:#B0CCDF; font-size:12px; margin:0; }
    </style>
</head>
<body>
<div class="wrap">
    <div class="header">
        <h1>ASPAPI</h1>
        <p>Asosiasi Sarjana dan Praktisi Administrasi Perkantoran Indonesia</p>
    </div>
    <div class="body">
        <p>Yth. <strong>{{ $name }}</strong>,</p>
        <p>
            Selamat datang di ASPAPI! Anda telah didaftarkan sebagai anggota oleh ASPAPI Daerah setempat.
            Berikut adalah informasi akun Anda untuk login ke portal anggota:
        </p>

        <div class="cred-box">
            <div class="row">
                <span class="lbl">Email</span>
                <span class="val">{{ $email }}</span>
            </div>
            <div class="row">
                <span class="lbl">Password</span>
                <span class="val">{{ $password }}</span>
            </div>
        </div>

        <a href="{{ url('/login') }}" class="cta">Login ke Portal Anggota →</a>

        <div class="note">
            <p>⚠ Segera ganti password Anda setelah login pertama melalui menu <strong>Profil → Ubah Password</strong>.</p>
        </div>

        <p>
            Setelah login, lengkapi biodata Anda agar keanggotaan dapat diverifikasi dan KTA dapat diterbitkan.
        </p>

        <p>Salam hormat,<br/><strong>Tim ASPAPI</strong></p>
    </div>
    <div class="footer">
        <p>© {{ date('Y') }} ASPAPI — Email ini dikirim otomatis, harap tidak membalas.</p>
    </div>
</div>
</body>
</html>