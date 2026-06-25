<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\MemberVerificationLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class MemberVerificationController extends Controller
{
    public function index(Request $request)
    {
        $query = Member::with(['user', 'provinceModel', 'cityModel', 'registeredByRegion'])
            ->when($request->status,   fn($q) => $q->where('biodata_status', $request->status))
            ->when($request->type,     fn($q) => $q->where('registration_type', $request->type))
            ->when($request->old,      fn($q) => $q->where('claims_old_member', true))
            ->when($request->search,   fn($q) => $q->where('full_name', 'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%'))
            ->latest();

        $members       = $query->paginate(20);
        $pendingCount  = Member::where('biodata_status', 'pending')->count();
        $oldClaimCount = Member::where('claims_old_member', true)->where('biodata_status', 'pending')->count();

        return view('admin.members.verify', compact('members', 'pendingCount', 'oldClaimCount'));
    }

    public function approve(Request $request, int $id)
    {
        $member = Member::findOrFail($id);

        // Jika klaim anggota lama, set registered_at mundur ke tahun klaim
        // Jika anggota baru, set registered_at ke sekarang (jika belum ada)
        if ($member->claims_old_member && $member->claimed_join_year) {
            $registeredAt = now()->setYear((int) $member->claimed_join_year);
        } else {
            $registeredAt = $member->registered_at ?? now();
        }

        $member->update([
            'biodata_status'    => 'verified',
            'status'            => $member->status === 'active' ? 'active' : 'pending',
            'registered_at'     => $registeredAt,
            // Jika klaim lama, pastikan registration_type ikut ter-set ke 'lama'
            'registration_type' => $member->claims_old_member ? 'lama' : $member->registration_type,
        ]);

        MemberVerificationLog::create([
            'member_id'   => $member->id,
            'verified_by' => auth()->id(),
            'action'      => $member->claims_old_member ? 'approve_old_member' : 'approve_biodata',
            'note'        => $member->claims_old_member
                ? 'Dikonfirmasi sebagai anggota lama sejak ' . $member->claimed_join_year
                : $request->note,
        ]);

        // Kirim email notifikasi (hanya untuk anggota baru)
        if (! $member->claims_old_member) {
            try {
                Mail::send('emails.biodata-approved', ['member' => $member], function ($m) use ($member) {
                    $m->to($member->email)->subject('Biodata Anda Telah Diverifikasi — ASPAPI');
                });
            } catch (\Exception $e) {
                Log::warning('Mail biodata-approved gagal: ' . $e->getMessage());
            }
        }

        return back()->with('success', 'Biodata anggota berhasil diverifikasi.');
    }

    public function reject(Request $request, int $id)
    {
        $request->validate(['reason' => 'required|string']);

        $member = Member::findOrFail($id);
        $member->update([
            'biodata_status'        => 'rejected',
            'biodata_reject_reason' => $request->reason,
        ]);

        MemberVerificationLog::create([
            'member_id'   => $member->id,
            'verified_by' => auth()->id(),
            'action'      => 'reject_biodata',
            'note'        => $request->reason,
        ]);

        try {
            Mail::send('emails.biodata-rejected', ['member' => $member, 'reason' => $request->reason], function ($m) use ($member) {
                $m->to($member->email)->subject('Biodata Anda Perlu Diperbaiki — ASPAPI');
            });
        } catch (\Exception $e) {
            Log::warning('Mail biodata-rejected gagal: ' . $e->getMessage());
        }

        return back()->with('success', 'Biodata anggota ditolak dan notifikasi telah dikirim.');
    }
}