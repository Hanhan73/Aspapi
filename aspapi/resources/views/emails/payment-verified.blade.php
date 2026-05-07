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
        .receipt { background: #EEF4FB; border-radius: 6px; padding: 1.25rem; margin: 1rem 0; }
        .receipt-row { display: flex; justify-content: space-between; font-size: 0.85rem; margin-bottom: 0.5rem; }
        .receipt-label { color: #4A6580; }
        .receipt-value { color: #1A2A3A; font-weight: 700; }
        .btn { display: inline-block; padding: 0.875rem 2rem; background: #2A7FC1; color: #fff; text-decoration: none; border-radius: 4px; font-weight: 700; font-size: 0.85rem; letter-spacing: 0.06em; text-transform: uppercase; }
        .footer { background: #F8FAFC; padding: 1.25rem 2rem; text-align: center; font-size: 0.75rem; color: #B0CCDF; border-top: 1px solid #EEF4FB; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('images/logo-aspapi.png') }}" alt="ASPAPI"/>
            <h1>Pembayaran Terverifikasi!</h1>
        </div>
        <div class="body">
            <p>Halo, <strong>{{ $payment->member->full_name }}</strong>!</p>
            <p>Pembayaran Anda telah berhasil diverifikasi oleh Bendahara ASPAPI. Berikut detail pembayaran Anda:</p>
            <div class="receipt">
                <div class="receipt-row">
                    <span class="receipt-label">Jenis Pembayaran</span>
                    <span class="receipt-value">{{ $payment->type_label }}</span>
                </div>
                <div class="receipt-row">
                    <span class="receipt-label">Jumlah</span>
                    <span class="receipt-value">{{ $payment->amount_formatted }}</span>
                </div>
                <div class="receipt-row">
                    <span class="receipt-label">Tanggal Verifikasi</span>
                    <span class="receipt-value">{{ now()->format('d M Y') }}</span>
                </div>
                <div class="receipt-row">
                    <span class="receipt-label">Status</span>
                    <span class="receipt-value" style="color:#276749;">✓ Terverifikasi</span>
                </div>
            </div>
            @if ($payment->member->canGenerateCard())
            <p style="background:#F0FFF4;border-left:4px solid #276749;padding:1rem;border-radius:0 4px 4px 0;color:#276749;font-weight:600;">
                Anda sekarang dapat men-generate Kartu Tanda Anggota (KTA) ASPAPI Anda!
            </p>
            @endif
            <div style="text-align:center;margin-top:1.5rem;">
                <a href="{{ url('/member/kartu') }}" class="btn">Generate Kartu Anggota</a>
            </div>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} ASPAPI — Asosiasi Sarjana dan Praktisi Administrasi Perkantoran Indonesia
        </div>
    </div>
</body>
</html>