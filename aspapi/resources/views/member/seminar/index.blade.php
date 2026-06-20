@extends('layouts.member')
@section('title', 'Daftar Seminar')

@section('content')
<div class="p-6">

    {{-- ── Header ── --}}
    <div class="flex items-start justify-between mb-6">
        <div>
            <h1 class="text-xl font-extrabold text-navy">Seminar ASPAPI</h1>
            <p class="text-sm text-neutral-500 mt-1">
                Sisa kuota periode ini:
                <span class="font-bold {{ $remainingQuota > 0 ? 'text-primary' : 'text-red-500' }}">
                    {{ $remainingQuota }}
                </span> dari 3 seminar.
            </p>
        </div>
        <a href="{{ route('member.seminar.my-seminars') }}"
           class="text-xs font-bold px-4 py-2 border border-neutral-200 rounded-lg text-neutral-600 hover:bg-neutral-50 transition flex-shrink-0">
            Seminar Saya →
        </a>
    </div>

    @if (session('success'))
        <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700 font-medium">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700 font-medium">
            {{ session('error') }}
        </div>
    @endif

    {{-- ── Search + Filter bar ── --}}
    @php
        $categories     = \App\Models\Seminar::where('is_active', true)
            ->whereNotNull('category')->distinct()->orderBy('category')->pluck('category');
        $activeCategory = request('category');
        $search         = request('search');
    @endphp

    <div class="flex flex-col sm:flex-row gap-3 mb-5">
        <form method="GET" action="{{ route('member.seminar.index') }}" class="flex gap-2 flex-1">
            @if ($activeCategory)
                <input type="hidden" name="category" value="{{ $activeCategory }}">
            @endif
            <div class="relative flex-1">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-neutral-400"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
                </svg>
                <input type="text" name="search" value="{{ $search }}"
                       placeholder="Cari seminar..."
                       class="w-full pl-9 pr-4 py-2.5 text-sm border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/30">
            </div>
            <button type="submit"
                    class="px-4 py-2.5 text-xs font-bold bg-primary text-white rounded-lg hover:bg-primary/90 transition flex-shrink-0">
                Cari
            </button>
            @if ($search || $activeCategory)
            <a href="{{ route('member.seminar.index') }}"
               class="px-3 py-2.5 text-xs font-bold border border-neutral-200 rounded-lg text-neutral-500 hover:bg-neutral-50 transition flex-shrink-0">
                Reset
            </a>
            @endif
        </form>
    </div>

    {{-- Filter Kategori --}}
    @if ($categories->isNotEmpty())
    <div class="flex items-center gap-2 flex-wrap mb-5">
        <a href="{{ route('member.seminar.index', array_filter(['search' => $search])) }}"
           class="text-xs font-bold px-3 py-1.5 rounded-full border transition
                  {{ ! $activeCategory ? 'bg-primary text-white border-primary' : 'border-neutral-200 text-neutral-500 bg-white hover:border-primary hover:text-primary' }}">
            Semua
        </a>
        @foreach ($categories as $cat)
        <a href="{{ route('member.seminar.index', array_filter(['category' => $cat, 'search' => $search])) }}"
           class="text-xs font-bold px-3 py-1.5 rounded-full border transition
                  {{ $activeCategory === $cat ? 'bg-primary text-white border-primary' : 'border-neutral-200 text-neutral-500 bg-white hover:border-primary hover:text-primary' }}">
            {{ $cat }}
        </a>
        @endforeach
    </div>
    @endif

    {{-- ── Info hasil ── --}}
    <div class="flex items-center justify-between mb-4">
        <p class="text-xs text-neutral-400">
            Menampilkan
            <span class="font-semibold text-navy">{{ $seminars->firstItem() ?? 0 }}–{{ $seminars->lastItem() ?? 0 }}</span>
            dari <span class="font-semibold text-navy">{{ $seminars->total() }}</span> seminar
            @if ($search) yang cocok dengan "<span class="font-semibold">{{ $search }}</span>" @endif
            @if ($activeCategory) dalam kategori "<span class="font-semibold">{{ $activeCategory }}</span>" @endif
        </p>
        <p class="text-xs text-neutral-400">
            Halaman {{ $seminars->currentPage() }} dari {{ $seminars->lastPage() }}
        </p>
    </div>

    {{-- ── Grid seminar ── --}}
    @if ($seminars->isEmpty())
        <div class="text-center py-20 text-neutral-400">
            <svg class="w-12 h-12 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
            <p class="text-sm font-medium text-neutral-500">Tidak ada seminar ditemukan.</p>
            @if ($search || $activeCategory)
            <a href="{{ route('member.seminar.index') }}" class="mt-2 inline-block text-xs text-primary hover:underline">
                Lihat semua seminar →
            </a>
            @endif
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
            @foreach ($seminars as $seminar)
            @php
                $enrollmentId = $enrolledMap[$seminar->id] ?? null;
                $enrolled     = $enrollmentId !== null;
                $canEnroll    = $isActive && ! $enrolled && $remainingQuota > 0;
                $hasDesc   = !empty(trim(strip_tags($seminar->description ?? '')));
                $isLong    = strlen(strip_tags($seminar->description ?? '')) > 300;
            @endphp

            {{-- Hidden div untuk deskripsi HTML (dipakai modal enroll) --}}
            @if ($seminar->description)
            <div id="seminar-desc-{{ $seminar->id }}" style="display:none;">{!! $seminar->description !!}</div>
            @endif

            <div class="bg-white border border-neutral-200 rounded-xl overflow-hidden flex flex-col shadow-sm hover:shadow-md transition-shadow">

                {{-- Thumbnail --}}
                <div style="position:relative;width:100%;padding-top:100%;overflow:hidden;background:#EEF4FB;flex-shrink:0;">
                    @if($seminar->thumbnail)
                        <img src="{{ Storage::url($seminar->thumbnail) }}"
                             style="position:absolute;top:0;left:0;width:100%;height:100%;object-fit:cover;display:block;"
                             alt="{{ $seminar->title }}">
                    @else
                        <div style="position:absolute;top:0;left:0;width:100%;height:100%;display:flex;align-items:center;justify-content:center;">
                            <svg style="width:40px;height:40px;color:#B0CCDF;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    @endif
                    @if($seminar->category)
                    <div style="position:absolute;top:10px;left:10px;">
                        <span style="background:rgba(26,42,58,0.75);color:#fff;font-size:0.6rem;font-weight:700;letter-spacing:0.06em;text-transform:uppercase;padding:3px 8px;border-radius:4px;backdrop-filter:blur(4px);">
                            {{ $seminar->category }}
                        </span>
                    </div>
                    @endif
                    @if($enrolled)
                    <div style="position:absolute;top:10px;right:10px;">
                        <span style="background:#2A7FC1;color:#fff;font-size:0.6rem;font-weight:700;letter-spacing:0.04em;padding:3px 8px;border-radius:4px;">
                            Terdaftar
                        </span>
                    </div>
                    @endif
                </div>

                {{-- Konten --}}
                <div class="p-4 flex flex-col flex-1">
                    <h3 class="font-bold text-navy text-sm leading-snug mb-1.5">{{ $seminar->title }}</h3>

                    {{-- Deskripsi: render HTML dari rich editor --}}
                    @if($hasDesc)
                    <div x-data="{ open: false }" class="mb-3 flex-1">
                        <div x-show="!open"
                             class="text-xs text-neutral-500 leading-relaxed rich-output"
                             style="overflow:hidden;display:-webkit-box;-webkit-box-orient:vertical;-webkit-line-clamp:3;">
                            {!! $seminar->description !!}
                        </div>
                        <div x-show="open"
                             style="display:none;"
                             class="text-xs text-neutral-500 leading-relaxed rich-output">
                            {!! $seminar->description !!}
                        </div>
                        @if($isLong)
                        <button @click="open = !open" type="button"
                                class="mt-1 text-2xs font-bold"
                                style="color:#2A7FC1;background:none;border:none;cursor:pointer;padding:0;line-height:1.4;">
                            <span x-text="open ? '↑ Tutup' : '↓ Selengkapnya'">↓ Selengkapnya</span>
                        </button>
                        @endif
                    </div>
                    @else
                    <div class="flex-1 mb-3"></div>
                    @endif

                    <div class="flex items-center gap-3 pb-3 border-b border-neutral-100 mb-3">
                        <span class="flex items-center gap-1 text-2xs text-neutral-400">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <span class="font-semibold text-navy">{{ $seminar->questions_count }}</span> soal
                        </span>
                        <span class="flex items-center gap-1 text-2xs text-neutral-400">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Passing <span class="font-semibold text-navy">{{ $seminar->passing_grade }}%</span>
                        </span>
                    </div>

                    {{-- Aksi --}}
                    @if ($enrolled && $enrollmentId)
                        <a href="{{ route('member.seminar.show', $enrollmentId) }}"
                           class="block text-center text-xs font-bold py-2 px-4 rounded-lg bg-primary/10 text-primary hover:bg-primary/20 transition">
                            Lanjutkan →
                        </a>
                    @elseif ($canEnroll)
                        <button type="button"
                                data-seminar-id="{{ $seminar->id }}"
                                data-seminar-title="{{ $seminar->title }}"
                                onclick="openEnrollModal(this)"
                                class="w-full text-xs font-bold py-2 px-4 rounded-lg bg-primary text-white hover:bg-primary/90 transition">
                            Daftar Seminar
                        </button>
                        <form id="enroll-form-{{ $seminar->id }}"
                              method="POST"
                              action="{{ route('member.seminar.enroll', $seminar) }}"
                              class="hidden">
                            @csrf
                        </form>
                    @elseif (! $isActive)
                        <span class="block text-center text-xs text-neutral-400 py-2">Keanggotaan tidak aktif</span>
                    @else
                        <span class="block text-center text-xs text-neutral-400 py-2">Kuota habis</span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        {{-- ── Pagination ── --}}
        @if ($seminars->hasPages())
        <div class="mt-6 flex items-center justify-between">
            <p class="text-xs text-neutral-400">Total {{ $seminars->total() }} seminar</p>
            <div class="flex items-center gap-1">
                @if ($seminars->onFirstPage())
                    <span class="px-3 py-1.5 text-xs rounded-lg border border-neutral-200 text-neutral-300 cursor-not-allowed">← Prev</span>
                @else
                    <a href="{{ $seminars->previousPageUrl() }}&{{ http_build_query(request()->except('page')) }}"
                       class="px-3 py-1.5 text-xs rounded-lg border border-neutral-200 text-neutral-500 hover:bg-neutral-50 transition">← Prev</a>
                @endif
                @foreach ($seminars->getUrlRange(1, $seminars->lastPage()) as $page => $url)
                    @if ($page == $seminars->currentPage())
                        <span class="px-3 py-1.5 text-xs rounded-lg bg-primary text-white font-bold">{{ $page }}</span>
                    @elseif (abs($page - $seminars->currentPage()) <= 2)
                        <a href="{{ $url }}&{{ http_build_query(request()->except('page')) }}"
                           class="px-3 py-1.5 text-xs rounded-lg border border-neutral-200 text-neutral-500 hover:bg-neutral-50 transition">{{ $page }}</a>
                    @elseif ($page == 1 || $page == $seminars->lastPage())
                        <a href="{{ $url }}&{{ http_build_query(request()->except('page')) }}"
                           class="px-3 py-1.5 text-xs rounded-lg border border-neutral-200 text-neutral-500 hover:bg-neutral-50 transition">{{ $page }}</a>
                    @elseif (abs($page - $seminars->currentPage()) == 3)
                        <span class="px-2 text-xs text-neutral-300">...</span>
                    @endif
                @endforeach
                @if ($seminars->hasMorePages())
                    <a href="{{ $seminars->nextPageUrl() }}&{{ http_build_query(request()->except('page')) }}"
                       class="px-3 py-1.5 text-xs rounded-lg border border-neutral-200 text-neutral-500 hover:bg-neutral-50 transition">Next →</a>
                @else
                    <span class="px-3 py-1.5 text-xs rounded-lg border border-neutral-200 text-neutral-300 cursor-not-allowed">Next →</span>
                @endif
            </div>
        </div>
        @endif
    @endif
</div>

{{-- ── Modal Konfirmasi Daftar Seminar ── --}}
<div id="modal-enroll"
     class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm px-4"
     onclick="if(event.target===this) closeEnrollModal()">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">
        <div class="p-6">

            <h3 class="font-extrabold text-navy text-base mb-1" id="modal-title"></h3>

            {{-- Deskripsi sebagai HTML dari rich editor --}}
            <div class="mb-5">
                <div id="modal-desc"
                     class="text-xs text-neutral-500 leading-relaxed seminar-modal-desc"
                     style="overflow:hidden;max-height:4rem;"></div>
                <button type="button" id="modal-desc-toggle" onclick="toggleModalDesc()"
                        style="display:none;color:#2A7FC1;background:none;border:none;cursor:pointer;padding:0;font-size:0.65rem;font-weight:700;line-height:1.4;margin-top:4px;">
                    ↓ Selengkapnya
                </button>
            </div>

            <div class="flex items-start gap-3 p-3 bg-amber-50 border border-amber-100 rounded-lg mb-5">
                <svg class="w-4 h-4 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <div>
                    <p class="text-xs font-bold text-amber-700 mb-0.5">Perhatikan sebelum mendaftar</p>
                    <p class="text-xs text-amber-600">
                        Seminar yang sudah dipilih akan mengurangi kuota Anda.
                        Dalam satu periode keanggotaan, Anda hanya bisa memilih
                        <strong>maksimal 3 seminar</strong>.
                        Pilihan tidak dapat dibatalkan.
                    </p>
                </div>
            </div>

            <p class="text-sm text-neutral-600 mb-5">Apakah Anda yakin ingin mendaftar seminar ini?</p>

            <div class="flex gap-3">
                <button type="button" onclick="closeEnrollModal()"
                        class="flex-1 py-2.5 text-xs font-bold border border-neutral-200 rounded-lg text-neutral-600 hover:bg-neutral-50 transition">
                    Batal
                </button>
                <button type="button" id="modal-confirm-btn" onclick="submitEnroll()"
                        class="flex-1 py-2.5 text-xs font-bold bg-primary text-white rounded-lg hover:bg-primary/90 transition">
                    Ya, Daftar Sekarang
                </button>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
/* Styling HTML dari rich editor di modal daftar seminar */
.seminar-modal-desc p            { margin-bottom: 0.4em; }
.seminar-modal-desc p:last-child { margin-bottom: 0; }
.seminar-modal-desc strong       { font-weight: 700; color: #1A2A3A; }
.seminar-modal-desc em           { font-style: italic; }
.seminar-modal-desc ul           { list-style: disc; padding-left: 1.1rem; margin-bottom: 0.4em; }
.seminar-modal-desc ol           { list-style: decimal; padding-left: 1.1rem; margin-bottom: 0.4em; }
.seminar-modal-desc li           { margin-bottom: 0.15em; }
</style>
@endpush

@push('scripts')
<script>
    let activeFormId  = null;
    let modalDescOpen = false;

    function openEnrollModal(btn) {
        const seminarId = btn.dataset.seminarId;
        activeFormId = 'enroll-form-' + seminarId;

        const descEl   = document.getElementById('modal-desc');
        const toggle   = document.getElementById('modal-desc-toggle');

        // Isi judul
        document.getElementById('modal-title').textContent = btn.dataset.seminarTitle;

        // Isi deskripsi dari hidden div (HTML asli dari rich editor)
        const descContainer = document.getElementById('seminar-desc-' + seminarId);
        const descHtml      = descContainer ? descContainer.innerHTML.trim() : '';
        descEl.innerHTML    = descHtml;

        // Reset state collapse
        modalDescOpen          = false;
        descEl.style.maxHeight = '4rem';
        descEl.style.overflow  = 'hidden';
        toggle.textContent     = '↓ Selengkapnya';
        toggle.style.display   = 'none';

        // Tampilkan modal
        document.getElementById('modal-enroll').classList.remove('hidden');
        document.body.style.overflow = 'hidden';

        // Cek apakah konten melebihi max-height
        requestAnimationFrame(function() {
            if (descEl.scrollHeight > descEl.clientHeight + 4) {
                toggle.style.display = 'block';
            }
        });
    }

    function toggleModalDesc() {
        const descEl = document.getElementById('modal-desc');
        const toggle = document.getElementById('modal-desc-toggle');
        modalDescOpen = !modalDescOpen;
        if (modalDescOpen) {
            descEl.style.maxHeight = 'none';
            descEl.style.overflow  = 'visible';
            toggle.textContent     = '↑ Tutup';
        } else {
            descEl.style.maxHeight = '4rem';
            descEl.style.overflow  = 'hidden';
            toggle.textContent     = '↓ Selengkapnya';
        }
    }

    function closeEnrollModal() {
        activeFormId = null;
        document.getElementById('modal-enroll').classList.add('hidden');
        document.body.style.overflow = '';
    }

    function submitEnroll() {
        if (!activeFormId) return;
        const btn       = document.getElementById('modal-confirm-btn');
        btn.disabled    = true;
        btn.textContent = 'Mendaftar...';
        document.getElementById(activeFormId).submit();
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeEnrollModal();
    });
</script>
@endpush

@endsection