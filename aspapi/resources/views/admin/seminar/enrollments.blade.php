@extends('layouts.admin')
@section('title', 'Peserta — ' . $seminar->title)

@section('content')
<div class="p-6">

    <div class="flex items-center justify-between mb-6">
        <div>
            <a href="{{ route('admin.seminar.index') }}" class="text-xs text-neutral-400 hover:text-primary">← Kembali</a>
            <h1 class="text-xl font-extrabold text-navy mt-1">Peserta Seminar</h1>
            <p class="text-sm text-neutral-500">{{ $seminar->title }}</p>
        </div>
        <div class="flex gap-4 text-right">
            <div>
                <p class="text-2xs text-neutral-400">Total Peserta</p>
                <p class="text-2xl font-extrabold text-navy">{{ $enrollments->total() }}</p>
            </div>
            <div>
                <p class="text-2xs text-neutral-400">Lulus</p>
                <p class="text-2xl font-extrabold text-green-600">
                    {{ $enrollments->getCollection()->where('status', 'completed')->count() }}
                </p>
            </div>
        </div>
    </div>

    <div class="bg-white border border-neutral-200 rounded-xl overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-neutral-100 text-2xs font-bold uppercase tracking-wider text-neutral-400">
                    <th class="px-5 py-3 text-left">Anggota</th>
                    <th class="px-4 py-3 text-left">No. Anggota</th>
                    <th class="px-4 py-3 text-center">Terdaftar</th>
                    <th class="px-4 py-3 text-center">Status</th>
                    <th class="px-4 py-3 text-center">Sertifikat</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-100">
                @forelse ($enrollments as $enrollment)
                @php
                    $statusColor = match($enrollment->status) {
                        'completed'      => 'bg-green-50 text-green-700',
                        'post_test_done' => 'bg-orange-50 text-orange-700',
                        'material_read'  => 'bg-blue-50 text-blue-700',
                        'pre_test_done'  => 'bg-indigo-50 text-indigo-700',
                        default          => 'bg-neutral-100 text-neutral-500',
                    };
                @endphp
                <tr class="hover:bg-neutral-50">
                    <td class="px-5 py-4">
                        <p class="font-semibold text-navy">{{ $enrollment->member->full_name }}</p>
                        <p class="text-xs text-neutral-400">{{ $enrollment->member->email }}</p>
                    </td>
                    <td class="px-4 py-4">
                        <span class="font-mono text-xs text-neutral-600">
                            {{ $enrollment->member->member_number ?? '—' }}
                        </span>
                    </td>
                    <td class="px-4 py-4 text-center text-xs text-neutral-500">
                        {{ $enrollment->created_at->format('d M Y') }}
                    </td>
                    <td class="px-4 py-4 text-center">
                        <span class="text-2xs font-bold px-2 py-0.5 rounded {{ $statusColor }}">
                            {{ $enrollment->status_label }}
                        </span>
                    </td>
                    <td class="px-4 py-4 text-center">
                        @if ($enrollment->certificate)
                            <div>
                                <p class="text-xs font-mono text-navy">{{ $enrollment->certificate->certificate_number }}</p>
                                <p class="text-2xs text-neutral-400">Skor: {{ $enrollment->certificate->score }}</p>
                            </div>
                        @else
                            <span class="text-xs text-neutral-300">—</span>
                        @endif
                    </td>
                    <td class="px-4 py-4 text-center">
                        <a href="{{ route('admin.members.show', $enrollment->member) }}"
                            class="text-xs px-3 py-1.5 bg-primary/10 text-primary rounded-lg hover:bg-primary/20">
                            Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-5 py-10 text-center text-neutral-400 text-sm">
                        Belum ada peserta yang mendaftar seminar ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if ($enrollments->hasPages())
        <div class="px-5 py-4 border-t border-neutral-100">
            {{ $enrollments->links() }}
        </div>
        @endif
    </div>
</div>
@endsection