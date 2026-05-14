@extends('layouts.app')
@php $title = 'Dewan Penasihat'; @endphp
@section('content')

<div style="background:linear-gradient(135deg,#111E2A,#1A5F9A,#2A7FC1);position:relative;padding:3rem 1.5rem 2.5rem;overflow:hidden;">
    <div style="position:absolute;inset-x:0;top:0;height:4px;background:linear-gradient(90deg,#C0392B,#E8B84B);"></div>
    <div class="max-w-7xl mx-auto">
        <nav style="display:flex;align-items:center;gap:0.5rem;font-size:0.75rem;color:#A8D4F5;margin-bottom:1rem;">
            <a href="{{ route('home') }}" style="color:#A8D4F5;">Beranda</a><span>›</span>
            <span style="color:#fff;font-weight:600;">Dewan Penasihat</span>
        </nav>
        <p style="font-size:0.65rem;font-weight:700;letter-spacing:0.16em;text-transform:uppercase;color:#E8B84B;margin-bottom:0.5rem;">Profil Organisasi</p>
        <h1 style="font-family:'DM Serif Display',serif;color:#fff;font-size:clamp(1.75rem,4vw,2.75rem);line-height:1.2;">Dewan Penasihat ASPAPI</h1>
        <p style="color:#A8D4F5;font-size:0.875rem;margin-top:0.75rem;max-width:600px;line-height:1.7;">Periode 2022–2026</p>
    </div>
</div>

<section class="py-14 bg-white">
    <div class="max-w-4xl mx-auto px-6">
        <p class="section-label">Tentang Dewan Penasihat</p>
        <h2 class="section-title mt-1">Peran dan Fungsi</h2>
        <div class="section-divider"></div>
        <p style="font-size:0.875rem;color:#4A6580;line-height:1.9;margin-bottom:1rem;">
            Dewan Penasihat merupakan himpunan Ketua Umum ASPAPI periode sebelumnya.
        </p>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:1rem;">
            @foreach ([
                ['label' => 'Fungsi',   'text' => 'Memberikan masukan kepada Pengurus Pusat dalam mengambil keputusan organisasi.'],
                ['label' => 'Tugas',    'text' => 'Memastikan pelaksanaan program kerja ASPAPI sesuai dengan visi dan misi.'],
                ['label' => 'Wewenang', 'text' => 'Melakukan pengawasan terhadap pelaksanaan program kerja ASPAPI.'],
            ] as $item)
            <div style="background:#EEF4FB;border-left:3px solid #2A7FC1;border-radius:0 4px 4px 0;padding:1rem 1.125rem;">
                <p style="font-size:0.65rem;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;color:#2A7FC1;margin-bottom:0.375rem;">{{ $item['label'] }}</p>
                <p style="font-size:0.825rem;color:#4A6580;line-height:1.7;">{{ $item['text'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section class="py-14" style="background:#F8FAFC;">
    <div class="max-w-4xl mx-auto px-6">
        <div class="text-center mb-10">
            <p class="section-label">Anggota</p>
            <h2 class="section-title mt-1">Dewan Penasihat ASPAPI</h2>
            <div class="section-divider mx-auto"></div>
        </div>

        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:1.5rem;">
            @forelse ($advisors as $person)
            @php
                $initial = strtoupper(substr(preg_replace('/^(Prof\.|Dr\.|Drs\.|Dra\.)\s*/i', '', $person->name), 0, 1));
            @endphp
            <div style="background:#fff;border:1px solid #D6E8F7;border-radius:8px;overflow:hidden;border-top:3px solid #2A7FC1;text-align:center;">
                <div style="width:100%;height:clamp(260px,55vw,360px);background:#EEF4FB;overflow:hidden;display:flex;align-items:center;justify-content:center;">
                    @if ($person->photo)
                        <img src="{{ Storage::url($person->photo) }}"
                             alt="{{ $person->name }}"
                             style="width:100%;height:100%;object-fit:cover;object-position:center 25%;"/>
                    @else
                        <div style="display:flex;flex-direction:column;align-items:center;gap:0.5rem;">
                            <div style="width:80px;height:80px;border-radius:50%;background:#2A7FC1;display:flex;align-items:center;justify-content:center;">
                                <span style="font-family:'DM Serif Display',serif;font-size:2rem;color:#fff;">{{ $initial }}</span>
                            </div>
                            <span style="font-size:0.6rem;color:#B0CCDF;letter-spacing:0.08em;text-transform:uppercase;">Foto belum tersedia</span>
                        </div>
                    @endif
                </div>
                <div style="padding:1.25rem;">
                    <div style="width:32px;height:3px;background:#E8B84B;border-radius:2px;margin:0 auto 0.75rem;"></div>
                    <p style="font-size:0.9rem;font-weight:700;color:#1A2A3A;line-height:1.4;">{{ $person->name }}</p>
                    @if ($person->institution)
                    <p style="font-size:0.775rem;color:#4A6580;margin-top:0.375rem;">{{ $person->institution }}</p>
                    @endif
                    @if ($person->position)
                    <span style="display:inline-block;margin-top:0.625rem;font-size:0.65rem;font-weight:700;letter-spacing:0.06em;text-transform:uppercase;padding:0.25rem 0.625rem;border-radius:2px;background:#EEF4FB;color:#2A7FC1;">
                        {{ $person->position }}
                    </span>
                    @endif
                </div>
            </div>
            @empty
            <div style="grid-column:1/-1;text-align:center;padding:3rem 0;">
                <p style="font-size:0.875rem;color:#B0CCDF;">Belum ada data Dewan Penasihat.</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

@endsection