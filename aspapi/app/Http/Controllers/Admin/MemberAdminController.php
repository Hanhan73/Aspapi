<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Region;
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
            ->when($request->region_id, fn($q) => $q->where('registered_by_region_id', $request->region_id))
            ->when($request->region_id === 'none', fn($q) => $q->whereNull('registered_by_region_id'))
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
}