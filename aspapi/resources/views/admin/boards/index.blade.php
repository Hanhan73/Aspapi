@extends('layouts.admin')
@php $title = 'Pengurus'; @endphp

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;">
    <div>
        <p style="font-size:0.7rem;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;color:#C0392B;">Kelola Profil</p>
        <h2 style="font-family:'DM Serif Display',serif;font-size:1.5rem;color:#1A2A3A;margin-top:0.125rem;">Pengurus Pusat</h2>
    </div>
    <a href="{{ route('admin.boards.create') }}"
       style="background:#2A7FC1;color:#fff;padding:0.625rem 1.25rem;border-radius:4px;font-size:0.75rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;text-decoration:none;">
        + Tambah Pengurus
    </a>
</div>

<div style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;overflow:hidden;">
    <table style="width:100%;border-collapse:collapse;">
        <thead>
            <tr style="background:#EEF4FB;">
                <th style="padding:0.75rem 1rem;text-align:left;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">Nama</th>
                <th style="padding:0.75rem 1rem;text-align:left;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">Jabatan</th>
                <th style="padding:0.75rem 1rem;text-align:left;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">Kategori</th>
                <th style="padding:0.75rem 1rem;text-align:left;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">Status</th>
                <th style="padding:0.75rem 1rem;text-align:left;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">Urutan</th>
                <th style="padding:0.75rem 1rem;text-align:left;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($boards as $board)
            <tr style="border-bottom:1px solid #EEF4FB;" onmouseover="this.style.background='#F8FAFC'" onmouseout="this.style.background='#fff'">
                <td style="padding:0.875rem 1rem;">
                    <div style="display:flex;align-items:center;gap:0.75rem;">
                        @if ($board->photo)
                        <img src="{{ Storage::url($board->photo) }}"
                             style="width:40px;height:40px;border-radius:50%;object-fit:cover;border:2px solid #D6E8F7;flex-shrink:0;"/>
                        @else
                        <div style="width:40px;height:40px;border-radius:50%;background:#EEF4FB;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <span style="font-size:0.9rem;font-weight:700;color:#2A7FC1;">{{ strtoupper(substr($board->name, 0, 1)) }}</span>
                        </div>
                        @endif
                        <div>
                            <p style="font-size:0.85rem;font-weight:600;color:#1A2A3A;">{{ $board->name }}</p>
                            <p style="font-size:0.72rem;color:#B0CCDF;">{{ $board->institution }}</p>
                        </div>
                    </div>
                </td>
                <td style="padding:0.875rem 1rem;font-size:0.825rem;color:#4A6580;">{{ $board->position }}</td>
                <td style="padding:0.875rem 1rem;">
                    @if ($board->position_category)
                    <span style="font-size:0.65rem;font-weight:700;letter-spacing:0.06em;text-transform:uppercase;padding:0.2rem 0.5rem;border-radius:2px;background:#EEF4FB;color:#2A7FC1;">
                        {{ $board->position_category }}
                    </span>
                    @else
                    <span style="color:#B0CCDF;">—</span>
                    @endif
                </td>
                <td style="padding:0.875rem 1rem;">
                    <span style="font-size:0.65rem;font-weight:700;padding:0.2rem 0.5rem;border-radius:2px;
                        {{ $board->is_active ? 'background:#F0FFF4;color:#276749;' : 'background:#FEF8EC;color:#B8860B;' }}">
                        {{ $board->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </td>
                <td style="padding:0.875rem 1rem;font-size:0.825rem;color:#4A6580;">{{ $board->sort_order ?? '—' }}</td>
                <td style="padding:0.875rem 1rem;">
                    <div style="display:flex;gap:0.5rem;">
                        <a href="{{ route('admin.boards.edit', $board) }}"
                           style="font-size:0.7rem;font-weight:700;padding:0.3rem 0.625rem;border:1.5px solid #2A7FC1;border-radius:3px;color:#2A7FC1;text-decoration:none;">
                           Edit
                        </a>
                        <form method="POST" action="{{ route('admin.boards.destroy', $board) }}"
                              onsubmit="return confirm('Hapus pengurus ini?')">
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
                    Belum ada data pengurus. <a href="{{ route('admin.boards.create') }}" style="color:#2A7FC1;">Tambah sekarang →</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if ($boards->hasPages())
<div style="margin-top:1rem;display:flex;justify-content:flex-end;">{{ $boards->links() }}</div>
@endif
@endsection