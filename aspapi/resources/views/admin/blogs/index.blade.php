@extends('layouts.admin')
@php $title = 'Blog'; $breadcrumbs = [['label' => 'Blog', 'url' => route('admin.blogs.index')]]; @endphp

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;">
    <div>
        <p style="font-size:0.7rem;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;color:#C0392B;">Kelola Konten</p>
        <h2 style="font-family:'DM Serif Display',serif;font-size:1.5rem;color:#1A2A3A;margin-top:0.125rem;">Blog ASPAPI</h2>
    </div>
    <a href="{{ route('admin.blogs.create') }}"
       style="display:inline-flex;align-items:center;gap:0.5rem;background:#2A7FC1;color:#fff;padding:0.625rem 1.25rem;border-radius:4px;font-size:0.75rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;text-decoration:none;">
        + Tambah Blog
    </a>
</div>

{{-- Filter --}}
<div style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;padding:1rem 1.25rem;margin-bottom:1.25rem;">
    <form method="GET" style="display:flex;gap:0.75rem;flex-wrap:wrap;align-items:center;">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Cari judul blog..."
               style="flex:1;min-width:200px;padding:0.5rem 0.875rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;"/>
        <select name="status"
                style="padding:0.5rem 0.875rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;">
            <option value="">Semua Status</option>
            <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Tayang</option>
            <option value="draft"     {{ request('status') === 'draft'     ? 'selected' : '' }}>Draft</option>
        </select>
        <button type="submit"
                style="padding:0.5rem 1rem;background:#2A7FC1;color:#fff;border:none;border-radius:4px;font-size:0.75rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;cursor:pointer;">
            Cari
        </button>
        @if(request('search') || request('status'))
        <a href="{{ route('admin.blogs.index') }}"
           style="padding:0.5rem 1rem;border:1.5px solid #D6E8F7;color:#4A6580;border-radius:4px;font-size:0.75rem;font-weight:700;text-decoration:none;">
            Reset
        </a>
        @endif
    </form>
</div>

{{-- Table --}}
<div style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;overflow:hidden;">
    <table style="width:100%;border-collapse:collapse;">
        <thead>
            <tr style="background:#EEF4FB;">
                <th style="padding:0.75rem 1rem;text-align:left;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">Judul</th>
                <th style="padding:0.75rem 1rem;text-align:left;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">Penulis</th>
                <th style="padding:0.75rem 1rem;text-align:left;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">Kategori</th>
                <th style="padding:0.75rem 1rem;text-align:left;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">Status</th>
                <th style="padding:0.75rem 1rem;text-align:left;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">Tanggal</th>
                <th style="padding:0.75rem 1rem;text-align:left;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($blogs as $item)
            <tr style="border-bottom:1px solid #EEF4FB;" onmouseover="this.style.background='#F8FAFC'" onmouseout="this.style.background='#fff'">
                <td style="padding:0.875rem 1rem;">
                    <div style="display:flex;align-items:center;gap:0.75rem;">
                        @if ($item->thumbnail)
                        <img src="{{ Storage::url($item->thumbnail) }}"
                             style="width:44px;height:44px;object-fit:cover;border-radius:4px;flex-shrink:0;border:1px solid #D6E8F7;"/>
                        @else
                        <div style="width:44px;height:44px;background:#EEF4FB;border-radius:4px;flex-shrink:0;display:flex;align-items:center;justify-content:center;">
                            <svg style="width:18px;height:18px;color:#B0CCDF;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        @endif
                        <div>
                            <p style="font-size:0.85rem;font-weight:600;color:#1A2A3A;line-height:1.4;max-width:280px;">{{ Str::limit($item->title, 55) }}</p>
                            <p style="font-size:0.72rem;color:#B0CCDF;margin-top:0.1rem;">{{ $item->views }} views</p>
                        </div>
                    </div>
                </td>
                <td style="padding:0.875rem 1rem;font-size:0.8rem;color:#4A6580;">
                    {{ $item->author_name ?? auth()->user()->name ?? '—' }}
                </td>
                <td style="padding:0.875rem 1rem;">
                    @if ($item->category)
                    <span style="font-size:0.65rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;padding:0.2rem 0.5rem;border-radius:2px;background:#EEF4FB;color:#2A7FC1;">
                        {{ $item->category }}
                    </span>
                    @else
                    <span style="color:#B0CCDF;font-size:0.8rem;">—</span>
                    @endif
                </td>
                <td style="padding:0.875rem 1rem;">
                    <span style="font-size:0.65rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;padding:0.25rem 0.625rem;border-radius:2px;
                        {{ $item->status === 'published' ? 'background:#F0FFF4;color:#276749;' : 'background:#FEF8EC;color:#B8860B;' }}">
                        {{ $item->status === 'published' ? 'Tayang' : 'Draft' }}
                    </span>
                </td>
                <td style="padding:0.875rem 1rem;font-size:0.8rem;color:#4A6580;">
                    {{ $item->published_at ? $item->published_at->format('d M Y') : $item->created_at->format('d M Y') }}
                </td>
                <td style="padding:0.875rem 1rem;">
                    <div style="display:flex;gap:0.5rem;">
                        <a href="{{ route('blog.show', $item->slug) }}" target="_blank"
                           style="font-size:0.7rem;font-weight:700;padding:0.3rem 0.625rem;border:1.5px solid #D6E8F7;border-radius:3px;color:#4A6580;text-decoration:none;">
                           Lihat
                        </a>
                        <a href="{{ route('admin.blogs.edit', $item) }}"
                           style="font-size:0.7rem;font-weight:700;padding:0.3rem 0.625rem;border:1.5px solid #2A7FC1;border-radius:3px;color:#2A7FC1;text-decoration:none;">
                           Edit
                        </a>
                        <form method="POST" action="{{ route('admin.blogs.destroy', $item) }}"
                              onsubmit="return confirm('Hapus blog ini?')">
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
                    Belum ada blog. <a href="{{ route('admin.blogs.create') }}" style="color:#2A7FC1;">Tambah sekarang →</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if ($blogs->hasPages())
<div style="margin-top:1rem;display:flex;justify-content:flex-end;">
    {{ $blogs->links() }}
</div>
@endif

@endsection