{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.admin')

@php $title = 'Dashboard'; @endphp

@section('content')

{{-- ── STAT CARDS ── --}}
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:1rem;margin-bottom:2rem;">

    @php
    $stats = [
        ['label' => 'Total Anggota',    'value' => $totalMembers,   'sub' => $pendingMembers . ' menunggu verifikasi', 'color' => '#2A7FC1', 'bg' => '#EEF4FB',
         'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>'],
        ['label' => 'Berita Tayang',    'value' => $totalNews,      'sub' => $draftNews . ' draft',                    'color' => '#C0392B', 'bg' => '#FDECEA',
         'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>'],
        ['label' => 'Blog Tayang',      'value' => $totalBlogs,     'sub' => $draftBlogs . ' draft',                   'color' => '#2A7FC1', 'bg' => '#EEF4FB',
         'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>'],
        ['label' => 'ASPAPI Daerah',    'value' => $totalRegions,   'sub' => 'wilayah aktif',                          'color' => '#B8860B', 'bg' => '#FEF8EC',
         'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>'],
        ['label' => 'Dokumen',          'value' => $totalDocuments, 'sub' => 'tersedia untuk diunduh',                 'color' => '#2A7FC1', 'bg' => '#EEF4FB',
         'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>'],
        ['label' => 'Pengurus Aktif',   'value' => $totalBoards,    'sub' => 'periode 2022–2026',                      'color' => '#C0392B', 'bg' => '#FDECEA',
         'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>'],
    ];
    @endphp

    @foreach ($stats as $stat)
    <div style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;padding:1.25rem;border-top:3px solid {{ $stat['color'] }};">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:0.75rem;">
            <div style="width:36px;height:36px;border-radius:6px;background:{{ $stat['bg'] }};display:flex;align-items:center;justify-content:center;">
                <svg style="width:18px;height:18px;color:{{ $stat['color'] }};" fill="none" stroke="{{ $stat['color'] }}" viewBox="0 0 24 24">
                    {!! $stat['icon'] !!}
                </svg>
            </div>
        </div>
        <p style="font-family:'DM Serif Display',serif;font-size:2rem;color:#1A2A3A;line-height:1;">{{ $stat['value'] }}</p>
        <p style="font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-top:0.25rem;">{{ $stat['label'] }}</p>
        <p style="font-size:0.72rem;color:#B0CCDF;margin-top:0.25rem;">{{ $stat['sub'] }}</p>
    </div>
    @endforeach

</div>

{{-- ── MAIN GRID ── --}}
<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;">

    {{-- Anggota Pending --}}
    <div style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;overflow:hidden;">
        <div style="padding:1rem 1.25rem;border-bottom:1px solid #EEF4FB;display:flex;align-items:center;justify-content:space-between;">
            <div>
                <p style="font-size:0.7rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#C0392B;">Perlu Tindakan</p>
                <h3 style="font-size:0.9rem;font-weight:700;color:#1A2A3A;margin-top:0.125rem;">Anggota Menunggu Verifikasi</h3>
            </div>
            <a href="{{ route('admin.members.index', ['status' => 'pending']) }}"
               style="font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;text-decoration:none;border-bottom:2px solid #E8B84B;padding-bottom:1px;">
               Lihat Semua
            </a>
        </div>

        @if ($pendingMembersList->isEmpty())
        <div style="padding:2rem;text-align:center;">
            <p style="font-size:0.8rem;color:#B0CCDF;">Tidak ada anggota yang menunggu verifikasi.</p>
        </div>
        @else
        <div>
            @foreach ($pendingMembersList as $member)
            <div style="display:flex;align-items:center;gap:0.875rem;padding:0.875rem 1.25rem;border-bottom:1px solid #F8FAFC;">
                <div style="width:36px;height:36px;border-radius:50%;background:#EEF4FB;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <span style="font-size:0.85rem;font-weight:700;color:#2A7FC1;">{{ strtoupper(substr($member->full_name, 0, 1)) }}</span>
                </div>
                <div style="flex:1;min-width:0;">
                    <p style="font-size:0.825rem;font-weight:600;color:#1A2A3A;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $member->full_name }}</p>
                    <p style="font-size:0.75rem;color:#4A6580;margin-top:0.1rem;">{{ $member->institution ?? '-' }} · {{ $member->member_type_label }}</p>
                </div>
                <div style="display:flex;gap:0.5rem;flex-shrink:0;">
                    <a href="{{ route('admin.members.show', $member) }}"
                       style="font-size:0.7rem;font-weight:700;letter-spacing:0.06em;text-transform:uppercase;color:#2A7FC1;text-decoration:none;padding:0.25rem 0.625rem;border:1.5px solid #2A7FC1;border-radius:3px;">
                       Review
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- Berita Terbaru --}}
    <div style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;overflow:hidden;">
        <div style="padding:1rem 1.25rem;border-bottom:1px solid #EEF4FB;display:flex;align-items:center;justify-content:space-between;">
            <div>
                <p style="font-size:0.7rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#C0392B;">Konten</p>
                <h3 style="font-size:0.9rem;font-weight:700;color:#1A2A3A;margin-top:0.125rem;">Berita Terbaru</h3>
            </div>
            <a href="{{ route('admin.news.create') }}"
               style="font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#fff;text-decoration:none;background:#2A7FC1;padding:0.35rem 0.75rem;border-radius:3px;">
               + Tambah
            </a>
        </div>

        @if ($latestNews->isEmpty())
        <div style="padding:2rem;text-align:center;">
            <p style="font-size:0.8rem;color:#B0CCDF;">Belum ada berita. Mulai tambahkan berita pertama.</p>
        </div>
        @else
        <div>
            @foreach ($latestNews as $news)
            <div style="display:flex;align-items:center;gap:0.875rem;padding:0.875rem 1.25rem;border-bottom:1px solid #F8FAFC;">
                <div style="flex:1;min-width:0;">
                    <p style="font-size:0.825rem;font-weight:600;color:#1A2A3A;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $news->title }}</p>
                    <p style="font-size:0.72rem;color:#4A6580;margin-top:0.1rem;">{{ $news->created_at->format('d M Y') }}</p>
                </div>
                <span style="font-size:0.65rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;padding:0.2rem 0.5rem;border-radius:2px;flex-shrink:0;
                    {{ $news->status === 'published' ? 'background:#F0FFF4;color:#276749;' : 'background:#FEF8EC;color:#B8860B;' }}">
                    {{ $news->status === 'published' ? 'Tayang' : 'Draft' }}
                </span>
                <a href="{{ route('admin.news.edit', $news) }}"
                   style="font-size:0.7rem;font-weight:700;letter-spacing:0.06em;text-transform:uppercase;color:#2A7FC1;text-decoration:none;padding:0.25rem 0.625rem;border:1.5px solid #2A7FC1;border-radius:3px;flex-shrink:0;">
                   Edit
                </a>
            </div>
            @endforeach
        </div>
        @endif
    </div>

</div>

{{-- ── QUICK LINKS ── --}}
<div style="margin-top:1.5rem;background:#fff;border:1px solid #D6E8F7;border-radius:6px;padding:1.25rem;">
    <p style="font-size:0.7rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#4A6580;margin-bottom:1rem;">Akses Cepat</p>
    <div style="display:flex;flex-wrap:wrap;gap:0.75rem;">
        @foreach ([
            ['Tambah Berita',       route('admin.news.create'),      '#2A7FC1'],
            ['Tambah Blog',         route('admin.blogs.create'),     '#2A7FC1'],
            ['Tambah Pengurus',     route('admin.boards.create'),    '#C0392B'],
            ['Tambah Dewan Pakar',  route('admin.experts.create'),   '#C0392B'],
            ['Tambah Dokumen',      route('admin.documents.create'), '#B8860B'],
            ['Tambah Daerah',       route('admin.regions.create'),   '#B8860B'],
        ] as $link)
        <a href="{{ $link[1] }}"
           style="font-size:0.72rem;font-weight:700;letter-spacing:0.06em;text-transform:uppercase;padding:0.5rem 0.875rem;border-radius:3px;text-decoration:none;border:1.5px solid {{ $link[2] }};color:{{ $link[2] }};transition:all 0.2s;"
           onmouseover="this.style.background='{{ $link[2] }}';this.style.color='#fff';"
           onmouseout="this.style.background='transparent';this.style.color='{{ $link[2] }}';">
            + {{ $link[0] }}
        </a>
        @endforeach
    </div>
</div>

@endsection