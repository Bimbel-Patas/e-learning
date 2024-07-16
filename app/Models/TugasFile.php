<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TugasFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'tugas_id',
        'file',
    ];

    public function Tugas()
    {
        $this->belongsTo(UserTugas::class);
    }
}
