<?php

namespace App\Exports;

use App\Models\KelasMapel;
use App\Models\Tugas;
use App\Models\User;
use App\Models\UserTugas;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Fill as fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class NilaiTugasExport implements FromCollection, WithHeadings, WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $tugasId; // ujianId yang akan diekspor atau tugas id

    protected $kelasMapel; // ujianId yang akan diekspor atau tugas id

    protected $namaTugas; // ujianId yang akan diekspor atau tugas id

    public function __construct($tugasId, $kelasMapel)
    {
        $this->tugasId = $tugasId;
        $this->kelasMapel = $kelasMapel;
        $temp = Tugas::where('id', $this->tugasId)->first();
        $this->namaTugas = $temp['name'];
    }

    public function collection()
    {
        $kelasMapel = KelasMapel::where('id', $this->kelasMapel)->first();
        $user = User::where('kelas_id', $kelasMapel['kelas_id'])->get();

        $data = [];

        foreach ($user as $key) {
            $userTugas = UserTugas::where('tugas_id', $this->tugasId)->where('user_id', $key->id)->first();
            $nilai = 0;

            if ($userTugas) {
                $nilai = $userTugas['nilai'];
            } else {
                $nilai = 0;
            }

            $data[] = [
                'Nama' => $key->name,
                'Nilai' => $nilai,
            ];
        }

        return collect($data);
    }

    public function headings(): array
    {

        return [
            'Nama',
            'Nilai',
            'Nama Tugas : '.$this->namaTugas,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            'A1:C1' => [
                // Gaya untuk sel heading baris pertama (A1 sampai C1)
                'font' => [
                    'bold' => true, // Membuat teks tebal
                    'color' => ['rgb' => 'FFFFFF'], // Warna teks (putih)
                ],
                'fill' => [
                    'fillType' => fill::FILL_SOLID, // Jenis pengisian (solid)
                    'startColor' => ['rgb' => '333333'], // Warna latar belakang (abu-abu gelap)
                ],
            ],
        ];
    }
}
