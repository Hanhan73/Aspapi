@extends('layouts.app')
@php $title = 'Dewan Pakar'; @endphp
@section('content')

<div style="background:linear-gradient(135deg,#111E2A,#1A5F9A,#2A7FC1);position:relative;padding:3rem 1.5rem 2.5rem;overflow:hidden;">
    <div style="position:absolute;inset-x:0;top:0;height:4px;background:linear-gradient(90deg,#C0392B,#E8B84B);"></div>
    <div class="max-w-7xl mx-auto">
        <nav style="display:flex;align-items:center;gap:0.5rem;font-size:0.75rem;color:#A8D4F5;margin-bottom:1rem;">
            <a href="{{ route('home') }}" style="color:#A8D4F5;">Beranda</a><span>›</span>
            <span style="color:#fff;font-weight:600;">Dewan Pakar</span>
        </nav>
        <p style="font-size:0.65rem;font-weight:700;letter-spacing:0.16em;text-transform:uppercase;color:#E8B84B;margin-bottom:0.5rem;">Profil Organisasi</p>
        <h1 style="font-family:'DM Serif Display',serif;color:#fff;font-size:clamp(1.75rem,4vw,2.75rem);line-height:1.2;">Dewan Pakar ASPAPI</h1>
        <p style="color:#A8D4F5;font-size:0.875rem;margin-top:0.75rem;max-width:600px;line-height:1.7;">Periode 2022–2026</p>
    </div>
</div>

<section class="py-14 bg-white">
    <div class="max-w-4xl mx-auto px-6">
        <p class="section-label">Tentang Dewan Pakar</p>
        <h2 class="section-title mt-1">Peran dan Fungsi</h2>
        <div class="section-divider"></div>
        <p style="font-size:0.875rem;color:#4A6580;line-height:1.9;margin-bottom:1rem;">
            Dewan Pakar ASPAPI adalah himpunan guru besar bidang administrasi/manajemen perkantoran dan ex officio ketua program studi administrasi/manajemen perkantoran dari perguruan tinggi atau sebutan lainnya, yang berfungsi memberikan pertimbangan kepada Pengurus ASPAPI.
        </p>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:1rem;">
            @foreach ([
                ['label' => 'Fungsi', 'text' => 'Memberikan pertimbangan kepada Pengurus Pusat dalam merumuskan kebijakan-kebijakan ASPAPI, berdasarkan kajian ilmu administrasi/manajemen perkantoran.'],
                ['label' => 'Tugas',  'text' => 'Memastikan pelaksanaan program kerja ASPAPI sesuai bidang kajian ilmu administrasi/manajemen perkantoran.'],
            ] as $item)
            <div style="background:#EEF4FB;border-left:3px solid #2A7FC1;border-radius:0 4px 4px 0;padding:1rem 1.125rem;">
                <p style="font-size:0.65rem;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;color:#2A7FC1;margin-bottom:0.375rem;">{{ $item['label'] }}</p>
                <p style="font-size:0.825rem;color:#4A6580;line-height:1.7;">{{ $item['text'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

@php
$grouped = $experts->groupBy('expertise');

// Mapping expertise ke label section & warna
$sectionMap = [
    'Pimpinan'     => ['title' => 'Pimpinan Dewan Pakar',                      'color' => '#2A7FC1', 'bg' => '#EEF4FB'],
    'Guru Besar'   => ['title' => 'Anggota Dewan Pakar — Unsur Guru Besar',     'color' => '#C0392B', 'bg' => '#FDECEA'],
    'Ketua Prodi'  => ['title' => 'Anggota Dewan Pakar — Unsur Ketua Prodi',    'color' => '#2A7FC1', 'bg' => '#EEF4FB'],
];
$defaultSection = ['title' => 'Anggota Dewan Pakar', 'color' => '#2A7FC1', 'bg' => '#EEF4FB'];
@endphp

<section class="py-14" style="background:#F8FAFC;">
    <div class="max-w-5xl mx-auto px-6 space-y-12">
        @forelse ($experts->groupBy('expertise') as $group => $members)
        @php
            $scheme = $sectionMap[$group] ?? $defaultSection;
            $color  = $scheme['color'];
            $bg     = $scheme['bg'];
            $title  = $scheme['title'];
        @endphp
        <div>
            <div style="display:flex;align-items:center;gap:1rem;margin-bottom:1.5rem;">
                <div style="width:4px;height:32px;background:{{ $color }};border-radius:2px;flex-shrink:0;"></div>
                <h2 style="font-family:'DM Serif Display',serif;font-size:1.4rem;color:#1A2A3A;">{{ $title }}</h2>
            </div>
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:1rem;">
                @foreach ($members as $person)
                @php
                    $initial = strtoupper(substr(preg_replace('/^(Prof\.|Dr\.|Drs\.|Dra\.)\s*/i', '', $person->name), 0, 1));
                @endphp
                <div style="background:#fff;border:1px solid #D6E8F7;border-radius:8px;overflow:hidden;border-top:3px solid {{ $color }};">
                    <div style="width:100%;height:180px;background:{{ $bg }};overflow:hidden;display:flex;align-items:center;justify-content:center;">
                        @if ($person->photo)
                            <img src="{{ Storage::url($person->photo) }}"
                                 alt="{{ $person->name }}"
                                 style="width:100%;height:100%;object-fit:cover;object-position:top;"/>
                        @else
                            <div style="display:flex;flex-direction:column;align-items:center;gap:0.5rem;">
                                <div style="width:70px;height:70px;border-radius:50%;background:{{ $color }};display:flex;align-items:center;justify-content:center;">
                                    <span style="font-family:'DM Serif Display',serif;font-size:1.75rem;color:#fff;">{{ $initial }}</span>
                                </div>
                                <span style="font-size:0.6rem;color:#B0CCDF;letter-spacing:0.08em;text-transform:uppercase;">Foto belum tersedia</span>
                            </div>
                        @endif
                    </div>
                    <div style="padding:0.875rem 1rem 1rem;">
                        @if ($person->title)
                        <span style="display:inline-block;font-size:0.62rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;padding:0.2rem 0.5rem;border-radius:2px;background:{{ $bg }};color:{{ $color }};margin-bottom:0.5rem;">
                            {{ $person->title }}
                        </span>
                        @endif
                        <p style="font-size:0.85rem;font-weight:700;color:#1A2A3A;line-height:1.4;">{{ $person->name }}</p>
                        @if ($person->institution)
                        <p style="font-size:0.775rem;color:#4A6580;margin-top:0.3rem;display:flex;align-items:flex-start;gap:0.3rem;">
                            <svg style="width:12px;height:12px;flex-shrink:0;margin-top:2px;" fill="none" stroke="#B0CCDF" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            {{ $person->institution }}
                        </p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @empty
        <div style="text-align:center;padding:4rem 0;">
            <p style="font-size:0.875rem;color:#B0CCDF;">Belum ada data Dewan Pakar.</p>
        </div>
        @endforelse
    </div>
</section>

@endsection