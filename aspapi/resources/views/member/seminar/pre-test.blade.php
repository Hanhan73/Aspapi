@extends('layouts.member')
@section('title', 'Pre-Test — ' . $enrollment->seminar->title)

@section('content')
<div class="p-6">

    {{-- Breadcrumb --}}
    <div class="mb-5 flex items-center gap-2 text-2xs text-neutral-400">
        <a href="{{ route('member.seminar.my-seminars') }}" class="hover:text-primary">Seminar Saya</a>
        <span>/</span>
        <a href="{{ route('member.seminar.show', $enrollment) }}" class="hover:text-primary">{{ $enrollment->seminar->title }}</a>
        <span>/</span>
        <span class="text-navy font-medium">Pre-Test</span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

        {{-- ── Kolom Kiri: Soal ── --}}
        <div class="lg:col-span-2">
            <form method="POST" action="{{ route('member.seminar.pretest.submit', $attempt) }}" id="pretest-form">
                @csrf
                <div class="space-y-4">
                    @foreach ($questions as $i => $question)
                    <div class="bg-white border border-neutral-200 rounded-xl p-5" id="soal-{{ $i + 1 }}">

                        {{-- Nomor + teks soal --}}
                        <div class="flex items-start gap-3 mb-4">
                            <span class="flex-shrink-0 w-7 h-7 rounded-full bg-primary/10 text-primary text-xs font-extrabold flex items-center justify-center">
                                {{ $i + 1 }}
                            </span>
                            <p class="text-sm font-semibold text-navy leading-relaxed pt-0.5">
                                {{ $question->question }}
                            </p>
                        </div>

                        {{-- Opsi jawaban --}}
                        <div class="space-y-2 ml-10">
                            @foreach ($shuffledOptions[$question->id] as $opt)
                    @php $key = $opt['key']; $label = $opt['label']; @endphp
                            <label class="flex items-start gap-3 p-3 rounded-lg border border-neutral-200 cursor-pointer
                                          hover:bg-primary/5 hover:border-primary/30
                                          has-[:checked]:bg-primary/10 has-[:checked]:border-primary
                                          transition-all">
                                <input type="radio"
                                       name="answers[{{ $question->id }}]"
                                       value="{{ $key }}"
                                       class="mt-0.5 accent-primary flex-shrink-0"
                                       onchange="markAnswered({{ $i + 1 }})">
                                <span class="text-sm text-navy">
                                    <span class="font-bold uppercase mr-1"></span>{{ $label }}
                                </span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Submit --}}
                <div class="mt-5 flex justify-end">
                    <button type="button"
                            onclick="confirmSubmit()"
                            class="px-8 py-2.5 bg-primary text-white text-sm font-bold rounded-lg hover:bg-primary/90 transition">
                        Submit Pre-Test
                    </button>
                </div>
            </form>
        </div>

        {{-- ── Kolom Kanan: Info & navigasi soal ── --}}
        <div class="space-y-4 lg:sticky lg:top-6">

            {{-- Info seminar --}}
            <div class="bg-white border border-neutral-200 rounded-xl p-5">
                <p class="text-xs font-bold text-neutral-500 uppercase tracking-wider mb-3">Pre-Test</p>
                <h2 class="font-bold text-navy text-sm mb-1">{{ $enrollment->seminar->title }}</h2>
                <div class="mt-3 space-y-2 text-xs text-neutral-500">
                    <div class="flex justify-between">
                        <span>Jumlah Soal</span>
                        <span class="font-bold text-navy">{{ count($questions) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Pengaruh Kelulusan</span>
                        <span class="font-bold text-amber-600">Tidak</span>
                    </div>
                </div>
                <div class="mt-3 p-3 bg-amber-50 border border-amber-100 rounded-lg text-2xs text-amber-700 leading-relaxed">
                    Pre-test hanya untuk mengukur pemahamanmu sebelum materi. Jawab semua soal lalu klik Submit.
                </div>
            </div>

            {{-- Navigasi soal --}}
            <div class="bg-white border border-neutral-200 rounded-xl p-5">
                <p class="text-xs font-bold text-neutral-500 uppercase tracking-wider mb-3">Navigasi Soal</p>
                <div class="grid grid-cols-5 gap-2">
                    @foreach ($questions as $i => $question)
                    <button type="button"
                            id="nav-{{ $i + 1 }}"
                            onclick="scrollToSoal({{ $i + 1 }})"
                            class="w-full aspect-square rounded-lg text-xs font-bold border border-neutral-200 text-neutral-400 hover:border-primary hover:text-primary transition-all">
                        {{ $i + 1 }}
                    </button>
                    @endforeach
                </div>
                <div class="flex items-center gap-3 mt-3 text-2xs text-neutral-400">
                    <div class="flex items-center gap-1.5">
                        <div class="w-3 h-3 rounded bg-primary"></div> Sudah dijawab
                    </div>
                    <div class="flex items-center gap-1.5">
                        <div class="w-3 h-3 rounded border border-neutral-200"></div> Belum
                    </div>
                </div>
            </div>

            {{-- Tombol submit sticky --}}
            <button type="button"
                    onclick="confirmSubmit()"
                    class="w-full py-3 bg-primary text-white text-sm font-bold rounded-xl hover:bg-primary/90 transition">
                Submit Pre-Test
            </button>
        </div>

    </div>
</div>

{{-- Modal konfirmasi submit --}}
<div id="modal-submit"
     class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm px-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6 text-center">
        <div class="w-12 h-12 bg-amber-50 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>
        <h3 class="font-bold text-navy text-base mb-2">Submit Pre-Test?</h3>
        <p class="text-sm text-neutral-500 mb-1" id="modal-answered-info"></p>
        <p class="text-xs text-neutral-400 mb-6">Pre-test tidak bisa diulang setelah disubmit.</p>
        <div class="flex gap-3">
            <button type="button"
                    onclick="document.getElementById('modal-submit').classList.add('hidden')"
                    class="flex-1 py-2.5 text-xs font-bold border border-neutral-200 rounded-lg text-neutral-600 hover:bg-neutral-50 transition">
                Cek Lagi
            </button>
            <button type="button"
                    onclick="document.getElementById('pretest-form').submit()"
                    class="flex-1 py-2.5 text-xs font-bold bg-primary text-white rounded-lg hover:bg-primary/90 transition">
                Ya, Submit
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const totalSoal   = {{ count($questions) }};
    const answered    = new Set();

    function markAnswered(nomor) {
        answered.add(nomor);
        const btn = document.getElementById('nav-' + nomor);
        if (btn) {
            btn.classList.remove('border-neutral-200', 'text-neutral-400');
            btn.classList.add('bg-primary', 'text-white', 'border-primary');
        }
    }

    function scrollToSoal(nomor) {
        const el = document.getElementById('soal-' + nomor);
        if (el) el.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    function confirmSubmit() {
        const belum = totalSoal - answered.size;
        const info  = document.getElementById('modal-answered-info');
        if (belum > 0) {
            info.textContent = answered.size + ' dari ' + totalSoal + ' soal dijawab. ' + belum + ' soal belum dijawab.';
            info.classList.add('text-orange-500');
        } else {
            info.textContent = 'Semua ' + totalSoal + ' soal sudah dijawab.';
            info.classList.remove('text-orange-500');
        }
        document.getElementById('modal-submit').classList.remove('hidden');
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') document.getElementById('modal-submit').classList.add('hidden');
    });
</script>
@endpush

@endsection