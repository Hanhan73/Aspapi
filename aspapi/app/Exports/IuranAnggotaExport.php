<?php

namespace App\Exports;

use App\Models\Member;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class IuranAnggotaExport implements FromQuery, WithHeadings, WithMapping, WithTitle, WithStyles
{
    public function __construct(
        protected ?string $filterStatus,
        protected ?string $search,
        protected ?int $regionId,
        protected array $aktifIds,
        protected array $kadaluarsaIds
    ) {}

    public function query()
    {
        return Member::with(['payments' => fn($q) =>
                $q->where('type', 'iuran_tahunan')
                  ->where('status', 'verified')
                  ->latest('verified_at')
                  ->limit(1)
            ])
            ->where('biodata_status', 'verified')
            ->whereIn('status', ['active', 'pending'])
            ->when($this->filterStatus === 'aktif',       fn($q) => $q->whereIn('id', $this->aktifIds))
            ->when($this->filterStatus === 'kadaluarsa',  fn($q) => $q->whereIn('id', $this->kadaluarsaIds))
            ->when($this->filterStatus === 'belum_aktif', fn($q) => $q->where('status', 'pending'))
            ->when($this->regionId, fn($q) => $q->where('registered_by_region_id', $this->regionId))
            ->when($this->search, fn($q) =>
                $q->where(fn($sub) =>
                    $sub->where('full_name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%')
                        ->orWhere('member_number', 'like', '%' . $this->search . '%')
                )
            )
            ->orderByRaw("FIELD(status, 'active', 'pending')")
            ->orderBy('full_name');
    }

    public function headings(): array
    {
        return ['No', 'NIA', 'Nama Anggota', 'Email', 'ASPAPI Daerah', 'Status Akun', 'Status Iuran', 'Terakhir Bayar', 'Aktif Hingga', 'Sisa Hari'];
    }

    public function map($member): array
    {
        static $i = 0;
        $i++;

        $isPending    = $member->status === 'pending';
        $isAktif      = in_array($member->id, $this->aktifIds);
        $isKadaluarsa = in_array($member->id, $this->kadaluarsaIds);
        $activeUntil  = $member->active_until;
        $sisaHari     = $activeUntil ? (int) now()->diffInDays($activeUntil, false) : null;
        $iuranTerakhir = $member->payments->first();

        if ($isPending) {
            $statusIuran = 'Menunggu Pembayaran';
        } elseif ($isAktif && $sisaHari !== null && $sisaHari <= 30) {
            $statusIuran = 'Segera Kadaluarsa';
        } elseif ($isAktif) {
            $statusIuran = 'Aktif';
        } elseif ($isKadaluarsa) {
            $statusIuran = 'Kadaluarsa';
        } else {
            $statusIuran = 'Belum Pernah Bayar';
        }

        return [
            $i,
            $member->member_number ?? '—',
            $member->full_name,
            $member->email,
            $member->region->name ?? 'Pusat / Mandiri',
            $isPending ? 'Belum Aktif' : 'Aktif',
            $statusIuran,
            $iuranTerakhir?->verified_at?->format('d/m/Y') ?? '—',
            $activeUntil?->format('d/m/Y') ?? '—',
            $sisaHari !== null ? ($sisaHari >= 0 ? $sisaHari . ' hari' : abs($sisaHari) . ' hari lalu') : '—',
        ];
    }

    public function title(): string { return 'Status Iuran'; }

    public function styles(Worksheet $sheet): array
    {
        return [1 => ['font' => ['bold' => true]]];
    }
}