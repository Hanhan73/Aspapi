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
     */
    public function index()
    {
        $member = auth()->user()->member;
        return view('member.card', compact('member'));
    }

    /**
     * Generate nomor anggota lalu redirect ke preview
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

        // Set masa berlaku 1 tahun dari sekarang jika belum ada
        if (!$member->active_until) {
            $member->update(['active_until' => now()->addYear()]);
        }

        return redirect()->route('member.card')
            ->with('success', 'Nomor anggota berhasil dibuat!');
    }

    /**
     * Download KTA sebagai PDF 2 halaman (depan + belakang)
     */
    public function download()
    {
        $member = auth()->user()->member;

        if (!$member->canGenerateCard() || !$member->member_number) {
            return back()->with('error', 'Kartu belum bisa didownload.');
        }

        // ── 1. Background depan (base64 dari storage atau public) ──────────
        $frontBase64 = $this->imageToBase64(public_path('images/kta-depan.png'));
        $backBase64  = $this->imageToBase64(public_path('images/kta-belakang.png'));

        // ── 2. Foto anggota ─────────────────────────────────────────────────
        $photoBase64 = null;
        if ($member->photo) {
            $photoPath = Storage::disk('public')->path($member->photo);
            $photoBase64 = $this->imageToBase64($photoPath);
        }

        // ── 3. QR Code ──────────────────────────────────────────────────────
        $qrBase64 = $this->generateQrBase64($member);

        // ── 4. Render PDF ───────────────────────────────────────────────────
        $pdf = Pdf::loadView('member.card-pdf', compact(
            'member',
            'frontBase64',
            'backBase64',
            'photoBase64',
            'qrBase64'
        ))->setPaper([0, 0, 242.65, 153.07], 'landscape'); // CR80 dalam points

        return $pdf->download('KTA-ASPAPI-' . $member->member_number . '.pdf');
    }

    // ── Helpers ─────────────────────────────────────────────────────────────

    /**
     * Konversi file gambar ke string base64.
     * Kembalikan null jika file tidak ada.
     */
    private function imageToBase64(?string $path): ?string
    {
        if (!$path || !file_exists($path)) {
            return null;
        }
        return base64_encode(file_get_contents($path));
    }

    /**
     * Buat QR Code dan kembalikan sebagai base64 PNG.
     * Coba beberapa library yang mungkin tersedia.
     */
    private function generateQrBase64(Member $member): ?string
    {
        $content = implode('|', [
            'NIA:'    . $member->member_number,
            'NAMA:'   . strtoupper($member->full_name),
            'BERLAKU:'. ($member->active_until?->format('d-m-Y') ?? '-'),
            'ASPAPI',
        ]);

        $tmpPath = sys_get_temp_dir() . '/qr_' . $member->member_number . '_' . time() . '.png';

        try {
            if (class_exists(\chillerlan\QRCode\QRCode::class)) {
                $qr = new \chillerlan\QRCode\QRCode();
                file_put_contents($tmpPath, $qr->render($content));

            } elseif (class_exists(\SimpleSoftwareIO\QrCode\Facades\QrCode::class)) {
                \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')
                    ->size(120)
                    ->generate($content, $tmpPath);

            } elseif (class_exists(\BaconQrCode\Writer::class)) {
                $renderer = new \BaconQrCode\Renderer\ImageRenderer(
                    new \BaconQrCode\Renderer\RendererStyle\RendererStyle(120),
                    new \BaconQrCode\Renderer\Image\ImagickImageBackEnd()
                );
                (new \BaconQrCode\Writer($renderer))->writeFile($content, $tmpPath);

            } else {
                // Fallback: Google Chart API (butuh internet, hanya dev)
                $url  = 'https://chart.googleapis.com/chart?chs=120x120&cht=qr&chl=' . urlencode($content);
                $data = @file_get_contents($url);
                if ($data) {
                    file_put_contents($tmpPath, $data);
                }
            }

            if (file_exists($tmpPath)) {
                $base64 = base64_encode(file_get_contents($tmpPath));
                unlink($tmpPath);
                return $base64;
            }
        } catch (\Throwable $e) {
            \Log::warning('QR Code generation failed: ' . $e->getMessage());
        }

        return null;
    }
}