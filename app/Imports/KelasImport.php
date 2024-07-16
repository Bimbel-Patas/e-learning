<?php

namespace App\Imports;

use App\Models\Kelas;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class KelasImport implements ToModel, WithStartRow
{
    /**
     * @param  Collection  $collection
     */
    public function model(array $row)
    {
        // Temukan Mapel berdasarkan kolom tertentu (misalnya, id)
        $kelas = Kelas::where('name', $row[1])->first();

        if ($row[1] == null) {
            return null;
        }

        if ($kelas) {

            $data = [
                'name' => $row[1],
            ];

            // Perbarui data kelas yang sudah ada
            Kelas::where('name', $kelas['name'])->update($data);

            $importedIds = session('imported_ids', []);
            $importedIds[] = $kelas['id'];
            session(['imported_ids' => $importedIds]);
        } else {

            $data = [
                'name' => $row[1],
            ];

            // Buat kelas baru
            $kelas = Kelas::create($data);

            $importedIds = session('imported_ids', []);
            $importedIds[] = $kelas['id'];
            session(['imported_ids' => $importedIds]);
        }

        return DB::commit();
    }

    /**
     * Tentukan baris pertama data dalam file Excel
     */
    public function startRow(): int
    {
        return 2; // Baris pertama berisi header, data dimulai dari baris kedua
    }
}
