<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImpersonateController extends Controller
{
    /**
     * Admin/daerah masuk sebagai member.
     * Hanya bisa impersonate user dengan role 'anggota'.
     */
    public function impersonate(int $userId)
    {
        $actor = auth()->user();

        // Hanya admin dan aspapi_daerah yang boleh
        if (!in_array($actor->role, ['admin', 'aspapi_daerah'])) {
            abort(403);
        }

        $target = User::findOrFail($userId);

        // Hanya boleh impersonate anggota biasa
        if ($target->role !== 'anggota') {
            return back()->with('error', 'Hanya bisa masuk sebagai anggota biasa.');
        }

        // Simpan ID admin asli di session sebelum switch
        session(['impersonator_id'   => $actor->id]);
        session(['impersonator_role' => $actor->role]);

        Auth::login($target);

        return redirect()->route('member.dashboard')
            ->with('info', 'Anda sedang masuk sebagai ' . $target->name . '.');
    }

    /**
     * Keluar dari mode impersonate, kembali ke akun asli.
     */
    public function leave()
    {
        $impersonatorId   = session('impersonator_id');
        $impersonatorRole = session('impersonator_role');

        if (!$impersonatorId) {
            return redirect()->route('login');
        }

        $original = User::findOrFail($impersonatorId);

        // Bersihkan session impersonate
        session()->forget(['impersonator_id', 'impersonator_role']);

        Auth::login($original);

        // Redirect ke dashboard asal sesuai role
        return match($impersonatorRole) {
            'admin'         => redirect()->route('admin.dashboard')
                                   ->with('success', 'Anda kembali ke akun admin.'),
            'aspapi_daerah' => redirect()->route('daerah.dashboard')
                                   ->with('success', 'Anda kembali ke akun daerah.'),
            default         => redirect()->route('admin.dashboard'),
        };
    }
}