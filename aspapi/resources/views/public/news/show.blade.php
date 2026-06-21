@extends('layouts.app')

@php
$title = $news->title;
$description = $news->excerpt ?? Str::limit(strip_tags($news->body), 155);
$ogImage = $news->thumbnail ? Storage::url($news->thumbnail) : null;
$ogType = 'article';
@endphp

@section('content')

{{-- ══════════════════════════════════════════════
     HERO / HEADER ARTIKEL
══════════════════════════════════════════════ --}}
<section class="relative overflow-hidden bg-gradient-to-br from-navy-dark via-primary-600 to-primary py-16">

    <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-accent-red via-accent-yellow to-accent-yellow"></div>
    <div class="absolute -right-32 -top-32 w-96 h-96 rounded-full bg-white/5 pointer-events-none"></div>

    <div class="relative max-w-4xl mx-auto px-6">

        {{-- Breadcrumb --}}
        <nav class="flex items-center gap-2 text-2xs text-primary-200 mb-6">
            <a href="{{ route('home') }}" class="hover:text-white transition-colors">Beranda</a>
            <span>/</span>
            <a href="{{ route('news.index') }}" class="hover:text-white transition-colors">Berita</a>
            <span>/</span>
            <span class="text-white line-clamp-1">{{ Str::limit($news->title, 40) }}</span>
        </nav>

        {{-- Category & Date --}}
        <div class="flex items-center gap-3 mb-4">
            @if($news->category)
            <span
                class="inline-flex items-center px-2.5 py-1 rounded text-2xs font-bold tracking-widest uppercase bg-accent-yellow/20 text-accent-yellow border border-accent-yellow/30">
                {{ $news->category }}
            </span>
            @endif
            <span class="text-primary-200 text-2xs">
                {{ $news->published_at?->translatedFormat('d F Y') ?? $news->created_at->translatedFormat('d F Y') }}
            </span>
            @if($news->views > 0)
            <span class="text-primary-200 text-2xs flex items-center gap-1">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                {{ number_format($news->views) }} dibaca
            </span>
            @endif
        </div>

        {{-- Title --}}
        <h1 class="font-display text-white text-3xl leading-tight max-w-3xl">
            {{ $news->title }}
        </h1>

    </div>
</section>

{{-- ══════════════════════════════════════════════
     KONTEN ARTIKEL
══════════════════════════════════════════════ --}}
<section class="py-14 bg-neutral-50">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-[1fr_300px] gap-10 items-start">

            {{-- ── MAIN CONTENT ── --}}
            <div>

 {{-- Thumbnail --}}
@if($news->thumbnail)
<div class="rounded-lg overflow-hidden mb-8 shadow-card bg-neutral-100">
    <img src="{{ Storage::url($news->thumbnail) }}"
         alt="{{ $news->title }}"
         class="w-full h-auto object-contain"/>
</div>
@endif

                {{-- Excerpt / Lead --}}
                @if($news->excerpt)
                <p
                    class="text-base text-neutral-600 leading-relaxed font-medium border-l-4 border-accent-yellow pl-5 mb-8 italic">
                    {{ $news->excerpt }}
                </p>
                @endif

                {{-- Body --}}
                <div class="prose-aspapi">
                    {!! $news->body !!}
                </div>

                {{-- Share & Back --}}
                <div class="mt-10 pt-8 border-t border-neutral-200 flex flex-wrap items-center justify-between gap-4">
                    <a href="{{ route('news.index') }}" class="btn btn-outline text-sm">
                        ← Kembali ke Berita
                    </a>

                    <div class="flex items-center gap-2">
                        <span class="text-2xs text-neutral-400 font-bold tracking-widest uppercase">Bagikan:</span>
                        <a href="https://twitter.com/intent/tweet?text={{ urlencode($news->title) }}&url={{ urlencode(request()->url()) }}"
                            target="_blank" rel="noopener"
                            class="w-8 h-8 rounded bg-neutral-100 hover:bg-primary hover:text-white text-neutral-500 flex items-center justify-center transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.746l7.73-8.835L1.254 2.25H8.08l4.258 5.63zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                            </svg>
                        </a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}"
                            target="_blank" rel="noopener"
                            class="w-8 h-8 rounded bg-neutral-100 hover:bg-primary hover:text-white text-neutral-500 flex items-center justify-center transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                            </svg>
                        </a>
                        <a href="https://wa.me/?text={{ urlencode($news->title . ' ' . request()->url()) }}"
                            target="_blank" rel="noopener"
                            class="w-8 h-8 rounded bg-neutral-100 hover:bg-green-600 hover:text-white text-neutral-500 flex items-center justify-center transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            {{-- ── SIDEBAR ── --}}
            <aside class="flex flex-col gap-6 lg:sticky lg:top-24">

                {{-- Info artikel --}}
                <div class="card p-5">
                    <p class="text-2xs font-bold tracking-widest uppercase text-neutral-400 mb-4">
                        Informasi Artikel
                    </p>
                    <div class="flex flex-col gap-3">
                        <div class="flex items-start gap-3">
                            <svg class="w-4 h-4 text-primary mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <div>
                                <p class="text-2xs text-neutral-300 mb-0.5">Tanggal Tayang</p>
                                <p class="text-xs text-navy font-medium">
                                    {{ $news->published_at?->translatedFormat('d F Y') ?? $news->created_at->translatedFormat('d F Y') }}
                                </p>
                            </div>
                        </div>

                        @if($news->category)
                        <div class="flex items-start gap-3">
                            <svg class="w-4 h-4 text-primary mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                            <div>
                                <p class="text-2xs text-neutral-300 mb-0.5">Kategori</p>
                                <a href="{{ route('news.index', ['kategori' => $news->category]) }}"
                                    class="text-xs text-primary font-medium hover:text-primary-600 transition-colors">
                                    {{ $news->category }}
                                </a>
                            </div>
                        </div>
                        @endif

                        <div class="flex items-start gap-3">
                            <svg class="w-4 h-4 text-primary mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <div>
                                <p class="text-2xs text-neutral-300 mb-0.5">Dibaca</p>
                                <p class="text-xs text-navy font-medium">{{ number_format($news->views) }} kali</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Berita terkait --}}
                @if($related->isNotEmpty())
                <div class="card p-5">
                    <p class="text-2xs font-bold tracking-widest uppercase text-neutral-400 mb-4">
                        Berita Terkait
                    </p>
                    <div class="flex flex-col gap-4">
                        @foreach($related as $rel)
                        <a href="{{ route('news.show', $rel->slug) }}" class="flex gap-3 group">
                            @if($rel->thumbnail)
                            <div class="w-16 h-14 rounded overflow-hidden flex-shrink-0">
                                <img src="{{ Storage::url($rel->thumbnail) }}" alt="{{ $rel->title }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
                            </div>
                            @else
                            <div
                                class="w-16 h-14 rounded bg-primary-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-primary-300" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                                </svg>
                            </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <p
                                    class="text-xs font-bold text-navy leading-snug group-hover:text-primary transition-colors line-clamp-2">
                                    {{ $rel->title }}
                                </p>
                                <p class="text-2xs text-neutral-300 mt-1">
                                    {{ $rel->published_at?->translatedFormat('d M Y') ?? $rel->created_at->translatedFormat('d M Y') }}
                                </p>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- CTA box --}}
                <div class="card p-5 bg-gradient-to-br from-primary to-primary-600 text-white">
                    <p class="text-xs font-bold tracking-widest uppercase text-accent-yellow mb-2">
                        Bergabung Sekarang
                    </p>
                    <h3 class="font-display text-base leading-snug mb-3">
                        Jadilah Anggota ASPAPI
                    </h3>
                    <p class="text-xs text-primary-200 leading-relaxed mb-4">
                        Dapatkan akses penuh ke seluruh program, sertifikasi, dan jaringan profesional ASPAPI.
                    </p>
                    <a href="{{ route('members.register') }}" class="btn btn-accent-yellow w-full text-center text-xs">
                        Daftar Sekarang
                    </a>
                </div>

            </aside>
        </div>
    </div>
</section>

@endsection

@push('styles')
<style>
/* ── Prose Styling untuk konten body berita ── */
.prose-aspapi {
    color: #4A6580;
    line-height: 1.85;
    font-size: 0.9375rem;
}

.prose-aspapi h2 {
    font-family: "DM Serif Display", Georgia, serif;
    font-size: 1.35rem;
    color: #1A2A3A;
    margin-top: 2rem;
    margin-bottom: 0.75rem;
}

.prose-aspapi h3 {
    font-size: 1.05rem;
    font-weight: 700;
    color: #1A2A3A;
    margin-top: 1.5rem;
    margin-bottom: 0.5rem;
}

.prose-aspapi h4 {
    font-size: 0.9rem;
    font-weight: 700;
    color: #1A5F9A;
    margin-top: 1.25rem;
    margin-bottom: 0.4rem;
}

.prose-aspapi p {
    margin-bottom: 1.15rem;
}

.prose-aspapi strong {
    color: #1A2A3A;
    font-weight: 700;
}

.prose-aspapi em {
    color: #2A7FC1;
}

.prose-aspapi a {
    color: #2A7FC1;
    text-decoration: underline;
    text-underline-offset: 3px;
}

.prose-aspapi a:hover {
    color: #1A5F9A;
}

.prose-aspapi ul {
    list-style: none;
    padding: 0;
    margin-bottom: 1.15rem;
}

.prose-aspapi ul li {
    padding-left: 1.25rem;
    position: relative;
    margin-bottom: 0.4rem;
}

.prose-aspapi ul li::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0.55rem;
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: #E8B84B;
}

.prose-aspapi ol {
    list-style: decimal;
    padding-left: 1.5rem;
    margin-bottom: 1.15rem;
}

.prose-aspapi ol li {
    margin-bottom: 0.4rem;
    padding-left: 0.25rem;
}

.prose-aspapi blockquote {
    border-left: 4px solid #E8B84B;
    padding-left: 1.25rem;
    color: #374F63;
    font-style: italic;
    margin: 1.5rem 0;
}

.prose-aspapi img {
    border-radius: 6px;
    max-width: 100%;
    height: auto;
    margin: 1.5rem auto;
    display: block;
}

.prose-aspapi hr {
    border: none;
    border-top: 1.5px solid #D6E8F7;
    margin: 2rem 0;
}
</style>
@endpush