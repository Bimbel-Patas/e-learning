<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    protected $guarded = [
        'id',
    ];

    public function User()
    {
        return $this->hasMany(User::class);
    }

    public function DataSiswa()
    {
        return $this->hasMany(DataSiswa::class);
    }

    public function KelasMapel()
    {
        return $this->hasMany(KelasMapel::class);
    }
}
