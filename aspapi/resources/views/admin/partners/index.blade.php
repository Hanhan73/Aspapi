@extends('layouts.admin')
@section('title', 'Mitra')

@section('content')

<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-lg font-bold text-navy">Daftar Mitra</h2>
        <p class="text-xs text-neutral-500 mt-0.5">Kelola mitra ASPAPI berdasarkan kategori</p>
    </div>
    <a href="{{ route('admin.partners.create') }}" class="btn btn-primary btn-sm">
        + Tambah Mitra
    </a>
</div>

{{-- Filter --}}
<form method="GET" class="flex flex-wrap gap-3 mb-5">
    <input type="text" name="search" value="{{ request('search') }}"
           placeholder="Cari nama mitra..."
           class="input input-sm w-56" />

    <select name="category" class="input input-sm w-48">
        <option value="">Semua Kategori</option>
        @foreach ($categories as $value => $label)
            <option value="{{ $value }}" {{ request('category') === $value ? 'selected' : '' }}>
                {{ $label }}
            </option>
        @endforeach
    </select>

    <button type="submit" class="btn btn-secondary btn-sm">Filter</button>
    @if (request()->hasAny(['search', 'category']))
        <a href="{{ route('admin.partners.index') }}" class="btn btn-ghost btn-sm">Reset</a>
    @endif
</form>

{{-- Table --}}
<div class="card overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-neutral-50 border-b border-neutral-200">
                <th class="text-left px-4 py-3 text-xs font-bold uppercase tracking-wider text-neutral-500 w-14">#</th>
                <th class="text-left px-4 py-3 text-xs font-bold uppercase tracking-wider text-neutral-500">Logo</th>
                <th class="text-left px-4 py-3 text-xs font-bold uppercase tracking-wider text-neutral-500">Nama</th>
                <th class="text-left px-4 py-3 text-xs font-bold uppercase tracking-wider text-neutral-500">Kategori</th>
                <th class="text-left px-4 py-3 text-xs font-bold uppercase tracking-wider text-neutral-500">Website</th>
                <th class="text-left px-4 py-3 text-xs font-bold uppercase tracking-wider text-neutral-500">Status</th>
                <th class="text-left px-4 py-3 text-xs font-bold uppercase tracking-wider text-neutral-500">Urutan</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-neutral-100">
            @forelse ($partners as $partner)
            <tr class="hover:bg-neutral-50 transition-colors">
                <td class="px-4 py-3 text-xs text-neutral-400">{{ $partner->id }}</td>

                {{-- Logo --}}
                <td class="px-4 py-3">
                    @if ($partner->logo)
                        <img src="{{ Storage::url($partner->logo) }}"
                             alt="{{ $partner->name }}"
                             class="h-10 w-16 object-contain rounded border border-neutral-100 bg-white p-1">
                    @else
                        <div class="h-10 w-16 rounded border border-neutral-100 bg-neutral-50 flex items-center justify-center">
                            <span class="text-2xs text-neutral-300 font-bold">NO IMG</span>
                        </div>
                    @endif
                </td>

                <td class="px-4 py-3">
                    <p class="font-semibold text-navy text-sm">{{ $partner->name }}</p>
                    @if ($partner->profile)
                        <p class="text-xs text-neutral-400 mt-0.5 line-clamp-1">{{ Str::limit($partner->profile, 60) }}</p>
                    @endif
                </td>

                <td class="px-4 py-3">
                    <span class="badge {{ $partner->category_color }} text-2xs">
                        {{ $partner->category_label }}
                    </span>
                </td>

                <td class="px-4 py-3">
                    @if ($partner->website_url)
                        <a href="{{ $partner->website_url }}" target="_blank"
                           class="text-xs text-primary hover:underline truncate block max-w-[140px]">
                            {{ parse_url($partner->website_url, PHP_URL_HOST) }}
                        </a>
                    @else
                        <span class="text-xs text-neutral-300">—</span>
                    @endif
                </td>

                <td class="px-4 py-3">
                    @if ($partner->is_active)
                        <span class="badge badge-success text-2xs">Aktif</span>
                    @else
                        <span class="badge badge-neutral text-2xs">Nonaktif</span>
                    @endif
                </td>

                <td class="px-4 py-3 text-xs text-neutral-500">{{ $partner->sort_order }}</td>

                <td class="px-4 py-3">
                    <div class="flex items-center gap-2 justify-end">
                        <a href="{{ route('admin.partners.edit', $partner) }}"
                           class="btn btn-ghost btn-sm text-xs">Edit</a>
                        <form method="POST" action="{{ route('admin.partners.destroy', $partner) }}"
                              onsubmit="return confirm('Hapus mitra {{ $partner->name }}?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-ghost btn-sm text-xs text-accent-red hover:bg-red-50">
                                Hapus
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="px-4 py-12 text-center text-neutral-400 text-sm">
                    Belum ada data mitra.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Pagination --}}
@if ($partners->hasPages())
    <div class="mt-4">{{ $partners->links() }}</div>
@endif

@endsection

@push('scripts')
<script>
// Tambahkan Str helper di blade
</script>
@endpush