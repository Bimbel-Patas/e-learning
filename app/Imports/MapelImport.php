<?php

namespace App\Imports;

use App\Models\Mapel;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class MapelImport implements ToModel, WithStartRow
{
    /**
     * @param  Collection  $collection
     */
    public function model(array $row)
    {
        // Temukan Mapel berdasarkan kolom tertentu (misalnya, id)
        $mapel = Mapel::where('name', $row[1])->first();

        if ($row[1] == null) {
            return null;
        }

        if ($mapel) {

            $data = [
                'name' => $row[1],
                'deskripsi' => $row[2],
            ];

            // Perbarui data Mapel yang sudah ada
            Mapel::where('name', $mapel['name'])->update($data);

            $importedIds = session('imported_ids', []);
            $importedIds[] = $mapel['id'];
            session(['imported_ids' => $importedIds]);
        } else {

            $data = [
                'name' => $row[1],
                'deskripsi' => $row[2],
            ];

            // Buat Mapel baru
            $mapel = Mapel::create($data);

            $importedIds = session('imported_ids', []);
            $importedIds[] = $mapel['id'];
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
