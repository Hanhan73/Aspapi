<?php

namespace App\Helpers;

use App\Models\Region;
use App\Models\User;

class NotificationEmail
{
    /**
     * Email notifikasi admin pusat.
     * Priority: notification_email user admin → env ADMIN_NOTIFICATION_EMAIL
     */
    public static function admin(): string
    {
        $user = User::where('role', 'admin')
            ->whereNotNull('notification_email')
            ->value('notification_email');

        return $user ?? config('mail.admin_email', 'admin@aspapi.id');
    }

    /**
     * Email notifikasi bendahara.
     * Priority: notification_email user bendahara → env BENDAHARA_NOTIFICATION_EMAIL
     */
    public static function bendahara(): string
    {
        $user = User::where('role', 'bendahara')
            ->whereNotNull('notification_email')
            ->value('notification_email');

        return $user ?? config('mail.bendahara_email', 'bendahara@aspapi.id');
    }

    /**
     * Email notifikasi admin daerah.
     * Priority: notification_email di tabel regions
     *           → notification_email user aspapi_daerah
     *           → email login user aspapi_daerah
     */
    public static function daerah(int $regionId): ?string
    {
        $region = Region::with('activeUser')->find($regionId);

        if (!$region) {
            return null;
        }

        return $region->notification_email
            ?? $region->activeUser?->notification_email
            ?? $region->activeUser?->email;
    }
}