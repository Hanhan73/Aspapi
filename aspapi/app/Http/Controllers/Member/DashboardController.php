<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $member   = auth()->user()->member;
        $payments = $member?->payments()->latest()->get();

        return view('member.dashboard', compact('member', 'payments'));
    }
}