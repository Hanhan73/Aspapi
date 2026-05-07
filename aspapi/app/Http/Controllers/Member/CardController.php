<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;

class CardController extends Controller
{
    public function show()
    {
        $member = auth()->user()->member;
        return view('member.card', compact('member'));
    }

    public function generate()
    {
        $member = auth()->user()->member;

        if (!$member->canGenerateCard()) {
            return back()->with('error', 'Anda belum memenuhi syarat untuk generate kartu anggota.');
        }

        // Generate nomor anggota kalau belum ada
        if (!$member->member_number) {
            $member->assignMemberNumber();
        }

        return back()->with('success', 'Kartu anggota berhasil digenerate!');
    }

    public function download()
    {
        $member = auth()->user()->member;

        if (!$member->member_number) {
            return back()->with('error', 'Generate kartu terlebih dahulu.');
        }

        $pdf = Pdf::loadView('member.card-pdf', compact('member'))
                  ->setPaper([0, 0, 255.12, 153.07]); // CR80 card size in points

        return $pdf->download('KTA-ASPAPI-' . $member->member_number . '.pdf');
    }
}