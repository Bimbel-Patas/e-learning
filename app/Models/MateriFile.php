<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MateriFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'materi_id',
        'file',
    ];

    public function Materi()
    {
        return $this->belongsTo(Materi::class);
    }
}
