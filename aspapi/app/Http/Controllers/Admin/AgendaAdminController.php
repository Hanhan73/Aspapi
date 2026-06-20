<?php
// app/Http/Controllers/Admin/AgendaAdminController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agenda;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AgendaAdminController extends Controller
{
    public function index(Request $request)
    {
        $regions      = Region::where('is_active', true)->orderBy('name')->get();
        $pendingCount = Agenda::where('status', 'pending')->count();

        $agendas = Agenda::with('region')
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->region, fn($q) => $q->where('region_id', $request->region))
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        return view('admin.agenda.index', compact('agendas', 'regions', 'pendingCount'));
    }

    public function create()
    {
        $regions = Region::where('is_active', true)->orderBy('name')->get();
        return view('admin.agenda.form', compact('regions'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'event_date'  => 'required|date',
            'description' => 'nullable|string',
            'photo'       => 'nullable|image|max:2048',
            'region_id'   => 'nullable|exists:regions,id',
        ]);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('agendas', 'public');
        }

        // Pastikan region_id null (bukan string kosong)
        $data['region_id'] = $request->filled('region_id') ? $request->region_id : null;
        $data['status']    = 'approved';

        Agenda::create($data);

        return redirect()->route('admin.agenda.index')
            ->with('success', 'Agenda berhasil ditambahkan dan langsung ditampilkan di halaman publik.');
    }

    public function edit(Agenda $agenda)
    {
        $regions = Region::where('is_active', true)->orderBy('name')->get();
        return view('admin.agenda.form', compact('agenda', 'regions'));
    }

    public function update(Request $request, Agenda $agenda)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'event_date'  => 'required|date',
            'description' => 'nullable|string',
            'photo'       => 'nullable|image|max:2048',
            'region_id'   => 'nullable|exists:regions,id',
        ]);

        if ($request->hasFile('photo')) {
            if ($agenda->photo) Storage::disk('public')->delete($agenda->photo);
            $data['photo'] = $request->file('photo')->store('agendas', 'public');
        } elseif ($request->boolean('remove_photo') && $agenda->photo) {
            Storage::disk('public')->delete($agenda->photo);
            $data['photo'] = null;
        }

        $agenda->update($data);

        return redirect()->route('admin.agenda.index')
            ->with('success', 'Agenda berhasil diperbarui.');
    }

    public function approve(Agenda $agenda)
    {
        $agenda->update(['status' => 'approved', 'reject_reason' => null]);
        return back()->with('success', 'Agenda "' . $agenda->title . '" disetujui dan tampil di halaman publik.');
    }

    public function reject(Request $request, Agenda $agenda)
    {
        $request->validate(['reject_reason' => 'required|string|max:500']);
        $agenda->update([
            'status'        => 'rejected',
            'reject_reason' => $request->reject_reason,
        ]);
        return back()->with('success', 'Agenda ditolak.');
    }

    public function destroy(Agenda $agenda)
    {
        if ($agenda->photo) Storage::disk('public')->delete($agenda->photo);
        $agenda->delete();
        return back()->with('success', 'Agenda dihapus.');
    }
}