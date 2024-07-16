<?php

namespace App\Imports;

use App\Models\DataSiswa;
use App\Models\Kelas;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class SiswaImport implements ToModel, WithStartRow
{
    /**
     * @param  Collection  $collection
     */
    public function model(array $row)
    {
        //sdsss
        // Temukan Mapel berdasarkan kolom tertentu (misalnya, id)
        $siswa = DataSiswa::where('nis', $row[3])->first();
        $kelasId = Kelas::where('name', $row[2])->first();
        //dd($row);


        if ($row[1] == null) {
            return null;
        }

        if ($siswa) {

            if ($kelasId == null) {
                $data = [
                    'name' => $row[1],
                    'kelas_id' => null,
                    'nis' => $row[3],
                ];
            } else {
                $data = [
                    'name' => $row[1],
                    'kelas_id' => $kelasId['id'],
                    'nis' => $row[3],
                ];
            }

            // Perbarui data Mapel yang sudah ada
            DataSiswa::where('nis', $siswa['nis'])->update($data);

            $importedIds = session('imported_ids', []);
            $importedIds[] = $siswa['id'];
            session(['imported_ids' => $importedIds]);
        } else {

            if ($kelasId == null) {
                $data = [
                    'name' => $row[1],
                    'kelas_id' => null,
                    'nis' => $row[3],
                ];
            } else {
                $data = [
                    'name' => $row[1],
                    'kelas_id' => $kelasId['id'],
                    'nis' => $row[3],
                ];
            }

            // Buat Mapel baru
            $siswa = DataSiswa::create($data);

            $importedIds = session('imported_ids', []);
            $importedIds[] = $siswa['id'];
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
