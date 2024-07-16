<?php

namespace App\Exports;

use App\Models\KelasMapel;
use App\Models\SoalUjianEssay;
use App\Models\SoalUjianMultiple;
use App\Models\Tugas;
use App\Models\Ujian;
use App\Models\User;
use App\Models\UserJawaban;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Fill as fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class NilaiUjianExport implements FromCollection, WithHeadings, WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $ujianId; // ujianId yang akan diekspor atau tugas id

    protected $kelasMapel; // ujianId yang akan diekspor atau tugas id

    protected $namaUjian; // ujianId yang akan diekspor atau tugas id

    protected $tipe; // ujianId yang akan diekspor atau tugas id

    public function __construct($ujianId, $kelasMapel)
    {
        $this->ujianId = $ujianId;
        $this->kelasMapel = $kelasMapel;
        $temp = Ujian::where('id', $this->ujianId)->first();
        $this->namaUjian = $temp['name'];
        $this->tipe = $temp['tipe'];
    }

    public function collection()
    {
        if ($this->tipe == 'essay') {
            $kelasMapel = KelasMapel::where('id', $this->kelasMapel)->first();
            $user = User::where('kelas_id', $kelasMapel['kelas_id'])->get();

            $data = [];

            foreach ($user as $key) {
                $soalUjianEssay = SoalUjianEssay::where('ujian_id', $this->ujianId)->get();
                $nilai = 0;
                foreach ($soalUjianEssay as $key2) {
                    $userJawaban = UserJawaban::where('essay_id', $key2->id)->where('user_id', $key->id)->first();

                    if ($userJawaban) {
                        $nilai += $userJawaban['nilai'];
                    } else {
                        $nilai += 0;
                    }
                }

                $data[] = [
                    'Nama' => $key->name,
                    'Nilai' => $nilai,
                ];
            }

            return collect($data);
        }

        if ($this->tipe == 'multiple') {
            $kelasMapel = KelasMapel::where('id', $this->kelasMapel)->first();
            $user = User::where('kelas_id', $kelasMapel['kelas_id'])->get();

            $data = [];

            foreach ($user as $key) {
                $soalUjianMultiple = SoalUjianMultiple::where('ujian_id', $this->ujianId)->get();
                $nilai = 0;
                foreach ($soalUjianMultiple as $key2) {
                    $userJawaban = UserJawaban::where('multiple_id', $key2->id)->where('user_id', $key->id)->first();

                    if ($userJawaban) {
                        $nilai += $userJawaban['nilai'];
                    } else {
                        $nilai += 0;
                    }
                }

                $data[] = [
                    'Nama' => $key->name,
                    'Nilai' => $nilai,
                ];
            }

            return collect($data);
        }
    }

    public function headings(): array
    {

        return [
            'Nama',
            'Nilai',
            'Nama Ujian : '.$this->namaUjian,
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
