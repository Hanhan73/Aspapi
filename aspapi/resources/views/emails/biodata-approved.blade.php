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
        .badge { display: inline-block; background: #F0FFF4; color: #276749; padding: 0.5rem 1.25rem; border-radius: 20px; font-weight: 700; font-size: 0.85rem; margin: 1rem 0; }
        .body { padding: 2rem; }
        .body p { font-size: 0.9rem; color: #4A6580; line-height: 1.8; margin-bottom: 1rem; }
        .steps { background: #EEF4FB; border-radius: 6px; padding: 1.25rem; margin: 1rem 0; }
        .step { display: flex; align-items: flex-start; gap: 0.75rem; margin-bottom: 0.75rem; }
        .step-num { width: 24px; height: 24px; border-radius: 50%; background: #2A7FC1; color: #fff; font-size: 0.75rem; font-weight: 700; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .step-text { font-size: 0.85rem; color: #4A6580; line-height: 1.5; }
        .btn { display: inline-block; padding: 0.875rem 2rem; background: #2A7FC1; color: #fff; text-decoration: none; border-radius: 4px; font-weight: 700; font-size: 0.85rem; letter-spacing: 0.06em; text-transform: uppercase; }
        .footer { background: #F8FAFC; padding: 1.25rem 2rem; text-align: center; font-size: 0.75rem; color: #B0CCDF; border-top: 1px solid #EEF4FB; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('images/logo-aspapi.png') }}" alt="ASPAPI"/>
            <h1>Biodata Anda Terverifikasi!</h1>
        </div>
        <div class="body">
            <div style="text-align:center;">
                <span class="badge">✓ Biodata Disetujui</span>
            </div>
            <p>Halo, <strong>{{ $member->full_name }}</strong>!</p>
            <p>Selamat! Biodata Anda telah diverifikasi oleh Admin ASPAPI. Sekarang Anda dapat melanjutkan langkah berikutnya:</p>
            <div class="steps">
                <div class="step">
                    <div class="step-num">1</div>
                    <div class="step-text"><strong>Upload Bukti Transfer</strong> — Transfer iuran ke BNI 1661531545 (Sitti Hardiyanti Arhas), lalu upload bukti di portal anggota.</div>
                </div>
                <div class="step">
                    <div class="step-num">2</div>
                    <div class="step-text"><strong>Tunggu Verifikasi Bendahara</strong> — Pembayaran akan diverifikasi oleh Bendahara ASPAPI.</div>
                </div>
                <div class="step">
                    <div class="step-num">3</div>
                    <div class="step-text"><strong>Generate & Download KTA</strong> — Setelah pembayaran terverifikasi, Anda dapat membuat Kartu Tanda Anggota.</div>
                </div>
            </div>
            <div style="text-align:center;margin-top:1.5rem;">
                <a href="{{ url('/member') }}" class="btn">Buka Portal Anggota</a>
            </div>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} ASPAPI — Asosiasi Sarjana dan Praktisi Administrasi Perkantoran Indonesia
        </div>
    </div>
</body>
</html>