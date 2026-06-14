<?php
 
namespace App\Http\Controllers\Member;
 
use App\Http\Controllers\Controller;
use App\Models\Province;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
 
class BiodataController extends Controller
{
    public function edit()
    {
        $member    = auth()->user()->member;
        $provinces = Province::orderBy('name')->get();
        $cities    = $member?->province_id
            ? City::where('province_id', $member->province_id)->orderBy('name')->get()
            : collect();
 
        return view('member.biodata', compact('member', 'provinces', 'cities'));
    }
 
    public function update(Request $request)
    {
        $member = auth()->user()->member;
        $isImpersonating = session()->has('impersonator_id');

        // Guard: tidak boleh update kalau terkunci — KECUALI sedang diimpersonate
        if (!$isImpersonating && in_array($member->biodata_status, ['pending', 'verified'])) {
            return back()->with('error', 'Biodata terkunci. Klik "Buka Kunci" terlebih dahulu.');
        }
 
        $validated = $request->validate([
            'full_name'      => 'required|string|max:255',
            'front_title'    => 'nullable|string|max:50',
            'back_title'     => 'nullable|string|max:100',
            'nik'            => 'required|digits:16',
            'birth_place'    => 'required|string|max:100',
            'birth_date'     => 'required|date|before:today',
            'phone'          => 'required|string|max:20',
            'email'          => 'required|email|max:255',
            'gender'         => 'required|in:L,P',
            'last_education' => 'required|in:sd,smp,sma,d3,s1,s2,s3,profesi,lainnya',
            'province_id'    => 'required|exists:provinces,id',
            'city_id'        => 'required|exists:cities,id',
            'address'        => 'required|string|max:500',
            'occupation'     => 'nullable|string|max:150',
            'institution'    => 'nullable|string|max:255',
            'position'       => 'nullable|string|max:150',
            'photo'          => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);
 
        if ($request->hasFile('photo')) {
            if ($member->photo) {
                Storage::disk('public')->delete($member->photo);
            }
            $validated['photo'] = $request->file('photo')->store('member-photos', 'public');
        }

        // Cek apakah ini verifikasi ulang (pernah submit sebelumnya)
        $isResubmit = $member->registered_at !== null;

        if ($isImpersonating) {
            // Admin yang edit → langsung verified, tidak perlu antri ke admin lagi
            $validated['biodata_status']        = 'verified';
            $validated['biodata_reject_reason'] = null;
        } else {
            // Member sendiri yang edit → masuk antrian verifikasi seperti biasa
            $validated['biodata_status']        = 'pending';
            $validated['biodata_reject_reason'] = null;
        }
 
        $member->update($validated);

        // Kirim notif ke admin (hanya jika bukan mode impersonate)
        if (!$isImpersonating) {
            try {
                Mail::send(
                    'emails.notify-admin-biodata-submitted',
                    [
                        'member'      => $member->fresh(),
                        'isResubmit'  => $isResubmit,
                        'submittedAt' => now()->setTimezone('Asia/Jakarta')->format('d M Y, H:i') . ' WIB',
                        'adminUrl'    => route('admin.members.verify'),
                    ],
                    function ($m) use ($isResubmit) {
                        $subject = $isResubmit
                            ? 'Verifikasi Ulang Biodata Anggota — ASPAPI'
                            : 'Pengajuan Biodata Baru — ASPAPI';
                        $m->to(config('mail.admin_email'))->subject($subject);
                    }
                );
            } catch (\Exception $e) {
                \Log::warning('Gagal kirim notif admin (biodata submit): ' . $e->getMessage());
            }
        }
 
        $msg = $isImpersonating
            ? 'Biodata berhasil diperbarui dan langsung diverifikasi (mode admin).'
            : 'Biodata berhasil disimpan dan diajukan ke Admin untuk diverifikasi.';

        return redirect()->route('member.biodata')->with('success', $msg);
    }
 
    /**
     * Buka kunci biodata — set status ke 'draft' agar bisa diedit.
     * Hanya bisa dilakukan dari status 'pending' atau 'verified'.
     */
    public function unlock(Request $request)
    {
        $member = auth()->user()->member;
        $isImpersonating = session()->has('impersonator_id');

        if (!$isImpersonating && !in_array($member->biodata_status, ['pending', 'verified'])) {
            return back()->with('error', 'Biodata tidak dalam kondisi terkunci.');
        }

        $member->update([
            'biodata_status'        => 'draft',
            'biodata_reject_reason' => null,
        ]);

        return redirect()->route('member.biodata')->with('success', 'Biodata berhasil dibuka.');
    }
}