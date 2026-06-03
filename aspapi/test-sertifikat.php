<?php

/**
 * Testing generate sertifikat PDF
 * Jalankan via: php artisan tinker
 *   >>> require base_path('test-sertifikat.php');
 *
 * Output: storage/app/public/test-sertifikat.pdf
 */

use App\Models\SeminarCertificate;
use Barryvdh\DomPDF\Facade\Pdf;

// ── 1. Ambil data dari DB ────────────────────────────────────────────────────

$certificate = SeminarCertificate::with([
    'enrollment.seminar',
    'enrollment.member',
])->first();

if (! $certificate) {
    echo "⚠  Tidak ada sertifikat di DB. Membuat data dummy...\n";

    $enrollment = \App\Models\SeminarEnrollment::with(['seminar', 'member'])->first();

    if (! $enrollment) {
        echo "✗ Tidak ada enrollment. Pastikan ada data seminar & enrollment dulu.\n";
        return;
    }

    $certificate = new SeminarCertificate([
        'enrollment_id'      => $enrollment->id,
        'certificate_number' => 'TEST/ASPAPI/SF/001/2026',
        'score'              => 85,
        'issued_at'          => now(),
    ]);
    $certificate->setRelation('enrollment', $enrollment);
    $memberData = $enrollment->member;
} else {
    $memberData = $certificate->enrollment->member;
    $enrollment = $certificate->enrollment;
    echo "✓ Sertifikat ditemukan: {$certificate->certificate_number}\n";
}

echo "  Member : {$memberData->full_name}\n";
echo "  Seminar: {$enrollment->seminar->title}\n";

// ── 2. Load template gambar ──────────────────────────────────────────────────

$templatePath = public_path('images/sertifikat-template.jpg');

if (! file_exists($templatePath)) {
    echo "✗ Template tidak ditemukan di: {$templatePath}\n";
    return;
}

$templateBase64 = base64_encode(file_get_contents($templatePath));
echo "✓ Template loaded (" . round(strlen($templateBase64) / 1024) . " KB)\n";

// ── 3. Generate PDF ──────────────────────────────────────────────────────────

try {
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

    $outputPath = storage_path('app/public/test-sertifikat.pdf');
    $pdf->save($outputPath);

    echo "✓ PDF berhasil!\n";
    echo "  Path: {$outputPath}\n";
    echo "  URL : " . url('storage/test-sertifikat.pdf') . "\n";

} catch (\Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}