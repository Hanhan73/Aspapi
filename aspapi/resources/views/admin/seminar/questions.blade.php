@extends('layouts.admin')
@section('title', 'Soal — ' . $seminar->title)

@section('content')
<div class="p-6">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <a href="{{ route('admin.seminar.index') }}" class="text-xs text-neutral-400 hover:text-primary">← Kembali</a>
            <h1 class="text-xl font-extrabold text-navy mt-1">Bank Soal</h1>
            <p class="text-sm text-neutral-500">{{ $seminar->title }}</p>
        </div>
        <div class="flex items-end gap-6">
            <div class="text-right">
                <p class="text-2xs text-neutral-400">Total soal</p>
                <p class="text-2xl font-extrabold text-navy">{{ $questions->total() }}</p>
                <p class="text-2xs {{ $questions->total() < 5 ? 'text-red-500 font-bold' : 'text-neutral-400' }}">
                    {{ $questions->total() < 5 ? '⚠ min. 5 untuk pre-test' : 'min. 5 ✓' }}
                </p>
            </div>
            {{-- Tombol Import + Download Template --}}
            <div class="flex gap-2">
                <a href="{{ route('admin.seminar.template') }}"
                   class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-2 border border-neutral-200 rounded-lg text-neutral-600 hover:bg-neutral-50 transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Template Excel
                </a>
                <button onclick="document.getElementById('modal-import').classList.remove('hidden')"
                        class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l4-4m0 0l4 4m-4-4v12"/>
                    </svg>
                    Import Excel
                </button>
            </div>
        </div>
    </div>

    {{-- Flash messages --}}
    @if (session('success'))
        <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif
    @if (session('warning'))
        <div class="mb-4 px-4 py-3 bg-amber-50 border border-amber-200 rounded-lg text-sm text-amber-700">
            {{ session('warning') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700 space-y-1">
            @foreach ($errors->all() as $e) <p>{{ $e }}</p> @endforeach
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

        {{-- ── Form tambah soal ── --}}
        <div class="lg:col-span-2">
            <div class="bg-white border border-neutral-200 rounded-xl p-5 sticky top-6">
                <h2 class="font-bold text-navy text-sm mb-4">Tambah Soal Baru</h2>

                <form method="POST" action="{{ route('admin.seminar.questions.store', $seminar) }}">
                    @csrf
                    @include('admin.seminar._question-form', ['q' => null, 'submitLabel' => 'Tambah Soal'])
                </form>
            </div>
        </div>

        {{-- ── Daftar soal ── --}}
        <div class="lg:col-span-3 space-y-4">
            @forelse ($questions as $i => $q)
            <div class="bg-white border border-neutral-200 rounded-xl overflow-hidden"
                 x-data="{ editing: false }">

                {{-- Mode tampil --}}
                <div x-show="!editing" class="p-5">
                    <div class="flex items-start justify-between gap-3 mb-3">
                        <p class="text-sm font-semibold text-navy flex-1">
                            {{ $questions->firstItem() + $i }}. {{ $q->question }}
                        </p>
                        <div class="flex gap-2 flex-shrink-0">
                            <button @click="editing = true"
                                    class="text-2xs font-bold px-2.5 py-1 bg-primary/10 text-primary rounded-lg hover:bg-primary/20 transition">
                                Edit
                            </button>
                            <form method="POST" action="{{ route('admin.seminar.questions.destroy', $q) }}"
                                  onsubmit="return confirm('Hapus soal ini?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="text-2xs font-bold px-2.5 py-1 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach ($q->getOptions() as $key => $opt)
                        <div class="text-xs px-3 py-2 rounded-lg
                            {{ $q->correct_answer === $key
                                ? 'bg-green-50 border border-green-200 text-green-700 font-bold'
                                : 'bg-neutral-50 border border-neutral-200 text-neutral-600' }}">
                            <span class="font-bold uppercase">{{ $key }}.</span> {{ $opt }}
                            @if ($q->correct_answer === $key)
                                <span class="ml-1 text-green-500">✓</span>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Mode edit --}}
                <div x-show="editing" x-cloak class="p-5 bg-blue-50/40 border-t-2 border-primary/20">
                    <div class="flex items-center justify-between mb-3">
                        <p class="text-xs font-bold text-primary uppercase tracking-wide">Mode Edit</p>
                        <button @click="editing = false" class="text-2xs text-neutral-400 hover:text-neutral-600">
                            ✕ Batal
                        </button>
                    </div>
                    <form method="POST" action="{{ route('admin.seminar.questions.update', $q) }}">
                        @csrf @method('PUT')
                        @include('admin.seminar._question-form', ['q' => $q, 'submitLabel' => 'Simpan Perubahan'])
                    </form>
                </div>
            </div>
            @empty
            <div class="text-center py-16 text-neutral-400 text-sm">
                <svg class="w-10 h-10 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Belum ada soal. Tambahkan dari form di sebelah kiri,<br>atau import dari Excel.
            </div>
            @endforelse

            @if ($questions->hasPages())
            <div>{{ $questions->links() }}</div>
            @endif
        </div>
    </div>
</div>

{{-- ── Modal Import Excel ── --}}
<div id="modal-import"
     class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-navy">Import Soal dari Excel</h3>
            <button onclick="document.getElementById('modal-import').classList.add('hidden')"
                    class="text-neutral-400 hover:text-neutral-600 text-xl leading-none">✕</button>
        </div>

        <div class="mb-4 p-3 bg-blue-50 border border-blue-100 rounded-lg text-xs text-blue-700 space-y-1">
            <p class="font-bold">Perhatikan:</p>
            <p>• Gunakan file template yang sudah didownload.</p>
            <p>• Data soal diisi mulai baris ke-4 di sheet "Soal".</p>
            <p>• Soal yang sudah ada tidak akan ditimpa, hanya ditambahkan.</p>
        </div>

        <form method="POST"
              action="{{ route('admin.seminar.import', $seminar) }}"
              enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label class="block text-xs font-bold text-neutral-600 mb-1.5">
                    Pilih File Excel (.xlsx)
                </label>
                <input type="file" name="excel_file" accept=".xlsx,.xls" required
                       class="block w-full text-sm text-neutral-500
                              file:mr-3 file:text-xs file:font-bold
                              file:border-0 file:bg-primary/10 file:text-primary
                              file:rounded-lg file:px-3 file:py-1.5 file:cursor-pointer">
            </div>
            <div class="flex gap-3 justify-end">
                <button type="button"
                        onclick="document.getElementById('modal-import').classList.add('hidden')"
                        class="px-4 py-2 text-xs font-bold border border-neutral-200 rounded-lg text-neutral-600 hover:bg-neutral-50">
                    Batal
                </button>
                <button type="submit"
                        class="px-5 py-2 text-xs font-bold bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                    Upload & Import
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Tutup modal jika klik backdrop
    document.getElementById('modal-import').addEventListener('click', function(e) {
        if (e.target === this) this.classList.add('hidden');
    });
</script>
@endpush

@endsection