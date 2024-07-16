<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserJawaban extends Model
{
    use HasFactory;

    protected $fillable = [
        'multiple_id',
        'essay_id',
        'user_id',
        'user_jawaban',
        'nilai',
    ];

    public function SoalUjianMultiple()
    {
        return $this->belongsTo(SoalUjianMultiple::class);
    }

    public function SoalUjianEssay()
    {
        return $this->belongsTo(SoalUjianEssay::class);
    }
}
