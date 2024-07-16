<?php

namespace App\Imports;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class PengajarImport implements ToModel, WithStartRow
{
    public function model(array $row)
    {
        // Temukan pengguna berdasarkan kolom tertentu (misalnya, email)
        $user = User::where('email', $row[3])->first();

        if ($row[1] == null) {
            return null;
        }

        if ($row[2] != 'Laki-Laki' || $row[2] != 'Perempuan') {
            $row[2] = 'Laki-Laki';
        }

        if ($row[3] == null) {
            return null;
        }

        if ($user) {

            $data = [
                'name' => $row[1],
                'gender' => $row[2],
                'email' => $row[3],
                'roles_id' => 2,
                'password' => Hash::make('password778'),
            ];

            // Perbarui data pengguna yang sudah ada
            User::where('id', $user->id)->update($data);

            // Perbarui data kontak yang sesuai

            Contact::where('user_id', $user->id)->update(['no_telp' => $row[4]]);

            $userId = User::latest()->first();
            $userId = $userId['id'];

            $importedIds = session('imported_ids', []);
            $importedIds[] = $user['id'];
            session(['imported_ids' => $importedIds]);
        } else {

            $data = [
                'name' => $row[1],
                'gender' => $row[2],
                'email' => $row[3],
                'roles_id' => 2,
                'password' => Hash::make('password778'),
            ];

            // Buat pengguna baru
            $newUser = User::create($data);

            // Buat data kontak baru untuk pengguna baru
            Contact::create([
                'user_id' => $newUser->id,
                'no_telp' => $row[4],
            ]);

            $importedIds = session('imported_ids', []);
            $importedIds[] = $newUser->id;
            session(['imported_ids' => $importedIds]);
        }
    }

    /**
     * Tentukan baris pertama data dalam file Excel
     */
    public function startRow(): int
    {
        return 2; // Baris pertama berisi header, data dimulai dari baris kedua
    }
}
