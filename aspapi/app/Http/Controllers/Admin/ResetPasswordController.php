<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    /**
     * Reset password anggota oleh admin.
     * Generate password baru → simpan → kirim email ke anggota.
     *
     * Route: POST /admin/anggota/{member}/reset-password
     * Name:  admin.member.reset-password
     */
    public function reset(Request $request, int $memberId)
    {
        $member = Member::with('user')->findOrFail($memberId);

        if (!$member->user) {
            return back()->with('error', 'Anggota ini tidak memiliki akun login.');
        }

        // Generate password baru: 10 karakter alfanumerik
        $newPassword = Str::random(10);

        // Simpan ke tabel users
        $member->user->update([
            'password' => Hash::make($newPassword),
        ]);

        // Kirim email berisi password baru
        try {
            Mail::send(
                'emails.password-reset',
                ['member' => $member, 'newPassword' => $newPassword],
                function ($m) use ($member) {
                    $m->to($member->user->email)
                      ->subject('Password Anda Telah Direset — ASPAPI');
                }
            );
            $emailStatus = 'dan email telah dikirim ke ' . $member->user->email;
        } catch (\Exception $e) {
            \Log::error('Email reset password gagal: ' . $e->getMessage());
            $emailStatus = '(email gagal dikirim, catat password berikut: ' . $newPassword . ')';
        }

        return back()->with('success', "Password anggota {$member->full_name} berhasil direset {$emailStatus}.");
    }

    /**
     * Reset password dengan password custom yang diinput admin.
     * Opsional — gunakan jika butuh set password tertentu.
     *
     * Route: POST /admin/anggota/{member}/set-password
     * Name:  admin.member.set-password
     */
    public function setPassword(Request $request, int $memberId)
    {
        $request->validate([
            'new_password'              => 'required|string|min:8|confirmed',
            'new_password_confirmation' => 'required',
        ]);

        $member = Member::with('user')->findOrFail($memberId);

        if (!$member->user) {
            return back()->with('error', 'Anggota ini tidak memiliki akun login.');
        }

        $member->user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return back()->with('success', "Password anggota {$member->full_name} berhasil diubah.");
    }
}