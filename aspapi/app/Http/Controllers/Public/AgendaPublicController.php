<?php
// app/Http/Controllers/Public/AgendaPublicController.php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Agenda;
use App\Models\Region;
use Illuminate\Http\Request;

class AgendaPublicController extends Controller
{
    public function index(Request $request)
    {
        $regions = Region::where('is_active', true)->orderBy('name')->get();

        $agendas = Agenda::approved()
            ->with('region')
            ->when($request->search, fn($q) => $q->where('title', 'like', '%' . $request->search . '%'))
            ->when($request->region, function ($q) use ($request) {
                if ($request->region === 'pusat') {
                    // Filter agenda ASPAPI Pusat (region_id null)
                    $q->whereNull('region_id');
                } else {
                    $q->where('region_id', $request->region);
                }
            })
            ->orderBy('event_date', 'desc')
            ->paginate(12)
            ->withQueryString();

        return view('public.agenda.index', compact('agendas', 'regions'));
    }
}