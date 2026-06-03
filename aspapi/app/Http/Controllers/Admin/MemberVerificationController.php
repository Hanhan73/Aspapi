<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\MemberVerificationLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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

        $members        = $query->paginate(20);
        $pendingCount   = Member::where('biodata_status', 'pending')->count();
        $oldClaimCount  = Member::where('claims_old_member', true)->where('biodata_status', 'pending')->count();

        return view('admin.members.verify', compact('members', 'pendingCount', 'oldClaimCount'));
    }

    public function approve(Request $request, int $id)
    {
        $member = Member::findOrFail($id);
        $member->update([
            'biodata_status' => 'verified',
            'status'         => $member->status === 'active' ? 'active' : 'pending',
            'registered_at'  => $member->registered_at ?? now(),
        ]);

        MemberVerificationLog::create([
            'member_id'   => $member->id,
            'verified_by' => auth()->id(),
            'action'      => 'approve_biodata',
            'note'        => $request->note,
        ]);

        try {
            Mail::send('emails.biodata-approved', ['member' => $member], function ($m) use ($member) {
                $m->to($member->email)->subject('Biodata Anda Telah Diverifikasi — ASPAPI');
            });
        } catch (\Exception $e) {
            \Log::error('Mail gagal: ' . $e->getMessage());
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
            \Log::error('Mail gagal: ' . $e->getMessage());
        }

        return back()->with('success', 'Biodata anggota ditolak dan notifikasi telah dikirim.');
    }

    public function approveOldMember(Request $request, int $id)
    {
        $member = Member::findOrFail($id);
        $member->update([
            'biodata_status'    => 'verified',
            'status'            => 'pending',
            'registration_type' => 'lama',
            'registered_at'     => now()->setYear((int) $member->claimed_join_year), // fix cast ke int
        ]);

        MemberVerificationLog::create([
            'member_id'   => $member->id,
            'verified_by' => auth()->id(),
            'action'      => 'approve_old_member',
            'note'        => 'Dikonfirmasi sebagai anggota lama sejak ' . $member->claimed_join_year,
        ]);

        return back()->with('success', 'Klaim anggota lama berhasil dikonfirmasi.');
    }
}
