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

    /**
     * List pembayaran mandiri — filter: status, type, method, search, year
     */
    public function payments(Request $request)
    {
        $payments = Payment::with(['member', 'verifier'])
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
            ->when($request->filled('type'),   fn($q) => $q->where('type', $request->type))
            ->when($request->filled('method'), fn($q) => $q->where('payment_method', $request->method))
            ->when($request->filled('year'),   fn($q) => $q->where('payment_year', $request->year))
            ->when(
                $request->filled('search'),
                fn($q) =>
                $q->whereHas(
                    'member',
                    fn($sub) =>
                    $sub->where('full_name', 'like', '%' . $request->search . '%')
                        ->orWhere('email', 'like', '%' . $request->search . '%')
                )
            )
            ->latest()
            ->paginate(20)
            ->withQueryString();

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

        $member = $payment->member;

        if ($payment->type === 'iuran_tahunan') {
            // Jika member masih punya sisa masa aktif di masa depan (perpanjang lebih awal),
            // tambahkan 1 tahun dari active_until lama agar tidak rugi hari.
            // Jika sudah kadaluarsa atau belum pernah bayar, hitung dari sekarang.
            $baseDate = ($member->active_until && $member->active_until->isFuture())
                ? $member->active_until->copy()
                : now();

            $member->update([
                'status'       => 'active',      // aktifkan jika sebelumnya pending
                'dues_paid'    => true,
                'dues_paid_at' => now(),
                'active_until' => $baseDate->addYear(),
            ]);
        } elseif ($payment->type === 'uang_pangkal') {
            // Uang pangkal bukan iuran periodik, tidak mengatur active_until
            $member->update([
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

        try {
            Mail::send('emails.payment-verified', ['payment' => $payment], function ($m) use ($payment) {
                $m->to($payment->member->email)->subject('Pembayaran Anda Telah Diverifikasi — ASPAPI');
            });
        } catch (\Exception $e) {
            \Log::error('Email payment verified gagal: ' . $e->getMessage());
        }

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

    /**
     * List batch kolektif — filter: status, region, year
     */
    public function batches(Request $request)
    {
        $batches = PaymentBatch::with(['region', 'submitter', 'verifier'])
            ->when($request->filled('status'),    fn($q) => $q->where('status', $request->status))
            ->when($request->filled('region_id'), fn($q) => $q->where('region_id', $request->region_id))
            ->when($request->filled('year'),      fn($q) => $q->where('payment_year', $request->year))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $regions = \App\Models\Region::orderBy('province')->get();

        return view('bendahara.batches', compact('batches', 'regions'));
    }

    public function verifyBatch(Request $request, int $id)
    {
        $batch = PaymentBatch::with('payments.member')->findOrFail($id);

        $now = now();

        $batch->payments()->update([
            'status'      => 'verified',
            'verified_by' => auth()->id(),
            'verified_at' => $now,
        ]);

        foreach ($batch->payments as $payment) {
            $member = $payment->member;

            // Hitung active_until per member — logika sama dengan verify mandiri
            $baseDate = ($member->active_until && $member->active_until->isFuture())
                ? $member->active_until->copy()
                : $now->copy();

            $member->update([
                'status'       => 'active',      // aktifkan jika sebelumnya pending
                'dues_paid'    => true,
                'dues_paid_at' => $now,
                'active_until' => $baseDate->addYear(),
            ]);
        }

        $batch->update([
            'status'      => 'verified',
            'verified_by' => auth()->id(),
            'verified_at' => $now,
        ]);

        return back()->with('success', 'Batch berhasil diverifikasi. ' . $batch->member_count . ' anggota diperbarui.');
    }

    public function showBatch(int $id)
    {
        $batch = PaymentBatch::with(['payments.member', 'region', 'submitter', 'verifier'])
            ->findOrFail($id);

        return view('bendahara.batch-detail', compact('batch'));
    }

    public function rejectBatch(Request $request, int $id)
    {
        $request->validate(['reason' => 'required|string|max:500']);

        $batch = PaymentBatch::findOrFail($id);
        $batch->update([
            'status'        => 'rejected',
            'reject_reason' => $request->reason,
            'verified_by'   => auth()->id(),
            'verified_at'   => now(),
        ]);

        return redirect()->route('bendahara.batch.show', $id)
            ->with('success', 'Batch ditolak.');
    }
}
