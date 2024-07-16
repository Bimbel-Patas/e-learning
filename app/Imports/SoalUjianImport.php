<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class SoalUjianImport implements ToModel, WithStartRow
{
    /**
     * @param  Collection  $collection
     */
    public function model(array $row)
    {
        // Menggunakan array_push untuk menambahkan elemen baru ke dalam sesi 'soal'
        $soal = session('soal', []); // Mengambil sesi 'soal' atau inisialisasi array kosong jika belum ada
        array_push($soal, $row); // Menambahkan $row ke dalam array $soal
        session(['soal' => $soal]); // Menyimpan kembali array $soal ke dalam sesi 'soal'
    }

    /**
     * Tentukan baris pertama data dalam file Excel
     */
    public function startRow(): int
    {
        return 2; // Baris pertama berisi header, data dimulai dari baris kedua
    }
}
