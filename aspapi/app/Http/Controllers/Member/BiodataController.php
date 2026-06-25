<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Helpers\NotificationEmail;
use App\Models\Province;
use App\Models\City;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class BiodataController extends Controller
{
    public function edit()
    {
        $member    = auth()->user()->member;
        $provinces = Province::orderBy('name')->get();
        $regions   = Region::where('is_active', true)->orderBy('province')->get();
        $cities    = $member?->province_id
            ? City::where('province_id', $member->province_id)->orderBy('name')->get()
            : collect();

        return view('member.biodata', compact('member', 'provinces', 'cities', 'regions'));
    }

    public function update(Request $request)
    {
        $member          = auth()->user()->member;
        $isImpersonating = session()->has('impersonator_id');

        if (! $isImpersonating && in_array($member->biodata_status, ['pending', 'verified'])) {
            return back()->with('error', 'Biodata terkunci. Klik "Buka Kunci" terlebih dahulu.');
        }

        $validated = $request->validate([
            'full_name'               => 'required|string|max:255',
            'front_title'             => 'nullable|string|max:50',
            'back_title'              => 'nullable|string|max:100',
            'nik'                     => 'required|digits:16',
            'birth_place'             => 'required|string|max:100',
            'birth_date'              => 'required|date|before:today',
            'phone'                   => 'required|string|max:20',
            'email'                   => 'required|email|max:255',
            'gender'                  => 'required|in:L,P',
            'last_education'          => 'required|in:sd,smp,sma,d3,s1,s2,s3,profesi,lainnya',
            'province_id'             => 'required|exists:provinces,id',
            'city_id'                 => 'required|exists:cities,id',
            'address'                 => 'required|string|max:500',
            'occupation'              => 'nullable|string|max:150',
            'institution'             => 'nullable|string|max:255',
            'position'                => 'nullable|string|max:150',
            'photo'                   => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'registered_by_region_id' => 'nullable|exists:regions,id',
        ], [
            'full_name.required'      => 'Nama lengkap wajib diisi.',
            'full_name.max'           => 'Nama lengkap maksimal 255 karakter.',
            'front_title.max'         => 'Gelar depan maksimal 50 karakter.',
            'back_title.max'          => 'Gelar belakang maksimal 100 karakter.',
            'nik.required'            => 'NIK wajib diisi.',
            'nik.digits'              => 'NIK harus tepat 16 digit angka.',
            'birth_place.required'    => 'Tempat lahir wajib diisi.',
            'birth_place.max'         => 'Tempat lahir maksimal 100 karakter.',
            'birth_date.required'     => 'Tanggal lahir wajib diisi.',
            'birth_date.date'         => 'Format tanggal lahir tidak valid.',
            'birth_date.before'       => 'Tanggal lahir harus sebelum hari ini.',
            'phone.required'          => 'Nomor telepon wajib diisi.',
            'phone.max'               => 'Nomor telepon maksimal 20 karakter.',
            'email.required'          => 'Email wajib diisi.',
            'email.email'             => 'Format email tidak valid.',
            'email.max'               => 'Email maksimal 255 karakter.',
            'gender.required'         => 'Jenis kelamin wajib dipilih.',
            'gender.in'               => 'Jenis kelamin tidak valid.',
            'last_education.required' => 'Pendidikan terakhir wajib dipilih.',
            'last_education.in'       => 'Pilihan pendidikan tidak valid.',
            'province_id.required'    => 'Provinsi wajib dipilih.',
            'province_id.exists'      => 'Provinsi tidak ditemukan.',
            'city_id.required'        => 'Kota/Kabupaten wajib dipilih.',
            'city_id.exists'          => 'Kota/Kabupaten tidak ditemukan.',
            'address.required'        => 'Alamat lengkap wajib diisi.',
            'address.max'             => 'Alamat maksimal 500 karakter.',
            'occupation.max'          => 'Pekerjaan maksimal 150 karakter.',
            'institution.max'         => 'Institusi maksimal 255 karakter.',
            'position.max'            => 'Jabatan maksimal 150 karakter.',
            'photo.image'             => 'File foto harus berupa gambar.',
            'photo.mimes'             => 'Format foto harus JPG atau PNG.',
            'photo.max'               => 'Ukuran foto maksimal 2MB.',
            'registered_by_region_id.exists' => 'ASPAPI Daerah yang dipilih tidak valid.',
        ]);

        if ($request->hasFile('photo')) {
            if ($member->photo) {
                Storage::disk('public')->delete($member->photo);
            }
            $validated['photo'] = $request->file('photo')->store('member-photos', 'public');
        }

        $isResubmit = $member->registered_at !== null;

        $validated['biodata_status']        = $isImpersonating ? 'verified' : 'pending';
        $validated['biodata_reject_reason'] = null;

        if (! $member->registered_at) {
            $validated['registered_at'] = now();
        }

        $member->update($validated);
        $member->refresh();

        if (! $isImpersonating) {
            $submittedAt = now()->setTimezone('Asia/Jakarta')->format('d M Y, H:i') . ' WIB';

            // Notif ke admin pusat
            try {
                Mail::send(
                    'emails.notify-admin-biodata-submitted',
                    [
                        'member'      => $member,
                        'isResubmit'  => $isResubmit,
                        'submittedAt' => $submittedAt,
                        'adminUrl'    => route('admin.member.verify.index'),
                    ],
                    function ($m) use ($isResubmit) {
                        $subject = $isResubmit
                            ? 'Verifikasi Ulang Biodata Anggota — ASPAPI'
                            : 'Pengajuan Biodata Baru — ASPAPI';
                        $m->to(NotificationEmail::admin())->subject($subject);
                    }
                );
            } catch (\Exception $e) {
                Log::warning('Gagal kirim notif admin (biodata submit): ' . $e->getMessage());
            }

            // Notif ke admin daerah (jika anggota memilih region)
            if ($member->registered_by_region_id) {
                $daerahEmail = NotificationEmail::daerah($member->registered_by_region_id);
                $region      = Region::find($member->registered_by_region_id);

                if ($daerahEmail) {
                    try {
                        Mail::send(
                            'emails.notify-daerah-biodata-submitted',
                            [
                                'member'      => $member,
                                'region'      => $region,
                                'isResubmit'  => $isResubmit,
                                'submittedAt' => $submittedAt,
                                'daerahUrl'   => route('daerah.verify.index'),
                            ],
                            function ($m) use ($isResubmit, $region) {
                                $regionName = $region?->name ?? $region?->province ?? 'ASPAPI Daerah';
                                $subject    = $isResubmit
                                    ? "Verifikasi Ulang Biodata — {$regionName}"
                                    : "Pengajuan Biodata Baru — {$regionName}";
                                $m->to($daerahEmail)->subject($subject);
                            }
                        );
                    } catch (\Exception $e) {
                        Log::warning('Gagal kirim notif daerah (biodata submit): ' . $e->getMessage());
                    }
                }
            }
        }

        $msg = $isImpersonating
            ? 'Biodata berhasil diperbarui dan langsung diverifikasi (mode admin).'
            : 'Biodata berhasil disimpan dan diajukan ke Admin untuk diverifikasi.';

        return redirect()->route('member.biodata')->with('success', $msg);
    }

    public function unlock(Request $request)
    {
        $member          = auth()->user()->member;
        $isImpersonating = session()->has('impersonator_id');

        if (! $isImpersonating && ! in_array($member->biodata_status, ['pending', 'verified'])) {
            return back()->with('error', 'Biodata tidak dalam kondisi terkunci.');
        }

        $member->update([
            'biodata_status'        => 'draft',
            'biodata_reject_reason' => null,
        ]);

        return redirect()->route('member.biodata')->with('success', 'Biodata berhasil dibuka.');
    }
}