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

{{-- Chart row --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

    {{-- Chart Pertumbuhan Anggota (line) — 2/3 lebar --}}
    <div class="lg:col-span-2 bg-white border border-neutral-200 rounded-lg p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <p class="text-2xs font-bold uppercase tracking-widest text-neutral-400">Pertumbuhan Anggota</p>
                <p class="text-xs text-neutral-400 mt-0.5">12 bulan terakhir</p>
            </div>
            <span class="text-2xs font-bold text-primary bg-blue-50 px-2 py-1 rounded">
                {{ $region->province }}
            </span>
        </div>
        <div style="position:relative;height:200px;">
            <canvas id="growthChart"></canvas>
        </div>
    </div>

    {{-- Donut + Iuran — 1/3 lebar --}}
    <div class="flex flex-col gap-6">

        {{-- Status Donut --}}
        <div class="bg-white border border-neutral-200 rounded-lg p-5 flex-1">
            <p class="text-2xs font-bold uppercase tracking-widest text-neutral-400 mb-3">Status Anggota</p>
            <div style="position:relative;height:130px;">
                <canvas id="statusChart"></canvas>
            </div>
            <div class="mt-3 space-y-1">
                @php
                    $legendItems = [
                        ['label' => 'Aktif',       'color' => '#276749', 'count' => $statusBreakdown['active']],
                        ['label' => 'Pending',      'color' => '#E8B84B', 'count' => $statusBreakdown['pending']],
                        ['label' => 'Tidak Aktif',  'color' => '#9CA3AF', 'count' => $statusBreakdown['inactive']],
                        ['label' => 'Ditolak',      'color' => '#C0392B', 'count' => $statusBreakdown['rejected']],
                    ];
                @endphp
                @foreach ($legendItems as $item)
                @if ($item['count'] > 0)
                <div class="flex items-center justify-between text-xs">
                    <div class="flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full flex-shrink-0" style="background:{{ $item['color'] }};"></span>
                        <span class="text-neutral-500">{{ $item['label'] }}</span>
                    </div>
                    <span class="font-bold text-navy">{{ $item['count'] }}</span>
                </div>
                @endif
                @endforeach
            </div>
        </div>

        {{-- Iuran bar sederhana --}}
        <div class="bg-white border border-neutral-200 rounded-lg p-5">
            <p class="text-2xs font-bold uppercase tracking-widest text-neutral-400 mb-3">Status Iuran</p>
            @php
                $totalDues = $duesBreakdown['lunas'] + $duesBreakdown['belum'];
                $lunasPercent = $totalDues > 0 ? round(($duesBreakdown['lunas'] / $totalDues) * 100) : 0;
            @endphp
            <div class="flex items-end gap-3 mb-3">
                <span class="text-2xl font-bold text-navy font-serif">{{ $lunasPercent }}%</span>
                <span class="text-xs text-neutral-400 pb-0.5">sudah lunas</span>
            </div>
            <div class="w-full bg-neutral-100 rounded-full h-2 mb-3">
                <div class="h-2 rounded-full bg-green-600 transition-all"
                     style="width:{{ $lunasPercent }}%;"></div>
            </div>
            <div class="flex justify-between text-xs text-neutral-500">
                <span>Lunas: <strong class="text-navy">{{ $duesBreakdown['lunas'] }}</strong></span>
                <span>Belum: <strong class="text-navy">{{ $duesBreakdown['belum'] }}</strong></span>
            </div>
        </div>

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

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"
        integrity="sha512-ZwR1/gSZM3ai6vCdI+LVF1zSq/5HznD3oD+sCoJrzXJ+yKywrkrqvT3lZIVQMd/t9M9I5oPTjbVTbdQrdF0Q=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
// ── Data dari PHP
const growthLabels = @json($chartLabels);
const growthData   = @json($chartData);
const statusData   = @json($statusBreakdown);

// ── Warna
const NAVY    = '#1A2A3A';
const BLUE    = '#2A7FC1';
const GREEN   = '#276749';
const YELLOW  = '#E8B84B';
const GRAY    = '#9CA3AF';
const RED     = '#C0392B';

// ── Chart Pertumbuhan (Line)
const growthCtx = document.getElementById('growthChart').getContext('2d');
const gradient  = growthCtx.createLinearGradient(0, 0, 0, 200);
gradient.addColorStop(0,   'rgba(42,127,193,0.18)');
gradient.addColorStop(1,   'rgba(42,127,193,0)');

new Chart(growthCtx, {
    type: 'line',
    data: {
        labels: growthLabels,
        datasets: [{
            label: 'Anggota Baru',
            data: growthData,
            borderColor: BLUE,
            backgroundColor: gradient,
            borderWidth: 2,
            pointBackgroundColor: BLUE,
            pointRadius: 3,
            pointHoverRadius: 5,
            fill: true,
            tension: 0.35,
        }],
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: NAVY,
                titleColor: '#fff',
                bodyColor: '#6AAFE6',
                padding: 10,
                callbacks: {
                    label: ctx => ` ${ctx.parsed.y} anggota baru`,
                },
            },
        },
        scales: {
            x: {
                grid: { color: '#F0F2F4' },
                ticks: { font: { size: 10 }, color: '#8A97A4' },
            },
            y: {
                grid: { color: '#F0F2F4' },
                ticks: {
                    font: { size: 10 },
                    color: '#8A97A4',
                    stepSize: 1,
                    precision: 0,
                },
                beginAtZero: true,
            },
        },
    },
});

// ── Chart Status (Donut)
const statusCtx = document.getElementById('statusChart').getContext('2d');
const statusValues = [
    statusData.active,
    statusData.pending,
    statusData.inactive,
    statusData.rejected,
];
const statusLabels = ['Aktif', 'Pending', 'Tidak Aktif', 'Ditolak'];
const statusColors = [GREEN, YELLOW, GRAY, RED];

// Filter hanya yang > 0 agar donut tidak kelihatan janggal
const filteredValues = [], filteredLabels = [], filteredColors = [];
statusValues.forEach((v, i) => {
    if (v > 0) {
        filteredValues.push(v);
        filteredLabels.push(statusLabels[i]);
        filteredColors.push(statusColors[i]);
    }
});

new Chart(statusCtx, {
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