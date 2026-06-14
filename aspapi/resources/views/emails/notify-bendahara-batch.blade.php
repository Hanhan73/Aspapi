<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <style>
        body { font-family: Arial, sans-serif; background: #F8FAFC; margin: 0; padding: 0; }
        .container { max-width: 560px; margin: 40px auto; background: #fff; border-radius: 8px; overflow: hidden; border: 1px solid #D6E8F7; }
        .header { background: linear-gradient(135deg, #1A2A3A, #276749); padding: 1.75rem 2rem; }
        .header h1 { color: #fff; font-size: 1.1rem; margin: 0; font-weight: 700; }
        .header p { color: #9AE6B4; font-size: 0.8rem; margin: 0.25rem 0 0; }
        .badge { display: inline-block; background: #F0FFF4; color: #276749; padding: 0.35rem 0.875rem; border-radius: 20px; font-weight: 700; font-size: 0.75rem; margin-bottom: 1.25rem; border: 1px solid #C6F6D5; }
        .body { padding: 2rem; }
        .body p { font-size: 0.875rem; color: #4A6580; line-height: 1.8; margin: 0 0 1rem; }
        .info-box { background: #F8FAFC; border: 1px solid #D6E8F7; border-radius: 6px; padding: 1.25rem; margin: 1rem 0; }
        .info-row { display: flex; justify-content: space-between; padding: 0.4rem 0; border-bottom: 1px solid #EEF4FB; font-size: 0.825rem; }
        .info-row:last-child { border-bottom: none; }
        .info-label { color: #8A97A4; }
        .info-value { color: #1A2A3A; font-weight: 600; text-align: right; }
        .alert { background: #F0FFF4; border-left: 3px solid #276749; border-radius: 4px; padding: 0.875rem 1rem; margin: 1rem 0; font-size: 0.825rem; color: #276749; line-height: 1.6; }
        .btn { display: inline-block; padding: 0.75rem 1.75rem; background: #276749; color: #fff !important; text-decoration: none; border-radius: 4px; font-weight: 700; font-size: 0.8rem; letter-spacing: 0.06em; text-transform: uppercase; }
        .footer { background: #F8FAFC; padding: 1.25rem 2rem; text-align: center; font-size: 0.72rem; color: #B0CCDF; border-top: 1px solid #EEF4FB; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔔 Pembayaran Kolektif Masuk</h1>
            <p>Notifikasi sistem — ASPAPI</p>
        </div>
        <div class="body">
            <span class="badge">Batch Kolektif</span>
            <p>ASPAPI Daerah mengirimkan pembayaran iuran kolektif dan membutuhkan verifikasi.</p>

            <div class="info-box">
                <div class="info-row">
                    <span class="info-label">ASPAPI Daerah</span>
                    <span class="info-value">{{ $regionName }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Jumlah Anggota</span>
                    <span class="info-value">{{ $memberCount }} anggota</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Total Pembayaran</span>
                    <span class="info-value" style="color:#276749;">{{ $totalLabel }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Tahun Iuran</span>
                    <span class="info-value">{{ $paymentYear }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Waktu Submit</span>
                    <span class="info-value">{{ $submittedAt }}</span>
                </div>
            </div>

            <div class="alert">
                <strong>📋 Perlu Tindakan:</strong> Buka portal bendahara, periksa bukti transfer kolektif, lalu verifikasi atau tolak batch ini.
            </div>

            <div style="text-align:center;margin-top:1.5rem;">
                <a href="{{ $bendaharaUrl }}" class="btn">Verifikasi Batch</a>
            </div>
        </div>
        <div class="footer">
            Email ini dikirim otomatis oleh sistem ASPAPI. Jangan balas email ini.
        </div>
    </div>
</body>
</html>
