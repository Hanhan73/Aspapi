@extends('layouts.admin')
@section('title', 'Mitra')

@section('content')

<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-lg font-bold text-navy">Daftar Mitra</h2>
        <p class="text-xs text-neutral-500 mt-0.5">Drag baris untuk mengatur urutan tampil</p>
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

{{-- Toast notifikasi urutan tersimpan --}}
<div id="sort-toast"
     class="fixed bottom-6 right-6 z-50 hidden items-center gap-2 bg-navy text-white text-xs font-semibold px-4 py-3 rounded-md shadow-lg transition-all duration-300">
    <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
    </svg>
    <span id="sort-toast-msg">Urutan berhasil disimpan</span>
</div>

{{-- Info drag (hanya tampil jika tidak ada filter aktif) --}}
@if (!request()->hasAny(['search', 'category']))
<div class="flex items-center gap-2 text-2xs text-neutral-400 mb-3">
    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M8 9h8M8 12h8M8 15h5"/>
    </svg>
    Drag baris untuk mengubah urutan. Perubahan disimpan otomatis.
</div>
@else
<div class="flex items-center gap-2 text-2xs text-amber-500 bg-amber-50 border border-amber-200 rounded px-3 py-2 mb-3">
    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
    </svg>
    Drag & drop urutan dinonaktifkan saat filter aktif. Reset filter untuk mengatur urutan.
</div>
@endif

{{-- Table --}}
<div class="card overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-neutral-50 border-b border-neutral-200">
                @if (!request()->hasAny(['search', 'category']))
                <th class="w-8 px-3 py-3"></th> {{-- drag handle --}}
                @endif
                <th class="text-left px-4 py-3 text-xs font-bold uppercase tracking-wider text-neutral-500 w-14">#</th>
                <th class="text-left px-4 py-3 text-xs font-bold uppercase tracking-wider text-neutral-500">Logo</th>
                <th class="text-left px-4 py-3 text-xs font-bold uppercase tracking-wider text-neutral-500">Nama</th>
                <th class="text-left px-4 py-3 text-xs font-bold uppercase tracking-wider text-neutral-500">Kategori</th>
                <th class="text-left px-4 py-3 text-xs font-bold uppercase tracking-wider text-neutral-500">Website</th>
                <th class="text-left px-4 py-3 text-xs font-bold uppercase tracking-wider text-neutral-500">Status</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody id="sortable-body" class="divide-y divide-neutral-100">
            @forelse ($partners as $partner)
            <tr class="hover:bg-neutral-50 transition-colors group" data-id="{{ $partner->id }}">

                {{-- Drag handle --}}
                @if (!request()->hasAny(['search', 'category']))
                <td class="px-3 py-3 w-8">
                    <div class="drag-handle cursor-grab active:cursor-grabbing text-neutral-300 hover:text-neutral-500 transition-colors flex justify-center">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <circle cx="9"  cy="5"  r="1.5"/>
                            <circle cx="15" cy="5"  r="1.5"/>
                            <circle cx="9"  cy="12" r="1.5"/>
                            <circle cx="15" cy="12" r="1.5"/>
                            <circle cx="9"  cy="19" r="1.5"/>
                            <circle cx="15" cy="19" r="1.5"/>
                        </svg>
                    </div>
                </td>
                @endif

                <td class="px-4 py-3 text-xs text-neutral-400 sort-order-cell">{{ $partner->sort_order }}</td>

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

                <td class="px-4 py-3">
                    <div class="flex items-center gap-2 justify-end">
                        <a href="{{ route('admin.partners.edit', $partner) }}"
                           class="btn btn-ghost btn-sm text-xs">Edit</a>
                        <form method="POST" action="{{ route('admin.partners.destroy', $partner) }}"
                              onsubmit="return confirm('Hapus mitra {{ addslashes($partner->name) }}?')">
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
{{-- SortableJS via CDN --}}
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>

<script>
(function () {
    const tbody   = document.getElementById('sortable-body');
    const toast   = document.getElementById('sort-toast');
    const toastMsg = document.getElementById('sort-toast-msg');
    const hasFilter = {{ request()->hasAny(['search', 'category']) ? 'true' : 'false' }};

    if (!tbody || hasFilter) return; // Drag dinonaktifkan saat filter aktif

    let toastTimer = null;

    function showToast(msg, isError = false) {
        toastMsg.textContent = msg;
        toast.classList.remove('hidden');
        toast.classList.add('flex');
        toast.querySelector('svg').classList.toggle('text-red-400', isError);
        toast.querySelector('svg').classList.toggle('text-green-400', !isError);

        clearTimeout(toastTimer);
        toastTimer = setTimeout(() => {
            toast.classList.add('hidden');
            toast.classList.remove('flex');
        }, 2500);
    }

    function updateOrderLabels() {
        tbody.querySelectorAll('tr[data-id]').forEach((row, index) => {
            const cell = row.querySelector('.sort-order-cell');
            if (cell) cell.textContent = index;
        });
    }

    Sortable.create(tbody, {
        handle: '.drag-handle',
        animation: 150,
        ghostClass: 'bg-primary-50',
        dragClass: 'shadow-card-hover',

        onEnd() {
            // Kumpulkan urutan ID terbaru
            const ids = [...tbody.querySelectorAll('tr[data-id]')]
                .map(row => parseInt(row.dataset.id));

            updateOrderLabels();

            // Kirim ke server
            axios.post('{{ route('admin.partners.reorder') }}', { ids })
                .then(() => showToast('Urutan berhasil disimpan'))
                .catch(() => showToast('Gagal menyimpan urutan', true));
        },
    });
})();
</script>
@endpush