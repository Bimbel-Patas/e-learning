<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ujian extends Model
{
    use HasFactory;

    protected $fillable = [
        'kelas_mapel_id',
        'isHidden',
        'name',
        'tipe',
        'due',
        'time',
        'status',
        'jumlah_kolom',
    ];

    protected $guarded = [
        'id',
    ];

    public function KelasMapel()
    {
        return $this->belongsTo(KelasMapel::class);
    }

    public function SoalUjianMultiple()
    {
        return $this->hasMany(SoalUjianMultiple::class);
    }
    public function Ujian()
    {
        return $this->hasMany(Ujian::class);
    }

    public function SoalUjianEssay()
    {
        return $this->hasMany(SoalUjianEssay::class);
    }
    public function Kecermatan()
    {
        return $this->hasMany(Kecermatan::class);
    }

    public function UserUjian()
    {
        return $this->hasMany(UserUjian::class);
    }

    public function UserCommit()
    {
        return $this->hasMany(UserCommit::class);
    }

    public function User()
    {
        return $this->belongsTo(User::class);
    }
}
