@extends('layouts.admin')
@php
    $title = 'Edit Album';
    $breadcrumbs = [
        ['label' => 'Galeri', 'url' => route('admin.gallery.index')],
        ['label' => 'Edit', 'url' => '#'],
    ];
@endphp

@section('content')

{{-- Ringkasan foto --}}
<div style="background:#EEF4FB;border:1px solid #D6E8F7;border-radius:6px;padding:0.875rem 1.25rem;margin-bottom:1.25rem;display:flex;align-items:center;justify-content:space-between;gap:1rem;flex-wrap:wrap;">
    <p style="font-size:0.85rem;color:#1A2A3A;">
        <strong>{{ $album->photos_count }}</strong> foto di album ini ·
        <span style="color:#4A6580;">{{ $album->is_published ? 'Tayang' : 'Draft' }}</span>
    </p>
    <a href="{{ route('admin.gallery.photos.index', $album) }}"
       style="display:inline-flex;align-items:center;gap:0.5rem;background:#2A7FC1;color:#fff;padding:0.5rem 1rem;border-radius:4px;font-size:0.72rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;text-decoration:none;">
        Kelola Foto →
    </a>
</div>

<form method="POST" action="{{ route('admin.gallery.update', $album) }}">
    @csrf @method('PUT')
    @include('admin.gallery._form')
</form>

{{-- Form hapus di luar form utama agar tidak nested --}}
<form method="POST" action="{{ route('admin.gallery.destroy', $album) }}"
      onsubmit="return confirm('Hapus album ini beserta semua fotonya secara permanen?')"
      style="margin-top:0.75rem;">
    @csrf @method('DELETE')
    <button type="submit"
            style="width:100%;padding:0.625rem;background:transparent;border:1.5px solid #C0392B;border-radius:4px;font-size:0.75rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#C0392B;cursor:pointer;">
        Hapus Album
    </button>
</form>

@endsection
