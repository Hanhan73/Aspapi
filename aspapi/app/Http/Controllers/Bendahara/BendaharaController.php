<?php

namespace App\Http\Controllers\Bendahara;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\PaymentBatch;
use App\Models\MemberVerificationLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class BendaharaController extends Controller
{
    public function index()
    {
        $pendingPayments = Payment::where('status', 'pending')->count();
        $pendingBatches  = PaymentBatch::where('status', 'pending')->count();
        $totalVerified   = Payment::where('status', 'verified')->sum('amount');

        return view('bendahara.dashboard', compact('pendingPayments', 'pendingBatches', 'totalVerified'));
    }

    public function payments(Request $request)
    {
        $payments = Payment::with(['member', 'verifier'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->type,   fn($q) => $q->where('type', $request->type))
            ->when($request->method, fn($q) => $q->where('payment_method', $request->method))
            ->latest()
            ->paginate(20);

        return view('bendahara.payments', compact('payments'));
    }

    public function verify(Request $request, int $id)
    {
        $payment = Payment::with('member')->findOrFail($id);
        $payment->update([
            'status'      => 'verified',
            'verified_by' => auth()->id(),
            'verified_at' => now(),
        ]);

        // Update status dues di member
        if ($payment->type === 'uang_pangkal' || $payment->type === 'iuran_tahunan') {
            $payment->member->update([
                'dues_paid'    => true,
                'dues_paid_at' => now(),
            ]);
        }

        MemberVerificationLog::create([
            'member_id'   => $payment->member_id,
            'verified_by' => auth()->id(),
            'action'      => 'approve_payment',
            'note'        => 'Pembayaran ' . $payment->type_label . ' diverifikasi',
        ]);

        Mail::send('emails.payment-verified', ['payment' => $payment], function ($m) use ($payment) {
            $m->to($payment->member->email)->subject('Pembayaran Anda Telah Diverifikasi — ASPAPI');
        });

        return back()->with('success', 'Pembayaran berhasil diverifikasi.');
    }

    public function reject(Request $request, int $id)
    {
        $request->validate(['reason' => 'required|string']);

        $payment = Payment::findOrFail($id);
        $payment->update([
            'status'        => 'rejected',
            'reject_reason' => $request->reason,
            'verified_by'   => auth()->id(),
            'verified_at'   => now(),
        ]);

        return back()->with('success', 'Pembayaran ditolak.');
    }

    public function batches(Request $request)
    {
        $batches = PaymentBatch::with(['region', 'submitter', 'verifier'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(20);

        return view('bendahara.batches', compact('batches'));
    }

    public function verifyBatch(Request $request, int $id)
    {
        $batch = PaymentBatch::with('payments.member')->findOrFail($id);

        // Verifikasi semua payment dalam batch
        $batch->payments()->update([
            'status'      => 'verified',
            'verified_by' => auth()->id(),
            'verified_at' => now(),
        ]);

        // Update dues semua member dalam batch
        foreach ($batch->payments as $payment) {
            $payment->member->update(['dues_paid' => true, 'dues_paid_at' => now()]);
        }

        $batch->update([
            'status'      => 'verified',
            'verified_by' => auth()->id(),
            'verified_at' => now(),
        ]);

        return back()->with('success', 'Batch pembayaran berhasil diverifikasi. ' . $batch->member_count . ' anggota diperbarui.');
    }
}