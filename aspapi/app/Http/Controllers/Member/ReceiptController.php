<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Receipt;
use Barryvdh\DomPDF\Facade\Pdf;

class ReceiptController extends Controller
{
    public function show(Receipt $receipt)
    {
        $member = auth()->user()->member;

        // Anggota cuma boleh lihat kwitansi pembayaran miliknya sendiri
        abort_unless(
            $receipt->source_type === 'payment' && $receipt->member_id === $member->id,
            403,
            'Anda tidak berhak mengakses kwitansi ini.'
        );

        $receipt->load('member');

        $pdf = Pdf::loadView('kwitansi.pdf', compact('receipt'))
            ->setPaper('a4', 'portrait');

        $filename = 'Kwitansi-' . str_replace('/', '-', $receipt->receipt_number) . '.pdf';

        return $pdf->stream($filename);
    }
}
