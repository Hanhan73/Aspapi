<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Region;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MembersExport;
use Illuminate\Http\Request;

class MemberAdminController extends Controller
{
    public function index(Request $request)
    {
        $members = Member::with(['user', 'provinceModel', 'cityModel', 'registeredByRegion'])
            ->when($request->q, fn($q) => $q->where(function ($sub) use ($request) {
                $sub->where('full_name', 'like', '%'.$request->q.'%')
                    ->orWhere('email', 'like', '%'.$request->q.'%')
                    ->orWhere('member_number', 'like', '%'.$request->q.'%');
            }))
            ->when($request->status,    fn($q) => $q->where('status', $request->status))
            ->when($request->type,      fn($q) => $q->where('registration_type', $request->type))
            ->when($request->biodata,   fn($q) => $q->where('biodata_status', $request->biodata))
            ->when($request->region_id && $request->region_id !== 'none',
                fn($q) => $q->where('registered_by_region_id', $request->region_id))
            ->when($request->region_id === 'none',
                fn($q) => $q->whereNull('registered_by_region_id'))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $regions = Region::orderBy('province')->orderBy('name')->get();

        return view('admin.members.index', compact('members', 'regions'));
    }
    public function show(Member $member)
    {
        $member->load(['user', 'provinceModel', 'cityModel', 'payments', 'registeredByRegion']);

        // Semua daerah untuk dropdown, diurutkan provinsi lalu nama
        $regions = Region::orderBy('province')->orderBy('name')->get();

        // Auto-suggest: daerah yang provinsinya sama dengan member
        $suggestedRegions = $member->province_id
            ? $regions->where('province_id', $member->province_id)
            : collect();

        return view('admin.members.show', compact('member', 'regions', 'suggestedRegions'));
    }

    public function destroy(Member $member)
    {
        $member->delete();
        return redirect()->route('admin.members.index')
            ->with('success', 'Anggota berhasil dihapus.');
    }

    /**
     * Assign atau ubah ASPAPI Daerah untuk seorang anggota.
     */
    public function assignRegion(Request $request, Member $member)
    {
        $request->validate([
            'region_id' => 'nullable|exists:regions,id',
        ]);

        $regionId = $request->region_id ?: null;

        $member->update([
            'registered_by_region_id' => $regionId,
        ]);

        $message = $regionId
            ? 'Anggota berhasil di-assign ke ' . Region::find($regionId)->name . '.'
            : 'Anggota berhasil dilepas dari ASPAPI Daerah.';

        return back()->with('success', $message);
    }

    /**
     * Ubah tipe registrasi anggota dari "baru" menjadi "lama".
     *
     * Kasus: anggota sebenarnya anggota lama tapi mendaftar lewat form anggota baru,
     * lalu hanya membayar iuran tahunan (Rp 120.000). Karena tercatat "baru",
     * sistem menuntut uang pangkal sehingga kartu tidak bisa digenerate.
     * Dengan diubah ke "lama", syarat kartu cukup iuran tahunan aktif —
     * pembayaran yang sudah diverifikasi tetap dipakai, tidak perlu daftar/bayar ulang.
     */
    public function convertToOldMember(Request $request, Member $member)
    {
        if ($member->registration_type === 'lama') {
            return back()->with('error', 'Anggota ini sudah tercatat sebagai anggota lama.');
        }

        $data = $request->validate([
            'claimed_join_year' => 'nullable|integer|min:1980|max:' . now()->year,
        ]);

        $year = $data['claimed_join_year'] ?? null;

        $updates = [
            'registration_type' => 'lama',
            'claims_old_member' => true,
        ];

        if ($year) {
            $updates['claimed_join_year'] = $year;
            $updates['registered_at']     = now()->setYear((int) $year);
        }

        $member->update($updates);

        \App\Models\MemberVerificationLog::create([
            'member_id'   => $member->id,
            'verified_by' => auth()->id(),
            'action'      => 'approve_old_member',
            'note'        => 'Diubah admin dari anggota baru menjadi anggota lama'
                . ($year ? ' (bergabung sejak ' . $year . ')' : ''),
        ]);

        $message = 'Anggota berhasil diubah menjadi anggota lama.';
        $message .= $member->fresh()->canGenerateCard()
            ? ' Anggota sekarang sudah bisa generate kartu.'
            : ' Namun kartu belum bisa digenerate: pastikan biodata terverifikasi dan iuran tahunan masih aktif.';

        return back()->with('success', $message);
    }

    public function export(Request $request)
{
    $filename = 'anggota-aspapi-' . now()->format('Ymd-His') . '.xlsx';
    return Excel::download(new MembersExport($request->all()), $filename);
}
}