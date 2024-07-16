<?php

namespace App\Models;

use App\Models\UserJawabanKecermatan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kecermatan extends Model
{
    use HasFactory;

    protected $fillable = [
        "id",
        "ujian_id",
        "a",
        "b",
        "c",
        "d",
        "e",
        "jumlah_soal",
    ];

    public function Ujian()
    {
        return $this->belongsTo(Ujian::class);
    }
    public function UserJawabanKecermatan()
    {
        return $this->hasMany(UserJawabanKecermatan::class);
    }
}
