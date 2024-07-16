<?php

namespace App\Exports;

use App\Models\Mapel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Fill as Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MapelExport implements FromCollection, WithHeadings, WithStyles
{
    /**
     * @return \Illuminate\Database\Query\Builder
     */
    public function Collection()
    {
        return Mapel::get(['id', 'name', 'deskripsi']);
    }

    public function headings(): array
    {
        return [
            'Id',
            'Nama',
            'Deskripsi',
            // Tambahkan judul kolom sesuai dengan kebutuhan
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            'A1:C1' => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '333333'],
                ],
            ],
        ];
    }
}
