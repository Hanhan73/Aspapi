{{--
    Partial: admin/seminar/_question-form.blade.php
    Props:
      $q           — SeminarQuestion|null (null = form tambah baru)
      $submitLabel — string teks tombol submit
--}}

<div class="space-y-3">
    <div>
        <label class="block text-xs font-bold text-neutral-500 mb-1">
            Pertanyaan <span class="text-red-500">*</span>
        </label>
        <textarea name="question" rows="3" required
                  class="w-full border border-neutral-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 resize-none"
                  placeholder="Tulis pertanyaan...">{{ old('question', $q?->question) }}</textarea>
    </div>

    @foreach (['a' => 'A', 'b' => 'B', 'c' => 'C', 'd' => 'D', 'e' => 'E'] as $key => $label)
    <div>
        <label class="block text-xs font-bold text-neutral-500 mb-1">
            Opsi {{ $label }} <span class="text-red-500">*</span>
        </label>
        <input type="text" name="option_{{ $key }}" required
               value="{{ old('option_' . $key, $q?->{'option_' . $key}) }}"
               class="w-full border border-neutral-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30"
               placeholder="Teks opsi {{ $label }}...">
    </div>
    @endforeach

    <div>
        <label class="block text-xs font-bold text-neutral-500 mb-1">
            Jawaban Benar <span class="text-red-500">*</span>
        </label>
        <select name="correct_answer" required
                class="w-full border border-neutral-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
            @foreach (['a' => 'A', 'b' => 'B', 'c' => 'C', 'd' => 'D', 'e' => 'E'] as $key => $label)
                <option value="{{ $key }}"
                    {{ old('correct_answer', $q?->correct_answer) === $key ? 'selected' : '' }}>
                    Opsi {{ $label }}
                </option>
            @endforeach
        </select>
    </div>
</div>

<button type="submit"
        class="mt-4 w-full py-2.5 bg-primary text-white text-sm font-bold rounded-lg hover:bg-primary/90 transition">
    {{ $submitLabel }}
</button>