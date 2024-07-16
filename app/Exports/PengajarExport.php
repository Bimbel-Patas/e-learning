<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Fill as fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PengajarExport implements FromCollection, WithHeadings, WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $users = User::where('roles_id', 2)->get(['id', 'name', 'gender', 'email']);

        // Menambahkan data kontak untuk setiap pengguna
        $users->map(function ($user) {
            $user->nomor_telepon = $user->contact->no_telp; // Sesuaikan dengan atribut yang sesuai

            return $user;
        });

        return $users;
    }

    public function headings(): array
    {
        return [
            'Id',
            'Nama',
            'Gender',
            'Email',
            'Kontak',
            // Tambahkan judul kolom sesuai dengan kebutuhan
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            'A1:E1' => [
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
