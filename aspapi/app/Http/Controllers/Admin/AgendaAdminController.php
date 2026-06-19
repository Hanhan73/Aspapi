<?php

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