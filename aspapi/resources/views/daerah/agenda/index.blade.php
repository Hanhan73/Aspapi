@extends('layouts.daerah')
@section('title', 'Agenda Kegiatan')

@section('content')
<div class="p-6">

    <div class="flex items-start justify-between mb-6">
        <div>
            <h1 class="text-xl font-extrabold text-navy">Agenda Kegiatan</h1>
            <p class="text-sm text-neutral-500 mt-1">Kelola agenda kegiatan daerah Anda. Agenda yang disetujui akan tampil di halaman publik.</p>
        </div>
        <a href="{{ route('daerah.agenda.create') }}"
           class="btn btn-primary btn-sm flex-shrink-0">
            + Tambah Agenda
        </a>
    </div>

    @if (session('success'))
        <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700 font-medium">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700 font-medium">{{ session('error') }}</div>
    @endif

    @if ($agendas->count())
    <div class="card overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-neutral-100 bg-neutral-50">
                    <th class="text-left px-5 py-3 text-2xs font-bold tracking-widest uppercase text-neutral-400">Nama Kegiatan</th>
                    <th class="text-left px-5 py-3 text-2xs font-bold tracking-widest uppercase text-neutral-400 hidden sm:table-cell">Tanggal</th>
                    <th class="text-left px-5 py-3 text-2xs font-bold tracking-widest uppercase text-neutral-400">Status</th>
                    <th class="text-right px-5 py-3 text-2xs font-bold tracking-widest uppercase text-neutral-400">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-50">
                @foreach ($agendas as $agenda)
                <tr class="hover:bg-neutral-50/70 transition-colors">
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            @if ($agenda->photo)
                            <div class="w-10 h-10 rounded overflow-hidden flex-shrink-0">
                                <img src="{{ Storage::url($agenda->photo) }}" class="w-full h-full object-cover" alt="">
                            </div>
                            @else
                            <div class="w-10 h-10 rounded bg-primary-50 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-primary-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            @endif
                            <div>
                                <p class="font-semibold text-navy text-sm">{{ $agenda->title }}</p>
                                @if ($agenda->reject_reason)
                                <p class="text-2xs text-accent-red mt-0.5">Alasan: {{ $agenda->reject_reason }}</p>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-4 hidden sm:table-cell">
                        <span class="text-sm text-neutral-500">{{ $agenda->event_date->translatedFormat('d M Y') }}</span>
                    </td>
                    <td class="px-5 py-4">
                        @php
                            $statusConfig = [
                                'pending'  => ['bg:#FEF3C7;color:#92400E;', 'Menunggu'],
                                'approved' => ['background:#D1FAE5;color:#065F46;', 'Disetujui'],
                                'rejected' => ['background:#FEE2E2;color:#991B1B;', 'Ditolak'],
                            ][$agenda->status] ?? ['background:#F3F4F6;color:#6B7280;', $agenda->status];
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-1 rounded text-2xs font-bold" style="{{ $statusConfig[0] }}">
                            {{ $statusConfig[1] }}
                        </span>
                    </td>
                    <td class="px-5 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            @if ($agenda->status !== 'approved')
                            <a href="{{ route('daerah.agenda.edit', $agenda) }}"
                               class="text-2xs font-bold text-primary hover:text-primary-600 transition-colors">Edit</a>
                            @endif
                            <form method="POST" action="{{ route('daerah.agenda.destroy', $agenda) }}"
                                  onsubmit="return confirm('Hapus agenda ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-2xs font-bold text-accent-red hover:opacity-70 transition-opacity">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if ($agendas->hasPages())
    <div class="mt-4 flex justify-end">{{ $agendas->links() }}</div>
    @endif

    @else
    <div class="card p-12 text-center">
        <div class="w-14 h-14 rounded-full bg-neutral-100 flex items-center justify-center mx-auto mb-3">
            <svg class="w-7 h-7 text-neutral-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>
        <p class="text-sm font-semibold text-neutral-500">Belum ada agenda</p>
        <a href="{{ route('daerah.agenda.create') }}" class="btn btn-primary btn-sm mt-4">Tambah Agenda Pertama</a>
    </div>
    @endif
</div>
@endsection