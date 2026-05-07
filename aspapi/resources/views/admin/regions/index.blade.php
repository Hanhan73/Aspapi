@extends('layouts.admin')
@section('title', 'ASPAPI Daerah')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-xl font-bold text-navy">ASPAPI Daerah</h1>
        <p class="text-sm text-neutral-500 mt-0.5">{{ $regions->count() }} daerah terdaftar</p>
    </div>
    <a href="{{ route('admin.regions.create') }}" class="btn btn-primary">+ Tambah Daerah</a>
</div>

@if (session('success'))
    <div class="alert alert-success mb-4">{{ session('success') }}</div>
@endif

<div class="card overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-neutral-50 border-b border-neutral-200">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-neutral-500">#</th>
                <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-neutral-500">Provinsi</th>
                <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-neutral-500">Ketua</th>
                <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-neutral-500">Periode</th>
                <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-neutral-500">Akun Login</th>
                <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-neutral-500">Status</th>
                <th class="px-4 py-3 text-right text-xs font-bold uppercase tracking-wider text-neutral-500">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-neutral-100">
            @forelse ($regions as $region)
            <tr class="hover:bg-neutral-50 transition-colors">
                <td class="px-4 py-3 text-neutral-400 text-xs">{{ $loop->iteration }}</td>
                <td class="px-4 py-3 font-semibold text-navy">ASPAPI {{ $region->province }}</td>
                <td class="px-4 py-3">
                    <div class="font-medium text-navy text-xs">{{ $region->chairman_name ?? '—' }}</div>
                    @if ($region->chairman_title)
                        <div class="text-neutral-400 text-2xs">{{ $region->chairman_title }}</div>
                    @endif
                </td>
                <td class="px-4 py-3 text-xs text-neutral-600">{{ $region->period }}</td>
                <td class="px-4 py-3">
                    @if ($region->activeUser)
                        <span class="badge badge-success text-2xs">Ada</span>
                        <div class="text-2xs text-neutral-400 mt-0.5">{{ $region->activeUser->email }}</div>
                    @else
                        <span class="badge badge-neutral text-2xs">Belum ada</span>
                    @endif
                </td>
                <td class="px-4 py-3">
                    @if ($region->is_active)
                        <span class="badge badge-success text-2xs">Aktif</span>
                    @else
                        <span class="badge badge-neutral text-2xs">Nonaktif</span>
                    @endif
                </td>
                <td class="px-4 py-3 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.regions.account', $region) }}"
                           class="btn btn-outline text-2xs py-1 px-2">Akun</a>
                        <a href="{{ route('admin.regions.edit', $region) }}"
                           class="btn btn-outline text-2xs py-1 px-2">Edit</a>
                        <form action="{{ route('admin.regions.destroy', $region) }}" method="POST"
                              onsubmit="return confirm('Hapus ASPAPI {{ $region->province }}?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger text-2xs py-1 px-2">Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-4 py-10 text-center text-neutral-400 text-sm">
                    Belum ada data ASPAPI Daerah.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection