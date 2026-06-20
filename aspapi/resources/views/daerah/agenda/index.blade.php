@extends('layouts.daerah')
@section('title', 'Agenda Kegiatan')

@section('content')
<div class="p-6">

    <div class="flex items-start justify-between mb-6">
        <div>
            <h1 class="text-xl font-extrabold text-navy">Agenda Kegiatan</h1>
            <p class="text-sm text-neutral-500 mt-1">Kelola agenda kegiatan daerah Anda. Agenda yang disetujui akan tampil di halaman publik.</p>
        </div>
        <a href="{{ route('daerah.agenda.create') }}" class="btn btn-primary btn-sm flex-shrink-0">+ Tambah Agenda</a>
    </div>

    @if (session('success'))
        <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700 font-medium">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700 font-medium">{{ session('error') }}</div>
    @endif

    @if ($agendas->count())
    <div class="card overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-neutral-100 bg-neutral-50">
                    <th class="text-left px-4 py-3 text-2xs font-bold tracking-widest uppercase text-neutral-400">Nama Kegiatan</th>
                    <th class="text-left px-4 py-3 text-2xs font-bold tracking-widest uppercase text-neutral-400 hidden sm:table-cell w-32">Tanggal</th>
                    <th class="text-left px-4 py-3 text-2xs font-bold tracking-widest uppercase text-neutral-400 w-28">Status</th>
                    <th class="text-right px-4 py-3 text-2xs font-bold tracking-widest uppercase text-neutral-400 w-32">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-50">
                @foreach ($agendas as $agenda)
                <tr class="hover:bg-neutral-50/70 transition-colors">

                    {{-- Nama Kegiatan --}}
                    <td class="px-4 py-3">
                        <div class="flex items-start gap-3">
                            <button type="button" onclick="openDetailModal({{ $agenda->id }})"
                                    class="flex-shrink-0 focus:outline-none group mt-0.5">
                                @if ($agenda->photo)
                                <div class="w-9 h-9 rounded overflow-hidden border border-neutral-100 group-hover:ring-2 group-hover:ring-primary/30 transition-all">
                                    <img src="{{ Storage::url($agenda->photo) }}" class="w-full h-full object-cover" alt="">
                                </div>
                                @else
                                <div class="w-9 h-9 rounded bg-primary-50 flex items-center justify-center border border-neutral-100 group-hover:ring-2 group-hover:ring-primary/30 transition-all">
                                    <svg class="w-4 h-4 text-primary-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                @endif
                            </button>
                            <div>
                                <button type="button" onclick="openDetailModal({{ $agenda->id }})"
                                        class="font-semibold text-navy text-sm hover:text-primary transition-colors text-left leading-snug">
                                    {{ $agenda->title }}
                                </button>
                                <div class="flex flex-wrap gap-x-3 mt-1">
                                    <span class="text-2xs text-neutral-400 sm:hidden">{{ $agenda->event_date->translatedFormat('d M Y') }}</span>
                                    @if ($agenda->reject_reason)
                                    <span class="text-2xs text-accent-red">Ditolak: {{ $agenda->reject_reason }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </td>

                    {{-- Tanggal --}}
                    <td class="px-4 py-3 hidden sm:table-cell whitespace-nowrap align-top">
                        <span class="text-sm text-neutral-500">{{ $agenda->event_date->translatedFormat('d M Y') }}</span>
                    </td>

                    {{-- Status --}}
                    <td class="px-4 py-3 whitespace-nowrap align-top">
                        @php
                            $statusConfig = match($agenda->status) {
                                'pending'  => ['background:#FEF3C7;color:#92400E;', 'Menunggu'],
                                'approved' => ['background:#D1FAE5;color:#065F46;', 'Disetujui'],
                                'rejected' => ['background:#FEE2E2;color:#991B1B;', 'Ditolak'],
                                default    => ['background:#F3F4F6;color:#6B7280;', $agenda->status],
                            };
                        @endphp
                        <span class="inline-flex items-center px-2 py-1 rounded text-2xs font-bold" style="{{ $statusConfig[0] }}">
                            {{ $statusConfig[1] }}
                        </span>
                    </td>

                    {{-- Aksi --}}
                    <td class="px-4 py-3 text-right align-top">
                        <div class="flex items-center justify-end gap-2 flex-wrap">
                            <button type="button" onclick="openDetailModal({{ $agenda->id }})"
                                    class="text-2xs font-bold text-neutral-400 hover:text-primary transition-colors">Detail</button>
                            @if ($agenda->status !== 'approved')
                            <a href="{{ route('daerah.agenda.edit', $agenda) }}"
                               class="text-2xs font-bold text-primary hover:text-primary-600 transition-colors">Edit</a>
                            @endif
                            <form method="POST" action="{{ route('daerah.agenda.destroy', $agenda) }}"
                                  onsubmit="return confirm('Hapus agenda ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-2xs font-bold text-accent-red hover:opacity-70 transition-opacity">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>

                <tr style="display:none;"><td colspan="4">
                    <div id="agenda-data-{{ $agenda->id }}"
                         data-title="{{ e($agenda->title) }}"
                         data-date="{{ $agenda->event_date->translatedFormat('d F Y') }}"
                         data-desc="{{ e($agenda->description ?? '') }}"
                         data-photo="{{ $agenda->photo ? Storage::url($agenda->photo) : '' }}"
                         data-status="{{ $agenda->status }}"
                         data-reject="{{ e($agenda->reject_reason ?? '') }}"
                         data-edit-url="{{ $agenda->status !== 'approved' ? route('daerah.agenda.edit', $agenda) : '' }}">
                    </div>
                </td></tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if ($agendas->hasPages())
    <div class="mt-4 flex justify-end">{{ $agendas->links() }}</div>
    @endif

    @else
    <div class="card p-12 text-center">
        <div class="w-14 h-14 rounded-full bg-neutral-100 flex items-center justify-center mx-auto mb-3">
            <svg class="w-7 h-7 text-neutral-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>
        <p class="text-sm font-semibold text-neutral-500">Belum ada agenda</p>
        <a href="{{ route('daerah.agenda.create') }}" class="btn btn-primary btn-sm mt-4">Tambah Agenda Pertama</a>
    </div>
    @endif
</div>


{{-- ── MODAL DETAIL ── --}}
<div id="modal-detail"
     class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm px-4 py-6"
     onclick="if(event.target===this) closeDetailModal()">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg overflow-hidden max-h-[90vh] overflow-y-auto">

        <div id="modal-photo-wrap" class="w-full overflow-hidden" style="display:none;">
            <img id="modal-photo" src="" alt="" class="w-full aspect-square object-cover">
        </div>
        <div id="modal-no-photo"
             style="width:100%;padding-top:40%;position:relative;background:linear-gradient(135deg,#EEF4FB,#D6E8F7);">
            <div style="position:absolute;top:0;left:0;width:100%;height:100%;display:flex;align-items:center;justify-content:center;">
                <svg style="width:36px;height:36px;color:#B0CCDF;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
        </div>

        <div class="p-6">
            <div class="mb-3">
                <span id="modal-status-badge" class="inline-flex items-center gap-1.5 text-2xs font-bold px-2.5 py-1 rounded"></span>
            </div>
            <div class="flex items-center gap-1.5 mb-2">
                <svg class="w-3.5 h-3.5 text-primary flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span id="modal-date" class="text-xs text-neutral-400 font-medium"></span>
            </div>
            <h2 id="modal-title" class="font-display text-navy text-xl leading-snug mb-4 break-words"></h2>
            <div id="modal-reject-wrap" class="mb-4 p-3 bg-red-50 border border-red-100 rounded-lg" style="display:none;">
                <p class="text-2xs font-bold text-accent-red uppercase tracking-widest mb-1">Alasan Penolakan</p>
                <p id="modal-reject" class="text-xs text-red-700 leading-relaxed break-words"></p>
            </div>
            <div id="modal-desc-wrap" style="display:none;">
                <div class="border-t border-neutral-100 pt-4">
                    <p class="text-2xs font-bold text-neutral-400 uppercase tracking-widest mb-2">Deskripsi</p>
                    <p id="modal-desc" class="text-sm text-neutral-500 leading-relaxed break-words"
                       style="display:-webkit-box;-webkit-box-orient:vertical;-webkit-line-clamp:4;overflow:hidden;"></p>
                    <button id="modal-desc-more" type="button" onclick="toggleDesc()"
                            class="mt-1.5 text-2xs font-bold text-primary hover:opacity-70 transition-opacity"
                            style="display:none;">↓ Selengkapnya</button>
                </div>
            </div>
        </div>

        <div class="px-6 py-4 border-t border-neutral-100 flex gap-3">
            <button onclick="closeDetailModal()"
                    class="flex-1 btn border border-neutral-200 text-neutral-500 hover:bg-neutral-50">Tutup</button>
            <a id="modal-edit-btn" href="#" class="flex-1 btn btn-primary justify-center" style="display:none;">
                Edit Agenda →
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
const statusMap = {
    pending:  { style: 'background:#FEF3C7;color:#92400E;', label: '⏳ Menunggu Persetujuan' },
    approved: { style: 'background:#D1FAE5;color:#065F46;', label: '✓ Disetujui — Tampil di Publik' },
    rejected: { style: 'background:#FEE2E2;color:#991B1B;', label: '✕ Ditolak' },
};
let descExpanded = false;

function toggleDesc() {
    descExpanded = !descExpanded;
    const p = document.getElementById('modal-desc'), btn = document.getElementById('modal-desc-more');
    p.style.webkitLineClamp = descExpanded ? 'unset' : '4';
    p.style.overflow        = descExpanded ? 'visible' : 'hidden';
    btn.textContent         = descExpanded ? '↑ Tutup' : '↓ Selengkapnya';
}

function openDetailModal(id) {
    const el = document.getElementById('agenda-data-' + id);
    if (!el) return;
    descExpanded = false;
    const descP = document.getElementById('modal-desc');
    descP.style.webkitLineClamp = '4'; descP.style.overflow = 'hidden';
    const descBtn = document.getElementById('modal-desc-more');
    descBtn.textContent = '↓ Selengkapnya'; descBtn.style.display = 'none';

    const { title, date, desc, photo, status, reject, editUrl } = el.dataset;

    const s = statusMap[status] || { style: 'background:#F3F4F6;color:#6B7280;', label: status };
    const badge = document.getElementById('modal-status-badge');
    badge.style.cssText = s.style; badge.textContent = s.label;

    document.getElementById('modal-title').textContent = title;
    document.getElementById('modal-date').textContent  = date;

    const photoWrap = document.getElementById('modal-photo-wrap');
    const noPhoto   = document.getElementById('modal-no-photo');
    const photoEl   = document.getElementById('modal-photo');
    if (photo) { photoEl.src = photo; photoEl.alt = title; photoWrap.style.display = ''; noPhoto.style.display = 'none'; }
    else { photoWrap.style.display = 'none'; noPhoto.style.display = ''; }

    const rejectWrap = document.getElementById('modal-reject-wrap');
    if (reject && status === 'rejected') { document.getElementById('modal-reject').textContent = reject; rejectWrap.style.display = ''; }
    else { rejectWrap.style.display = 'none'; }

    const descWrap = document.getElementById('modal-desc-wrap');
    if (desc) {
        descP.textContent = desc; descWrap.style.display = '';
        requestAnimationFrame(() => { if (descP.scrollHeight > descP.clientHeight + 4) descBtn.style.display = ''; });
    } else { descWrap.style.display = 'none'; }

    const editBtn = document.getElementById('modal-edit-btn');
    if (editUrl) { editBtn.href = editUrl; editBtn.style.display = ''; }
    else { editBtn.style.display = 'none'; }

    document.getElementById('modal-detail').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}
function closeDetailModal() { document.getElementById('modal-detail').classList.add('hidden'); document.body.style.overflow = ''; }
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeDetailModal(); });
</script>
@endpush

@endsection