<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoalUjianEssay extends Model
{
    use HasFactory;

    protected $fillable = [
        'ujian_id',
        'soal',
    ];

    protected $guarded = [
        'id',
    ];

    public function UserJawaban()
    {
        return $this->hasMany(UserJawaban::class);
    }

    public function Ujian()
    {
        return $this->belongsTo(Ujian::class);
    }
}
