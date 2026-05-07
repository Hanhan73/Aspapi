<?php
// app/Http/Middleware/RegionMiddleware.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RegionMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || auth()->user()->role !== 'aspapi_daerah') {
            abort(403, 'Akses khusus ASPAPI Daerah.');
        }
        return $next($request);
    }
}