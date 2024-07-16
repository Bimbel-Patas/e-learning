<?php

namespace App\Exports;

use App\Models\Kelas;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class KelasExport implements FromCollection, WithHeadings, WithStyles
{
    /**
     * @return \Illuminate\Database\Query\Builder
     */
    public function Collection()
    {
        return Kelas::get(['id', 'name']);
    }

    public function headings(): array
    {
        return [
            'Id',
            'Nama',
            // Tambahkan judul kolom sesuai dengan kebutuhan
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            'A1:B1' => [
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
