<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    // ── Form: minta link reset ───────────────────────────────────────────────

    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        // Pesan generik selalu sama, baik email ditemukan atau tidak,
        // supaya tidak bisa dipakai buat enumerasi email terdaftar.
        $genericMessage = 'Jika email terdaftar, link reset password sudah kami kirim. Silakan cek inbox / folder spam Anda.';

        if (! $user) {
            return back()->with('success', $genericMessage);
        }

        // Hapus token lama untuk email ini
        DB::table('password_reset_tokens')->where('email', $user->email)->delete();

        $plainToken = Str::random(64);

        DB::table('password_reset_tokens')->insert([
            'email'      => $user->email,
            'token'      => Hash::make($plainToken),
            'created_at' => now(),
        ]);

        $resetUrl = route('password.reset', [
            'token' => $plainToken,
            'email' => $user->email,
        ]);

        try {
            Mail::send(
                'emails.reset-password',
                ['resetUrl' => $resetUrl, 'name' => $user->name],
                function ($m) use ($user) {
                    $m->to($user->email)->subject('Reset Password — ASPAPI');
                }
            );
        } catch (\Exception $e) {
            Log::warning('Email reset password gagal: ' . $e->getMessage());
        }

        return back()->with('success', $genericMessage);
    }

    // ── Form: input password baru ────────────────────────────────────────────

    public function showResetForm(Request $request, string $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $record = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (! $record || ! Hash::check($request->token, $record->token)) {
            return back()->withErrors(['email' => 'Link reset password tidak valid atau sudah pernah digunakan.']);
        }

        // Token berlaku 60 menit
        if (now()->diffInMinutes($record->created_at) > 60) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();

            return back()->withErrors(['email' => 'Link reset password sudah kedaluwarsa. Silakan minta link baru.']);
        }

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return back()->withErrors(['email' => 'Akun tidak ditemukan.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login')
            ->with('success', 'Password berhasil diubah! Silakan login dengan password baru Anda.');
    }
}