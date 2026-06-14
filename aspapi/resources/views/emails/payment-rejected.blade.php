<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <style>
        body { font-family: Arial, sans-serif; background: #F8FAFC; margin: 0; padding: 0; }
        .container { max-width: 560px; margin: 40px auto; background: #fff; border-radius: 8px; overflow: hidden; border: 1px solid #D6E8F7; }
        .header { background: linear-gradient(135deg, #1A2A3A, #C0392B); padding: 2rem; text-align: center; }
        .header h1 { color: #fff; font-size: 1.25rem; margin: 0; font-weight: 700; }
        .badge { display: inline-block; background: #FDECEA; color: #C0392B; padding: 0.5rem 1.25rem; border-radius: 20px; font-weight: 700; font-size: 0.85rem; margin: 1rem 0; }
        .body { padding: 2rem; }
        .body p { font-size: 0.9rem; color: #4A6580; line-height: 1.8; margin-bottom: 1rem; }
        .info-box { background: #F8FAFC; border: 1px solid #D6E8F7; border-radius: 6px; padding: 1.25rem; margin: 1rem 0; }
        .info-row { display: flex; justify-content: space-between; padding: 0.4rem 0; border-bottom: 1px solid #EEF4FB; font-size: 0.825rem; }
        .info-row:last-child { border-bottom: none; }
        .info-label { color: #8A97A4; }
        .info-value { color: #1A2A3A; font-weight: 600; text-align: right; }
        .reason-box { background: #FDECEA; border-left: 3px solid #C0392B; border-radius: 4px; padding: 0.875rem 1rem; margin: 1rem 0; font-size: 0.875rem; color: #922B21; line-height: 1.6; }
        .btn { display: inline-block; padding: 0.875rem 2rem; background: #2A7FC1; color: #fff !important; text-decoration: none; border-radius: 4px; font-weight: 700; font-size: 0.85rem; letter-spacing: 0.06em; text-transform: uppercase; }
        .footer { background: #F8FAFC; padding: 1.25rem 2rem; text-align: center; font-size: 0.75rem; color: #B0CCDF; border-top: 1px solid #EEF4FB; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>✗ Pembayaran Ditolak</h1>
        </div>
        <div class="body">
            <div style="text-align:center;">
                <span class="badge">✗ Pembayaran Ditolak</span>
            </div>
            <p>Halo, <strong>{{ $member->full_name }}</strong>!</p>
            <p>Mohon maaf, pembayaran Anda tidak dapat diverifikasi. Berikut rincian dan alasannya:</p>

            <div class="info-box">
                <div class="info-row">
                    <span class="info-label">Jenis Pembayaran</span>
                    <span class="info-value">{{ $typeLabel }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Jumlah</span>
                    <span class="info-value">{{ $amountLabel }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Status</span>
                    <span class="info-value" style="color:#C0392B;">✗ Ditolak</span>
                </div>
            </div>

            <div class="reason-box">
                <strong>Alasan Penolakan:</strong><br>
                {{ $reason }}
            </div>

            <p>Silakan unggah ulang bukti pembayaran yang sesuai melalui portal anggota.</p>

            <div style="text-align:center;margin-top:1.5rem;">
                <a href="{{ route('member.payment') }}" class="btn">Upload Ulang Bukti</a>
            </div>
        </div>
        <div class="footer">
            Email ini dikirim otomatis oleh sistem ASPAPI. Jangan balas email ini.
        </div>
    </div>
</body>
</html>
