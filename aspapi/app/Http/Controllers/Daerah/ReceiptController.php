<?php

namespace App\Http\Controllers\Daerah;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Receipt;
use Barryvdh\DomPDF\Facade\Pdf;

class ReceiptController extends Controller
{
    public function show(Receipt $receipt)
    {
        $region = auth()->user()->region;
        abort_unless($region, 403, 'Akun ini tidak terhubung ke ASPAPI Daerah manapun.');

        abort_unless(
            $receipt->source_type === 'payment_batch' && $receipt->region_id == $region->id,
            403,
            'Anda tidak berhak mengakses kwitansi ini.'
        );

        $receipt->load('region');

        $batchMembers = Payment::whereIn('id', $receipt->payment_id_list)
            ->with('member')
            ->get()
            ->map(fn ($p) => $p->member)
            ->filter()
            ->values();

        $pdf = Pdf::loadView('bendahara.kwitansi-pdf', compact('receipt', 'batchMembers'))
            ->setPaper('a4', 'portrait');

        $filename = 'Kwitansi-' . str_replace('/', '-', $receipt->receipt_number) . '.pdf';

        return $pdf->stream($filename);
    }
}