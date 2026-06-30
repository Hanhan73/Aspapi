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

        // Guard idempoten: jangan proses ulang jika sudah verified
        if ($payment->status === 'verified') {
            return back()->with('info', 'Pembayaran ini sudah diverifikasi sebelumnya.');
        }

        $payment->update([
            'status'      => 'verified',
            'verified_by' => auth()->id(),
            'verified_at' => now(),
        ]);

        $member = $payment->member;

        if ($payment->type === 'iuran_tahunan') {
            // Cek apakah member sudah punya iuran aktif untuk tahun ini dari payment lain
            $alreadyExtended = Payment::where('member_id', $member->id)
                ->where('id', '!=', $payment->id)
                ->where('type', 'iuran_tahunan')
                ->where('status', 'verified')
                ->where('payment_year', $payment->payment_year)
                ->exists();

            if (!$alreadyExtended) {
                $baseDate = ($member->active_until && $member->active_until->isFuture())
                    ? $member->active_until->copy()
                    : now();

                $member->update([
                    'status'       => 'active',
                    'dues_paid'    => true,
                    'dues_paid_at' => now(),
                    'active_until' => $baseDate->addYear(),
                ]);
            } else {
                // Tetap update status/dues_paid tapi jangan extend active_until lagi
                $member->update([
                    'status'    => 'active',
                    'dues_paid' => true,
                ]);
            }
        } elseif ($payment->type === 'uang_pangkal') {
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

        $this->issueReceiptForPayment($payment);

        // Notif ke anggota
        try {
            Mail::send(
                'emails.payment-verified',
                [
                    'member'      => $member->fresh(),
                    'typeLabel'   => $payment->type_label,
                    'amountLabel' => 'Rp ' . number_format($payment->amount, 0, ',', '.'),
                    'verifiedAt'  => now()->setTimezone('Asia/Jakarta')->format('d M Y, H:i') . ' WIB',
                ],
                function ($m) use ($member) {
                    $m->to($member->email)->subject('Pembayaran Anda Telah Diverifikasi — ASPAPI');
                }
            );
        } catch (\Exception $e) {
            \Log::warning('Gagal kirim notif anggota (payment verified): ' . $e->getMessage());
        }

        return back()->with('success', 'Pembayaran berhasil diverifikasi.');
    }

    /**
     * Terbitkan kwitansi untuk Payment mandiri yang baru verified.
     * Khusus "gabungan" (uang_pangkal + iuran_tahunan dengan notes sama,
     * dikirim dalam satu upload bukti): tunggu sampai DUA-DUANYA verified,
     * baru terbit SATU kwitansi gabungan. Kalau pasangannya belum verified,
     * payment ini ditandai "menunggu pasangan" dan kwitansi belum terbit.
     */
    private function issueReceiptForPayment(Payment $payment): void
    {
        $member = $payment->member;
        $isGabungan = $payment->notes === 'Pembayaran gabungan';

        if ($isGabungan) {
            $pair = Payment::where('member_id', $payment->member_id)
                ->where('id', '!=', $payment->id)
                ->where('notes', 'Pembayaran gabungan')
                ->where('receipt_path', $payment->receipt_path)
                ->first();

            // Pasangannya belum diupload/ditemukan atau belum verified -> tunda kwitansi
            if (!$pair || $pair->status !== 'verified') {
                return;
            }

            // Hindari duplikat kalau verify() pasangan dipanggil duluan
            if (\App\Models\Receipt::where('source_type', 'payment')
                ->whereJsonContains('payment_id_list', $pair->id)
                ->exists()) {
                return;
            }

            $totalAmount = $payment->amount + $pair->amount;

            \App\Services\ReceiptNumberGenerator::issue([
                'source_type'     => 'payment',
                'source_id'       => $payment->id,
                'member_id'       => $member->id,
                'payment_id_list' => [$payment->id, $pair->id],
                'amount'          => $totalAmount,
                'payer_name'      => $member->full_name,
                'purpose'         => 'Uang Pangkal + Iuran Tahunan (Gabungan) Anggota ASPAPI',
                'issued_by'       => auth()->id(),
            ]);

            return;
        }

        $purpose = $payment->type === 'uang_pangkal'
            ? 'Uang pangkal anggota ASPAPI'
            : 'Iuran tahunan anggota ASPAPI tahun ' . $payment->payment_year;

        \App\Services\ReceiptNumberGenerator::issue([
            'source_type'     => 'payment',
            'source_id'       => $payment->id,
            'member_id'       => $member->id,
            'payment_id_list' => [$payment->id],
            'amount'          => $payment->amount,
            'payer_name'      => $member->full_name,
            'purpose'         => $purpose,
            'issued_by'       => auth()->id(),
        ]);
    }

    public function reject(Request $request, int $id)
    {
        $request->validate(['reason' => 'required|string']);

        $payment = Payment::with('member')->findOrFail($id);
        $payment->update([
            'status'        => 'rejected',
            'reject_reason' => $request->reason,
            'verified_by'   => auth()->id(),
            'verified_at'   => now(),
        ]);

        // Notif ke anggota
        try {
            Mail::send(
                'emails.payment-rejected',
                [
                    'member'      => $payment->member,
                    'typeLabel'   => $payment->type_label,
                    'amountLabel' => 'Rp ' . number_format($payment->amount, 0, ',', '.'),
                    'reason'      => $request->reason,
                ],
                function ($m) use ($payment) {
                    $m->to($payment->member->email)->subject('Pembayaran Anda Ditolak — ASPAPI');
                }
            );
        } catch (\Exception $e) {
            \Log::warning('Gagal kirim notif anggota (payment rejected): ' . $e->getMessage());
        }

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

    $batch->update([
            'status'      => 'verified',
            'verified_by' => auth()->id(),
            'verified_at' => $now,
        ]);

        $this->issueReceiptForBatch($batch);

        return back()->with('success', 'Batch berhasil diverifikasi. ' . $batch->member_count . ' anggota diperbarui.');
    }

    /**
     * Terbitkan satu kwitansi untuk seluruh batch kolektif (1 transfer = 1 kwitansi).
     */
    private function issueReceiptForBatch(PaymentBatch $batch): void
    {
        if (\App\Models\Receipt::where('source_type', 'payment_batch')
            ->where('source_id', $batch->id)
            ->exists()) {
            return; // sudah pernah diterbitkan, jangan dobel
        }

        $batch->loadMissing('region', 'payments');

        \App\Services\ReceiptNumberGenerator::issue([
            'source_type'     => 'payment_batch',
            'source_id'       => $batch->id,
            'region_id'       => $batch->region_id,
            'payment_id_list' => $batch->payments->pluck('id')->toArray(),
            'amount'          => $batch->total_amount,
            'payer_name'      => 'ASPAPI Daerah ' . ($batch->region->province ?? $batch->region->name ?? '-')
                                  . ' (' . $batch->member_count . ' anggota)',
            'purpose'         => 'Iuran tahunan kolektif ' . $batch->member_count . ' anggota tahun ' . $batch->payment_year,
            'issued_by'       => auth()->id(),
        ]);
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