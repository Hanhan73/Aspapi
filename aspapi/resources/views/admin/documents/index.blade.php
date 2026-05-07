@extends('layouts.admin')

@php $title = 'Download Dokumen'; @endphp

@section('content')

<div class="flex items-center gap-3">
    <a href="{{ route('admin.documents.sort') }}"
       class="inline-flex items-center gap-2 px-4 py-2 bg-white text-navy text-sm font-semibold rounded border border-neutral-200 hover:bg-neutral-50 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
        Atur Urutan
    </a>
    <a href="{{ route('admin.documents.create') }}"
       class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-white text-sm font-semibold rounded hover:bg-primary-600 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Dokumen
    </a>
</div>
{{-- Stats --}}
<div class="grid grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-lg border border-neutral-200 px-5 py-4">
        <p class="text-2xs text-neutral-400 uppercase tracking-widest font-semibold">Total Dokumen</p>
        <p class="text-2xl font-bold text-navy mt-1">{{ $documents->total() }}</p>
    </div>
    <div class="bg-white rounded-lg border border-neutral-200 px-5 py-4">
        <p class="text-2xs text-neutral-400 uppercase tracking-widest font-semibold">Ditampilkan Publik</p>
        <p class="text-2xl font-bold text-primary mt-1">{{ $documents->where('is_public', true)->count() }}</p>
    </div>
    <div class="bg-white rounded-lg border border-neutral-200 px-5 py-4">
        <p class="text-2xs text-neutral-400 uppercase tracking-widest font-semibold">Total Unduhan</p>
        <p class="text-2xl font-bold text-accent-yellow mt-1">{{ number_format($documents->sum('download_count')) }}</p>
    </div>
</div>

{{-- Table --}}
<div class="bg-white rounded-lg border border-neutral-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-neutral-200 bg-neutral-50">
                <th class="px-5 py-3 text-left text-2xs font-bold text-neutral-500 uppercase tracking-widest">Judul</th>
                <th class="px-5 py-3 text-left text-2xs font-bold text-neutral-500 uppercase tracking-widest">Kategori</th>
                <th class="px-5 py-3 text-left text-2xs font-bold text-neutral-500 uppercase tracking-widest">Tipe</th>
                <th class="px-5 py-3 text-left text-2xs font-bold text-neutral-500 uppercase tracking-widest">Ukuran</th>
                <th class="px-5 py-3 text-left text-2xs font-bold text-neutral-500 uppercase tracking-widest">Unduhan</th>
                <th class="px-5 py-3 text-left text-2xs font-bold text-neutral-500 uppercase tracking-widest">Status</th>
                <th class="px-5 py-3 text-left text-2xs font-bold text-neutral-500 uppercase tracking-widest">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-neutral-100">
            @forelse($documents as $doc)
            <tr class="hover:bg-neutral-50 transition-colors">
                <td class="px-5 py-3.5">
                    <p class="font-semibold text-navy text-sm leading-snug">{{ $doc->title }}</p>
                    @if($doc->description)
                        <p class="text-2xs text-neutral-400 mt-0.5 line-clamp-1">{{ $doc->description }}</p>
                    @endif
                </td>
                <td class="px-5 py-3.5">
                    @if($doc->category)
                        <span class="px-2 py-0.5 text-2xs font-semibold rounded bg-primary-100 text-primary-600 tracking-wide">
                            {{ $doc->category }}
                        </span>
                    @else
                        <span class="text-neutral-300">—</span>
                    @endif
                </td>
                <td class="px-5 py-3.5">
                    <span class="px-2 py-0.5 text-2xs font-bold rounded tracking-wider
                        @if($doc->file_type === 'PDF') bg-red-50 text-accent-red
                        @elseif(in_array($doc->file_type, ['DOC','DOCX'])) bg-blue-50 text-primary-600
                        @elseif(in_array($doc->file_type, ['XLS','XLSX'])) bg-green-50 text-green-700
                        @elseif(in_array($doc->file_type, ['PPT','PPTX'])) bg-orange-50 text-orange-600
                        @else bg-neutral-100 text-neutral-500
                        @endif">
                        {{ $doc->file_type }}
                    </span>
                </td>
                <td class="px-5 py-3.5 text-xs text-neutral-500 tabular-nums">{{ $doc->file_size_formatted }}</td>
                <td class="px-5 py-3.5 text-xs text-neutral-500 tabular-nums">{{ number_format($doc->download_count) }}x</td>
                <td class="px-5 py-3.5">
                    @if($doc->is_public)
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-2xs font-semibold bg-green-50 text-green-700">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                            Publik
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-2xs font-semibold bg-neutral-100 text-neutral-500">
                            <span class="w-1.5 h-1.5 rounded-full bg-neutral-400"></span>
                            Privat
                        </span>
                    @endif
                </td>
                <td class="px-5 py-3.5">
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.documents.edit', $doc) }}"
                           class="px-3 py-1.5 text-2xs font-bold tracking-wide text-primary border border-primary/30 rounded hover:bg-primary hover:text-white transition-all">
                            Edit
                        </a>
                        <form action="{{ route('admin.documents.destroy', $doc) }}"
                              method="POST"
                              onsubmit="return confirm('Hapus dokumen \'{{ addslashes($doc->title) }}\'?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    class="px-3 py-1.5 text-2xs font-bold tracking-wide text-accent-red border border-accent-red/30 rounded hover:bg-accent-red hover:text-white transition-all">
                                Hapus
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-5 py-16 text-center">
                    <div class="flex flex-col items-center gap-3">
                        <svg class="w-10 h-10 text-neutral-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        <p class="text-sm text-neutral-400">Belum ada dokumen. <a href="{{ route('admin.documents.create') }}" class="text-primary underline">Tambah sekarang</a>.</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($documents->hasPages())
    <div class="px-5 py-3 border-t border-neutral-200 bg-neutral-50">
        {{ $documents->links() }}
    </div>
    @endif
</div>

@endsection