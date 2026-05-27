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
        {{-- Search --}}
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
            @endphp
            <div class="bg-white border border-neutral-200 rounded-xl overflow-hidden flex flex-col shadow-sm hover:shadow-md transition-shadow">

                {{-- Thumbnail --}}
                <div class="h-40 bg-neutral-100 overflow-hidden relative flex-shrink-0">
                    <img src="{{ $seminar->thumbnail_url }}"
                         alt="{{ $seminar->title }}"
                         class="w-full h-full object-contain p-2">
                    @if ($seminar->category)
                    <span class="absolute top-2.5 left-2.5 text-2xs font-bold px-2.5 py-1 rounded-full bg-black/50 text-white backdrop-blur-sm">
                        {{ $seminar->category }}
                    </span>
                    @endif
                    @if ($enrolled)
                    <span class="absolute top-2.5 right-2.5 text-2xs font-bold px-2.5 py-1 rounded-full bg-primary text-white">
                        Terdaftar
                    </span>
                    @endif
                </div>

                {{-- Konten --}}
                <div class="p-4 flex flex-col flex-1">
                    <h3 class="font-bold text-navy text-sm leading-snug mb-1.5">{{ $seminar->title }}</h3>
                    <p class="text-xs text-neutral-500 line-clamp-2 flex-1 mb-3">{{ $seminar->description }}</p>

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
                                onclick="openEnrollModal({{ $seminar->id }}, '{{ addslashes($seminar->title) }}', '{{ addslashes($seminar->description ?? '') }}', '{{ $seminar->thumbnail_url }}')"
                                class="w-full text-xs font-bold py-2 px-4 rounded-lg bg-primary text-white hover:bg-primary/90 transition">
                            Daftar Seminar
                        </button>
                        {{-- Hidden form untuk submit --}}
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
            <p class="text-xs text-neutral-400">
                Total {{ $seminars->total() }} seminar
            </p>
            <div class="flex items-center gap-1">
                {{-- Prev --}}
                @if ($seminars->onFirstPage())
                    <span class="px-3 py-1.5 text-xs rounded-lg border border-neutral-200 text-neutral-300 cursor-not-allowed">← Prev</span>
                @else
                    <a href="{{ $seminars->previousPageUrl() }}&{{ http_build_query(request()->except('page')) }}"
                       class="px-3 py-1.5 text-xs rounded-lg border border-neutral-200 text-neutral-500 hover:bg-neutral-50 transition">← Prev</a>
                @endif

                {{-- Nomor halaman --}}
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

                {{-- Next --}}
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

        {{-- Thumbnail --}}
        <div id="modal-thumbnail" class="h-40 bg-neutral-100 overflow-hidden">
            <img id="modal-thumb-img" src="" alt="" class="w-full h-full object-cover">
        </div>

        <div class="p-6">
            {{-- Judul --}}
            <h3 class="font-extrabold text-navy text-base mb-1" id="modal-title"></h3>
            <p class="text-xs text-neutral-500 line-clamp-2 mb-5" id="modal-desc"></p>

            {{-- Peringatan kuota --}}
            <div class="flex items-start gap-3 p-3 bg-amber-50 border border-amber-100 rounded-lg mb-5">
                <svg class="w-4 h-4 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <div>
                    <p class="text-xs font-bold text-amber-700 mb-0.5">Perhatikan sebelum mendaftar</p>
                    <p class="text-xs text-amber-600">
                        Seminar yang sudah dipilih akan mengurangi kuota kamu.
                        Dalam satu periode keanggotaan, kamu hanya bisa memilih
                        <strong>maksimal 3 seminar</strong>.
                        Pilihan tidak dapat dibatalkan.
                    </p>
                </div>
            </div>

            <p class="text-sm text-neutral-600 mb-5">
                Apakah kamu yakin ingin mendaftar seminar ini?
            </p>

            <div class="flex gap-3">
                <button type="button"
                        onclick="closeEnrollModal()"
                        class="flex-1 py-2.5 text-xs font-bold border border-neutral-200 rounded-lg text-neutral-600 hover:bg-neutral-50 transition">
                    Batal
                </button>
                <button type="button"
                        id="modal-confirm-btn"
                        onclick="submitEnroll()"
                        class="flex-1 py-2.5 text-xs font-bold bg-primary text-white rounded-lg hover:bg-primary/90 transition">
                    Ya, Daftar Sekarang
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let activeFormId = null;

    function openEnrollModal(seminarId, title, desc, thumbUrl) {
        activeFormId = 'enroll-form-' + seminarId;
        document.getElementById('modal-title').textContent    = title;
        document.getElementById('modal-desc').textContent     = desc;
        document.getElementById('modal-thumb-img').src        = thumbUrl;
        document.getElementById('modal-thumb-img').alt        = title;
        document.getElementById('modal-enroll').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeEnrollModal() {
        activeFormId = null;
        document.getElementById('modal-enroll').classList.add('hidden');
        document.body.style.overflow = '';
    }

    function submitEnroll() {
        if (!activeFormId) return;
        const btn = document.getElementById('modal-confirm-btn');
        btn.disabled    = true;
        btn.textContent = 'Mendaftar...';
        document.getElementById(activeFormId).submit();
    }

    // Tutup modal dengan tombol Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeEnrollModal();
    });
</script>
@endpush

@endsection