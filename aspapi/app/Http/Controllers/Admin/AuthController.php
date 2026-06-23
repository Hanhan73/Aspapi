<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();

            if (!in_array($user->role, ['superadmin', 'admin'])) {
                Auth::logout();
                return back()->withErrors(['email' => 'Akun Anda tidak memiliki akses admin.']);
            }

            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors(['email' => 'Email atau password salah.'])->onlyInput('email');
    }

    public function loginMember(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();

            // Cek verifikasi email untuk anggota baru mandiri
            if ($user->role === 'anggota' && !$user->email_verified) {
                Auth::logout();
                return back()->withErrors(['email' => 'Email Anda belum diverifikasi. Cek inbox email Anda.'])->onlyInput('email');
            }

            $request->session()->regenerate();

            return match($user->role) {
                'superadmin', 'admin' => redirect()->route('admin.dashboard'),
                'bendahara'           => redirect()->route('bendahara.dashboard'),
                'aspapi_daerah'       => redirect()->route('daerah.dashboard'),
                'anggota'             => redirect()->route('member.dashboard'),
                default               => redirect('/'),
            };
        }

        return back()->withErrors(['email' => 'Email atau password salah.'])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }
}