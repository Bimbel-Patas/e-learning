<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $roles = ['Admin', 'Pengajar', 'Siswa'];

        foreach ($roles as $role) {
            $temp = [
                'name' => $role,
            ];
            Role::create($temp);
        }

        // $this->importAdmin();
    }

    public function importAdmin()
    {
        $data = [
            "name" => "admin",
            "roles_id" => 1,
            "email" => "admin@gmail.com",
            "password" => Hash::make("password"),
        ];
        User::create($data);
    }
}
