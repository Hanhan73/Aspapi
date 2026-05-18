<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
 
Schedule::command('aspapi:send-card-expiry-reminders')
    ->dailyAt('08:00')          // Kirim tiap hari jam 08.00
    ->withoutOverlapping()      // Tidak jalan paralel jika proses sebelumnya belum selesai
    ->runInBackground()         // Tidak block proses lain
    ->appendOutputTo(storage_path('logs/card-expiry-reminders.log')); // Log output ke file

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
