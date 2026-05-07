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
            return back()->with('error', 'Biodata Anda belum diverifikasi oleh Admin. Harap tunggu verifikasi terlebih dahulu.');
        }

        $request->validate([
            'type'    => 'required|in:uang_pangkal,iuran_tahunan',
            'receipt' => 'required|file|mimes:jpg,jpeg,png,pdf|max:3072',
        ]);

        // Cek kalau anggota baru sudah pernah bayar uang pangkal
        if ($request->type === 'uang_pangkal' && $member->hasPaidUangPangkal()) {
            return back()->with('error', 'Anda sudah pernah membayar uang pangkal.');
        }

        // Cek kalau sudah bayar iuran tahun ini
        if ($request->type === 'iuran_tahunan' && $member->hasPaidIuranTahunan()) {
            return back()->with('error', 'Anda sudah membayar iuran tahunan untuk tahun ini.');
        }

        $amount = $request->type === 'uang_pangkal' ? 250000 : 120000;

        $receiptPath = $request->file('receipt')->store('payments', 'public');

        Payment::create([
            'member_id'      => $member->id,
            'type'           => $request->type,
            'payment_method' => 'mandiri',
            'amount'         => $amount,
            'receipt_path'   => $receiptPath,
            'status'         => 'pending',
            'payment_year'   => now()->year,
        ]);

        return back()->with('success', 'Bukti pembayaran berhasil diunggah. Menunggu verifikasi dari Bendahara.');
    }
}