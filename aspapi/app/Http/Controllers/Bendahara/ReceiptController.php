<?php

namespace App\Http\Controllers\Bendahara;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Receipt;
use Barryvdh\DomPDF\Facade\Pdf;

class ReceiptController extends Controller
{
    public function show(Receipt $receipt)
    {
        $receipt->load('member', 'region');

        // Untuk batch: load daftar anggota dari payment_id_list
        $batchMembers = collect();
        if ($receipt->source_type === 'payment_batch') {
            $batchMembers = Payment::whereIn('id', $receipt->payment_id_list)
                ->with('member')
                ->get()
                ->map(fn ($p) => $p->member)
                ->filter()
                ->values();
        }

        $pdf = Pdf::loadView('kwitansi.pdf', compact('receipt', 'batchMembers'))
            ->setPaper('a4', 'portrait');

        $filename = 'Kwitansi-' . str_replace('/', '-', $receipt->receipt_number) . '.pdf';

        return $pdf->stream($filename);
    }
}