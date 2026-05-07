<?php

namespace App\Http\Controllers\RegionStaff;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\User;
use App\Models\Payment;
use App\Models\PaymentBatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class RegionMemberController extends Controller
{
    private function region()
    {
        return auth()->user()->region;
    }

    public function index()
    {
        $region      = $this->region();
        $memberCount = Member::where('registered_by_region_id', $region->id)->count();
        $paidCount   = Member::where('registered_by_region_id', $region->id)->where('dues_paid', true)->count();

        return view('daerah.dashboard', compact('region', 'memberCount', 'paidCount'));
    }

    public function members(Request $request)
    {
        $region  = $this->region();
        $members = Member::where('registered_by_region_id', $region->id)
            ->when($request->search, fn($q) => $q->where('full_name', 'like', '%'.$request->search.'%'))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(20);

        return view('daerah.members', compact('members', 'region'));
    }

    public function batchForm()
    {
        return view('daerah.batch-register');
    }

    // Upload Excel anggota baru — tanpa verifikasi email
    public function batchStore(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:5120',
        ]);

        $region = $this->region();
        $rows   = Excel::toArray([], $request->file('file'))[0];
        $count  = 0;

        foreach ($rows as $i => $row) {
            if ($i === 0) continue; // Skip header

            [$fullName, $email, $phone, $institution, $gender] = array_pad($row, 5, null);

            if (!$fullName || !$email) continue;
            if (User::where('email', $email)->exists()) continue;

            $password = Str::random(10);

            $user = User::create([
                'name'           => $fullName,
                'email'          => $email,
                'password'       => Hash::make($password),
                'role'           => 'anggota',
                'email_verified' => true, // batch tidak perlu verifikasi email
                'region_id'      => $region->id,
            ]);

            Member::create([
                'user_id'                => $user->id,
                'full_name'              => $fullName,
                'email'                  => $email,
                'phone'                  => $phone,
                'institution'            => $institution,
                'gender'                 => $gender === 'L' ? 'L' : 'P',
                'registration_type'      => 'baru',
                'is_batch'               => true,
                'registered_by_region_id'=> $region->id,
                'status'                 => 'pending',
                'biodata_status'         => 'pending',
            ]);

            // Kirim email password ke anggota
            \Mail::send('emails.batch-welcome', [
                'name'     => $fullName,
                'email'    => $email,
                'password' => $password,
                'region'   => $region->name,
            ], function ($m) use ($email, $fullName) {
                $m->to($email)->subject('Akun ASPAPI Anda Telah Dibuat');
            });

            $count++;
        }

        return back()->with('success', $count . ' anggota baru berhasil didaftarkan.');
    }

    public function payBatchForm()
    {
        $region  = $this->region();
        $members = Member::where('registered_by_region_id', $region->id)
            ->where('status', 'active')
            ->where('dues_paid', false)
            ->get();

        return view('daerah.batch-pay', compact('members', 'region'));
    }

    public function payBatchStore(Request $request)
    {
        $request->validate([
            'member_ids'  => 'required|array|min:1',
            'member_ids.*'=> 'exists:members,id',
            'receipt'     => 'required|image|mimes:jpg,jpeg,png,pdf|max:5120',
            'year'        => 'required|integer|min:2010|max:' . (now()->year + 1),
        ]);

        $region      = $this->region();
        $memberIds   = $request->member_ids;
        $amount      = count($memberIds) * 120000;
        $receiptPath = $request->file('receipt')->store('payment-batches', 'public');

        $batch = PaymentBatch::create([
            'region_id'    => $region->id,
            'submitted_by' => auth()->id(),
            'receipt_path' => $receiptPath,
            'total_amount' => $amount,
            'member_count' => count($memberIds),
            'status'       => 'pending',
            'payment_year' => $request->year,
        ]);

        foreach ($memberIds as $memberId) {
            Payment::create([
                'member_id'      => $memberId,
                'type'           => 'iuran_tahunan',
                'payment_method' => 'kolektif',
                'amount'         => 120000,
                'status'         => 'pending',
                'payment_year'   => $request->year,
                'batch_id'       => $batch->id,
            ]);
        }

        return back()->with('success', 'Pembayaran kolektif berhasil dikirim. Menunggu verifikasi dari Bendahara.');
    }
}