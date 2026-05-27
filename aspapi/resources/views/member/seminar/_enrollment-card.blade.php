{{-- resources/views/member/seminar/_enrollment-card.blade.php --}}
@php
    $statusColor = match($enrollment->status) {
        'completed'      => 'bg-green-50 text-green-700',
        'post_test_done' => 'bg-orange-50 text-orange-700',
        'material_read'  => 'bg-blue-50 text-blue-700',
        'pre_test_done'  => 'bg-indigo-50 text-indigo-700',
        default          => 'bg-neutral-100 text-neutral-500',
    };

    $progressStep = match($enrollment->status) {
        'completed'      => 4,
        'post_test_done' => 3,
        'material_read'  => 3,
        'pre_test_done'  => 2,
        default          => 1,
    };
@endphp

<div class="bg-white border border-neutral-200 rounded-xl overflow-hidden shadow-sm
            {{ $isCurrentPeriod ? 'hover:shadow-md' : 'opacity-90' }} transition-shadow">
    <div class="flex items-stretch">

        {{-- Thumbnail --}}
        <div class="w-28 flex-shrink-0 bg-neutral-100 relative">
            <img src="{{ $enrollment->seminar->thumbnail_url }}"
                 alt="{{ $enrollment->seminar->title }}"
                 class="w-full h-full object-contain p-2">
            @if ($enrollment->seminar->category)
            <span class="absolute bottom-1.5 left-1.5 text-2xs font-bold px-1.5 py-0.5 rounded bg-black/50 text-white leading-tight">
                {{ $enrollment->seminar->category }}
            </span>
            @endif
        </div>

        {{-- Konten --}}
        <div class="flex-1 min-w-0 p-4 flex flex-col justify-between">
            <div>
                <div class="flex items-start justify-between gap-3">
                    <h3 class="font-bold text-navy text-sm leading-snug">
                        {{ $enrollment->seminar->title }}
                    </h3>
                    <span class="inline-block text-2xs font-bold px-2.5 py-0.5 rounded-full flex-shrink-0 {{ $statusColor }}">
                        {{ $enrollment->status_label }}
                    </span>
                </div>

                {{-- Progress steps --}}
                <div class="flex items-center mt-3">
                    @php $steps = ['Pre-Test', 'Materi', 'Post-Test', 'Lulus']; @endphp
                    @foreach ($steps as $si => $stepLabel)
                    @php
                        $stepNum = $si + 1;
                        $isDone  = $progressStep > $stepNum;
                        $isAct   = $progressStep === $stepNum;
                    @endphp
                    <div class="flex items-center {{ $loop->last ? '' : 'flex-1' }}">
                        <div class="flex flex-col items-center">
                            <div class="w-5 h-5 rounded-full flex items-center justify-center text-2xs font-bold
                                {{ $isDone ? 'bg-green-500 text-white' : ($isAct ? 'bg-primary text-white' : 'bg-neutral-100 text-neutral-400') }}">
                                @if ($isDone) ✓ @else {{ $stepNum }} @endif
                            </div>
                            <span class="text-2xs mt-0.5 whitespace-nowrap
                                {{ $isAct ? 'text-primary font-bold' : ($isDone ? 'text-green-600' : 'text-neutral-400') }}">
                                {{ $stepLabel }}
                            </span>
                        </div>
                        @if (! $loop->last)
                        <div class="flex-1 h-px mx-1 mb-4 {{ $isDone ? 'bg-green-400' : 'bg-neutral-200' }}"></div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Footer --}}
            <div class="flex items-center justify-between mt-3 pt-3 border-t border-neutral-100">
                <div>
                    @if ($enrollment->certificate)
                        <p class="text-2xs text-neutral-400">
                            No. <span class="font-mono font-semibold text-navy">{{ $enrollment->certificate->certificate_number }}</span>
                        </p>
                        <p class="text-2xs text-neutral-400 mt-0.5">
                            Skor: <span class="font-bold text-green-600">{{ $enrollment->certificate->score }}</span>
                        </p>
                    @else
                        <p class="text-2xs text-neutral-400">
                            Terdaftar {{ $enrollment->created_at->diffForHumans() }}
                        </p>
                    @endif
                </div>
                <div class="flex gap-2">
                    @if ($enrollment->certificate)
                        <a href="{{ route('member.seminar.certificate', $enrollment->certificate) }}"
                           target="_blank"
                           class="text-xs font-bold px-3 py-1.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                            Sertifikat
                        </a>
                    @endif
                    <a href="{{ route('member.seminar.show', $enrollment) }}"
                       class="text-xs font-bold px-3 py-1.5 bg-primary/10 text-primary rounded-lg hover:bg-primary/20 transition">
                        {{ $enrollment->isCompleted() ? 'Lihat Detail' : 'Lanjutkan →' }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>