<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class CardController extends Controller
{
    public function show()
    {
        $member = auth()->user()->member;
        return view('member.card', compact('member'));
    }

    public function generate()
    {
        $member = auth()->user()->member;

        if (! $member->canGenerateCard()) {
            return back()->with('error', 'Anda belum memenuhi syarat untuk generate kartu anggota.');
        }

        // Generate nomor anggota kalau belum ada
        if (! $member->member_number) {
            $member->assignMemberNumber();
        }

        // Set active_until kalau belum ada
        if (! $member->active_until) {
            $member->update(['active_until' => now()->addYear()]);
        }

        return back()->with('success', 'Kartu anggota berhasil digenerate!');
    }

    public function download()
    {
        $member = auth()->user()->member;

        if (! $member->member_number) {
            return back()->with('error', 'Generate kartu terlebih dahulu.');
        }

        // Generate QR code ke file sementara
        $qrPath = $this->generateQrCode($member);

        $pdf = Pdf::loadView('member.card-pdf', compact('member', 'qrPath'))
            ->setPaper([0, 0, 242.64, 153.07], 'landscape'); // CR80 dalam points

        // Hapus file QR sementara
        if (file_exists($qrPath)) {
            unlink($qrPath);
        }

        return $pdf->download('KTA-ASPAPI-' . $member->member_number . '.pdf');
    }

    private function generateQrCode(Member $member): string
    {
        // Konten QR: NIA + nama + berlaku sampai
        $content = implode('|', [
            'NIA:' . $member->member_number,
            'NAMA:' . strtoupper($member->full_name),
            'BERLAKU:' . ($member->active_until?->format('d-m-Y') ?? '-'),
            'ASPAPI',
        ]);

        $tmpPath = sys_get_temp_dir() . '/qr_' . $member->member_number . '.png';

        // Cek apakah library Simple QR tersedia
        if (class_exists(\chillerlan\QRCode\QRCode::class)) {
            $qr = new \chillerlan\QRCode\QRCode();
            file_put_contents($tmpPath, $qr->render($content));
        } elseif (class_exists(\BaconQrCode\Renderer\ImageRenderer::class)) {
            // BaconQrCode (bundled dengan DomPDF kadang)
            $renderer = new \BaconQrCode\Renderer\ImageRenderer(
                new \BaconQrCode\Renderer\RendererStyle\RendererStyle(150),
                new \BaconQrCode\Renderer\Image\ImagickImageBackEnd()
            );
            $writer = new \BaconQrCode\Writer($renderer);
            $writer->writeFile($content, $tmpPath);
        } else {
            // Fallback: pakai Google Chart API (online) — untuk development
            // Di production sebaiknya pakai library lokal
            $url = 'https://chart.googleapis.com/chart?chs=150x150&cht=qr&chl=' . urlencode($content);
            file_put_contents($tmpPath, file_get_contents($url));
        }

        return $tmpPath;
    }
}