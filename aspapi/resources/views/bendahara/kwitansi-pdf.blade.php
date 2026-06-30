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
            margin: 25px 0 35px 0;
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

        .date-line {
            width: 300px;
            float: right;
            text-align: left;
            margin-bottom: 0;
        }

        .signature-block {
            width: 100%;
            margin-top: 10px;
        }

        .signature-right {
            width: 300px;
            float: right;
            text-align: center;
            position: relative;
        }

        .sign-area {
            position: relative;
            height: 195px;
            /* dibesarin lagi biar muat stempel & ttd */
        }

        .stempel {
            position: absolute;
            top: -20px;
            left: 20px;
            width: 220px;
            /* sebelumnya 175 */
            opacity: 0.85;
            z-index: 0;
        }

        .ttd-img {
            position: absolute;
            top: 35px;
            left: 95px;
            width: 190px;
            /* sebelumnya 150 */
            z-index: 1;
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

        <p class="date-line">Surakarta, {{ $receipt->created_at->translatedFormat('d F Y') }}</p>

        <div class="signature-block">
            <div class="signature-right">
                <p style="margin-bottom: 2px;">a.n. Bendahara Umum ASPAPI Pusat<br>
                    Periode 2022&ndash;2026<br>
                    Bendahara III,</p>

                <div class="sign-area">
                    <img class="stempel" src="{{ storage_path('app/kwitansi/kwitansi-stempel.png') }}">
                    <img class="ttd-img" src="{{ storage_path('app/kwitansi/kwitansi-ttd.png') }}">
                </div>

                <p style="margin-top: 0; font-weight: bold; text-decoration: underline;">
                    Sitti Hardiyanti Arhas, S.Pd., M.Pd.
                </p>
            </div>
        </div>
    </div>

    <div class="footer">
        <img src="{{ storage_path('app/kwitansi/kwitansi-footer.png') }}">
    </div>

</body>

</html>