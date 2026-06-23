<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImpersonateController extends Controller
{
    public function impersonate(int $userId)
    {
        $actor = auth()->user();

        // Yang boleh impersonate: superadmin dan admin (dan daerah untuk anggotanya)
        if (!in_array($actor->role, ['superadmin', 'admin', 'aspapi_daerah'])) {
            abort(403);
        }

        $target = User::findOrFail($userId);

        // Jangan impersonate diri sendiri
        if ($target->id === $actor->id) {
            return back()->with('error', 'Tidak bisa masuk sebagai diri sendiri.');
        }

        // Jangan impersonate superadmin lain
        if ($target->role === 'superadmin') {
            return back()->with('error', 'Tidak bisa impersonate akun superadmin.');
        }

        // Admin & daerah hanya boleh impersonate anggota
        // Superadmin bisa impersonate semua role
        if ($actor->role !== 'superadmin' && $target->role !== 'anggota') {
            return back()->with('error', 'Hanya bisa masuk sebagai anggota biasa.');
        }

        // Simpan ID & role asli di session
        session(['impersonator_id'   => $actor->id]);
        session(['impersonator_role' => $actor->role]);

        Auth::login($target);

        // Redirect ke dashboard sesuai role target
        $redirect = match($target->role) {
            'admin'         => redirect()->route('admin.dashboard'),
            'bendahara'     => redirect()->route('bendahara.dashboard'),
            'aspapi_daerah' => redirect()->route('daerah.dashboard'),
            default         => redirect()->route('member.dashboard'),
        };

        return $redirect->with('info', 'Anda sedang masuk sebagai ' . $target->name . ' (' . $target->role . ').');
    }

    public function leave()
    {
        $impersonatorId   = session('impersonator_id');
        $impersonatorRole = session('impersonator_role');

        if (!$impersonatorId) {
            return redirect()->route('login');
        }

        $original = User::findOrFail($impersonatorId);

        session()->forget(['impersonator_id', 'impersonator_role']);

        Auth::login($original);

        return match($impersonatorRole) {
            'superadmin', 'admin' => redirect()->route('admin.dashboard')
                                         ->with('success', 'Anda kembali ke akun ' . $original->name . '.'),
            'aspapi_daerah'       => redirect()->route('daerah.dashboard')
                                         ->with('success', 'Anda kembali ke akun daerah.'),
            default               => redirect()->route('admin.dashboard'),
        };
    }
}