<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    // ── Form anggota BARU ─────────────────────────────────────────────────────

    public function showForm()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|min:8|confirmed',
        ]);

        // Token disimpan di kolom tersendiri, BUKAN remember_token
        $token = Str::random(64);

        $user = User::create([
            'name'                => $request->full_name,
            'email'               => $request->email,
            'password'            => Hash::make($request->password),
            'role'                => 'anggota',
            'email_verified'      => false,
            'email_verify_token'  => $token,   // ← kolom baru
        ]);

        Member::create([
            'user_id'           => $user->id,
            'full_name'         => $request->full_name,
            'email'             => $request->email,
            'registration_type' => 'baru',
            'status'            => 'pending',
            'biodata_status'    => 'draft',
        ]);

        // Kirim email verifikasi
        $verifyUrl = route('verify.email', ['token' => $token]);

        Mail::send(
            'emails.verify',
            ['verifyUrl' => $verifyUrl, 'name' => $request->full_name],
            function ($m) use ($request) {
                $m->to($request->email)->subject('Verifikasi Email — ASPAPI');
            }
        );

        return redirect()->route('login')
            ->with('success', 'Pendaftaran berhasil! Cek email Anda untuk verifikasi akun.');
    }

    // ── Form anggota LAMA ─────────────────────────────────────────────────────

    public function showOldForm()
    {
        return view('auth.register-old');
    }

    public function storeOld(Request $request)
    {
        $request->validate([
            'full_name'         => 'required|string|max:255',
            'email'             => 'required|email|unique:users,email',
            'password'          => 'required|min:8|confirmed',
            'claimed_join_year' => 'required|integer|min:2010|max:' . now()->year,
        ]);

        $user = User::create([
            'name'           => $request->full_name,
            'email'          => $request->email,
            'password'       => Hash::make($request->password),
            'role'           => 'anggota',
            'email_verified' => true, // anggota lama tidak perlu verif email
        ]);

        Member::create([
            'user_id'            => $user->id,
            'full_name'          => $request->full_name,
            'email'              => $request->email,
            'registration_type'  => 'lama',
            'claims_old_member'  => true,
            'claimed_join_year'  => $request->claimed_join_year,
            'status'             => 'pending',
            'biodata_status'     => 'draft',
        ]);

        // Notif admin
        Mail::send(
            'emails.notify-admin-old-member',
            ['name' => $request->full_name, 'year' => $request->claimed_join_year],
            function ($m) {
                $m->to(config('mail.admin_email', 'admin@aspapi.or.id'))
                  ->subject('Klaim Anggota Lama Baru — ASPAPI');
            }
        );

        return redirect()->route('login')
            ->with('success', 'Pendaftaran anggota lama berhasil! Tunggu verifikasi dari Admin ASPAPI.');
    }

    // ── Verifikasi Email ──────────────────────────────────────────────────────

    public function verifyEmail(string $token)
    {
        // Cari dari kolom email_verify_token, bukan remember_token
        $user = User::where('email_verify_token', $token)->first();

        if (! $user) {
            return redirect()->route('login')
                ->with('error', 'Link verifikasi tidak valid atau sudah pernah digunakan.');
        }

        $user->update([
            'email_verified'     => true,
            'email_verified_at'  => now(),
            'email_verify_token' => null, // hapus token setelah dipakai
        ]);

        return redirect()->route('login')
            ->with('success', 'Email berhasil diverifikasi! Silakan login dan lengkapi biodata Anda.');
    }
}