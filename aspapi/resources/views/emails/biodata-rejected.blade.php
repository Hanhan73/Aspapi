<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <style>
        body { font-family: Arial, sans-serif; background: #F8FAFC; margin: 0; padding: 0; }
        .container { max-width: 560px; margin: 40px auto; background: #fff; border-radius: 8px; overflow: hidden; border: 1px solid #D6E8F7; }
        .header { background: linear-gradient(135deg, #8B1A1A, #C0392B); padding: 2rem; text-align: center; }
        .header img { height: 48px; filter: brightness(0) invert(1); }
        .header h1 { color: #fff; font-size: 1.25rem; margin-top: 1rem; }
        .body { padding: 2rem; }
        .body p { font-size: 0.9rem; color: #4A6580; line-height: 1.8; margin-bottom: 1rem; }
        .reason-box { background: #FDECEA; border-left: 4px solid #C0392B; border-radius: 0 4px 4px 0; padding: 1rem 1.25rem; margin: 1rem 0; }
        .reason-label { font-size: 0.7rem; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: #C0392B; margin-bottom: 0.375rem; }
        .reason-text { font-size: 0.875rem; color: #922B21; }
        .btn { display: inline-block; padding: 0.875rem 2rem; background: #2A7FC1; color: #fff; text-decoration: none; border-radius: 4px; font-weight: 700; font-size: 0.85rem; letter-spacing: 0.06em; text-transform: uppercase; }
        .footer { background: #F8FAFC; padding: 1.25rem 2rem; text-align: center; font-size: 0.75rem; color: #B0CCDF; border-top: 1px solid #EEF4FB; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('images/logo-aspapi.png') }}" alt="ASPAPI"/>
            <h1>Biodata Perlu Diperbaiki</h1>
        </div>
        <div class="body">
            <p>Halo, <strong>{{ $member->full_name }}</strong>!</p>
            <p>Mohon maaf, biodata Anda belum dapat diverifikasi karena alasan berikut:</p>
            <div class="reason-box">
                <div class="reason-label">Alasan Penolakan</div>
                <div class="reason-text">{{ $reason }}</div>
            </div>
            <p>Silakan perbaiki biodata Anda dan kirim ulang untuk diverifikasi kembali.</p>
            <div style="text-align:center;margin-top:1.5rem;">
                <a href="{{ url('/member/biodata') }}" class="btn">Perbaiki Biodata</a>
            </div>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} ASPAPI — Asosiasi Sarjana dan Praktisi Administrasi Perkantoran Indonesia
        </div>
    </div>
</body>
</html>