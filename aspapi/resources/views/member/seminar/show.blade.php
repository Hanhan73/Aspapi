@extends('layouts.member')
@section('title', $enrollment->seminar->title)

@section('content')
<div class="p-6">

    {{-- Breadcrumb --}}
    <div class="mb-5 flex items-center gap-2 text-2xs text-neutral-400">
        <a href="{{ route('member.seminar.my-seminars') }}" class="hover:text-primary">Seminar Saya</a>
        <span>/</span>
        <span class="text-navy font-medium">{{ $enrollment->seminar->title }}</span>
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

    @php
        $driveUrl = $enrollment->seminar->material_url;
        preg_match('/\/d\/([a-zA-Z0-9_-]+)/', $driveUrl, $matches);
        $fileId   = $matches[1] ?? null;
        $embedUrl = $fileId ? "https://drive.google.com/file/d/{$fileId}/preview" : $driveUrl;
    @endphp

    {{-- ── Header Seminar ── --}}
    <div class="bg-white border border-neutral-200 rounded-xl overflow-hidden mb-6">
        <div class="flex flex-col md:flex-row">

            {{-- Thumbnail --}}
            <div class="md:w-56 h-44 md:h-auto flex-shrink-0 bg-neutral-100">
                <img src="{{ $enrollment->seminar->thumbnail_url }}"
                     alt="{{ $enrollment->seminar->title }}"
                     class="w-full h-full object-contain p-3">
            </div>

            {{-- Info --}}
            <div class="flex-1 p-5 flex flex-col justify-between">
                <div>
                    @if ($enrollment->seminar->category)
                    <span class="text-2xs font-bold px-2.5 py-1 rounded-full bg-primary/10 text-primary mb-2 inline-block">
                        {{ $enrollment->seminar->category }}
                    </span>
                    @endif
                    <h1 class="text-lg font-extrabold text-navy mt-1">{{ $enrollment->seminar->title }}</h1>
                    <p class="text-sm text-neutral-500 mt-2 leading-relaxed line-clamp-2">{{ $enrollment->seminar->description }}</p>
                </div>

                {{-- Stats row --}}
                <div class="flex items-center gap-5 mt-4 pt-4 border-t border-neutral-100">
                    <div class="text-center">
                        <p class="text-xs text-neutral-400">Passing Grade</p>
                        <p class="text-sm font-extrabold text-navy">{{ $enrollment->seminar->passing_grade }}%</p>
                    </div>
                    @if ($preTest?->submitted_at)
                    <div class="text-center">
                        <p class="text-xs text-neutral-400">Skor Pre-Test</p>
                        <p class="text-sm font-extrabold text-indigo-600">{{ $preTest->score }}</p>
                    </div>
                    @endif
                    @if ($postTest?->submitted_at)
                    <div class="text-center">
                        <p class="text-xs text-neutral-400">Skor Post-Test</p>
                        <p class="text-sm font-extrabold {{ $postTest->is_passed ? 'text-green-600' : 'text-orange-500' }}">
                            {{ $postTest->score }}
                        </p>
                    </div>
                    @endif
                    <div class="ml-auto">
                        @php
                            $statusColor = match($enrollment->status) {
                                'completed'      => 'bg-green-50 text-green-700',
                                'post_test_done' => 'bg-orange-50 text-orange-700',
                                'material_read'  => 'bg-blue-50 text-blue-700',
                                'pre_test_done'  => 'bg-indigo-50 text-indigo-700',
                                default          => 'bg-neutral-100 text-neutral-500',
                            };
                        @endphp
                        <span class="text-2xs font-bold px-3 py-1.5 rounded-full {{ $statusColor }}">
                            {{ $enrollment->status_label }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Stepper ── --}}
    @php
        $steps = [
            ['key' => 'pre_test',    'label' => 'Pre-Test'],
            ['key' => 'material',    'label' => 'Materi'],
            ['key' => 'post_test',   'label' => 'Post-Test'],
            ['key' => 'certificate', 'label' => 'Sertifikat'],
        ];
        $currentStep = match(true) {
            $enrollment->isCompleted()               => 'certificate',
            $enrollment->status === 'post_test_done' => 'post_test',
            $enrollment->isMaterialRead()            => 'post_test',
            $enrollment->isPreTestDone()             => 'material',
            default                                  => 'pre_test',
        };
        $stepOrder  = ['pre_test' => 0, 'material' => 1, 'post_test' => 2, 'certificate' => 3];
        $currentIdx = $stepOrder[$currentStep];
    @endphp

    <div class="bg-white border border-neutral-200 rounded-xl px-6 py-5 mb-6">
        <div class="flex items-center">
            @foreach ($steps as $i => $step)
            @php
                $thisIdx  = $stepOrder[$step['key']];
                $isDone   = $thisIdx < $currentIdx;
                $isActive = $step['key'] === $currentStep;
            @endphp
            <div class="flex items-center {{ $loop->last ? '' : 'flex-1' }}">
                <div class="flex flex-col items-center">
                    <div class="w-9 h-9 rounded-full flex items-center justify-center text-xs font-bold transition-all
                        {{ $isDone ? 'bg-green-500 text-white' : ($isActive ? 'bg-primary text-white ring-4 ring-primary/20' : 'bg-neutral-100 text-neutral-400') }}">
                        @if ($isDone)
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                        @else
                            {{ $i + 1 }}
                        @endif
                    </div>
                    <span class="text-2xs mt-1.5 font-medium whitespace-nowrap
                        {{ $isActive ? 'text-primary' : ($isDone ? 'text-green-600' : 'text-neutral-400') }}">
                        {{ $step['label'] }}
                    </span>
                </div>
                @if (! $loop->last)
                    <div class="flex-1 h-0.5 mx-3 mb-5 {{ $isDone ? 'bg-green-400' : 'bg-neutral-200' }}"></div>
                @endif
            </div>
            @endforeach
        </div>
    </div>

    {{-- ── Panel Aksi ── --}}

    {{-- STEP 1: Pre-Test --}}
    @if (! $enrollment->isPreTestDone())
        <div class="bg-white border border-neutral-200 rounded-xl p-8 text-center">
            <div class="w-14 h-14 bg-primary/10 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-7 h-7 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                </svg>
            </div>
            <h2 class="font-bold text-navy text-base mb-2">Mulai dengan Pre-Test</h2>
            <p class="text-sm text-neutral-500 mb-2 max-w-md mx-auto">
                Kerjakan <strong>5 soal</strong> untuk mengukur pemahamanmu sebelum materi.
            </p>
            <p class="text-xs text-neutral-400 mb-6">Pre-test tidak mempengaruhi kelulusan.</p>
            <a href="{{ route('member.seminar.pretest.start', $enrollment) }}"
               class="inline-block px-8 py-2.5 bg-primary text-white text-sm font-bold rounded-lg hover:bg-primary/90 transition">
                Mulai Pre-Test
            </a>
        </div>

    {{-- STEP 2: Materi --}}
    @elseif (! $enrollment->isMaterialRead())
        <div class="bg-white border border-neutral-200 rounded-xl overflow-hidden">
            {{-- Top bar --}}
            <div class="flex items-center justify-between px-5 py-4 border-b border-neutral-100">
                <div>
                    <h2 class="font-bold text-navy text-sm">Materi Seminar</h2>
                    <p class="text-xs text-neutral-400 mt-0.5">Baca materi berikut sebelum mengerjakan post-test.</p>
                </div>
                <div class="flex items-center gap-3">
                    @if ($preTest)
                    <span class="text-2xs px-2.5 py-1 bg-indigo-50 text-indigo-700 rounded-full font-bold">
                        Pre-test: {{ $preTest->score }}
                    </span>
                    @endif
                    {{-- Buka fullscreen --}}
                    <button onclick="openMaterialModal()"
                            class="flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 border border-neutral-200 rounded-lg text-neutral-600 hover:bg-neutral-50 transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
                        </svg>
                        Fullscreen
                    </button>
                </div>
            </div>

            {{-- Iframe embed --}}
            <div style="height: 560px;">
                <iframe src="{{ $embedUrl }}" width="100%" height="100%"
                        allow="autoplay" style="border:none; display:block;"></iframe>
            </div>

            {{-- Bottom bar --}}
            <div class="px-5 py-4 border-t border-neutral-100 flex items-center justify-between">
                <p class="text-xs text-neutral-400 flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Materi hanya bisa dilihat, tidak bisa diunduh atau dicetak.
                </p>
                <form method="POST" action="{{ route('member.seminar.material.done', $enrollment) }}">
                    @csrf
                    <button type="submit"
                            class="px-6 py-2 bg-primary text-white text-xs font-bold rounded-lg hover:bg-primary/90 transition">
                        Selesai Membaca → Lanjut Post-Test
                    </button>
                </form>
            </div>
        </div>

    {{-- STEP 3: Post-Test --}}
    @elseif (! $enrollment->isCompleted())
        <div class="bg-white border border-neutral-200 rounded-xl overflow-hidden">
            <div class="p-6">
                {{-- Info post-test --}}
                <div class="flex items-start gap-4 mb-5">
                    <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="font-bold text-navy text-base mb-1">Post-Test</h2>
                        <p class="text-sm text-neutral-500">
                            Kerjakan <strong class="text-navy">{{ $enrollment->seminar->questions_count }} soal</strong>
                            dengan passing grade <strong class="text-navy">{{ $enrollment->seminar->passing_grade }}%</strong>
                            untuk mendapatkan sertifikat.
                        </p>
                    </div>
                </div>

                @if ($postTest && $postTest->submitted_at)
                <div class="mb-5 p-4 bg-orange-50 border border-orange-100 rounded-lg flex items-center gap-3">
                    <svg class="w-5 h-5 text-orange-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <p class="text-sm text-orange-700">
                        Percobaan terakhir: skor <strong>{{ $postTest->score }}</strong> —
                        belum mencapai passing grade. Kamu bisa mencoba lagi.
                    </p>
                </div>
                @endif

                {{-- Tombol baca ulang materi (accordion) --}}
                <div class="mb-5 border border-neutral-200 rounded-xl overflow-hidden" x-data="{ open: false }">
                    <button @click="open = !open"
                            class="w-full flex items-center justify-between px-4 py-3 bg-neutral-50 hover:bg-neutral-100 transition text-left">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253"/>
                            </svg>
                            <span class="text-xs font-bold text-neutral-600">Baca ulang materi sebelum post-test</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <button @click.stop="openMaterialModal()"
                                    class="text-2xs font-bold px-2 py-1 border border-neutral-300 rounded text-neutral-500 hover:bg-white transition">
                                Fullscreen
                            </button>
                            <svg class="w-4 h-4 text-neutral-400 transition-transform" :class="open ? 'rotate-180' : ''"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </button>
                    <div x-show="open" x-transition style="height: 500px;">
                        <iframe src="{{ $embedUrl }}" width="100%" height="100%"
                                allow="autoplay" style="border:none; display:block;"></iframe>
                    </div>
                </div>

                <a href="{{ route('member.seminar.posttest.start', $enrollment) }}"
                   class="flex items-center justify-center gap-2 w-full py-3 bg-primary text-white text-sm font-bold rounded-lg hover:bg-primary/90 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    {{ $postTest && $postTest->submitted_at ? 'Ulangi Post-Test' : 'Mulai Post-Test' }}
                </a>
            </div>
        </div>

    {{-- STEP 4: Lulus --}}
    @else
        <div class="bg-white border border-neutral-200 rounded-xl p-8 text-center">
            <div class="w-16 h-16 bg-green-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h2 class="font-bold text-navy text-xl mb-1">Selamat, kamu lulus!</h2>
            @if ($enrollment->certificate)
                <p class="text-sm text-neutral-500 mb-1">
                    Skor akhir: <strong class="text-green-600 text-base">{{ $postTest?->score ?? $enrollment->certificate->score }}</strong>
                </p>
                <p class="text-xs text-neutral-400 mb-6">
                    No. Sertifikat: <span class="font-mono font-semibold text-navy">{{ $enrollment->certificate->certificate_number }}</span>
                </p>
                <div class="flex items-center justify-center gap-3">
                    <a href="{{ route('member.seminar.certificate', $enrollment->certificate) }}"
                       target="_blank"
                       class="inline-flex items-center gap-2 px-6 py-2.5 bg-green-600 text-black text-sm font-bold rounded-lg hover:bg-green-700 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Unduh Sertifikat
                    </a>
                    {{-- Baca ulang materi tetap tersedia --}}
                    <button onclick="openMaterialModal()"
                            class="inline-flex items-center gap-2 px-6 py-2.5 border border-neutral-200 text-neutral-600 text-sm font-bold rounded-lg hover:bg-neutral-50 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253"/>
                        </svg>
                        Baca Materi Lagi
                    </button>
                </div>
            @endif
        </div>
    @endif

</div>

{{-- ── Modal Materi Fullscreen ── --}}
<div id="modal-material"
     class="hidden fixed inset-0 z-50 flex flex-col"
     style="background: rgba(0,0,0,0.92);">

    {{-- Iframe fullscreen — ambil semua ruang --}}
    <div class="flex-1 overflow-hidden">
        <iframe id="material-iframe"
                src=""
                data-src="{{ $embedUrl }}"
                width="100%"
                height="100%"
                allow="autoplay"
                style="border:none; display:block;">
        </iframe>
    </div>

    {{-- Toolbar di BAWAH — selalu terlihat, tidak tertutup bar notifikasi --}}
    <div class="flex-shrink-0 flex items-center justify-between px-5 py-3"
         style="background: #1B3A6B;">
        <div class="flex items-center gap-3">
            <svg class="w-4 h-4 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253"/>
            </svg>
            <div>
                <p class="text-2xs text-white/50">Materi Seminar — hanya bisa dilihat, tidak bisa diunduh atau dicetak</p>
                <p class="text-xs font-bold text-white">{{ $enrollment->seminar->title }}</p>
            </div>
        </div>
        <button onclick="closeMaterialModal()"
                class="flex items-center gap-2 text-sm font-bold px-5 py-2.5 rounded-lg transition"
                style="background: rgba(255,255,255,0.15); color: white;"
                onmouseover="this.style.background='rgba(255,255,255,0.25)'"
                onmouseout="this.style.background='rgba(255,255,255,0.15)'">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            Tutup Materi
        </button>
    </div>
</div>

@push('scripts')
<script>
    function openMaterialModal() {
        const modal  = document.getElementById('modal-material');
        const iframe = document.getElementById('material-iframe');
        // Lazy load iframe src saat modal dibuka
        if (! iframe.src || iframe.src === window.location.href) {
            iframe.src = iframe.dataset.src;
        }
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeMaterialModal() {
        document.getElementById('modal-material').classList.add('hidden');
        document.body.style.overflow = '';
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeMaterialModal();
    });
</script>
@endpush

@endsection