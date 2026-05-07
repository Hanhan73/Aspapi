<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;

class MemberAdminController extends Controller
{
    public function index(Request $request)
    {
        $members = Member::with(['user', 'provinceModel', 'cityModel'])
            ->when($request->q, fn($q) => $q->where(function ($sub) use ($request) {
                $sub->where('full_name', 'like', '%'.$request->q.'%')
                    ->orWhere('email', 'like', '%'.$request->q.'%')
                    ->orWhere('member_number', 'like', '%'.$request->q.'%');
            }))
            ->when($request->status,  fn($q) => $q->where('status', $request->status))
            ->when($request->type,    fn($q) => $q->where('registration_type', $request->type))
            ->when($request->biodata, fn($q) => $q->where('biodata_status', $request->biodata))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.members.index', compact('members'));
    }

    public function show(Member $member)
    {
        $member->load(['user', 'provinceModel', 'cityModel', 'payments']);
        return view('admin.members.show', compact('member'));
    }

    public function destroy(Member $member)
    {
        $member->delete();
        return redirect()->route('admin.members.index')
            ->with('success', 'Anggota berhasil dihapus.');
    }
}