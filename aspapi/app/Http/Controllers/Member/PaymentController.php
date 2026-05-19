<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    public function index()
    {
        $member   = auth()->user()->member;
        $payments = $member->payments()->latest()->get();

        return view('member.payment', compact('member', 'payments'));
    }

    public function store(Request $request)
    {
        $member = auth()->user()->member;

        // Cek biodata sudah diverifikasi
        if ($member->biodata_status !== 'verified') {
            return back()->with('error', 'Biodata Anda belum diverifikasi oleh Admin.');
        }

        $request->validate([
            'type'    => 'required|in:uang_pangkal,iuran_tahunan,gabungan',
            'receipt' => 'required|file|mimes:jpg,jpeg,png,pdf|max:3072',
        ]);

        $type = $request->type;

        // ── Validasi kondisi per jenis ──────────────────────────────────────

        if ($type === 'gabungan') {
            // Hanya anggota baru yang belum bayar keduanya
            if ($member->registration_type !== 'baru') {
                return back()->with('error', 'Pembayaran gabungan hanya untuk anggota baru.');
            }
            if ($member->hasPaidUangPangkal()) {
                return back()->with('error', 'Anda sudah pernah membayar uang pangkal. Pilih iuran tahunan saja.');
            }
            if ($member->hasPaidIuranTahunan()) {
                $activeUntil = $member->active_until->format('d M Y');
                return back()->with('error', "Iuran tahunan Anda masih aktif hingga {$activeUntil}. Pilih uang pangkal saja.");
            }
        }

        if ($type === 'uang_pangkal') {
            if ($member->registration_type !== 'baru') {
                return back()->with('error', 'Uang pangkal hanya untuk anggota baru.');
            }
            if ($member->hasPaidUangPangkal()) {
                return back()->with('error', 'Anda sudah pernah membayar uang pangkal.');
            }
        }

        if ($type === 'iuran_tahunan' && $member->hasPaidIuranTahunan()) {
            $activeUntil = $member->active_until->format('d M Y');
            return back()->with('error', "Iuran tahunan Anda masih aktif hingga {$activeUntil}.");
        }

        // ── Simpan bukti & buat record ──────────────────────────────────────

        $receiptPath = $request->file('receipt')->store('payments', 'public');

        if ($type === 'gabungan') {
            // Buat DUA record terpisah dengan satu bukti yang sama
            Payment::create([
                'member_id'      => $member->id,
                'type'           => 'uang_pangkal',
                'payment_method' => 'mandiri',
                'amount'         => 250000,
                'receipt_path'   => $receiptPath,
                'status'         => 'pending',
                'payment_year'   => now()->year,
                'notes'          => 'Pembayaran gabungan',
            ]);

            Payment::create([
                'member_id'      => $member->id,
                'type'           => 'iuran_tahunan',
                'payment_method' => 'mandiri',
                'amount'         => 120000,
                'receipt_path'   => $receiptPath,
                'status'         => 'pending',
                'payment_year'   => now()->year,
                'notes'          => 'Pembayaran gabungan',
            ]);
        } else {
            $amount = $type === 'uang_pangkal' ? 250000 : 120000;

            Payment::create([
                'member_id'      => $member->id,
                'type'           => $type,
                'payment_method' => 'mandiri',
                'amount'         => $amount,
                'receipt_path'   => $receiptPath,
                'status'         => 'pending',
                'payment_year'   => now()->year,
            ]);
        }

        return back()->with('success', 'Bukti pembayaran berhasil diunggah. Menunggu verifikasi dari Bendahara.');
    }
}