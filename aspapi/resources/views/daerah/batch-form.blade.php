@extends('layouts.daerah')
@php $title = 'Daftar Anggota Baru (Batch)'; @endphp

@section('content')

<div class="max-w-2xl">

    <div class="bg-blue-50 border-l-4 border-primary rounded px-4 py-3 mb-6 text-sm text-blue-800">
        Upload file Excel dengan kolom:
        <strong>Nama Lengkap, Email, No. Telepon, Institusi, Jenis Kelamin (L/P)</strong>.
        Anggota akan langsung mendapatkan email berisi akun mereka tanpa perlu verifikasi email.
    </div>

    <div class="bg-white border border-neutral-200 rounded-lg p-6">
        <form method="POST" action="{{ route('daerah.batch.store') }}" enctype="multipart/form-data">
            @csrf

            {{-- Format info --}}
            <div class="bg-neutral-50 border border-neutral-200 rounded p-4 mb-6">
                <p class="text-xs font-bold text-neutral-500 uppercase tracking-widest mb-2">Format Kolom Excel</p>
                <code class="text-xs text-navy">Nama Lengkap | Email | No. Telepon | Institusi | Jenis Kelamin</code>
                <p class="text-xs text-neutral-400 mt-2">Baris pertama dianggap sebagai header dan akan dilewati.</p>
            </div>

            {{-- Upload --}}
            <div class="mb-6">
                <label class="block text-2xs font-bold tracking-widest uppercase text-neutral-500 mb-2">
                    File Excel (.xlsx / .xls) *
                </label>
                <input type="file" name="file" accept=".xlsx,.xls" required
                       class="w-full text-sm text-neutral-600 file:mr-3 file:py-2 file:px-4 file:rounded file:border-0 file:text-xs file:font-bold file:bg-primary file:text-white hover:file:bg-blue-700 cursor-pointer"/>
                <p class="text-xs text-neutral-400 mt-1.5">Maks 5MB.</p>
            </div>

            @if ($errors->any())
            <div class="alert alert-danger mb-5">
                <ul class="list-disc list-inside space-y-1 text-xs">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="flex gap-3">
                <button type="submit" class="btn btn-primary">
                    Upload dan Daftarkan Anggota
                </button>
                <a href="{{ route('daerah.dashboard') }}" class="btn btn-ghost">Batal</a>
            </div>
        </form>
    </div>

</div>

@endsection