<?php

namespace App\Http\Controllers\Daerah;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Payment;
use App\Models\PaymentBatch;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;

class RegionMemberController extends Controller
{
    // ── Helpers ───────────────────────────────────────────────────────────────

    private function region()
    {
        $region = auth()->user()->region;
        abort_unless($region, 403, 'Akun ini tidak terhubung ke ASPAPI Daerah manapun.');
        return $region;
    }

    // ── Dashboard ─────────────────────────────────────────────────────────────

    public function index()
    {
        $region = $this->region();

        $stats = [
            'total_members'  => Member::where('registered_by_region_id', $region->id)->count(),
            'active_members' => Member::where('registered_by_region_id', $region->id)->where('status', 'active')->count(),
            'pending'        => Member::where('registered_by_region_id', $region->id)->where('status', 'pending')->count(),
        ];

        $recentMembers = Member::where('registered_by_region_id', $region->id)
            ->latest()->take(5)->get();

        return view('daerah.dashboard', compact('region', 'stats', 'recentMembers'));
    }

    // ── Daftar Anggota ────────────────────────────────────────────────────────

    public function members(Request $request)
    {
        $region = $this->region();

        $members = Member::where('registered_by_region_id', $region->id)
            ->when($request->filled('search'), fn($q) =>
                $q->where(fn($sub) =>
                    $sub->where('full_name', 'like', '%' . $request->search . '%')
                        ->orWhere('email', 'like', '%' . $request->search . '%')
                        ->orWhere('institution', 'like', '%' . $request->search . '%')
                )
            )
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
            ->when($request->filled('dues'), function ($q) use ($request) {
                $request->dues === 'lunas'
                    ? $q->where('dues_paid', true)
                    : $q->where('dues_paid', false);
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('daerah.members', compact('region', 'members'));
    }

    // ══════════════════════════════════════════════════════════════════════════
    //  #4A — BATCH DAFTAR (Manual atau Upload Excel)
    // ══════════════════════════════════════════════════════════════════════════

    public function batchForm()
    {
        $region = $this->region();
        return view('daerah.batch-form', compact('region'));
    }

    public function batchStore(Request $request)
    {
        $request->validate([
            'file'         => 'nullable|file|mimes:xlsx,xls|max:5120',
            'participants' => 'nullable|array',
        ]);

        $region = $this->region();

        if ($request->hasFile('file')) {
            return $this->processBatchFromExcel($request, $region);
        }

        return $this->processBatchManual($request, $region);
    }

    // ── Proses dari Excel ─────────────────────────────────────────────────────

    private function processBatchFromExcel(Request $request, $region)
    {
        $path = $request->file('file')->getRealPath();

        try {
            $spreadsheet = IOFactory::load($path);
            $rows        = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
        } catch (\Exception $e) {
            return back()->withErrors(['file' => 'File Excel tidak bisa dibaca: ' . $e->getMessage()]);
        }

        // Template memiliki 3 baris header (judul, instruksi, nama kolom).
        // Data dimulai dari baris ke-4.
        $rows = array_slice($rows, 3);

        $berhasil = 0;
        $gagal    = [];

        foreach ($rows as $i => $row) {
            $namaLengkap = trim($row['A'] ?? '');
            $email       = strtolower(trim($row['B'] ?? ''));
            $telepon     = trim($row['C'] ?? '');
            $institusi   = trim($row['D'] ?? '');
            $gender      = strtoupper(trim($row['E'] ?? 'L'));

            // Lewati baris yang benar-benar kosong
            if (empty($namaLengkap) && empty($email)) {
                continue;
            }

            // +4 karena data mulai baris 4 di file Excel
            $noRow = $i + 4;

            if (empty($namaLengkap) || empty($email)) {
                $gagal[] = "Baris {$noRow}: Nama dan Email wajib diisi.";
                continue;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $gagal[] = "Baris {$noRow}: Email '{$email}' tidak valid.";
                continue;
            }

            if (User::where('email', $email)->exists()) {
                $gagal[] = "Baris {$noRow}: Email '{$email}' sudah terdaftar.";
                continue;
            }

            if (!in_array($gender, ['L', 'P'])) {
                $gender = 'L';
            }

            $password = Str::random(10);

            try {
                DB::transaction(function () use (
                    $email, $password, $namaLengkap, $telepon, $institusi, $gender, $region
                ) {
                    $user = User::create([
                        'name'              => $namaLengkap,
                        'email'             => $email,
                        'password'          => Hash::make($password),
                        'role'              => 'anggota',
                        'email_verified_at' => now(),
                    ]);

                    Member::create([
                        'user_id'                 => $user->id,
                        'full_name'               => $namaLengkap,
                        'email'                   => $email,
                        'phone'                   => $telepon,
                        'institution'             => $institusi,
                        'gender'                  => $gender,
                        'biodata_status'          => 'pending',
                        'status'                  => 'pending',
                        'registration_type'       => 'baru',
                        'is_batch'                => true,
                        'registered_by_region_id' => $region->id,
                        'registered_at'           => now(),
                    ]);

                    try {
                        Mail::send(
                            'emails.batch-welcome',
                            ['name' => $namaLengkap, 'email' => $email, 'password' => $password],
                            fn($m) => $m->to($email)->subject('Selamat Datang di ASPAPI — Akun Anda Telah Dibuat')
                        );
                    } catch (\Exception $e) {
                        Log::warning("Batch welcome email gagal ke {$email}: " . $e->getMessage());
                    }
                });

                $berhasil++;
            } catch (\Exception $e) {
                $gagal[] = "Baris {$noRow} ({$namaLengkap}): " . $e->getMessage();
                Log::error("Batch Excel error row {$noRow}: " . $e->getMessage());
            }
        }

        return $this->batchResponse($berhasil, $gagal);
    }

    // ── Proses dari Input Manual ──────────────────────────────────────────────

    private function processBatchManual(Request $request, $region)
    {
        $participants = collect($request->input('participants', []))
            ->filter(fn($p) => !empty(trim($p['name'] ?? '')) || !empty(trim($p['email'] ?? '')))
            ->values();

        if ($participants->isEmpty()) {
            return back()->withErrors(['participants' => 'Tambahkan minimal 1 peserta.']);
        }

        $berhasil = 0;
        $gagal    = [];

        foreach ($participants as $i => $data) {
            $namaLengkap = trim($data['name'] ?? '');
            $email       = strtolower(trim($data['email'] ?? ''));
            $telepon     = trim($data['phone'] ?? '');
            $institusi   = trim($data['institution'] ?? '');
            $gender      = strtoupper(trim($data['gender'] ?? 'L'));
            $noRow       = $i + 1;

            if (empty($namaLengkap) || empty($email)) {
                $gagal[] = "Baris {$noRow}: Nama dan Email wajib diisi.";
                continue;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $gagal[] = "Baris {$noRow}: Email '{$email}' tidak valid.";
                continue;
            }

            if (User::where('email', $email)->exists()) {
                $gagal[] = "Baris {$noRow}: Email '{$email}' sudah terdaftar.";
                continue;
            }

            if (!in_array($gender, ['L', 'P'])) {
                $gender = 'L';
            }

            $password = Str::random(10);

            try {
                DB::transaction(function () use (
                    $email, $password, $namaLengkap, $telepon, $institusi, $gender, $region
                ) {
                    $user = User::create([
                        'name'              => $namaLengkap,
                        'email'             => $email,
                        'password'          => Hash::make($password),
                        'role'              => 'anggota',
                        'email_verified_at' => now(),
                    ]);

                    Member::create([
                        'user_id'                 => $user->id,
                        'full_name'               => $namaLengkap,
                        'email'                   => $email,
                        'phone'                   => $telepon,
                        'institution'             => $institusi,
                        'gender'                  => $gender,
                        'biodata_status'          => 'pending',
                        'status'                  => 'pending',
                        'registration_type'       => 'baru',
                        'is_batch'                => true,
                        'registered_by_region_id' => $region->id,
                        'registered_at'           => now(),
                    ]);

                    try {
                        Mail::send(
                            'emails.batch-welcome',
                            ['name' => $namaLengkap, 'email' => $email, 'password' => $password],
                            fn($m) => $m->to($email)->subject('Selamat Datang di ASPAPI — Akun Anda Telah Dibuat')
                        );
                    } catch (\Exception $e) {
                        Log::warning("Batch welcome email gagal ke {$email}: " . $e->getMessage());
                    }
                });

                $berhasil++;
            } catch (\Exception $e) {
                $gagal[] = "Baris {$noRow} ({$namaLengkap}): " . $e->getMessage();
                Log::error("Batch manual error row {$noRow}: " . $e->getMessage());
            }
        }

        return $this->batchResponse($berhasil, $gagal);
    }

    // ── Helper response batch ─────────────────────────────────────────────────

    private function batchResponse(int $berhasil, array $gagal)
    {
        $pesan = "Berhasil mendaftarkan {$berhasil} anggota.";

        if (!empty($gagal)) {
            $pesan .= ' ' . count($gagal) . ' baris gagal.';
            return back()
                ->with('success', $pesan)
                ->with('batch_errors', $gagal);
        }

        return back()->with('success', $pesan);
    }

    // ══════════════════════════════════════════════════════════════════════════
    //  #4B — BATCH BAYAR (Pembayaran Kolektif)
    // ══════════════════════════════════════════════════════════════════════════

    public function payBatchForm()
    {
        $region = $this->region();

        // Tampilkan anggota yang:
        //   - terdaftar di region ini
        //   - biodata sudah verified (sudah disetujui admin)
        //   - status 'active' (perpanjang) ATAU 'pending' (baru, belum pernah bayar)
        //   - iurannya belum aktif: active_until null atau sudah lewat
        //   - tidak ada payment iuran yang masih pending (hindari double submit)
        $members = Member::where('registered_by_region_id', $region->id)
            ->where('biodata_status', 'verified')
            ->whereIn('status', ['active', 'pending'])
            ->where(fn($q) =>
                $q->whereNull('active_until')
                  ->orWhere('active_until', '<=', now())
            )
            ->whereDoesntHave('payments', fn($q) =>
                $q->where('type', 'iuran_tahunan')
                  ->where('status', 'pending')
            )
            ->orderByRaw("FIELD(status, 'pending', 'active')")  // anggota baru tampil duluan
            ->orderBy('full_name')
            ->get();

        return view('daerah.pay-batch', compact('region', 'members'));
    }

    public function payBatchStore(Request $request)
    {
        $request->validate([
            'member_ids'   => 'required|array|min:1',
            'member_ids.*' => 'exists:members,id',
            'receipt'      => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $region = $this->region();

        // Security: pastikan member benar-benar milik region ini + eligible
        $memberIds = Member::whereIn('id', $request->member_ids)
            ->where('registered_by_region_id', $region->id)
            ->where('biodata_status', 'verified')
            ->whereIn('status', ['active', 'pending'])
            ->pluck('id')
            ->toArray();

        if (empty($memberIds)) {
            return back()->withErrors(['member_ids' => 'Tidak ada anggota valid yang dipilih.']);
        }

        $receiptPath     = $request->file('receipt')->store('receipts/batch', 'public');
        $iuranPerAnggota = 120000;
        $totalAmount     = count($memberIds) * $iuranPerAnggota;

        DB::transaction(function () use ($memberIds, $region, $receiptPath, $iuranPerAnggota, $totalAmount) {
            $batch = PaymentBatch::create([
                'region_id'    => $region->id,
                'submitted_by' => auth()->id(),
                'receipt_path' => $receiptPath,
                'total_amount' => $totalAmount,
                'member_count' => count($memberIds),
                'status'       => 'pending',
                'payment_year' => now()->year,
            ]);

            foreach ($memberIds as $memberId) {
                Payment::create([
                    'member_id'      => $memberId,
                    'batch_id'       => $batch->id,
                    'type'           => 'iuran_tahunan',
                    'payment_method' => 'kolektif',
                    'amount'         => $iuranPerAnggota,
                    'receipt_path'   => $receiptPath,
                    'status'         => 'pending',
                    'payment_year'   => now()->year,
                    'notes'          => "Pembayaran kolektif batch #{$batch->id} oleh " . ($region->province ?? 'ASPAPI Daerah'),
                ]);
            }
        });

        return back()->with('success',
            'Pembayaran kolektif untuk ' . count($memberIds) . ' anggota berhasil dikirim dan menunggu verifikasi bendahara.'
        );
    }

    // ── Download Template Excel ───────────────────────────────────────────────

    public function downloadTemplate()
    {
        $filePath = public_path('templates/template-batch-anggota.xlsx');

        if (!file_exists($filePath)) {
            abort(404, 'Template tidak ditemukan.');
        }

        return response()->download($filePath, 'template-batch-anggota.xlsx');
    }

    // ── Cek Duplikat Email ────────────────────────────────────────────────────

    public function checkDuplicates(Request $request)
    {
        $request->validate([
            'emails'   => 'required|array',
            'emails.*' => 'email',
        ]);

        $duplicates = [];

        foreach ($request->emails as $email) {
            $user = User::where('email', strtolower(trim($email)))->first();
            if ($user) {
                $member       = $user->member;
                $duplicates[] = [
                    'email'         => $email,
                    'name'          => $member?->full_name ?? $user->name,
                    'member_number' => $member?->member_number,
                    'status'        => $member?->status,
                ];
            }
        }

        return response()->json([
            'duplicates' => $duplicates,
            'total'      => count($duplicates),
        ]);
    }
}