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
        $materials = $enrollment->seminar->materials;
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

                    {{-- Deskripsi: render sebagai HTML dari rich editor --}}
                    @if ($enrollment->seminar->description)
                    <div class="seminar-desc text-sm text-neutral-500 mt-2 leading-relaxed line-clamp-3">
                        {!! $enrollment->seminar->description !!}
                    </div>
                    @endif
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
            <div class="flex items-center justify-between px-5 py-4 border-b border-neutral-100">
                <div>
                    <h2 class="font-bold text-navy text-sm">Materi Seminar</h2>
                    <p class="text-xs text-neutral-400 mt-0.5">
                        Baca semua materi berikut sebelum mengerjakan post-test.
                        ({{ $materials->count() }} materi)
                    </p>
                </div>
                @if ($preTest)
                <span class="text-2xs px-2.5 py-1 bg-indigo-50 text-indigo-700 rounded-full font-bold flex-shrink-0">
                    Pre-test: {{ $preTest->score }}
                </span>
                @endif
            </div>

            <div class="divide-y divide-neutral-100" x-data="{ active: 0 }">
                @foreach ($materials as $i => $material)
                @php $embedUrl = $material->embed_url; @endphp
                <div>
                    <button type="button"
                            @click="active = {{ $i }}"
                            class="w-full flex items-center gap-3 px-5 py-3.5 text-left hover:bg-neutral-50 transition"
                            :class="active === {{ $i }} ? 'bg-primary/5' : ''">
                        <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0 bg-primary/10 text-primary">
                            {{ $i + 1 }}
                        </div>
                        <span class="text-sm font-semibold text-navy flex-1 text-left">{{ $material->label }}</span>
                        <div class="flex items-center gap-2 flex-shrink-0">
                            <button type="button"
                                    @click.stop="openMaterialModal('{{ addslashes($embedUrl) }}', '{{ addslashes($material->label) }}')"
                                    class="text-2xs font-bold px-2 py-1 border border-neutral-200 rounded text-neutral-500 hover:bg-white transition">
                                Fullscreen
                            </button>
                            <svg class="w-4 h-4 text-neutral-400 transition-transform"
                                 :class="active === {{ $i }} ? 'rotate-180' : ''"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </button>
                    <div x-show="active === {{ $i }}" style="height: 520px;">
                        <iframe src="{{ $embedUrl }}" width="100%" height="100%"
                                allow="autoplay" style="border:none; display:block;"></iframe>
                    </div>
                </div>
                @endforeach
            </div>

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
                        belum mencapai passing grade. Anda bisa mencoba lagi.
                    </p>
                </div>
                @endif

                @if ($materials->isNotEmpty())
                <div class="mb-5 border border-neutral-200 rounded-xl overflow-hidden"
                     x-data="{ open: false, activeTab: 0 }">
                    <button @click="open = !open"
                            class="w-full flex items-center justify-between px-4 py-3 bg-neutral-50 hover:bg-neutral-100 transition text-left">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253"/>
                            </svg>
                            <span class="text-xs font-bold text-neutral-600">
                                Baca ulang materi ({{ $materials->count() }} materi)
                            </span>
                        </div>
                        <svg class="w-4 h-4 text-neutral-400 transition-transform" :class="open ? 'rotate-180' : ''"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-transition>
                        @if ($materials->count() > 1)
                        <div class="flex gap-1 px-4 py-2 bg-neutral-50 border-t border-neutral-100 flex-wrap">
                            @foreach ($materials as $i => $material)
                            <button type="button"
                                    @click="activeTab = {{ $i }}"
                                    class="text-2xs font-bold px-3 py-1.5 rounded-full border transition"
                                    :class="activeTab === {{ $i }}
                                        ? 'bg-primary text-white border-primary'
                                        : 'border-neutral-200 text-neutral-500 hover:border-primary hover:text-primary'">
                                {{ $i + 1 }}. {{ Str::limit($material->label, 30) }}
                            </button>
                            @endforeach
                        </div>
                        @endif
                        @foreach ($materials as $i => $material)
                        <div x-show="activeTab === {{ $i }}" class="relative" style="height: 460px;">
                            <button type="button"
                                    @click.stop="openMaterialModal('{{ addslashes($material->embed_url) }}', '{{ addslashes($material->label) }}')"
                                    class="absolute top-2 right-2 z-10 text-2xs font-bold px-2 py-1 bg-white border border-neutral-200 rounded shadow text-neutral-600 hover:bg-neutral-50 transition">
                                Fullscreen
                            </button>
                            <iframe src="{{ $material->embed_url }}" width="100%" height="100%"
                                    allow="autoplay" style="border:none; display:block;"></iframe>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

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
            <h2 class="font-bold text-navy text-xl mb-1">Selamat, Anda lulus!</h2>
            @if ($enrollment->certificate)
                <p class="text-sm text-neutral-500 mb-1">
                    Skor akhir: <strong class="text-green-600 text-base">{{ $postTest?->score ?? $enrollment->certificate->score }}</strong>
                </p>
                <p class="text-xs text-neutral-400 mb-6">
                    No. Sertifikat: <span class="font-mono font-semibold text-navy">{{ $enrollment->certificate->certificate_number }}</span>
                </p>
                <div class="flex items-center justify-center gap-3 flex-wrap">
                    <a href="{{ route('member.seminar.certificate', $enrollment->certificate) }}"
                       target="_blank"
                       class="inline-flex items-center gap-2 px-6 py-2.5 bg-green-600 text-black text-sm font-bold rounded-lg hover:bg-green-700 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Unduh Sertifikat
                    </a>
                    @if ($materials->isNotEmpty())
                    <button onclick="openMaterialModal('{{ addslashes($materials->first()->embed_url) }}', '{{ addslashes($materials->first()->label) }}')"
                            class="inline-flex items-center gap-2 px-6 py-2.5 border border-neutral-200 text-neutral-600 text-sm font-bold rounded-lg hover:bg-neutral-50 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253"/>
                        </svg>
                        Baca Materi Lagi
                    </button>
                    @endif
                </div>
            @endif
        </div>
    @endif

</div>

{{-- ── Modal Materi Fullscreen ── --}}
<div id="modal-material"
     class="hidden fixed inset-0 z-50 flex flex-col"
     style="background: rgba(0,0,0,0.92);">

    <div class="flex-1 overflow-hidden">
        <iframe id="material-iframe" src="" width="100%" height="100%"
                allow="autoplay" style="border:none; display:block;"></iframe>
    </div>

    <div class="flex-shrink-0 flex flex-col gap-0" style="background: #1B3A6B;">
        @if ($materials->count() > 1)
        <div class="flex items-center gap-2 px-5 py-2 border-b border-white/10 overflow-x-auto flex-shrink-0">
            @foreach ($materials as $i => $material)
            <button type="button" id="modal-tab-{{ $i }}" onclick="modalGoTo({{ $i }})"
                    class="flex-shrink-0 text-2xs font-bold px-3 py-1.5 rounded-full border transition whitespace-nowrap"
                    style="border-color: rgba(255,255,255,0.2); color: rgba(255,255,255,0.6);">
                {{ $i + 1 }}. {{ Str::limit($material->label, 35) }}
            </button>
            @endforeach
        </div>
        @endif

        <div class="flex items-center justify-between px-5 py-3">
            <div class="flex items-center gap-3">
                <svg class="w-4 h-4 text-white/50 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253"/>
                </svg>
                <div>
                    <p class="text-2xs text-white">Materi Seminar — hanya bisa dilihat, tidak bisa diunduh</p>
                    <p class="text-xs font-bold text-white" id="modal-material-label"></p>
                </div>
            </div>
            <div class="flex items-center gap-2 flex-shrink-0">
                @if ($materials->count() > 1)
                <button type="button" onclick="modalPrev()" id="modal-btn-prev"
                        class="flex items-center gap-1 text-xs font-bold px-3 py-2 rounded-lg transition"
                        style="background: rgba(255,255,255,0.12); color: white;">← Sebelumnya</button>
                <span class="text-xs text-white/40" id="modal-counter"></span>
                <button type="button" onclick="modalNext()" id="modal-btn-next"
                        class="flex items-center gap-1 text-xs font-bold px-3 py-2 rounded-lg transition"
                        style="background: rgba(255,255,255,0.12); color: white;">Berikutnya →</button>
                @endif
                <button onclick="closeMaterialModal()"
                        class="flex items-center gap-2 text-sm font-bold px-5 py-2 rounded-lg transition ml-2"
                        style="background: rgba(255,255,255,0.15); color: white;"
                        onmouseover="this.style.background='rgba(255,255,255,0.25)'"
                        onmouseout="this.style.background='rgba(255,255,255,0.15)'">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
/* Styling HTML dari rich editor di header seminar */
.seminar-desc p          { margin-bottom: 0.5em; }
.seminar-desc p:last-child { margin-bottom: 0; }
.seminar-desc strong     { font-weight: 700; }
.seminar-desc em         { font-style: italic; }
.seminar-desc ul         { list-style: disc; padding-left: 1.25rem; margin-bottom: 0.5em; }
.seminar-desc ol         { list-style: decimal; padding-left: 1.25rem; margin-bottom: 0.5em; }
.seminar-desc li         { margin-bottom: 0.2em; }
</style>
@endpush

@push('scripts')
<script>
const MODAL_MATERIALS = @json($materials->map(fn($m) => [
    'label'    => $m->label,
    'embedUrl' => $m->embed_url,
])->values());

let modalCurrentIdx = 0;

function openMaterialModal(embedUrl, label) {
    const idx = MODAL_MATERIALS.findIndex(m => m.embedUrl === embedUrl);
    modalGoTo(idx >= 0 ? idx : 0);
    document.getElementById('modal-material').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeMaterialModal() {
    document.getElementById('modal-material').classList.add('hidden');
    document.getElementById('material-iframe').src = '';
    document.body.style.overflow = '';
}

function modalGoTo(idx) {
    if (idx < 0 || idx >= MODAL_MATERIALS.length) return;
    modalCurrentIdx = idx;
    const mat = MODAL_MATERIALS[idx];
    document.getElementById('material-iframe').src = mat.embedUrl;
    document.getElementById('modal-material-label').textContent = mat.label;
    const counter = document.getElementById('modal-counter');
    if (counter) counter.textContent = (idx + 1) + ' / ' + MODAL_MATERIALS.length;
    const btnPrev = document.getElementById('modal-btn-prev');
    const btnNext = document.getElementById('modal-btn-next');
    if (btnPrev) { btnPrev.style.opacity = idx === 0 ? '0.3' : '1'; btnPrev.style.pointerEvents = idx === 0 ? 'none' : 'auto'; }
    if (btnNext) { btnNext.style.opacity = idx === MODAL_MATERIALS.length - 1 ? '0.3' : '1'; btnNext.style.pointerEvents = idx === MODAL_MATERIALS.length - 1 ? 'none' : 'auto'; }
    MODAL_MATERIALS.forEach(function (_, i) {
        const tab = document.getElementById('modal-tab-' + i);
        if (!tab) return;
        if (i === idx) { tab.style.background = 'rgba(255,255,255,0.2)'; tab.style.color = 'white'; tab.style.borderColor = 'rgba(255,255,255,0.5)'; }
        else { tab.style.background = 'transparent'; tab.style.color = 'rgba(255,255,255,0.6)'; tab.style.borderColor = 'rgba(255,255,255,0.2)'; }
    });
}

function modalPrev() { modalGoTo(modalCurrentIdx - 1); }
function modalNext() { modalGoTo(modalCurrentIdx + 1); }

document.addEventListener('keydown', function (e) {
    if (document.getElementById('modal-material').classList.contains('hidden')) return;
    if (e.key === 'Escape')     closeMaterialModal();
    if (e.key === 'ArrowRight') modalNext();
    if (e.key === 'ArrowLeft')  modalPrev();
});
</script>
@endpush
@endsection