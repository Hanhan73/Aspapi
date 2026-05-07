<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;

class LspController extends Controller
{
    public function index()
    {
        return view('public.lsp.index');
    }
}