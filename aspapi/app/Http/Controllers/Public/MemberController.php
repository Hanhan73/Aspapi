<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    /**
     * Halaman Jenis & Syarat Anggota
     */
    public function types()
    {
        return view('public.members.types');
    }

    /**
     * Halaman Registrasi & Iuran Anggota
     */
    public function registerForm()
    {
        return view('public.members.register');
    }

    public function registerStore(Request $request)
    {
        // redirect ke portal member untuk daftar
        return redirect()->away('https://member.aspapi.id/register');
    }
}