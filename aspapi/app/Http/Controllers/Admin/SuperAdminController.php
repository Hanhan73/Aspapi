<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class SuperAdminController extends Controller
{
    public function index()
    {
        $users = User::whereIn('role', ['admin', 'bendahara', 'aspapi_daerah'])
            ->with('region')
            ->orderByRaw("FIELD(role, 'admin', 'bendahara', 'aspapi_daerah')")
            ->orderBy('name')
            ->get();

        return view('admin.superadmin.users', compact('users'));
    }

    public function create()
    {
        $regions = Region::orderBy('province')->get();
        return view('admin.superadmin.create', compact('regions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'role'     => ['required', Rule::in(['admin', 'bendahara', 'aspapi_daerah'])],
            'region_id'=> 'nullable|exists:regions,id',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name'           => $request->name,
            'email'          => $request->email,
            'role'           => $request->role,
            'region_id'      => $request->role === 'aspapi_daerah' ? $request->region_id : null,
            'password'       => Hash::make($request->password),
            'email_verified' => true,
        ]);

        return redirect()->route('admin.superadmin.users')
            ->with('success', 'Akun ' . $request->name . ' berhasil dibuat.');
    }

    public function edit(User $user)
    {
        abort_if($user->role === 'superadmin', 403);
        $regions = Region::orderBy('province')->get();
        return view('admin.superadmin.edit', compact('user', 'regions'));
    }

    public function update(Request $request, User $user)
    {
        abort_if($user->role === 'superadmin', 403);

        $validated = $request->validate([
            'name'               => 'required|string|max:255',
            'email'              => 'required|email|unique:users,email,' . $user->id,
            'notification_email' => 'nullable|email|max:255',
            'role'               => 'required|in:admin,bendahara,aspapi_daerah,anggota,superadmin',
            'password'           => 'nullable|min:8|confirmed',
        ]);

        $user->update([
            'name'      => $request->name,
            'email'     => $request->email,
            'notification_email' => $request->notification_email,
            'role'      => $request->role,
            'region_id' => $request->role === 'aspapi_daerah' ? $request->region_id : null,
        ]);

        return redirect()->route('admin.superadmin.users')
            ->with('success', 'Akun ' . $user->name . ' berhasil diperbarui.');
    }

    public function resetPassword(User $user)
    {
        abort_if($user->role === 'superadmin', 403);

        $newPassword = Str::random(10);
        $user->update(['password' => Hash::make($newPassword)]);

        return back()->with('success', 'Password ' . $user->name . ' direset. Password baru: <strong>' . $newPassword . '</strong>');
    }

    public function destroy(User $user)
    {
        abort_if($user->role === 'superadmin', 403);
        abort_if($user->id === auth()->id(), 403, 'Tidak bisa hapus akun sendiri.');

        $name = $user->name;
        $user->delete();

        return redirect()->route('admin.superadmin.users')
            ->with('success', 'Akun ' . $name . ' berhasil dihapus.');
    }
}