<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
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

        $token = Str::random(64);

        $user = User::create([
            'name'               => $request->full_name,
            'email'              => $request->email,
            'password'           => Hash::make($request->password),
            'role'               => 'anggota',
            'email_verified'     => false,
            'email_verify_token' => $token,
        ]);

        Member::create([
            'user_id'           => $user->id,
            'full_name'         => $request->full_name,
            'email'             => $request->email,
            'registration_type' => 'baru',
            'status'            => 'pending',
            'biodata_status'    => 'draft',
        ]);

        $verifyUrl = route('verify.email', ['token' => $token]);

        try {
            Mail::send(
                'emails.verify',
                ['verifyUrl' => $verifyUrl, 'name' => $request->full_name],
                function ($m) use ($request) {
                    $m->to($request->email)->subject('Verifikasi Email — ASPAPI');
                }
            );
        } catch (\Exception $e) {
            Log::warning('Email verifikasi gagal (baru): ' . $e->getMessage());
        }

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

        // Anggota lama sekarang juga wajib verifikasi email
        $token = Str::random(64);

        $user = User::create([
            'name'               => $request->full_name,
            'email'              => $request->email,
            'password'           => Hash::make($request->password),
            'role'               => 'anggota',
            'email_verified'     => false,
            'email_verify_token' => $token,
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

        $verifyUrl = route('verify.email', ['token' => $token]);

        // Kirim email verifikasi (sama seperti anggota baru)
        try {
            Mail::send(
                'emails.verify',
                ['verifyUrl' => $verifyUrl, 'name' => $request->full_name],
                function ($m) use ($request) {
                    $m->to($request->email)->subject('Verifikasi Email — ASPAPI');
                }
            );
        } catch (\Exception $e) {
            Log::warning('Email verifikasi gagal (lama): ' . $e->getMessage());
        }

        // Notif admin
        $adminUrl = route('admin.member.verify.index');
        try {
            Mail::send(
                'emails.notify-admin-old-member',
                [
                    'name'     => $request->full_name,
                    'email'    => $request->email,
                    'year'     => $request->claimed_join_year,
                    'adminUrl' => $adminUrl,
                ],
                function ($m) {
                    $m->to(config('mail.admin_email', 'admin@aspapi.or.id'))
                      ->subject('Klaim Anggota Lama Baru — ASPAPI');
                }
            );
        } catch (\Exception $e) {
            Log::warning('Notif admin old member gagal: ' . $e->getMessage());
        }

        return redirect()->route('login')
            ->with('success', 'Pendaftaran berhasil! Cek email Anda untuk verifikasi akun sebelum login.');
    }

    // ── Verifikasi Email ──────────────────────────────────────────────────────

    public function verifyEmail(string $token)
    {
        $user = User::where('email_verify_token', $token)->first();

        if (! $user) {
            return redirect()->route('login')
                ->with('error', 'Link verifikasi tidak valid atau sudah pernah digunakan.');
        }

        $user->update([
            'email_verified'     => true,
            'email_verified_at'  => now(),
            'email_verify_token' => null,
        ]);

        return redirect()->route('login')
            ->with('success', 'Email berhasil diverifikasi! Silakan login dan lengkapi biodata Anda.');
    }
}