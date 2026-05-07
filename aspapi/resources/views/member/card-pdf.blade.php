<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family: Arial, sans-serif; }

        .card {
            width: 85.6mm;
            height: 53.98mm;
            border-radius: 4px;
            overflow: hidden;
            position: relative;
            background: {{ $member->member_type === 'luar_biasa' ? 'linear-gradient(135deg, #8B1A1A, #C0392B)' : ($member->member_type === 'kehormatan' ? 'linear-gradient(135deg, #4A4A4A, #1A1A1A)' : 'linear-gradient(135deg, #1A5F9A, #2A7FC1)') }};
        }

        .top-bar {
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg, #E8B84B, #C0392B);
        }

        .header {
            position: absolute;
            top: 6px; left: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .header-logo {
            width: 24px;
            height: 24px;
        }

        .header-text { color: #fff; }
        .header-text .kta-label { font-size: 5pt; font-weight: bold; letter-spacing: 0.05em; }
        .header-text .org-name  { font-size: 3.5pt; color: rgba(255,255,255,0.7); }

        .photo {
            position: absolute;
            right: 8px; top: 8px;
            width: 22mm; height: 28mm;
            border: 1.5px solid rgba(255,255,255,0.4);
            border-radius: 2px;
            overflow: hidden;
            background: rgba(255,255,255,0.1);
        }

        .photo img { width: 100%; height: 100%; object-fit: cover; }

        .info {
            position: absolute;
            bottom: 0; left: 0; right: 0;
            padding: 6px 8px;
            background: rgba(0,0,0,0.3);
        }

        .member-type { color: #E8B84B; font-size: 4pt; font-weight: bold; letter-spacing: 0.08em; text-transform: uppercase; }
        .member-name { color: #fff; font-size: 8pt; font-weight: bold; margin-top: 1px; line-height: 1.2; }
        .member-inst { color: rgba(255,255,255,0.7); font-size: 4pt; margin-top: 1px; }

        .footer-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 5px;
        }

        .number-label { color: rgba(255,255,255,0.6); font-size: 3.5pt; letter-spacing: 0.08em; text-transform: uppercase; }
        .number-value { color: #fff; font-size: 6pt; font-weight: bold; letter-spacing: 0.12em; font-family: monospace; }
        .date-label  { color: rgba(255,255,255,0.6); font-size: 3.5pt; letter-spacing: 0.08em; text-transform: uppercase; text-align: right; }
        .date-value  { color: #fff; font-size: 5.5pt; font-weight: bold; text-align: right; }
    </style>
</head>
<body>
    <div class="card">
        <div class="top-bar"></div>

        <div class="header">
            @if (file_exists(public_path('images/logo-aspapi.png')))
            <img src="{{ public_path('images/logo-aspapi.png') }}" class="header-logo"/>
            @endif
            <div class="header-text">
                <p class="kta-label">KARTU TANDA ANGGOTA</p>
                <p class="org-name">ASOSIASI SARJANA DAN PRAKTISI</p>
                <p class="org-name">ADMINISTRASI PERKANTORAN INDONESIA</p>
            </div>
        </div>

        <div class="photo">
            @if ($member->photo && file_exists(storage_path('app/public/' . $member->photo)))
            <img src="{{ storage_path('app/public/' . $member->photo) }}"/>
            @endif
        </div>

        <div class="info">
            <p class="member-type">{{ $member->member_type_label }}</p>
            <p class="member-name">{{ $member->full_name }}</p>
            <p class="member-inst">{{ $member->institution ?? 'ASPAPI' }}</p>

            <div class="footer-row">
                <div>
                    <p class="number-label">No. Anggota</p>
                    <p class="number-value">{{ $member->member_number }}</p>
                </div>
                <div>
                    <p class="date-label">Berlaku s.d.</p>
                    <p class="date-value">
                        {{ $member->active_until ? $member->active_until->format('d/m/Y') : now()->addYear()->format('d/m/Y') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>