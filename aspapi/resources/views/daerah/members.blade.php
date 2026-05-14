@extends('layouts.daerah')
@php $title = 'Data Anggota'; @endphp

@section('content')

{{-- Filter --}}
<div class="bg-white border border-neutral-200 rounded-lg px-5 py-4 mb-5">
    <form method="GET" action="{{ route('daerah.members') }}" class="flex flex-wrap gap-3 items-center">
        {{-- FIX: name="search" — sesuai controller --}}
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Cari nama, email, institusi..."
               class="px-3 py-2 border border-neutral-200 rounded text-sm text-navy outline-none focus:border-primary w-64"/>

        {{-- FIX: filter status diimplementasikan di controller --}}
        <select name="status"
                class="px-3 py-2 border border-neutral-200 rounded text-sm text-navy outline-none focus:border-primary">
            <option value="">Semua Status</option>
            <option value="active"   {{ request('status') === 'active'   ? 'selected' : '' }}>Aktif</option>
            <option value="pending"  {{ request('status') === 'pending'  ? 'selected' : '' }}>Pending</option>
            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
        </select>

        {{-- FIX: tambah filter iuran --}}
        <select name="dues"
                class="px-3 py-2 border border-neutral-200 rounded text-sm text-navy outline-none focus:border-primary">
            <option value="">Semua Iuran</option>
            <option value="lunas" {{ request('dues') === 'lunas' ? 'selected' : '' }}>Lunas</option>
            <option value="belum" {{ request('dues') === 'belum' ? 'selected' : '' }}>Belum Lunas</option>
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

{{-- Info hasil filter --}}
@if (request()->hasAny(['search', 'status', 'dues']))
<div class="mb-4 text-sm text-neutral-500">
    Menampilkan <strong class="text-navy">{{ $members->total() }}</strong> anggota
    @if(request('search')) dengan kata kunci "<em>{{ request('search') }}</em>" @endif
    @if(request('status')) — status: <em>{{ request('status') }}</em> @endif
    @if(request('dues')) — iuran: <em>{{ request('dues') }}</em> @endif
</div>
@endif

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
                    @php
                        $statusClass = match($member->status) {
                            'active'   => 'bg-green-50 text-green-700',
                            'pending'  => 'bg-yellow-50 text-yellow-700',
                            'inactive' => 'bg-neutral-100 text-neutral-500',
                            default    => 'bg-neutral-100 text-neutral-500',
                        };
                        $statusLabel = match($member->status) {
                            'active'   => 'Aktif',
                            'pending'  => 'Pending',
                            'inactive' => 'Tidak Aktif',
                            default    => ucfirst($member->status),
                        };
                    @endphp
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-2xs font-bold {{ $statusClass }}">
                        {{ $statusLabel }}
                    </span>
                </td>
                <td class="px-4 py-3.5 text-sm text-neutral-500">{{ $member->created_at->format('d M Y') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-4 py-12 text-center text-sm text-neutral-400">
                    @if (request()->hasAny(['search', 'status', 'dues']))
                        Tidak ada anggota yang cocok dengan filter.
                        <a href="{{ route('daerah.members') }}" class="text-primary font-semibold">Reset filter</a>
                    @else
                        Belum ada anggota di wilayah ini.
                    @endif
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if ($members->hasPages())
{{-- FIX: withQueryString() di controller memastikan filter dipertahankan saat paginasi --}}
<div class="mt-4 flex justify-end">{{ $members->links() }}</div>
@endif

@endsection