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
     * Generate nomor anggota lalu redirect ke preview
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
     * Download KTA sebagai PDF 2 halaman (depan + belakang)
     * Route: GET /member/kartu/download → name: member.card.download
     */
    public function download()
    {
        $member = auth()->user()->member;

        if (!$member->canGenerateCard() || !$member->member_number) {
            return back()->with('error', 'Kartu belum bisa didownload.');
        }

        // ── Load background images ────────────────────────────────────
        $frontBase64 = $this->imageToBase64(public_path('images/kta-depan.png'));
        $backBase64  = $this->imageToBase64(public_path('images/kta-belakang.png'));

        // ── Load foto anggota ─────────────────────────────────────────
        $photoBase64 = null;
        if ($member->photo) {
            $photoPath = Storage::disk('public')->path($member->photo);
            if (file_exists($photoPath)) {
                $photoBase64 = $this->imageToBase64($photoPath);
            }
        }

        // ── Generate QR Code ──────────────────────────────────────────
        $qrBase64 = $this->generateQrBase64($member);

        // ── Generate PDF ──────────────────────────────────────────────
        //
        // CR80 landscape: 85.6mm × 53.98mm
        // Dalam points (1mm = 2.8346pt):
        //   W = 85.6 × 2.8346 = 242.64pt
        //   H = 53.98 × 2.8346 = 153.01pt
        //
        // DomPDF setPaper([0, 0, width, height]) dalam orientasi portrait.
        // Untuk hasil landscape, kita set width > height:
        //   → [0, 0, 242.64, 153.01] dengan orientasi 'portrait'
        //   (DomPDF akan render sesuai ukuran tersebut)
        //
        // CSS di blade menggunakan: @page { size: 85.6mm 53.98mm }
        // body height: 107.96mm (2 × 53.98mm) agar 2 halaman pas.

        $pdf = Pdf::loadView('member.card-pdf', compact(
            'member',
            'frontBase64',
            'backBase64',
            'photoBase64',
            'qrBase64'
        ))
            // CR80 landscape dalam points: [x1, y1, x2(width), y2(height)]
            ->setPaper([0, 0, 242.64, 153.01], 'portrait')
            ->setOption([
                'dpi'                  => 150,
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled'      => false,
                'margin_top'           => 0,
                'margin_right'         => 0,
                'margin_bottom'        => 0,
                'margin_left'          => 0,
                'defaultFont'          => 'Arial',
            ]);

        $filename = 'KTA-ASPAPI-' . $member->member_number . '.pdf';

        return $pdf->download($filename);
    }

    // ── Private helpers ───────────────────────────────────────────────

    /**
     * Konversi file gambar ke data URI base64.
     * DomPDF tidak bisa load file via path langsung, harus base64.
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
            'webp'        => 'image/webp',
            default       => 'image/png',
        };

        $data = file_get_contents($path);
        if ($data === false) {
            return null;
        }

        return 'data:' . $mime . ';base64,' . base64_encode($data);
    }

    /**
     * Generate QR Code dan kembalikan sebagai data URI base64.
     * Mencoba beberapa library QR yang umum diinstall.
     */
    private function generateQrBase64(Member $member): ?string
    {
        // Konten QR
        $content = implode("\n", [
            'NIA: '     . $member->member_number,
            'NAMA: '    . strtoupper($member->full_name),
            'BERLAKU: ' . ($member->active_until?->format('d-m-Y') ?? '-'),
            'ASPAPI',
        ]);

        $tmpPath = sys_get_temp_dir() . '/qr_aspapi_' . $member->id . '_' . time() . '.png';

        try {
            // ── Opsi 1: chillerlan/php-qrcode (recommended) ──────────
            if (class_exists(\chillerlan\QRCode\QRCode::class)) {
                $options = new \chillerlan\QRCode\QROptions([
                    'outputType' => \chillerlan\QRCode\Output\QROutputInterface::GDIMAGE_PNG,
                    'scale'      => 5,
                    'imageBase64' => false,
                ]);
                $qr   = new \chillerlan\QRCode\QRCode($options);
                $data = $qr->render($content);
                file_put_contents($tmpPath, $data);
            }

            // ── Opsi 2: endroid/qr-code ──────────────────────────────
            elseif (class_exists(\Endroid\QrCode\QrCode::class)) {
                $qr     = \Endroid\QrCode\QrCode::create($content)
                    ->setSize(120)
                    ->setMargin(2);
                $writer = new \Endroid\QrCode\Writer\PngWriter();
                $result = $writer->write($qr);
                file_put_contents($tmpPath, $result->getString());
            }

            // ── Opsi 3: simplesoftwareio/simple-qrcode ────────────────
            elseif (class_exists(\SimpleSoftwareIO\QrCode\Facades\QrCode::class)) {
                $png = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')
                    ->size(120)
                    ->generate($content);
                file_put_contents($tmpPath, $png);
            }

            // ── Opsi 4: bacon/bacon-qr-code ───────────────────────────
            elseif (class_exists(\BaconQrCode\Writer::class)) {
                $renderer = new \BaconQrCode\Renderer\ImageRenderer(
                    new \BaconQrCode\Renderer\RendererStyle\RendererStyle(120),
                    new \BaconQrCode\Renderer\Image\ImagickImageBackEnd()
                );
                (new \BaconQrCode\Writer($renderer))->writeFile($content, $tmpPath);
            }

            // ── Fallback: generate QR sederhana via GD ─────────────────
            else {
                return $this->generateQrFallback($content);
            }

            // Baca file yang sudah di-generate
            if (file_exists($tmpPath) && filesize($tmpPath) > 0) {
                $b64 = 'data:image/png;base64,' . base64_encode(file_get_contents($tmpPath));
                @unlink($tmpPath);
                return $b64;
            }
        } catch (\Throwable $e) {
            \Log::warning('QR Code generation failed: ' . $e->getMessage());
            @unlink($tmpPath);
        }

        return null;
    }

    /**
     * Fallback: buat QR Code placeholder pakai GD jika tidak ada library.
     * Ini hanya placeholder visual, bukan QR Code sungguhan.
     * Install salah satu library QR di atas untuk QR yang bisa di-scan.
     */
    private function generateQrFallback(string $content): ?string
    {
        if (!extension_loaded('gd')) {
            return null;
        }

        try {
            $size  = 120;
            $img   = imagecreate($size, $size);
            $white = imagecolorallocate($img, 255, 255, 255);
            $black = imagecolorallocate($img, 0, 0, 0);

            // Grid sederhana sebagai placeholder QR
            $cells  = 21;
            $cell   = (int) floor($size / $cells);
            for ($i = 0; $i < $cells; $i++) {
                for ($j = 0; $j < $cells; $j++) {
                    // Buat pola sederhana
                    if (($i + $j) % 3 === 0 || ($i < 7 && $j < 7)
                        || ($i < 7 && $j > $cells - 8)
                        || ($i > $cells - 8 && $j < 7)
                    ) {
                        imagefilledrectangle(
                            $img,
                            $j * $cell,
                            $i * $cell,
                            ($j + 1) * $cell - 1,
                            ($i + 1) * $cell - 1,
                            $black
                        );
                    }
                }
            }

            // Border
            imagerectangle($img, 0, 0, $size - 1, $size - 1, $black);

            ob_start();
            imagepng($img);
            $data = ob_get_clean();
            imagedestroy($img);

            return 'data:image/png;base64,' . base64_encode($data);
        } catch (\Throwable $e) {
            return null;
        }
    }
}
