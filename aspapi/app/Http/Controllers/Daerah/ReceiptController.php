<?php

namespace App\Http\Controllers\Daerah;

use App\Http\Controllers\Controller;
use App\Models\Receipt;
use Barryvdh\DomPDF\Facade\Pdf;

class ReceiptController extends Controller
{
    public function show(Receipt $receipt)
    {
        $region = auth()->user()->region;
        abort_unless($region, 403, 'Akun ini tidak terhubung ke ASPAPI Daerah manapun.');

        // ASPAPI Daerah cuma boleh lihat kwitansi batch kolektif miliknya sendiri
        abort_unless(
            $receipt->source_type === 'payment_batch' && $receipt->region_id === $region->id,
            403,
            'Anda tidak berhak mengakses kwitansi ini.'
        );

        $receipt->load('region');

        $pdf = Pdf::loadView('kwitansi.pdf', compact('receipt'))
            ->setPaper('a4', 'portrait');

        $filename = 'Kwitansi-' . str_replace('/', '-', $receipt->receipt_number) . '.pdf';

        return $pdf->stream($filename);
    }
}
