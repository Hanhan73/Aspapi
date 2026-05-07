@extends('layouts.admin')
@php $title = 'Daftar Anggota Baru (Batch)'; @endphp

@section('content')

<div style="background:#EEF4FB;border-left:4px solid #2A7FC1;border-radius:4px;padding:1rem 1.25rem;margin-bottom:1.5rem;font-size:0.825rem;color:#1A5F9A;">
    Upload file Excel dengan kolom: <strong>Nama Lengkap, Email, No. Telepon, Institusi, Jenis Kelamin (L/P)</strong>. Anggota akan langsung mendapatkan email berisi akun mereka tanpa perlu verifikasi email.
</div>

<div style="background:#fff;border:1px solid #D6E8F7;border-radius:8px;padding:1.5rem;max-width:600px;">
    <form method="POST" action="{{ route('daerah.batch.store') }}" enctype="multipart/form-data">
        @csrf

        <div style="margin-bottom:1.5rem;">
            <label style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.5rem;">
                File Excel (.xlsx / .xls) *
            </label>
            <input type="file" name="file" accept=".xlsx,.xls" required
                   style="width:100%;font-size:0.875rem;color:#4A6580;padding:0.5rem 0;"/>
            <p style="font-size:0.72rem;color:#B0CCDF;margin-top:0.375rem;">Maks 5MB. Baris pertama dianggap sebagai header dan akan dilewati.</p>
        </div>

        {{-- Template Download --}}
        <div style="background:#F8FAFC;border:1px solid #D6E8F7;border-radius:4px;padding:1rem;margin-bottom:1.5rem;">
            <p style="font-size:0.75rem;font-weight:700;color:#4A6580;margin-bottom:0.375rem;">Format Kolom Excel:</p>
            <p style="font-size:0.75rem;color:#4A6580;font-family:monospace;">Nama Lengkap | Email | No. Telepon | Institusi | Jenis Kelamin</p>
        </div>

        <button type="submit"
                style="padding:0.875rem 2rem;background:#2A7FC1;color:#fff;border:none;border-radius:4px;font-size:0.75rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;cursor:pointer;">
            Upload dan Daftarkan Anggota
        </button>
    </form>
</div>

@endsection