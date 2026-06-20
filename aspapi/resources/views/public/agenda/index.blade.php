@extends('layouts.app')

@php $title = 'Agenda'; @endphp

@section('content')

{{-- HERO --}}
<section class="relative overflow-hidden bg-gradient-to-br from-navy-dark via-primary-600 to-primary py-20">
    <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-accent-red via-accent-yellow to-accent-yellow"></div>
    <div class="absolute -right-32 -top-32 w-96 h-96 rounded-full bg-white/5 pointer-events-none"></div>
    <div class="absolute -right-16 -bottom-24 w-64 h-64 rounded-full bg-white/5 pointer-events-none"></div>

    <div class="relative max-w-7xl mx-auto px-6">
        <div class="inline-flex items-center gap-2 mb-4">
            <span class="w-8 h-px bg-accent-yellow"></span>
            <span class="text-accent-yellow text-2xs font-bold tracking-widest uppercase">Publikasi ASPAPI</span>
        </div>
        <h1 class="font-display text-white text-4xl leading-tight mb-3">Agenda</h1>
        <p class="text-primary-200 text-sm max-w-xl leading-relaxed">
            Kumpulan kegiatan dan agenda dari seluruh ASPAPI Daerah di Indonesia.
        </p>
        <nav class="flex items-center gap-2 mt-6 text-2xs text-primary-300">
            <a href="{{ route('home') }}" class="hover:text-white transition-colors">Beranda</a>
            <span>/</span>
            <span class="text-white font-medium">Agenda</span>
        </nav>
    </div>
</section>

{{-- FILTER --}}
<section class="py-8 bg-white border-b border-neutral-100">
    <div class="max-w-7xl mx-auto px-6">
        <form method="GET" action="{{ route('public.agenda') }}">
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1">
                    <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                        <svg class="w-4 h-4 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari nama kegiatan..."
                           class="form-input pl-9 w-full text-sm" autocomplete="off">
                </div>

                <div class="sm:w-56">
                    <select name="region" class="form-input w-full text-sm" onchange="this.form.submit()">
                        <option value="">Semua</option>
                        <option value="pusat" {{ request('region') === 'pusat' ? 'selected' : '' }}>ASPAPI Pusat</option>
                        @foreach ($regions as $region)
                            <option value="{{ $region->id }}" {{ request('region') == $region->id ? 'selected' : '' }}>{{ $region->name }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-primary flex-shrink-0">Cari</button>

                @if (request('search') || request('region'))
                <a href="{{ route('public.agenda') }}" class="btn flex-shrink-0 border border-neutral-200 text-neutral-500 hover:bg-neutral-50">Reset</a>
                @endif
            </div>
        </form>
    </div>
</section>

{{-- GRID --}}
<section class="py-12 bg-neutral-50 min-h-64">
    <div class="max-w-7xl mx-auto px-6">

        <div class="flex items-center justify-between mb-6">
            <p class="text-sm text-neutral-500">
                <span class="font-bold text-navy">{{ $agendas->total() }}</span> kegiatan ditemukan
            </p>
        </div>

        @if ($agendas->count())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($agendas as $agenda)
            <div class="card card-top-blue card-hover flex flex-col overflow-hidden cursor-pointer group"
                 onclick="openAgendaModal({{ $agenda->id }})"
                 data-id="{{ $agenda->id }}"
                 data-title="{{ e($agenda->title) }}"
                 data-date="{{ $agenda->event_date->translatedFormat('d F Y') }}"
                 
                 data-region="{{ e($agenda->region->name ?? 'ASPAPI Pusat') }}"
                 data-photo="{{ $agenda->photo ? Storage::url($agenda->photo) : '' }}">

                {{-- Foto 1:1 --}}
                <div style="position:relative;width:100%;padding-top:100%;overflow:hidden;background:#EEF4FB;flex-shrink:0;">
                    @if ($agenda->photo)
                        <img src="{{ Storage::url($agenda->photo) }}"
                             style="position:absolute;top:0;left:0;width:100%;height:100%;object-fit:cover;display:block;"
                             class="group-hover:scale-105 transition-transform duration-500"
                             alt="{{ $agenda->title }}">
                    @else
                        <div style="position:absolute;top:0;left:0;width:100%;height:100%;display:flex;align-items:center;justify-content:center;">
                            <svg style="width:48px;height:48px;color:#B0CCDF;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    @endif

                    @if ($agenda->region)
                    <div style="position:absolute;top:10px;left:10px;">
                        <span style="background:rgba(26,42,58,0.75);color:#fff;font-size:0.6rem;font-weight:700;letter-spacing:0.04em;padding:3px 8px;border-radius:4px;backdrop-filter:blur(4px);">
                            {{ $agenda->region->name ?? 'ASPAPI Pusat' }}
                        </span>
                    </div>
                    @endif
                </div>

                <div class="p-5 flex flex-col flex-1">
                    <div class="flex items-center gap-1.5 mb-2">
                        <svg class="w-3.5 h-3.5 text-primary flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span class="text-2xs text-neutral-400 font-medium">{{ $agenda->event_date->translatedFormat('d F Y') }}</span>
                    </div>

                    <h3 class="text-sm font-bold text-navy leading-snug mb-2 flex-1 group-hover:text-primary transition-colors">
                        {{ $agenda->title }}
                    </h3>

                    @php
                        $rawDesc = $agenda->description ?? '';
                        // Convert <br>, </p>, </div>, </li>, </h*> to newline first
                        $plainDesc = preg_replace('/<br\s*\/?>/i', "\n", $rawDesc);
                        $plainDesc = preg_replace('/<\/(p|div|section|article|li|ul|ol|h[1-6]|tr)>/i', "\n", $plainDesc);
                        // Strip all remaining HTML tags
                        $plainDesc = strip_tags($plainDesc);
                        // Decode HTML entities (from Quill output)
                        $plainDesc = html_entity_decode($plainDesc, ENT_QUOTES, 'UTF-8');
                        // Collapse multiple newlines / whitespace
                        $plainDesc = preg_replace("/[ \t]+/", " ", $plainDesc);
                        $plainDesc = preg_replace("/\n\s*\n+/", "\n", $plainDesc);
                        $plainDesc = trim($plainDesc);
                    @endphp
                    @if ($plainDesc)
                    <p class="text-xs text-neutral-400 leading-relaxed line-clamp-2 mb-3">{!! nl2br(e($plainDesc)) !!}</p>
                    @endif

                    <span class="text-2xs font-bold tracking-widest uppercase text-primary border-b-2 border-accent-yellow pb-0.5 w-fit mt-auto">
                        Lihat Detail →
                    </span>
                </div>
            </div>

            <div id="agenda-desc-{{ $agenda->id }}" class="hidden">
                {!! $agenda->description !!}
            </div>
            @endforeach
        </div>

        @if ($agendas->hasPages())
        <div class="mt-10 flex items-center justify-center gap-1">
            @if ($agendas->onFirstPage())
                <span class="inline-flex items-center justify-center w-8 h-8 rounded text-neutral-300 cursor-not-allowed">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </span>
            @else
                <a href="{{ $agendas->previousPageUrl() }}" class="inline-flex items-center justify-center w-8 h-8 rounded border border-neutral-200 text-neutral-500 hover:bg-neutral-100 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </a>
            @endif

            @foreach ($agendas->getUrlRange(max(1, $agendas->currentPage()-2), min($agendas->lastPage(), $agendas->currentPage()+2)) as $page => $url)
                @if ($page == $agendas->currentPage())
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded bg-primary text-white text-xs font-bold">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="inline-flex items-center justify-center w-8 h-8 rounded border border-neutral-200 text-neutral-500 hover:bg-neutral-100 transition-colors text-xs">{{ $page }}</a>
                @endif
            @endforeach

            @if ($agendas->hasMorePages())
                <a href="{{ $agendas->nextPageUrl() }}" class="inline-flex items-center justify-center w-8 h-8 rounded border border-neutral-200 text-neutral-500 hover:bg-neutral-100 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            @else
                <span class="inline-flex items-center justify-center w-8 h-8 rounded text-neutral-300 cursor-not-allowed">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </span>
            @endif
        </div>
        @endif

        @else
        <div class="card p-16 text-center">
            <div class="w-16 h-16 rounded-full bg-neutral-100 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-neutral-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <p class="text-sm font-semibold text-neutral-500">Belum ada agenda</p>
            <p class="text-xs text-neutral-400 mt-1">Pantau terus halaman ini untuk agenda kegiatan terbaru.</p>
        </div>
        @endif

    </div>
</section>

{{-- MODAL DETAIL AGENDA --}}
<div id="modal-agenda"
     class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm px-4 py-6"
     onclick="if(event.target===this) closeAgendaModal()">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg overflow-hidden max-h-[90vh] overflow-y-auto">

        <div id="modal-photo-wrap" class="w-full overflow-hidden" style="display:none;">
            <img id="modal-photo" src="" alt="" class="w-full aspect-square object-cover">
        </div>
        <div id="modal-no-photo" class="w-full aspect-square bg-gradient-to-br from-primary-100 to-primary-200 flex items-center justify-center">
            <svg class="w-16 h-16 text-primary-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>

        <div class="p-6">
            <div class="flex items-center gap-3 mb-3 flex-wrap">
                <span id="modal-region-badge"
                      class="inline-flex items-center gap-1.5 bg-primary-50 text-primary text-2xs font-bold px-2.5 py-1 rounded"
                      style="display:none;">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    </svg>
                    <span id="modal-region"></span>
                </span>
                <div class="flex items-center gap-1.5 text-2xs text-neutral-400">
                    <svg class="w-3.5 h-3.5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span id="modal-date" class="font-medium"></span>
                </div>
            </div>

            <h2 id="modal-title" class="font-display text-navy text-xl leading-snug mb-4"></h2>

            <div id="modal-desc-wrap" style="display:none;">
                <div class="border-t border-neutral-100 pt-4">
                    <div id="modal-desc" class="prose prose-sm max-w-none text-neutral-600"></div>
                </div>
            </div>
        </div>

        <div class="px-6 py-4 border-t border-neutral-100">
            <button onclick="closeAgendaModal()"
                    class="btn w-full justify-center border border-neutral-200 text-neutral-500 hover:bg-neutral-50">
                Tutup
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openAgendaModal(id) {
    const card = document.querySelector('[data-id="' + id + '"]');
    if (!card) return;

    const title  = card.dataset.title;
    const date   = card.dataset.date;
    const descContainer = document.getElementById(`agenda-desc-${id}`);
    const desc = descContainer ? descContainer.innerHTML : '';
    const region = card.dataset.region;
    const photo  = card.dataset.photo;

    document.getElementById('modal-title').textContent = title;
    document.getElementById('modal-date').textContent  = date;

    const regionBadge = document.getElementById('modal-region-badge');
    const regionEl    = document.getElementById('modal-region');
    if (region) {
        regionEl.textContent      = region;
        regionBadge.style.display = '';
    } else {
        regionBadge.style.display = 'none';
    }

    const photoWrap = document.getElementById('modal-photo-wrap');
    const noPhoto   = document.getElementById('modal-no-photo');
    const photoEl   = document.getElementById('modal-photo');
    if (photo) {
        photoEl.src             = photo;
        photoEl.alt             = title;
        photoWrap.style.display = '';
        noPhoto.style.display   = 'none';
    } else {
        photoWrap.style.display = 'none';
        noPhoto.style.display   = '';
    }

    const descWrap = document.getElementById('modal-desc-wrap');
    const descEl   = document.getElementById('modal-desc');
    if (desc) {
        descEl.innerHTML       = desc;
        descWrap.style.display  = '';
    } else {
        descWrap.style.display  = 'none';
    }

    document.getElementById('modal-agenda').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeAgendaModal() {
    document.getElementById('modal-agenda').classList.add('hidden');
    document.body.style.overflow = '';
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeAgendaModal();
});
</script>
@endpush

@endsection