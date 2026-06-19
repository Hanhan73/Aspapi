<?php

namespace App\Http\Controllers\Daerah;
 
use App\Http\Controllers\Controller;
use App\Models\Agenda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
 
class AgendaController extends Controller
{
    private function getRegion()
    {
        return auth()->user()->region;
    }
 
    public function index()
    {
        $region  = $this->getRegion();
        $agendas = Agenda::where('region_id', $region->id)
            ->orderBy('event_date', 'desc')
            ->paginate(15);
 
        return view('daerah.agenda.index', compact('agendas'));
    }
 
    public function create()
    {
        return view('daerah.agenda.form');
    }
 
    public function store(Request $request)
    {
        $region = $this->getRegion();
 
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'event_date'  => 'required|date',
            'description' => 'nullable|string',
            'photo'       => 'nullable|image|max:2048',
        ]);
 
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('agendas', 'public');
        }
 
        $data['region_id'] = $region->id;
        $data['status']    = 'pending';
 
        Agenda::create($data);
 
        return redirect()->route('daerah.agenda.index')
            ->with('success', 'Agenda berhasil dikirim dan menunggu persetujuan admin.');
    }
 
    public function edit(Agenda $agenda)
    {
        abort_if($agenda->region_id !== $this->getRegion()->id, 403);
        abort_if($agenda->status === 'approved', 403, 'Agenda yang sudah disetujui tidak dapat diedit.');
 
        return view('daerah.agenda.form', compact('agenda'));
    }
 
    public function update(Request $request, Agenda $agenda)
    {
        abort_if($agenda->region_id !== $this->getRegion()->id, 403);
        abort_if($agenda->status === 'approved', 403);
 
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'event_date'  => 'required|date',
            'description' => 'nullable|string',
            'photo'       => 'nullable|image|max:2048',
        ]);
 
        if ($request->hasFile('photo')) {
            if ($agenda->photo) Storage::disk('public')->delete($agenda->photo);
            $data['photo'] = $request->file('photo')->store('agendas', 'public');
        }
 
        // Jika diedit setelah ditolak, reset ke pending
        $data['status']        = 'pending';
        $data['reject_reason'] = null;
 
        $agenda->update($data);
 
        return redirect()->route('daerah.agenda.index')
            ->with('success', 'Agenda diperbarui dan dikirim ulang untuk disetujui.');
    }
 
    public function destroy(Agenda $agenda)
    {
        abort_if($agenda->region_id !== $this->getRegion()->id, 403);
        if ($agenda->photo) Storage::disk('public')->delete($agenda->photo);
        $agenda->delete();
 
        return redirect()->route('daerah.agenda.index')
            ->with('success', 'Agenda berhasil dihapus.');
    }
}