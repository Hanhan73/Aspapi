<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AccountController extends Controller
{
    public function show()
    {
        return view('account.settings', [
            'user' => auth()->user(),
        ]);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|min:8|confirmed',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password berhasil diubah.');
    }

    public function updateEmail(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'email'            => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'current_password' => 'required',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password_email' => 'Password tidak sesuai.'])->with('tab', 'email');
        }

        $user->update(['email' => $request->email]);

        return back()->with('success_email', 'Email berhasil diubah.');
    }
}