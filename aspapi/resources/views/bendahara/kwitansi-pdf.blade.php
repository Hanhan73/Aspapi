<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
    @font-face {
        font-family: 'DejaVu Sans';
        src: local('DejaVu Sans');
    }

    body {
        font-family: 'DejaVu Sans', sans-serif;
        font-size: 12px;
        color: #1A2A3A;
        margin: 0;
        padding: 0;
    }

    .header img {
        width: 100%;
        display: block;
    }

    .content {
        padding: 10px 40px 0 40px;
    }

    h1.title {
        text-align: center;
        font-size: 22px;
        letter-spacing: 2px;
        margin: -20px 0 35px 0;
    }

    table.fields {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 30px;
    }

    table.fields td {
        padding: 4px 0;
        vertical-align: top;
    }

    table.fields td.label {
        width: 160px;
    }

    table.fields td.colon {
        width: 15px;
    }

    /* ── Tabel rincian anggota batch ── */
    table.member-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
        font-size: 10px;
    }

    table.member-table th {
        background: #EEF4FB;
        color: #2A7FC1;
        font-weight: 700;
        padding: 5px 8px;
        text-align: left;
        border: 1px solid #D6E8F7;
    }

    table.member-table td {
        padding: 4px 8px;
        border: 1px solid #D6E8F7;
        vertical-align: top;
    }

    table.member-table tr:nth-child(even) td {
        background: #F8FAFC;
    }

    table.member-table td.no-col {
        width: 30px;
        text-align: center;
    }

    /* Blok tanda tangan pakai table, bukan float — float di DomPDF gampang
       kacau kalau ada lebih dari satu elemen yang float bertumpuk. Dengan
       table, kolom kanan (tanggal + a.n. + ttd + nama) otomatis jadi satu
       kolom yang sejajar rapi. */
    table.signature-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    table.signature-table td {
        vertical-align: top;
    }

    table.signature-table td.spacer {
        width: 55%;
    }

    table.signature-table td.sign-col {
        width: 45%;
        text-align: center;
    }

    .date-line {
        text-align: center;
        margin: 0 0 10px 0;
    }

    .sign-area {
        position: relative;
        height: 200px;
        /* ruang buat cap + ttd, jangan dikecilin biar gak numpuk sama nama */
    }

    .stempel {
        position: absolute;
        top: 0;
        margin-left: -50px;
        width: 270px;
        opacity: 0.85;
        z-index: 0;
        margin-top: -75px;
    }

    .ttd-img {
        position: absolute;
        top: 0;
        left: 50px;
        width: 200px;
        z-index: 1;
    }

    .signer-name {
        margin-top: -130px;
        left: 150px;
        font-weight: bold;
        text-decoration: underline;
    }

    .footer {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
    }

    .footer img {
        width: 100%;
        display: block;
    }
    </style>
</head>

<body>

    <div class="header">
        <img src="{{ storage_path('app/kwitansi/kwitansi-header.png') }}">
    </div>

    <div class="content">
        <h1 class="title">KWITANSI</h1>

        <table class="fields">
            <tr>
                <td class="label">Nomor</td>
                <td class="colon">:</td>
                <td><strong>{{ $receipt->receipt_number }}</strong></td>
            </tr>
            <tr>
                <td class="label">Telah terima dari</td>
                <td class="colon">:</td>
                <td>{{ $receipt->payer_name }}</td>
            </tr>
            <tr>
                <td class="label">Uang sejumlah</td>
                <td class="colon">:</td>
                <td>{{ $receipt->amountLabel() }}, (<em>{{ $receipt->amountInWords() }}</em>)</td>
            </tr>
            <tr>
                <td class="label">Untuk pembayaran</td>
                <td class="colon">:</td>
                <td>{{ $receipt->purpose }}</td>
            </tr>
        </table>

        {{-- Tabel rincian anggota — hanya untuk kwitansi batch kolektif --}}
        @if ($receipt->source_type === 'payment_batch' && $batchMembers->isNotEmpty())
        <table class="member-table">
            <thead>
                <tr>
                    <th class="no-col">No.</th>
                    <th>Nama Lengkap</th>
                    <th>Instansi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($batchMembers as $i => $m)
                <tr>
                    <td class="no-col">{{ $i + 1 }}</td>
                    <td>{{ $m->full_name_with_title ?? $m->full_name }}</td>
                    <td>{{ $m->institution ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif

        <table class="signature-table">
            <tr>
                <td class="spacer"></td>
                <td class="sign-col">
                    <p class="date-line">Surakarta, {{ $receipt->created_at->translatedFormat('d F Y') }}</p>

                    <p style="margin: 0 0 2px 0;">a.n. Bendahara Umum ASPAPI Pusat<br>
                        Periode 2022&ndash;2026<br>
                        Bendahara III,</p>

                    <div class="sign-area">
                        <img class="stempel" src="{{ storage_path('app/kwitansi/kwitansi-stempel.png') }}">
                        <img class="ttd-img" src="{{ storage_path('app/kwitansi/kwitansi-ttd.png') }}">
                    </div>

                    <p class="signer-name">
                        Sitti Hardiyanti Arhas, S.Pd., M.Pd.
                    </p>
                </td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <img src="{{ storage_path('app/kwitansi/kwitansi-footer.png') }}">
    </div>

</body>

</html>