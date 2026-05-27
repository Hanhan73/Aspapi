<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SeminarQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'seminar_id', 'question',
        'option_a', 'option_b', 'option_c', 'option_d', 'option_e',
        'correct_answer', 'sort_order',
    ];

    public function seminar()
    {
        return $this->belongsTo(Seminar::class);
    }

    public function answers()
    {
        return $this->hasMany(SeminarAttemptAnswer::class, 'question_id');
    }

    // Selalu 5 opsi — E wajib ada
    public function getOptions(): array
    {
        return [
            'a' => $this->option_a,
            'b' => $this->option_b,
            'c' => $this->option_c,
            'd' => $this->option_d,
            'e' => $this->option_e,
        ];
    }

    /**
     * Kembalikan opsi dengan urutan diacak.
     * Key tetap asli (a/b/c/d/e) supaya penilaian jawaban tetap valid.
     * Hanya urutan tampilnya yang berubah.
     *
     * @return array<string, string>  ['c' => '...', 'a' => '...', ...]
     */
    public function getShuffledOptions(): array
    {
        $options = $this->getOptions();
        $keys    = array_keys($options);
        shuffle($keys);

        $shuffled = [];
        foreach ($keys as $key) {
            $shuffled[$key] = $options[$key];
        }

        return $shuffled;
    }
}