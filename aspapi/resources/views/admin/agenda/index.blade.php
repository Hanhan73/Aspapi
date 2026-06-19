@extends('layouts.admin')
@section('title', 'Agenda Kegiatan')

@section('content')
<div class="p-6">

    <div class="flex items-start justify-between mb-6">
        <div>
            <h1 class="text-xl font-extrabold text-navy">Agenda Kegiatan Daerah</h1>
            <p class="text-sm text-neutral-500 mt-1">Agenda yang disetujui akan tampil di halaman publik.</p>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700 font-medium">{{ session('success') }}</div>
    @endif

    {{-- Filter --}}
    <div class="card p-4 mb-5 flex flex-wrap gap-3 items-center">
        <form method="GET" action="{{ route('admin.agenda.index') }}" class="flex gap-3 flex-1 flex-wrap">
            <select name="status" class="form-input text-sm" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="pending"  {{ request('status') === 'pending'  ? 'selected' : '' }}>Menunggu</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Disetujui</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
            </select>
            <select name="region" class="form-input text-sm" onchange="this.form.submit()">
                <option value="">Semua Daerah</option>
                @foreach ($regions as $region)
                    <option value="{{ $region->id }}" {{ request('region') == $region->id ? 'selected' : '' }}>{{ $region->name }}</option>
                @endforeach
            </select>
            @if (request('status') || request('region'))
            <a href="{{ route('admin.agenda.index') }}" class="btn border border-neutral-200 text-neutral-500 hover:bg-neutral-50 text-sm">Reset</a>
            @endif
        </form>

        {{-- Badge pending count --}}
        @if ($pendingCount > 0)
        <span class="inline-flex items-center gap-1.5 bg-amber-50 text-amber-700 border border-amber-200 text-xs font-bold px-3 py-1.5 rounded">
            <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
            {{ $pendingCount }} menunggu persetujuan
        </span>
        @endif
    </div>

    @if ($agendas->count())
    <div class="card overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-neutral-100 bg-neutral-50">
                    <th class="text-left px-5 py-3 text-2xs font-bold tracking-widest uppercase text-neutral-400">Kegiatan</th>
                    <th class="text-left px-5 py-3 text-2xs font-bold tracking-widest uppercase text-neutral-400 hidden md:table-cell">Tanggal</th>
                    <th class="text-left px-5 py-3 text-2xs font-bold tracking-widest uppercase text-neutral-400 hidden sm:table-cell">Daerah</th>
                    <th class="text-left px-5 py-3 text-2xs font-bold tracking-widest uppercase text-neutral-400">Status</th>
                    <th class="text-right px-5 py-3 text-2xs font-bold tracking-widest uppercase text-neutral-400">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-50">
                @foreach ($agendas as $agenda)
                <tr class="hover:bg-neutral-50/70 transition-colors">
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            @if ($agenda->photo)
                            <div class="w-10 h-10 rounded overflow-hidden flex-shrink-0">
                                <img src="{{ Storage::url($agenda->photo) }}" class="w-full h-full object-cover" alt="">
                            </div>
                            @else
                            <div class="w-10 h-10 rounded bg-primary-50 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-primary-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            @endif
                            <div>
                                <p class="font-semibold text-navy text-sm">{{ $agenda->title }}</p>
                                @if ($agenda->description)
                                <p class="text-xs text-neutral-400 mt-0.5 line-clamp-1">{{ $agenda->description }}</p>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-4 hidden md:table-cell">
                        <span class="text-sm text-neutral-500">{{ $agenda->event_date->translatedFormat('d M Y') }}</span>
                    </td>
                    <td class="px-5 py-4 hidden sm:table-cell">
                        <span class="text-sm text-neutral-500">{{ $agenda->region->name ?? '—' }}</span>
                    </td>
                    <td class="px-5 py-4">
                        @php
                            $statusConfig = [
                                'pending'  => ['background:#FEF3C7;color:#92400E;', 'Menunggu'],
                                'approved' => ['background:#D1FAE5;color:#065F46;', 'Disetujui'],
                                'rejected' => ['background:#FEE2E2;color:#991B1B;', 'Ditolak'],
                            ][$agenda->status] ?? ['background:#F3F4F6;color:#6B7280;', $agenda->status];
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-1 rounded text-2xs font-bold" style="{{ $statusConfig[0] }}">
                            {{ $statusConfig[1] }}
                        </span>
                    </td>
                    <td class="px-5 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            @if ($agenda->status === 'pending')
                            <form method="POST" action="{{ route('admin.agenda.approve', $agenda) }}">
                                @csrf
                                <button type="submit" class="text-2xs font-bold text-green-600 hover:text-green-700 transition-colors">Setujui</button>
                            </form>
                            <button type="button"
                                    onclick="openRejectModal({{ $agenda->id }})"
                                    class="text-2xs font-bold text-accent-red hover:opacity-70 transition-opacity">Tolak</button>
                            @endif

                            @if ($agenda->status === 'approved')
                            <form method="POST" action="{{ route('admin.agenda.reject', $agenda) }}"
                                  onsubmit="return confirm('Batalkan persetujuan agenda ini?')">
                                @csrf
                                <button type="submit" class="text-2xs font-bold text-neutral-400 hover:text-accent-red transition-colors">Batalkan</button>
                            </form>
                            @endif

                            <form method="POST" action="{{ route('admin.agenda.destroy', $agenda) }}"
                                  onsubmit="return confirm('Hapus agenda ini permanen?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-2xs font-bold text-neutral-300 hover:text-accent-red transition-colors">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if ($agendas->hasPages())
    <div class="mt-4 flex justify-end">{{ $agendas->links() }}</div>
    @endif

    @else
    <div class="card p-12 text-center">
        <p class="text-sm font-semibold text-neutral-500">Belum ada agenda</p>
    </div>
    @endif
</div>

{{-- Modal Tolak --}}
<div id="modal-reject"
     class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4"
     onclick="if(event.target===this) closeRejectModal()">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md p-6">
        <h3 class="font-bold text-navy text-base mb-4">Tolak Agenda</h3>
        <form id="reject-form" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-2xs font-bold text-navy uppercase tracking-widest mb-1.5">Alasan Penolakan</label>
                <textarea name="reject_reason" rows="3"
                          placeholder="Jelaskan alasan penolakan..."
                          class="form-input w-full text-sm resize-none" required></textarea>
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="closeRejectModal()"
                        class="flex-1 btn border border-neutral-200 text-neutral-500 hover:bg-neutral-50">Batal</button>
                <button type="submit"
                        class="flex-1 btn bg-accent-red text-white hover:opacity-90">Tolak Agenda</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openRejectModal(id) {
    document.getElementById('reject-form').action = '/admin/agenda/' + id + '/reject';
    document.getElementById('modal-reject').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}
function closeRejectModal() {
    document.getElementById('modal-reject').classList.add('hidden');
    document.body.style.overflow = '';
}
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeRejectModal(); });
</script>
@endpush

@endsection