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
    //  #4A — BATCH DAFTAR (Upload Excel)
    // ══════════════════════════════════════════════════════════════════════════

    public function batchForm()
    {
        $region = $this->region();
        return view('daerah.batch-form', compact('region'));
    }

    public function batchStore(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:5120',
        ]);

        $region = $this->region();
        $path   = $request->file('file')->getRealPath();

        try {
            $spreadsheet = IOFactory::load($path);
            $rows        = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
        } catch (\Exception $e) {
            return back()->withErrors(['file' => 'File Excel tidak bisa dibaca: ' . $e->getMessage()]);
        }

        // Lewati baris header (baris 1)
        array_shift($rows);

        $berhasil = 0;
        $gagal    = [];

        foreach ($rows as $i => $row) {
            // Kolom: A=Nama, B=Email, C=Telepon, D=Institusi, E=Gender
            $namaLengkap = trim($row['A'] ?? '');
            $email       = trim($row['B'] ?? '');
            $telepon     = trim($row['C'] ?? '');
            $institusi   = trim($row['D'] ?? '');
            $gender      = strtoupper(trim($row['E'] ?? 'L'));

            $noRow = $i + 1; // +1 karena header sudah digeser

            // Validasi baris
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
                $gender = 'L'; // default
            }

            // Generate password sementara
            $password = Str::random(10);

            try {
                DB::transaction(function () use (
                    $email, $password, $namaLengkap, $telepon, $institusi, $gender, $region
                ) {
                    // Buat user
                    $user = User::create([
                        'name'              => $namaLengkap,
                        'email'             => $email,
                        'password'          => Hash::make($password),
                        'role'              => 'anggota',
                        'email_verified_at' => now(), // batch = langsung verified
                    ]);

                    // Buat profil member
                    Member::create([
                        'user_id'                => $user->id,
                        'full_name'              => $namaLengkap,
                        'email'                  => $email,
                        'phone'                  => $telepon,
                        'institution'            => $institusi,
                        'gender'                 => $gender,
                        'biodata_status'         => 'pending',
                        'status'                 => 'pending',
                        'registration_type'      => 'baru',
                        'is_batch'               => true,
                        'registered_by_region_id'=> $region->id,
                        'registered_at'          => now(),
                    ]);

                    // Kirim email dengan kredensial login
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
                Log::error("Batch register error row {$noRow}: " . $e->getMessage());
            }
        }

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

        // Anggota aktif yang belum lunas iuran tahun ini
        $members = Member::where('registered_by_region_id', $region->id)
            ->where('status', 'active')
            ->whereDoesntHave('payments', fn($q) =>
                $q->where('type', 'iuran_tahunan')
                  ->where('status', 'verified')
                  ->where('payment_year', now()->year)
            )
            ->orderBy('full_name')
            ->get();

        return view('daerah.pay-batch', compact('region', 'members'));
    }

    public function payBatchStore(Request $request)
    {
        $request->validate([
            'member_ids'   => 'required|array|min:1',
            'member_ids.*' => 'exists:members,id',
            'year'         => 'required|integer|min:2020|max:' . (now()->year + 1),
            'receipt'      => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $region = $this->region();

        // Pastikan semua member_ids milik region ini
        $memberIds = Member::whereIn('id', $request->member_ids)
            ->where('registered_by_region_id', $region->id)
            ->pluck('id')
            ->toArray();

        if (empty($memberIds)) {
            return back()->withErrors(['member_ids' => 'Tidak ada anggota valid yang dipilih.']);
        }

        // Simpan bukti transfer
        $receiptPath = $request->file('receipt')->store('receipts/batch', 'public');

        $iuranPerAnggota = 120000;
        $totalAmount     = count($memberIds) * $iuranPerAnggota;

        DB::transaction(function () use ($memberIds, $region, $request, $receiptPath, $iuranPerAnggota, $totalAmount) {

            // Buat PaymentBatch
            $batch = PaymentBatch::create([
                'region_id'    => $region->id,
                'submitted_by' => auth()->id(),
                'receipt_path' => $receiptPath,
                'total_amount' => $totalAmount,
                'member_count' => count($memberIds),
                'status'       => 'pending',
                'payment_year' => $request->year,
            ]);

            // Buat Payment per anggota, terhubung ke batch
            foreach ($memberIds as $memberId) {
                Payment::create([
                    'member_id'      => $memberId,
                    'batch_id'       => $batch->id,
                    'type'           => 'iuran_tahunan',
                    'payment_method' => 'transfer',
                    'amount'         => $iuranPerAnggota,
                    'receipt_path'   => $receiptPath,
                    'status'         => 'pending',
                    'payment_year'   => $request->year,
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
}