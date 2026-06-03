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

        $pdf = Pdf::loadView('member.seminar.certificate-pdf', compact(
            'certificate',
            'enrollment',
            'memberData',
            'templateBase64'
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