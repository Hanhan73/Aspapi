<?php

namespace App\Exports;

use App\Models\Payment;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RekapTransaksiExport implements FromQuery, WithHeadings, WithMapping, WithTitle, WithStyles
{
    public function __construct(
        protected int $year,
        protected ?int $month,
        protected ?string $type,
        protected ?string $search,
        protected ?int $regionId
    ) {}

    public function query()
    {
        return Payment::with(['member.region', 'verifier'])
            ->where('status', 'verified')
            ->whereYear('verified_at', $this->year)
            ->when($this->month,    fn($q) => $q->whereMonth('verified_at', $this->month))
            ->when($this->type,     fn($q) => $q->where('type', $this->type))
            ->when($this->regionId, fn($q) => $q->whereHas('member', fn($sub) =>
                $sub->where('registered_by_region_id', $this->regionId)
            ))
            ->when($this->search,   fn($q) => $q->whereHas('member', fn($sub) =>
                $sub->where('full_name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%')
            ))
            ->latest('verified_at');
    }

    public function headings(): array
    {
        return ['No', 'NIA', 'Nama Anggota', 'Email', 'ASPAPI Daerah', 'Jenis Pembayaran', 'Jumlah (Rp)', 'Diverifikasi Oleh', 'Tanggal Verifikasi'];
    }

    public function map($row): array
    {
        static $i = 0;
        $i++;
        return [
            $i,
            $row->member->member_number ?? '—',
            $row->member->full_name,
            $row->member->email,
            $row->member->region->name ?? 'Pusat / Mandiri',
            $row->type_label,
            $row->amount,
            $row->verifier->name ?? '—',
            $row->verified_at?->format('d/m/Y'),
        ];
    }

    public function title(): string
    {
        return 'Transaksi ' . $this->year;
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}