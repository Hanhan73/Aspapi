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
    // Form anggota BARU
    public function showForm()
    {
        return view('auth.register');
    }

    // Simpan anggota baru — butuh verifikasi email
    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|min:8|confirmed',
        ]);

        $token = Str::random(64);

        $user = User::create([
            'name'           => $request->full_name,
            'email'          => $request->email,
            'password'       => Hash::make($request->password),
            'role'           => 'anggota',
            'email_verified' => false,
            'remember_token' => $token,
        ]);

        Member::create([
            'user_id'           => $user->id,
            'full_name'         => $request->full_name,
            'email'             => $request->email,
            'registration_type' => 'baru',
            'status'            => 'pending',
            'biodata_status'    => 'pending',
        ]);

        // Kirim email verifikasi
        Mail::send('emails.verify', ['token' => $token, 'name' => $request->full_name], function ($m) use ($request) {
            $m->to($request->email)->subject('Verifikasi Email — ASPAPI');
        });

        return redirect()->route('login')
            ->with('success', 'Pendaftaran berhasil! Cek email Anda untuk verifikasi akun.');
    }

    // Form anggota LAMA
    public function showOldForm()
    {
        return view('auth.register-old');
    }

    // Simpan anggota lama — langsung aktif, tunggu verifikasi admin
    public function storeOld(Request $request)
    {
        $request->validate([
            'full_name'        => 'required|string|max:255',
            'email'            => 'required|email|unique:users,email',
            'password'         => 'required|min:8|confirmed',
            'claimed_join_year'=> 'required|integer|min:2010|max:' . now()->year,
        ]);

        $user = User::create([
            'name'           => $request->full_name,
            'email'          => $request->email,
            'password'       => Hash::make($request->password),
            'role'           => 'anggota',
            'email_verified' => true, // langsung aktif tapi tunggu admin
        ]);

        Member::create([
            'user_id'            => $user->id,
            'full_name'          => $request->full_name,
            'email'              => $request->email,
            'registration_type'  => 'lama',
            'claims_old_member'  => true,
            'claimed_join_year'  => $request->claimed_join_year,
            'status'             => 'pending',
            'biodata_status'     => 'pending',
        ]);

        // Notif admin via email
        Mail::send('emails.notify-admin-old-member', ['name' => $request->full_name, 'year' => $request->claimed_join_year], function ($m) {
            $m->to(config('mail.admin_email', 'admin@aspapi.or.id'))
              ->subject('Klaim Anggota Lama Baru — ASPAPI');
        });

        return redirect()->route('login')
            ->with('success', 'Pendaftaran anggota lama berhasil! Tunggu verifikasi dari Admin ASPAPI.');
    }

    // Verifikasi email
    public function verifyEmail(string $token)
    {
        $user = User::where('remember_token', $token)->firstOrFail();
        $user->update([
            'email_verified'    => true,
            'email_verified_at' => now(),
            'remember_token'    => null,
        ]);

        return redirect()->route('login')
            ->with('success', 'Email berhasil diverifikasi! Silakan login.');
    }
}