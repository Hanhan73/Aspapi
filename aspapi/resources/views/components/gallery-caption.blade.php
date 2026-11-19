{{--
    Caption galeri — satu sumber untuk publik, preview admin, dan lightbox.
    Pemakaian: <x-gallery-caption :album="$album" :photo="$photo" />
    Semua teks di-escape oleh {{ }}; jangan ganti ke {!! !!}.
--}}
@props(['album', 'photo' => null])

@php
    $lines     = $album->captionLines($photo);
    $noteIndex = filled($photo?->note) ? count($lines) - 1 : -1;
@endphp

@if (count($lines))
    <div {{ $attributes->merge(['class' => 'gallery-caption space-y-0.5 text-sm leading-snug']) }}>
        @foreach ($lines as $i => $line)
            <p class="{{ $i === 0 ? 'font-semibold' : ($i === $noteIndex ? 'italic opacity-90' : 'opacity-90') }}">{{ $line }}</p>
        @endforeach
    </div>
@endif
