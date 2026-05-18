<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>Reminder Kadaluarsa Kartu Anggota</title>
    <style>
        body        { font-family: Arial, sans-serif; background:#f4f7fb; margin:0; padding:0; }
        .wrap       { max-width:560px; margin:40px auto; background:#fff; border-radius:8px; overflow:hidden; box-shadow:0 2px 12px rgba(0,0,0,0.08); }
        .header     { background:#1A3A5C; padding:28px 32px; }
        .header h1  { color:#fff; font-size:20px; margin:0; letter-spacing:0.04em; }
        .header p   { color:rgba(255,255,255,0.6); font-size:12px; margin:4px 0 0; }
        .urgency-bar{ padding:14px 32px; text-align:center; font-size:13px; font-weight:700; letter-spacing:0.04em; }
        .body       { padding:28px 32px; }
        .body p     { color:#4A6580; font-size:14px; line-height:1.7; margin:0 0 14px; }
        .info-box   { background:#EEF4FB; border-radius:6px; padding:16px 20px; margin:20px 0; }
        .info-box p { margin:0; color:#1A3A5C; font-size:13px; }
        .info-box .label { font-size:11px; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; color:#8A97A4; margin-bottom:4px; }
        .info-box .val   { font-size:16px; font-weight:700; color:#1A2A3A; }
        .cta        { display:block; text-align:center; padding:14px 24px; border-radius:6px; font-size:14px; font-weight:700; text-decoration:none; margin:20px 0; }
        .steps      { background:#F8FAFC; border-radius:6px; padding:16px 20px; margin:20px 0; }
        .steps p    { color:#4A6580; font-size:13px; margin:0 0 8px; }
        .steps ol   { margin:0; padding-left:20px; color:#4A6580; font-size:13px; line-height:1.8; }
        .footer     { background:#F4F7FB; padding:16px 32px; text-align:center; }
        .footer p   { color:#B0CCDF; font-size:12px; margin:0; }
    </style>
</head>
<body>
<div class="wrap">

    {{-- Header --}}
    <div class="header">
        <h1>ASPAPI</h1>
        <p>Asosiasi Sarjana dan Praktisi Administrasi Perkantoran Indonesia</p>
    </div>

    {{-- Urgency bar — warna berubah sesuai sisa hari --}}
    @if ($daysLeft <= 1)
    <div class="urgency-bar" style="background:#C0392B;color:#fff;">
        🚨 KARTU ANGGOTA ANDA KADALUARSA BESOK!
    </div>
    @elseif ($daysLeft <= 7)
    <div class="urgency-bar" style="background:#E8B84B;color:#fff;">
        ⚠ Kartu anggota Anda kadaluarsa dalam {{ $daysLeft }} hari
    </div>
    @else
    <div class="urgency-bar" style="background:#2A7FC1;color:#fff;">
        ℹ Pengingat: Kartu anggota Anda akan kadaluarsa dalam {{ $daysLeft }} hari
    </div>
    @endif

    <div class="body">
        <p>Yth. <strong>{{ $member->full_name }}</strong>,</p>

        <p>
            Kami mengingatkan bahwa Kartu Tanda Anggota (KTA) Anda di ASPAPI akan segera
            @if ($daysLeft <= 1) <strong style="color:#C0392B;">kadaluarsa besok</strong>.
            @else kadaluarsa dalam <strong>{{ $daysLeft }} hari</strong>.
            @endif
            Segera lakukan perpanjangan agar keanggotaan Anda tetap aktif.
        </p>

        {{-- Info kartu --}}
        <div class="info-box">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                <div>
                    <p class="label">Nama Anggota</p>
                    <p class="val">{{ $member->full_name }}</p>
                </div>
                <div>
                    <p class="label">No. Anggota</p>
                    <p class="val" style="font-family:monospace;font-size:14px;">{{ $member->member_number ?? '—' }}</p>
                </div>
                <div>
                    <p class="label">Berlaku Hingga</p>
                    <p class="val" style="color:{{ $daysLeft <= 1 ? '#C0392B' : '#1A2A3A' }};">
                        {{ $member->active_until?->translatedFormat('d F Y') ?? '—' }}
                    </p>
                </div>
                <div>
                    <p class="label">Sisa Waktu</p>
                    <p class="val" style="color:{{ $daysLeft <= 7 ? '#C0392B' : '#1A2A3A' }};">
                        {{ $daysLeft }} hari
                    </p>
                </div>
            </div>
        </div>

        {{-- CTA --}}
        <a href="{{ url('/member/pembayaran') }}" class="cta"
           style="background:#2A7FC1;color:#fff;">
            Bayar Iuran Tahunan Sekarang →
        </a>

        {{-- Langkah perpanjangan --}}
        <div class="steps">
            <p><strong>Cara perpanjang keanggotaan:</strong></p>
            <ol>
                <li>Login ke portal ASPAPI</li>
                <li>Pilih menu <strong>Pembayaran</strong></li>
                <li>Pilih jenis <strong>Iuran Tahunan</strong></li>
                <li>Upload bukti transfer dan submit</li>
                <li>Tunggu verifikasi dari bendahara</li>
            </ol>
        </div>

        <p style="font-size:13px;color:#8A97A4;">
            Jika Anda sudah melakukan pembayaran dan sedang menunggu verifikasi, abaikan email ini.
            Untuk pertanyaan lebih lanjut, hubungi pengurus ASPAPI.
        </p>

        <p>Salam hormat,<br/><strong>Tim ASPAPI</strong></p>
    </div>

    <div class="footer">
        <p>© {{ date('Y') }} ASPAPI — Email ini dikirim otomatis, harap tidak membalas.</p>
    </div>
</div>
</body>
</html>