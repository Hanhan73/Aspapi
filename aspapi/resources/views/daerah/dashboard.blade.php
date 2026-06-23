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

{{-- Row: Status + Iuran + Info Daerah --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

    {{-- Status Anggota (donut) --}}
    <div class="bg-white border border-neutral-200 rounded-lg p-6">
        <p class="text-2xs font-bold uppercase tracking-widest text-neutral-400 mb-4">Status Anggota</p>
        <div style="position:relative;height:160px;">
            <canvas id="statusChart"></canvas>
        </div>
        <div class="mt-4 space-y-1.5">
            @php
                $legendItems = [
                    ['label' => 'Aktif',      'color' => '#276749', 'count' => $statusBreakdown['active']],
                    ['label' => 'Pending',     'color' => '#E8B84B', 'count' => $statusBreakdown['pending']],
                    ['label' => 'Tidak Aktif', 'color' => '#9CA3AF', 'count' => $statusBreakdown['inactive']],
                    ['label' => 'Ditolak',     'color' => '#C0392B', 'count' => $statusBreakdown['rejected']],
                ];
            @endphp
            @foreach ($legendItems as $item)
            @if ($item['count'] > 0)
            <div class="flex items-center justify-between text-xs">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full flex-shrink-0" style="background:{{ $item['color'] }};"></span>
                    <span class="text-neutral-500">{{ $item['label'] }}</span>
                </div>
                <span class="font-bold text-navy">{{ $item['count'] }}</span>
            </div>
            @endif
            @endforeach
        </div>
    </div>

    {{-- Status Iuran --}}
    <div class="bg-white border border-neutral-200 rounded-lg p-6">
        <p class="text-2xs font-bold uppercase tracking-widest text-neutral-400 mb-4">Status Iuran</p>
        @php
            $totalDues    = $duesBreakdown['lunas'] + $duesBreakdown['belum'];
            $lunasPercent = $totalDues > 0 ? round(($duesBreakdown['lunas'] / $totalDues) * 100) : 0;
        @endphp

        <div class="flex items-end gap-2 mb-4">
            <span class="text-4xl font-bold text-navy font-serif">{{ $lunasPercent }}%</span>
            <span class="text-xs text-neutral-400 pb-1">sudah lunas</span>
        </div>

        <div class="w-full bg-neutral-100 rounded-full h-3 mb-5">
            <div class="h-3 rounded-full bg-green-600 transition-all"
                 style="width:{{ $lunasPercent }}%;"></div>
        </div>

        <div class="grid grid-cols-2 gap-3">
            <div class="bg-green-50 rounded-lg p-3 text-center">
                <p class="text-xl font-bold text-green-700 font-serif">{{ $duesBreakdown['lunas'] }}</p>
                <p class="text-2xs text-green-600 uppercase tracking-widest mt-0.5">Lunas</p>
            </div>
            <div class="bg-red-50 rounded-lg p-3 text-center">
                <p class="text-xl font-bold text-red-700 font-serif">{{ $duesBreakdown['belum'] }}</p>
                <p class="text-2xs text-red-600 uppercase tracking-widest mt-0.5">Belum</p>
            </div>
        </div>
    </div>

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
                <p class="text-sm font-medium text-navy">{{ $member->full_name_with_title }}</p>
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

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
const statusData   = @json($statusBreakdown);

const GREEN  = '#276749';
const YELLOW = '#E8B84B';
const GRAY   = '#9CA3AF';
const RED    = '#C0392B';
const NAVY   = '#1A2A3A';

const statusValues = [statusData.active, statusData.pending, statusData.inactive, statusData.rejected];
const statusLabels = ['Aktif', 'Pending', 'Tidak Aktif', 'Ditolak'];
const statusColors = [GREEN, YELLOW, GRAY, RED];

const filteredValues = [], filteredLabels = [], filteredColors = [];
statusValues.forEach((v, i) => {
    if (v > 0) {
        filteredValues.push(v);
        filteredLabels.push(statusLabels[i]);
        filteredColors.push(statusColors[i]);
    }
});

new Chart(document.getElementById('statusChart').getContext('2d'), {
    type: 'doughnut',
    data: {
        labels: filteredLabels,
        datasets: [{
            data: filteredValues,
            backgroundColor: filteredColors,
            borderWidth: 2,
            borderColor: '#fff',
            hoverOffset: 4,
        }],
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '68%',
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: NAVY,
                titleColor: '#fff',
                bodyColor: '#6AAFE6',
                padding: 10,
            },
        },
    },
});
</script>
@endpush