<?php

namespace App\Exports;

use App\Models\DataSiswa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SiswaExport implements FromCollection, WithHeadings, WithStyles
{
    /**
     * @return \Illuminate\Database\Query\Builder
     */
    public function Collection()
    {
        $siswas = DataSiswa::leftJoin('kelas', 'data_siswas.kelas_id', '=', 'kelas.id')
            ->get(['data_siswas.id', 'data_siswas.name',  'kelas.name as namee', 'data_siswas.nis']);

        return $siswas;
    }

    public function headings(): array
    {
        return [
            'Id',
            'Nama',
            'Kelas',
            'NIS',
            // Tambahkan judul kolom sesuai dengan kebutuhan
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            'A1:D1' => [
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
