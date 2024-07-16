<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataSiswa extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'nis',
        'kelas_id',
        'punya_akun',
        'user_id',
    ];

    protected $guarded = [
        'id',
    ];

    public function User()
    {
        return $this->hasOne(User::class);
    }

    public function Kelas()
    {
        return $this->belongsTo(Kelas::class);
    }
}
