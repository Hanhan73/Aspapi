@extends('layouts.daerah')
@php $title = 'Data Anggota'; @endphp

@section('content')

{{-- Filter --}}
<div class="bg-white border border-neutral-200 rounded-lg px-5 py-4 mb-5">
    <form method="GET" class="flex flex-wrap gap-3 items-center">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Cari nama atau institusi..."
               class="px-3 py-2 border border-neutral-200 rounded text-sm text-navy outline-none focus:border-primary w-64"/>
        <select name="status"
                class="px-3 py-2 border border-neutral-200 rounded text-sm text-navy outline-none focus:border-primary">
            <option value="">Semua Status</option>
            <option value="active"  {{ request('status') === 'active'  ? 'selected' : '' }}>Aktif</option>
            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
        </select>
        <button type="submit" class="btn btn-primary btn-sm">Filter</button>
        <a href="{{ route('daerah.members') }}" class="btn btn-ghost btn-sm">Reset</a>
        <div class="ml-auto flex gap-2">
            <a href="{{ route('daerah.batch.form') }}" class="btn btn-sm"
               style="background:#E8B84B;color:#1A2A3A;border:none;">
                + Daftar Batch
            </a>
        </div>
    </form>
</div>

{{-- Tabel --}}
<div class="bg-white border border-neutral-200 rounded-lg overflow-hidden">
    <table class="w-full border-collapse">
        <thead>
            <tr class="bg-neutral-50">
                <th class="px-4 py-3 text-left text-2xs font-bold tracking-widest uppercase text-primary">Nama</th>
                <th class="px-4 py-3 text-left text-2xs font-bold tracking-widest uppercase text-primary">Institusi</th>
                <th class="px-4 py-3 text-left text-2xs font-bold tracking-widest uppercase text-primary">Kontak</th>
                <th class="px-4 py-3 text-left text-2xs font-bold tracking-widest uppercase text-primary">Iuran</th>
                <th class="px-4 py-3 text-left text-2xs font-bold tracking-widest uppercase text-primary">Status</th>
                <th class="px-4 py-3 text-left text-2xs font-bold tracking-widest uppercase text-primary">Terdaftar</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-neutral-100">
            @forelse ($members as $member)
            <tr class="hover:bg-neutral-50 transition-colors">
                <td class="px-4 py-3.5">
                    <p class="text-sm font-semibold text-navy">{{ $member->full_name }}</p>
                    <p class="text-xs text-neutral-400">{{ $member->email }}</p>
                </td>
                <td class="px-4 py-3.5 text-sm text-neutral-600">{{ $member->institution ?? '—' }}</td>
                <td class="px-4 py-3.5 text-sm text-neutral-600">{{ $member->phone ?? '—' }}</td>
                <td class="px-4 py-3.5">
                    @if ($member->dues_paid)
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-2xs font-bold bg-green-50 text-green-700">Lunas</span>
                    @else
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-2xs font-bold bg-red-50 text-red-700">Belum</span>
                    @endif
                </td>
                <td class="px-4 py-3.5">
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-2xs font-bold
                        {{ $member->status === 'active'   ? 'bg-green-50 text-green-700'   :
                           ($member->status === 'pending'  ? 'bg-yellow-50 text-yellow-700' : 'bg-neutral-100 text-neutral-500') }}">
                        {{ match($member->status) { 'active' => 'Aktif', 'pending' => 'Pending', default => ucfirst($member->status) } }}
                    </span>
                </td>
                <td class="px-4 py-3.5 text-sm text-neutral-500">{{ $member->created_at->format('d M Y') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-4 py-12 text-center text-sm text-neutral-400">
                    Belum ada anggota di wilayah ini.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if ($members->hasPages())
<div class="mt-4 flex justify-end">{{ $members->links() }}</div>
@endif

@endsection