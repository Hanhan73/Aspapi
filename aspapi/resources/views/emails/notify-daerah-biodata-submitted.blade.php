<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <style>
        body { font-family: Arial, sans-serif; background: #F8FAFC; margin: 0; padding: 0; }
        .container { max-width: 560px; margin: 40px auto; background: #fff; border-radius: 8px; overflow: hidden; border: 1px solid #D6E8F7; }
        .header { background: linear-gradient(135deg, #1A2A3A, #2A7FC1); padding: 1.75rem 2rem; }
        .header h1 { color: #fff; font-size: 1.1rem; margin: 0; font-weight: 700; }
        .header p { color: #6AAFE6; font-size: 0.8rem; margin: 0.25rem 0 0; }
        .badge { display: inline-block; padding: 0.35rem 0.875rem; border-radius: 20px; font-weight: 700; font-size: 0.75rem; margin-bottom: 1.25rem; }
        .badge-new { background: #EEF4FB; color: #2A7FC1; border: 1px solid #D6E8F7; }
        .badge-resubmit { background: #FEF8EC; color: #B8860B; border: 1px solid #F5D78E; }
        .body { padding: 2rem; }
        .body p { font-size: 0.875rem; color: #4A6580; line-height: 1.8; margin: 0 0 1rem; }
        .info-box { background: #F8FAFC; border: 1px solid #D6E8F7; border-radius: 6px; padding: 1.25rem; margin: 1rem 0; }
        .info-row { display: flex; justify-content: space-between; padding: 0.4rem 0; border-bottom: 1px solid #EEF4FB; font-size: 0.825rem; }
        .info-row:last-child { border-bottom: none; }
        .info-label { color: #8A97A4; }
        .info-value { color: #1A2A3A; font-weight: 600; text-align: right; }
        .alert { background: #EEF4FB; border-left: 3px solid #2A7FC1; border-radius: 4px; padding: 0.875rem 1rem; margin: 1rem 0; font-size: 0.825rem; color: #1A5F9A; line-height: 1.6; }
        .btn { display: inline-block; padding: 0.75rem 1.75rem; background: #2A7FC1; color: #fff !important; text-decoration: none; border-radius: 4px; font-weight: 700; font-size: 0.8rem; letter-spacing: 0.06em; text-transform: uppercase; }
        .footer { background: #F8FAFC; padding: 1.25rem 2rem; text-align: center; font-size: 0.72rem; color: #B0CCDF; border-top: 1px solid #EEF4FB; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔔 {{ $isResubmit ? 'Verifikasi Ulang Biodata' : 'Pengajuan Biodata Baru' }}</h1>
            <p>Notifikasi untuk {{ $region?->name ?? ('ASPAPI ' . $region?->province) ?? 'ASPAPI Daerah' }}</p>
        </div>
        <div class="body">
            <span class="badge {{ $isResubmit ? 'badge-resubmit' : 'badge-new' }}">
                {{ $isResubmit ? '↩ Verifikasi Ulang' : 'Biodata Baru' }}
            </span>
            <p>
                Anggota berikut mengajukan diri sebagai bagian dari wilayah Anda dan membutuhkan verifikasi biodata.
                @if ($isResubmit) Ini adalah pengajuan ulang setelah perbaikan. @endif
            </p>

            <div class="info-box">
                <div class="info-row">
                    <span class="info-label">Nama Lengkap</span>
                    <span class="info-value">{{ $member->full_name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Email</span>
                    <span class="info-value">{{ $member->email }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Tipe Pendaftaran</span>
                    <span class="info-value">{{ $member->registration_type === 'baru' ? 'Anggota Baru' : 'Anggota Lama' }}</span>
                </div>
                @if ($member->claims_old_member)
                <div class="info-row">
                    <span class="info-label">Klaim Bergabung Sejak</span>
                    <span class="info-value" style="color:#B8860B;">{{ $member->claimed_join_year }}</span>
                </div>
                @endif
                <div class="info-row">
                    <span class="info-label">ASPAPI Daerah Dipilih</span>
                    <span class="info-value">{{ $region?->name ?? ('ASPAPI ' . $region?->province) ?? '—' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Waktu Pengajuan</span>
                    <span class="info-value">{{ $submittedAt }}</span>
                </div>
            </div>

            <div class="alert">
                <strong>📋 Perlu Tindakan:</strong> Login ke portal ASPAPI Daerah untuk meninjau dan memverifikasi biodata anggota ini. Jika anggota tidak sesuai wilayah Anda, tolak dengan catatan — admin pusat bisa memindahkan ke daerah yang benar.
            </div>

            <div style="text-align:center;margin-top:1.5rem;">
                <a href="{{ $daerahUrl }}" class="btn">Verifikasi Biodata</a>
            </div>
        </div>
        <div class="footer">
            Email ini dikirim otomatis oleh sistem ASPAPI. Jangan balas email ini.
        </div>
    </div>
</body>
</html>