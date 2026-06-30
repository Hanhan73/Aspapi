<?php

namespace App\Helpers;

class Terbilang
{
    protected static array $angka = [
        '',
        'satu',
        'dua',
        'tiga',
        'empat',
        'lima',
        'enam',
        'tujuh',
        'delapan',
        'sembilan',
        'sepuluh',
        'sebelas',
    ];

    public static function make(int $n): string
    {
        if ($n < 0) {
            return 'minus ' . self::make(abs($n));
        }

        if ($n < 12) {
            return trim(self::$angka[$n]);
        }

        if ($n < 20) {
            return trim(self::make($n - 10) . ' belas');
        }

        if ($n < 100) {
            return trim(self::make(intdiv($n, 10)) . ' puluh ' . self::make($n % 10));
        }

        if ($n < 200) {
            return trim('seratus ' . self::make($n - 100));
        }

        if ($n < 1000) {
            return trim(self::make(intdiv($n, 100)) . ' ratus ' . self::make($n % 100));
        }

        if ($n < 2000) {
            return trim('seribu ' . self::make($n - 1000));
        }

        if ($n < 1000000) {
            return trim(self::make(intdiv($n, 1000)) . ' ribu ' . self::make($n % 1000));
        }

        if ($n < 1000000000) {
            return trim(self::make(intdiv($n, 1000000)) . ' juta ' . self::make($n % 1000000));
        }

        return trim(self::make(intdiv($n, 1000000000)) . ' miliar ' . self::make($n % 1000000000));
    }
}
