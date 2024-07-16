<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoalUjianMultiple extends Model
{
    use HasFactory;

    protected $fillable = [
        'ujian_id',
        'soal',
        'a',
        'b',
        'c',
        'd',
        'e',
        'jawaban',
    ];

    protected $guarded = [
        'id',
    ];

    public function Ujian()
    {
        return $this->belongsTo(Ujian::class);
    }

    public function UserJawaban()
    {
        return $this->hasMany(UserJawaban::class);
    }
}
