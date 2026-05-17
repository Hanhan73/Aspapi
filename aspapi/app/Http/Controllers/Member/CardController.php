<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class CardController extends Controller
{
    /**
     * Halaman preview kartu anggota
     * Route: GET /member/kartu → name: member.card
     */
    public function show()
    {
        $member = auth()->user()->member;
        return view('member.card', compact('member'));
    }

    /**
     * Generate nomor anggota
     * Route: POST /member/kartu/generate → name: member.card.generate
     */
    public function generate()
    {
        $member = auth()->user()->member;

        if (!$member->canGenerateCard()) {
            return back()->with('error', 'Syarat belum terpenuhi untuk generate kartu.');
        }

        if (!$member->member_number) {
            $member->assignMemberNumber();
        }

        if (!$member->active_until) {
            $member->update(['active_until' => now()->addYear()]);
        }

        return redirect()->route('member.card')
            ->with('success', 'Nomor anggota berhasil dibuat!');
    }

    /**
     * Download KTA sebagai PDF 2 halaman
     * Route: GET /member/kartu/download → name: member.card.download
     */
    public function download()
    {
        $member = auth()->user()->member;

        if (!$member->canGenerateCard() || !$member->member_number) {
            return back()->with('error', 'Kartu belum bisa didownload.');
        }

        // ── Background kartu (base64 supaya DomPDF tidak perlu HTTP request) ──
        $frontBase64 = $this->imageToBase64(public_path('images/kta-depan.png'));
        $backBase64  = $this->imageToBase64(public_path('images/kta-belakang.png'));

        // ── Foto anggota ──
        $photoBase64 = null;
        if ($member->photo) {
            $photoBase64 = $this->imageToBase64(Storage::disk('public')->path($member->photo));
        }

        // ── QR Code ──
        $qrBase64 = $this->generateQrBase64($member);

        /*
         * Paper size: CR80 landscape
         * 85.6mm × 53.98mm → dalam poin (1 mm = 2.8346 pt)
         * = 242.64 × 153.07 pt  (landscape: width > height)
         *
         * DomPDF menerima [x1, y1, x2, y2] atau nama.
         * Untuk landscape portrait adalah [0, 0, lebar_pt, tinggi_pt].
         *
         * Body HTML = 2 kartu tinggi (107.96mm) → DomPDF otomatis
         * membaginya menjadi 2 halaman sesuai tinggi paper (53.98mm).
         */
        $pdf = Pdf::loadView('member.card-pdf', compact(
            'member',
            'frontBase64',
            'backBase64',
            'photoBase64',
            'qrBase64'
        ))
        ->setPaper([0, 0, 242.64, 153.07], 'landscape')
        ->setOption('dpi', 150)
        ->setOption('isHtml5ParserEnabled', true)
        ->setOption('isRemoteEnabled', false)
        ->setOption('margin_top', 0)
        ->setOption('margin_right', 0)
        ->setOption('margin_bottom', 0)
        ->setOption('margin_left', 0);

        return $pdf->download('KTA-ASPAPI-' . $member->member_number . '.pdf');
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    /**
     * Konversi file gambar ke data URI base64.
     * Return null jika file tidak ada.
     */
    private function imageToBase64(?string $path): ?string
    {
        if (!$path || !file_exists($path)) {
            return null;
        }

        $ext  = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $mime = match ($ext) {
            'jpg', 'jpeg' => 'image/jpeg',
            'gif'         => 'image/gif',
            'svg'         => 'image/svg+xml',
            default       => 'image/png',
        };

        return 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($path));
    }

    /**
     * Generate QR Code → data URI base64.
     * Mencoba beberapa library yang mungkin tersedia.
     */
    private function generateQrBase64(Member $member): ?string
    {
        $content = implode('|', [
            'NIA:'     . $member->member_number,
            'NAMA:'    . strtoupper($member->full_name),
            'BERLAKU:' . ($member->active_until?->format('d-m-Y') ?? '-'),
            'ASPAPI',
        ]);

        $tmpPath = sys_get_temp_dir() . '/qr_aspapi_' . $member->id . '_' . time() . '.png';

        try {
            if (class_exists(\chillerlan\QRCode\QRCode::class)) {
                file_put_contents($tmpPath, (new \chillerlan\QRCode\QRCode())->render($content));

            } elseif (class_exists(\SimpleSoftwareIO\QrCode\Facades\QrCode::class)) {
                \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')
                    ->size(120)->generate($content, $tmpPath);

            } elseif (class_exists(\BaconQrCode\Writer::class)) {
                $renderer = new \BaconQrCode\Renderer\ImageRenderer(
                    new \BaconQrCode\Renderer\RendererStyle\RendererStyle(120),
                    new \BaconQrCode\Renderer\Image\ImagickImageBackEnd()
                );
                (new \BaconQrCode\Writer($renderer))->writeFile($content, $tmpPath);
            }

            if (file_exists($tmpPath)) {
                $b64 = 'data:image/png;base64,' . base64_encode(file_get_contents($tmpPath));
                @unlink($tmpPath);
                return $b64;
            }
        } catch (\Throwable $e) {
            \Log::warning('QR Code generation failed: ' . $e->getMessage());
        }

        return null;
    }
}