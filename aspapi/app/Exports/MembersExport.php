<?php

namespace App\Exports;

use App\Models\Member;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MembersExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    public function __construct(protected array $filters) {}

    public function query()
    {
        return Member::with(['registeredByRegion'])
            ->when($this->filters['q'] ?? null, fn($q) => $q->where(function ($sub) {
                $sub->where('full_name', 'like', '%'.$this->filters['q'].'%')
                    ->orWhere('email', 'like', '%'.$this->filters['q'].'%')
                    ->orWhere('member_number', 'like', '%'.$this->filters['q'].'%');
            }))
            ->when($this->filters['status']    ?? null, fn($q) => $q->where('status', $this->filters['status']))
            ->when($this->filters['type']      ?? null, fn($q) => $q->where('registration_type', $this->filters['type']))
            ->when($this->filters['biodata']   ?? null, fn($q) => $q->where('biodata_status', $this->filters['biodata']))
            ->when(
                isset($this->filters['region_id']) && $this->filters['region_id'] !== 'none' && $this->filters['region_id'],
                fn($q) => $q->where('registered_by_region_id', $this->filters['region_id'])
            )
            ->when(
                ($this->filters['region_id'] ?? null) === 'none',
                fn($q) => $q->whereNull('registered_by_region_id')
            )
            ->latest();
    }

    public function headings(): array
    {
        return [
            'No. Anggota',
            'Nama',
            'Email',
            'Tipe',
            'ASPAPI Daerah',
            'Biodata',
            'Status',
            'Tanggal Daftar',
        ];
    }

    public function map($member): array
    {
        return [
            $member->member_number ?? '—',
            $member->full_name ?? $member->user?->name ?? '—',
            $member->email ?? $member->user?->email ?? '—',
            $member->registration_type === 'baru' ? 'Anggota Baru' : 'Anggota Lama',
            $member->registeredByRegion?->name ?? '—',
            match($member->biodata_status) {
                'verified' => 'Terverifikasi',
                'rejected' => 'Ditolak',
                default    => 'Pending',
            },
            match($member->status) {
                'active'   => 'Aktif',
                'rejected' => 'Ditolak',
                'inactive' => 'Tidak Aktif',
                default    => 'Pending',
            },
            $member->created_at->format('d M Y'),
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font'    => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill'    => ['fillType' => 'solid', 'startColor' => ['argb' => 'FF2A7FC1']],
                'alignment' => ['horizontal' => 'center'],
            ],
        ];
    }
}