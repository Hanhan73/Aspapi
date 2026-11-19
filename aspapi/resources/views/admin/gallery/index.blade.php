@extends('layouts.admin')
@php $title = 'Galeri'; $breadcrumbs = [['label' => 'Galeri', 'url' => route('admin.gallery.index')]]; @endphp

@section('content')

@php
    $th = 'padding:0.75rem 1rem;text-align:left;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;';
    $field = 'padding:0.5rem 0.875rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;';
@endphp

<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;">
    <div>
        <p style="font-size:0.7rem;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;color:#C0392B;">Kelola Konten</p>
        <h2 style="font-family:'DM Serif Display',serif;font-size:1.5rem;color:#1A2A3A;margin-top:0.125rem;">Galeri Kegiatan</h2>
    </div>
    <a href="{{ route('admin.gallery.create') }}"
       style="display:inline-flex;align-items:center;gap:0.5rem;background:#2A7FC1;color:#fff;padding:0.625rem 1.25rem;border-radius:4px;font-size:0.75rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;text-decoration:none;">
        + Tambah Album
    </a>
</div>

{{-- Filter --}}
<div style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;padding:1rem 1.25rem;margin-bottom:1.25rem;">
    <form method="GET" style="display:flex;gap:0.75rem;flex-wrap:wrap;align-items:center;width:100%;">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Cari judul, tempat, atau jenis kegiatan..."
               style="flex:1;min-width:200px;{{ $field }}"/>
        <select name="year" style="{{ $field }}">
            <option value="">Semua Tahun</option>
            @foreach ($years as $y)
                <option value="{{ $y }}" {{ (string) request('year') === (string) $y ? 'selected' : '' }}>{{ $y }}</option>
            @endforeach
        </select>
        <select name="region" style="{{ $field }}">
            <option value="">Semua Daerah</option>
            <option value="pusat" {{ request('region') === 'pusat' ? 'selected' : '' }}>ASPAPI Pusat</option>
            @foreach ($regions as $r)
                <option value="{{ $r->id }}" {{ (string) request('region') === (string) $r->id ? 'selected' : '' }}>{{ $r->name }}</option>
            @endforeach
        </select>
        <button type="submit"
                style="padding:0.5rem 1rem;background:#2A7FC1;color:#fff;border:none;border-radius:4px;font-size:0.75rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;cursor:pointer;">
            Cari
        </button>
        @if (request('search') || request('year') || request('region'))
        <a href="{{ route('admin.gallery.index') }}"
           style="padding:0.5rem 1rem;border:1.5px solid #D6E8F7;color:#4A6580;border-radius:4px;font-size:0.75rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;text-decoration:none;">
            Reset
        </a>
        @endif
    </form>
</div>

{{-- Table --}}
<div style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;overflow-x:auto;">
    <table style="width:100%;border-collapse:collapse;">
        <thead>
            <tr style="background:#EEF4FB;">
                <th style="{{ $th }}">Album</th>
                <th style="{{ $th }}">Tanggal</th>
                <th style="{{ $th }}">Daerah</th>
                <th style="{{ $th }}">Foto</th>
                <th style="{{ $th }}">Status</th>
                <th style="{{ $th }}">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($albums as $album)
            @php $thumb = $album->coverThumbUrl(); @endphp
            <tr style="border-bottom:1px solid #EEF4FB;" onmouseover="this.style.background='#F8FAFC'" onmouseout="this.style.background='#fff'">
                <td style="padding:0.875rem 1rem;">
                    <div style="display:flex;align-items:center;gap:0.75rem;">
                        @if ($thumb)
                        <img src="{{ $thumb }}" alt="" loading="lazy"
                             style="width:56px;height:44px;object-fit:cover;border-radius:4px;flex-shrink:0;border:1px solid #D6E8F7;"/>
                        @else
                        <div style="width:56px;height:44px;background:#EEF4FB;border-radius:4px;flex-shrink:0;display:flex;align-items:center;justify-content:center;">
                            <svg style="width:18px;height:18px;color:#B0CCDF;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        @endif
                        <div>
                            <p style="font-size:0.85rem;font-weight:600;color:#1A2A3A;line-height:1.4;max-width:320px;">{{ Str::limit($album->title, 60) }}</p>
                            <p style="font-size:0.72rem;color:#7A93AB;margin-top:0.1rem;">{{ $album->activity_type }}@if ($album->place) · {{ $album->place }}@endif</p>
                        </div>
                    </div>
                </td>
                <td style="padding:0.875rem 1rem;font-size:0.8rem;color:#4A6580;white-space:nowrap;">{{ $album->dateLabel() }}</td>
                <td style="padding:0.875rem 1rem;font-size:0.8rem;color:#4A6580;">{{ $album->region->name ?? 'ASPAPI Pusat' }}</td>
                <td style="padding:0.875rem 1rem;font-size:0.8rem;color:#4A6580;">{{ $album->photos_count }}</td>
                <td style="padding:0.875rem 1rem;">
                    <span style="font-size:0.65rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;padding:0.25rem 0.625rem;border-radius:2px;
                        {{ $album->is_published ? 'background:#F0FFF4;color:#276749;' : 'background:#FEF8EC;color:#B8860B;' }}">
                        {{ $album->is_published ? 'Tayang' : 'Draft' }}
                    </span>
                </td>
                <td style="padding:0.875rem 1rem;">
                    <div style="display:flex;gap:0.5rem;">
                        @if ($album->is_published)
                        <a href="{{ route('galleries.show', $album->slug) }}" target="_blank"
                           style="font-size:0.7rem;font-weight:700;padding:0.3rem 0.625rem;border:1.5px solid #D6E8F7;border-radius:3px;color:#4A6580;text-decoration:none;">
                           Lihat
                        </a>
                        @endif
                        <a href="{{ route('admin.gallery.photos.index', $album) }}"
                           style="font-size:0.7rem;font-weight:700;padding:0.3rem 0.625rem;border:1.5px solid #2A7FC1;border-radius:3px;background:#2A7FC1;color:#fff;text-decoration:none;">
                           Foto
                        </a>
                        <a href="{{ route('admin.gallery.edit', $album) }}"
                           style="font-size:0.7rem;font-weight:700;padding:0.3rem 0.625rem;border:1.5px solid #2A7FC1;border-radius:3px;color:#2A7FC1;text-decoration:none;">
                           Edit
                        </a>
                        <form method="POST" action="{{ route('admin.gallery.destroy', $album) }}"
                              onsubmit="return confirm('Hapus album ini beserta {{ $album->photos_count }} fotonya?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    style="font-size:0.7rem;font-weight:700;padding:0.3rem 0.625rem;border:1.5px solid #C0392B;border-radius:3px;color:#C0392B;background:transparent;cursor:pointer;">
                                Hapus
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="padding:3rem;text-align:center;color:#B0CCDF;font-size:0.875rem;">
                    Belum ada album. <a href="{{ route('admin.gallery.create') }}" style="color:#2A7FC1;">Tambah sekarang →</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if ($albums->hasPages())
<div style="margin-top:1rem;display:flex;justify-content:flex-end;">
    {{ $albums->links() }}
</div>
@endif

@endsection
