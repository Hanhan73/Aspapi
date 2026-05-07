@extends('layouts.daerah')
@php $title = 'Dashboard — ASPAPI ' . $region->province; @endphp

@section('content')

{{-- Statistik --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="bg-white border border-neutral-200 rounded-lg p-5 border-t-4 border-t-primary">
        <p class="text-3xl font-bold text-navy font-serif">{{ $stats['total_members'] }}</p>
        <p class="text-2xs uppercase tracking-widest text-neutral-400 mt-1">Total Anggota</p>
    </div>
    <div class="bg-white border border-neutral-200 rounded-lg p-5 border-t-4 border-t-green-600">
        <p class="text-3xl font-bold text-navy font-serif">{{ $stats['active_members'] }}</p>
        <p class="text-2xs uppercase tracking-widest text-neutral-400 mt-1">Anggota Aktif</p>
    </div>
    <div class="bg-white border border-neutral-200 rounded-lg p-5 border-t-4 border-t-accent-yellow">
        <p class="text-3xl font-bold text-navy font-serif">{{ $stats['pending'] }}</p>
        <p class="text-2xs uppercase tracking-widest text-neutral-400 mt-1">Menunggu Verifikasi</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- Informasi Daerah --}}
    <div class="bg-white border border-neutral-200 rounded-lg p-6">
        <p class="text-2xs font-bold uppercase tracking-widest text-neutral-400 mb-4">Informasi Daerah</p>
        <div class="space-y-3">
            <div>
                <p class="text-2xs text-neutral-400 mb-0.5">Ketua</p>
                <p class="text-sm font-semibold text-navy">{{ $region->chairman_name ?? '—' }}</p>
                @if ($region->chairman_title)
                    <p class="text-xs text-neutral-500">{{ $region->chairman_title }}</p>
                @endif
            </div>
            <div>
                <p class="text-2xs text-neutral-400 mb-0.5">Periode</p>
                <p class="text-sm font-semibold text-navy">{{ $region->period ?? '—' }}</p>
            </div>
            @if ($region->email)
            <div>
                <p class="text-2xs text-neutral-400 mb-0.5">Email</p>
                <p class="text-sm text-navy">{{ $region->email }}</p>
            </div>
            @endif
            @if ($region->website_url)
            <div>
                <p class="text-2xs text-neutral-400 mb-0.5">Website</p>
                <a href="{{ $region->website_url }}" target="_blank"
                   class="text-xs text-primary hover:underline">{{ $region->website_url }}</a>
            </div>
            @endif
        </div>
    </div>

    {{-- Anggota Terbaru --}}
    <div class="bg-white border border-neutral-200 rounded-lg p-6">
        <div class="flex items-center justify-between mb-4">
            <p class="text-2xs font-bold uppercase tracking-widest text-neutral-400">Anggota Terbaru</p>
            <a href="{{ route('daerah.members') }}" class="text-2xs font-bold text-primary hover:underline">Lihat Semua →</a>
        </div>
        @if ($recentMembers->isNotEmpty())
        <div class="divide-y divide-neutral-100">
            @foreach ($recentMembers as $member)
            <div class="flex items-center justify-between py-3">
                <div>
                    <p class="text-sm font-medium text-navy">{{ $member->full_name }}</p>
                    <p class="text-xs text-neutral-400">{{ $member->institution ?? '—' }}</p>
                </div>
                <span class="inline-flex items-center px-2 py-0.5 rounded text-2xs font-bold
                    {{ $member->status === 'active'  ? 'bg-green-50 text-green-700' :
                       ($member->status === 'pending' ? 'bg-yellow-50 text-yellow-700' : 'bg-neutral-100 text-neutral-500') }}">
                    {{ match($member->status) { 'active' => 'Aktif', 'pending' => 'Pending', default => ucfirst($member->status) } }}
                </span>
            </div>
            @endforeach
        </div>
        @else
        <p class="text-sm text-neutral-400 py-4 text-center">Belum ada anggota terdaftar.</p>
        @endif
    </div>

</div>

@endsection