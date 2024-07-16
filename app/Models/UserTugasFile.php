<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTugasFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_tugas_id',
        'file',
    ];

    public function UserTugas()
    {
        return $this->belongsTo(UserTugas::class);
    }
}
