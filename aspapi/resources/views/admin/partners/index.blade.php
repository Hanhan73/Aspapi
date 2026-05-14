@extends('layouts.admin')
@section('title', 'Mitra')

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;">
    <div>
        <p style="font-size:0.7rem;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;color:#C0392B;">Kelola Kemitraan</p>
        <h2 style="font-family:'DM Serif Display',serif;font-size:1.5rem;color:#1A2A3A;margin-top:0.125rem;">Daftar Mitra</h2>
    </div>
    <a href="{{ route('admin.partners.create') }}"
       style="background:#2A7FC1;color:#fff;padding:0.625rem 1.25rem;border-radius:4px;font-size:0.75rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;text-decoration:none;">
        + Tambah Mitra
    </a>
</div>

{{-- Tab Kategori --}}
@php
    $activeTab = request('category', 'semua');
    $tabList = array_merge(['semua' => 'Semua'], $categories->toArray());
@endphp

<div style="display:flex;gap:0;border-bottom:2px solid #D6E8F7;margin-bottom:1.25rem;overflow-x:auto;">
    @foreach ($tabList as $tabKey => $tabLabel)
    @php
        $isActive = $activeTab === $tabKey;
        $count = $tabKey === 'semua'
            ? $allPartners->count()
            : $allPartners->where('category', $tabKey)->count();
    @endphp
    <a href="{{ route('admin.partners.index', $tabKey !== 'semua' ? ['category' => $tabKey] : []) }}"
       style="flex-shrink:0;padding:0.625rem 1rem;font-size:0.72rem;font-weight:700;letter-spacing:0.07em;text-transform:uppercase;text-decoration:none;border-bottom:2px solid {{ $isActive ? '#2A7FC1' : 'transparent' }};margin-bottom:-2px;color:{{ $isActive ? '#2A7FC1' : '#4A6580' }};display:flex;align-items:center;gap:0.4rem;white-space:nowrap;">
        {{ $tabLabel }}
        <span style="font-size:0.65rem;font-weight:700;padding:0.1rem 0.4rem;border-radius:10px;background:{{ $isActive ? '#2A7FC1' : '#EEF4FB' }};color:{{ $isActive ? '#fff' : '#4A6580' }};">
            {{ $count }}
        </span>
    </a>
    @endforeach
</div>

{{-- Search --}}
<form method="GET" style="display:flex;gap:0.75rem;margin-bottom:1rem;">
    @if ($activeTab !== 'semua')
        <input type="hidden" name="category" value="{{ $activeTab }}"/>
    @endif
    <input type="text" name="search" value="{{ request('search') }}"
           placeholder="Cari nama mitra..."
           style="padding:0.5rem 0.875rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.8rem;color:#1A2A3A;outline:none;width:240px;"
           onfocus="this.style.borderColor='#2A7FC1'" onblur="this.style.borderColor='#D6E8F7'"/>
    <button type="submit"
            style="padding:0.5rem 1rem;background:#EEF4FB;color:#2A7FC1;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.75rem;font-weight:700;letter-spacing:0.06em;text-transform:uppercase;cursor:pointer;">
        Cari
    </button>
    @if (request('search'))
        <a href="{{ route('admin.partners.index', $activeTab !== 'semua' ? ['category' => $activeTab] : []) }}"
           style="padding:0.5rem 1rem;color:#4A6580;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.75rem;font-weight:700;letter-spacing:0.06em;text-transform:uppercase;text-decoration:none;display:flex;align-items:center;">
            Reset
        </a>
    @endif
</form>

{{-- Info drag --}}
@if (!request('search'))
<div style="display:flex;align-items:center;gap:0.5rem;font-size:0.7rem;color:#B0CCDF;margin-bottom:0.75rem;">
    <svg style="width:14px;height:14px;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9h8M8 12h8M8 15h5"/>
    </svg>
    Drag baris untuk mengatur urutan dalam kategori
    <strong style="color:#2A7FC1;">{{ $activeTab === 'semua' ? 'semua kategori' : ($tabList[$activeTab] ?? $activeTab) }}</strong>.
    Disimpan otomatis.
</div>
@else
<div style="display:flex;align-items:center;gap:0.5rem;font-size:0.7rem;color:#B8860B;background:#FEF8EC;border:1px solid #F6D860;border-radius:4px;padding:0.5rem 0.75rem;margin-bottom:0.75rem;">
    <svg style="width:14px;height:14px;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
    </svg>
    Drag & drop dinonaktifkan saat pencarian aktif.
</div>
@endif

{{-- Toast --}}
<div id="sort-toast"
     style="position:fixed;bottom:1.5rem;right:1.5rem;z-index:50;display:none;align-items:center;gap:0.5rem;background:#1A2A3A;color:#fff;font-size:0.75rem;font-weight:600;padding:0.75rem 1rem;border-radius:6px;box-shadow:0 4px 16px rgba(0,0,0,0.15);">
    <svg id="sort-toast-icon" style="width:16px;height:16px;color:#4ade80;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
    </svg>
    <span id="sort-toast-msg">Urutan berhasil disimpan</span>
</div>

{{-- Table --}}
<div style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;overflow:hidden;">
    <table style="width:100%;border-collapse:collapse;">
        <thead>
            <tr style="background:#EEF4FB;">
                <th style="width:36px;padding:0.75rem 0.5rem;"></th>
                <th style="padding:0.75rem 1rem;text-align:left;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">Logo</th>
                <th style="padding:0.75rem 1rem;text-align:left;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">Nama</th>
                <th style="padding:0.75rem 1rem;text-align:left;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">Kategori</th>
                <th style="padding:0.75rem 1rem;text-align:left;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">Website</th>
                <th style="padding:0.75rem 1rem;text-align:left;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">Status</th>
                <th style="padding:0.75rem 1rem;text-align:left;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">Urutan</th>
                <th style="padding:0.75rem 1rem;"></th>
            </tr>
        </thead>
        <tbody id="sortable-body">
            @forelse ($partners as $partner)
            <tr style="border-bottom:1px solid #EEF4FB;" data-id="{{ $partner->id }}"
                onmouseover="this.style.background='#F8FAFC'" onmouseout="this.style.background='#fff'">

                {{-- Drag handle --}}
                <td style="padding:0.75rem 0.5rem;text-align:center;width:36px;">
                    <div class="drag-handle"
                         style="cursor:{{ request('search') ? 'not-allowed' : 'grab' }};color:#D6E8F7;display:flex;justify-content:center;opacity:{{ request('search') ? '0.3' : '1' }};"
                         onmouseover="this.style.color='{{ request('search') ? '#D6E8F7' : '#2A7FC1' }}'"
                         onmouseout="this.style.color='#D6E8F7'">
                        <svg style="width:16px;height:16px;" fill="currentColor" viewBox="0 0 24 24">
                            <circle cx="9"  cy="5"  r="1.5"/>
                            <circle cx="15" cy="5"  r="1.5"/>
                            <circle cx="9"  cy="12" r="1.5"/>
                            <circle cx="15" cy="12" r="1.5"/>
                            <circle cx="9"  cy="19" r="1.5"/>
                            <circle cx="15" cy="19" r="1.5"/>
                        </svg>
                    </div>
                </td>

                {{-- Logo --}}
                <td style="padding:0.875rem 1rem;">
                    @if ($partner->logo)
                        <img src="{{ Storage::url($partner->logo) }}"
                             alt="{{ $partner->name }}"
                             style="height:40px;width:64px;object-fit:contain;border-radius:4px;border:1px solid #EEF4FB;background:#fff;padding:4px;">
                    @else
                        <div style="height:40px;width:64px;border-radius:4px;border:1px solid #EEF4FB;background:#EEF4FB;display:flex;align-items:center;justify-content:center;">
                            <span style="font-size:0.6rem;font-weight:700;color:#B0CCDF;">NO IMG</span>
                        </div>
                    @endif
                </td>

                {{-- Nama --}}
                <td style="padding:0.875rem 1rem;">
                    <p style="font-size:0.85rem;font-weight:600;color:#1A2A3A;">{{ $partner->name }}</p>
                    @if ($partner->profile)
                        <p style="font-size:0.72rem;color:#B0CCDF;margin-top:0.125rem;">{{ Str::limit($partner->profile, 60) }}</p>
                    @endif
                </td>

                {{-- Kategori --}}
                <td style="padding:0.875rem 1rem;">
                    <span style="font-size:0.65rem;font-weight:700;letter-spacing:0.06em;text-transform:uppercase;padding:0.2rem 0.5rem;border-radius:2px;background:#EEF4FB;color:#2A7FC1;">
                        {{ $partner->category_label }}
                    </span>
                </td>

                {{-- Website --}}
                <td style="padding:0.875rem 1rem;">
                    @if ($partner->website_url)
                        <a href="{{ $partner->website_url }}" target="_blank"
                           style="font-size:0.75rem;color:#2A7FC1;text-decoration:none;display:block;max-width:140px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"
                           onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">
                            {{ parse_url($partner->website_url, PHP_URL_HOST) }}
                        </a>
                    @else
                        <span style="color:#B0CCDF;">—</span>
                    @endif
                </td>

                {{-- Status --}}
                <td style="padding:0.875rem 1rem;">
                    <span style="font-size:0.65rem;font-weight:700;padding:0.2rem 0.5rem;border-radius:2px;
                        {{ $partner->is_active ? 'background:#F0FFF4;color:#276749;' : 'background:#FEF8EC;color:#B8860B;' }}">
                        {{ $partner->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </td>

                {{-- Urutan --}}
                <td style="padding:0.875rem 1rem;font-size:0.825rem;color:#4A6580;" class="sort-order-cell">
                    {{ $partner->sort_order }}
                </td>

                {{-- Aksi --}}
                <td style="padding:0.875rem 1rem;">
                    <div style="display:flex;gap:0.5rem;">
                        <a href="{{ route('admin.partners.edit', $partner) }}"
                           style="font-size:0.7rem;font-weight:700;padding:0.3rem 0.625rem;border:1.5px solid #2A7FC1;border-radius:3px;color:#2A7FC1;text-decoration:none;">
                            Edit
                        </a>
                        <form method="POST" action="{{ route('admin.partners.destroy', $partner) }}"
                              onsubmit="return confirm('Hapus mitra {{ addslashes($partner->name) }}?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    style="font-size:0.7rem;font-weight:700;padding:0.3rem 0.625rem;border:1.5px solid #C0392B;border-radius:3px;color:#C0392B;background:transparent;cursor:pointer;">
                                Hapus
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="padding:3rem;text-align:center;color:#B0CCDF;font-size:0.875rem;">
                    Belum ada mitra untuk kategori ini.
                    <a href="{{ route('admin.partners.create') }}" style="color:#2A7FC1;">Tambah sekarang →</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if ($partners->hasPages())
<div style="margin-top:1rem;display:flex;justify-content:flex-end;">{{ $partners->links() }}</div>
@endif

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
(function () {
    const tbody     = document.getElementById('sortable-body');
    const toast     = document.getElementById('sort-toast');
    const toastMsg  = document.getElementById('sort-toast-msg');
    const toastIcon = document.getElementById('sort-toast-icon');
    const hasSearch = {{ request('search') ? 'true' : 'false' }};

    if (!tbody || hasSearch) return;

    let timer = null;

    function showToast(msg, isError = false) {
        toastMsg.textContent = msg;
        toastIcon.style.color = isError ? '#f87171' : '#4ade80';
        toast.style.display = 'flex';
        clearTimeout(timer);
        timer = setTimeout(() => { toast.style.display = 'none'; }, 2500);
    }

    function updateLabels() {
        tbody.querySelectorAll('tr[data-id]').forEach((row, i) => {
            const cell = row.querySelector('.sort-order-cell');
            if (cell) cell.textContent = i;
        });
    }

    Sortable.create(tbody, {
        handle: '.drag-handle',
        animation: 150,
        ghostClass: 'sortable-ghost',
        onEnd() {
            const ids = [...tbody.querySelectorAll('tr[data-id]')]
                .map(row => parseInt(row.dataset.id));
            updateLabels();

            // Kirim category supaya server tahu konteksnya
            axios.post('{{ route('admin.partners.reorder') }}', {
                ids,
                category: '{{ $activeTab }}'
            })
            .then(() => showToast('Urutan berhasil disimpan'))
            .catch(() => showToast('Gagal menyimpan urutan', true));
        },
    });
})();
</script>
<style>.sortable-ghost { opacity:0.4; background:#EEF4FB !important; }</style>
@endpush