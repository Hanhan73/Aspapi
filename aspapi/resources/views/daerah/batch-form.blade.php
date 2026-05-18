@extends('layouts.daerah')
@php $title = 'Daftar Anggota Baru (Batch)'; @endphp

@section('content')

<div class="max-w-2xl">

    {{-- Flash success --}}
    @if (session('success'))
    <div style="background:#F0FFF4;border-left:4px solid #276749;border-radius:4px;padding:0.875rem 1.25rem;margin-bottom:1.25rem;font-size:0.875rem;color:#276749;">
        {{ session('success') }}
    </div>
    @endif

    {{-- Error per baris --}}
    @if (session('batch_errors'))
    <div style="background:#FDECEA;border-left:4px solid #C0392B;border-radius:4px;padding:0.875rem 1.25rem;margin-bottom:1.25rem;">
        <p style="font-size:0.8rem;font-weight:700;color:#922B21;margin:0 0 0.5rem;">Baris yang gagal didaftarkan:</p>
        <ul style="margin:0;padding-left:1.25rem;">
            @foreach (session('batch_errors') as $err)
            <li style="font-size:0.78rem;color:#922B21;margin-bottom:0.2rem;">{{ $err }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Petunjuk --}}
    <div class="bg-blue-50 border-l-4 border-primary rounded px-4 py-3 mb-6 text-sm text-blue-800">
        Upload file Excel dengan data anggota. Setiap anggota akan langsung mendapat email berisi akun login
        tanpa perlu verifikasi email terlebih dahulu.
    </div>

    <div class="bg-white border border-neutral-200 rounded-lg p-6">

        {{-- Format kolom --}}
        <div class="bg-neutral-50 border border-neutral-200 rounded p-4 mb-6">
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs font-bold text-neutral-500 uppercase tracking-widest">Format Kolom Excel</p>
                {{-- Download template --}}
                <a href="{{ route('daerah.batch.template') }}"
                   style="font-size:0.7rem;font-weight:700;padding:0.25rem 0.75rem;background:#2A7FC1;color:#fff;border-radius:4px;text-decoration:none;">
                    ⬇ Download Template
                </a>
            </div>
            <div style="overflow-x:auto;">
                <table style="width:100%;border-collapse:collapse;font-size:0.75rem;">
                    <thead>
                        <tr style="background:#E2EDF7;">
                            <th style="padding:0.4rem 0.75rem;text-align:left;color:#2A7FC1;font-weight:700;white-space:nowrap;">A — Nama Lengkap *</th>
                            <th style="padding:0.4rem 0.75rem;text-align:left;color:#2A7FC1;font-weight:700;white-space:nowrap;">B — Email *</th>
                            <th style="padding:0.4rem 0.75rem;text-align:left;color:#2A7FC1;font-weight:700;white-space:nowrap;">C — No. Telepon</th>
                            <th style="padding:0.4rem 0.75rem;text-align:left;color:#2A7FC1;font-weight:700;white-space:nowrap;">D — Institusi</th>
                            <th style="padding:0.4rem 0.75rem;text-align:left;color:#2A7FC1;font-weight:700;white-space:nowrap;">E — Gender (L/P)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr style="border-top:1px solid #D6E8F7;">
                            <td style="padding:0.4rem 0.75rem;color:#4A6580;">Budi Santoso</td>
                            <td style="padding:0.4rem 0.75rem;color:#4A6580;">budi@email.com</td>
                            <td style="padding:0.4rem 0.75rem;color:#4A6580;">08123456789</td>
                            <td style="padding:0.4rem 0.75rem;color:#4A6580;">Universitas XYZ</td>
                            <td style="padding:0.4rem 0.75rem;color:#4A6580;">L</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <p class="text-xs text-neutral-400 mt-2">* Wajib diisi. Baris pertama (header) otomatis dilewati.</p>
        </div>

        {{-- Form upload --}}
        <form method="POST" action="{{ route('daerah.batch.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="mb-6">
                <label class="block text-2xs font-bold tracking-widest uppercase text-neutral-500 mb-2">
                    File Excel (.xlsx / .xls) *
                </label>
                <input type="file" name="file" id="file-input" accept=".xlsx,.xls" required
                       class="w-full text-sm text-neutral-600 file:mr-3 file:py-2 file:px-4 file:rounded file:border-0 file:text-xs file:font-bold file:bg-primary file:text-white hover:file:bg-blue-700 cursor-pointer"/>
                <p class="text-xs text-neutral-400 mt-1.5">Maks 5MB. Format .xlsx atau .xls.</p>
            </div>

            {{-- Preview nama file --}}
            <div id="file-preview" class="hidden mb-4 text-xs text-neutral-500 bg-neutral-50 border border-neutral-200 rounded px-3 py-2">
                📄 <span id="file-name"></span>
            </div>

            @error('file')
            <div class="alert alert-danger mb-4">{{ $message }}</div>
            @enderror

            <div class="flex gap-3">
                <button type="submit"
                        class="btn btn-primary">
                    Upload dan Daftarkan Anggota
                </button>
                <a href="{{ route('daerah.dashboard') }}" class="btn btn-ghost">Batal</a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('file-input').addEventListener('change', function() {
    const preview = document.getElementById('file-preview');
    const name    = document.getElementById('file-name');
    if (this.files.length > 0) {
        name.textContent = this.files[0].name;
        preview.classList.remove('hidden');
    } else {
        preview.classList.add('hidden');
    }
});
</script>
@endpush

@endsection