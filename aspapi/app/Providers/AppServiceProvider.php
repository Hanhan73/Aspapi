<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Set locale Carbon ke Indonesia — semua ->translatedFormat(), ->diffForHumans(), dll jadi Bahasa Indonesia
        Carbon::setLocale('id');
        Date::setLocale('id');
    }
}