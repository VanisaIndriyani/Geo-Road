<?php

namespace App\Exports;

use App\Models\Road;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RoadsExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(private readonly array $filters = [])
    {
    }

    public function collection(): Collection
    {
        $query = Road::query()->orderBy('nama_ruas');

        $q = trim((string) ($this->filters['q'] ?? ''));
        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('nama_ruas', 'like', "%{$q}%")
                    ->orWhere('kabupaten', 'like', "%{$q}%")
                    ->orWhere('kecamatan', 'like', "%{$q}%");
            });
        }

        $kondisi = (string) ($this->filters['kondisi'] ?? '');
        if ($kondisi !== '') {
            $query->where('kondisi', $kondisi);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Nama Ruas Jalan',
            'Kabupaten',
            'Kecamatan',
            'Panjang (Km)',
            'Lebar (m)',
            'Kondisi',
            'Jenis Kerusakan',
            'Prioritas',
            'Tahun Survey',
            'Foto',
            'Geometry (Polyline JSON)',
        ];
    }

    public function map($row): array
    {
        return [
            $row->nama_ruas,
            $row->kabupaten,
            $row->kecamatan,
            $row->panjang,
            $row->lebar,
            $row->kondisi,
            $row->jenis_kerusakan,
            $row->prioritas,
            $row->tahun,
            $row->foto_url,
            $row->geometry,
        ];
    }
}

