<?php

namespace App\Mail;

use App\Models\Member;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CardExpiryReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly Member $member,
        public readonly int    $daysLeft,
    ) {}

    public function envelope(): Envelope
    {
        $label = match (true) {
            $this->daysLeft <= 1  => 'Kartu Anggota Anda Kadaluarsa BESOK',
            $this->daysLeft <= 7  => "Kartu Anggota Anda Kadaluarsa dalam {$this->daysLeft} Hari",
            default               => 'Kartu Anggota Anda Akan Segera Kadaluarsa — Segera Perpanjang',
        };

        return new Envelope(subject: $label . ' — ASPAPI');
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.card-expiry-reminder',
        );
    }
}