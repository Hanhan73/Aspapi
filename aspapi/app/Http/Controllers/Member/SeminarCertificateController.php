<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\SeminarCertificate;
use Barryvdh\DomPDF\Facade\Pdf;

class SeminarCertificateController extends Controller
{
    /**
     * Download sertifikat seminar sebagai PDF.
     * Route: GET /member/seminar/certificate/{certificate}
     * Name:  member.seminar.certificate
     */
    public function download(SeminarCertificate $certificate)
    {
        // Pastikan sertifikat milik member yang sedang login
        $enrollment = $certificate->enrollment;
        abort_if(
            $enrollment->member_id !== auth()->user()->member?->id,
            403
        );

        $memberData     = $enrollment->member;
        $templateBase64 = $this->imageToBase64(public_path('images/sertifikat-template.jpg'));

        // Hitung ukuran font nama & posisi teks di bawahnya secara dinamis
        // supaya nama yang panjang (banyak gelar) tidak nabrak ke baris berikutnya.
        $nameLength = mb_strlen($memberData->full_name_with_title);
        [$nameFontSize, $nameTop, $atasPartisipasiTop, $temaTop] = $this->calculateNameLayout($nameLength);

        $pdf = Pdf::loadView('member.seminar.certificate-pdf', compact(
            'certificate',
            'enrollment',
            'memberData',
            'templateBase64',
            'nameFontSize',
            'nameTop',
            'atasPartisipasiTop',
            'temaTop'
        ))
        ->setPaper('a4', 'landscape')
        ->setOption([
            'dpi'                  => 150,
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled'      => false,
            'margin_top'           => 0,
            'margin_right'         => 0,
            'margin_bottom'        => 0,
            'margin_left'          => 0,
            'defaultFont'          => 'DejaVu Sans',
        ]);

        $filename = 'Sertifikat-ASPAPI-' . str_replace('/', '-', $certificate->certificate_number) . '.pdf';

        return $pdf->download($filename);
    }

    // ── Helper ────────────────────────────────────────────────────────────────

    /**
     * Tentukan font-size nama & posisi top untuk baris di bawahnya
     * berdasarkan panjang nama+gelar, supaya tidak overlap.
     *
     * @return array{0:int,1:float,2:float,3:float} [fontSize(pt), nameTop(mm), atasPartisipasiTop(mm), temaTop(mm)]
     */
    private function calculateNameLayout(int $length): array
    {
        return match (true) {
            $length <= 20 => [36, 95, 112, 128],
            $length <= 30 => [30, 96, 110, 126],
            $length <= 40 => [26, 96, 109, 125],
            $length <= 50 => [22, 97, 109, 125],
            $length <= 60 => [19, 97, 110, 126],
            default        => [16, 97, 111, 127],
        };
    }

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

        return base64_encode($data);
    }
}