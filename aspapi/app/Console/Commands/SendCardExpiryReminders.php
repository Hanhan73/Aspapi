<?php

namespace App\Console\Commands;

use App\Mail\CardExpiryReminderMail;
use App\Models\Member;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendCardExpiryReminders extends Command
{
    protected $signature   = 'aspapi:send-card-expiry-reminders';
    protected $description = 'Kirim reminder email ke anggota yang kartunya akan kadaluarsa (H-30, H-7, H-1)';

    /**
     * Hari-hari sebelum kadaluarsa yang memicu reminder.
     */
    private array $triggerDays = [30, 7, 1];

    public function handle(): int
    {
        $today = now()->startOfDay();
        $sent  = 0;
        $skipped = 0;

        foreach ($this->triggerDays as $daysLeft) {
            $targetDate = $today->copy()->addDays($daysLeft);

            // Cari anggota aktif yang active_until tepat = targetDate
            $members = Member::with('user')
                ->where('status', 'active')
                ->whereNotNull('active_until')
                ->whereDate('active_until', $targetDate->toDateString())
                ->get();

            foreach ($members as $member) {
                if (!$member->user || !$member->user->email) {
                    $skipped++;
                    continue;
                }

                try {
                    Mail::to($member->user->email)
                        ->send(new CardExpiryReminderMail($member, $daysLeft));

                    $sent++;
                    $this->info("✓ [{$daysLeft}h] {$member->full_name} <{$member->user->email}>");

                    Log::info("CardExpiryReminder sent", [
                        'member_id'    => $member->id,
                        'email'        => $member->user->email,
                        'days_left'    => $daysLeft,
                        'active_until' => $member->active_until->toDateString(),
                    ]);
                } catch (\Exception $e) {
                    $skipped++;
                    $this->error("✗ [{$daysLeft}h] {$member->full_name}: {$e->getMessage()}");
                    Log::error("CardExpiryReminder failed", [
                        'member_id' => $member->id,
                        'error'     => $e->getMessage(),
                    ]);
                }
            }
        }

        $this->info("Selesai. Terkirim: {$sent}, Gagal/Skip: {$skipped}");
        return Command::SUCCESS;
    }
}